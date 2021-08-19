<?php
define("HOST", "localhost");
define("DB_USERNAME", "just1st_admin");
define("DB_PASSWORD", "KI747LNWMJQL");
define('DB_NAME', "just1st_leaves");

$DB = new PDO('mysql:host='.HOST.';dbname='.DB_NAME.';charset=utf8', DB_USERNAME, DB_PASSWORD);

$cron_title = "Contract Check.";

$today = date("Y-m-d");
$current_year = date("Y");
$start_date = $current_year."-12-31";
$period = ' - 2 years';
$expired_date = date('Y-m-d', strtotime($start_date.$period));

// contract that are going to expire in coming 30 days
$checking_period = date('Y-m-d', strtotime('+30 days'));

$sqlCheckContract = "SELECT * FROM  u_userjobhistory WHERE uh_expiry_date <= '$checking_period'";
$stmt = $DB->prepare($sqlCheckContract);
$stmt->execute();
$numRow = $stmt->rowCount();

if($stmt->rowCount()>0)
{
	$to = "hr@mispmis.com";
	$subject = $numRow." Contract/s Expiring Soon.";
	
	$headers = "From: no-reply@mispmis.com\r\n";
	$headers .= "Reply-To: ".$to."\r\n";
	$headers .= "Return-Path: no-reply@mispmis.com\r\n";
	$headers .= "CC: ";
	$headers .= "BCC: just1st_85@hotmail.com\r\n";
		
	$body = "Dear HR Manager,";
	
	$count = 1;
	while($row = $stmt->fetch(PDO::FETCH_ASSOC))
	{
		$user_id = $row['user_id'];
		
		$stmt2 = $DB->prepare('SELECT * FROM u_user WHERE user_Status=? AND user_id=?');
		$stmt2->bindParam(1,'A');
		$stmt2->bindParam(2,$user_id);
		$stmt2->execute();
		$employeeDetail = $stmt->fetch(PDO::FETCH_ASSOC);
		 
		$body.= $count;
		$body.= "Employee ID: ".$employeeDetail['user_UserCode']."\n";
		$body.= "Employee Name: ".$employeeDetail['user_FirstName']." ".$employeeDetail['user_LastName']."\n";
		$body.= "Expire ON: ".$row['uh_expiry_date']."\n\n";
		
		$count++;
	}
	
	$body.= "Kindly create a new contract the staff/s.\n\n";
	$body.= "Thank you.\n\n\n";
	$body.= "Your Faithfully,\nBerotech EMS";
	
	mail($to,$subject,$body,$headers);
	
	$cron_status = "Successful. ".$numRow." row/s affected.";
	
	$sqlInsertCJHistory = 'INSERT INTO cnf_cronjobhistory (cron_title, cron_status) VALUES (?,?)';
	$stmt3 = $DB->prepare($sqlInsertCJHistory);
	$stmt3->bindValue(1,$cron_title);
	$stmt3->bindValue(2,$cron_status);
	$stmt3->execute();
	
	$sql = "INSERT INTO cnf_emailnotification(en_Subject, en_Message, en_Type)
	   		VALUE(?, ?, ?)";
	$stmt4 = $DB->prepare($sql);
	$stmt4->bindValue(1,$subject);
	$stmt4->bindValue(2,$body);
	$stmt4->bindValue(3,$cron_title);
	$stmt4->execute();
}
?>