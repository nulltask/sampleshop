<?php

class Sampleshop_Cli_Action_Test extends Ethna_ActionClass
{
	function authenticate()
	{
		return null;
	}
	
	function prepare()
	{
		return null;
	}
	
	function perform()
	{

		$this->testUser();	// ==== 原始的な単体テストもどき
		$this->testShop();	// ==== 原始的な機能テストもどき
		return null;
	}
	
	function testUser()
	{
		// テストケース用のダミーのオブジェクトを生成する
		$user_object = $this->backend->getObject('User');
		
		// ==== 19 歳は成人？
		$user_object->set('age', 19);
		if ($user_object->isAdult() === false)	// ちがいまーす
		{
			$this->backend->log(LOG_INFO, '**** OK: case 1 passed');
		}
		else
		{
			$this->backend->log(LOG_INFO, '---- NG: case 1 failed');
		}
		
		// ==== 20 歳は成人？
		$user_object->set('age', 20);
		if ($user_object->isAdult() === true)	// そうでーす
		{
			$this->backend->log(LOG_INFO, '**** OK: case 2-1 passed');
		}
		else
		{
			$this->backend->log(LOG_INFO, '---- NG: case 2-1 failed');
		}
		
		$user_object->set('age', 50);
		if ($user_object->isAdult() === false)	// ケースが間違っている場合
		{
			$this->backend->log(LOG_INFO, '**** OK: case 2-2 passed');
		}
		else
		{
			$this->backend->log(LOG_INFO, '---- NG: case 2-2 failed');
		}
		
		// 100 円もたせます (isMoneyAvailable は引数以上のお金を持っていれば所持金を返す)
		$user_object->set('money', 100);
		if ($user_object->isMoneyAvailable(99) === 100)	// 99 円もってる？
		{
			$this->backend->log(LOG_INFO, '**** OK: case 3 passed');
		}
		else
		{
			$this->backend->log(LOG_INFO, '---- NG: case 3 failed');
		}
		if ($user_object->isMoneyAvailable(100) === 100)	// 100 円持ってる？
		{
			$this->backend->log(LOG_INFO, '**** OK: case 4 passed');
		}
		else
		{
			$this->backend->log(LOG_INFO, '---- NG: case 4 failed');
		}
		if ($user_object->isMoneyAvailable(101) instanceof Ethna_Error)	// 101 円はないので Ethna_Error のはず
		{
			$this->backend->log(LOG_INFO, '**** OK: case 5 passed');
		}
		else
		{
			$this->backend->log(LOG_INFO, '---- NG: case 5 failed');
		}
		
		// このへんにしとっか！
		$user_object->_clearPropCache();
		unset($user_object);
	}
	
	function testShop()
	{
		
		
		// じゅんび。
		
		$baby = $this->backend->getObject('User');
		$baby->set('name', '赤ちゃん');
		$baby->set('age', 2);
		$baby->set('money', 0);
		$baby->add();
		
		$student = $this->backend->getObject('User');
		$student->set('name', '学生');
		$student->set('age', 13);
		$student->set('money', 500);
		$student->add();
		
		$president = $this->backend->getObject('User');
		$president->set('name', 'しゃちょう');
		$president->set('age', 60);
		$president->set('money', 100000);
		$president->add();
		
		$beer = $this->backend->getObject('Item');
		$beer->set('type', ITEM_TYPE_BEER);
		$beer->set('name', '空想上のビール');
		$beer->set('price', 300);	// あえて add しない
		
		$snack = $this->backend->getObject('Item');
		$snack->set('type', ITEM_TYPE_SNACK);
		$snack->set('name', '空想上のお菓子');
		$snack->set('price', 5);
		$snack->add();
		
		// student にお菓子を 5 個持たせる
		$user_item_manager = $this->backend->getManager('UserItem');
		$user_item_object_list = array();
		for ($i = 0; $i < 5; $i++)
		{
			$user_item_object[] = $user_item_manager->create(array(
				'user_id'	=> $student->get('id'),
				'item_id'	=> $snack->get('id'),
			), true);
		}
		
		// てすとだよ。
		$sugoi_manager = $this->backend->getManager('Sugoi');
		
		$ret1 = $sugoi_manager->_validateShop($president, $beer, 10);
		if ($ret1 === true)	// しゃちょうなのでビール買えます
		{
			$this->backend->log(LOG_INFO, '**** OK: case 1 passed');
		}
		else
		{
			$this->backend->log(LOG_INFO, '---- NG: case 1 failed');
		}
		
		$ret2 = $sugoi_manager->_validateShop($student, $beer, 1);
		if ($ret2 instanceof Ethna_Error)	// ネンレーカクニンガヒツヨーナショーヒンデス
		{
			$this->backend->log(LOG_INFO, '**** OK: case 2 passed');
		}
		else
		{
			$this->backend->log(LOG_INFO, '---- NG: case 2 failed');
		}
		
		$ret3 = $sugoi_manager->_validateShop($president, $beer, 3000000);
		if ($ret3 instanceof Ethna_Error)	// しゃちょうとはいえ、こんなには買えない
		{
			$this->backend->log(LOG_INFO, '**** OK: case 3 passed');
		}
		else
		{
			$this->backend->log(LOG_INFO, '---- NG: case 3 failed');
		}
		
		
		$ret4 = $sugoi_manager->_validateShop($baby, $snack, 1);
		if ($ret4 instanceof Ethna_Error)	// 赤ちゃんお金持ってないのでだめ
		{
			$this->backend->log(LOG_INFO, '**** OK: case 4 passed');
		}
		else
		{
			$this->backend->log(LOG_INFO, '---- NG: case 4 failed');
		}
		
		$ret5 = $sugoi_manager->_validateShop($student, $snack, 5);
		if ($ret5 === true)	// ギリギリ 10 個以内なのでオッケー
		{
			$this->backend->log(LOG_INFO, '**** OK: case 5 passed');
		}
		else
		{
			$this->backend->log(LOG_INFO, '---- NG: case 5 failed');
		}
		
		$ret6 = $sugoi_manager->_validateShop($student, $snack, 6);
		if ($ret6 instanceof Ethna_Error)	// くいしんぼめ！
		{
			$this->backend->log(LOG_INFO, '**** OK: case 6 passed');
		}
		else
		{
			$this->backend->log(LOG_INFO, '---- NG: case 6 failed');
		}
		
		// ======== まだ使っていないオブジェクトいっぱいあるけどこの辺でお開き。
		
		// ボッシュート
		foreach ($user_item_object_list as $user_item_object)
		{
			$user_item_object->remove();
		}
		
		// さいなら〜
		$baby->remove();
		$student->remove();
		$president->remove();
		
		// beer は add してないので snack だけ
		$snack->remove();
	}
}
