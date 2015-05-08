<?php
$ME = $_SERVER['PHP_SELF'];
$mBuf = "";
//---------------------------------------------
$f_table = trim($_POST['table']);

$f_input = trim($_POST['input']);

$mSql = preg_replace("/\r?\n/", " ", $f_input);

if($mSql != null)
{
	include_once dirname(__FILE__) . '/./DbiClass.php';
	$DBH = new DbiClass();
	
	$mysqli = $DBH->get_mysqli();
	//--------------------------------------------------------
	$mRes = $mysqli->query($mSql);
	if($mRes)
	{
		$isGotHeader = false;
		while($ent = $mRes->fetch_assoc())
		{
			if($isGotHeader == false)
			{
				$hLine = "";
				
				foreach ($ent as $k => $v)
				{
					if(is_numeric($k) == false)
					{
						$hLine .= ($f_table == null) ? "$k\t" : "<th>$k</th>";
					}
				}
				
				$mBuf .= ($f_table == null) ? //

					preg_replace("/\t$/", "\n", $hLine) ://
					
					"<tr>$hLine</tr>\n";
				
				$isGotHeader = true;
			}
			
			$line = "";
			foreach ($ent as $v)
			{
				$line .= ($f_table == null) ? "$v\t" : "<td>$v</td>";
			}
				
			$mBuf .= ($f_table == null) ? //
				
				preg_replace("/\t$/", "\n", $line) : //
				
				"<tr>$line</tr>\n";
		}
		
		$mRes->free();
	}
	
	$mysqli->close();
	
	$mOutputHTML = ($f_table == null) ? //
		
		"<textarea style='width:100%; height:20em;' readonly >$mBuf</textarea>" : //
		
		"<table>$mBuf</table>";
}
//==============================================================
$mSname = $_SERVER['SERVER_NAME'];
echo <<< EOF
<!doctype html>
<html lang="ja">
<head>
	<meta charset="UTF-8" />
	<title>[$mSname]view-dbic</title>
	<style type='text/css'>
	th, td{ border:1px solid gray; border-radius:0.5em; padding-left:1em; padding-right:1em; }
	th{ background-color:#ffffcc; }
	input:focus, textarea:focus{ background-color:#ffff88; }
	</style>
</head>
<body>
	<form action="$ME" method="post">
	
		SQL:<br />
		<textarea name="input" style="width:100%; height:12em; line-height:2em;">$f_input</textarea><br />
		
		table:<input type="text" name="table" size="1" value="$f_table" />
		
		<input type="submit" value="go" />
	</form>
	<hr />
	
	output:<br />
	$mOutputHTML
</body>
</html>
EOF;
?>
