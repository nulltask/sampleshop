<?php

class Sampleshop_Form_BadShop extends Sampleshop_ActionForm
{
	var $form = array(
	);
}

class Sampleshop_Action_BadShop extends Sampleshop_ActionClass
{
	function prepare()
	{
		return null;
	}

	function perform()
	{
		return 'bad_shop';
	}
}

class Sampleshop_View_BadShop extends Sampleshop_ViewClass
{
	function preforward()
	{
		$item_manager = $this->backend->getManager('Item');
		
		$filter = array('price' => new Ethna_AppSearchObject(0, OBJECT_CONDITION_GE));
		$order = array('price' => OBJECT_SORT_DESC);
		$offset = 0;
		$count = 10;
		
		$object_list_result = $item_manager->getObjectList('Item', $filter, $order, $offset, $count);
		if (Ethna::isError($object_list_result))
		{
			$this->ae->addObject(null, $object_list_result);
			return;
		}
		
		$item_list = array();
		foreach ($object_list_result[1] as $item_object)
		{
			if ($item_object->get('type') == ITEM_TYPE_BEER)
			{
				$type_name = 'ビール';
			}
			else if ($item_object->get('type') == ITEM_TYPE_SNACK)
			{
				$type_name = 'お菓子';
			}
			
			$item_tmp = $item_object->getNameObject();
			$item_tmp['type_name'] = $type_name;
			
			$item_list[] = $item_tmp;
		}
		$this->af->setApp('item_list', $item_list);
		
		$this->session->start();	// omajinai
		$user_object = $this->backend->getObject('User', 'id', $this->session->get('id'));
		if ($user_object->isValid())
		{
			$this->af->setApp('user', $user_object->getNameObject());
		}
	}
}
