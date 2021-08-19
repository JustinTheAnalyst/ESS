<?php 
include '../inc/PHP/constants.php';
include '../inc/PHP/configs.php';

global $DB;

$user_id = $_POST['uid'];
$uh_id = $_POST['uhid'];

$getJobHistory = "SELECT * FROM u_userjobhistory WHERE uh_user_id = ? AND uh_status != ?";
$stmt = $DB->prepare($getJobHistory);
$stmt->bindValue(1, $user_id);
$stmt->bindValue(2, 'E');
$stmt->execute();

while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
{
	if ($row['uh_id'] == $uh_id)
	{
		$status = "A";
	}
	else 
	{
		$status = "E";
	}
	
	$sql = "UPDATE u_userjobhistory SET uh_status = ? WHERE uh_id = ?";
	$stmt2 = $DB->prepare($sql);
	$stmt2->bindValue(1, $status);
	$stmt2->bindValue(2, $row['uh_id']);
	$stmt2->execute();
}

$sqlGetJobHistoryByID = "SELECT * FROM u_userjobhistory WHERE uh_id=?";
$stmt = $DB->prepare($sqlGetJobHistoryByID);
$stmt->bindValue(1, $uh_id);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

$sqlUpdateUserCompany = "UPDATE u_usercompany SET loc_id=?, dept_id=?, desig_id=? WHERE user_id=?";
$stmt3 = $DB->prepare($sqlUpdateUserCompany);
$stmt3->bindValue(1, $row['uh_loc_id']);
$stmt3->bindValue(2, $row['uh_dept_id']);
$stmt3->bindValue(3, $row['uh_desig_id']);
$stmt3->bindValue(4, $user_id);
$stmt3->execute();

//header("Location: employee-edit.php?user_id=".$user_id);
echo "Updated successfully.";
?>