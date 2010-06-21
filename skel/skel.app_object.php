<?php

class {$app_object}Manager extends Ethna_AppManager
{
}

class {$app_object} extends Ethna_AppObject
{
	function getName($key)
	{
		return $this->get($key);
	}
}
