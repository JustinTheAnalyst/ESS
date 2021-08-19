<?php 

if(isset($_POST['generateReport'])){
print_r($_POST);

  $group_id = $_POST['group_id'];
  $dept_id = $_POST['dept_id'];
  $user_id = 0;



  if(!isset($_POST['all_user'])){

   echo $user_id = $_POST['user_id'];

  }
  $user_id = $_POST['user_id'];
  //$preparedBy = $_SESSION['user_id'];

   $sqlSelectEmployeeInfo = "SELECT u_usercompany.user_id , cnf_dept.dept_Name, cnf_designation.desig_Name, cnf_group.group_Name , u_user.user_FirstName , u_user.user_LastName FROM u_usercompany 
  INNER JOIN u_user ON u_user.user_id = u_usercompany.user_id
  INNER JOIN cnf_designation ON cnf_designation.desig_id = u_usercompany.desig_id
  INNER JOIN cnf_dept ON cnf_dept.dept_id = u_usercompany.dept_id
  INNER JOIN cnf_group ON cnf_group.group_id = u_usercompany.group_id
  WHERE 1 ";
  if($user_id!=0){

   echo $sqlSelectEmployeeInfo.=" AND u_usercompany.user_id = $user_id ";

  }
}

 ?>