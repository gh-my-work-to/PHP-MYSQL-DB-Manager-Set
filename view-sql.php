<?php
$DBNAME = "my_db_name";
//=============================================
$ME = $_SERVER['PHP_SELF'];
$SERVER_NAME = $_SERVER['SERVER_NAME'];
//---------------------------------------------
$f_sql = trim($_GET['sql']);
$mSql = preg_replace("/[\r]?\n/", " ", $f_sql);
//---------------------------------------------
$mLog = "";
$mOutput = "";

if($f_sql != null)
{
	include_once dirname(__FILE__) . '/./MyDbManageClass.php';
	$dbh = new MyDbManageClass();
	$dbh->connect();
	
	$dbh->retQueryed("use $DBNAME");
	
	$res = $dbh->retQueryed($mSql);
	$hAry = array();
	
	$cnt = 0;
	while($ent = mysql_fetch_array($res))
	{
		$line = "";
		
		if(++$cnt == 1)
		{
			$header = "";
			foreach ($ent as $k => $v)
			{
				if(preg_match("/^\d+$/", $k))
				{
					continue;
				}
				array_push($hAry, $k);
				$header .= "$k\t";
				$line .= "$v\t";
			}
			
			$mOutput .= preg_replace("/\t$/", "\n", $header); 
		}
		else 
		{
			for($i=0; $i<count($hAry); $i++)
			{
				$hName = $hAry[$i];
				$line .= $ent[$hName] . "\t";
			}
		}
		
		$mOutput .= preg_replace("/\t$/", "\n", $line);
	}
	
	$dbh->disconnect();
}
//========================================================================
function getSafed($data)
{
	return preg_replace('/</s', '&lt;',
			preg_replace('/>/s', '&gt;',
					preg_replace('/\&/s', '&amp;', $data)));
}
//========================================================================
echo <<< EOF
<!doctype html>
<html lang="ja">
<head>
	<meta charset="UTF-8" />
	<title>[$SERVER_NAME]view-sql</title>
	<style type="text/css">
		input:focus, textarea:focus{ border:3px solid blue; }
	</style>
</head>
<body>
	<h2>[$SERVER_NAME]</h2>
	<form action="$ME" method="get">
	
		SQL:<br />
		<textarea name="sql" style="width:100%; height:8em;">$f_sql</textarea><p />
		
		<input type="submit" value="view" />
	</form>
	<hr />
	
	output:<br />
	<textarea style="width:100%;height:32em;" readonly >$mOutput</textarea>
	
	log:<br />
	<textarea style="width:100%;height:3em;" readonly >$mLog</textarea>
</body>
</html>
EOF;
?>
