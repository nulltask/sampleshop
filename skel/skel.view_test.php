<?php

class {$view_class}_TestCase extends Ethna_UnitTestCase
{
	var $forward_name = '{$forward_name}';

	function setUp()
	{
		$this->createPlainActionForm(); // create ActionForm
		$this->createViewClass();	   // create View.
	}

	function tearDown()
	{
	}

	function test_viewSample()
	{
		$this->fail('No Test! write Test!');
	}
}
