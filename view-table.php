<?php
$me = $_SERVER['PHP_SELF'];

$dbName = "theDB";
$tableName = $_POST['tableName'];
$colNames = $_POST['colNames'];
$option = $_POST['option'];
$isDoDecode = $_POST['isDoDecode'];

$mLog = "";
$mOutput = "";

if($tableName != null && $colNames != null)
{
	$mSql = "SELECT $colNames FROM $dbName.$tableName";
	if($option != null)
	{
		$mSql = "$mSql $option";
	}
	$mColNameAry = explode(",", $colNames);
	
	$mOutput = makeOutput($mSql, $mColNameAry, $isDoDecode);
}
/////////////////////////////////////////////////////////////////
function makeOutput($sql, $mColNameAry, $isDoDecode = false)
{
	$ret = "";
	global $mLog;
	
	include_once dirname(__FILE__) . '/./MyDbManageClass.php';
	
	$dbh = new MyDbManageClass();
	
	$dbh->connect();
	$res = $dbh->retQueryed(trim($sql));
	
	$mLog .= "count:" . mysql_num_rows($res) . "\n";
	
	while($ent = mysql_fetch_array($res))
	{
		$line = "";
		
		// foreach ($ent as $key => $v)
		// {
		// if(!is_numeric($key))
		// {
		// $line .= $ent[$key].",";
		// }
		// }
		
		foreach($mColNameAry as $cname)
		{
			$val = $isDoDecode ? urldecode($ent[$cname]) : $ent[$cname];
			$line .= $val . ",";
		}
		
		$line = substr($line, 0, strlen($line) - 1);
		$ret .= $line . "\n";
	}
	
	$dbh->disconnect();
	return substr($ret, 0, strlen($ret) - 1);
}
///////////////////////////////////////////////////////////////////
echo <<< EOF
<!doctype html>
<html lang="ja">
<head>
	<meta charset="UTF-8" />
	<title>view-table</title>
	<style type="text/css">
		.box{ float:left; background-color:#f8f8f8; padding:0.5em; border-radius:0.25em; }
	</style>
</head>
<body>
	<form action="$me" method="post">
		<div style="background-color:#f0f0f0; padding:0.75em; border-radius:0.5em;">
			dbName:
			<input type="text" value="$dbName" readonly /><p />
			
			tableName:
			<input type="text" name="tableName" size="20" value="$tableName" /><p />
			
			colNames:
			<input type="text" name="colNames" size="40" value="$colNames" /><p />
			
			option:<br />
			<input type="text" name="option" style="width:90%;" value="$option" /><p />
			
			isDoDecode:
			<input type="text" name="isDoDecode" value="$isDoDecode" size="3" />
			<input type="submit" value="view" />
		</div>
	</form>
	<hr clear="both" />
	
	output:
	<textarea cols="50" rows="12" readonly>$mOutput</textarea>
	<hr />
	
	log:
	<textarea name="" id="log" cols="50" rows="12" readonly>$mLog</textarea>
</body>
</html>
EOF;
?>
