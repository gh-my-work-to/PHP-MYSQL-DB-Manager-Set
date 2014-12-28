<?php
class NameMod
{

	function getIt($sql, $col, $isDoDecode = false)
	{
		include_once dirname(__FILE__) . '/./MyDbManageClass.php';
		
		$mDbh = new MyDbManageClass();
		$mDbh->connect();
		
		$mRes = $mDbh->retQueryed($sql);
		
		$ret = "";
		while($mEnt = mysql_fetch_array($mRes))
		{
			$ret = $isDoDecode ? urldecode($mEnt[$col]) : $mEnt[$col];
			break;
		}
		$mDbh->disconnect();
		
		return $ret;
	}

}
?>