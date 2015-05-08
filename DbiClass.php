<?php
class DbiClass
{
	private $mUrl;
	private $mUser;
	private $mPass;
	
	private $mDbName;
	
	function __construct()
	{
		if($_SERVER['SERVER_NAME'] == 'localhost')
		{
			$this->mUrl = 'localhost';
			$this->mUser = 'user';
			$this->mPass = 'password';
			
			$this->mDbName = 'dbname';
		}
		else
		{
			$this->mUrl = 'remotehost';
			$this->mUser = 'r_user';
			$this->mPass = 'r_password';
			
			$this->mDbName = 'r_dbname';
		}
	}
	
	function get_mysqli()
	{
		$mysqli = new mysqli($this->mUrl, $this->mUser, $this->mPass, $this->mDbName);
		
		if($mysqli->connect_errno)
		{
			echo "Connect failed:" . $mysqli->connect_error . "<br />\n";
			exit();
		}
		
		if($mysqli->query('use ' . $this->mDbName))
		{
			return $mysqli;
		}
		else
		{
			echo "ERROR!:use database!<br />\n";
			exit();
		}
	}
}
?>
