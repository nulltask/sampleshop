<?php

class Sampleshop_UserManager extends Ethna_AppManager
{
	/**
	 * 連想配列をもとに User オブジェクトを生成します。
	 * 
	 * @param array $prop
	 * @param boolean $add
	 * @return Sampleshop_UserItem
	 */
	public function create($prop, $add = false)
	{
		$user_object = $this->backend->getObject('User');
		foreach ($prop as $key => $value)
		{
			$user_object->set($key, $value);
		}
		
		if ($add)
		{
			$user_object_add_result = $user_object->add();
			if (Ethna::isError($user_object_add_result))
			{
				return $user_object_add_result;
			}
		}
		
		return $user_object;
	}
}

class Sampleshop_User extends Ethna_AppObject
{
	function getName($key)
	{
		return $this->get($key);
	}
	
	/**
	 * ユーザが成人か取得します。
	 * 
	 * @return boolean
	 */
	public function isAdult()
	{
		return ($this->get('age') >= 20);
	}
	
	/**
	 * ユーザの所持金を減らします。
	 * 指定した額より所持金が少ない場合は Ethna_Error を返します。
	 * 
	 * @param int $value
	 * @param boolean $update
	 */
	public function decreaseMoney($value, $update = false)
	{
		$money_available_result = $this->isMoneyAvailable($value);
		if (Ethna::isError($money_available_result))
		{
			return $money_available_result;
		}
		
		$this->set('money', $money_available_result - $value);
		
		if ($update)
		{
			return $this->update();
		}
	}
	
	public function increaseMoney($value, $update = false)
	{
		$current_money = $this->get('money');
		$this->set('money', $current_money + $value);
		
		if ($update)
		{
			return $this->update();
		}
		
		return null;
	}
	
	/**
	 * ユーザの支払能力をチェックします
	 * 
	 * @param int $value
	 * @return int
	 */
	public function isMoneyAvailable($value = 0)
	{
		$current_money = $this->get('money');
		
		if ($current_money < $value)
		{
			return Ethna::raiseNotice('そんなお金ないです。');
		}
		
		return $current_money;
	}
}
