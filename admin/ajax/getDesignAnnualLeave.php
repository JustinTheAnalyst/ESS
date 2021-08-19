<?php 
include '../inc/PHP/constants.php';
include '../inc/PHP/configs.php';

global $DB;

$design_id = $_POST['did'];
$currentMonth = date('m');
$currentYear = date('Y');

$getDesignAnnualLeave = "SELECT * FROM cnf_desigleave WHERE dl_Year = ? AND desig_id = ?";
$stmt = $DB->prepare($getDesignAnnualLeave);
$stmt->bindValue(1, $currentYear);
$stmt->bindValue(2, $design_id);
$stmt->execute();

while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
{
	$dl_Number = ceil(($row['dl_Number']/12)*(12-$currentMonth));
	
	echo $dl_Number;
}
?>