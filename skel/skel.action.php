<?php

class {$action_form} extends Sampleshop_ActionForm
{
	var $form = array(
	);
}

class {$action_class} extends Sampleshop_ActionClass
{
	function prepare()
	{
		return null;
	}

	function perform()
	{
		return '{$action_name}';
	}
}

class {$view_class} extends Sampleshop_ViewClass
{
	function preforward()
	{
	}
}
