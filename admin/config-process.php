<?php 
include('inc/PHP/functions.php');

if ($_SESSION['user_Type'] == 'A')
{
	/**
	 * update location details
	 */ 
	if(isset($_POST['loc_Name']) && (!empty($_POST['loc_id'])))
	{
		updateLocation($_POST['loc_id'],$_POST['loc_Name'],$_POST['loc_ShortName'],$_POST['loc_Status']);
	}
	
	/**
	 * Create New Document Record
	 */
	if(isset($_POST['doc_Name']) && empty($_POST['doc_id']))
	{
		createDocument($_POST['doc_Name'],$_POST['doc_Type'],$_POST['doc_Status']);
	}
	
	/**
	 * Update Document Details
	 */
	if(isset($_POST['doc_Name']) && !empty($_POST['doc_id']))
	{
		updateDocument($_POST['doc_id'],$_POST['doc_Name'],$_POST['doc_Type'],$_POST['doc_Status']);
	}
	
	/**
	 * Delete Document Record
	 */
	if(isset($_POST['doc_id']))
	{
		deleteDocument($_POST['doc_id']);
	}
	
	
	/****** Designation **********/
	if(isset($_POST['desig_Name']) && empty($_POST['desig_id']))
	{
		createDesignation($_POST['desig_Name'],$_POST['desig_ShortName'],$_POST['desig_Scale'],$_POST['desig_Status'],$_POST['dl_Number']);
	}
	if(isset($_POST['desig_id']) && (!empty($_POST['is_delete'])))
	{
		deleteDesignation($_POST['desig_id']);
	}
	if(isset($_POST['desig_Name']) && (!empty($_POST['desig_id'])))
	{
		updateDesignation($_POST['desig_id'],$_POST['desig_Name'],$_POST['desig_ShortName'],$_POST['desig_Scale'],$_POST['desig_Status'],$_POST['dl_Number']);
	}
	/************End of designation********/
	
	
	/**
	 * Create New Notice Record
	 */
	if(isset($_POST['notice_Title']) && empty($_POST['notice_id']))
	{
		createNotice($_POST);
	}
	
	/**
	 * Update Notice Details
	 */
	if(isset($_POST['notice_Title']) && (!empty($_POST['notice_id'])) )
	{
		updateNotice($_POST);
	}
	
	/**
	 * Delete Notice Record
	 */
	if(isset($_POST['notice_id']) && (!empty($_POST['is_delete'])))
	{
		deleteNotice($_POST['notice_id']);
	}
	
	
	/**
	 * Create New Holiday Record
	 * 
	 * If holiday ID is empty
	 */
	if(isset($_POST['holiday_Title']) && empty($_POST['holiday_id']))
	{
		createHoliday($_POST);
	}
	
	/**
	 * Update Holiday Details
	 * 
	 * If holiday ID is not empty
	 */
	if(isset($_POST['holiday_Title']) && (!empty($_POST['holiday_id'])) )
	{
		updateHoliday($_POST);
	}
	
	/**
	 * Delete Holiday Record
	 */
	if(isset($_POST['holiday_id']) && (!empty($_POST['is_delete'])))
	{
		deleteHoliday($_POST['holiday_id']);
	}
	
	/*************Department**************/
	if(isset($_POST['dept_Name']) && empty($_POST['dept_id']))
	{
		createdepartment($_POST['dept_Name'],$_POST['dept_ShortName'],$_POST['dept_Status']);
	}
	if(isset($_POST['dept_id']) && (!empty($_POST['is_delete'])))
	{
		deleteDepartment($_POST['dept_id']);
	}
	if(isset($_POST['dept_Name']) && (!empty($_POST['dept_id'])))
	{
		updateDepartment($_POST['dept_id'],$_POST['dept_Name'],$_POST['dept_ShortName'],$_POST['dept_Status']);
	}
	/*************End of department*******/
	/************Employee Add ***********/
	
	// check user code validity
	if((isset($_POST['user_UserCode'])) && (!isset($_POST['user_id'])) && (!isset($_POST['u_id'])))
	{
		checkUserCode($_POST['user_UserCode']);
	}
	
	// check user email validity
	if((isset($_POST['user_Email'])) && (!isset($_POST['user_id'])) && (!isset($_POST['u_id'])))
	{
		checkUserEmail($_POST['user_Email']);
	}
	
	if((isset($_POST['user_UserCode'])) && (isset($_POST['user_id'])) && (isset($_POST['u_id'])))
	{
		checkUserCodeEdit($_POST['user_UserCode'],$_POST['user_id']);
	}
	if((isset($_POST['user_Email'])) && (isset($_POST['user_id'])) && (isset($_POST['u_id'])))
	{
		checkUserEmailEdit($_POST['user_Email'],$_POST['user_id']);
	}
	if(isset($_POST['user_FirstName'])  && isset($_POST['is_create']))
	{
		//print_r($_POST);
		createEmployee($_POST,$_FILES);
	}
	
	// View Employee Profile
	if(isset($_POST['get_user_id']))
	{
		viewEmployee($_POST['get_user_id']);
	}
	
	if(isset($_POST['user_id']) && (!empty($_POST['is_delete'])))
	{
		deleteEmployee($_POST['user_id']);
	}
	if(isset($_POST['edit_user_id']))
	{
		editGetEmployee($_POST['edit_user_id']);
	}
	if(isset($_POST['u_id']) && isset($_POST['is_update']))
	{	
		//print_r($_POST);
		updateEmployee($_POST,$_FILES);
	}
	/**********End of employee Add *********/
	
	/**
	 * Update Leave Status By Batch
	 **/
	if(isset($_POST['lb_id']) && isset($_POST['lb_Status']))
	{
		changeLeaveBatchStatus($_POST['lb_id'],$_POST['lb_Status'],$_POST['lb_Remarks'] , $_FILES);
	}
	
	/**
	 * Change Individual Leave Status
	 **/
	if(isset($_POST['la_id']) && isset($_POST['la_Status']))
	{
		changeLeaveStatus($_POST['la_id'],$_POST['la_Status']);
	}
	
	/***************************
	*	Leave Type Starts Here 
	***************************/
	if(isset($_POST['lt_Name']) && empty($_POST['lt_id']))
	{
		createLeaveType($_POST['lt_Name'],$_POST['lt_Status']);
	}
	if(isset($_POST['lt_id']) && (!empty($_POST['is_delete'])))
	{
		deleteLeaveType($_POST['lt_id']);
	}
	if(isset($_POST['lt_Name']) && (!empty($_POST['lt_id'])))
	{
		updateLeaveType($_POST['lt_id'],$_POST['lt_Name'],$_POST['lt_Status']);
	}
	/***************************
	*	Leave Type Ends Here 
	***************************/
	
	/**
	 * Add New Job Of Employee
	 */
	
	if(isset($_POST['addNewEmployeeJobId']) && isset($_POST['editNewEmployeeJobId']) )
	{
		if($_POST['addNewEmployeeJobId']!=0)
		{
			addNewEmployeeJob($_POST);	
		}
		else
		{
			editNewEmployeeJob($_POST);
		}
	}
	
	/****************************************
	    Add New Job Of Employee Ends Here
	*****************************************/
	
	/*** Employee Job Delete Starts ***/
		if(isset($_POST['uh_id']) && isset($_POST['is_delete'])){
	
			deleteEmployeeJob($_POST['uh_id']);
		}
	/*** Employee Job Delete Ends ***/
	/***** Adjust Employee Leaves Starts here ****/
		
		if(isset($_POST['EmployeeLeaveAdjustId'])){
	
			EmployeeLeaveAdjust($_POST);
		}
	
	/***** Adjust Employee Leaves Ends here ****/
	/***** DELETE Leave Adjustment Starts *****/
		
		if(isset($_POST['ul_id']) && isset($_POST['is_delete'])){
	
			deleteUL($_POST['ul_id']);
		}
	
	/***** DELETE Leave Adjustment Ends *****/
	if(isset($_POST['SingleLeaveAdjustId'])){
	SingleLeaveAdjust($_POST);
	
	}
	
	/**************************************88
	*	Application	
	****************************************/
	if(isset($_POST['lt_id']) && (!empty($_POST['la_FromDate'])))
	{
		//print_r($_POST);
	
		createLeaveApplication($_POST, $_FILES);
	}
	
	/*
	Delete Leave Batch
	*/
	
	if(isset($_POST['lb_id']) && isset($_POST['is_delete'])){
	
		deleteLeaveBatch($_POST['lb_id']);
	}
	
	/**
	 * Update My Profile
	 */
	if(isset($_POST['profile_id']) && isset($_POST['is_update']))
	{
		updateMyProfile($_POST, $_FILES);
	}
	
	/*******************************
	*	DB Backup Starts Here
	********************************/
	
	if(isset($_POST['db_id']) && isset($_POST['is_delete'])){
	
		deleteDbBackup($_POST['db_id']);
	}
}
elseif ($_SESSION['user_Type'] == 'E')
{
	/**
	 * Create Leave Application
	 */
	if(isset($_POST['lt_id']) && (!empty($_POST['la_FromDate'])))
	{
		createLeaveApplication($_POST, $_FILES);
	}
	
	/**
	 * Delete Leave Application
	 */
	if(isset($_POST['lb_id']) && isset($_POST['is_delete'])){
	
		deleteLeaveBatch($_POST['lb_id']);
	}
	
	/**
	 * Update My Profile
	 */
	if(isset($_POST['profile_id']) && isset($_POST['is_update']))
	{
		updateMyProfile($_POST, $_FILES);
	}
}
elseif ($_SESSION['user_Type'] == 'M' || $_SESSION['user_Type'] == 'T')
{
	/**
	 * Create Leave Application
	 */
	if(isset($_POST['lt_id']) && (!empty($_POST['la_FromDate'])))
	{
		createLeaveApplication($_POST, $_FILES);
	}
	
	/**
	 * Delete Leave Application
	 */
	if(isset($_POST['lb_id']) && isset($_POST['is_delete'])){
	
		deleteLeaveBatch($_POST['lb_id']);
	}
	
	/**
	 * Update My Profile
	 */
	if(isset($_POST['profile_id']) && isset($_POST['is_update']))
	{
		updateMyProfile($_POST, $_FILES);
	}
	
	/**
	 * Update Leave Status By Batch
	 **/
	if(isset($_POST['lb_id']) && isset($_POST['lb_Status']))
	{
		changeLeaveBatchStatus($_POST['lb_id'],$_POST['lb_Status'],$_POST['lb_Remarks'] , $_FILES);
	}
}
elseif ($_SESSION['user_Type'] == 'C')
{
	/**
	 * Create Leave Application
	 */
	if(isset($_POST['lt_id']) && (!empty($_POST['la_FromDate'])))
	{
		createLeaveApplication($_POST, $_FILES);
	}
	
	/**
	 * Delete Leave Application
	 */
	if(isset($_POST['lb_id']) && isset($_POST['is_delete'])){
	
		deleteLeaveBatch($_POST['lb_id']);
	}
	
	/**
	 * Update My Profile
	 */
	if(isset($_POST['profile_id']) && isset($_POST['is_update']))
	{
		updateMyProfile($_POST, $_FILES);
	}
}
else 
{
	
}
?>