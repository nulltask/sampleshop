<?php

class Sampleshop_Cli_Action_ImportItem extends Ethna_ActionClass
{
	function perform()
	{
		$item_manager = $this->backend->getManager('Item');
		
		$item_manager->db->begin();
		{
			$import_result = $item_manager->import(BASE . '/schema/item.csv');
			if (Ethna::isError($import_result))
			{
				return null;
			}
		}
		$item_manager->db->commit();
		
		return null;
	}
}
