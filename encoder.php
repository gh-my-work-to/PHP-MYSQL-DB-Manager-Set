<?php
$me = $_SERVER['PHP_SELF'];

$word = $_POST['word'];
$encoded = "";

$ary = explode("\n", $word);
foreach ($ary as $v)
{
	$v = urlencode(trim($v));
	if($v == null)
	{
		continue;
	}
	$encoded .= $v."\n";
}
unset($ary);

echo <<< EOF
<!doctype html>
<html lang="ja">
<head>
	<meta charset="UTF-8" />
	<title>encoder</title>
	<style type="text/css">.box{ float:left; }</style>
</head>
<body>
	<form action="$me" method="post">
		<div class="box">
			before encode:<br />
			<textarea name="word" cols="30" rows="30">$word</textarea>
		</div>
		<div class="box">
			after encode:<br />
			<textarea cols="60" rows="30">$encoded</textarea>
		</div>
		<br clear="both" />
		<input type="submit" />
	</form>
</body>
</html>
EOF;
?>