<?php

class Sampleshop_Form_GoodShopComplete extends Sampleshop_ActionForm
{
	var $form = array(
		'id'	=> array(
			'type'		=> VAR_TYPE_INT,
			'form_type'	=> FORM_TYPE_HIDDEN,
			'required'	=> true,
		),
		'count'	=> array(
			'type'		=> VAR_TYPE_INT,
			'form_type'	=> FORM_TYPE_HIDDEN,
			'required'	=> true,
			'option'	=> array(1 => 1, 2, 3, 4, 5, 6, 7, 8, 9, 10),
		),
	);
}

class Sampleshop_Action_GoodShopComplete extends Sampleshop_ActionClass
{
	function prepare()
	{
		if ($this->af->validate() > 0)
		{
			return 'error';
		}
		
		return null;
	}

	function perform()
	{
		$item_id = $this->af->get('id');
		$item_object = $this->backend->getObject('Item', 'id', $item_id);
		$item_count = $this->af->get('count');
		
		$sugoi_manager = $this->backend->getManager('Sugoi');
		$login_user_object = $sugoi_manager->getLoginUserObject();	// 本当はベースクラスの authenticate() ですべき
		if (Ethna::isError($login_user_object))
		{
			$this->ae->addObject(null, $login_user_object);
			return 'error';
		}
		
		// Confirm でもやってるけど、ずるするやついるので
		$validate_result = $sugoi_manager->validateShop($login_user_object, $item_object, $item_count);
		if (Ethna::isError($validate_result))
		{
			$this->ae->addObject(null, $validate_result);
			return 'error';
		}
		
		$sugoi_manager->db->begin();
		{	// ここのインデントは本当はいらないです。が、個人的にトランザクション中であることが分かりやすいようにブロックで囲みます。
			$update_result = $sugoi_manager->completeShop($login_user_object, $item_object, $item_count, true);
			if (Ethna::isError($update_result))
			{
				$this->ae->addObject(null, $update_result);
				return 'error';	// エラーしたらここで perform() が終了するので commit() は呼び出されない。めでたし。
			}
		}
		$sugoi_manager->db->commit();
		
		$this->af->setApp('user', $login_user_object->getNameObject());
		$this->af->setApp('item', $item_object->getNameObject());
		$this->af->setApp('cost', $item_object->getCost($item_count));
		
		return 'good_shop_complete';
	}
}

class Sampleshop_View_GoodShopComplete extends Sampleshop_ViewClass
{
	function preforward()
	{
	}
}
