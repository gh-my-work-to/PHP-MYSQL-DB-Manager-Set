<?php
$DB_NAME = 'MYDBNAME'; 

$SV_NAME = $_SERVER['SERVER_NAME'];
//--------------------------------
$mOutput = "";
if($DB_NAME != null)
{
	include_once dirname(__FILE__) . '/./MyDbManageClass.php';
	$dbh = new MyDbManageClass();
	$dbh->connect();
	
	$res = $dbh->retQueryed("use $DB_NAME");
	
	$res = $dbh->retQueryed("show tables");
	
	$cnt = 1;
	while($ent = mysql_fetch_array($res))
	{
		$table = $ent[0];
		
		$line = "<div class='box'><div style='text-align:center;'>($cnt)</div><br /><b>$table</b><br /><table>\n<tr><th class='col'>col</th><th class='type'>type</th><th class='null'>null</th><th class='key'>key</th><th class='def'>def</th><th class='ext'>ext</th></tr>\n";
		
		$qes = $dbh->retQueryed("desc $table");
		
		while($dnt = mysql_fetch_array($qes))
		{		
			foreach ($dnt as $k => $v)
			{
				if(is_numeric($k))
				{
					continue;
				}
				$line .= "<td>$v</td>";
			}
			
			$line .= "</tr>\n";
		}
		$mOutput .= "$line</table></div>\n";
		$cnt++;
	}
	$dbh->disconnect();
}
//=========================
echo <<< EOF
<!doctype html>
<html lang="ja">
<head>
	<meta charset="UTF-8" />
	<title>[$SV_NAME]show-tables</title>
	<style type="text/css">
	th, td{ border:1px outset gray; padding:0.2em; border-radius:0.3em; }
	th{ background-color:#DEFF00; }
	.col, .type{ width:8em; }
	.null, .key, .def, .ext{ width:3em; }
	.box{ border-bottom:1px solid gray; padding-bottom:2em; }
	</style>
</head>
<body>
	<h2>[$SV_NAME]</h2>
	$mOutput
</body>
</html>
EOF;
?>
