<?php

if(!defined('INCLUDE_CHECK')) die('You are not allowed to execute this file directly');

function GetUsersDatabaseConnection()
{
	/* Database config */

	$db_host		= 'localhost';
	$db_user		= ADMIN;
	$db_pass		= PASSWORD;
	$db_database	= DATABASE; 

	/* End config */


	$mysqli = new mysqli($db_host,$db_user,$db_pass) or die('Unable to establish a DB connection');
	$mysqli->select_db($db_database);
	$mysqli->query("SET names UTF8");
	
	return $mysqli;
}

?>
