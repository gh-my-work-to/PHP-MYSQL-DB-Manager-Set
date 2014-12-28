<?php
$me = $_SERVER['PHP_SELF'];

$sql = $_POST['sql'];
$colNames = $_POST['colNames'];

$mOutput = "";
$mLog = "";

$mColNameAry = explode(",", $colNames);
foreach ($mColNameAry as $v)
{
	$mLog .= "[$v] ";
}
$mLog .= "\n";

if(strlen($sql) != 0 && count($mColNameAry) != 0)
{
	$mOutput .= makeOutput($sql, $mColNameAry);
}

function makeOutput($sql, $mColNameAry)
{
	$ret = "";
	global $mLog;
	
	include_once dirname(__FILE__) . '/./MyDbManageClass.php';

	$dbh = new MyDbManageClass();
	
	$dbh->connect();
	$res = $dbh->retQueryed(trim($sql));
	
	$mLog .= "count:".mysql_num_rows($res)."\n";
	
	while($ent = mysql_fetch_array($res))
	{
		$line = "";
		
// 		foreach ($ent as $key => $v)
// 		{
// 			if(!is_numeric($key))
// 			{
// 				$line .= $ent[$key].",";
// 			}
// 		}
		
		foreach ($mColNameAry as $cname)
		{
			$line .= $ent[$cname].",";
		}
		
		$line = substr($line, 0, strlen($line) - 1);
		$ret .= $line."\n"; 
	}
	
	$dbh->disconnect();
	return $ret;
}

echo <<< EOF
<!doctype html>
<html lang="ja">
<head>
	<meta charset="UTF-8" />
	<title>mysql-console</title>
	<style type="text/css">.box{ float:left; }</style>
</head>
<body>
	<form action="$me" method="post">
		<div class="box">
			sql:<br />
			<input name="sql" size="50" value="$sql" /><p />
			colNames:<br />
			<input type="text" size="50" name="colNames" value="$colNames" />
		</div>
		<div class="box">
			output:<br />
			<textarea cols="40" rows="20" readonly>$mOutput</textarea>
		</div>
		<br clear="both" />
		<input type="submit" />
	</form>
	<hr />
	log:<br />
	<textarea cols="60" rows="16" readonly>$mLog</textarea>
</body>
</html>
EOF;
?>
