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
	<h2>Index Page</h2>
	<h3>ログイン</h3>
	<p>
	{if $smarty.session.id > 0}ユーザID {$smarty.session.id} でログイン
		{if $app.user} (name={$app.user.name}, age={$app.user.age}, money={$app.user.money})
		{else} (未登録ユーザ)
		{/if}
	{else}
		以下のフォームから適当にログインしてください。
	{/if}
	</p>
	
	{form ethna_action="login"}
		ID: {form_input name="id" size="3"}
		{form_submit value="login"} (ID：1 = 成人ユーザ / ID:2 = 子供ユーザ / それ以外は未登録ユーザ)
	{/form}
	<ul>
		<li><a href="?action_bad_shop=1">バッドなショップ</a></li>
		<li><a href="?action_good_shop=1">グッドなショップ</a></li>
	</ul>
	<p><a href="?action_forge=true">[デバッグ用] 千円札をカラーコピーする</a></p>
</div>

<div id="footer">
	Powered By <a href="http://ethna.jp">Ethna</a>-{$smarty.const.ETHNA_VERSION}.
</div>

</body>
</html>
