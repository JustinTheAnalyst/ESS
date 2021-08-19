<?php
define("HOST", "localhost");
define("DB_USERNAME", "mgr_admin");
define("DB_PASSWORD", "");
define('DB_NAME', "essdb");

$DB = new PDO('mysql:host='.HOST.';dbname='.DB_NAME.';charset=utf8', DB_USERNAME, DB_PASSWORD);

$cron_title = "Leave Check.";

$today = date("Y-m-d");
$current_year = date("Y");
$start_date = $current_year."-12-31";
$period = ' - 2 years';
$expired_date = date('Y-m-d', strtotime($start_date.$period));


$sqlSelectExpiryDate = "SELECT * FROM u_userleave WHERE ul_Status = ? AND ul_ExpiryDate <= ?";
$stmt = $DB->prepare($sqlSelectExpiryDate);
$stmt->bindValue(1,'A');
$stmt->bindValue(2,$today);
$stmt->execute();

$numRow = $stmt->rowCount();
$leave_status = "E";

while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
{
	$id = $row['ul_id'];
	
	$sqlUpdateStatus = "UPDATE u_userleave SET ul_Status = ? WHERE ul_id = ?";
	$stmt2 = $DB->prepare($sqlUpdateStatus);
	$stmt2->bindValue(1,$leave_status);
	$stmt2->bindValue(2,$id);
	$stmt2->execute();
}
	
$cron_status = "Successful. ".$numRow." row/s affected.";

$sqlInsertCJHistory = 'INSERT INTO cnf_cronjobhistory (cron_title, cron_status) VALUES (?,?)';
$stmt = $DB->prepare($sqlInsertCJHistory);
$stmt->bindValue(1,$cron_title);
$stmt->bindValue(2,$cron_status);
$stmt->execute();
?>
