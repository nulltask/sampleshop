<?php

class Sampleshop_Form_Login extends Sampleshop_ActionForm
{
	var $form = array(
		'id'	=> array(
			'type'		=> VAR_TYPE_INT,
			'form_type'	=> FORM_TYPE_TEXT,
			'required'	=> true,
		),
	);
}

class Sampleshop_Action_Login extends Sampleshop_ActionClass
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
		// サンプルのための嘘っぱちログインシステム
		
		$this->session->start();
		$user_id = $this->af->get('id');
		$this->session->set('id', $user_id);
		
		return 'index';
	}
}
