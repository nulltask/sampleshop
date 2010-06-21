<?php

class Sampleshop_Form_GoodShop extends Sampleshop_ActionForm
{
	var $form = array(
	);
}

class Sampleshop_Action_GoodShop extends Sampleshop_ActionClass
{
	function prepare()
	{
		return null;
	}

	function perform()
	{
		return 'good_shop';
	}
}

class Sampleshop_View_GoodShop extends Sampleshop_ViewClass
{
	function preforward()
	{
		$item_manager = $this->backend->getManager('Item');
		$item_object_list = $item_manager->getShopItemObjectList();
		if (Ethna::isError($item_object_list))
		{
			$this->ae->addObject(null, $item_object_list);
			return;
		}
		
		$item_name_object_list = array();
		foreach ($item_object_list[1] as $item_object)
		{
			$item_name_object_list[] = $item_object->getNameObject();
		}
		$this->af->setApp('item_list', $item_name_object_list);
		
		$sugoi_manager = $this->backend->getManager('Sugoi');
		$login_user_object = $sugoi_manager->getLoginUserObject();
		if (!Ethna::isError($login_user_object))
		{
			$this->af->setApp('user', $login_user_object->getNameObject());
		}
	}
}
