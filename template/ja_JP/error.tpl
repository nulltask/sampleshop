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
	<h2>エラーだよ</h2>
	{if count($errors)}
		<ul>
			{foreach from=$errors item=error}
				<li>{$error}</li>
			{/foreach}
		</ul>
	{/if}
	<p><a href="?action_index=true">戻る</a></p>
</div>

<div id="footer">
	Powered By <a href="http://ethna.jp">Ethna</a>-{$smarty.const.ETHNA_VERSION}.
</div>

</body>
</html>
