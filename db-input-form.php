<?php
include_once dirname(__FILE__) . '/./MyDbManageClass.php';

$me = $_SERVER['PHP_SELF'];

$dbName = "theDB";
$tableName = $_POST['tableName'];
$colName = $_POST['colName'];

$word = $_POST['word'];
$mAry = explode("\n", $word);

$dbh = new MyDbManageClass();

$dbh->connect();
$dbh->startTransaction();

$cnt = 0;
$mLog = "";
foreach ($mAry as $v)
{
	$v = trim($v);
	if($v == null){ continue; }
	
	//quote add
	$mBry = explode(",", $v);
	$vQd = "";
	foreach ($mBry as $bv)
	{
		if(!is_numeric($bv))
		{
			$bv = "'$bv'";
		}
		$vQd .= "$bv,";
	}
	$vQd = substr($vQd, 0, strlen($vQd) - 1);
	
	$sql = "insert into $dbName.$tableName ($colName) values($vQd)";
	
	$res = $dbh->retQueryedNoError($sql);
	if($res != 1)
	{
		$mLog .= "ERROR! not inserted --> $vQd\n";
	}
	else
	{
		$cnt += $res;	
	}
}
$dbh->endTransaction();
$dbh->disconnect();
unset($mAry);

$mLog .= "$cnt inserted.\n";

echo <<< EOF
<!doctype html>
<html lang="ja">
<head>
	<meta charset="UTF-8" />
	<title>db-input-form</title>
	<style type="text/css">
		.box{ float:left; background-color:#f8f8f8; padding:0.5em; border-radius:0.25em; }
	</style>
</head>
<body>
	<form action="$me" method="post">
		<div class="box">
			dbName:
			$dbName<p />
			
			tableName:
			<input type="text" name="tableName" size="20" value="$tableName" /><p />
			
			colName:
			<input type="text" name="colName" size="20" value="$colName" /><p />
		</div>
		<div class="box">
			data:<br />
			<textarea name="word" cols="30" rows="16">$word</textarea>
			<input type="submit" value="追加" />
		</div>
		<br clear="both" />
	</form>
	<hr />
	log:<br /><textarea name="" id="log" cols="50" rows="16" readonly>$mLog</textarea>
</body>
</html>
EOF;
?>
