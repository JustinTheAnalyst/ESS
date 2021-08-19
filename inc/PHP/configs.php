<?php 
date_default_timezone_set("Asia/Kuala_Lumpur"); 
$whitelist = array('127.0.0.1', '::1');

if(!in_array($_SERVER['REMOTE_ADDR'], $whitelist))
{
	/*****************************
	 * DATABASE CONNECTION LIVE **
	 ****************************/
	try{
		$DB = new PDO('mysql:host='.HOST.';dbname='.DB_NAME.';charset=utf8', DB_USERNAME, DB_PASSWORD);
	}
	catch(Exception $e){
		echo $e->getMessage();
		echo "A Database Error";
		die();
	}
}
else 
{
	/************************
	 * DATABASE CONNECTION LOCALHOST **
	 ***********************/
	try{
	 	$DB	= new PDO('mysql:host='.HOST.';dbname='.DB_NAME.';charset=utf8', 'root', '');
	 }
	 	catch(Exception $e){
	 	echo $e->getMessage();
	 	echo "A Database Error";
	 	die();
	 }
}
?>