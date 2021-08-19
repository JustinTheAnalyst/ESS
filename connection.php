<?php
//session_start();
$whitelist = array('127.0.0.1', '::1', 'localhost');
	
	if(in_array($_SERVER['REMOTE_ADDR'], $whitelist))
	{
		$con=mysqli_connect("localhost" , "root" , "" , "ess" );
	}
	else
	{
		$con=mysqli_connect("localhost" , "just1st_admin" , "KI747LNWMJQL" , "just1st_leaves" );
	}

 if(!$con)
 {

 	die(mysqli_error($con));
 	 
 }
 
 $GLOBALS['con']=$con;

 function exe_Query($query)
 {
 	$con=$GLOBALS['con'];
 	if(mysqli_query($con,$query))
 	{
 		return 1;
 	}
 	else
 	{
 		return 0;
 	}
 }
date_default_timezone_set("Asia/Kuala_Lumpur"); 
 
/*
function test_input($data)
{
 	return $data;
}
*/
?>
