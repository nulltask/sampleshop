<?php

class {$action_form}_TestCase extends Ethna_UnitTestCase
{
	var $action_name = '{$action_name}';

	function setUp()
	{
		$this->createActionForm();  // create ActionForm.
	}

	function tearDown()
	{
	}

	function test_formSample()
	{
		$this->fail('No Test! write Test!');
	}
}

class {$action_class}_TestCase extends Ethna_UnitTestCase
{
	var $action_name = '{$action_name}';

	function setUp()
	{
		$this->createActionForm();  // create ActionForm.
		$this->createActionClass(); // create ActionClass.

		$this->session->start();	// start session.
	}

	function tearDown()
	{
		$this->session->destroy();   // destroy session.
	}

	function test_actionSample()
	{
		$this->fail('No Test! write Test!');
	}
}
