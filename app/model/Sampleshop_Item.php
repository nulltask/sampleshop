<?php

class Sampleshop_ItemManager extends Ethna_AppManager
{
	/**
	 * 連想配列をもとに Item オブジェクトを生成します。
	 * 
	 * @param array $prop
	 * @param boolean $add
	 * @return Sampleshop_UserItem
	 */
	public function create($prop, $add = false)
	{
		$item_object = $this->backend->getObject('Item');
		foreach ($prop as $key => $value)
		{
			$item_object->set($key, $value);
		}
		
		if ($add)
		{
			$item_object_add_result = $item_object->add();
			if (Ethna::isError($item_object_add_result))
			{
				return $item_object_add_result;
			}
		}
		
		return $user_object;
	}
	
	/**
	 * お店に陳列されるアイテムの一覧を取得します。
	 * デフォルトで価格順にソートされます。
	 * 
	 * @param array $filter
	 * @param array $order
	 * @param int $offset
	 * @param int $count
	 * @return array
	 */
	public function getShopItemObjectList($filter = array(), $order = array(), $offset = 0, $count = 10)
	{
		$filter += array('price' => new Ethna_AppSearchObject(0, OBJECT_CONDITION_GT));
		$order += array('price' => OBJECT_SORT_DESC);
		
		return $this->getObjectList('Item', $filter, $order, $offset, $count);
	}
	
	/**
	 * 指定した type のアイテム ID のリストを取得します。
	 *  
	 * @param int $type
	 * @return array
	 */
	public function getItemIdListByType($type)
	{
		$filter = array();
		$filter['type'] = new Ethna_AppSearchObject($type, OBJECT_CONDITION_EQ);
		
		$result = $this->getObjectPropList('Item', array('id'), $filter, null, null, null);
		if (Ethna::isError($result))	// ↑ Item オブジェクトのメソッドを使う必要性が全くないので連想配列で取得
		{
			return $result;
		}
		
		$id_list = array();
		foreach ($result[1] as $prop_list)
		{
			$id_list[] = $prop_list['id'];	// 連想配列から普通の配列に変換
		}
		
		return $id_list;
	}
	
	/**
	 * CSV をロードして item テーブルにデータを挿入します
	 * 
	 * @param string $csv_filename
	 * @return void
	 */
	public function import($csv_filename)
	{
		$adle = ini_get("auto_detect_line_endings");
		ini_set("auto_detect_line_endings", "1");

		$fp = fopen($csv_filename, 'r');
		$data = fgetcsv($fp, 0);	// 先頭行はヘッダなので捨てる
		while (($data = fgetcsv($fp, 0)) !== false)
		{
			$n = $data[0];
			$o = $this->backend->getObject('Item', 'id', $n);

			$o->set('id',		$data[0]);
			$o->set('type',		$data[1]);
			$o->set('name',		$data[2]);
			$o->set('price',	$data[3]);
			
			$replace_result = $o->replace();
			if (Ethna::isError($replace_result))
			{
				return $replace_result;
			}
		}

		ini_set("auto_detect_line_endings", $adle);
	}
}

class Sampleshop_Item extends Ethna_AppObject
{
	var $item_type_mapper = array(
		ITEM_TYPE_BEER	=> 'ビール',
		ITEM_TYPE_SNACK	=> 'お菓子',
	);
	
	function getName($key)
	{
		if ($key == 'type')
		{
			return $this->item_type_mapper[$this->get($key)];
		}
		
		return $this->get($key);
	}
	
	/**
	 * アイテムの金額を取得します
	 * 
	 * @param int $count
	 * @return int
	 */
	public function getCost($count = 1)
	{
		return ($this->get('price') * $count);
	}
}
