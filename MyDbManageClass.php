<?php
class MyDbManageClass
{
	private $mUrl;
	private $mUser;
	private $mPass;
	
	private $mLink;
	private $mResult;
	
	function __construct()
	{
		$this->mUrl = "localhost";
		$this->mUser = "tester";
		$this->mPass = "tester";
		
		$this->mLink = null;
		$this->mResult = null;
	}
	
	function connect()
	{
		$this->mLink = mysql_connect ( $this->mUrl, $this->mUser, $this->mPass ) or //
		die ( "ERROR! failed to connect to DB." );
	}
	
	function startTransaction()
	{
		mysql_query("set autocommit = 0", $this->mLink);
		mysql_query("begin", $this->mLink);
	}
	function endTransaction()
	{
		mysql_query("commit", $this->mLink);
		mysql_query("set autocommit = 1", $this->mLink);
	}
	
	function retQueryed($sql)
	{
		// carry on query 
		$this->mResult = mysql_query ( $sql, $this->mLink ) or //
		die ( "ERROR! sending query: SQL:" . $sql );
		return $this->mResult;
	}
	function retQueryedNoError($sql)
	{
		$this->mResult = mysql_query ( $sql, $this->mLink );
		return $this->mResult;
	}
	
	function disconnect()
	{
		// free memory
		if ($this->mResult != null && is_bool($this->mResult) == false)
		{
			mysql_free_result($this->mResult);
		}
		
		// disconnect
		if ($this->mLink != null)
		{
			mysql_close($this->mLink) or die("ERROR! disconnecting DB.");
		}
	}
	
	function removeDupli($tableName, $cid, $cname)
	{
		// remove duplicated records
		$sql = "DELETE FROM $tableName WHERE $cid NOT IN (
		SELECT min_id FROM (
		SELECT MIN($cid) min_id FROM $tableName GROUP BY $cname
		) tmp
		)";
		
		mysql_query ( $sql, $this->mLink ) or //
		die ( "ERROR! sending query on removing duplicated records: SQL:" . $sql );
	}

	function getMaxOf($tableName, $colName)
	{
		$r = $this->retQueryed("select max($colName) from $tableName");
		$row = mysql_fetch_assoc($r);
		
		$max = $row["max($colName)"];
		mysql_free_result ($r);
		
		return $max;
	}
}
?>
