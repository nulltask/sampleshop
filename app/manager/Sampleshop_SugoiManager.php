<?php

class Sampleshop_SugoiManager extends Ethna_AppManager
{
	/**
	 * ログイン中の User オブジェクトを取得します。
	 * 
	 * @return Sampleshop_User
	 */
	public function getLoginUserObject()
	{
		$this->session->start();
		
		$user_object = $this->backend->getObject('User', 'id', $this->session->get('id'));
		
		if (!$user_object->isValid())
		{
			return Ethna::raiseNotice('むむ！曲者め！未登録ユーザだな！');
		}
		
		return $user_object;
	}
	
	/**
	 * 購入遷移時に購入可能かチェックします。
	 * オブジェクトの妥当性もあわせてチェックします。
	 * 
	 * @param Sampleshop_User $user_object
	 * @param Sampleshop_Item $item_object
	 * @param int $item_count
	 * @return boolean
	 */
	public function validateShop(Sampleshop_User $user_object, Sampleshop_Item $item_object, $item_count)
	{
		if (!$user_object->isValid())
		{
			return Ethna::raiseNotice('この User オブジェクトはだめだな！');
		}
		if (!$item_object->isValid())
		{
			return Ethna::raiseNotice('この Item オブジェクトはダメだな！');
		}
		
		return $this->_validateShop($user_object, $item_object, $item_count);
	}
	
	/**
	 * 購入遷移時に購入可能かチェックします。
	 * オブジェクトの妥当性はチェックしません。 (ですのでコントローラからは呼び出さないでください。ダメゼッタイ！)
	 * 
	 * @param Sampleshop_User $user_object
	 * @param Sampleshop_Item $item_object
	 * @param int $item_count
	 * @return boolean
	 */
	public function _validateShop(Sampleshop_User $user_object, Sampleshop_Item $item_object, $item_count)
	{
		$money_available_result = $user_object->isMoneyAvailable($item_object->getCost($item_count));
		if (Ethna::isError($money_available_result))
		{
			return $money_available_result;
		}
		
		switch ($item_object->get('type'))
		{
			case ITEM_TYPE_BEER:
				return $this->_validateShopBeer($user_object, $item_object);
			case ITEM_TYPE_SNACK:
				return $this->_validateShopSnack($user_object, $item_object, $item_count);
			default:
				return Ethna::raiseNotice('未知のアイテムタイプです。指定間違ってない？確認すべき。');
		}
	}
	
	/**
	 * ビールの購入が可能かチェックします。
	 * 
	 * @param Sampleshop_User $user_object
	 * @param Sampleshop_Item $item_object
	 * @return boolean
	 */
	public function _validateShopBeer(Sampleshop_User $user_object, Sampleshop_Item $item_object)
	{
		if (!$user_object->isAdult())
		{
			return Ethna::raiseNotice('ビールはハタチになってから！');
		}
		
		return true;
	}
	
	/**
	 * お菓子の購入が可能かチェックします。
	 * 
	 * @param Sampleshop_User $user_object
	 * @param Sampleshop_Item $item_object
	 * @param int $item_count
	 * @return boolean
	 */
	public function _validateShopSnack(Sampleshop_User $user_object, Sampleshop_Item $item_object, $item_count)
	{
		$user_item_manager = $this->backend->getManager('UserItem');
		$current_snack_count = $user_item_manager->getSnackCountByUserId($user_object->get('id'));
		
		if (Ethna::isError($current_snack_count))
		{
			return $current_snack_count;
		}
		
		$total_snack_count = $current_snack_count + $item_count;
		
		if ($total_snack_count > 10)
		{
			return Ethna::raiseNotice('お菓子は10個までだよ。');
		}
		
		return true;
	}
	
	/**
	 * 購入遷移を完了します。
	 * コントローラから呼び出す場合は、ぜってー validateShop() で購入可能かチェックすること！
	 * あとトランザクション張るべき。
	 * 
	 * @param Sampleshop_User $user_object
	 * @param Sampleshop_Item $item_object
	 * @param int $item_count
	 * @param boolean $update
	 * @return Sampleshop_UserItem
	 */
	public function completeShop(Sampleshop_User $user_object, Sampleshop_Item $item_object, $item_count, $update = false)
	{
		// お金を減らす
		$cost = $item_object->getCost($item_count);
		$decrease_result = $user_object->decreaseMoney($cost);
		if (Ethna::isError($decrease_result))
		{
			return $decrease_result;
		}
		
		// ストックに追加
		$user_item_manager = $this->backend->getManager('UserItem');
		$user_item_object = $user_item_manager->create(array(
			'user_id'	=> $user_object->get('id'),
			'item_id'	=> $item_object->get('id'),
		));
		
		if ($update)
		{
			$user_object_update_result = $user_object->update();
			if (Ethna::isError($user_object_update_result)) return $user_object_update_result;
			$item_object_update_result = $item_object->update();
			if (Ethna::isError($item_object_update_result)) return $item_object_update_result;
			$user_item_object_add_result = $user_item_object->add();
			if (Ethna::isError($user_item_object_add_result)) return $user_item_object_add_result;
		}
		
		return $user_item_object;
	}
}