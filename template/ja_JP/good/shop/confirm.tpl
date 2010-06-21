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
	<h2>確認</h2>
	<p>以下の内容でよろしいですか？</p>
	<table>
		<tbody>
			<tr>
				<th>商品名</th>
				<td>{$app.item.name}</td>
			</tr>
			<tr>
				<th>個数</th>
				<td>{$form.count}個</td>
			</tr>
			<tr>
				<th>合計金額</th>
				<td>{$app.cost}</td>
			</tr>
			<tr>
				<th>所持金</th>
				<td>&yen;{$app.user.money}</td>
			</tr>
		</tbody>
	</table>
	
	{form ethna_action="good_shop_complete"}
		{form_input name="id"}
		{form_input name="count"}
		{form_submit value="はい"}&nbsp;<input type="button" onclick="javascript:history.back();" value="やっぱうそ" />
	{/form}
</div>

<div id="footer">
	Powered By <a href="http://ethna.jp">Ethna</a>-{$smarty.const.ETHNA_VERSION}.
</div>

</body>
</html>
