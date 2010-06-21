<?php

class Sampleshop_Form_Index extends Sampleshop_ActionForm
{
	var $form = array(
	);
}

class Sampleshop_Action_Index extends Sampleshop_ActionClass
{
	function prepare()
	{
		return null;
	}

	function perform()
	{
		return 'index';
	}
}

class Sampleshop_View_Index extends Sampleshop_ViewClass
{
	function preforward()
	{
		$sugoi_manager = $this->backend->getManager('Sugoi');
		$login_user_object = $sugoi_manager->getLoginUserObject();
		if (!Ethna::isError($login_user_object))
		{
			$this->af->setApp('user', $login_user_object->getNameObject());
		}
	}
}
