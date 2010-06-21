<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" href="{$config.url}css/ethna.css" type="text/css" />
</head>
<body>

<div id="header">
	<h1>Sampleshop</h1>
</div>

<div id="main">
	<h2>グッドなショップ</h2>
	<p>こんにちは{$app.user.name|default:'ゲスト'}さん{if $app.user} (所持金: &yen;{$app.user.money|number_format}){/if}</p>
	{if count($error)}
		<p>アイテムの取得に失敗しました。</p>
	{else}
		<table>
		{foreach from=$app.item_list item=item}
			<tr>
				<th>{$item.name}</th>
				<td>&yen;{$item.price|number_format} ({$item.type})</td>
				<td>
					{form ethna_action="good_shop_confirm"}
						{form_input name="id" value=`$item.id`}
						{form_input name="count"}個
						{form_submit value="購入"}
					{/form}
				</td>
			</tr>
		{foreachelse}
			<tr><td>ごめん品切れ</td></tr>
		{/foreach}
		</table>
	{/if}
	<p><a href="?action_index=true">トップページ</a></p>
</div>

<div id="footer">
	Powered By <a href="http://ethna.jp">Ethna</a>-{$smarty.const.ETHNA_VERSION}.
</div>

</body>
</html>
