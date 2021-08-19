<?php
include 'sessionCheck.php';
include 'constants.php';
include 'database_tables.php';
include 'configs.php';
//include 'send-mail.php';

    /*===============================================
    ||
    ||      FUNCTIONS FILE
    ||      --------------
    ||      Using PHP PDO (PHP Data Objects) for
    ||      strong DB interaction and security.
    =================================================*/

// get locations list
function getLocationList()
{
	global $DB;

	$sql = "SELECT * FROM cnf_location";
	$stmt = $DB->prepare($sql);
	if($stmt->execute())
	{
		while($r=$stmt->fetch(PDO::FETCH_ASSOC))
		{
			$row[]=$r;
		}
		return $row;
	}
}

/******************************************
 *   Get location from id
 *******************************************/
function getLocationFromID($location_id)
{
	global $DB;
	$stmt =  $DB->prepare('SELECT * FROM cnf_location WHERE loc_id=?');
	$stmt->bindValue(1,$location_id);
	$stmt->execute();
	if($stmt->rowCount()<0)
	{
		return "";
	}
	else
	{
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		return $row;
	}
}

// update table location
function updateLocation($loc_id,$loc_Name,$loc_ShortName,$loc_Status)
{
	global $DB;
	$sql = "UPDATE cnf_location SET loc_name=?,loc_shortName=?,loc_status=? WHERE loc_id=?";
	$stmt = $DB->prepare($sql);
	$stmt->bindParam(1,$loc_Name);
	$stmt->bindParam(2,$loc_ShortName);
	$stmt->bindParam(3,$loc_Status);
	$stmt->bindParam(4,$loc_id);

	if($stmt->execute())
	{
		echo "UPDATED";
	}
	else
	{
		echo "FAILE. Please try again.";
	}
}

    /****************************
     * GET LOGGED IN USER DATA **
     ***************************/
function getActiveDocuments()
{
    global $DB;
    $stmt   =  $DB->prepare('SELECT doc_id,doc_Name,doc_Status,doc_Type FROM cnf_document WHERE doc_Status="A"');
    $stmt->execute();

    while($r=$stmt->fetch(PDO::FETCH_ASSOC))
    {
        $row[]=$r;
    }
    return $row;
}
function createDocument($doc_Name,$doc_Type,$doc_Status)
{
    global $DB;
    $stmt       =       $DB->prepare('INSERT INTO cnf_document (doc_Name, doc_Status,doc_Type) VALUES (?, ?, ?)');
    $stmt->bindParam(1, $doc_Name);
    $stmt->bindParam(2, $doc_Status);
    $stmt->bindParam(3, $doc_Type);
    $stmt->execute();
    $last_id = $DB->lastInsertId();
    echo $last_id."INSERTED";
    die();
}

function deleteDocument($doc_id)
{
    global $DB;
    $stmt   =   $DB->prepare('DELETE FROM cnf_document WHERE doc_id=?');
    $stmt->bindParam(1,$doc_id);
    $stmt->execute();
    echo "DELETED";
}

function getDocumentList()
{
    global $DB;

    $query="SELECT `doc_id`, `doc_Name`, `doc_Type`, `doc_Status` FROM `cnf_document` WHERE 1 ORDER BY doc_id DESC";
    $stmt = $DB->prepare($query);
    $stmt->execute();
    $row=array();
    if($stmt->rowCount())
    {
       while($r = $stmt->fetch(PDO::FETCH_ASSOC))
       {
            $row[]=$r;
       }
    }
    return $row;
}

function editGetDocument($doc_id)
{
    global $DB;
    $stmt   =   $DB->prepare('SELECT doc_id,doc_Name,doc_Type,doc_Status FROM cnf_document WHERE doc_id=?');
    $stmt->bindParam(1,$doc_id);
    $stmt->execute();
    $row =   $stmt->fetch(PDO::FETCH_ASSOC);
    $data='
    <form role="form" id="edit-document-form" onsubmit="editSaveDocument()" action="" method="post">
    <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 ><span class="glyphicon glyphicon-book"></span> Edit Document</h4>
        </div>
        <div class="modal-body">
          
            <div class="form-group">
              <label for="docName"> Document Name</label>
              <input type="text" class="form-control" id="docNameE" name="doc_NameE" value="'.$row['doc_Name'].'" required>
            </div>
            <div class="form-group">
              <label for="docType"> Document Type</label>
              <select class="form-control" id="docTypeE" name="doc_TypeE">
                <option value="F"';
                if($row['doc_Status']=='F'){ $data.=' selected="selected" '; }
                $data.='>File</option>
                <option value="I"';
                if($row['doc_Status']=='I'){ $data.=' selected="selected" '; }
                $data.='>Image</option>
              </select>
            </div>
               <div class="form-group">
                  <label for="docStatus"> Document Status</label>
                  <select class="form-control" id="docStatusE" name="doc_StatusE">
                    <option value="A"';
                    if($row['doc_Status']=='A'){ $data.=' selected="selected" '; }
                    $data.='>Active</option>
                    <option value="I"';
                    if($row['doc_Status']=='I'){ $data.=' selected="selected" '; }
                    $data.='>In-Active</option>
                  </select>
                </div>
            <input type="hidden" name="hidden_doc_id" value="'.$row['doc_id'].'">
            
          
        </div>
        <div class="modal-footer">
          <button class="btn btn-default btn-default " data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Cancel</button>
          <input type="submit" class="btn btn-default btn-success" value="Save" >
          
        </div>
        </form>';
        echo $data;
}
function editSaveDocument($doc_id,$doc_Name,$doc_Type,$doc_Status)
{
    global $DB;
    $stmt   =   $DB->prepare('UPDATE cnf_document SET doc_Name=?, doc_Type=?, doc_Status=? WHERE doc_id=?');
    $stmt->bindParam(1,$doc_Name);
    $stmt->bindParam(2,$doc_Type);
    $stmt->bindParam(3,$doc_Status);
    $stmt->bindParam(4,$doc_id);

    $stmt->execute();
    echo $doc_id."UPDATED";
    die();
}

function createDesignation($desig_Name,$desig_ShortName,$desig_Scale,$desig_Status,$dl_Number)
{
    global $DB;
    $stmt   =   $DB->prepare('INSERT INTO cnf_designation (desig_Name,desig_ShortName,desig_Scale,desig_Status) VALUES (?,?,?,?)');
    $stmt->bindParam(1,$desig_Name);
    $stmt->bindParam(2,$desig_ShortName);
    $stmt->bindParam(3,$desig_Scale);
    $stmt->bindParam(4,$desig_Status);
    if($stmt->execute()){
        $desig_id = $DB->lastInsertId();
        $dl_ExpiryYear=date('Y')+2;
        $dl_ExpiryDate=$dl_ExpiryYear.'-12-31';
        $stmt2=$DB->prepare('INSERT INTO cnf_desigleave(desig_id, lt_id, dl_Number, dl_ExpiryDate, dl_Year, dl_DateTime) VALUES (?,?,?,?, ?, now())');
        $stmt2->bindValue(1,$desig_id);
        $stmt2->bindValue(2,1);
        $stmt2->bindValue(3,$dl_Number);
         
        $stmt2->bindValue(4,$dl_ExpiryDate);
        
        $stmt2->bindValue(5,date('Y'));
        $stmt2->execute();
        echo $desig_id;
    }
    else{
        echo "";
    }

}

function deleteDesignation($desig_id)
{
    global $DB;
    $stmt = $DB->prepare('DELETE FROM cnf_designation WHERE desig_id=?');
    $stmt->bindParam(1,$desig_id);
    if($stmt->execute()){

        deleteDesigLeave($desig_id);
        echo "1";
    }
    else
    {
        "";
    }
}

function getDesignationList()
{
    global $DB;
    $row = array();
    $stmt =  $DB->prepare('SELECT desig_id, desig_Name,desig_ShortName,desig_Scale,desig_Status,(SELECT dl_Number FROM cnf_desigleave WHERE desig_id=cnf_designation.desig_id AND lt_id=1 limit 1 ) AS dl_Number FROM cnf_designation ORDER BY desig_id DESC');
    if($stmt->execute()){
        while($r=$stmt->fetch(PDO::FETCH_ASSOC))
        {
            $row[]=$r;
        }
        return $row;
    }
}

function getActiveDesignation()
{
    global $DB;

    $stmt =  $DB->prepare('SELECT desig_id, desig_Name,desig_ShortName,desig_Scale,desig_Status FROM cnf_designation WHERE desig_Status="A" ORDER BY desig_id DESC');
    if($stmt->execute()){
        while($r=$stmt->fetch(PDO::FETCH_ASSOC))
        {
            $row[]=$r;
        }
        return $row;
    }
}

function updateDesignation($desig_id,$desig_Name,$desig_ShortName,$desig_Scale,$desig_Status,$dl_Number)
{
    global $DB;
    $stmt =  $DB->prepare('UPDATE cnf_designation SET desig_Name=?,desig_ShortName=?,desig_Scale=?,desig_Status=?  WHERE desig_id=?');
    $stmt->bindParam(1,$desig_Name);
    $stmt->bindParam(2,$desig_ShortName);
    $stmt->bindParam(3,$desig_Scale);
    $stmt->bindParam(4,$desig_Status);
    $stmt->bindParam(5,$desig_id);

    if($stmt->execute()){

        $stmt2 = $DB->prepare('UPDATE cnf_desigleave SET dl_Number=? WHERE desig_id=?');
        $stmt2->bindValue(1,$dl_Number);
        $stmt2->bindValue(2,$desig_id);
        if($stmt2->execute())
        {
            echo "UPDATED";
        }
        
    }
    else{
        echo "";
    }
}

