<?php
$ME = $_SERVER['PHP_SELF'];
$SERVER_NAME = $_SERVER['SERVER_NAME'];
//---------------------------------------------
$f_sql = trim($_POST['sql']);
$f_doit = trim($_POST['doit']);
$f_isTable = trim($_POST['isTable']);
//---------------------------------
$mSql = preg_replace("/[\r]?\n/", " ", $f_sql);
//---------------------------------------------
$mOutput = "";
$mLog = "";

$mOutputHTML = "";

if($f_sql != null && $f_doit != null)
{
	include_once dirname(__FILE__) . '/./DbManageClass.php';
	$dbh = new DbManageClass();
	$dbh->connect();
	
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
				
				if($f_isTable == null)
				{
					$header .= "$k\t";
					$line .= "$v\t";
				}
				else
				{
					$header .= "<th>$k</th>";
					$line .= "<td>$v</td>";
				}
			}
			
			if($f_isTable == null)
			{
				$mOutput .= preg_replace("/\t$/", "\n", $header);
			}
			else 
			{
				$mOutput .= "<tr>$header</tr>\n";
			}
		}
		else 
		{
			for($i=0; $i<count($hAry); $i++)
			{
				$hName = $hAry[$i];
				if($f_isTable == null)
				{
					$line .= $ent[$hName] . "\t";
				}
				else 
				{
					$line .= "<td>" . $ent[$hName] . "</td>";
				}
			}
		}
		
		if($f_isTable == null)
		{
			$mOutput .= preg_replace("/\t$/", "\n", $line);
		}
		else
		{
			$mOutput .= "<tr>$line</tr>\n";
		}
	}
	
	$dbh->disconnect();
	
	if($f_isTable == null)
	{
		$mOutputHTML = "<textarea style='width:100%;height:32em;' readonly >$mOutput</textarea>";
	}
	else 
	{
		$mOutputHTML = "<table>\n$mOutput</table>\n";
	}
	
	$mLog .= "count($cnt)\n";
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
	<title>[$SERVER_NAME]view-sql-sp</title>
	<style type="text/css">
		input:focus, textarea:focus{ border:3px solid blue; }
		th, td{ border:1px solid gray; padding-left:1em; padding-right:1em; }
	</style>
	<script type="text/javascript" src="./jquery.min.js"></script>
</head>
<body>
	<h2>[$SERVER_NAME]</h2>
	
	header
	<input type="text" id="header" size="5" /> 
	
	slug
	<input type="text" id="slug" size="10" /> 
	
	<button id="setHeader">setHeader</button>
	
	<form action="$ME" method="post">
	
		SQL:<br />
		<textarea id="sql" name="sql" style="width:100%; height:32em;">$f_sql</textarea><p />
		
		<label for="doit">doit</label>
		<input name="doit" type="text" size="1" value="$f_doit" />
		
		<label for="isTable">isTable</label>
		<input name="isTable" type="text" size="1" value="$f_isTable" />
		
		<input type="submit" value="view" />
	</form>
	<hr />
	
	output:<br />
	$mOutputHTML<p />
	
	log:<br />
	<textarea style="width:100%;height:3em;" readonly >$mLog</textarea>
			
	<script type="text/javascript" src="./view-sql-sp.js"></script>
</body>
</html>
EOF;
?>
