<?php 
include '../inc/PHP/constants.php';
include '../inc/PHP/configs.php';

global $DB;

if (empty($_POST["loc_id"]) && empty($_POST["dept_id"]))
{
	$loc_id = $_POST['loc_id'];
	$sql  = "SELECT * FROM u_user WHERE u_user.user_Status=?";
	$stmt = $DB->prepare($sql);
	$stmt->bindValue(1, 'A');
	$stmt->execute();
	$numRow = $stmt->rowCount();

	if($numRow > 0)
	{
		echo '<option value="" selected="" disabled="">-- Select Employee --</option>';

		while($row = $stmt->fetch(PDO::FETCH_ASSOC))
		{
			echo '<option value="'.$row['user_id'].'">'.$row['user_FirstName'].' / '.$row['user_LastName'].'</option>';
		}
	}
	else
	{
		echo '<option value="">No Data Found.</option>';
	}
}

if (isset($_POST['loc_id']) && !empty($_POST["loc_id"]))
{
	$loc_id = $_POST['loc_id'];
	$sql  = "SELECT * FROM u_user
		 	 INNER JOIN u_usercompany ON u_usercompany.user_id = u_user.user_id
		 	 WHERE u_usercompany.loc_id = ?";
	$stmt = $DB->prepare($sql);
	$stmt->bindValue(1, $loc_id);
	$stmt->execute();
	$numRow = $stmt->rowCount();
	
	if($numRow > 0)
	{
		echo '<option value="" selected="" disabled="">-- Select Employee --</option>';
		
		while($row = $stmt->fetch(PDO::FETCH_ASSOC))
		{
			echo '<option value="'.$row['user_id'].'">'.$row['user_FirstName'].' / '.$row['user_LastName'].'</option>';
		}
	}
	else
	{
		echo '<option value="">No Data Found.</option>';
	}
}

if (isset($_POST['dept_id']) && !empty($_POST["dept_id"]))
{
	$dept_id = $_POST['dept_id'];
	$sql  = "SELECT * FROM u_user
		 	 INNER JOIN u_usercompany ON u_usercompany.user_id = u_user.user_id
		 	 WHERE u_usercompany.dept_id = ?";
	$stmt = $DB->prepare($sql);
	$stmt->bindValue(1, $dept_id);
	$stmt->execute();
	$numRow = $stmt->rowCount();

	if($numRow > 0)
	{
		echo '<option value="">-- Select Employee --</option>';

		while($row = $stmt->fetch(PDO::FETCH_ASSOC))
		{
			echo '<option value="'.$row['user_id'].'">'.$row['user_FirstName'].' / '.$row['user_LastName'].'</option>';
		}
	}
	else
	{
		echo '<option value="">No Data Found.</option>';
	}
}

if (isset($_POST['loc_id']) && isset($_POST['dept_id']) && !empty($_POST["loc_id"]) && !empty($_POST["dept_id"]))
{
	$loc_id = $_POST['loc_id'];
	$dept_id = $_POST['dept_id'];

	$sql  = "SELECT * FROM u_user
		 	 INNER JOIN u_usercompany ON u_usercompany.user_id = u_user.user_id
		 	 WHERE u_usercompany.loc_id = ? AND u_usercompany.dept_id = ?";
	$stmt = $DB->prepare($sql);
	$stmt->bindValue(1, $loc_id);
	$stmt->bindValue(2, $dept_id);
	$stmt->execute();
	$numRow = $stmt->rowCount();
	
	if($numRow > 0)
	{
		echo '<option value="">-- Select Employee --</option>';
	
		while($row = $stmt->fetch(PDO::FETCH_ASSOC))
		{
			echo '<option value="'.$row['user_id'].'">'.$row['user_FirstName'].' / '.$row['user_LastName'].'</option>';
		}
	}
	else
	{
		echo '<option value="">No Data Found.</option>';
	}
}
?>