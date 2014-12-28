<?php
$me = $_SERVER['PHP_SELF'];

$input = $_POST['input'];
$word_from = $_POST['word_from'];
$word_to = $_POST['word_to'];
$output = "";

if($input != null && $word_from != null && $word_to != null)
{
	doThat();
}

function doThat()
{
	global $input, $output;
	global $word_from, $word_to;

	$ary_f = explode("\r\n", $word_from);
	$ary_t = explode("\r\n", $word_to);
	$ary_input = explode("\r\n", $input);
	
	foreach ($ary_input as $v)
	{	
		for($i = 0; $i < count($ary_f); $i++)
		{
			$f = "/".$ary_f[$i]."/";
			$t = $ary_t[$i];
			$v = preg_replace($f, $t, $v);
		}		
		$output .= "$v\n";
	}
	unset($ary_f);
	unset($ary_t);
	unset($ary_input);
}

echo <<< EOF
<!doctype html>
<html lang="ja">
<head>
	<meta charset="UTF-8" />
	<title>replace-from-to</title>
	<style type="text/css">.box{ float:left; }</style>
</head>
<body>
	<form action="$me" method="post">
		<div class="box">
			word_from:<br />
			<textarea name="word_from" cols="30" rows="18">$word_from</textarea>
		</div>
		<div class="box">
			word_to:<br />
			<textarea name="word_to" cols="30" rows="18">$word_to</textarea>
		</div>
		<div class="box">
			input:<br />
			<textarea name="input" cols="30" rows="18">$input</textarea>
		</div>
		<br clear="both" />
		<input type="submit" />
	</form><hr style="border:1px dotted #ccc;" />
	<div class="box">
		output:<br />
		<textarea cols="60" rows="18" readonly>$output</textarea>
	</div>
</body>
</html>
EOF;
?>