function createNotice($postArray)
{
    global $DB;
  // print_r($postArray);
    
    $user_id=$_SESSION['user_id'];
    $notice_Title=$postArray['notice_Title'];
    $notice_Description=$postArray['notice_Description'];
    $notice_FromDate=$postArray['notice_FromDate'];
    $notice_ToDate=$postArray['notice_ToDate'];
    $notice_Status=$postArray['notice_Status'];
    $notice_Remarks=$postArray['notice_Remarks'];
    
    $stmt= $DB->prepare('INSERT INTO cnf_notice(notice_Title, notice_Description, notice_FromDate, notice_ToDate, notice_DateTime, notice_Status, notice_Remarks, user_id) 
                    VALUES (?,?,?,?,now(),?,?,?)');
    $stmt->bindValue(1,$notice_Title);
    $stmt->bindValue(2,$notice_Description);
    $stmt->bindValue(3,$notice_FromDate);
    $stmt->bindValue(4,$notice_ToDate);
    $stmt->bindValue(5,$notice_Status);
    $stmt->bindValue(6,$notice_Remarks);
    $stmt->bindValue(7,$user_id);
    if($stmt->execute())
    {
        echo $notice_id = $DB->lastInsertId();
    }
}


function getNoticeList()
{
    global $DB;

    $stmt=$DB->prepare('SELECT notice_id, notice_Title, notice_Description, notice_FromDate, notice_ToDate, notice_DateTime, notice_Status, notice_Remarks, user_id FROM cnf_notice WHERE 1');
    $stmt->execute();
    $num_rows = $stmt->rowCount();
    if($num_rows<1)
    {
        $row = array();
    }
    else
    {
        while($r=$stmt->fetch(PDO::FETCH_ASSOC))
        {
            $row[]=$r;
        }
    }
   
    return $row;
}

function updateNotice($postArray)
{
    global $DB;
    $notice_id=$postArray['notice_id'];
    $notice_Title=$postArray['notice_Title'];
    $notice_Description=$postArray['notice_Description'];
    $notice_FromDate=$postArray['notice_FromDate'];
    $notice_ToDate=$postArray['notice_ToDate'];
    $notice_Status=$postArray['notice_Status'];
    $notice_Remarks=$postArray['notice_Remarks'];
    $stmt= $DB->prepare('UPDATE cnf_notice SET notice_Title=?,notice_Description=?,notice_FromDate=?,notice_ToDate=?,notice_Status=?,notice_Remarks=? WHERE notice_id=?');

    $stmt->bindValue(1,$notice_Title);
    $stmt->bindValue(2,$notice_Description);
    $stmt->bindValue(3,$notice_FromDate);
    $stmt->bindValue(4,$notice_ToDate);
    $stmt->bindValue(5,$notice_Status);
    $stmt->bindValue(6,$notice_Remarks);
    $stmt->bindValue(7,$notice_id);
    if($stmt->execute())
    {
        echo "UPDATED";
    }
    else
    {
        echo "";
    }
}

function deleteNotice($notice_id)
{
    global $DB;

    $stmt=$DB->prepare('DELETE FROM cnf_notice WHERE notice_id=?');
    $stmt->bindValue(1,$notice_id);
    if($stmt->execute()){
        echo "1";
    }
    else{
        echo "";
    }
}

function createHoliday($postArray)
{
    global $DB;

    $holiday_Title=$postArray['holiday_Title'];
    $holiday_Description=$postArray['holiday_Description'];
    $holiday_Date=$postArray['holiday_Date'];
    $holiday_Status=$postArray['holiday_Status'];
    $month_id= date("m", strtotime($holiday_Date));

    $stmt= $DB->prepare('INSERT INTO cnf_holiday(holiday_Title, holiday_Description, holiday_Date, month_id, holiday_DateTime, holiday_Status) 
            VALUES (?,?,?,?,now(),?)');
    $stmt->bindValue(1,$holiday_Title);
    $stmt->bindValue(2,$holiday_Description);
    $stmt->bindValue(3,$holiday_Date);
    $stmt->bindValue(4,$month_id);
    $stmt->bindValue(5,$holiday_Status);
    if($stmt->execute())
    {
        echo $holiday_id=$DB->lastInsertId();
    }
    
}

function updateHoliday($postArray)
{
    global $DB;

    $holiday_id=$postArray['holiday_id'];
    $holiday_Title=$postArray['holiday_Title'];
    $holiday_Description=$postArray['holiday_Description'];
    $holiday_Date=$postArray['holiday_Date'];
    $holiday_Status=$postArray['holiday_Status'];
    $month_id= date("m", strtotime($holiday_Date));

     $stmt= $DB->prepare('UPDATE cnf_holiday SET holiday_Title=?,holiday_Description=?,holiday_Date=?,month_id=?,holiday_Status=? WHERE holiday_id=?'); 
    $stmt->bindValue(1,$holiday_Title);
    $stmt->bindValue(2,$holiday_Description);
    $stmt->bindValue(3,$holiday_Date);
    $stmt->bindValue(4,$month_id);
    $stmt->bindValue(5,$holiday_Status);
    $stmt->bindValue(6,$holiday_id);
    if($stmt->execute())
    {
        echo "UPDATED";
    }
}

function deleteHoliday($holiday_id)
{
    global $DB;

    $stmt=$DB->prepare('DELETE FROM cnf_holiday WHERE holiday_id=?');
    $stmt->bindValue(1,$holiday_id);
    if($stmt->execute()){
        echo "1";
    }
    else{
        echo "";
    }
}

function getHolidayList()
{
    global $DB;

    $stmt=$DB->prepare('SELECT holiday_id, holiday_Title, holiday_Description, holiday_Date, month_id, holiday_DateTime, holiday_Status FROM cnf_holiday WHERE 1');
    $stmt->execute();
    $num_rows = $stmt->rowCount();
    if($num_rows<1)
    {
        $row = array();
    }
    else
    {
        while($r=$stmt->fetch(PDO::FETCH_ASSOC))
        {
            $row[]=$r;
        }
    }
   
    return $row;
}


function getDepartmentList()
{
    global $DB;

    $row = array();
    $stmt =  $DB->prepare('SELECT dept_id, dept_Name,dept_ShortName,dept_Status FROM cnf_dept ORDER BY dept_id DESC');
    if($stmt->execute()){
        while($r=$stmt->fetch(PDO::FETCH_ASSOC))
        {
            $row[]=$r;
        }
        return $row;
    }
}
function getActiveDepartments()
{
    global $DB;
    $row = array();

    $stmt =  $DB->prepare('SELECT dept_id, dept_Name,dept_ShortName,dept_Status FROM cnf_dept WHERE dept_Status="A" ORDER BY dept_id DESC ');
    if($stmt->execute()){
        while($r=$stmt->fetch(PDO::FETCH_ASSOC))
        {
            $row[]=$r;
        }
        return $row;
    }
}
function createDepartment($dept_Name,$dept_ShortName,$dept_Status)
{
    global $DB;
    $stmt   =   $DB->prepare('INSERT INTO cnf_dept (dept_Name,dept_ShortName,dept_Status) VALUES (?,?,?)');
    $stmt->bindParam(1,$dept_Name);
    $stmt->bindParam(2,$dept_ShortName);
    $stmt->bindParam(3,$dept_Status);
    if($stmt->execute()){
        echo $DB->lastInsertId();
    }
    else{
        echo "";
    }
}

function updateDepartment($dept_id,$dept_Name,$dept_ShortName,$dept_Status)
{
    global $DB;
    $stmt =  $DB->prepare('UPDATE cnf_dept SET dept_Name=?,dept_ShortName=?,dept_Status=? WHERE dept_id=?');
    $stmt->bindParam(1,$dept_Name);
    $stmt->bindParam(2,$dept_ShortName);
    $stmt->bindParam(3,$dept_Status);
    $stmt->bindParam(4,$dept_id);

    if($stmt->execute()){
        echo "UPDATED";
    }
    else{
        echo "";
    }
}

function deleteDepartment($dept_id)
{
    global $DB;
    $stmt = $DB->prepare('DELETE FROM cnf_dept WHERE dept_id=?');
    $stmt->bindParam(1,$dept_id);
    if($stmt->execute()){
        echo "1";
    }
    else
    {
        "";
    }
}

function getActiveGroup()
{
    global $DB;
    $stmt = $DB->prepare('SELECT group_id,group_Name,group_ShortName,group_Status FROM cnf_group WHERE group_Status=?');
    $stmt->bindValue(1,"A");
    $groupRow =array();
    if($stmt->execute())
    {
        while($r=$stmt->fetch(PDO::FETCH_ASSOC))
        {
            $groupRow[]=$r;
        }
    }
    
    return $groupRow;
}
/*************Employee **************/
function checkUserCode($user_UserCode)
{
    global $DB;
    $stmt =  $DB->prepare('SELECT user_id FROM u_user WHERE user_UserCode=?');
    $stmt->bindValue(1,$user_UserCode);
    $stmt->execute();
    if($stmt->rowCount())
    {
        echo "Y";
    }
    else
    {
        echo "N";
    }
}

function checkUserEmail($user_Email)
{

    global $DB;
    $stmt =  $DB->prepare('SELECT user_id FROM u_user WHERE user_Email=?');
    $stmt->bindValue(1,$user_Email);
    $stmt->execute();
    if($stmt->rowCount())
    {
        echo "Y";
    }
    else
    {
        echo "N";
    }
}

function checkUserCodeEdit($user_UserCode,$user_id)
{
    global $DB;
  
    $stmt =  $DB->prepare('SELECT user_id FROM u_user WHERE user_UserCode=? AND user_id!=?');
    $stmt->bindValue(1,$user_UserCode);
    $stmt->bindValue(2,$user_id);
    $stmt->execute();
    if($stmt->rowCount())
    {
        echo "Y";
    }
    else
    {
        echo "N";
    }
}

function checkUserEmailEdit($user_Email,$user_id)
{

    global $DB;
    $stmt =  $DB->prepare('SELECT user_id FROM u_user WHERE user_Email=? AND user_id!=?');
    $stmt->bindValue(1,$user_Email);
    $stmt->bindValue(2,$user_id);
    $stmt->execute();
    if($stmt->rowCount())
    {
        echo "Y";
    }
    else
    {
        echo "N";
    }
}

// last edited by justin 27/03/2017
function createEmployee($postArray,$filesArray)
{
    global $DB;

    $imgFile = $filesArray['user_PicPath']['name'];
    $tmp_dir = $filesArray['user_PicPath']['tmp_name'];
    $img_PicPath="";
    $img_direcotry="../uploads/employee_images/";
    $img_NewName=round(microtime(true) * 1000).$filesArray['user_PicPath']['name'];
    if($imgFile)
    {
        move_uploaded_file($tmp_dir, $img_direcotry.$img_NewName);
        $img_PicPath=$img_direcotry.$img_NewName;
    }   

    $stmt= $DB->prepare('INSERT INTO u_user ( user_UserCode, user_Email, user_PhoneNo, user_Password, user_FirstName, user_LastName, user_FatherName, user_Status, user_PicPath, user_DOB, user_Gender, user_TAddress, user_PAddress, user_DateTime, user_JoiningDateTime,user_Type) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,now(),?,?)');
    $stmt->bindValue(1,$postArray['user_UserCode']);
    $stmt->bindValue(2,$postArray['user_Email']);
    $stmt->bindValue(3,$postArray['user_PhoneNo']);
    $stmt->bindValue(4,$postArray['user_Password']);
    $stmt->bindValue(5,$postArray['user_FirstName']);
    $stmt->bindValue(6,$postArray['user_LastName']);
    $stmt->bindValue(7,$postArray['user_FatherName']);
    $stmt->bindValue(8,$postArray['user_Status']);
    $stmt->bindValue(9,$img_PicPath);
    $stmt->bindValue(10,$postArray['user_DOB']);
    $stmt->bindValue(11,$postArray['user_Gender']);
    $stmt->bindValue(12,$postArray['user_TAddress']);
    $stmt->bindValue(13,$postArray['user_PAddress']);
    $stmt->bindValue(14,$postArray['user_JoiningDateTime']);
    $stmt->bindValue(15,$postArray['user_Type']);
    
    if($stmt->execute())
    {
       	$user_id = $DB->lastInsertId();


       	/*********************************
       	*	Email Starts here
       	**********************************/
       		$subject = "Your account has been created."; 
		    $fields = array(); 
		    $fields{"sv2_email"} = "Email: ".$postArray['user_Email']; 
		    $fields{"sv2_password"} = "Password:".$postArray['user_Password']; 
		    $fields{"sv2_url"} = "URL: www.innotter.com/project/leaveManagementSystem/login.php"; 
		    $body = "Dear ".$postArray['user_FirstName'].",\n\nYour E-Leave account details as follows:\n"; 

		    foreach($fields as $a => $b)
		    {   
		        $body .= sprintf("%s\n",$b,$_REQUEST[$a]); 
		    }
		    
		    $body.="\nPlease remember to change your password  and don't share them to anyone.\n\n";
		    $body.="Please contact HR manager for further assistance if you fail to login. \n\n";
		    $body.="Thank you.\n";
		    $body.="Your Faithful,\nHR Manager";

		    sendMail($postArray['user_Email'], $subject, $body, 'Account Creation');
    
       	/*********************************
       	*	Email Ends here
       	**********************************/

       
       	if ($postArray['user_JoiningDateTime'] == '')
       	{
       		$joining_date = date("Y-m-d");
       	}
       	else
       	{
       		$joining_date = $postArray['user_JoiningDateTime'];
       	}
       	
       	/*********** Docs Uploading ************/
        foreach($filesArray["docs"]["tmp_name"] as $key=>$tmp_name)
        {
            $docName=$filesArray["docs"]["name"][$key];
            if(!empty($docName))
            {
                
                $docTempName=$filesArray['docs']['tmp_name'][$key];
                $docDirectory="../uploads/employee_docs/";
                $docNewName=round(microtime(true) * 1000).$docName;
                move_uploaded_file($docTempName, $docDirectory.$docNewName);
                $stmt = $DB->prepare('INSERT INTO u_doclist (doc_id,user_id,udc_Path) VALUES (?,?,?)');
                $stmt->bindValue(1,$key);
                $stmt->bindValue(2,$user_id);
                $stmt->bindValue(3,$docDirectory.$docNewName);
                $stmt->execute();
            }
        }
       
        /*********** Add User Company ************/
        $stmt = $DB->prepare('	INSERT INTO u_usercompany (user_id, group_id, dept_id, desig_id, uc_ContractType, uc_JoiningDate, uc_Status) 
        						VALUES (?,?,?,?,?,?,?)');
        $stmt->bindValue(1,$user_id);
        $stmt->bindValue(2,$postArray['group_id']);
        $stmt->bindValue(3,$postArray['dept_id']);
        $stmt->bindValue(4,$postArray['desig_id']);
        $stmt->bindValue(5,$postArray['contract_type']);
        $stmt->bindValue(6,$joining_date);
        $stmt->bindValue(7,$postArray['user_Status']);
		$stmt->execute();
       
        /********** Add User Job History ***********/
        if ($postArray['contract_type'] == 'C')
        {
        	$contract_duration = $postArray['contract_duration'];
        	$expiry_date = date('Y-m-d',strtotime('+'.$contract_duration.' years',strtotime($joining_date)));
        }
        else
        {
        	$contract_duration = null;
        	$expiry_date = null;
        }
       
        $stmt = $DB->prepare('	INSERT INTO u_userjobhistory (uh_user_id, uh_group, uh_dept, 
        						uh_desig, uh_contract_type,uh_contract_duration,
        						uh_effective_date,uh_expiry_date,uh_status)
        						VALUES (?,?,?,?,?,?,?,?,?)');
        $stmt->bindValue(1, $user_id);
        $stmt->bindValue(2, $postArray['group_id']);
        $stmt->bindValue(3, $postArray['dept_id']);
        $stmt->bindValue(4, $postArray['desig_id']);
        $stmt->bindValue(5, $postArray['contract_type']);
        $stmt->bindValue(6, $contract_duration);
        $stmt->bindValue(7, $joining_date);
        $stmt->bindValue(8, $expiry_date);
        $stmt->bindValue(9, $postArray['user_Status']);
        $stmt->execute();
        
        
        /********* Add User Leaves **********/
        
        $leaveList = getLeaveTypeList();
        $ol_Year = date('Y');
        $currentMonth = date('m');
        $currentYear = date('Y');
        $fromDate   = date('Y-m-d',strtotime(date('Y-01-01')));
        $toDate   = date('Y-m-d',strtotime(date('Y-12-31')));
        
        for ($i=0; $i <count($leaveList) ; $i++) 
        { 
        	$lt_id = $leaveList[$i]['lt_id'];
        	
            if($leaveList[$i]['lt_Annual']!='Y')
            {
            	/********** Add User Other Leaves **********/
            	
            	$expiryDate = $toDate;
                if(isset($postArray['userleave'][$lt_id])){
            	$dl_Number = $postArray['userleave'][$lt_id];
            	$is_annual_leave = 'N';
            	
            	$stmtDL = $DB->prepare('INSERT INTO u_userleave (lt_id,ul_Number,ul_RemainingNumber,
            							ul_Year,ul_DateTime,ul_Status,ul_Annual,ul_FromDate,
            							ul_ToDate,ul_ExpiryDate,user_id) 
            							VALUES(?,?,?,?,now(),?,?,?,?,?,?)');
            	$stmtDL->bindValue(1,$lt_id);
            	$stmtDL->bindValue(2,$dl_Number);
            	$stmtDL->bindValue(3,$dl_Number);
            	$stmtDL->bindValue(4,$currentYear);
            	$stmtDL->bindValue(5,'A');
            	$stmtDL->bindValue(6,$is_annual_leave);
            	$stmtDL->bindValue(7,$fromDate);
            	$stmtDL->bindValue(8,$toDate);
            	$stmtDL->bindValue(9,$expiryDate);
            	$stmtDL->bindValue(10,$user_id);
            	$stmtDL->execute();
            	}
                /*
                $stmtL =  $DB->prepare('SELECT ol_Number FROM cnf_otherleave WHERE lt_id=? AND ol_Year=?');
                $stmtL->bindParam(1,$leaveList[$i]['lt_id']);
                $stmtL->bindParam(2,$ol_Year);
                $stmtL->execute();
                
                if($stmtL->rowCount()>0)
                {
	                $dlRow = $stmtL->fetch(PDO::FETCH_ASSOC);
	                $ol_Number = $dlRow['ol_Number'];
	                $expiryDate = date('Y-m-d',strtotime(date('Y-12-31')));
	                $dl_Number = ceil(($dlRow['ol_Number']/12)*($currentMonth-12));
	                $ul_Annual='N'; 
	
	                $stmtDL = $DB->prepare('INSERT INTO u_userleave (lt_id,ul_Number,ul_RemainingNumber,ul_Year,ul_DateTime,ul_Status,ul_Annual,ul_FromDate,ul_ToDate,ul_ExpiryDate,user_id) VALUES(?,?,?,now(),now(),?,?,?,?,?,?)');
	                $stmtDL->bindValue(1,$leaveList[$i]['lt_id']);
	                $stmtDL->bindValue(2,$dlRow['ol_Number']);
	                $stmtDL->bindValue(3,$dlRow['ol_Number']);
	                $stmtDL->bindValue(4,'A');
	                $stmtDL->bindValue(5,$ul_Annual);
	                $stmtDL->bindValue(6,$fromDate);
	                $stmtDL->bindValue(7,$toDate);
	                $stmtDL->bindValue(8,$expiryDate);
	                $stmtDL->bindValue(9,$user_id);
	                $stmtDL->execute();
	        	}
	        	*/
	    	}
	    	else 
	    	{
	    		/*********** Add User Annual Leave ***********/
	    		 
	    		$desig_id = $postArray['desig_id'];
	    		$assigned_AL = $postArray['user_annualleave'];
	    		$is_annual_leave = 'Y';
	    		$contract_type = $postArray['contract_type'];
	    		$contract_duration = $postArray['contract_duration'];
	    		
	    		if ($contract_type == 'C')
	    		{
	    			$expiry_date = date('Y-m-d',strtotime('+'.$contract_duration.' years',strtotime($joining_date)));
	    		}
	    		else
	    		{
	    			$expiry_date = date('Y-m-d',strtotime('+2 years',strtotime(date('Y-12-31'))));
	    		}
	    		
	    		$stmtL = $DB->prepare(' SELECT * FROM cnf_desigleave WHERE desig_id=? AND dl_Year=?');
	    		$stmtL->bindParam(1,$desig_id);
	    		$stmtL->bindParam(2,$currentYear);
	    		$stmtL->execute();
	    			
	    		$stmtDL = $DB->prepare('INSERT INTO u_userleave (lt_id,ul_Number,ul_RemainingNumber,
								ul_Year,ul_DateTime,ul_Status,ul_Annual,ul_FromDate,ul_ToDate,
								ul_ExpiryDate,user_id)
								VALUES(?,?,?,?,now(),?,?,?,?,?,?)');
	    		$stmtDL->bindValue(1,$lt_id);
	    		$stmtDL->bindValue(2,$assigned_AL);
	    		$stmtDL->bindValue(3,$assigned_AL);
	    		$stmtDL->bindValue(4,$currentYear);
	    		$stmtDL->bindValue(5,'A');
	    		$stmtDL->bindValue(6,$is_annual_leave);
	    		$stmtDL->bindValue(7,$fromDate);
	    		$stmtDL->bindValue(8,$toDate);
	    		$stmtDL->bindValue(9,$expiry_date);
	    		$stmtDL->bindValue(10,$user_id);
	    		$stmtDL->execute();
	    	}
	   	}

	   	
		
		/*
		 * ONLY apply this if auto-assign without alteration of the default leave values
		 * together with previous year leaves
		 * 
        $stmtL = $DB->prepare(' SELECT dl_id,desig_id,lt_id,dl_Number,dl_Year,dl_ExpiryDate 
        						FROM cnf_desigleave WHERE desig_id=?');
        $stmtL->bindParam(1,$desig_id);
        //$stmtL->bindParam(2,$ol_Year);
        if($stmtL->execute())
        {
            $currentMonth = date('m');
            $currentYear = date('Y');
            $fromDate   = date('Y-m-d',strtotime(date('Y-01-01')));
            $toDate   = date('Y-m-d',strtotime(date('Y-12-31')));
            
            while($dlRow = $stmtL->fetch(PDO::FETCH_ASSOC))
            {
            	$year = $dlRow['dl_Year'];
                // print_r($dlRow);
                $expiryDate = date('Y-m-d',strtotime(date('Y-12-31')));
                $dl_Number = ceil(($dlRow['dl_Number']/12)*($currentMonth-12));
                if($dlRow['lt_id']==1){ 
                	$fromDate   = date('Y-m-d',strtotime(date($year.'-01-01')));
            		$toDate   = date('Y-m-d',strtotime(date($year.'-12-31')));
                    //$expiryDate = date('Y-m-d',strtotime('+2 years',strtotime($expiryDate)));
                    $expiryDate = date('Y-m-d',strtotime('+2 years',strtotime(date($year.'-12-31'))));
                    $ul_Annual='Y';
                }
                else{ 
                    $ul_Annual='N'; 
                }

                //$stmtDL = $DB->prepare('INSERT INTO u_userleave (lt_id,ul_Number,ul_RemainingNumber,ul_Year,ul_DateTime,ul_Status,ul_Annual,ul_FromDate,ul_ToDate,ul_ExpiryDate,user_id) VALUES(?,?,?,now(),now(),?,?,?,?,?,?)');
                $stmtDL = $DB->prepare('INSERT INTO u_userleave (lt_id,ul_Number,ul_RemainingNumber,ul_Year,ul_DateTime,ul_Status,ul_Annual,ul_FromDate,ul_ToDate,ul_ExpiryDate,user_id) VALUES(?,?,?,?,now(),?,?,?,?,?,?)');
                $stmtDL->bindValue(1,$dlRow['lt_id']);
                $stmtDL->bindValue(2,$dlRow['dl_Number']);
                $stmtDL->bindValue(3,$dlRow['dl_Number']);
                $stmtDL->bindValue(4,$dlRow['dl_Year']);
                $stmtDL->bindValue(5,'A');
                $stmtDL->bindValue(6,$ul_Annual);
                $stmtDL->bindValue(7,$fromDate);
                $stmtDL->bindValue(8,$toDate);
                $stmtDL->bindValue(9,$expiryDate);
                $stmtDL->bindValue(10,$user_id);
                $stmtDL->execute();
            }
        }
		*/
		
        /*******END OF USER LEAVE ADD ***********/
        echo $user_id;
    }
    else
    {
        echo "";
    }
    
}

function viewEmployee($user_id)
{
    global $DB;

    $stmt = $DB->prepare('SELECT * FROM u_user WHERE user_id=?');
    $stmt->bindParam(1,$user_id);
    $stmt->execute();
    $row=$stmt->fetch(PDO::FETCH_ASSOC);
    if($row['user_Status']=='A'){ $user_Status="Active";} else{ $user_Status="In-Active"; }
    if($row['user_Gender']=='M'){ $user_Gender="Male"; }else{ $user_Gender="Female";}
    $DATA='<div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 ><span class="glyphicon glyphicon-book"></span> Employee</h4>
        </div>
        <div class="modal-body">
          <div class="row">
        <form class="form-horizontal" id="create-employee-form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
        <div class="col-sm-6">
        <div class="panel panel-primary">
          <div class="panel-heading">
                <div class="box-header">
                      <h6 class="box-title"><i class="fa fa-pencil"></i>&nbsp;Personal Details</h6>
                </div>
          </div>
          <div class="panel-body">
        <div class="box box-primary">
       
            <div class="box-body">
            <div class="form-group">
                <label for="group_name" class="col-sm-3 control-label"><small>Photo</small></label>
                
                <div>
                    <img src="'.$row['user_PicPath'].'" style="height:100px;width:200px">
                </div>
            </div>
            <div class="form-group">
                <label for="group_name" class="col-sm-3 control-label"><small>First Name:<span class="text-danger"> </span></small></label>
                <div class="col-sm-9">
                   '.$row['user_FirstName'].'                
                </div>
            </div>
            <div class="form-group">
                <label for="group_name" class="col-sm-3 control-label"><small>Last Name:<span class="text-danger"> </span></small></label>
                <div class="col-sm-9">
                  '.$row['user_LastName'].'
                </div>
            </div>
            <div class="form-group">
                <label for="group_name" class="col-sm-3 control-label"><small>Father Name:</small></label>
                <div class="col-sm-9">
                   '.$row['user_FatherName'].'            
                </div>
            </div>
            <div class="form-group">
                <label for="group_name" class="col-sm-3 control-label"><small>Date of Birth:</small></label>
                <div class="col-sm-9">                                       
                   '.$row['user_DOB'].'                 
                </div>
            </div>
            <div class="form-group">
                <label for="group_name" class="col-sm-3 control-label"><small>Gender:</small></label>
                <div class="col-sm-9">
                   '.$user_Gender.'               
                 </div>
            </div>
            <div class="form-group">
                <label for="group_name" class="col-sm-3 control-label"><small>Phone (Temporary):</small></label>
                <div class="col-sm-9">
                   '.$row['user_PhoneNo'].'               
                 </div>
            </div>
         
            <div class="form-group">
                <label for="group_name" class="col-sm-3 control-label"><small>Local Address:</small></label>
                <div class="col-sm-9">
                   '.$row['user_TAddress'].'               
                 </div>
            </div>
            <div class="form-group">
                <label for="group_name" class="col-sm-3 control-label"><small>Permanent Address:</small></label>
                <div class="col-sm-9">
                   '.$row['user_PAddress'].'                
                 </div>
            </div>
            <div class="form-group">
                <div class="col-sm-12">
                    <label for="group_name" class="control-label"><h3>Account Login</h3></label>
                </div>
            </div>
            
            <div class="form-group">
                <label for="group_name" class="col-sm-3 control-label"><small>E-Mail:<span class="text-danger"> </span></small></label>
                <div class="col-sm-9">
                   '.$row['user_Email'].'             
                </div>
            </div>
            <div class="form-group">
                <label for="group_name" class="col-sm-3 control-label"><small>Password:<span class="text-danger"> </span></small></label>
                <div class="col-sm-9">
                                 
                </div>
            </div>
            
                </div><!--End of box-body-->
               </div><!--End of box-primary-->
            </div><!--End of panel body-->
            </div><!--End of panel-->
           </div><!--End of col-sm-6-->'; 
    
    $stmt = $DB->prepare('SELECT UC.uc_id, UC.user_id, UC.dept_id, UC.desig_id, UC.uc_JoiningDate, UC.uc_ExitDate, UC.uc_Status,cnf_dept.dept_Name,cnf_designation.desig_Name,cnf_group.group_Name FROM u_usercompany AS UC INNER JOIN cnf_dept ON cnf_dept.dept_id=UC.dept_id INNER JOIN cnf_designation ON cnf_designation.desig_id=UC.desig_id INNER JOIN cnf_group ON cnf_group.group_id=UC.group_id WHERE user_id=? ORDER BY UC.uc_id DESC');
    $stmt->bindParam(1,$user_id);
    $stmt->execute();
    $ucRow=$stmt->fetch(PDO::FETCH_ASSOC);

    if($ucRow['uc_Status']=='A'){ $uc_Status="Active";} else{ $uc_Status="In-Active"; }
    $DATA.='       
        <div class="col-sm-6">

        <div class="panel panel-info">
          <div class="panel-heading">
                <div class="box-header">
                      <h6 class="box-title"><i class="fa fa-briefcase"></i>&nbsp;Company Details</h6>
                </div>
          </div>
          <div class="panel-body">
            <div class="box box-danger">
                    
                <div class="box-body">
                <div class="form-group">
                    <label for="group_name" class="col-sm-3 control-label"><small>Employee ID:<span class="text-danger"> </span></small></label>
                    <div class="col-sm-9">
                       '.$row['user_UserCode'].'
                       <div id="user_UserCodeCheck"></div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="group_name" class="col-sm-3 control-label"><small>Group:</small></label>
                    <div class="col-sm-9">
                        '.$ucRow['group_Name'].'
                    </div>
                </div>

                <div class="form-group">
                    <label for="group_name" class="col-sm-3 control-label"><small>Department:</small></label>
                    <div class="col-sm-9">
                        '.$ucRow['dept_Name'].'
                    </div>
                </div>
                <div class="form-group">
                    <label for="group_name" class="col-sm-3 control-label"><small>Designation:</small></label>
                    <div class="col-sm-9">
                        '.$ucRow['desig_Name'].'        
                    </div>
                </div>
                <div class="form-group">
                    <label for="group_name" class="col-sm-3 control-label"><small>Joining Date:<span class="text-danger"> </span></small></label>
                    <div class="col-sm-9">                    
                        '.$ucRow['uc_JoiningDate'].'               
                    </div>
                </div>
                <div class="form-group">
                    <label for="group_name" class="col-sm-3 control-label"><small>Exit Date:<span class="text-danger"> </span></small></label>
                    <div class="col-sm-9">
                       '.$ucRow['uc_ExitDate'].'     
                    </div>
                </div>
                <div class="form-group">
                    <label for="group_name" class="col-sm-3 control-label"><small>Status:<span class="text-danger"> </span></small></label>
                    <div class="col-sm-9">
                       '.$uc_Status.'     
                    </div>
                </div>
                </div><!--End of box-body-->
            </div><!--End of box-danger-->
        </div><!--End of panel body-->
        </div><!--End of panel-->

         <div class="panel panel-success">
          <div class="panel-heading">
                <div class="box-header">
                      <h6 class="box-title"><i class="fa fa-file"></i>&nbsp;Documents</h6>
                </div>
          </div>
          <div class="panel-body">
                <div class="box box-danger">
                   <div class="box-header">
                          <h3 class="box-title"><i class="fa fa-file"></i>&nbsp;Documents</h3>
                        </div>
                        <div class="box-body">
                        <div class="form-group row">
                                    <stong for="group_name" class="col-sm-3 "><small>Documents</small></stong>
                                    <div class="col-sm-9">
                                        Download
                                    </div>
                            </div>';
                $stmt = $DB->prepare('SELECT UDC.udc_id, UDC.doc_id, UDC.user_id, UDC.udc_Path, DOC.doc_Name,DOC.doc_Type FROM u_doclist AS UDC
                            INNER JOIN cnf_document AS DOC ON DOC.doc_id=UDC.doc_id
                             WHERE user_id=?');
                        $stmt->bindParam(1,$user_id);
                        $stmt->execute();
                        while($docRow=$stmt->fetch(PDO::FETCH_ASSOC))
                        { 
                            $DATA.='
                             <div class="form-group row">
                                    <span for="group_name" class="col-sm-3 "><small>'.$docRow['doc_Name'].'</small></span>
                                    <div class="col-sm-9">
                                                      
                                    ';
                            if($docRow['doc_Type']=='F')
                            {
                                $DATA.='

                                <a href="'.$docRow['udc_Path'].'" target="_blank">Download</a>';
                            }
                            elseif($docRow['doc_Type']=='I')
                            {
                                $DATA.='<a href="'.$docRow['udc_Path'].'" target="_blank"><img src="'.$docRow['udc_Path'].'" style="height:60px;width:100px"></a> ';
                            }

                            $DATA.='</div>
                                </div>';
                        }
                        
                        $DATA.='
                                 </div>   

                </div><!--End of box-danger-->
        </div><!--End of panel body-->
        </div><!--End of panel-->
        </div><!--End of col-sm-6-->
        ';
       $DATA.='<div class="col-sm-12">
                                <div class="panel panel-warning">
          <div class="panel-heading">
                <div class="box-header">
                      <h6 class="box-title"><i class="fa fa-file"></i>&nbsp;Jobs</h6>
                </div>
          </div>
          <div class="panel-body">
                <div class="box box-danger">
                        <div class="box-header">
                            
                        </div>
                        <div class="box-body">';
                         
                        $sqlSelectJobHistory = "SELECT * FROM u_userjobhistory AS UH
                                    INNER JOIN cnf_group AS GP ON GP.group_id = UH.uh_group
                                    INNER JOIN cnf_dept AS DP ON DP.dept_id = UH.uh_dept
                                    INNER JOIN cnf_designation AS DE ON DE.desig_id = UH.uh_desig
                                    WHERE UH.uh_user_id = ?";
                        $stmt = $DB->prepare($sqlSelectJobHistory);
                        $stmt->bindValue(1, $user_id);
                        $stmt->execute();
                        
                        $DATA.='<div class="table-responsive" >
                                <table class="table" id="addNewEmployeeJobTable">
                        <thead>
                            <tr>
                                <td>Effective</td>
                                <td>Group</td>
                                <td>Department</td>
                                <td>Designation</td>
                                <td>Contract Type</td>
                                <td>Status</td>
                                
                            </tr>
                        </thead>
                        <tbody>
                            <tr id="addNewEmployeeJobRow" style="display:none">
                                <td class="effective_date"></td>
                                <td class="groupName"></td>
                                <td class="deptName"></td>
                                <td class="desigName"></td>
                                <td class="contractType"></td>
                                <td class="Status"></td>
                                
                                
                                ';
                        
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
                        {
                            $effective_date = $row['uh_effective_date'];
                            $group = $row['group_Name'];
                            $dept = $row['dept_Name'];
                            $desig = $row['desig_Name'];
                             
                            if ($row['uh_contract_type'] == "P")
                            {
                                $ct = "Permenant";
                            }
                            elseif ($row['uh_contract_type'] == "C")
                            {
                                $ct = "Contract";
                            }
                             
                            if ($row['uh_status'] == "A")
                            {
                                $status = "Active";
                            }
                            else
                            {
                                $status = "Inactive";
                            }
                             
                            $DATA.='    <tr id="jobrow'.$row['uh_id'].'">
                                <td class="effective_date">'.$effective_date.'</td>
                                <td class="groupName">'.$group.'</td>
                                <td class="deptName">'.$dept.'</td>
                                <td class="desigName">'.$desig.'</td>
                                <td class="contractType">'.$ct.'</td>
                                <td class="Status">'.$status.'</td>
                               
                            </tr>';
                        }
                        
                        $DATA.='    </tbody>
                    </table>
                    </div>
                    </div>
                        
                </div><!--End of box-danger-->
        </div><!--End of panel body-->
        </div><!--End of panel-->
        </div>';
                        
                        $DATA.='<div class="col-sm-12">
                                <div class="panel panel-success">
          <div class="panel-heading">
                <div class="box-header">
                      <h6 class="box-title"><i class="fa fa-file"></i>&nbsp;Leave Assignment by Year</h6>
                </div>
          </div>
          <div class="panel-body">
                <div class="box box-danger">
                   <div class="box-header">
                         
                        </div>
                        <div class="box-body">';
                         
                        $sqlSelectUserLeave = " SELECT * FROM u_userleave AS UL
                                                INNER JOIN cnf_leavetype AS LT ON LT.lt_id = UL.lt_id
                                                WHERE UL.user_id = ? AND UL.ul_Annual = ?";
                        $stmt = $DB->prepare($sqlSelectUserLeave);
                        $stmt->bindValue(1, $user_id);
                        $stmt->bindValue(2, 'Y');
                        $stmt->execute();
                        
                        $DATA.="<p><h3><u>Annual Leaves</u></h3></p>
                                <table class='table table-bordered' id='AnnualLeaveTable'>
                                    <thead>
                                        <tr id=''>
                                            <td class='leaveYear'>Year</td>
                                            <td class='lt_Name'>Type</td>
                                            <td class='leaveNumber'>Leave Balance</td>
                                            <td class='leaveExpiryDate'>Expired On</td>
                                            <td class='leaveStatus'>Status</td>
                                            
                                            
                                       
                                        </tr>
                                    </thead>
                                    <tbody>";
                        
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
                        {
                            $year = $row['ul_Year'];
                            $leave_type = $row['lt_Name'];
                            $remaining_leave = $row['ul_RemainingNumber'];
                            $expiry_date = $row['ul_ExpiryDate'];
                            $ul_id = $row['ul_id'];
                            
                            if ($row['ul_Status'] == "A")
                            {
                                $status = "Active";
                            }
                            else
                            {
                                $status = "Inactive";
                            }
                            
                            $DATA.="    <tr id='ULrow".$ul_id."'>
                                            <td>$year</td>
                                            <td>$leave_type</td>
                                            <td class='Year".$year."UL".$ul_id."'>$remaining_leave</td>
                                            <td>$expiry_date</td>
                                            <td>$status</td>
                                           
                                            
                                        </tr>";
                        }
                        
                        $DATA.='    </tbody>
                                </table>';
                        
                        $sqlSelectUserLeave = " SELECT * FROM u_userleave AS UL
                                                INNER JOIN cnf_leavetype AS LT ON LT.lt_id = UL.lt_id
                                                WHERE UL.user_id = ? AND UL.ul_Annual = ?";
                        $stmt = $DB->prepare($sqlSelectUserLeave);
                        $stmt->bindValue(1, $user_id);
                        $stmt->bindValue(2, 'N');
                        $stmt->execute();
                        
                        $DATA.="<hr><p><h3><u>Other Leaves</u></h3></p>
                                <table class='table table-bordered' id='OtherLeaveTable'>
                                    <thead>
                                        <tr>
                                            <td class='leaveYear'>Year</td>
                                            <td class='lt_Name'>Type</td>
                                            <td class='leaveNumber'>Leave Balance</td>
                                            <td class='leaveExpiryDate'>Expired On</td>
                                            <td class='leaveStatus'>Status</td>
                                            
                                        </tr>
                                    </thead>
                                    <tbody>";
                        
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
                        {
                            $year = $row['ul_Year'];
                            $leave_type = $row['lt_Name'];
                            $remaining_leave = $row['ul_RemainingNumber'];
                            $expiry_date = $row['ul_ExpiryDate'];
                            $ul_id = $row['ul_id'];
                             
                            if ($row['ul_Status'] == "A")
                            {
                                $status = "Active";
                            }
                            else
                            {
                                $status = "Inactive";
                            }
                            
                            $DATA.="    <tr id='ULrow".$ul_id."'>
                                            <td>$year</td>
                                            <td>$leave_type</td>
                                            <td class='Year".$year."UL".$ul_id."'>$remaining_leave</td>
                                            <td>$expiry_date</td>
                                            <td>$status</td>
                                            
                                            
                                        </tr>";
                        }
                        
                        $DATA.='    </tbody>
                                </table>';
                        
                        $DATA.='</div>
                        
                </div><!--End of box-danger-->
        </div><!--End of panel body-->
        </div><!--End of panel-->
        </div>';
     $DATA.='</form> </div>   

        </div>
        <div class="modal-footer clearfix">
          <button  class="btn btn-default btn-default " data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Cancel</button>
          
        </div>';





echo $DATA;

}

// last edited by justin: edit the way to show the designation,department,group and leaves
// edit employee profile
function editGetEmployee($user_id)
{
    global $DB;
    $stmt = $DB->prepare('SELECT * FROM u_user WHERE user_id=?');
    $stmt->bindParam(1,$user_id);
    $stmt->execute();
    $row=$stmt->fetch(PDO::FETCH_ASSOC);
/*

<div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 ><span class="glyphicon glyphicon-book"></span> Employee</h4>
        </div>
        <div class="modal-body">

        </div>
*/
    $DATA='
        <form class="form-horizontal" onsubmit="updateEmployee()"" id="update-employee-form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
        <div class="col-sm-6">
        <div class="panel panel-primary">
          <div class="panel-heading">
                <div class="box-header">
                      <h6 class="box-title"><i class="fa fa-pencil"></i>&nbsp;Personal Details</h6>
                </div>
          </div>
          <input type="hidden" name="u_id" value="'.$user_id.'">
          <div class="panel-body">
        <div class="box box-primary">
       
            <div class="box-body">
            <div class="form-group">
                <label for="group_name" class="col-sm-3 control-label"><small>Photo</small></label>
                <div class="col-sm-9">
                   <input type="file" name="user_PicPath" onchange="readURL(this)">                
                </div>
                <div>
                    <img src="" style="max-height: 120px;max-width: 190px" id="img">
                </div>
            </div>
            <div class="form-group">
                <label for="group_name" class="col-sm-3 control-label"><small>First Name<span class="text-danger"> *</span></small></label>
                <div class="col-sm-9">
                   <input class="form-control" name="user_FirstName" id="userFirstName" value="'.$row['user_FirstName'].'">                
                </div>
            </div>
            <div class="form-group">
                <label for="group_name" class="col-sm-3 control-label"><small>Last Name<span class="text-danger"> *</span></small></label>
                <div class="col-sm-9">
                   <input class="form-control" name="user_LastName" id="userLastName" value="'.$row['user_LastName'].'">                
                </div>
            </div>
            <div class="form-group">
                <label for="group_name" class="col-sm-3 control-label"><small>Father Name</small></label>
                <div class="col-sm-9">
                   <input class="form-control" name="user_FatherName" id="userFatherName" value="'.$row['user_FatherName'].'">             
                </div>
            </div>
            <div class="form-group">
                <label for="group_name" class="col-sm-3 control-label"><small>Date of Birth</small></label>
                <div class="col-sm-9">                                       
                    <input type="date" class="form-control" name="user_DOB" id="userDOB" value="'.$row['user_DOB'].'">                  
                </div>
            </div>
            <div class="form-group">
                <label for="group_name" class="col-sm-3 control-label"><small>Gender</small></label>
                <div class="col-sm-9">
                   <select class="form-control" name="user_Gender" id="userGender">
                    <option value="M"';
                    if($row['user_Gender']=='M'){ $DATA.='selected="selected"'; }
                    $DATA.='>Male</option>
                    <option value="F"';
                    if($row['user_Gender']=='F'){ $DATA.='selected="selected"'; }
                    $DATA.='>Female</option>
                    </select>               
                 </div>
            </div>
            <div class="form-group">
                <label for="group_name" class="col-sm-3 control-label"><small>Phone (Temporary)</small></label>
                <div class="col-sm-9">
                   <input type="text" class="form-control" name="user_PhoneNo" id="userPhoneNo" value="'.$row['user_PhoneNo'].'">                
                 </div>
            </div>
         
            <div class="form-group">
                <label for="group_name" class="col-sm-3 control-label"><small>Local Address</small></label>
                <div class="col-sm-9">
                   <textarea  class="form-control" placeholder="Temporary Address" cols="30" rows="6" name="user_TAddress" id="userTAddress">'.$row['user_TAddress'].'</textarea>               
                 </div>
            </div>
            <div class="form-group">
                <label for="group_name" class="col-sm-3 control-label"><small>Permanent Address</small></label>
                <div class="col-sm-9">
                   <textarea  class="form-control" placeholder="Permanent Address" cols="30" rows="6" name="user_PAddress" id="userPAddress">'.$row['user_PAddress'].'</textarea>                
                 </div>
            </div>
            <div class="form-group">
                <div class="col-sm-12">
                    <label for="group_name" class="control-label"><h3>Account Login</h3></label>
                </div>
            </div>
            
            <div class="form-group">
                <label for="group_name" class="col-sm-3 control-label"><small>E-Mail<span class="text-danger"> *</span></small></label>
                <div class="col-sm-9">
                   <input type="email" class="form-control" name="user_Email" id="userEmailEdit" onkeyup="checkUserEmailEdit('.$row['user_id'].')"  value="'.$row['user_Email'].'">   
                   <div id="user_EmailCheckEdit"></div>             
                </div>
            </div>
            <div class="form-group">
                <label for="group_name" class="col-sm-3 control-label"><small>Password<span class="text-danger"> *</span></small></label>
                <div class="col-sm-9">
                   <input type="password" class="form-control" name="user_Password" id="userPassword" value="'.$row['user_Password'].'">                
                </div>
            </div>
            <div class="form-group">
                <label for="group_name" class="col-sm-3 control-label"><small>User Type<span class="text-danger"> *</span></small></label>
                <div class="col-sm-9">
                   	<select class="form-control" name="user_Type" id="userType">
                    <option value="E"';
                    if($row['user_Type']=='E'){ $DATA.='selected="selected"'; }
                    $DATA.='>Employee</option>
                    <option value="M"';
                    if($row['user_Type']=='M'){ $DATA.='selected="selected"'; }
                    $DATA.='>Manager</option>
                    <option value="A"';
                    if($row['user_Type']=='A'){ $DATA.='selected="selected"'; }
                    $DATA.='>Admin</option>
                    </select>  
                </div>
            </div>
                </div><!--End of box-body-->
               </div><!--End of box-primary-->
            </div><!--End of panel body-->
            </div><!--End of panel-->
           </div><!--End of col-sm-6-->';
$stmt = $DB->prepare('SELECT UC.uc_id, UC.user_id, UC.dept_id, UC.desig_id, UC.uc_JoiningDate, UC.uc_ExitDate, UC.uc_Status,cnf_dept.dept_Name,cnf_designation.desig_Name,cnf_group.group_id FROM u_usercompany AS UC INNER JOIN cnf_dept ON cnf_dept.dept_id=UC.dept_id INNER JOIN cnf_designation ON cnf_designation.desig_id=UC.desig_id INNER JOIN cnf_group ON cnf_group.group_id=UC.group_id WHERE UC.user_id=? ORDER BY UC.uc_id DESC');
    $stmt->bindParam(1,$user_id);
    // $stmt->bindValue(2,'A');
    $stmt->execute();
    $ucRow=$stmt->fetch(PDO::FETCH_ASSOC);

           $DATA.='<div class="col-sm-6">

        <div class="panel panel-info">
          <div class="panel-heading">
                <div class="box-header">
                      <h6 class="box-title"><i class="fa fa-briefcase"></i>&nbsp;Company Details</h6>
                </div>
          </div>
          <div class="panel-body">
            <div class="box box-danger">
                    
                <div class="box-body">
                <div class="form-group">
                    <label for="group_name" class="col-sm-3 control-label"><small>Employee ID<span class="text-danger"> *</span></small></label>
                    <div class="col-sm-9">
                       <input type="text" class="form-control" placeholder="EMP-39283" name="user_UserCode" id="userUserCodeEdit" onkeyup="checkUserCodeEdit('.$row['user_id'].')" value="'.$row['user_UserCode'].'">
                       <div id="user_UserCodeCheckEdit"></div>
                    </div>
                </div>
           
                <!--<div class="form-group">
                    <label for="group_name" class="col-sm-3 control-label"><small>Group</small></label>
                    <div class="col-sm-9">
                    <select name="group_id" id="groupId" class="form-control">'; 
                        $groupList=getActiveGroup();
                        foreach ($groupList as $key => $groupArray) 
                        {
                            $DATA.='<option value="';
                            $DATA.=$groupArray['group_id'];
                            $DATA.='"';
                            if($groupArray['group_id']==$ucRow['group_id'])
                            {
                                $DATA.='selected="selected"';
                            }
                            $DATA.='>';
                            $DATA.=$groupArray['group_Name'];
                            $DATA.='</option>';
                        }
                        
                $DATA.='</select>                
                    </div>
                </div>
                <div class="form-group">
                    <label for="group_name" class="col-sm-3 control-label"><small>Department</small></label>
                    <div class="col-sm-9">
                    <select name="dept_id" id="deptId" class="form-control">'; 
                    $deptList=getActiveDepartments();
                    foreach ($deptList as $key => $deptArray) 
                    {
                        $DATA.='<option value="';
                        $DATA.=$deptArray['dept_id'];
                        $DATA.='"';
                        if($deptArray['dept_id']==$ucRow['dept_id'])
                        {
                            $DATA.='selected="selected"';
                        }
                        $DATA.='>';
                        $DATA.=$deptArray['dept_Name'];
                        $DATA.='</option>';
                    }
                    
            $DATA.='</select>                 
                    </div>
                </div>
                <div class="form-group">
                    <label for="group_name" class="col-sm-3 control-label"><small>Designation</small></label>
                    <div class="col-sm-9">
                     <select name="desig_id" id="desigId" class="form-control">';  
                        $desigList=getActiveDesignation();
                        foreach ($desigList as $key => $desigArray) 
                        {
                            
                            $DATA.='<option value="';
                            $DATA.=$desigArray['desig_id'];
                            $DATA.='"';
                            if($desigArray['desig_id']==$ucRow['desig_id'])
                            {
                                $DATA.=' selected="selected"';
                            }
                            $DATA.='>';
                            $DATA.=$desigArray['desig_Name']; 
                            $DATA.='</option>';
                            
                        }
                      
                       $DATA.='</select>                       
                    </div>
                </div>-->
                <div class="form-group">
                    <label for="group_name" class="col-sm-3 control-label"><small>Joining Date</small></label>
                    <div class="col-sm-9">                    
                        <input type="date" name="user_JoiningDateTime" id="userJoiningDateTime" class="form-control" value="'.date('Y-m-d',strtotime($ucRow['uc_JoiningDate'])).'">                
                    </div>
                </div>
                <div class="form-group">
                    <label for="group_name" class="col-sm-3 control-label"><small>Exit Date</small></label>
                    <div class="col-sm-9">                    
                        <input type="date" name="uc_ExitDate" id="uc_ExitDate" class="form-control" value="';
                        if(!empty($ucRow['uc_ExitDate'])){
                            $DATA.=date('Y-m-d',strtotime($ucRow['uc_ExitDate']));
                        }

                        $DATA.='">                
                    </div>
                </div>
                <div class="form-group">
                    <label for="group_name" class="col-sm-3 control-label"><small>Status</small></label>
                    <div class="col-sm-9">
                       <select class="form-control" name="user_Status" id="userStatus">
                        <option value="A" ';
                        if($ucRow['uc_Status']=='A'){ $DATA.='selected="selected"'; }
                        $DATA.='>Active</option>
                        <option value="I" ';
                        if($ucRow['uc_Status']=='I'){ $DATA.='selected="selected"'; }
                        $DATA.='>In-Active</option>
                   </select>       
                    </div>
                </div>
                 <!--       		
                <div class="form-group">
                    <label for="group_name" class="col-sm-3 control-label">Promote</label>
                    <div class="col-sm-9">
                        <input type="checkbox" value="P" name="promote" id="promote" class="form-control" style="margin-left:-130px" onchange="promoteCheck(this)">
                    </div>
                    <input type="hidden" name="uc_id" value="'.$ucRow['uc_id'].'">
                </div>-->
            <div id="ULDiv" style="display:none;">';
           
            

            $DATA.='</div>
                </div><!--End of box-body-->
            </div><!--End of box-danger-->
        </div><!--End of panel body-->
        </div><!--End of panel-->';
        
            
            
            
            
        $DATA.='<div class="panel panel-default">
          <div class="panel-heading">
                <div class="box-header">
                      <h6 class="box-title"><i class="fa fa-file"></i>&nbsp;Documents</h6>
                </div>
          </div>
          <div class="panel-body">
                <div class="box box-danger">
                   <div class="box-header">
                          <h3 class="box-title"><i class="fa fa-file"></i>&nbsp;Documents</h3>
                        </div>
                        <div class="box-body">';
                            $DATA.=' <div class="form-group row">
                                    <stong for="group_name" class="col-sm-3 "><small>Documents</small></stong>
                                    <div class="col-sm-9">
                                        Download
                                    </div>
                            </div>';
                $stmt = $DB->prepare('SELECT UDC.udc_id, UDC.doc_id, UDC.user_id, UDC.udc_Path, DOC.doc_Name,DOC.doc_Type FROM u_doclist AS UDC
                            INNER JOIN cnf_document AS DOC ON DOC.doc_id=UDC.doc_id
                             WHERE user_id=?');
                        $stmt->bindParam(1,$user_id);
                        $stmt->execute();
                        while($docRow=$stmt->fetch(PDO::FETCH_ASSOC))
                        { 
                            $DATA.='
                             <div class="form-group row">
                                    <span for="group_name" class="col-sm-3 "><small>'.$docRow['doc_Name'].'</small></span>
                                    <div class="col-sm-9">
                                                      
                                    ';
                            if($docRow['doc_Type']=='F')
                            {
                                $DATA.='

                                <a href="'.$docRow['udc_Path'].'" target="_blank">Download</a>';
                            }
                            elseif($docRow['doc_Type']=='I')
                            {
                                $DATA.='<a href="'.$docRow['udc_Path'].'" target="_blank"><img src="'.$docRow['udc_Path'].'" style="height:60px;width:100px"></a> ';
                            }

                            $DATA.='</div>
                                </div>';
                        }
                            $docList=getActiveDocuments();
                            foreach ($docList as $key => $docArray) 
                            {
                                
                               $DATA.='<div class="form-group">
                                    <label for="group_name" class="col-sm-3 control-label"><small>';
                                $DATA.=$docArray['doc_Name'];
                                $DATA.='</small></label>
                                    <div class="col-sm-9">
                                       <input type="file" name="docs[';
                                       $DATA.=$docArray['doc_id'];
                                       $DATA.=']" class="form-control">                 
                                    </div>
                                </div>';
                              
                            }
                        
                        $DATA.='</div>    

                </div><!--End of box-danger-->
        </div><!--End of panel body-->
        </div><!--End of panel-->
        </div><!--End of col-sm-6-->';

                        $DATA.='<div class="col-sm-12">
                        		<div class="panel panel-warning">
          <div class="panel-heading">
                <div class="box-header">
                      <h6 class="box-title"><i class="fa fa-file"></i>&nbsp;Jobs</h6>
                </div>
          </div>
          <div class="panel-body">
                <div class="box box-danger">
                   		<div class="box-header">
                          	<p><button type="button" class="btn btn-default"  data-toggle="modal" data-target = "#addNewEmployeeJobModal" onclick="setUserId('.$user_id.')" >Add New Job</button></p>
                        </div>
                        <div class="box-body">';
                         
                        $sqlSelectJobHistory = "SELECT * FROM u_userjobhistory AS UH
            						INNER JOIN cnf_group AS GP ON GP.group_id = UH.uh_group
            						INNER JOIN cnf_dept AS DP ON DP.dept_id = UH.uh_dept
            						INNER JOIN cnf_designation AS DE ON DE.desig_id = UH.uh_desig
            						WHERE UH.uh_user_id = ?";
                        $stmt = $DB->prepare($sqlSelectJobHistory);
                        $stmt->bindValue(1, $user_id);
                        $stmt->execute();
                        
                        $DATA.='<div class="table-responsive" >
                        		<table class="table" id="addNewEmployeeJobTable">
            			<thead>
            				<tr>
            					<td>Effective</td>
            					<td>Group</td>
            					<td>Department</td>
            					<td>Designation</td>
            					<td>Contract Type</td>
            					<td>Status</td>
            					<td>Actions</td>
            				</tr>
            			</thead>
            			<tbody>
                            <tr id="addNewEmployeeJobRow" style="display:none">
                                <td class="effective_date"></td>
                                <td class="groupName"></td>
                                <td class="deptName"></td>
                                <td class="desigName"></td>
                                <td class="contractType"></td>
                                <td class="Status"></td>
                                
                                <td>
                                    <p  class="btn btn-primary btn-xs edit" data-toggle="modal" data-target = "#addNewEmployeeJobModal" 
                                        onclick="setUserJobId(this)"><i class="fa fa-pencil" 
                                    ></i></p>
                                    <a href="javascript:(0)" class="btn btn-danger btn-xs delete" ><i class="fa fa-trash-o"></i></a>
                                </td>

                                ';
                        
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
                        {
                        	$effective_date = $row['uh_effective_date'];
                        	$group = $row['group_Name'];
                        	$dept = $row['dept_Name'];
                        	$desig = $row['desig_Name'];
                        	 
                        	if ($row['uh_contract_type'] == "P")
                        	{
                        		$ct = "Permenant";
                        	}
                        	elseif ($row['uh_contract_type'] == "C")
                        	{
                        		$ct = "Contract";
                        	}
                        	 
                        	if ($row['uh_status'] == "A")
                        	{
                        		$status = "Active";
                        	}
                        	else
                        	{
                        		$status = "Inactive";
                        	}
                        	 
                        	$DATA.='	<tr id="jobrow'.$row['uh_id'].'">
            					<td class="effective_date">'.$effective_date.'</td>
            					<td class="groupName">'.$group.'</td>
            					<td class="deptName">'.$dept.'</td>
            					<td class="desigName">'.$desig.'</td>
            					<td class="contractType">'.$ct.'</td>
            					<td class="Status">'.$status.'</td>
            					<td><p  
                                class="btn btn-primary btn-xs edit"
                                data-toggle="modal" data-target = "#addNewEmployeeJobModal" 
                                     onclick="setUserJobId(this)"
                                    dept_id ="'.$row['dept_id'].'"
                                    desig_id ="'.$row['desig_id'].'"
                                    group_id ="'.$row['group_id'].'"
                                    uh_contract_type ="'.$row['uh_contract_type'].'"
                                    uh_contract_duration ="'.$row['uh_contract_duration'].'"
                                    uh_id="'.$row['uh_id'].'"
                                    uh_effective_date="'.$row['uh_effective_date'].'"
                                ><i class="fa fa-pencil"
                                    
                                ></i></p>
                                ';
                                $uh_id= $row['uh_id'];
                                $stmtCheckJobLeaves = "SELECT ul_id FROM u_userleave WHERE ul_RemainingNumber!=ul_Number AND uh_id = $uh_id";
                                $stmtCheckJobLeaves = $DB->prepare($stmtCheckJobLeaves);
                                $stmtCheckJobLeaves->execute();
                                if($stmtCheckJobLeaves->rowCount() <= 0){

                                    $DATA.='
                                    <p  onclick="del('.$row['uh_id'].')" class="btn btn-danger btn-xs delete" ><i class="fa fa-trash-o"  ></i></p>';
                                }

                                

                                    $DATA.='

                                    </td>
            				</tr>';
                        }
                        
                        $DATA.='	</tbody>
            		</table>
            		</div>
                    </div>
                        
                </div><!--End of box-danger-->
        </div><!--End of panel body-->
        </div><!--End of panel-->
        </div>';
                        
                        $DATA.='<div class="col-sm-12">
                        		<div class="panel panel-success">
          <div class="panel-heading">
                <div class="box-header">
                      <h6 class="box-title"><i class="fa fa-file"></i>&nbsp;Leave Assignment by Year</h6>
                </div>
          </div>
          <div class="panel-body">
                <div class="box box-danger">
                   <div class="box-header">
                          <p type="button" class="btn btn-default" data-toggle="modal" data-target = "#EmployeeLeaveAdjustModal" onclick="setUserId('.$user_id.')">Adjust Leaves</p>
                        </div>
                        <div class="box-body">';
                         
                        $sqlSelectUserLeave = "	SELECT * FROM u_userleave AS UL
                        						INNER JOIN cnf_leavetype AS LT ON LT.lt_id = UL.lt_id
                        						WHERE UL.user_id = ? AND UL.ul_Annual = ? ORDER BY ul_Year DESC";
                        $stmt = $DB->prepare($sqlSelectUserLeave);
                        $stmt->bindValue(1, $user_id);
                        $stmt->bindValue(2, 'Y');
                        $stmt->execute();
                        
                        $DATA.="<p><h3><u>Annual Leaves</u></h3></p>
                        		<table class='table table-bordered' id='AnnualLeaveTable'>
                        			<thead>
	                        			<tr>
			            					<tr id=''>
                                            <td class=''>Year</td>
                                            <td class=''>Type</td>
                                            <td class=''>Leave Balance</td>
                                            <td class=''>Expired On</td>
                                            <td class=''>Status</td>
                                            <td>Adjust/Delete</td>
                                            
                                       
                                        </tr>
                                            
			            					
			            				</tr>
                        			</thead>
                        			<tbody>
                                        <tr>
                                            <tr id='copyLeaveRow' style='display:none'>
                                            <td class='leaveYear'></td>
                                            <td class='lt_Name'></td>
                                            <td class='leaveNumber'></td>
                                            <td class='leaveExpiryDate'></td>
                                            <td class='leaveStatus'></td>
                                            <td>
                                                <a href='javascript:(0)' 
                                                data-toggle='modal' 
                                                class='btn btn-primary btn-xs edit'><i class='fa fa-pencil'
                                                data-toggle='modal' data-target = '#SingleLeaveAdjustModal' 
                                                   
                                                     onclick='setULId(this)'
                                                     >
                                                </i></a>
                                                <a href='javascript:(0)' class='btn btn-danger btn-xs delete' ><i class='fa fa-trash-o'  ></i></a>
                                            </td>
                                            
                                       
                                        </tr>
                                    ";
                        
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
                        {
                        	$year = $row['ul_Year'];
                        	$leave_type = $row['lt_Name'];
                        	$remaining_leave = $row['ul_RemainingNumber'];
                            $expiry_date = $row['ul_ExpiryDate'];
                        	$ul_id = $row['ul_id'];
                        	
                        	if ($row['ul_Status'] == "A")
                        	{
                        		$status = "Active";
                        	}
                        	else
                        	{
                        		$status = "Inactive";
                        	}
                        	
                        	$DATA.="	<tr id='ULrow".$ul_id."'>
			            					<td>$year</td>
			            					<td>$leave_type</td>
			            					<td class='UL".$ul_id."'>$remaining_leave</td>
			            					<td>$expiry_date</td>
			            					<td>$status</td>
                                            <td>
                                                <p
                                                data-toggle='modal' data-target = '#SingleLeaveAdjustModal' 
                                                    ul_Number='".$remaining_leave."'
                                                    ul_id = '".$ul_id."'
                                                     onclick='setULId(this)'
                                                class='btn btn-primary btn-xs edit'><i class='fa fa-pencil'
                                                     >
                                                </i></p>";

                                             $stmtCheckLeaves = "SELECT ul_id FROM u_userleave WHERE ul_RemainingNumber!=ul_Number AND ul_id = '$ul_id'";
                                            $stmtCheckLeaves = $DB->prepare($stmtCheckLeaves);
                                            $stmtCheckLeaves->execute();
                                            if($stmtCheckLeaves->rowCount()<=0){

                                             $DATA.="<p href='#' onclick='delUL(".$row['ul_id'].")' class='btn btn-danger btn-xs delete' ><i class='fa fa-trash-o'  ></i></p>";
                                            }
                                                
                                             $DATA.="   

                                            </td>
			            					
			            				</tr>";
                        }
                        
                        $DATA.='	</tbody>
                        		</table>';
                        
                        $sqlSelectUserLeave = "	SELECT * FROM u_userleave AS UL
                        						INNER JOIN cnf_leavetype AS LT ON LT.lt_id = UL.lt_id
                        						WHERE UL.user_id = ? AND UL.ul_Annual = ? ORDER BY ul_Year DESC";
                        $stmt = $DB->prepare($sqlSelectUserLeave);
                        $stmt->bindValue(1, $user_id);
                        $stmt->bindValue(2, 'N');
                        $stmt->execute();
                        
                        $DATA.="<hr><p><h3><u>Other Leaves</u></h3></p>
                        		<table class='table table-bordered' id='OtherLeaveTable'>
                        			<thead>
	                        			<tr>
                                            <tr>
                                            <td class='leaveYear'>Year</td>
                                            <td class='lt_Name'>Type</td>
                                            <td class='leaveNumber'>Leave Balance</td>
                                            <td class='leaveExpiryDate'>Expired On</td>
                                            <td class='leaveStatus'>Status</td>
                                            <td>Adjust/Delete</td>
                                            
                                       
                                        </tr>
                        			</thead>
                        			<tbody>";
                        
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
                        {
                        	$year = $row['ul_Year'];
                        	$leave_type = $row['lt_Name'];
                        	$remaining_leave = $row['ul_RemainingNumber'];
                            $expiry_date = $row['ul_ExpiryDate'];
                        	$ul_id = $row['ul_id'];
                        	 
                        	if ($row['ul_Status'] == "A")
                        	{
                        		$status = "Active";
                        	}
                        	else
                        	{
                        		$status = "Inactive";
                        	}
                        	
                        	$DATA.="	<tr id='ULrow".$ul_id."'>
				                        	<td>$year</td>
				                        	<td>$leave_type</td>
				                        	<td class='UL".$ul_id."'>$remaining_leave</td>
				                        	<td>$expiry_date</td>
				                        	<td>$status</td>
                                            <td>
                                                
                                                <a href='javascript:(0)' 
                                                data-toggle='modal' data-target = '#SingleLeaveAdjustModal' 
                                                    ul_Number='".$remaining_leave."'
                                                    ul_id = '".$ul_id."'
                                                     onclick='setULId(this)' 
                                                class='btn btn-primary btn-xs edit'><i class='fa fa-pencil'
                                                    >
                                                </i></a>";

                                        $stmtCheckLeaves = "SELECT ul_id FROM u_userleave WHERE ul_RemainingNumber!=ul_Number AND ul_id = '$ul_id'";
                                            $stmtCheckLeaves = $DB->prepare($stmtCheckLeaves);
                                            $stmtCheckLeaves->execute();
                                            if($stmtCheckLeaves->rowCount()<=0){

                                             $DATA.="<p href='#' onclick='delUL(".$row['ul_id'].")' class='btn btn-danger btn-xs delete' ><i class='fa fa-trash-o'  ></i></p>";
                                            }
                                              $DATA.="
                                            </td>
				                        	
				                        </tr>";
                        }
                        
                        $DATA.='	</tbody>
                        		</table>';
                        
                        $DATA.='</div>
                        
                </div><!--End of box-danger-->
        </div><!--End of panel body-->
        </div><!--End of panel-->
        </div>';

    $DATA.='
            <input type="hidden" name="user_PicPathOld" value="'.$row['user_PicPath'].'">
        <div class="form-group text-left">
            <div class="col-sm-offset-2 col-sm-9">
                  
                <button type="submit" class="btn btn-default btn-success pull-right" id="update" ><span class="glyphicon glyphicon-floppy-saved"></span> Save</button>              
                
            </div>
        </div>
        </div></div>
        </form>
         ';

        echo $DATA;

}

function updateEmployee($postArray,$filesArray)
{
    global $DB;
                    
    $img_PicPath=$postArray['user_PicPathOld'];
    $imgFile = $filesArray['user_PicPath']['name'];
    $tmp_dir = $filesArray['user_PicPath']['tmp_name'];
    $img_direcotry="../uploads/employee_images/";
    $img_NewName=round(microtime(true) * 1000).$filesArray['user_PicPath']['name'];
    if($imgFile)
    {
        move_uploaded_file($tmp_dir, $img_direcotry.$img_NewName);
        $img_PicPath=$img_direcotry.$img_NewName;
    } 
    $stmt= $DB->prepare('UPDATE `u_user` SET user_UserCode=?,user_Email=?,user_PhoneNo=?,user_Password=?,user_FirstName=?,user_LastName=?,user_Status=?,user_PicPath=?,user_DOB=?,user_Gender=?,user_TAddress=?,user_PAddress=?,user_JoiningDateTime=?,user_ExitDateTime=?,user_FatherName=? , user_Type = ? WHERE user_id=? ');
    $stmt->bindValue(1,$postArray['user_UserCode']);
    $stmt->bindValue(2,$postArray['user_Email']);
    $stmt->bindValue(3,$postArray['user_PhoneNo']);
    $stmt->bindValue(4,$postArray['user_Password']);
    $stmt->bindValue(5,$postArray['user_FirstName']);
    $stmt->bindValue(6,$postArray['user_LastName']);
    $stmt->bindValue(7,$postArray['user_Status']);
    $stmt->bindValue(8,$img_PicPath);
    $stmt->bindValue(9,$postArray['user_DOB']);
    $stmt->bindValue(10,$postArray['user_Gender']);
    $stmt->bindValue(11,$postArray['user_TAddress']);
    $stmt->bindValue(12,$postArray['user_PAddress']);
    $stmt->bindValue(13,$postArray['user_JoiningDateTime']);
    $stmt->bindValue(14,$postArray['uc_ExitDate']);
    $stmt->bindValue(15,$postArray['user_FatherName']);
    $stmt->bindValue(16,$postArray['user_Type']);
    $stmt->bindValue(17,$postArray['u_id']);
    if($stmt->execute())
    {
        
        /**********USER COMPANY DETAIL UPDATE**********/
/*        if(!isset($postArray['promote'])) //If Employee is not promoted & Just updated the Details
        {
            $stmt=  $DB->prepare('UPDATE u_usercompany SET group_id=?,dept_id=?,desig_id=?,uc_JoiningDate=?,uc_ExitDate=?,uc_Status=? WHERE uc_id=?');
            $stmt->bindValue(1,$postArray['group_id']);
            $stmt->bindValue(2,$postArray['dept_id']);
            $stmt->bindValue(3,$postArray['desig_id']);
            $stmt->bindValue(4,$postArray['user_JoiningDateTime']);
            $stmt->bindValue(5,$postArray['uc_ExitDate']);
            $stmt->bindValue(6,$postArray['user_Status']);
            $stmt->bindValue(7,$postArray['uc_id']);
            if($stmt->execute())
            {
               
            }
        }
        else //If Employee is promoted and INSERTED new company Detail - By Doing In-Active the older
        {
            $stmt=  $DB->prepare('UPDATE u_usercompany SET uc_Status=? WHERE uc_id=?');
            $stmt->bindValue(1,'I');
            $stmt->bindValue(2,$postArray['uc_id']);
            if($stmt->execute())
            {
               $stmt = $DB->prepare('INSERT INTO u_usercompany (`user_id`,`group_id`, `dept_id`, `desig_id`, `uc_JoiningDate`,`uc_ExitDate`, `uc_Status`) VALUES (?,?,?,?,?,?,?)');
                $stmt->bindValue(1,$postArray['u_id']);
                $stmt->bindValue(2,$postArray['group_id']);
                $stmt->bindValue(3,$postArray['dept_id']);
                $stmt->bindValue(4,$postArray['desig_id']);
                $stmt->bindValue(5,$postArray['user_JoiningDateTime']);
                $stmt->bindValue(6,$postArray['uc_ExitDate']);
                $stmt->bindValue(7,$postArray['user_Status']);

                if($stmt->execute())
                {
                    $userleave = $postArray['userleave'];
                    foreach ($userleave as $ul_id => $ul_Number) 
                    {

                       //////////////////////// CHECK IF ANNUAL THEN ADD MORE IN REMAINING///////
                        $checkAnnualQ='SELECT lt_id,ul_Annual FROM u_userleave WHERE ul_id=?';
                        $checkAnnual = $DB->prepare($checkAnnualQ);
                        $checkAnnual->bindValue(1,$ul_id);
                        $checkAnnual->execute();
                        $checkAnnualRow=$checkAnnual->fetch(PDO::FETCH_ASSOC);
                       
                        if($checkAnnualRow['lt_id']==1)
                        {
                            $updateUL = $DB->prepare('UPDATE u_userleave SET ul_RemainingNumber=ul_RemainingNumber+(?-ul_Number) ,ul_Number=ul_Number+(?-ul_Number) WHERE ul_id=?');
                                $updateUL->bindValue(1,$ul_Number);
                                $updateUL->bindValue(2,$ul_Number);
                                $updateUL->bindValue(3,$ul_id);
                                $updateUL->execute();
                        }
                        ////////////////ELSE UPDATE THE USER LEAVE NUMBER///////////////
                        else
                        {
                            $updateUL = $DB->prepare('UPDATE u_userleave SET ul_Number=? WHERE ul_id=?');
                            $updateUL->bindValue(1,$ul_Number);
                            $updateUL->bindValue(2,$ul_id);
                            $updateUL->execute();
                        }
                    }
                }
            }

        }*/
        
        /**********END OF USER COMPANY DETAIL UPDATE**********/
         /*******Docs Uploading ***********/
        $stmt= $DB->prepare('SELECT udc_id,udc_Path,doc_id,user_id FROM u_doclist WHERE user_id=?');
        $stmt->bindValue(1,$postArray['u_id']);
        $stmt->execute();
        $docRow=array();
        while($docR=$stmt->fetch(PDO::FETCH_ASSOC))
        {
            $docRow[$docR['doc_id']]=$docR['doc_id'];
            $docPath[$docR['doc_id']]=$docR['udc_Path'];
        }

            foreach($filesArray["docs"]["tmp_name"] as $key=>$tmp_name)
            {
                $docName=$filesArray["docs"]["name"][$key];
                if(!empty($docName))
                {
          
                    $docTempName=$filesArray['docs']['tmp_name'][$key];
                    $docDirectory="../uploads/employee_docs/";
                    $docNewName=round(microtime(true) * 1000).$docName;
                    move_uploaded_file($docTempName, $docDirectory.$docNewName);
                    if(in_array($key, $docRow))
                    {
                        //echo $key;
                        unlink($docPath[$key]);
                        $stmt = $DB->prepare('UPDATE u_doclist SET udc_Path=? WHERE user_id=? AND doc_id=?');
                        $stmt->bindValue(1,$docDirectory.$docNewName);
                        $stmt->bindValue(2,$postArray['u_id']);
                        $stmt->bindValue(3,$key);
                        $stmt->execute();
                    }
                    else
                    {
                        //echo "insert";
                        $stmt = $DB->prepare('INSERT INTO u_doclist (doc_id,user_id,udc_Path) VALUES (?,?,?)');
                        $stmt->bindValue(1,$key);
                        $stmt->bindValue(2,$postArray['u_id']);
                        $stmt->bindValue(3,$docDirectory.$docNewName);
                        $stmt->execute();
                    }
                }
            }
            /*******End of Docs Uploading ***********/
        echo "UPDATED";
    }
    else
    {
        echo "";
    }

}

// last edited by justin 27/03/2017
function deleteEmployee($user_id)
{
    global $DB;
    $stmt = $DB->prepare('DELETE FROM u_user WHERE user_id=?');
    $stmt->bindParam(1,$user_id);
    if($stmt->execute())
    {
        $stmt2 = $DB->prepare('DELETE FROM u_usercompany WHERE user_id=?');
        $stmt2->bindParam(1,$user_id);
        if($stmt2->execute())
        {
            $stmt3 = $DB->prepare('DELETE FROM u_doclist WHERE user_id=?');
            $stmt3->bindParam(1,$user_id);
            if($stmt3->execute())
            {
                $stmt4 = $DB->prepare('DELETE FROM u_userleave WHERE user_id = ?');
                $stmt4->bindParam(1,$user_id);
                if($stmt4->execute())
                {
                    $stmt5 = $DB->prepare('DELETE FROM u_leaveapplication WHERE user_id = ?');
                    $stmt5->bindParam(1,$user_id);
                    if($stmt5->execute())
                    {
                        $stmt6 = $DB->prepare('DELETE FROM u_leavebatch WHERE user_id = ?');
                        $stmt6->bindParam(1,$user_id);
                        if($stmt6->execute())
                        {
                        	$stmt7 = $DB->prepare('DELETE FROM u_userjobhistory WHERE uh_user_id = ?');
                        	$stmt7->bindParam(1,$user_id);
                        	
                        	if($stmt7->execute())
                        	{
                            	echo "DELETED";
                        	}
                        }
                       
                    }
                   
                }
            }
        }
    } 
    else
    {
        echo "";
    }
}


// retrieve all employee
function getEmployeeList()
{
    global $DB;
    $stmt = $DB->prepare('SELECT u_user.* FROM u_user WHERE 1');
    $stmt->execute();
    $num_rows = $stmt->rowCount();
    if($num_rows<1)
    {
        $row = array();
    }
    else
    {
        while($r=$stmt->fetch(PDO::FETCH_ASSOC))
        {
            $row[]=$r;
        }
    }
   
    return $row;
}

function getEmployeeListDetail()
{
    //, u_usercompany.group_id, u_usercompany.dept_id FROM u_user LEFT JOIN u_usercompany ON u_user.user_id = u_usercompany.uh_user_id
    global $DB;
    $stmt = $DB->prepare('SELECT DISTINCT u_user.* , u_usercompany.group_id, u_usercompany.dept_id FROM u_user INNER JOIN u_usercompany ON u_user.user_id = u_usercompany.user_id  WHERE 1 AND u_usercompany.uc_Status ="A"');
    $stmt->execute();
    $num_rows = $stmt->rowCount();
    if($num_rows<1)
    {
        $row = array();
    }
    else
    {
        while($r=$stmt->fetch(PDO::FETCH_ASSOC))
        {
            $row[]=$r;
        }
    }
   
    return $row;
}


function getEmployeeListPDF()
{
    //, u_usercompany.group_id, u_usercompany.dept_id FROM u_user LEFT JOIN u_usercompany ON u_user.user_id = u_usercompany.uh_user_id
    global $DB;
    $stmt = $DB->prepare("SELECT  u_user.* , cnf_group.group_Name, cnf_dept.dept_Name, cnf_designation.desig_Name FROM u_user INNER JOIN u_usercompany ON u_user.user_id = u_usercompany.user_id  
        LEFT JOIN cnf_dept ON cnf_dept.dept_id = u_usercompany.dept_id
        LEFT JOIN cnf_designation ON cnf_designation.desig_id = u_usercompany.desig_id
        LEFT JOIN cnf_group ON cnf_group.group_id = u_usercompany.group_id
        WHERE u_usercompany.uc_Status = 'A'
        ");
    /*echo $abc = "SELECT  u_user.* , cnf_group.group_Name, cnf_dept.dept_Name, cnf_designation.desig_Name FROM u_user INNER JOIN u_usercompany ON u_user.user_id = u_usercompany.user_id  
        LEFT JOIN cnf_dept.dept_id = u_usercompany.dept_id
        LEFT JOIN cnf_designation.desig_id = u_usercompany.desig_id
        LEFT JOIN cnf_group.group_id = u_usercompany.group_id
        WHERE u_usercompany.uc_Status = 'A'";*/
    $stmt->execute();
    $num_rows = $stmt->rowCount();
    if($num_rows<1)
    {
        $row = array();
    }
    else
    {
        while($r=$stmt->fetch(PDO::FETCH_ASSOC))
        {
            $row[]=$r;
        }
    }
   
    return $row;
}
/*************End of Employee **************/

// get employee details by id
function getEmpPersonalDetail($user_id)
{
    global $DB;

    $stmt = $DB->prepare('SELECT * FROM u_user WHERE user_id=?');
    $stmt->bindParam(1,$user_id);
    $stmt->execute();
    $row=$stmt->fetch(PDO::FETCH_ASSOC);
    return $row;
}

function getEmpCompanyDetail($user_id)
{
    global $DB;
    $stmt = $DB->prepare('SELECT UC.uc_id, UC.user_id, UC.dept_id, UC.desig_id, UC.uc_JoiningDate, UC.uc_ExitDate, UC.uc_Status,cnf_dept.dept_Name,cnf_designation.desig_Name 
    						FROM u_usercompany AS UC INNER JOIN cnf_dept ON cnf_dept.dept_id=UC.dept_id 
    						INNER JOIN cnf_designation ON cnf_designation.desig_id=UC.desig_id WHERE user_id=?');
    $stmt->bindParam(1,$user_id);
    $stmt->execute();
    $ucRow=$stmt->fetch(PDO::FETCH_ASSOC);
    return $ucRow;
}

function getEmpDocumentDetail($user_id)
{
    global $DB;
    $stmt = $DB->prepare('SELECT UDC.udc_id, UDC.doc_id, UDC.user_id, UDC.udc_Path, DOC.doc_Name,DOC.doc_Type FROM u_doclist AS UDC
                            INNER JOIN cnf_document AS DOC ON DOC.doc_id=UDC.doc_id
                             WHERE user_id=?');
                        $stmt->bindParam(1,$user_id);
                        $stmt->execute();
                        while($docRow=$stmt->fetch(PDO::FETCH_ASSOC))
                        {
                            $row[]=$docRow;
                        }
    return $row;
}

function getNoticeBoard()
{
    global $DB;

    $stmt = $DB->prepare('SELECT notice_id, notice_Title,notice_Description,notice_DateTime FROM cnf_notice WHERE notice_FromDate<=now() AND notice_ToDate>=now() AND notice_Status="A"');
    $stmt->execute();
    $noticeRow=array();
    if($stmt->rowCount()>0)
    {
        while($row = $stmt->fetch(PDO::FETCH_ASSOC))
        {
            $noticeRow[]=$row;
        }
    }
    

    return $noticeRow;
}

function getExcerpt($str, $startPos=0, $maxLength=100) {
    if(strlen($str) > $maxLength) {
        $excerpt   = substr($str, $startPos, $maxLength-3);
        $lastSpace = strrpos($excerpt, ' ');
        $excerpt   = substr($excerpt, 0, $lastSpace);
        $excerpt  .= '...';
    } else {
        $excerpt = $str;
    }
    
    return $excerpt;
}

function getHolidays()
{
    global $DB;

    $stmt = $DB->prepare('SELECT holiday_id, holiday_Title, holiday_Description, holiday_Date, month_id, holiday_DateTime FROM cnf_holiday WHERE holiday_Date>=now()');
    $stmt->execute();
    $holidayRow=array();
    if($stmt->rowCount())
    {
        while($row=$stmt->fetch(PDO::FETCH_ASSOC))
        {
            $holidayRow[]=$row;
        }
    }

    return $holidayRow;
}

function getAllLeaves()
{
    global $DB;

    $query='SELECT LA.la_id, LA.user_id, LA.lt_id, LA.la_Annual, LA.lr_id, LA.la_FromDate, LA.la_ToDate, LA.la_Date, LA.la_Days, LA.la_Comment, LA.la_Status, LA.la_DateTime,u_user.user_UserCode,u_user.user_FirstName,u_user.user_LastName,cnf_leavereason.lr_Title,cnf_leavetype.lt_Name 
        FROM u_leaveapplication AS LA
        INNER JOIN u_user ON u_user.user_id=LA.user_id
        INNER JOIN cnf_leavereason ON cnf_leavereason.lr_id=LA.lr_id
        INNER JOIN cnf_leavetype ON cnf_leavetype.lt_id=LA.lt_id
        WHERE 1 ORDER BY la_DateTime DESC';
    $stmt = $DB->prepare($query);
    $stmt->execute();
    $row=array();
    if($stmt->rowCount())
    {
        while($r=$stmt->fetch(PDO::FETCH_ASSOC))
        {
            $row[]=$r;
        }
    }
    return $row;
}

function getAllBatchLeaves()
{
    global $DB;

    $query='SELECT LB.lb_id, LB.user_id, LB.lb_FromDate,LB.lb_ToDate,LB.lb_Status,u_user.user_FirstName,u_user.user_LastName,cnf_leavetype.lt_Name,u_user.user_UserCode,cnf_leavereason.lr_Title,LB.lb_DateTime, LB.lb_Days, LB.lb_Doc, LB.lb_ReasonDoc
        FROM u_leavebatch AS LB
        INNER JOIN u_user ON u_user.user_id=LB.user_id
        INNER JOIN cnf_leavetype ON cnf_leavetype.lt_id=LB.lt_id
        INNER JOIN cnf_leavereason ON cnf_leavereason.lr_id=LB.lr_id
        WHERE 1  ORDER BY lb_id DESC ';
    $stmt = $DB->prepare($query);
    $stmt->execute();
    $row=array();
    if($stmt->rowCount())
    {
        while($r=$stmt->fetch(PDO::FETCH_ASSOC))
        {
            $row[]=$r;
        }
    }
    return $row;
}

function changeLeaveStatus($la_id,$la_Status)
{
    global $DB;
    $updateby="";
    $user_id=$_SESSION['user_id'];

    $ul_id = getUlId($la_id);

    if($la_Status=='R'){
        $updateby=" ,rejected_By=? , rejected_DateTime=now() ";
       
              
    }
    elseif($la_Status=='A'){
        $updateby=" ,approved_By=? , approved_DateTime=now() ";
        DeductLeave($ul_id);
    }
     elseif($la_Status=='C'){
        $updateby=" ,cancelledBy=? , cancelled_DateTime=now() ";
        reverseAnnualLeave($la_id);
    }
    $query="UPDATE u_leaveapplication SET la_Status=? $updateby WHERE la_id=?";
    $stmt = $DB->prepare($query);
    $stmt->bindParam(1,$la_Status);
    $stmt->bindParam(2,$user_id);
    $stmt->bindParam(3,$la_id);
    if($stmt->execute()){
        echo "UPDATED";


    }
    else{
        echo "";
    }
}

// change batch leave application status
function changeLeaveBatchStatus($lb_id,$lb_Status, $lb_Reason, $lb_ReasonDoc)
{
	global $DB;

	$user_id = $_SESSION['user_id'];
	
	$updateby="";
    $lb_ReasonDocName = date('YmdHis').$lb_ReasonDoc['lb_ReasonDoc']['name'];
    $lb_ReasonDocTmpName = $lb_ReasonDoc['lb_ReasonDoc']['tmp_name'];
     
    if($lb_ReasonDoc['lb_ReasonDoc']['name']!="")
    {
        move_uploaded_file($lb_ReasonDocTmpName, '../uploads/Leave_Reason_Docs/'.$lb_ReasonDocName);
    }
     
    
    if($lb_Status=='R')
    {
        $updateby=" ,lb_rejected_By=? , lb_rejected_DateTime=now() ";
    }
    elseif($lb_Status=='A')
    {
        $updateby=" ,lb_approved_By=? , lb_approved_DateTime=now() ";
    }
	elseif($lb_Status=='C')
    {
        $updateby=" ,lb_cancelledBy=? , lb_cancelled_DateTime=now() ";
    }

    $query="UPDATE u_leavebatch SET lb_Status=? $updateby , lb_Reason = ? , lb_ReasonDoc = ? WHERE lb_id=?";

    $stmt = $DB->prepare($query);
    $stmt->bindParam(1,$lb_Status);
    $stmt->bindParam(2,$user_id);
    $stmt->bindParam(3,$lb_Reason);
    $stmt->bindParam(4,$lb_ReasonDocName);
    $stmt->bindParam(5,$lb_id);
    if($stmt->execute())
    {
        echo "UPDATED";

        if($lb_Status=='A')
        {
       		$lb_StatusText.="Approved";
        }
       	elseif($lb_Status=='R')
       	{
       		$lb_StatusText.="Rejected";
       	}
       	elseif($lb_Status=='C')
       	{
       		$lb_StatusText.="Cancelled"; 
       	}
          /*********************************
       	*	Email Starts here
       	**********************************/
       		$subject = "Leave request #reference no. ".$lb_id." has been ";
       		$subject.=$lb_StatusText;
       		

       		$employeeDetail = getEmpPersonalDetail($user_id);
       		$batchDetail = getLeaveBatchFromId($lb_id);

		    $fields = array(); 
		    $fields{"sv2_Reference"} = "Reference No: ".$lb_id; 
		    $fields{"sv2_type"} = "Leave Type: ".getLeaveTypeFromId($batchDetail['lt_id'])['lt_Name']; 
		    $fields{"sv2_from"} = "Start Date: ".$batchDetail['lb_FromDate']; 
		    $fields{"sv2_to"} = "End Date: ".$batchDetail['lb_ToDate']; 
		    $fields{"sv2_days"} = "No. of Leaves Taken: ".$batchDetail['lb_Days']; 
		    $fields{"sv2_status"} = "Request Status: ".$lb_StatusText; 
		    
		    $body = "Dear ".$employeeDetail['user_FirstName'].",\n\n"; 

		    foreach($fields as $a => $b)
		    {   
		        $body .= sprintf("%s\n",$b,$_REQUEST[$a]); 
		    }
		    
		    $body.="\nPlease dont't Reply to this Email \n\n";
		    
		    $body.="Thank you.\n";
		    $body.="Your Faithful,\nHR Manager";

		    sendMail($postArray['user_Email'], $subject, $body, 'Leave Request Status Change');
    
       	/*********************************
       	*	Email Ends here
       	**********************************/

        $stmt2= $DB->prepare('SELECT `la_id`, `lb_id`, `lt_id`, `la_Annual`,`ul_id`, `user_id` , `la_Date` FROM u_leaveapplication WHERE lb_id=?');
        $stmt2->bindValue(1,$lb_id);
        $stmt2->execute();
        while($row=$stmt2->fetch(PDO::FETCH_ASSOC))
        { 
            $la_id=$row['la_id'];
            $user_id  =$row['user_id'];
            $la_Date = $row['la_Date'];
           
            /////////////////change the status for each individual leave /////////////////
            if($lb_Status=='R'){
                $updateby=" ,rejected_By=? , rejected_DateTime=now() ";
           
            }
            elseif($lb_Status=='A'){
                $updateby=" ,approved_By=? , approved_DateTime=now() ";
                if($row['lt_id']==1)  //reverse the leave if annual
                    {
                        if(!$ul_id=annualLeaveAdjustment($user_id,'A', 'LIMIT 1',$la_Date))
                        {
                            if(!$ul_id=annualLeaveAdjustment($user_id,'A', 'LIMIT 1,1',$la_Date))
                            {
                                if(!$ul_id=annualLeaveAdjustment($user_id,'A', 'LIMIT 2,1',$la_Date))
                                {
                                    if(!$ul_id=annualLeaveAdjustment($user_id,'A', 'LIMIT 3,1',$la_Date))
                                    {
                                        /*$error++; //There is no annual leave to use.
                                        $errors++;*/

                                    }

                                }
                            }
                        }   
                        //echo $ul_id;
                }
                else{
                    DeductLeave($row['ul_id']);
                }
            }
             elseif($lb_Status=='C'){
                $updateby=" ,cancelledBy=? , cancelled_DateTime=now() ";
                reverseAnnualLeave($la_id);
            }
                    
            $query="UPDATE u_leaveapplication SET la_Status=? $updateby WHERE la_id=?";
            $stmt3 = $DB->prepare($query);
            $stmt3->bindParam(1,$lb_Status);
            $stmt3->bindParam(2,$user_id);
            $stmt3->bindParam(3,$la_id);
            if($stmt3->execute()){
                //
            }
            else{
              
                }
            /////////////////End of change the status for each individual leave /////////////////
        }
    }
    else{
        echo "";
    }
}

function reverseAnnualLeave($la_id)
{
    global $DB;

    $query = "SELECT la_id,ul_id FROM u_leaveapplication WHERE la_id=$la_id";
    $stmt = $DB->prepare($query);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if($row['ul_id'])
    {

        $ul_id =$row['ul_id'];
        $query2 = "UPDATE u_userleave SET ul_RemainingNumber=ul_RemainingNumber+1 WHERE ul_id=$ul_id";
        $stmt2 =  $DB->prepare($query2);
        $stmt2->execute();
    }
    return true;
}


/*********************
Leave Adjustment Starts here

***********************/
function annualLeaveAdjustment($user_id,$status,$limit,$la_Date)
{
    global $DB;
    $checkQ="SELECT ul_id, ul_Number,ul_RemainingNumber,ul_ExpiryDate FROM u_userleave WHERE user_id=$user_id AND ul_Status='$status' AND ul_RemainingNumber>0 AND lt_id=1 ORDER BY ul_ExpiryDate ASC $limit";
    $stmt2 = $DB->prepare($checkQ);
    $stmt2->execute();
    if($stmt2->rowCount())
    {
        $checkRow=$stmt2->fetch(PDO::FETCH_ASSOC);
        $ul_id = $checkRow['ul_id'];
        if($checkRow['ul_ExpiryDate']<$la_Date)
        {
            return 0;
        }
        else
        {
            DeductLeave($ul_id);
            return $ul_id;
        }
        
    }
    else
    {
        return 0;
    }
    
}






/***********************************
* Leave Type Work Starts Here
*************************************/
function getLeaveTypeList()
{
    global $DB;

    $stmt =  $DB->prepare('SELECT lt_id, lt_Name,lt_Status, lt_Annual FROM cnf_leavetype ORDER BY lt_id DESC');
    if($stmt->execute()){
        while($r=$stmt->fetch(PDO::FETCH_ASSOC))
        {
            $row[]=$r;
        }
        return $row;
    }
}

function createLeaveType($lt_Name,$lt_Status)
{
    global $DB;
    $stmt   =   $DB->prepare('INSERT INTO cnf_leavetype (lt_Name,lt_Status) VALUES (?,?)');
    $stmt->bindParam(1,$lt_Name);
    
    $stmt->bindParam(2,$lt_Status);
    if($stmt->execute()){
        echo $DB->lastInsertId();
    }
    else{
        echo "";
    }
}

function updateLeaveType($lt_id,$lt_Name,$lt_Status)
{
    global $DB;
    $stmt =  $DB->prepare('UPDATE cnf_leavetype SET lt_Name=?,lt_Status=? WHERE lt_id=?');
    $stmt->bindParam(1,$lt_Name);
    $stmt->bindParam(2,$lt_Status);
    $stmt->bindParam(3,$lt_id);

    if($stmt->execute()){
        echo "UPDATED";
    }
    else{
        echo "";
    }
}

function deleteLeaveType($lt_id)
{
    global $DB;
    $stmt = $DB->prepare('DELETE FROM cnf_leavetype WHERE lt_id=?');
    $stmt->bindParam(1,$lt_id);
    if($stmt->execute()){


        $stmt1 = $DB->prepare('DELETE FROM cnf_otherleave WHERE lt_id=?');
        $stmt1->bindParam(1,$lt_id);
        if($stmt1->execute()){

            $stmt2 = $DB->prepare('DELETE FROM u_userleave WHERE lt_id=?');
            $stmt2->bindParam(1,$lt_id);
            if($stmt2->execute()){
                echo "1";
            }
            
        }
        
       
    }
    else
    {
       echo "";
    }
}

/***********************************
* Leave Type Work Ends Here
*************************************/

/**
 * Other Leave Allocation
 * last edited: justin, 07/08/17
 */
function allocateOtherLeaves($lt_id, $ul_Number, $leaveAllocationyear)
{
    global $DB;
    $ul_Year = $leaveAllocationyear;
    $ul_FromDate = $ul_Year.'-01-01';
    $ul_ToDate = $ul_Year.'-12-31';
    $ul_ExpiryDate = $ul_FromDate;
    $ul_Annual = 'N';
    $ul_Status = 'A';

    $stmtCheckOtherLeave = $DB->prepare("SELECT ol_id FROM cnf_otherleave WHERE lt_id = $lt_id AND ol_Year = $leaveAllocationyear");
    $stmtCheckOtherLeave->execute();
     
    if($stmtCheckOtherLeave->rowCount()==0)
    {
     	$stmtOtherLeaves =  $DB->prepare('INSERT INTO cnf_otherleave(lt_id, ol_Number, ol_Year, ol_DateTime) VALUES(?,?,?,NOW())');
    	$stmtOtherLeaves->bindParam(1,$lt_id);
    	$stmtOtherLeaves->bindParam(2,$ul_Number);
    	$stmtOtherLeaves->bindParam(3,$ul_Year);
    	$stmtOtherLeaves->execute();
    }
    
    $AllEmployeeIDs = getEmployeeID();
   /* echo "<pre>";
    print_r($AllEmployeeIDs);
    echo "</pre>";*/
	for ($i=0; $i <count($AllEmployeeIDs) ; $i++) 
	{ 
     	$stmtCheckAnnualLeave = $DB->prepare("SELECT ul_id FROM u_userleave WHERE user_id = $AllEmployeeIDs[$i] AND ul_Year = $leaveAllocationyear AND lt_id = $lt_id");
     	$stmtCheckAnnualLeave->execute();
     	
     	if($stmtCheckAnnualLeave->rowCount()==0)
     	{    
    		$stmt = $DB->prepare('INSERT INTO u_userleave (lt_id, ul_Number,ul_Year, ul_Annual, ul_DateTime, ul_ToDate, ul_FromDate, user_id, ul_ExpiryDate, ul_Status, ul_RemainingNumber) VALUES (?,?,?,?,NOW(),?,?,?,?,?,?)');
    		$stmt->bindParam(1, $lt_id);
    		$stmt->bindParam(2, $ul_Number);
    		$stmt->bindParam(3, $ul_Year);
    		$stmt->bindParam(4, $ul_Annual);
    		$stmt->bindParam(5, $ul_ToDate);
    		$stmt->bindParam(6, $ul_FromDate);
    		$stmt->bindParam(7, $AllEmployeeIDs[$i]);
    		$stmt->bindParam(8, $ul_ExpiryDate);
    		$stmt->bindParam(9, $ul_Status);
    		$stmt->bindParam(10, $ul_Number);

    		$stmt->execute();
    		$last_id = $DB->lastInsertId();
   			/* echo $last_id."INSERTED";*/
      	}
  	} 
}

/**
 * Annual Leave Allocation
 * last edited: justin, 07/08/17
 */
function allocateAnnualLeaves($desig_id,$lt_id, $ul_Number,$leaveAllocationyear)
{
    global $DB;
    $ul_Year = $leaveAllocationyear;
    $ul_FromDate = $ul_Year.'-01-01';
	$ul_ToDate = $ul_Year.'-12-31';
    $ul_ExpiryDate = ($ul_Year+2).'-12-31';
    $ul_Annual = 'Y';
    $ul_Status = 'A';
     
    $stmtCheckAnnualLeave = $DB->prepare("SELECT dl_id FROM cnf_desigleave WHERE desig_id = $desig_id AND dl_Year = $leaveAllocationyear");
    $stmtCheckAnnualLeave->execute();
     
   	if($stmtCheckAnnualLeave->rowCount()==0)
   	{
    	$stmtAnnualLeaves =  $DB->prepare('INSERT INTO cnf_desigleave(desig_id, lt_id, dl_Number, dl_Year, dl_DateTime) VALUES(?,?,?,?,NOW())');
    	$stmtAnnualLeaves->bindParam(1,$desig_id);
    	$stmtAnnualLeaves->bindParam(2,$lt_id);
    	$stmtAnnualLeaves->bindParam(3,$ul_Number);
    	$stmtAnnualLeaves->bindParam(4,$ul_Year);
    	$stmtAnnualLeaves->execute();
    }
    /*echo "<pre>";
    print_r(getEmployeeWithDesig($desig_id));
    echo "</pre>";*/

    $EmployeeWithDesig = getEmployeeWithDesig($desig_id);

    for ($i=0; $i <count($EmployeeWithDesig); $i++) 
    { 
		$stmtCheckAnnualLeave = $DB->prepare("SELECT ul_id FROM u_userleave WHERE user_id = $EmployeeWithDesig[$i] AND ul_Year = $leaveAllocationyear AND lt_id = $lt_id");
     	$stmtCheckAnnualLeave->execute();
     	
     	if($stmtCheckAnnualLeave->rowCount()==0)
     	{      
  			$stmt = $DB->prepare('INSERT INTO u_userleave (lt_id, ul_Number,ul_Year, ul_Annual, ul_DateTime, ul_ToDate, ul_FromDate, user_id, ul_ExpiryDate, ul_Status, ul_RemainingNumber) VALUES (?,?,?,?,NOW(),?,?,?,?,?,?)');
    		$stmt->bindParam(1, $lt_id);
    		$stmt->bindParam(2, $ul_Number);
    		$stmt->bindParam(3, $ul_Year);
    		$stmt->bindParam(4, $ul_Annual);
    		$stmt->bindParam(5, $ul_ToDate);
    		$stmt->bindParam(6, $ul_FromDate);
    		$stmt->bindParam(7, $EmployeeWithDesig[$i]);
    		$stmt->bindParam(8, $ul_ExpiryDate);
    		$stmt->bindParam(9, $ul_Status);
    		$stmt->bindParam(10, $ul_Number);

    		$stmt->execute();
    		$last_id = $DB->lastInsertId();
    		/*echo $last_id."INSERTED";*/
       	}
  	} 
}

/**
 * Employee Id's
 */
function getEmployeeID()
{
 global $DB;

    $row = array();
    $user_Status = 'A';
    $stmt =  $DB->prepare('SELECT user_id FROM u_user WHERE user_Status=?');
    
    $stmt->bindParam(1,$user_Status);
    if($stmt->execute()){
        while($r=$stmt->fetch(PDO::FETCH_ASSOC))
        {
            $row[]=$r['user_id'];
        }
        return $row;
    }

}

/**
 * Employee With Designation
 */
function getEmployeeWithDesig($desig_id)
{
 	global $DB;

    $row = array();
    $uc_Status = 'A';
    $stmt =  $DB->prepare('SELECT user_id FROM u_usercompany WHERE desig_id = ? AND uc_Status=?');
    $stmt->bindParam(1,$desig_id);
    $stmt->bindParam(2,$uc_Status);
    
    if($stmt->execute())
    {
        while($r=$stmt->fetch(PDO::FETCH_ASSOC))
        {
            $row[]=$r['user_id'];
        }
        return $row;
    }
}

// get user leave
function getUserLeaves($user_id)
{
    global $DB;

    $query ="SELECT UL.ul_id,UL.lt_id,UL.ul_Number,UL.ul_Year,UL.ul_DateTime,UL.ul_Status,UL.user_id,UL.ul_Annual,UL.ul_FromDate,UL.ul_ToDate,UL.ul_Remarks,UL.ul_ExpiryDate,LT.lt_Name 
                            FROM u_userleave AS UL 
                            INNER JOIN cnf_leavetype AS LT ON LT.lt_id=UL.lt_id
                            WHERE UL.user_id=?
                            ORDER BY UL.ul_Year DESC";
    $stmt = $DB->prepare($query);
    $stmt->bindValue(1,$user_id);
    $stmt->execute();
    $leaveRow=array();
    if($stmt->rowCount())
    {
        while($row=$stmt->fetch(PDO::FETCH_ASSOC))
        {
            $leaveRow[]=$row;
        }
    }

    return $leaveRow;

}

function mng_getLeavesStats($user_id)
{
    global $DB;

    $currentYear = date('Y');
      $currentDate = date('Y-m-d');
       $query="SELECT UL.ul_id, UL.lt_id, SUM(UL.ul_Number) AS ul_Number, SUM(UL.ul_RemainingNumber) AS ul_RemainingNumber, UL.ul_Year, UL.ul_DateTime, UL.ul_Status, UL.user_id, UL.ul_Annual, UL.ul_FromDate, UL.ul_ToDate, UL.ul_Remarks, UL.ul_ExpiryDate,cnf_leavetype.lt_Name,
            (SELECT COUNT(la_id) FROM u_leaveapplication WHERE user_id=$user_id AND la_Status='P' AND lt_id=UL.lt_id AND YEAR(la_Date)='$currentYear') AS pending ,
            (SELECT COUNT(la_id) FROM u_leaveapplication WHERE user_id=$user_id AND la_Status='A' AND lt_id=UL.lt_id AND YEAR(la_Date)='$currentYear') AS approved,
            (SELECT COUNT(la_id) FROM u_leaveapplication WHERE user_id=$user_id AND la_Status='R' AND lt_id=UL.lt_id AND YEAR(la_Date)='$currentYear') AS rejected
        FROM u_userleave AS UL
        INNER JOIN cnf_leavetype ON cnf_leavetype.lt_id=UL.lt_id
        WHERE UL.user_id=? AND UL.ul_Status=? AND (UL.ul_ExpiryDate > '$currentDate' || UL.ul_ExpiryDate = '$currentDate')
        GROUP BY UL.lt_id";

        $stmt = $DB->prepare($query);
        $stmt->bindValue(1,$user_id);
        $stmt->bindValue(2,'A');
        $leaveStatRow=array();
        $stmt->execute();
        if($stmt->rowCount()){
            while($r=$stmt->fetch(PDO::FETCH_ASSOC))
            {
                $leaveStatRow[]=$r;
            }
        }

        return $leaveStatRow;
}

function getUserLeaveHistory($user_id)
{
    global $DB;
   /* $query='SELECT LA.la_id, LA.user_id, LA.lt_id, LA.la_Annual, LA.lr_id, LA.la_FromDate, LA.la_ToDate, LA.la_Date, LA.la_Days, LA.la_Comment, LA.la_Status, LR.lr_Title,LT.lt_Name,LA.la_DateTime 
        FROM u_leaveapplication AS LA 
        INNER JOIN cnf_leavetype AS LT ON LT.lt_id=LA.lt_id
        INNER JOIN cnf_leavereason AS LR ON LR.lr_id=LA.lr_id
        WHERE LA.user_id=?';*/

        //return $user_id;
      $query='SELECT LB.lb_id, LB.user_id, LB.lt_id, LB.lb_Annual, LB.lr_id, LB.lb_FromDate, LB.lb_ToDate, LB.lb_Date, LB.lb_Days, LB.lb_Comment, LB.lb_Status, LR.lr_Title,LT.lt_Name,LB.lb_DateTime , LB.lb_Doc, LB.lb_ReasonDoc, LB.lb_Reason
        FROM u_leavebatch AS LB 
        INNER JOIN cnf_leavetype AS LT ON LT.lt_id=LB.lt_id
        INNER JOIN cnf_leavereason AS LR ON LR.lr_id=LB.lr_id
        WHERE LB.user_id=?
      	ORDER BY LB.lb_id DESC';

    $stmt = $DB->prepare($query);
    $stmt->bindParam(1,$user_id);
    $stmt->execute();
    $leaveRow=array();
    if($stmt->rowCount()>0)
    {
        while($row=$stmt->fetch(PDO::FETCH_ASSOC))
        {
            $leaveRow[]=$row;
        }
    }
    return $leaveRow;
}

function getUserAllLeaves($user_id)
{
    global $DB;

    $query ="SELECT UL.ul_id,UL.lt_id,UL.ul_Number,UL.ul_Year,UL.ul_DateTime,UL.ul_Status,UL.user_id,UL.ul_Annual,UL.ul_FromDate,UL.ul_ToDate,UL.ul_Remarks,UL.ul_ExpiryDate,LT.lt_Name 
                            FROM u_userleave AS UL 
                            INNER JOIN cnf_leavetype AS LT ON LT.lt_id=UL.lt_id
                            WHERE UL.user_id=?
                            ORDER BY UL.ul_Year DESC";
    $stmt = $DB->prepare($query);
    $stmt->bindValue(1,$user_id);
    $stmt->execute();
    $leaveRow=array();
    if($stmt->rowCount())
    {
        while($row=$stmt->fetch(PDO::FETCH_ASSOC))
        {
            $leaveRow[]=$row;
        }
    }

    return $leaveRow;

}

function allLeaves($user_id)
{
    global $DB;
    $stmt = $DB->prepare('SELECT UL.ul_id,UL.user_id,UL.lt_id,UL.ul_Number,UL.ul_RemainingNumber,UL.ul_Year,UL.ul_Status,UL.user_id,UL.ul_Annual,UL.ul_FromDate,UL.ul_ToDate,UL.ul_ExpiryDate,LT.lt_Name,(SELECT COUNT(la_id) FROM u_leaveapplication AS LA WHERE LA.user_id=? AND UL.lt_id=LA.lt_id AND LA.la_Status=? AND YEAR(LA.la_Date)=UL.ul_Year) AS utilized
            FROM u_userleave AS UL
            INNER JOIN cnf_leavetype AS LT on LT.lt_id=UL.lt_id
            WHERE UL.user_id=?
            ORDER BY UL.lt_id');
    $stmt->bindValue(1,$user_id);
    $stmt->bindValue(2,'A');
    $stmt->bindValue(3,$user_id);
    $stmt->execute();
    $r = array();
    if($stmt->rowCount())
    {
        while($row= $stmt->fetch(PDO::FETCH_ASSOC))
        {
            $r[] = $row;
        }
    }

    return $r;
}
 
// get all active leaves except annual leave type
function getAllUserLeaves($user_id)
{
    global $DB;
    $stmt = $DB->prepare('SELECT *
            FROM u_userleave AS UL
            INNER JOIN cnf_leavetype AS LT on LT.lt_id=UL.lt_id
            WHERE UL.user_id=? AND UL.ul_Status=? AND UL.lt_id!=1
            ORDER BY UL.lt_id');
    $stmt->bindValue(1,$user_id);
    $stmt->bindValue(2,'A');
    $stmt->execute();
    $r = array();
    if($stmt->rowCount())
    {
        while($row= $stmt->fetch(PDO::FETCH_ASSOC))
        {
            $r[] = $row;
        }
    }

    return $r;
}

function allAnnualLeaves($user_id)
{
    global $DB;
    $stmt = $DB->prepare('SELECT UL.ul_id,UL.user_id,UL.lt_id,UL.ul_Number,UL.ul_RemainingNumber,UL.ul_Year,UL.ul_Status,UL.user_id,UL.ul_Annual,UL.ul_FromDate,UL.ul_ToDate,UL.ul_ExpiryDate,LT.lt_Name
            FROM u_userleave AS UL
            INNER JOIN cnf_leavetype AS LT on LT.lt_id=UL.lt_id
            WHERE UL.user_id=? AND UL.lt_id=?
            ORDER BY UL.ul_Year DESC');
    $stmt->bindValue(1,$user_id);
    $stmt->bindValue(2,1);
    $stmt->execute();
    $r = array();
    if($stmt->rowCount())
    {
        while($row= $stmt->fetch(PDO::FETCH_ASSOC))
        {
            $r[] = $row;
        }
    }

    return $r;
}

function getUserJobHistory($user_id)
{
    global $DB;

    $stmt= $DB->prepare('SELECT UC.uc_id, UC.group_id,UC.desig_id,UC.dept_id,cnf_designation.desig_Name,UC.uc_JoiningDate,UC.uc_ExitDate,UC.uc_Status
                FROM u_usercompany AS UC
                INNER JOIN cnf_designation ON cnf_designation.desig_id=UC.desig_id
                WHERE UC.user_id=?');
    $stmt->bindValue(1,$user_id);
    $stmt->execute();
    $r = array();
    if($stmt->rowCount())
    {
        while($row=$stmt->fetch(PDO::FETCH_ASSOC))
        {
            $r[] = $row;
        }
    }

    return $r;
}


function getDays()
{
    global $DB;

    $stmt=$DB->prepare('SELECT WD.wd_id, WD.day_id, WD.wd_SameTime, WD.wd_On, WD.wd_StartTime, WD.wd_EndTime, WD.wd_FromDate, WD.wd_ToDate, WD.wd_Status,cnf_day.day_Name,cnf_day.day_ShortName 
        FROM cnf_workingday  AS WD
        INNER JOIN cnf_day ON cnf_day.day_id=WD.day_id
        ORDER BY cnf_day.day_Order');
    $stmt->execute();
    $r=array();
    if($stmt->rowCount())
    {
        while($row=$stmt->fetch(PDO::FETCH_ASSOC))
        {
            $r[] = $row;
        }
    }

    return $r;
}

function getUlId($la_id){
     global $DB;
     /*$user_id=$_SESSION['user_id'];*/
     
     $stmt= $DB->prepare("SELECT ul_id FROM u_leaveapplication WHERE la_id = $la_id ");
      $stmt->execute();
      if($stmt->rowCount()>0){

        $row=$stmt->fetch(PDO::FETCH_ASSOC);
        return $row['ul_id'];
      }
      else{
        return 0;
      }
}

function DeductLeave($ul_id){

    global $DB;
     $updateQ = "UPDATE u_userleave SET ul_RemainingNumber=ul_RemainingNumber-1 WHERE ul_id=$ul_id";
            $stmt3 = $DB->prepare($updateQ);
            $stmt3->execute();
}

/*
    Delete Designation Leave
*/

function deleteDesigLeave($desig_id)
{
    global $DB;
    $stmt = $DB->prepare('DELETE FROM cnf_desigleave WHERE desig_id=?');
    $stmt->bindParam(1,$desig_id);
    if($stmt->execute()){
        return "1";
    }
    else
    {
        return "";
    }
}
/*
Expire Old Leaves
*/
function expireOldLeaves(){

     global $DB;
     $currentDate = date('Y-m-d');
     $currentYear = date('Y');
     
     $stmtExpireLeaves = $DB->prepare("UPDATE u_userleave SET ul_Status = 'I' WHERE ul_ExpiryDate <= '$currentDate'");
     if($stmtExpireLeaves -> execute()){
        return 1;

     }
     else{
        return 0;
     }


}

/****************************************
    Add New Job Of Employee Starts Here
*****************************************/
function addNewEmployeeJob($postArray){

    global $DB;

    $user_id = $postArray['addNewEmployeeJobId'];
    $returnArray = array();

    /*********** Add User Company ************/
        $uc_Status = 'A';
        $joining_date = date("Y-m-d");

        $stmtUpdate = $DB->prepare("UPDATE u_usercompany SET uc_Status = 'I' WHERE user_id = $user_id");
        $stmtUpdate->execute();

        $stmt = $DB->prepare('  INSERT INTO u_usercompany (user_id, group_id, dept_id, desig_id, uc_ContractType, uc_JoiningDate, uc_Status) 
                                VALUES (?,?,?,?,?,?,?)');
        $stmt->bindValue(1,$user_id);
        $stmt->bindValue(2,$postArray['group_id']);
        $stmt->bindValue(3,$postArray['dept_id']);
        $stmt->bindValue(4,$postArray['desig_id']);
        $stmt->bindValue(5,$postArray['contract_type']);
        $stmt->bindValue(6,$joining_date);
        $stmt->bindValue(7,$uc_Status);
        $stmt->execute();


       
        /********** Add User Job History ***********/
        if ($postArray['contract_type'] == 'C')
        {
            $contract_duration = $postArray['contract_duration'];
            $expiry_date = date('Y-m-d',strtotime('+'.$contract_duration.' years',strtotime($joining_date)));
        }
        else
        {
            $contract_duration = null;
            $expiry_date = null;
        }
        
        $uh_status = 'A';

        $stmtUpdate = $DB->prepare("UPDATE u_userjobhistory SET uh_status = 'I' WHERE uh_user_id = $user_id");
        $stmtUpdate->execute();

        $stmt = $DB->prepare(' INSERT INTO u_userjobhistory (uh_user_id, uh_group, uh_dept, 
                                uh_desig, uh_contract_type,uh_contract_duration,
                                uh_effective_date,uh_expiry_date,uh_status)
                                VALUES (?,?,?,?,?,?,?,?,?)');
        $stmt->bindValue(1, $user_id);
        $stmt->bindValue(2, $postArray['group_id']);
        $stmt->bindValue(3, $postArray['dept_id']);
        $stmt->bindValue(4, $postArray['desig_id']);
        $stmt->bindValue(5, $postArray['contract_type']);
        $stmt->bindValue(6, $contract_duration);
        $stmt->bindValue(7, $postArray['uh_effective_date']);
        $stmt->bindValue(8, $expiry_date);
        $stmt->bindValue(9, $uh_status);
        $stmt->execute();

        $returnArray['Status'] = "CREATED";
        $returnArray['effective_date'] = $postArray['uh_effective_date'];
        $returnArray['uh_id'] = $DB->lastInsertId();

        /*********************************
       	*	Email Starts here
       	**********************************/
       		$subject = "New contract has been created.";
       		$employeeDetail = getEmpPersonalDetail($user_id);
       		if($postArray['contract_type']=="C"){
       			$contract_type = "Contract";
       		}
       		else{
       			$contract_type = "Permanent";
       		}

       		
		    $fields = array(); 
		    $fields{"sv2_Reference"} = "Effective Date: ".$postArray['uh_effective_date']; 
		    $fields{"sv2_type"} = "Expired Date: ".$expiry_date; 
		    $fields{"sv2_from"} = "Contract Type: ".$contract_type; 
		    if($contract_type=="C"){
		 	   $fields{"sv2_to"} = "Contract Period: ".$contract_duration.' Years'; 
			}
		    $fields{"sv2_days"} = "Designation: ".getDesignationFromId($postArray['desig_id'])['desig_Name']; 
		    $fields{"sv2_status"} = "Department: ".getDepartmentFromId($postArray['dept_id'])['dept_Name']; 
		    $fields{"sv2_group"} = "Group: ".getGroupFromId($postArray['group_id'])['group_Name']; 
		    
		    $body = "Dear ".$employeeDetail['user_FirstName'].",\n\n"; 
		     $body.="\nYour new contract details as follows: \n\n";

		    foreach($fields as $a => $b)
		    {   
		        $body .= sprintf("%s\n",$b,$_REQUEST[$a]); 
		    }
		    
		   
		    
		    $body.="Thank you.\n";
		    $body.="Your Faithful,\nHR Manager";

		    sendMail($postArray['user_Email'], $subject, $body, 'New Job Creation');
    
       	/*********************************
       	*	Email Ends here
       	**********************************/

        $leaveList = getLeaveTypeList();
        $ol_Year = date('Y');
        $currentMonth = date('m');
        $currentYear = date('Y');
        $fromDate   = date('Y-m-d',strtotime(date('Y-01-01')));
        $toDate   = date('Y-m-d',strtotime(date('Y-12-31')));
        $uh_id = $returnArray['uh_id'];

        for ($i=0; $i <count($leaveList) ; $i++) 
        { 
            $lt_id = $leaveList[$i]['lt_id'];
            
            if($postArray['contract_type'] == 'C'){

                for($contract_leave = 0 ; $contract_leave <=$contract_duration; $contract_leave++){

                if($contract_leave==0){

                    $fromDate   = date('Y-m-d',strtotime('+'.$contract_leave.' years',strtotime($joining_date)));
                    $toDate   = date('Y-m-d',strtotime(date('Y-12-31' , strtotime('+'.$contract_leave.' years') )));

                }
                elseif($contract_leave == $contract_duration){
                    $fromDate   = date('Y-m-d',strtotime(date('Y-01-01' , strtotime('+'.$contract_leave.' years'))));
                    $toDate   = $expiry_date;
                }
                else{

                    $fromDate   = date('Y-m-d',strtotime(date('Y-01-01' , strtotime('+'.$contract_leave.' years'))));
                    $toDate   = date('Y-m-d',strtotime(date('Y-12-31' , strtotime('+'.$contract_leave.' years'))));
                }
                    
                $leave_year = date('Y' , strtotime($fromDate));

                if($leaveList[$i]['lt_Annual']!='Y')
                {
                /********** Add User Other Leaves **********/
                if(isset($postArray['userleave'][$lt_id]))
                $assigned_AL = $postArray['userleave'][$lt_id];
                $is_annual_leave = 'N';
                $leave_expiry_date = $toDate;
                
                
            }
            else 
            {
                /*********** Add User Annual Leave ***********/
        
                $assigned_AL = $postArray['user_annualleave'];
                $is_annual_leave = 'Y';
                $leave_expiry_date = $expiry_date;
                
                    
            }
                if(isset($assigned_AL)){
                $leave_months = date('m', strtotime($toDate)) - date('m' , strtotime($fromDate)) + 1;
                $assigned_AL = $leave_months/12*$assigned_AL;
                $assigned_AL = floor($assigned_AL);

                    $stmtDL = $DB->prepare('INSERT INTO u_userleave (lt_id,ul_Number,ul_RemainingNumber,
                                    ul_Year,ul_DateTime,ul_Status,ul_Annual,ul_FromDate,ul_ToDate,
                                    ul_ExpiryDate,user_id, uh_id)
                                    VALUES(?,?,?,?,now(),?,?,?,?,?,?,?)');
                    $stmtDL->bindValue(1,$lt_id);
                    $stmtDL->bindValue(2,$assigned_AL);
                    $stmtDL->bindValue(3,$assigned_AL);
                    $stmtDL->bindValue(4,$leave_year);
                    $stmtDL->bindValue(5,'A');
                    $stmtDL->bindValue(6,$is_annual_leave);
                    $stmtDL->bindValue(7,$fromDate);
                    $stmtDL->bindValue(8,$toDate);
                    $stmtDL->bindValue(9,$leave_expiry_date);
                    $stmtDL->bindValue(10,$user_id);
                    $stmtDL->bindValue(11,$uh_id);
                    $stmtDL->execute();

                    $returnArray['leaveAnnual'][] = $is_annual_leave;
                    $returnArray['leaveYear'][] = $leave_year;
                    $returnArray['leaveNumber'][] = $assigned_AL;
                    $returnArray['leaveExpiryDate'][] = $leave_expiry_date;
                    $returnArray['fromDate'][] = $fromDate;
                    $returnArray['toDate'][] = $toDate;
                    $returnArray['lt_Name'][] = $leaveList[$i]['lt_Name'];
                    $returnArray['ul_id'][] = $DB->lastInsertId();


                }

                }
            }
            else{
            if($leaveList[$i]['lt_Annual']!='Y')
            {
                /********** Add User Other Leaves **********/
                
                $expiryDate = $toDate;
                if(isset($postArray['userleave'][$lt_id])){
                $dl_Number = $postArray['userleave'][$lt_id];
                $is_annual_leave = 'N';
                
                $stmtDL = $DB->prepare('INSERT INTO u_userleave (lt_id,ul_Number,ul_RemainingNumber,
                                        ul_Year,ul_DateTime,ul_Status,ul_Annual,ul_FromDate,
                                        ul_ToDate,ul_ExpiryDate,user_id, uh_id) 
                                        VALUES(?,?,?,?,now(),?,?,?,?,?,?,?)');
                $stmtDL->bindValue(1,$lt_id);
                $stmtDL->bindValue(2,$dl_Number);
                $stmtDL->bindValue(3,$dl_Number);
                $stmtDL->bindValue(4,$currentYear);
                $stmtDL->bindValue(5,'A');
                $stmtDL->bindValue(6,$is_annual_leave);
                $stmtDL->bindValue(7,$fromDate);
                $stmtDL->bindValue(8,$toDate);
                $stmtDL->bindValue(9,$expiryDate);
                $stmtDL->bindValue(10,$user_id);
                $stmtDL->bindValue(11,$uh_id);
                $stmtDL->execute();

                    $returnArray['leaveAnnual'][] = $is_annual_leave;
                    $returnArray['leaveYear'][] = $currentYear;
                    $returnArray['leaveNumber'][] = $dl_Number;
                    $returnArray['leaveExpiryDate'][] = $expiryDate;
                    $returnArray['fromDate'][] = $fromDate;
                    $returnArray['toDate'][] = $toDate;
                    $returnArray['lt_Name'][] = $leaveList[$i]['lt_Name'];
                    $returnArray['ul_id'][] = $DB->lastInsertId();

                }
                
                
            }
            else 
            {
                /*********** Add User Annual Leave ***********/
                 
                $desig_id = $postArray['desig_id'];
                $assigned_AL = $postArray['user_annualleave'];
                $is_annual_leave = 'Y';
                $contract_type = $postArray['contract_type'];
                $contract_duration = $postArray['contract_duration'];
                
                if ($contract_type == 'C')
                {
                    $expiry_date = date('Y-m-d',strtotime('+'.$contract_duration.' years',strtotime($joining_date)));
                }
                else
                {
                    $expiry_date = date('Y-m-d',strtotime('+2 years',strtotime(date('Y-12-31'))));
                }

                
                $stmtL = $DB->prepare(' SELECT * FROM cnf_desigleave WHERE desig_id=? AND dl_Year=?');
                $stmtL->bindParam(1,$desig_id);
                $stmtL->bindParam(2,$currentYear);
                $stmtL->execute();
                    
                $stmtDL = $DB->prepare('INSERT INTO u_userleave (lt_id,ul_Number,ul_RemainingNumber,
                                ul_Year,ul_DateTime,ul_Status,ul_Annual,ul_FromDate,ul_ToDate,
                                ul_ExpiryDate,user_id, uh_id)
                                VALUES(?,?,?,?,now(),?,?,?,?,?,?,?)');
                $stmtDL->bindValue(1,$lt_id);
                $stmtDL->bindValue(2,$assigned_AL);
                $stmtDL->bindValue(3,$assigned_AL);
                $stmtDL->bindValue(4,$currentYear);
                $stmtDL->bindValue(5,'A');
                $stmtDL->bindValue(6,$is_annual_leave);
                $stmtDL->bindValue(7,$fromDate);
                $stmtDL->bindValue(8,$toDate);
                $stmtDL->bindValue(9,$expiry_date);
                $stmtDL->bindValue(10,$user_id);
                $stmtDL->bindValue(11,$uh_id);
                $stmtDL->execute();

                    $returnArray['leaveAnnual'][] = $is_annual_leave;
                    $returnArray['leaveYear'][] = $currentYear;
                    $returnArray['leaveNumber'][] = $assigned_AL;
                    $returnArray['leaveExpiryDate'][] = $expiry_date;
                    $returnArray['fromDate'][] = $fromDate;
                    $returnArray['toDate'][] = $toDate;
                    $returnArray['lt_Name'][] = $leaveList[$i]['lt_Name'];
                    $returnArray['ul_id'][] = $DB->lastInsertId();

                }
            }
        }

        
        
     

        echo json_encode($returnArray);
}

/****************************************
    Add New Job Of Employee Ends Here
*****************************************/

/****************************************
    Edit New Job Of Employee Starts Here
*****************************************/
function editNewEmployeeJob($postArray){

    global $DB;

    
    $returnArray = array();
    $uh_id = $postArray['editNewEmployeeJobId'];
    $returnArray['check'] = $uh_id;
    /*********** Edit User Company ************/
        $uc_Status = 'A';
        $joining_date = date("Y-m-d");

        /*$stmtUpdate = $DB->prepare("UPDATE u_usercompany SET uc_Status = 'I' WHERE user_id = $user_id");
        $stmtUpdate->execute();*/

/*        $stmt = $DB->prepare('  UPDATE  u_usercompany SET (user_id, group_id, dept_id, desig_id, uc_ContractType, uc_JoiningDate, uc_Status) 
                                VALUES (?,?,?,?,?,?,?)');
        $stmt->bindValue(1,$user_id);
        $stmt->bindValue(2,$postArray['group_id']);
        $stmt->bindValue(3,$postArray['dept_id']);
        $stmt->bindValue(4,$postArray['desig_id']);
        $stmt->bindValue(5,$postArray['contract_type']);
        $stmt->bindValue(6,$joining_date);
        $stmt->bindValue(7,$uc_Status);
        $stmt->execute();
*/

       
        /********** Edit User Job History ***********/
        if ($postArray['contract_type'] == 'C')
        {
            $contract_duration = $postArray['contract_duration'];
            $expiry_date = date('Y-m-d',strtotime('+'.$contract_duration.' years',strtotime($joining_date)));
        }
        else
        {
            $contract_duration = null;
            $expiry_date = null;
        }
        
        $uh_status = 'A';

      /*  $stmtUpdate = $DB->prepare("UPDATE u_userjobhistory SET uh_status = 'I' WHERE uh_user_id = $user_id");
        $stmtUpdate->execute();*/

        $stmt = $DB->prepare("UPDATE u_userjobhistory SET uh_group = ?, uh_dept = ?, 
                                uh_desig = ?, uh_contract_type = ?,uh_contract_duration = ? , uh_effective_date=?
                                WHERE uh_id = $uh_id;
                               ");
        
        $stmt->bindValue(1, $postArray['group_id']);
        $stmt->bindValue(2, $postArray['dept_id']);
        $stmt->bindValue(3, $postArray['desig_id']);
        $stmt->bindValue(4, $postArray['contract_type']);
        $stmt->bindValue(5, $contract_duration);
        $stmt->bindValue(6, $postArray['uh_effective_date']);

 /*       $stmt->bindValue(7, $joining_date);
        $stmt->bindValue(8, $expiry_date);
        $stmt->bindValue(9, $uh_status);*/
        $stmt->execute();

        $returnArray['Status'] = "UPDATED";
        $returnArray['effective_date'] = $postArray['uh_effective_date'];
        $returnArray['uh_id'] = $uh_id;

        echo json_encode($returnArray);
}

/****************************************
    Edit New Job Of Employee Ends Here
*****************************************/
/* Delete Employee Job */
function deleteEmployeeJob($uh_id)
{
    global $DB;
    $stmt   =   $DB->prepare('DELETE FROM u_userjobhistory WHERE uh_id=?');
    $stmt->bindParam(1,$uh_id);
    $stmt->execute();


         $stmt = $DB->prepare("DELETE FROM u_userleave WHERE uh_id = ?");
         $stmt->bindParam(1,$uh_id);
         $stmt->execute();

    echo "DELETED";
}

function EmployeeLeaveAdjust($postArray){

        global $DB;
        
        $returnArray = array();
        //die();
        $userleave = $postArray['userleave'];
        foreach ($userleave as $ul_id => $ul_Number) 
        {

           //////////////////////// CHECK IF ANNUAL THEN ADD MORE IN REMAINING///////
            $checkAnnualQ='SELECT lt_id,ul_Annual FROM u_userleave WHERE ul_id=?';
            $checkAnnual = $DB->prepare($checkAnnualQ);
            $checkAnnual->bindValue(1,$ul_id);
            $checkAnnual->execute();
            $checkAnnualRow=$checkAnnual->fetch(PDO::FETCH_ASSOC);
           
            /*if($checkAnnualRow['lt_id']==1)
            {*/
                $updateUL = $DB->prepare('UPDATE u_userleave SET ul_RemainingNumber=ul_RemainingNumber+(?-ul_Number) ,ul_Number=ul_Number+(?-ul_Number) WHERE ul_id=?');
                    $updateUL->bindValue(1,$ul_Number);
                    $updateUL->bindValue(2,$ul_Number);
                    $updateUL->bindValue(3,$ul_id);
                    $updateUL->execute();
           /* }*/
            ////////////////ELSE UPDATE THE USER LEAVE NUMBER///////////////
            /*else
            {
                $updateUL = $DB->prepare('UPDATE u_userleave SET ul_Number=? WHERE ul_id=?');
                $updateUL->bindValue(1,$ul_Number);
                $updateUL->bindValue(2,$ul_id);
                $updateUL->execute();
            }*/
        }

        $returnArray['Status'] ="UPDATED";
        $returnArray['userleave'] = $postArray['userleave'];
        echo json_encode($returnArray);


}

/***** DELETE Leave Adjustment Starts *****/
function deleteUL($ul_id)
{
    global $DB;
    $stmt   =   $DB->prepare('DELETE FROM u_userleave WHERE ul_id=?');
    $stmt->bindParam(1,$ul_id);
    $stmt->execute();
    echo "DELETED";
}
/***** DELETE Leave Adjustment Ends *****/

function SingleLeaveAdjust($postArray){

    global $DB;
        
   $returnArray = array();
    $ul_Number = $postArray['ul_Number'];
    $ul_id = $postArray['SingleLeaveAdjustId'];
    /*
    ul_RemainingNumber+(?-ul_Number)
    */
    $updateUL = $DB->prepare('UPDATE u_userleave SET ul_Number= ul_Number+(?-ul_RemainingNumber), ul_RemainingNumber=?  WHERE ul_id=?');
                    $updateUL->bindValue(1,$ul_Number);
                    $updateUL->bindValue(2,$ul_Number);
                    $updateUL->bindValue(3,$ul_id);
                    $updateUL->execute();
        $returnArray['Status'] ="UPDATED";
        
        echo json_encode($returnArray);
}

/*************************************************
*   Leave Application Of Admin Starts Here
**************************************************/
/*************************
**  Leave Stats
***********************/
function emp_getLeavesStats($user_id)
{
    global $DB;

    $currentYear = date('Y');
    $currentDate = date('Y-m-d');
     /*$query="SELECT UL.ul_id, UL.lt_id, UL.ul_Number, UL.ul_Year, UL.ul_DateTime, UL.ul_Status, UL.user_id, UL.ul_Annual, UL.ul_FromDate, UL.ul_ToDate, UL.ul_Remarks, UL.ul_ExpiryDate,cnf_leavetype.lt_Name,
            (SELECT COUNT(la_id) FROM u_leaveapplication WHERE user_id=$user_id AND la_Status='P' AND lt_id=UL.lt_id AND YEAR(la_Date)='$currentYear') AS pending ,
            (SELECT COUNT(la_id) FROM u_leaveapplication WHERE user_id=$user_id AND la_Status='A' AND lt_id=UL.lt_id AND YEAR(la_Date)='$currentYear') AS approved,
            (SELECT COUNT(la_id) FROM u_leaveapplication WHERE user_id=$user_id AND la_Status='R' AND lt_id=UL.lt_id AND YEAR(la_Date)='$currentYear') AS rejected
        FROM u_userleave AS UL
        INNER JOIN cnf_leavetype ON cnf_leavetype.lt_id=UL.lt_id
        WHERE UL.user_id=$user_id AND UL.ul_Year=now() AND UL.ul_Status='A'";*/ //working fine query
       $query="SELECT UL.ul_id, UL.lt_id, SUM(UL.ul_Number) AS ul_Number, SUM(UL.ul_RemainingNumber) AS ul_RemainingNumber, UL.ul_Year, UL.ul_DateTime, UL.ul_Status, UL.user_id, UL.ul_Annual, UL.ul_FromDate, UL.ul_ToDate, UL.ul_Remarks, UL.ul_ExpiryDate,cnf_leavetype.lt_Name,
            (SELECT COUNT(la_id) FROM u_leaveapplication WHERE user_id=$user_id AND la_Status='P' AND lt_id=UL.lt_id AND YEAR(la_Date)='$currentYear') AS pending ,
            (SELECT COUNT(la_id) FROM u_leaveapplication WHERE user_id=$user_id AND la_Status='A' AND lt_id=UL.lt_id AND YEAR(la_Date)='$currentYear') AS approved,
            (SELECT COUNT(la_id) FROM u_leaveapplication WHERE user_id=$user_id AND la_Status='R' AND lt_id=UL.lt_id AND YEAR(la_Date)='$currentYear') AS rejected

        FROM u_userleave AS UL
        INNER JOIN cnf_leavetype ON cnf_leavetype.lt_id=UL.lt_id
        WHERE UL.user_id=? AND UL.ul_Status=? AND (UL.ul_ExpiryDate > '$currentDate' || UL.ul_ExpiryDate = '$currentDate')
        GROUP BY UL.lt_id";

        $stmt = $DB->prepare($query);
        $stmt->bindValue(1,$user_id);
        $stmt->bindValue(2,'A');
        $leaveStatRow=array();
        $stmt->execute();
        if($stmt->rowCount()){
            while($r=$stmt->fetch(PDO::FETCH_ASSOC))
            {
                $leaveStatRow[]=$r;
            }
        }

        return $leaveStatRow;
}
/******************************8
* Active Leave Types
*******************************/
function getActiveLeaveType($user_id)
{
    global $DB;


    $stmt=  $DB->prepare('SELECT LT.lt_id,LT.lt_Name
                            FROM cnf_leavetype AS LT
                           
                            WHERE LT.lt_Status="A"  GROUP BY LT.lt_id');
    $stmt->bindParam(1,$user_id);
    $stmt->execute();
    while($row=$stmt->fetch(PDO::FETCH_ASSOC))
    {
        $ltRow[]=$row;
    }

    return $ltRow;
}

/***********************************
*   Leave Resonse
**********************************/
function getActiveLeaveReason()
{
    global $DB;

    $stmt = $DB->prepare('SELECT lr_id,lr_Title,lr_Status FROM cnf_leavereason WHERE lr_Status="A"');
    $lrRow = array();
    $stmt->execute();
    if($stmt->rowCount())
    {
        while($r=$stmt->fetch(PDO::FETCH_ASSOC))
        {
            $lrRow[]=$r;
        }
    }

    return $lrRow;

}

// Calculate Working Days
function getWorkingDays($startDate, $endDate)
{
	global $DB;
	
	$startDate = date("Y-m-d", $startDate);
	$endDate = date("Y-m-d", $endDate);
	
	// best stored as array, so you can add more than one
	$getNumberOfHolidays = "SELECT * FROM cnf_holiday
							WHERE holiday_Status = 'A' AND holiday_Date BETWEEN '$startDate' AND '$endDate'";
	$stmt = $DB->prepare($getNumberOfHolidays);
	$stmt->execute();
	
	$holidays = array();
	
	while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
	{
		$holidays[] = $row['holiday_Date'];
	}
	
	$start = new DateTime($startDate);
	$end = new DateTime($endDate);
	
	// otherwise the  end date is excluded
	$end->modify('+1 day');
	
	$interval = $end->diff($start);
	
	// total days
	$days = $interval->days;
	
	// create an iterateable period of date (P1D equates to 1 day)
	$period = new DatePeriod($start, new DateInterval('P1D'), $end);
	
	
	//$holidays = array('2017-08-31','2017-12-25');
	
	foreach($period as $dt)
	{
		$curr = $dt->format('D');
	
		// substract if Saturday or Sunday
		if ($curr == 'Sat' || $curr == 'Sun')
		{
			$days--;
		}
	
		// (optional) for the updated question
		elseif (in_array($dt->format('Y-m-d'), $holidays))
		{
			$days--;
		}
	}
	
	
	return $days;
}
/**
 * Create Leave Application
 * Last edited: Justin, 05/08/17
 * check vailability leave before processing form
 */

function createLeaveApplication($postArray, $postFile)
{
	global $DB;
	$reverseArray = array();
	$user_id = $_SESSION['user_id'];
	$error = 0;
	$errors = 0;
	$fromDate = strtotime($postArray['la_FromDate']);
	$toDate = strtotime($postArray['la_ToDate']);
	$lb_DocTmp = $postFile['lb_Doc']['tmp_name'];

	if($postFile['lb_Doc']['name']!="")
	{
		$lb_Doc = date('YmdHis').$postFile['lb_Doc']['name'];
		move_uploaded_file($lb_DocTmp, '../uploads/Leave_Docs/'.$lb_Doc);
	}
	else
	{
		$lb_Doc="";
	}

	if ($postArray['lt_id']==1)
	{
		$ul_id = '';

	}
	else
	{
		$ul_id = getUlIdFromType($postArray['lt_id']);
	}

	if($postArray['lt_id']==1)
	{

		if(!$ul_id=annualLeaveAdjustment($user_id,'A', 'LIMIT 1',$la_Date))
		{
			if(!$ul_id=annualLeaveAdjustment($user_id,'A', 'LIMIT 1,1',$la_Date))
			{
				if(!$ul_id=annualLeaveAdjustment($user_id,'A', 'LIMIT 2,1',$la_Date))
				{
					if(!$ul_id=annualLeaveAdjustment($user_id,'A', 'LIMIT 3,1',$la_Date))
					{
						$error++; //There is no annual leave to use.
						$errors++;

					}

				}
			}
		}

		if(!isset($reverseArray['remaining'][$ul_id]))
		{
			$reverseArray['remaining'][$ul_id]=1;
		}
		else
		{
			$reverseArray['remaining'][$ul_id]++;
		}
	}
	else
	{
		$ul_id = getUlIdFromType($postArray['lt_id']);
	}

	$datediff =  $toDate-$fromDate ;
	$la_Days = ($datediff/86400)+1;

	$stmt = $DB->prepare('	INSERT INTO u_leavebatch
	    						(user_id, lt_id, lr_id, lb_FromDate, lb_ToDate, lb_Days,
	    						lb_Comment, lb_Status,lb_Doc,lb_DateTime,ul_id)
	    						VALUES
	    						(?,?,?,?,?,?,
	    						?,?,?,now(),?)');
	$stmt->bindValue(1,$user_id);
	$stmt->bindValue(2,$postArray['lt_id']);
	$stmt->bindValue(3,$postArray['lr_id']);
	$stmt->bindValue(4,$postArray['la_FromDate']);
	$stmt->bindValue(5,$postArray['la_ToDate']);
	$stmt->bindValue(6,$la_Days);
	$stmt->bindValue(7,$postArray['la_Comment']);
	$stmt->bindValue(8,'P');
	$stmt->bindValue(9,$lb_Doc);
	$stmt->bindValue(10,$ul_id);
	 
	if($stmt->execute())
	{
		$lb_id = $DB->lastInsertId();
	}

	/*********************************
	 *	Email Starts here
	 **********************************/
	$employeeDetail = getEmpPersonalDetail($user_id);
	$subject = "New leave request #reference no. ".$lb_id." from ".$employeeDetail['user_FirstName']." ".$employeeDetail['user_LastName'];

	$employeeDetail = getEmpPersonalDetail($user_id);
	if($postArray['contract_type']=="C")
	{
		$contract_type = "Contract";
	}
	else
	{
		$contract_type = "Permanent";
	}

	$fields = array();
	$fields{"sv2_Reference"} = "Reference No: ".$lb_id;
	$fields{"sv2_Applicant"} = "Applicant: ".$employeeDetail['user_FirstName']." ".$employeeDetail['user_LastName'];
	$fields{"sv2_group"} = "Group: ".getGroupFromId($_SESSION['group_id'])['group_Name'];
	$fields{"sv2_dept"} = "Department: ".getDepartmentFromId($_SESSION['dept_id'])['dept_Name'];
	$fields{"sv2_desig"} = "Designation: ".getDesignationFromId($_SESSION['desig_id'])['desig_Name'];
	$fields{"sv2_Type"} = "Leave Type: ".getLeaveTypeFromId($postArray['lt_id'])['lt_Name'];
	$fields{"sv2_from"} = "Leave Start On: ".$postArray['la_FromDate'];
	$fields{"sv2_to"} = "Leave End On: ".$postArray['la_ToDate'];
	$fields{"sv2_days"} = "No. of Leaves Taken: ".$la_Days;
	$fields{"sv2_status"} = "Request Status: Pending";

	$body = "Dear Sir/Madam,\n\n";

	foreach($fields as $a => $b)
	{
		$body .= sprintf("%s\n",$b,$_REQUEST[$a]);
	}
	 
	$body.="\n \nPlease APPROVE OR REJECT via system and don't reply to this email.\n";
	$body.="Thank you.\n";
	$body.="Your Faithful,\nHR Manager";

	sendMail($postArray['user_Email'], $subject, $body, 'New Job Creation');

	/*********************************
	 *	Email Ends here
	 **********************************/

	$leavaBatchStatus = 0;
	$leavaBatchDays = 0;

	for($i=1;$i<=$la_Days;$i++)
	{
		$la_Date  = date('Y-m-d',$fromDate);
		$fromDate = strtotime("+1 day", $fromDate);
		 
		$holidayQ = "SELECT holiday_id FROM cnf_holiday WHERE holiday_Date=?";
		$holiday = $DB->prepare($holidayQ);
		$holiday->bindValue(1,$la_Date);
		$holiday->execute();
		$holiday->rowCount();
		if($holiday->rowCount()==1)
		{
			$error++;
			/*$errors++;*/
			/***************************Delete the Batch if No leave to create due to Holiday***************/
			/*********************  Extra Code ***************************/
			/*
			  
			*/
			/*********************  Extra Code ***************************/
			/***************************Delete the Batch if No leave to create due to Holiday***************/

		}
		else{
			$day_Name = date('l', strtotime($la_Date));
			$day_id = GetDayId($day_Name);
			$stmtWorkingDay = $DB->prepare("SELECT wd_id FROM cnf_workingday WHERE wd_On ='N' AND day_id = $day_id");
			$stmtWorkingDay->execute();
			if($stmtWorkingDay->rowCount()>0){
				$error++;
				/*$errors++;*/
			}
			 
		}


		/******************ANNUAL LEAVE ADJUSTMENT ***************/
		 
		if(empty($error)) //If there is no holiday on that date then .No error.
		{
			$ul_id=0;
			if($postArray['lt_id']==1)
			{

				if(!$ul_id=annualLeaveAdjustment($user_id,'A', 'LIMIT 1',$la_Date))
				{
					if(!$ul_id=annualLeaveAdjustment($user_id,'A', 'LIMIT 1,1',$la_Date))
					{
						if(!$ul_id=annualLeaveAdjustment($user_id,'A', 'LIMIT 2,1',$la_Date))
						{
							if(!$ul_id=annualLeaveAdjustment($user_id,'A', 'LIMIT 3,1',$la_Date))
							{
								$error++; //There is no annual leave to use.
								$errors++;

							}

						}
					}
				}
				 
				if(!isset($reverseArray['remaining'][$ul_id]))
				{
					$reverseArray['remaining'][$ul_id]=1;
				}
				else
				{
					$reverseArray['remaining'][$ul_id]++;
				}
			}
			else
			{
				$ul_id = getUlIdFromType($postArray['lt_id']);
			}
		}
		 
		/******************END OF ANNUAL LEAVE ADJUSTMENT ***************/

		/*      Check Remaining Other Leaves        */
		$checkRemaingLeave = $DB->prepare("SELECT ul_RemainingNumber FROM u_userleave WHERE ul_id = $ul_id ul_RemainingNumber < $leavaBatchDays");
		$checkRemaingLeave->execute();
		if($checkRemaingLeave->rowCount()>0)
		{
			$error++;
		}


		if(empty($error))
		{
			$stmt =  $DB->prepare('INSERT INTO u_leaveapplication (user_id,lt_id,lr_id,la_Comment,la_FromDate, la_ToDate,la_Date,la_Days,la_Status,la_DateTime,ul_id,lb_id) VALUES (?,?,?,?,?,?,?,?,?,now(),?,?)');
			$stmt->bindValue(1,$user_id);
			$stmt->bindValue(2,$postArray['lt_id']);
			$stmt->bindValue(3,$postArray['lr_id']);
			$stmt->bindValue(4,$postArray['la_Comment']);
			$stmt->bindValue(5,$postArray['la_FromDate']);
			$stmt->bindValue(6,$postArray['la_ToDate']);
			$stmt->bindValue(7,$la_Date);
			$stmt->bindValue(8,1);
			$stmt->bindValue(9,'P');
			$stmt->bindValue(10,$ul_id);
			$stmt->bindValue(11,$lb_id);
			 
			if($stmt->execute())
			{
				$leavaBatchStatus++;
				$leavaBatchDays++;
			}
			else
			{
				$error++;
				$errors++;
			}
		}
		$error=0;
	}
	 
	$updatelb_Days = $DB->prepare('UPDATE u_leavebatch SET lb_Days = ?, ul_id = ? WHERE lb_id = ?');
	$updatelb_Days->bindValue(1,$leavaBatchDays);
	$updatelb_Days->bindValue(2,$ul_id);
	$updatelb_Days->bindValue(3,$lb_id);
	$updatelb_Days->execute();
	 
	if($leavaBatchStatus==0)
	{
		$delstmt = $DB->prepare('DELETE FROM u_leavebatch WHERE lb_id=?');
		$delstmt->bindValue(1,$lb_id);
		 
		if($delstmt->execute())
		{
			//
		}
	}

	if(empty($errors))
	{
		echo "INSERTED";
	}

	if($postArray['lt_id']==1 && !empty($reverseArray['remaining'])){
		foreach ($reverseArray['remaining'] as $key => $value) {

			$stmtRemaining = $DB->prepare("UPDATE u_userleave SET ul_RemainingNumber = ul_RemainingNumber+$value WHERE ul_id = $key");
			$stmtRemaining->execute();

			 
		}
	}

}
/*
function createLeaveApplication($postArray, $postFile)
{
    global $DB;
    $reverseArray= array();
    $user_id=$_SESSION['user_id'];
    $error=$errors=0;
    $fromDate =strtotime($postArray['la_FromDate']);
    $toDate   =strtotime($postArray['la_ToDate']);
    $lb_DocTmp = $postFile['lb_Doc']['tmp_name'];

    $numberOfDaysApplied = getWorkingDays($fromDate, $toDate);
    
    // Check Remaining Other Leaves
    $checkRemaingLeave = $DB->prepare("SELECT SUM(ul_RemainingNumber) AS totalRemainingLeaves 
    									FROM u_userleave 
    									WHERE user_id = $user_id AND ul_Status = 'A'");
    $checkRemaingLeave->execute();

    if($checkRemaingLeave->rowCount()>0)
    {
    	$row = $checkRemaingLeave->fetch(PDO::FETCH_ASSOC);
    	$remainingLeave = $row['totalRemainingLeaves'];
    	
    	// process application form if the staff has enough leaves to apply
    	if ($remainingLeave >= $numberOfDaysApplied)
    	{
    		$lb_Doc = "";
    		
    		if($postFile['lb_Doc']['name']!="")
    		{
    			$lb_Doc = date('YmdHis').$postFile['lb_Doc']['name'];
    			move_uploaded_file($lb_DocTmp, '../uploads/Leave_Docs/'.$lb_Doc);
    		}
    		
    		// insert records into table
    		$sql = 'INSERT INTO u_leavebatch 
    				(user_id, lt_id, lr_id, lb_FromDate, lb_ToDate, lb_Days, lb_Comment, 
    				lb_Status,lb_Doc,lb_DateTime) 
    				VALUES 
    				(?,?,?,?,?,?,?,?,?,now())';
    		$stmt = $DB->prepare($sql);
    		$stmt->bindValue(1,$user_id);
    		$stmt->bindValue(2,$postArray['lt_id']);
    		$stmt->bindValue(3,$postArray['lr_id']);
    		$stmt->bindValue(4,$postArray['la_FromDate']);
    		$stmt->bindValue(5,$postArray['la_ToDate']);
    		$stmt->bindValue(6,$numberOfDaysApplied);
    		$stmt->bindValue(7,$postArray['la_Comment']);
    		$stmt->bindValue(8,'P');
    		$stmt->bindValue(9,$lb_Doc);
    		$stmt->execute();
    		
    		$lb_id = $DB->lastInsertId();
    		
    		// create each single day leave records
    		
    		
    		// notify employee and admin on new leave request via email notification
    		$employeeDetail = getEmpPersonalDetail($user_id);
    		$subject = "New leave request #reference no. ".$lb_id." from ".$employeeDetail['user_FirstName']." ".$employeeDetail['user_LastName'];
    		 
    		$fields = array();
    		$fields{"sv2_Reference"} = "Reference No: ".$lb_id;
    		$fields{"sv2_Applicant"} = "Applicant: ".$employeeDetail['user_FirstName']." ".$employeeDetail['user_LastName'];
    		
    		$fields{"sv2_loc"} = "Group: ".getLocationFromID($_SESSION['loc_id'])['loc_shortName'];
    		$fields{"sv2_dept"} = "Department: ".getDepartmentFromId($_SESSION['dept_id'])['dept_Name'];
    		$fields{"sv2_desig"} = "Designation: ".getDesignationFromId($_SESSION['desig_id'])['desig_Name'];
    		$fields{"sv2_Type"} = "Leave Type: ".getLeaveTypeFromId($postArray['lt_id'])['lt_Name'];
    		$fields{"sv2_from"} = "Leave Start On: ".$postArray['la_FromDate'];
    		$fields{"sv2_to"} = "Leave End On: ".$postArray['la_ToDate'];
    		$fields{"sv2_days"} = "No. of Leaves Taken: ".$numberOfDaysApplied;
    		$fields{"sv2_status"} = "Request Status: Pending";
    		
    		$body = "Dear Sir/Madam,\n\n";
    		
    		foreach($fields as $a => $b)
    		{
    			$body.= sprintf("%s\n",$b,$_REQUEST[$a]);
    		}    		
    		 
    		$body.="\n \nPlease APPROVE OR REJECT via system and don't reply to this email.\n";
    		$body.="Thank you.\n";
    		$body.="Your Faithful,\nHR Manager";
    		
    		sendMail($postArray['user_Email'], $subject, $body, 'New Leave Request');
    		
    		$message = "Your leave request has been sent to your superrior for further action.";
    		
    		echo "INSERTED";
    	}
    	else 
    	{
    		$message = "Sorry, you don't have enough leave to apply.";
    		
    		echo "not enough leave";
    	}
    }
    else 
    {
    	$message = "You have 0 leave to apply.";
    	
    	echo "no leave";
    }
}*/

/**********************************
*   Get UL FROM leave Type
************************************/
function getUlIdFromType($lt_id){
     global $DB;
     $user_id=$_SESSION['user_id'];
     $ul_Year = date('Y');
     $stmt= $DB->prepare("SELECT ul_id FROM u_userleave WHERE user_id =$user_id AND ul_Year =  $ul_Year AND lt_id = $lt_id ");
      $stmt->execute();
      if($stmt->rowCount()>0){

        $row=$stmt->fetch(PDO::FETCH_ASSOC);
        return $row['ul_id'];
      }
      else{
        return 0;
      }
}

function GetDayId($day_Name)
{
    global $DB;

        $query = "SELECT day_id FROM cnf_day WHERE day_Name LIKE '$day_Name'";
        $stmt =  $DB->prepare($query);
        $stmt->execute();
        if($stmt->rowCount()>0){

             $row=$stmt->fetch(PDO::FETCH_ASSOC);
            return $row['day_id'];

        }
    
}

function deleteLeaveBatch($lb_id){



						/*********************************
				       	*	Email Starts here
				       	**********************************/
				       		$employeeDetail = getEmpPersonalDetail($user_id);
				       		$batchDetail = getLeaveBatchFromId($lb_id);
				       		$subject = "Leave request #reference no. ".$lb_id." has been DELETED";

				       		$employeeDetail = getEmpPersonalDetail($user_id);
				       		
				       		
						    $fields = array(); 
						    $fields{"sv2_Reference"} = "Reference No: ".$lb_id; 
						    $fields{"sv2_Applicant"} = "Applicant: ".$employeeDetail['user_FirstName']." ".$employeeDetail['user_LastName']; 
						  
						    $fields{"sv2_group"} = "Group: ".getGroupFromId($_SESSION['group_id'])['group_Name'];
						    $fields{"sv2_dept"} = "Department: ".getDepartmentFromId($_SESSION['dept_id'])['dept_Name']; 
						    $fields{"sv2_desig"} = "Designation: ".getDesignationFromId($_SESSION['desig_id'])['desig_Name']; 
						    $fields{"sv2_Type"} = "Leave Type: ".getLeaveTypeFromId($batchDetail['lt_id'])['lt_Name']; 
						    $fields{"sv2_from"} = "Leave Start On: ".$batchDetail['lb_FromDate']; 
						    $fields{"sv2_to"} = "Leave End On: ".$batchDetail['lb_ToDate']; 
						    $fields{"sv2_days"} = "No. of Leaves Taken: ".$batchDetail['lb_Days']; 
						    $fields{"sv2_status"} = "Request Status: DELETED"; 
						    
						    
						    
						    $body = "Dear Sir/Madam,\n\n"; 
						    

						    foreach($fields as $a => $b)
						    {   
						        $body .= sprintf("%s\n",$b,$_REQUEST[$a]); 
						    }
						    
						   
						    $body.="\n \nPlease APPROVE OR REJECT via system and don't reply to this email.\n";
						    $body.="Thank you.\n";
						    $body.="Your Faithful,\nHR Manager";

						    sendMail($postArray['user_Email'], $subject, $body, 'Leave DELETED');
				    
				       	/*********************************
				       	*	Email Ends here
				       	**********************************/

            global $DB;
            $delstmt = $DB->prepare('DELETE FROM u_leavebatch WHERE lb_id=?');
            $delstmt->bindValue(1,$lb_id);
            if($delstmt->execute())
            {
                



            	    	


                $delstmtApp = $DB->prepare('DELETE FROM u_leaveapplication WHERE lb_id=?');
                $delstmtApp->bindValue(1,$lb_id);
                if($delstmtApp->execute())
                {
                    echo "DELETED";
                }    
            }    
}



/******************************************
*   Get Employee Email From ID
*******************************************/
function getEmailFromId($user_id){

    global $DB;
    $stmt =  $DB->prepare('SELECT user_Email FROM u_user WHERE user_id=?');
    $stmt->bindValue(1,$user_id);
    $stmt->execute();
    if($stmt->rowCount()<0)
    {
        return "";
    }
    else
    {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['user_Email'];
    }
}



/*******************************
*	DB Backup Starts Here
********************************/

function deleteDbBackup($db_id){

	 global $DB;
            $delstmt = $DB->prepare('DELETE FROM adm_dbbackup WHERE db_id=?');
            $delstmt->bindValue(1,$db_id);
            if($delstmt->execute())
            {
            	echo "DELETED";
            }

}



/******************************
*   View My Profile
*******************************/
 function viewMyProfile($user_id){
    global $DB;

    $stmt = $DB->prepare('SELECT user_id,user_UserCode,user_FirstName,user_LastName,user_FatherName,user_Email,user_PhoneNo,user_Status,user_PicPath,user_DOB,user_Gender,user_TAddress,user_PAddress,user_DateTime,user_JoiningDateTime FROM u_user WHERE user_id=?');
    $stmt->bindParam(1,$user_id);
    $stmt->execute();
    $row=$stmt->fetch(PDO::FETCH_ASSOC);
    if($row['user_Status']=='A'){ $user_Status="Active";} else{ $user_Status="In-Active"; }
    if($row['user_Gender']=='M'){ $user_Gender="Male"; }else{ $user_Gender="Female";}
    $DATA='
        <div class="row">
        <form class="form-horizontal" id="update-my-profile" enctype="multipart/form-data" method="post" accept-charset="utf-8" onsubmit="updateMyProfile()">
        <div class="col-sm-6">
        <div class="panel panel-primary">
          <div class="panel-heading">
                <div class="box-header">
                      <h6 class="box-title"><i class="fa fa-pencil"></i>&nbsp;Personal Details</h6>
                </div>
          </div>
          <div class="panel-body">
        <div class="box box-primary">
       
            <div class="box-body">
            <div class="form-group">
                <label for="group_name" class="col-sm-3 control-label input-label"><small>Photo</small></label>
                
                <div>
                <div class="col-sm-9">  
                    <img src="'.$row['user_PicPath'].'" style="height:100px;width:200px">
                   <input type="file" name="user_PicPath" onchange="readURL(this)">                
                </div>
                    
                </div>
            </div>
            <div class="form-group">
                <label for="group_name" class="col-sm-3 control-label input-label"><small>First Name:<span class="text-danger"> </span></small></label>
                <div class="col-sm-9">
                   <input class="form-control" name="user_FirstName" id="userFirstName" value="'.$row['user_FirstName'].'">                
                </div>
            </div>
            <div class="form-group">
                <label for="group_name" class="col-sm-3 control-label input-label"><small>Last Name:<span class="text-danger"> </span></small></label>
                <div class="col-sm-9">
                   <input class="form-control" name="user_LastName" id="userLastName" value="'.$row['user_LastName'].'"> 
                </div>
            </div>
            <div class="form-group">
                <label for="group_name" class="col-sm-3 control-label input-label"><small>Father Name:</small></label>
                <div class="col-sm-9">
                   <input class="form-control" name="user_FatherName" id="userFatherName" value="'.$row['user_FatherName'].'">            
                </div>
            </div>
            <div class="form-group">
                <label for="group_name" class="col-sm-3 control-label input-label"><small>Date of Birth:</small></label>
                <div class="col-sm-9">                                       
                   <input type="date" class="form-control" name="user_DOB" id="userDOB" value="'.$row['user_DOB'].'">                    
                </div>
            </div>
            <div class="form-group">
                <label for="group_name" class="col-sm-3 control-label"><small>Gender:</small></label>
                <div class="col-sm-9">
                  <select class="form-control" name="user_Gender" id="userGender">
                    <option value="M"';
                    if($row['user_Gender']=='M'){ $DATA.='selected="selected"'; }
                    $DATA.='>Male</option>
                    <option value="F"';
                    if($row['user_Gender']=='F'){ $DATA.='selected="selected"'; }
                    $DATA.='>Female</option>
                    </select>                  
                 </div>
            </div>
            <div class="form-group">
                <label for="group_name" class="col-sm-3 control-label"><small>Phone (Temporary):</small></label>
                <div class="col-sm-9">
                   <input type="text" class="form-control" name="user_PhoneNo" id="userPhoneNo" value="'.$row['user_PhoneNo'].'">                 
                 </div>
            </div>
         
            <div class="form-group">
                <label for="group_name" class="col-sm-3 control-label"><small>Local Address:</small></label>
                <div class="col-sm-9">
                    <textarea  class="form-control" placeholder="Temporary Address" cols="30" rows="6" name="user_TAddress" id="userTAddress">'.$row['user_TAddress'].'</textarea>               
                 </div>
            </div>
            <div class="form-group">
                <label for="group_name" class="col-sm-3 control-label"><small>Permanent Address:</small></label>
                <div class="col-sm-9">
                   <textarea  class="form-control" placeholder="Permanent Address" cols="30" rows="6" name="user_PAddress" id="userPAddress">'.$row['user_PAddress'].'</textarea>                
                 </div>
            </div>
            <div class="form-group">
                <div class="col-sm-12">
                    <label for="group_name" class="control-label"><h3>Account Login</h3></label>
                </div>
            </div>
            
            <div class="form-group">
                <label for="group_name" class="col-sm-3 control-label"><small>E-Mail:<span class="text-danger"> </span></small></label>
                <div class="col-sm-9">
                   '.$row['user_Email'].'             
                </div>
            </div>
            <div class="form-group">
                <label for="group_name" class="col-sm-3 control-label"><small>Password:<span class="text-danger"> </span></small></label>
                <div class="col-sm-9">
                    <a href="change_password.php" class="btn btn-primary btn-xs">Change Password</a>     
                </div>
            </div>
            
                </div><!--End of box-body-->
               </div><!--End of box-primary-->
            </div><!--End of panel body-->
            </div><!--End of panel-->
           </div><!--End of col-sm-6-->'; 
    
    $stmt = $DB->prepare('SELECT UC.uc_id, UC.user_id, UC.dept_id, UC.desig_id, UC.uc_JoiningDate, UC.uc_ExitDate, UC.uc_Status,cnf_dept.dept_Name,cnf_designation.desig_Name,cnf_group.group_Name FROM u_usercompany AS UC INNER JOIN cnf_dept ON cnf_dept.dept_id=UC.dept_id INNER JOIN cnf_designation ON cnf_designation.desig_id=UC.desig_id INNER JOIN cnf_group ON cnf_group.group_id=UC.group_id WHERE user_id=? ORDER BY UC.uc_id DESC');
    $stmt->bindParam(1,$user_id);
    $stmt->execute();
    $ucRow=$stmt->fetch(PDO::FETCH_ASSOC);

    if($ucRow['uc_Status']=='A'){ $uc_Status="Active";} else{ $uc_Status="In-Active"; }
    $DATA.='       
        <div class="col-sm-6">

        <div class="panel panel-info">
          <div class="panel-heading">
                <div class="box-header">
                      <h6 class="box-title"><i class="fa fa-briefcase"></i>&nbsp;Company Details</h6>
                </div>
          </div>
          <div class="panel-body">
            <div class="box box-danger">
                    
                <div class="box-body">
                <div class="form-group">
                    <label for="group_name" class="col-sm-3 control-label"><small>Employee ID:<span class="text-danger"> </span></small></label>
                    <div class="col-sm-9">
                       '.$row['user_UserCode'].'
                       <div id="user_UserCodeCheck"></div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="group_name" class="col-sm-3 control-label"><small>Group:</small></label>
                    <div class="col-sm-9">
                        '.$ucRow['group_Name'].'
                    </div>
                </div>

                <div class="form-group">
                    <label for="group_name" class="col-sm-3 control-label"><small>Department:</small></label>
                    <div class="col-sm-9">
                        '.$ucRow['dept_Name'].'
                    </div>
                </div>
                <div class="form-group">
                    <label for="group_name" class="col-sm-3 control-label"><small>Designation:</small></label>
                    <div class="col-sm-9">
                        '.$ucRow['desig_Name'].'        
                    </div>
                </div>
                <div class="form-group">
                    <label for="group_name" class="col-sm-3 control-label"><small>Joining Date:<span class="text-danger"> </span></small></label>
                    <div class="col-sm-9">                    
                        '.$ucRow['uc_JoiningDate'].'               
                    </div>
                </div>
                <div class="form-group">
                    <label for="group_name" class="col-sm-3 control-label"><small>Exit Date:<span class="text-danger"> </span></small></label>
                    <div class="col-sm-9">
                       '.$ucRow['uc_ExitDate'].'     
                    </div>
                </div>
                <div class="form-group">
                    <label for="group_name" class="col-sm-3 control-label"><small>Status:<span class="text-danger"> </span></small></label>
                    <div class="col-sm-9">
                       '.$uc_Status.'     
                    </div>
                </div>
                <input type="hidden" name="user_PicPathOld" value="'.$row['user_PicPath'].'">

                </div><!--End of box-body-->
            </div><!--End of box-danger-->
        </div><!--End of panel body-->
        </div><!--End of panel-->

         <div class="panel panel-success">
          <div class="panel-heading">
                <div class="box-header">
                      <h6 class="box-title"><i class="fa fa-file"></i>&nbsp;Documents</h6>
                </div>
          </div>
          <div class="panel-body">
                <div class="box box-danger">
                   <div class="box-header">
                          <h3 class="box-title"><i class="fa fa-file"></i>&nbsp;Documents</h3>
                        </div>
                        <div class="box-body">
                        <div class="form-group row">
                                    <stong for="group_name" class="col-sm-3 "><small>Documents</small></stong>
                                    <div class="col-sm-9">
                                        Download
                                    </div>
                            </div>';
                $stmt = $DB->prepare('SELECT UDC.udc_id, UDC.doc_id, UDC.user_id, UDC.udc_Path, DOC.doc_Name,DOC.doc_Type FROM u_doclist AS UDC
                            INNER JOIN cnf_document AS DOC ON DOC.doc_id=UDC.doc_id
                             WHERE user_id=?');
                        $stmt->bindParam(1,$user_id);
                        $stmt->execute();
                        while($docRow=$stmt->fetch(PDO::FETCH_ASSOC))
                        { 
                            $DATA.='
                             <div class="form-group row">
                                    <span for="group_name" class="col-sm-3 "><small>'.$docRow['doc_Name'].'</small></span>
                                    <div class="col-sm-9">
                                                      
                                    ';
                            if($docRow['doc_Type']=='F')
                            {
                                $DATA.='

                                <a href="'.$docRow['udc_Path'].'" target="_blank">Download</a>';
                            }
                            elseif($docRow['doc_Type']=='I')
                            {
                                $DATA.='<a href="'.$docRow['udc_Path'].'" target="_blank"><img src="'.$docRow['udc_Path'].'" style="height:60px;width:100px"></a> ';
                            }

                            $DATA.='</div>
                                </div>';
                        }
                        
                        $DATA.='
                                 </div>   

                </div><!--End of box-danger-->
        </div><!--End of panel body-->
        </div><!--End of panel-->
        </div><!--End of col-sm-6-->
        ';
       $DATA.='<div class="col-sm-12">
                                <div class="panel panel-warning">
          <div class="panel-heading">
                <div class="box-header">
                      <h6 class="box-title"><i class="fa fa-file"></i>&nbsp;Jobs</h6>
                </div>
          </div>
          <div class="panel-body">
                <div class="box box-danger">
                        <div class="box-header">
                            
                        </div>
                        <div class="box-body">';
                         
                        $sqlSelectJobHistory = "SELECT * FROM u_userjobhistory AS UH
                                    INNER JOIN cnf_group AS GP ON GP.group_id = UH.uh_group
                                    INNER JOIN cnf_dept AS DP ON DP.dept_id = UH.uh_dept
                                    INNER JOIN cnf_designation AS DE ON DE.desig_id = UH.uh_desig
                                    WHERE UH.uh_user_id = ?";
                        $stmt = $DB->prepare($sqlSelectJobHistory);
                        $stmt->bindValue(1, $user_id);
                        $stmt->execute();
                        
                        $DATA.='<div class="table-responsive" >
                                <table class="table" id="addNewEmployeeJobTable">
                        <thead>
                            <tr>
                                <td>Effective</td>
                                <td>Group</td>
                                <td>Department</td>
                                <td>Designation</td>
                                <td>Contract Type</td>
                                <td>Status</td>
                                
                            </tr>
                        </thead>
                        <tbody>
                            <tr id="addNewEmployeeJobRow" style="display:none">
                                <td class="effective_date"></td>
                                <td class="groupName"></td>
                                <td class="deptName"></td>
                                <td class="desigName"></td>
                                <td class="contractType"></td>
                                <td class="Status"></td>
                                
                                
                                ';
                        
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
                        {
                            $effective_date = $row['uh_effective_date'];
                            $group = $row['group_Name'];
                            $dept = $row['dept_Name'];
                            $desig = $row['desig_Name'];
                             
                            if ($row['uh_contract_type'] == "P")
                            {
                                $ct = "Permenant";
                            }
                            elseif ($row['uh_contract_type'] == "C")
                            {
                                $ct = "Contract";
                            }
                             
                            if ($row['uh_status'] == "A")
                            {
                                $status = "Active";
                            }
                            else
                            {
                                $status = "Inactive";
                            }
                             
                            $DATA.='    <tr id="jobrow'.$row['uh_id'].'">
                                <td class="effective_date">'.$effective_date.'</td>
                                <td class="groupName">'.$group.'</td>
                                <td class="deptName">'.$dept.'</td>
                                <td class="desigName">'.$desig.'</td>
                                <td class="contractType">'.$ct.'</td>
                                <td class="Status">'.$status.'</td>
                               
                            </tr>';
                        }
                        
                        $DATA.='    </tbody>
                    </table>
                    </div>
                    </div>
                        
                </div><!--End of box-danger-->
        </div><!--End of panel body-->
        </div><!--End of panel-->
        </div>';
                        
                        $DATA.='<div class="col-sm-12">
                                <div class="panel panel-success">
          <div class="panel-heading">
                <div class="box-header">
                      <h6 class="box-title"><i class="fa fa-file"></i>&nbsp;Leave Assignment by Year</h6>
                </div>
          </div>
          <div class="panel-body">
                <div class="box box-danger">
                   <div class="box-header">
                         
                        </div>
                        <div class="box-body">';
                         
                        $sqlSelectUserLeave = " SELECT * FROM u_userleave AS UL
                                                INNER JOIN cnf_leavetype AS LT ON LT.lt_id = UL.lt_id
                                                WHERE UL.user_id = ? AND UL.ul_Annual = ?";
                        $stmt = $DB->prepare($sqlSelectUserLeave);
                        $stmt->bindValue(1, $user_id);
                        $stmt->bindValue(2, 'Y');
                        $stmt->execute();
                        
                        $DATA.="<p><h3><u>Annual Leaves</u></h3></p>
                                <table class='table table-bordered' id='AnnualLeaveTable'>
                                    <thead>
                                        <tr id=''>
                                            <td class='leaveYear'>Year</td>
                                            <td class='lt_Name'>Type</td>
                                            <td class='leaveNumber'>Leave Balance</td>
                                            <td class='leaveExpiryDate'>Expired On</td>
                                            <td class='leaveStatus'>Status</td>
                                            
                                            
                                       
                                        </tr>
                                    </thead>
                                    <tbody>";
                        
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
                        {
                            $year = $row['ul_Year'];
                            $leave_type = $row['lt_Name'];
                            $remaining_leave = $row['ul_RemainingNumber'];
                            $expiry_date = $row['ul_ExpiryDate'];
                            $ul_id = $row['ul_id'];
                            
                            if ($row['ul_Status'] == "A")
                            {
                                $status = "Active";
                            }
                            else
                            {
                                $status = "Inactive";
                            }
                            
                            $DATA.="    <tr id='ULrow".$ul_id."'>
                                            <td>$year</td>
                                            <td>$leave_type</td>
                                            <td class='Year".$year."UL".$ul_id."'>$remaining_leave</td>
                                            <td>$expiry_date</td>
                                            <td>$status</td>
                                           
                                            
                                        </tr>";
                        }
                        
                        $DATA.='    </tbody>
                                </table>';
                        
                        $sqlSelectUserLeave = " SELECT * FROM u_userleave AS UL
                                                INNER JOIN cnf_leavetype AS LT ON LT.lt_id = UL.lt_id
                                                WHERE UL.user_id = ? AND UL.ul_Annual = ?";
                        $stmt = $DB->prepare($sqlSelectUserLeave);
                        $stmt->bindValue(1, $user_id);
                        $stmt->bindValue(2, 'N');
                        $stmt->execute();
                        
                        $DATA.="<hr><p><h3><u>Other Leaves</u></h3></p>
                                <table class='table table-bordered' id='OtherLeaveTable'>
                                    <thead>
                                        <tr>
                                            <td class='leaveYear'>Year</td>
                                            <td class='lt_Name'>Type</td>
                                            <td class='leaveNumber'>Leave Balance</td>
                                            <td class='leaveExpiryDate'>Expired On</td>
                                            <td class='leaveStatus'>Status</td>
                                            
                                        </tr>
                                    </thead>
                                    <tbody>";
                        
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
                        {
                            $year = $row['ul_Year'];
                            $leave_type = $row['lt_Name'];
                            $remaining_leave = $row['ul_RemainingNumber'];
                            $expiry_date = $row['ul_ExpiryDate'];
                            $ul_id = $row['ul_id'];
                             
                            if ($row['ul_Status'] == "A")
                            {
                                $status = "Active";
                            }
                            else
                            {
                                $status = "Inactive";
                            }
                            
                            $DATA.="    <tr id='ULrow".$ul_id."'>
                                            <td>$year</td>
                                            <td>$leave_type</td>
                                            <td class='Year".$year."UL".$ul_id."'>$remaining_leave</td>
                                            <td>$expiry_date</td>
                                            <td>$status</td>
                                            
                                            
                                        </tr>";
                        }
                        
                        $DATA.='    </tbody>
                                </table>';
                        
                        $DATA.='</div>
                        <div class="form-group text-left" >
                            <div class="col-sm-offset-2 col-sm-9">
                                  
                                <button type="submit" class="btn btn-default btn-success pull-right" style="margin-bottom: 20px;" id="update" ><span class="glyphicon glyphicon-floppy-saved"></span> Save</button>              
                                
                            </div>
                        </div>
                        
                </div><!--End of box-danger-->
        </div><!--End of panel body-->
        </div><!--End of panel-->
        </div>';
     $DATA.='</form> </div>   

    
        ';





echo $DATA;

 }
 /**************************
 *  UPDATE My Profile
 ***************************/
 function updateMyProfile($postArray,$filesArray)
{
    global $DB;
                    
    $img_PicPath=$postArray['user_PicPathOld'];
    $imgFile = $filesArray['user_PicPath']['name'];
    $tmp_dir = $filesArray['user_PicPath']['tmp_name'];
    $img_direcotry="../uploads/employee_images/";
    $img_NewName=round(microtime(true) * 1000).$filesArray['user_PicPath']['name'];
    if($imgFile)
    {
        move_uploaded_file($tmp_dir, $img_direcotry.$img_NewName);
        $img_PicPath=$img_direcotry.$img_NewName;
        $_SESSION['user_PicPath'] = $img_PicPath;
    } 
    $stmt= $DB->prepare('UPDATE `u_user` SET user_PhoneNo=?,user_FirstName=?,user_LastName=?,user_PicPath=?,user_DOB=?,user_Gender=?,user_TAddress=?,user_PAddress=?,  user_FatherName=? WHERE user_id=? ');
   
    $stmt->bindValue(1,$postArray['user_PhoneNo']);
    
    $stmt->bindValue(2,$postArray['user_FirstName']);
    $stmt->bindValue(3,$postArray['user_LastName']);
    
    $stmt->bindValue(4,$img_PicPath);
    $stmt->bindValue(5,$postArray['user_DOB']);
    $stmt->bindValue(6,$postArray['user_Gender']);
    $stmt->bindValue(7,$postArray['user_TAddress']);
    $stmt->bindValue(8,$postArray['user_PAddress']);

    $stmt->bindValue(9,$postArray['user_FatherName']);
    $stmt->bindValue(10,$_SESSION['user_id']);

    
    if($stmt->execute())
        {

                echo "UPDATED";
        }
    }


function expireLeaveNotification(){

	global $DB;

	 $today  = date('d-m-Y');
	 echo $expiry_date = date('Y-m-d', strtotime('+30 days'));

	 $stmt = $DB->prepare("SELECT * FROM  u_userjobhistory WHERE uh_expiry_date <= '$expiry_date'");
	
	 $stmt->execute();
	if($stmt->rowCount()<=0)
    {
        echo "";
    }
    else
    {
    	
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){

        	
        
        		/*********************************
		       	*	Email Starts here
		       	**********************************/
		       		$employeeDetail = getEmpPersonalDetail($row['uh_user_id']);
		       		$subject = "Employee's contract (".$employeeDetail['user_UserCode'].", ".$employeeDetail['user_FirstName']. " ".$employeeDetail['user_LastName'].") is going to expired soon.";

		  
		       		
				    $fields = array(); 
				    $fields{"sv2_code"} = "Employee ID: ".$employeeDetail['user_UserCode']; 
				    $fields{"sv2_Name"} = "Employee Name: ".$employeeDetail['user_FirstName']." ".$employeeDetail['user_LastName']; 
				    $fields{"sv2_expire"} = "Expire ON: ".$row['uh_expiry_date']; 


				  
				  
				    
				    
				    
				    $body = "Dear Sir/Madam,\n\n"; 
				    

				    foreach($fields as $a => $b)
				    {   
				        $body .= sprintf("%s\n",$b,$_REQUEST[$a]); 
				    }
				    
				   
				    $body.="\n \nKindly create a new contract and assign new job and leave for him/her..\n";
				    $body.="Thank you.\n";
				    

				    sendMail($postArray['user_Email'], $subject, $body, 'New Job Creation');
		    
		       	/*********************************
		       	*	Email Ends here
		       	**********************************/

        }
        
    }

}


function sendMail($to, $subject, $body, $type)
{
	global $DB;

	$to.=" , just1st_85@hotmail.com, admin@mispmis.com";
	$from = "admin@mispmis.com"; 
	$headers = 'From: Bearotech Employee Management System <admin@mispmis.com>'. "\r\n";
	$headers .= 'Cc:' .$from. "\r\n";
	$headers .= 'Bcc: just1st_85@hotmail.com' . "\r\n";

    $send = mail($to, $subject, $body, $headers);

    $body = addslashes($body);
   // $body = mysqli_real_escape_string($body);

    $stmt = $DB->prepare("INSERT INTO cnf_emailnotification(en_Subject, en_Message, en_Type) VALUE('$subject', '$body', '$type')");
    $stmt->execute();
}


/******************************************
*   Get Designation From ID
*******************************************/
function getDesignationFromId($desig_id){

    global $DB;
    $stmt =  $DB->prepare('SELECT * FROM cnf_designation WHERE desig_id=?');
    $stmt->bindValue(1,$desig_id);
    $stmt->execute();
    if($stmt->rowCount()<0)
    {
        return "";
    }
    else
    {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row;
    }
}

/******************************************
*   Get Department From ID
*******************************************/
function getDepartmentFromId($dept_id){

    global $DB;
    $stmt =  $DB->prepare('SELECT * FROM cnf_dept WHERE dept_id=?');
    $stmt->bindValue(1,$dept_id);
    $stmt->execute();
    if($stmt->rowCount()<0)
    {
        return "";
    }
    else
    {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row;
    }
}

/******************************************
*   Get group From ID
*******************************************/
function getGroupFromId($group_id){

    global $DB;
    $stmt =  $DB->prepare('SELECT * FROM cnf_group WHERE group_id=?');
    $stmt->bindValue(1,$group_id);
    $stmt->execute();
    if($stmt->rowCount()<0)
    {
        return "";
    }
    else
    {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row;
    }
}

/******************************************
*   Get LeaveType From ID
*******************************************/
function getLeaveTypeFromId($lt_id){

    global $DB;
    $stmt =  $DB->prepare('SELECT * FROM cnf_leavetype WHERE lt_id=?');
    $stmt->bindValue(1,$lt_id);
    $stmt->execute();
    if($stmt->rowCount()<0)
    {
        return "";
    }
    else
    {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row;
    }
}

// get batch leave application by id
function getLeaveBatchFromId($lb_id)
{
    global $DB;
    $stmt =  $DB->prepare('SELECT * FROM u_leavebatch WHERE lb_id=?');
    $stmt->bindValue(1,$lb_id);
    $stmt->execute();
    if($stmt->rowCount()<0)
    {
        return "";
    }
    else
    {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row;
    }
}

function getURL(){

	return "";
}

?>