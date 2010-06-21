<?php

class Sampleshop_Form_Forge extends Sampleshop_ActionForm
{
	var $form = array(
	);
}

class Sampleshop_Action_Forge extends Sampleshop_ActionClass
{
	function prepare()
	{
		return null;
	}

	function perform()
	{
		$sugoi_manager = $this->backend->getManager('Sugoi');
		$login_user_object = $sugoi_manager->getLoginUserObject();
		if (Ethna::isError($login_user_object))
		{
			$this->ae->addObject(null, $login_user_object);
			return 'error';
		}
		$increase_result = $login_user_object->increaseMoney(1000, true);
		if (Ethna::isError($increase_result))
		{
			$this->ae->addObject(null, $increase_result);
			return 'error';
		}
		
		return 'index';
	}
}