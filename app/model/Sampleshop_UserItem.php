<?php

class Sampleshop_UserItemManager extends Ethna_AppManager
{
	/**
	 * 連想配列をもとに UserItem オブジェクトを生成します。
	 * 
	 * @param array $prop
	 * @param boolean $add
	 * @return Sampleshop_UserItem
	 */
	public function create($prop, $add = false)
	{
		$user_item_object = $this->backend->getObject('UserItem');
		foreach ($prop as $key => $value)
		{
			$user_item_object->set($key, $value);
		}
		
		if ($add)
		{
			$user_item_object_add_result = $user_item_object->add();
			if (Ethna::isError($user_item_object_add_result))
			{
				return $user_item_object_add_result;
			}
		}
		
		return $user_item_object;
	}
	
	/**
	 * 指定したユーザ ID のお菓子の所持数を取得します。
	 * ユーザが既にレコードに登録されてるかはチェックしません。 (上位でやってねってこと)
	 * 
	 * @param int $user_id
	 * @return int
	 */
	public function getSnackCountByUserId($user_id)
	{
		$item_manager = $this->backend->getManager('Item');
		// はいここ注目！マネージャの中でマネージャを呼び出すなんてどうなの？と思う人がいるはず。
		// でもさあー、 UserItem って Item テーブル無しには生きていけない (Item テーブルの外部キーがある) ので別にいいんじゃねって結論。
		// 逆に考えれば、 Item マネージャの中で UserItemManager を呼び出すのはご法度だと思います。
		$snack_item_id_list = $item_manager->getItemIdListByType(ITEM_TYPE_SNACK);
		if (Ethna::isError($snack_item_id_list))
		{
			return $snack_item_id_list;
		}
		
		$filter = array();
		$filter['user_id'] = new Ethna_AppSearchObject($user_id, OBJECT_CONDITION_EQ);
		$filter['user_id']->addObject(
			'item_id', new Ethna_AppSearchObject($snack_item_id_list, OBJECT_CONDITION_EQ), OBJECT_CONDITION_AND);
		
		$result = $this->getObjectPropList('UserItem', null, $filter, null, 0, 0);
		if (Ethna::isError($result))	// ↑ 純粋にトータルだけ欲しいので count も offset もゼロです。
		{
			return $result;
		}
		
		return $result[0];
	}
}

class Sampleshop_UserItem extends Ethna_AppObject
{
	function getName($key)
	{
		return $this->get($key);
	}
}
