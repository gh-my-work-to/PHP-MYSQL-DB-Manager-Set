<?php
function getShortHash($data, $algo = 'CRC32')
{
	return strtr(rtrim(base64_encode(
			pack('H*', sprintf('%u', $algo($data)))), '='), '+/', '-_');
}
/*
PHPで短いハッシュ - Qiita
qiita.com/koriym/items/efc1c419e4b7772b65c0
*/
?>

<?php
$me = $_SERVER['PHP_SELF'];

$word = $_POST['word'];
$encoded = "";

$ary = explode("\n", $word);
foreach ($ary as $v)
{
	$v = trim($v);
	$vha = getShortHash($v);
	
	$vha = strrev($vha);
	while(strlen($vha) < 8)
	{
		$vha = "$vha"."0";
	}
	
	if($vha == null)
	{
		continue;
	}
	$encoded .= $vha.",$v\n";
}
unset($ary);

echo <<< EOF
<!doctype html>
<html lang="ja">
<head>
	<meta charset="UTF-8" />
	<title>short-hash</title>
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
