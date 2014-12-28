<?php
$me = $_SERVER['PHP_SELF'];

$input = $_POST['input'];
$isFLH = $_POST['isFLH'];

$from = $_POST['from'];
$to_list = $_POST['to_list'];

$from_dlm = ($_POST['from_dlm'] == "t") ? "\t" : ",";
$to_dlm = ($_POST['to_dlm'] == "t") ? "\t" : ",";

$output = "";

if($input != null && $from != null && $to_list != null)
{
	doThat();
}
function doThat()
{
	global $input, $output, $isFLH;
	global $from, $to_list;
	global $from_dlm, $to_dlm;
	
	$ary_t = explode("\r\n", $to_list);
	$ary_input = explode("\r\n", $input);
	
	if($isFLH != null && is_numeric($isFLH))
	{
		for($i = 0; $i < $isFLH; $i++)
		{
			$line = array_shift($ary_input);
			$output .= $line."\n";			
		}
	}
	
	$aryFF = explode($from_dlm, $from);//explode(",", $from);
	
	foreach($ary_t as $t)
	{
		if($t == null)
		{
			continue;
		}
		$aryTT = explode($to_dlm, $t);
		
		foreach($ary_input as $line)
		{
			$line_rd = $line;
			
			for($x = 0; $x < count($aryFF); $x++)
			{
				$line_rd = preg_replace("/".$aryFF[$x]."/", $aryTT[$x], $line_rd);				
			}
			
			$output .= "$line_rd\n";
		}
	}
	
	unset($ary_t);
	unset($ary_input);
}

$from_dlm_c_checked = ($from_dlm == ",") ? "checked" : "";
$from_dlm_t_checked = ($from_dlm == ",") ? "" : "checked";

$to_dlm_c_checked = ($to_dlm == ",") ? "checked" : "";
$to_dlm_t_checked = ($to_dlm == ",") ? "" : "checked";

echo <<< EOF
<!doctype html>
<html lang="ja">
<head>
	<meta charset="UTF-8" />
	<title>replace-repeat</title>
	<style type="text/css">.box{ float:left; }</style>
</head>
<body>
	<form action="$me" method="post">

		<div class="box">
			input:<br />
			<textarea name="input" cols="60" rows="18">$input</textarea><br />
			ヘッダー行数:<input type="text" size="3" name="isFLH" value="$isFLH" />
		</div>
		
		<div class="box">

			from:
			<input type="radio" name="from_dlm" value="c" $from_dlm_c_checked>,</input>
			<input type="radio" name="from_dlm" value="t" $from_dlm_t_checked>\\t</input>
			<br />
			<input size="20" type="text" name="from" value="$from" /><p />
		
			to_list:
			<input type="radio" name="to_dlm" value="c" $to_dlm_c_checked>,</input>
			<input type="radio" name="to_dlm" value="t" $to_dlm_t_checked>\\t</input>
			<br />
			<textarea name="to_list" cols="20" rows="16">$to_list</textarea>
		</div>
		
		<br clear="both" />
		<input type="submit" />
	</form>
	<hr style="border:1px dotted #ccc;" />
	
	<div class="box">
		output:<br />
		<textarea cols="60" rows="16" readonly>$output</textarea>
	</div>
</body>
</html>
EOF;
?>
