<?php

class Sampleshop_Form_BadShopConfirm extends Sampleshop_ActionForm
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

class Sampleshop_Action_BadShopConfirm extends Sampleshop_ActionClass
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
		$item_count = $this->af->get('count');
		
		$this->session->start();	// omajinai
		$user_object = $this->backend->getObject('User', 'id', $this->session->get('id'));
		if (!$user_object->isValid())
		{
			$this->ae->add(null, '会員になってね');
			return 'error';
		}
		
		$item_object = $this->backend->getObject('Item', 'id', $item_id);
		if (!$item_object->isValid())
		{
			$this->ae->add(null, 'おまー！ URL のパラメータ書き換えたろ！');
			return 'error';
		}
		
		// お金はあるかね
		$amount = $item_object->get('price') * $item_count;
		if ($user_object->get('money') < $amount)
		{
			$this->ae->add(null, 'お金ないんでしょ。冷やかしはやめてね。');
			return 'error';
		}
		
		// ネンレーカクニンガヒツヨーナショーヒンデス
		$user_age = $user_object->get('age');
		if ($item_object->get('type') == ITEM_TYPE_BEER && $user_age < 20)
		{
			$this->ae->add(null, 'ビールはハタチになってから！');
			return 'error';
		}
		
		// 食いしん坊はダメっすよ
		if ($item_object->get('type') == ITEM_TYPE_SNACK)
		{
			$item_manager = $this->backend->getManager('Item');
			$snack_item_id_list = $item_manager->getItemIdListByType(ITEM_TYPE_SNACK);
			// ↑ これ Action 側に展開すると解説の腰をへし折ってしまうレベルでバッドになるのでやめました。
			if (Ethna::isError($snack_item_id_list))
			{
				$this->ae->addObject(null, $snack_item_id_list);
				return 'error';
			}
			$user_item_manager = $this->backend->getManager('UserItem');
			$filter = array('item_id' => new Ethna_AppSearchObject($snack_item_id_list, OBJECT_CONDITION_EQ));
			$filter['item_id']->addObject('user_id', new Ethna_AppSearchObject($user_object->get('id'), OBJECT_CONDITION_EQ), OBJECT_CONDITION_AND);
			$snack_stock_result = $user_item_manager->getObjectList('UserItem', $filter, null, 0, 0);
			if (Ethna::isError($snack_stock_result))
			{
				$this->ae->addObject(null, $snack_stock_result);
				return 'error';
			}
			
			$snack_stock_count = $item_count + $snack_stock_result[0];
			if ($snack_stock_count > 10)
			{
				$this->ae->add(null, 'お菓子はおひとりさま十個限り！');
				return 'error';
			}
		}
		
		$this->af->setApp('item', $item_object->getNameObject());
		$this->af->setApp('user', $user_object->getNameObject());
		$this->af->setApp('cost', $amount);
		
		return 'bad_shop_confirm';
	}
}

class Sampleshop_View_BadShopConfirm extends Sampleshop_ViewClass
{
	function preforward()
	{
		// 別の Action から Forward する可能性は無いと思うので
		// setApp を無理して View でやんなくてもおｋ
	}
}
