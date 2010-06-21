<?php

class Sampleshop_Form_GoodShopConfirm extends Sampleshop_ActionForm
{
	var $form = array(
		'id'	=> array(
			'type'		=> VAR_TYPE_INT,
			'form_type'	=> FORM_TYPE_HIDDEN,
			'required'	=> true,
		),
		'count'	=> array(
			'type'		=> VAR_TYPE_INT,
			'form_type'	=> FORM_TYPE_SELECT,
			'required'	=> true,
			'option'	=> array(1 => 1, 2, 3, 4, 5, 6, 7, 8, 9, 10),
		),
	);
}

class Sampleshop_Action_GoodShopConfirm extends Sampleshop_ActionClass
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
		
		// ジャーマネさん、頼みましたぜ
		$validate_result = $sugoi_manager->validateShop($login_user_object, $item_object, $item_count);
		if (Ethna::isError($validate_result))
		{
			$this->ae->addObject(null, $validate_result);
			return 'error';
		}
		
		$this->af->setApp('user', $login_user_object->getNameObject());
		$this->af->setApp('item', $item_object->getNameObject());
		$this->af->setApp('cost', $item_object->getCost($item_count));
		
		return 'good_shop_confirm';
	}
}

class Sampleshop_View_GoodShopConfirm extends Sampleshop_ViewClass
{
	function preforward()
	{
	}
}
