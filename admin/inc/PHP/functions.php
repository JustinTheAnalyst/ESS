<?php
include 'sessionCheck.php';
include 'constants.php';
include 'database_tables.php';
include 'configs.php';

global $DB;

/*===============================================
    ||
    ||      FUNCTIONS FILE
    ||      --------------
    ||      Using PHP PDO (PHP Data Objects) for
    ||      strong DB interaction and security.
    ||
=================================================*/

/**
 * Get System Setting Details
 */
function getSysSettings()
{
	global $DB;

	$sql = "SELECT * FROM cnf_sys_settings";
	$stmt =  $DB->prepare($sql);
	$stmt->execute();
	if($stmt->rowCount()>0)
	{
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		return $row;
	}
	else
	{
		return "";
	}
}

/**
 * Get List of Job History
 */
function getJobHistory()
{
	global $DB;
	$user_id=$_SESSION['user_id'];

	$stmt= $DB->prepare('SELECT * FROM u_userjobhistory
                		 INNER JOIN cnf_designation ON cnf_designation.desig_id = u_userjobhistory.uh_desig_id
						 INNER JOIN cnf_location ON cnf_location.loc_id = u_userjobhistory.uh_loc_id
                		 WHERE u_userjobhistory.uh_user_id=?
						 ORDER BY u_userjobhistory.uh_id ASC');
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

/**
 * Get Leave Dashboard Details
 */
function leaveDashboard($user_id)
{
	global $DB;

	$currentYear= date('Y');
	$query="SELECT COUNT(la_id) AS total,
            (SELECT COUNT(la_id) FROM u_leaveapplication WHERE user_id=? AND YEAR(la_Date)=? AND la_Status='P') AS pending,
            (SELECT COUNT(la_id) FROM u_leaveapplication WHERE user_id=? AND YEAR(la_Date)=? AND la_Status='A') AS approved,
            (SELECT COUNT(la_id) FROM u_leaveapplication WHERE user_id=? AND YEAR(la_Date)=? AND la_Status='R') AS rejected
            FROM `u_leaveapplication`
            WHERE user_id=? AND YEAR(la_Date)=?";

	$stmt = $DB->prepare($query);
	$stmt->bindValue(1,$user_id);
	$stmt->bindValue(2,$currentYear);
	$stmt->bindValue(3,$user_id);
	$stmt->bindValue(4,$currentYear);
	$stmt->bindValue(5,$user_id);
	$stmt->bindValue(6,$currentYear);
	$stmt->bindValue(7,$user_id);
	$stmt->bindValue(8,$currentYear);

	$stmt->execute();
	$row=array();
	if($stmt->rowCount())
	{
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
	}

	return $row;
}

/**
 * Get List of Locations
 */ 
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

/**
 * Get Location by ID
 * @param unknown $location_id
 * @return string|mixed
 */
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

/**
 * Update Location Record
 */
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
		echo "FAILED. Please try again.";
	}
}

/**
 * Create New Document Record
 */
function createDocument($doc_Name,$doc_Type,$doc_Status)
{
    global $DB;
    $stmt = $DB->prepare('INSERT INTO cnf_document (doc_Name, doc_Status,doc_Type) VALUES (?, ?, ?)');
    $stmt->bindParam(1, $doc_Name);
    $stmt->bindParam(2, $doc_Status);
    $stmt->bindParam(3, $doc_Type);
    $stmt->execute();
    $last_id = $DB->lastInsertId();
    echo $last_id."INSERTED";
    die();
}

/**
 * Delete document type
 */
function deleteDocument($doc_id)
{
    global $DB;
    $stmt   =   $DB->prepare('DELETE FROM cnf_document WHERE doc_id=?');
    $stmt->bindParam(1,$doc_id);
    $stmt->execute();
    echo "DELETED";
}

/**
 * Get List of Documents
 */
function getDocumentList()
{
    global $DB;

    $query = "SELECT * FROM cnf_document WHERE 1 ORDER BY doc_id DESC";
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

/**
 * Get List of Active Document
 */
function getActiveDocuments()
{
	global $DB;
	$stmt   =  $DB->prepare('SELECT * FROM cnf_document WHERE doc_Status="A"');
	$stmt->execute();

	while($r=$stmt->fetch(PDO::FETCH_ASSOC))
	{
		$row[]=$r;
	}
	return $row;
}

/**
 * Update Document Details by ID
 */
function editGetDocument($doc_id)
{
    global $DB;
    $stmt   =   $DB->prepare('SELECT doc_id,doc_Name,doc_Type,doc_Status FROM cnf_document WHERE doc_id=?');
    $stmt->bindParam(1,$doc_id);
    $stmt->execute();
    $row =   $stmt->fetch(PDO::FETCH_ASSOC);
    $data='
    <form role="form" id="edit-document-form" onsubmit="editSaveDocument();" action="" method="post">
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

/**
 * Update Document Record
 * @param unknown $doc_id
 * @param unknown $doc_Name
 * @param unknown $doc_Type
 * @param unknown $doc_Status
 */
function updateDocument($doc_id,$doc_Name,$doc_Type,$doc_Status)
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

/**
 * Create Designation Record
 * @param unknown $desig_Name
 * @param unknown $desig_ShortName
 * @param unknown $desig_Scale
 * @param unknown $desig_Status
 * @param unknown $dl_Number
 */
function createDesignation($desig_Name,$desig_ShortName,$desig_Scale,$desig_Status,$dl_Number)
{
    global $DB;
    $stmt   =   $DB->prepare('INSERT INTO cnf_designation (desig_Name,desig_ShortName,desig_Scale,desig_Status) VALUES (?,?,?,?)');
    $stmt->bindParam(1,$desig_Name);
    $stmt->bindParam(2,$desig_ShortName);
    $stmt->bindParam(3,$desig_Scale);
    $stmt->bindParam(4,$desig_Status);
    if($stmt->execute())
    {
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
    else
    {
        echo "";
    }

}

/**
 * Delete Designation Record
 * @param unknown $desig_id
 */
function deleteDesignation($desig_id)
{
    global $DB;
    $stmt = $DB->prepare('DELETE FROM cnf_designation WHERE desig_id=?');
    $stmt->bindParam(1,$desig_id);
    
    if($stmt->execute())
    {
        deleteDesigLeave($desig_id);
        echo "1";
    }
    else
    {
        "";
    }
}

/**
 * Get List of Designation
 */
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

/**
 * Get List of Active Designation
 */
function getActiveDesignation()
{
    global $DB;

    $stmt =  $DB->prepare('SELECT * FROM cnf_designation WHERE desig_Status="A" ORDER BY desig_Name ASC');
    if($stmt->execute())
    {
        while($r=$stmt->fetch(PDO::FETCH_ASSOC))
        {
            $row[]=$r;
        }
        return $row;
    }
}

/**
 * Update Designation
 * @param unknown $desig_id
 * @param unknown $desig_Name
 * @param unknown $desig_ShortName
 * @param unknown $desig_Scale
 * @param unknown $desig_Status
 * @param unknown $dl_Number
 */
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

/**
 * Create New Notice
 * @param unknown $postArray
 */
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

/**
 * Get List of Notices
 */
function getNoticeList()
{
    global $DB;

    $stmt=$DB->prepare('SELECT * FROM cnf_notice 
    					WHERE 1
    					ORDER BY notice_id DESC');
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

/**
 * Update Notice
 * @param unknown $postArray
 */
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

/**
 * Delete Notice
 * @param unknown $notice_id
 */
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

/**
 * Create New Holiday Record
 */
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

/**
 * Update Holiday Details
 */
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

/**
 * Delete Holiday
 * @param unknown $holiday_id
 */
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

/**
 * Get List of Holidays
 */
function getHolidayList()
{
    global $DB;

    $stmt=$DB->prepare('SELECT * FROM cnf_holiday WHERE 1
    					ORDER BY holiday_Date DESC');
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

/**
 * Get List of Departments
 */
function getDepartmentList()
{
    global $DB;

    $row = array();
    $stmt =  $DB->prepare('SELECT * FROM cnf_dept ORDER BY dept_id DESC');
    if($stmt->execute()){
        while($r=$stmt->fetch(PDO::FETCH_ASSOC))
        {
            $row[]=$r;
        }
        return $row;
    }
}

/**
 * Get List of Active Departments
 */
function getActiveDepartments()
{
    global $DB;
    $row = array();

    $stmt =  $DB->prepare('SELECT * FROM cnf_dept WHERE dept_Status="A" ORDER BY dept_id DESC ');
    if($stmt->execute()){
        while($r=$stmt->fetch(PDO::FETCH_ASSOC))
        {
            $row[]=$r;
        }
        return $row;
    }
}


/**
 * Get List of Active Managers
 */
function getActiveReportTo()
{
	global $DB;
	$row = array();

	$stmt = $DB->prepare('SELECT * FROM u_user WHERE user_Status = ? AND user_Type IN
							(SELECT user_Type FROM u_user
                               WHERE user_Type <> "E" AND user_Type <> "C")
							ORDER BY user_FirstName ASC');
	$stmt->bindValue(1,'A');
	
	if($stmt->execute())
	{
		while($r=$stmt->fetch(PDO::FETCH_ASSOC))
		{
			$row[]=$r;
		}
		return $row;
	}
}

/**
 * Create New Department Record
 * @param unknown $dept_Name
 * @param unknown $dept_ShortName
 * @param unknown $dept_Status
 */
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

/**
 * Update Department Details
 * @param unknown $dept_id
 * @param unknown $dept_Name
 * @param unknown $dept_ShortName
 * @param unknown $dept_Status
 */
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

/**
 * Delete Department
 * @param unknown $dept_id
 */
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

/**
 * Check Employee ID Validity
 * @param unknown $user_UserCode
 */ 
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

/**
 * Check User Email Validity
 * @param unknown $user_Email
 */
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

/**
 * Generate Unique Code For User
 * @param unknown $size
 * @return string
 */
function random_num($size) 
{
	$alpha_key = '';
	$keys = range('A', 'Z');

	for ($i = 0; $i < 2; $i++) 
	{
		$alpha_key .= $keys[array_rand($keys)];
	}

	$length = $size - 2;

	$key = '';
	$keys = range(0, 9);

	for ($i = 0; $i < $length; $i++) 
	{
		$key .= $keys[array_rand($keys)];
	}

	$code =  $alpha_key.$key;
	
	global $DB;
	$sql = "SELECT user_UserName FROM u_user WHERE user_UserName = '$code'";
	$stmt= $DB->prepare($sql);
	$stmt->execute();
	
	if ($stmt->rowCount())
	{
		random_num(10);
	}
	
	return $code;
}

/**
 * Create New Employee Record
 * last edited: justin, 10/08/2017
 */ 
function createEmployee($postArray,$filesArray)
{
    global $DB;

    $imgFile = $filesArray['user_PicPath']['name'];
    $tmp_dir = $filesArray['user_PicPath']['tmp_name'];
    $img_PicPath="";
    $img_directory="../uploads/employee_images/";
    $img_NewName=round(microtime(true) * 1000).$filesArray['user_PicPath']['name'];
    
    if($imgFile)
    {
        move_uploaded_file($tmp_dir, $img_directory.$img_NewName);
        $img_PicPath=$img_directory.$img_NewName;
    }   

    $user_unique_code = random_num(10);
    
    $stmt= $DB->prepare('INSERT INTO u_user ( user_UserCode, user_Email, user_PhoneNo, user_Password, 
    						user_FirstName, user_LastName, user_Status, user_PicPath, 
    						user_DOB, user_Gender, user_TAddress, user_PAddress, user_DateTime, 
    						user_JoiningDateTime,user_Type, user_UserName, user_ReportTo) 
    						VALUES (?,?,?,?,?,?,?,?,?,?,?,?,now(),?,?,?,?)');
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
    $stmt->bindValue(14,$postArray['user_Type']);
    $stmt->bindValue(15,$user_unique_code);
    $stmt->bindValue(16,$postArray['report_to']);
    
    if($stmt->execute())
    {
       	$user_id = $DB->lastInsertId();
       
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
        $stmt = $DB->prepare('	INSERT INTO u_usercompany (user_id, loc_id, dept_id, desig_id, uc_ContractType, uc_JoiningDate, uc_Status) 
        						VALUES (?,?,?,?,?,?,?)');
        $stmt->bindValue(1,$user_id);
        $stmt->bindValue(2,$postArray['loc_id']);
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
       
        $stmt = $DB->prepare('	INSERT INTO u_userjobhistory (uh_user_id, uh_loc_id, uh_dept_id, 
        						uh_desig_id, uh_contract_type,uh_contract_duration,
        						uh_effective_date,uh_expiry_date,uh_status)
        						VALUES (?,?,?,?,?,?,?,?,?)');
        $stmt->bindValue(1, $user_id);
        $stmt->bindValue(2, $postArray['loc_id']);
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
		
	   	
	   	// notify employee that his/her account has been created
	   	$to = $postArray['user_Email'];
	   	$subject = "Your account has been created.";
	   	
	   	$headers = "From: no-reply@mispmis.com\r\n";
	   	$headers .= "Reply-To: ".$to."\r\n";
	   	$headers .= "Return-Path: no-reply@mispmis.com\r\n";
	   	$headers .= "CC: ";
	   	$headers .= "BCC: just1st_85@hotmail.com\r\n";
	   	
	   	$body = "Dear ".$postArray['user_FirstName'].",\n\nYour E-Leave account details as follows:\n";
	   	
	   	$body.= "Login Email: ".$to."\n\n";
	   	$body.= "Password: ".$postArray['user_Password']."\n\n";
	   	$body.= "Please remember to change your password and don't share them to anyone.\n\n";
	   	$body.= "Please contact HR manager for further assistance if you fail to login. \n\n";
	   	$body.= "Thank you.\n\n\n";
	   	$body.= "Your Faithful,\nHR Manager";
	   	
	   	mail($to,$subject,$body,$headers);
	   
	   	$sql = "INSERT INTO cnf_emailnotification(en_Subject, en_Message, en_Type) 
	   			VALUE(?, ?, ?)";
	   	$stmt = $DB->prepare($sql);
	   	$stmt->bindValue(1,$subject);
	   	$stmt->bindValue(2,$body);
	   	$stmt->bindValue(3,'New Account Creattion.');
	   	$stmt->execute();
	   	
        echo $user_id;
    }
    else
    {
        echo "";
    }
    
}

/**
 * Update Employee Profile
 */
function updateEmployee($postArray,$filesArray)
{
	global $DB;
	 
	$user_id = $postArray['u_id'];
	$imgFile = $filesArray['user_PicPath']['name'];
	$tmp_dir = $filesArray['user_PicPath']['tmp_name'];
	$img_directory = "../uploads/employee_images/";
	$img_tbnails_directory = "../uploads/employee_images/thumbs/";
	$img_NewName = round(microtime(true) * 1000).$filesArray['user_PicPath']['name'];

	$user_info = getEmployeeByID($user_id);

	if($imgFile)
	{
		$img_PicPath = $img_directory.$img_NewName;
		move_uploaded_file($tmp_dir, $img_directory.$img_NewName);

		$old_file_name = basename($img_directory.$user_info['user_PicPath']);

		if (file_exists($img_directory.$old_file_name))
		{
			unlink($img_directory.$old_file_name);
		}

		if (file_exists($img_tbnails_directory.$old_file_name))
		{
			unlink($img_tbnails_directory.$old_file_name);
		}

		make_thumb($img_PicPath,$img_tbnails_directory.$img_NewName,25);
	}
	else
	{
		$img_PicPath = null;
	}

	$stmt= $DB->prepare('UPDATE u_user SET user_UserCode=?,user_Email=?,user_PhoneNo=?,
							user_Password=?,user_FirstName=?,user_LastName=?,user_Status=?,
							user_PicPath=?,user_DOB=?,user_Gender=?,user_TAddress=?,user_PAddress=?, 
							user_Type = ?, user_ReportTo = ?
						 WHERE user_id=?');
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
	$stmt->bindValue(13,$postArray['user_Type']);
	$stmt->bindValue(14,$postArray['report_To']);
	$stmt->bindValue(15,$user_id);
	
	if($stmt->execute())
	{
		// upload documents
		$stmt= $DB->prepare('SELECT udc_id,udc_Path,doc_id,user_id FROM u_doclist WHERE user_id=?');
		$stmt->bindValue(1,$user_id);
		$stmt->execute();
		$docRow = array();

		while($docR = $stmt->fetch(PDO::FETCH_ASSOC))
		{
			$docRow[$docR['doc_id']] = $docR['doc_id'];
			$docPath[$docR['doc_id']] = $docR['udc_Path'];
		}

		foreach($filesArray["docs"]["tmp_name"] as $key=>$tmp_name)
		{
			$docName = $filesArray["docs"]["name"][$key];

			if(!empty($docName))
			{
				$docTempName = $filesArray['docs']['tmp_name'][$key];
				$docDirectory = "../uploads/employee_docs/";
				$docNewName = round(microtime(true) * 1000).$docName;
				 
				move_uploaded_file($docTempName, $docDirectory.$docNewName);
				
				if(in_array($key, $docRow))
				{
					unlink($docPath[$key]);
					$stmt = $DB->prepare('UPDATE u_doclist SET udc_Path=? WHERE user_id=? AND doc_id=?');
					$stmt->bindValue(1,$docDirectory.$docNewName);
					$stmt->bindValue(2,$user_id);
					$stmt->bindValue(3,$key);
					$stmt->execute();
				}
				else
				{
					$stmt = $DB->prepare('INSERT INTO u_doclist (doc_id,user_id,udc_Path) VALUES (?,?,?)');
					$stmt->bindValue(1,$key);
					$stmt->bindValue(2,$postArray['u_id']);
					$stmt->bindValue(3,$docDirectory.$docNewName);
					$stmt->execute();
				}
			}
		}
		
		echo "UPDATED";
	}
	else
	{
		echo "";
	}
}

/*
 * Delete employee entire record
 * last edited: justin 27/03/2017
 */
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

/**
 * View Employee Profile
 */
function viewEmployee($user_id)
{
    global $DB;

    $stmt = $DB->prepare('SELECT * FROM u_user WHERE user_id=?');
    $stmt->bindParam(1,$user_id);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if($row['user_Status']=='A')
    { 
    	$user_Status="Active";
    } 
    else
    { 
    	$user_Status="In-Active"; 
    }
    
    if($row['user_Gender']=='M')
    { 
    	$user_Gender="Male"; 
    }
    else
    { 
    	$user_Gender="Female";
    }
    
    $DATA='	<div class="modal-header">
          		<button type="button" class="close" data-dismiss="modal">&times;</button>
          		<h4 ><span class="glyphicon glyphicon-info-sign"></span> Employee Information</h4>
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
								                    <img src="'.$row['user_PicPath'].'" style="height:120px; width:120px">
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
                						</div>
               						</div>
            					</div>
            				</div>
           				</div>'; 
    
    $stmt = $DB->prepare('SELECT * FROM u_usercompany AS UC 
    						INNER JOIN cnf_dept ON cnf_dept.dept_id=UC.dept_id 
    						INNER JOIN cnf_designation ON cnf_designation.desig_id=UC.desig_id 
    						INNER JOIN cnf_location ON cnf_location.loc_id=UC.loc_id WHERE user_id=? ORDER BY UC.uc_id DESC');
    $stmt->bindParam(1,$user_id);
    $stmt->execute();
    $ucRow = $stmt->fetch(PDO::FETCH_ASSOC);

    if($ucRow['uc_Status']=='A')
    { 
    	$uc_Status="Active";
   	}
    else
    { 
    	$uc_Status="In-Active"; 
    }
    			
    if ($ucRow['uc_ExitDate'] == "")
    {
    	$exitDate = "N/A";
    }
    else 
    {
    	$exitDate = $ucRow['uc_ExitDate'];
    }
    
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
				                    <label for="group_name" class="col-sm-3 control-label"><small>Employee ID:<span class="text-danger"> </span></small></label>
				                    <div class="col-sm-9">
				                       '.$row['user_UserCode'].'
				                       <div id="user_UserCodeCheck"></div>
				                    </div>
				                </div>
				                <div class="form-group">
				                    <label for="group_name" class="col-sm-3 control-label"><small>Location:</small></label>
				                    <div class="col-sm-9">
				                        '.$ucRow['loc_name'].'
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
				                       '.$exitDate.'     
				                    </div>
				                </div>
				                <div class="form-group">
				                    <label for="group_name" class="col-sm-3 control-label"><small>Status:<span class="text-danger"> </span></small></label>
				                    <div class="col-sm-9">
				                       '.$uc_Status.'     
				                    </div>
				                </div>
                			</div>
            			</div>
        			</div>
        		</div>

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
                                    <strong for="group_name" class="col-sm-3 "><small>Documents</small></strong>
                                    <div class="col-sm-9">
                                        Download
                                    </div>
                            	</div>';
	
								$stmt = $DB->prepare('SELECT * FROM u_doclist AS UDC
							                          INNER JOIN cnf_document AS DOC ON DOC.doc_id=UDC.doc_id
							                          WHERE user_id=?');
							    $stmt->bindParam(1,$user_id);
							    $stmt->execute();
    
							    while($docRow=$stmt->fetch(PDO::FETCH_ASSOC))
							    { 
							    	$DATA.='<div class="form-group row">
							               		<span for="group_name" class="col-sm-3 "><small>'.$docRow['doc_Name'].'</small></span>
							                    <div class="col-sm-9">';
                            
                            		if($docRow['doc_Type']=='F')
                            		{
                                		$DATA.='<a href="'.$docRow['udc_Path'].'" target="_blank">Download</a>';
                            		}
                            		elseif($docRow['doc_Type']=='I')
                            		{
                                		$DATA.='<a href="'.$docRow['udc_Path'].'" target="_blank"><img src="'.$docRow['udc_Path'].'" style="height:60px;width:100px"></a> ';
                            		}

                            		$DATA.='	</div>
                                			</div>';
                        		}
                        
	$DATA.='				</div>   
						</div>
        			</div>
        		</div>
        	</div>';
       
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
                                    				INNER JOIN cnf_location AS LC ON LC.loc_id = UH.uh_loc_id
                                    				INNER JOIN cnf_dept AS DP ON DP.dept_id = UH.uh_dept_id
                                    				INNER JOIN cnf_designation AS DE ON DE.desig_id = UH.uh_desig_id
                                    				WHERE UH.uh_user_id = ?";
	                        $stmt = $DB->prepare($sqlSelectJobHistory);
	                        $stmt->bindValue(1, $user_id);
	                        $stmt->execute();
                        
                        	$DATA.='<div class="table-responsive" >
                                		<table class="table" id="addNewEmployeeJobTable">
					                        <thead>
					                            <tr>
					                                <td>Effective</td>
					                                <td>Location</td>
					                                <td>Department</td>
					                                <td>Designation</td>
					                                <td>Contract Type</td>
					                                <td>Status</td>
					                                
					                            </tr>
					                        </thead>
					                        <tbody>
					                            <tr id="addNewEmployeeJobRow" style="display:none">
					                                <td class="effective_date"></td>
					                                <td class="locName"></td>
					                                <td class="deptName"></td>
					                                <td class="desigName"></td>
					                                <td class="contractType"></td>
					                                <td class="Status"></td>';
                        
		                        	while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
		                        	{
			                            $effective_date = $row['uh_effective_date'];
			                            $loc = $row['loc_name'];
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
		                             
		                            	$DATA.='<tr id="jobrow'.$row['uh_id'].'">
					                                <td class="effective_date">'.$effective_date.'</td>
					                                <td class="locName">'.$loc.'</td>
					                                <td class="deptName">'.$dept.'</td>
					                                <td class="desigName">'.$desig.'</td>
					                                <td class="contractType">'.$ct.'</td>
					                                <td class="Status">'.$status.'</td>
		                            			</tr>';
		                        	}
                        
		$DATA.='							</tbody>
                    					</table>
                    				</div>
                    			</div>
                        	</div>
        				</div>
        			</div>
        		</div>';
                        
       $DATA.='	<div class="col-sm-12">
               		<div class="panel panel-success">
          				<div class="panel-heading">
                			<div class="box-header">
                      			<h6 class="box-title"><i class="fa fa-file"></i>&nbsp;Leave Details</h6>
                			</div>
          				</div>
			          	<div class="panel-body">
			                <div class="box box-danger">
			                   	<div class="box-header">
			                         
			                    </div>
			                    <div class="box-body">';
                         		$sqlSelectUserLeave = " SELECT * FROM u_userleave AS UL
                                                		INNER JOIN cnf_leavetype AS LT ON LT.lt_id = UL.lt_id
                                                		WHERE UL.user_id = ? AND UL.lt_id = ? AND UL.ul_Status = ?";
		                        $stmt = $DB->prepare($sqlSelectUserLeave);
		                        $stmt->bindValue(1, $user_id);
		                        $stmt->bindValue(2, 1);
		                        $stmt->bindValue(3, 'A');
		                        $stmt->execute();
                        
                        $DATA.="	<p><h3><u>Annual Leaves</u></h3></p>
                                	<table class='table table-bordered' id='AnnualLeaveTable'>
                                    	<thead>
	                                        <tr>
	                                            <td class='lt_Name'>Type (Year)</td>
	                                            <td class='leaveNumber' style='width:10%;'>Leave Balance</td>
	                                            <td class='leaveExpiryDate' style='width:10%;'>Expired On</td>
	                                            <td class='leaveStatus' style='width:10%;'>Status</td>
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
                                            <td>$leave_type ($year)</td>
                                            <td class='Year".$year."UL".$ul_id."'>$remaining_leave</td>
                                            <td>$expiry_date</td>
                                            <td>$status</td>
                                         </tr>";
                        }
                        
                        $DATA.='    </tbody>
                                </table>';
                        
                        $sqlSelectUserLeave = " SELECT * FROM u_userleave AS UL
                                                INNER JOIN cnf_leavetype AS LT ON LT.lt_id = UL.lt_id
                                                WHERE UL.user_id = ? AND UL.lt_id != ? AND UL.ul_Status = ?";
                        $stmt = $DB->prepare($sqlSelectUserLeave);
                        $stmt->bindValue(1, $user_id);
                        $stmt->bindValue(2, 1);
                        $stmt->bindValue(3, 'A');
                        $stmt->execute();
                        
                        $DATA.="<hr><p><h3><u>Other Leaves</u></h3></p>
                                <table class='table table-bordered' id='OtherLeaveTable'>
                                    <thead>
                                        <tr>
                                            <td class='lt_Name'>Type</td>
                                            <td class='leaveNumber' style='width:10%;'>Leave Balance</td>
                                            <td class='leaveExpiryDate' style='width:10%;'>Expired On</td>
                                            <td class='leaveStatus' style='width:10%;'>Status</td>
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
	                                            <td>$leave_type</td>
	                                            <td class='Year".$year."UL".$ul_id."'>$remaining_leave</td>
	                                            <td>$expiry_date</td>
	                                            <td>$status</td>
	                                        </tr>";
	                        }
                        
                        $DATA.='    </tbody>
                                </table>';
                        
	$DATA.='					</div>
	                     	</div>
	        			</div>
	        		</div>
	        	</div>
	    	</form> 
	    </div>   
	</div>
	<div class="modal-footer clearfix">
    	<button  class="btn btn-default btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Cancel</button>
    </div>';

echo $DATA;
}

/**
 * create uploaded image thumbnail
 * last editted: 12/08/17, justin
 */
function make_thumb($src, $dest, $desired_width) 
{
	$ext = pathinfo($src, PATHINFO_EXTENSION);

	if( strtolower($ext) == "png" ){

		/* read the source image */
		$source_image = imagecreatefrompng($src);
		imagealphablending($source_image, true);

		$width = imagesx($source_image);
		$height = imagesy($source_image);
		
		/* find the "desired height" of this thumbnail, relative to the desired width  */
		$desired_height = floor($height * ($desired_width / $width));
		
		/* create a new, "virtual" image */
		$virtual_image = imagecreatetruecolor($desired_width, $desired_height);
		
		imagealphablending($virtual_image, false);
		imagesavealpha($virtual_image, true);

		/* copy source image at a resized size */
		imagecopyresampled($virtual_image, $source_image, 0, 0, 0, 0, $desired_width, $desired_height, $width, $height);
		
		/* create the physical thumbnail image to its destination */
		imagepng($virtual_image, $dest);
		return true;

	}else{

			/* read the source image */
		$source_image = imagecreatefromjpeg($src);
		$width = imagesx($source_image);
		$height = imagesy($source_image);
		
		/* find the "desired height" of this thumbnail, relative to the desired width  */
		$desired_height = floor($height * ($desired_width / $width));
		
		/* create a new, "virtual" image */
		$virtual_image = imagecreatetruecolor($desired_width, $desired_height);
		
		/* copy source image at a resized size */
		imagecopyresampled($virtual_image, $source_image, 0, 0, 0, 0, $desired_width, $desired_height, $width, $height);
		
		/* create the physical thumbnail image to its destination */
		imagejpeg($virtual_image, $dest);
		return true;
	}
	return false;
}

// check images extension
function check_image_extention($ext)
{
	if( strtolower($ext) =="png" ||  strtolower($ext) == "jpg" ||  strtolower($ext) =="jpeg" ||  strtolower($ext) == "jpe")
	{
	 	return true;
	}
	else
	{
	 	return false;
	}
}

/**
 * Get employee data by its ID
 */
function getEmployeeByID($id)
{
	global $DB;
	$sql = "SELECT * FROM u_user WHERE 1 AND user_id=?";
	$stmt = $DB->prepare($sql);
	$stmt->bindValue(1,$id);
	$stmt->execute();

	if($stmt->rowCount()>0)
	{
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		return $row;
	}
	else
	{
		return "";
	}
}

/**
 * Get List of Employees
 * last editted: justin, 23/08/17
 * added location parameter to filter the list
 */
function getEmployeeList($locID=null,$deptID=null)
{
    global $DB;
    
    $sql = 'SELECT * FROM u_user AS US
    		INNER JOIN u_usercompany AS UC ON UC.user_id = US.user_id
    		INNER JOIN cnf_location AS LC ON LC.loc_id = UC.loc_id
    		INNER JOIN cnf_dept AS DP ON DP.dept_id = UC.dept_id';
    
    if ($locID <> null)
    {
    	$sql.= ' WHERE UC.loc_id='.$locID;
    	
    	if ($deptID <> null)
    	{
    		$sql.= ' AND UC.dept_id='.$deptID;
    	}
    }
    else 
    {
    	if ($deptID <> null)
    	{
    		$sql.= ' WHERE UC.dept_id='.$deptID;
    	}
    }
    
    $stmt = $DB->prepare($sql);
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

/**
 * Get List of Active Employees
 */
function getEmployeeListDetail()
{
    global $DB;
    $stmt = $DB->prepare('	SELECT * FROM u_user 
    						INNER JOIN u_usercompany ON u_user.user_id = u_usercompany.user_id  
    						WHERE 1 AND u_user.user_Status ="A"');
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

/**
 * Get employee details by id
 */ 
function getEmpPersonalDetail($user_id)
{
    global $DB;

    $stmt = $DB->prepare('SELECT * FROM u_user WHERE user_id=?');
    $stmt->bindParam(1,$user_id);
    $stmt->execute();
    $row=$stmt->fetch(PDO::FETCH_ASSOC);
    return $row;
}

/**
 * Get Employee Comapany Details
 * @param unknown $user_id
 * @return mixed
 */
function getEmpCompanyDetail($user_id)
{
    global $DB;
    $stmt = $DB->prepare('SELECT * FROM u_usercompany AS UC 
    						INNER JOIN cnf_location ON cnf_location.loc_id=UC.loc_id 
    						INNER JOIN cnf_dept ON cnf_dept.dept_id=UC.dept_id 
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

/**
 * Get List of Leave Application By Batch filter by its parameters
 * @param unknown $locID
 * @param unknown $deptID
 * @param unknown $filterByStatus
 * @param unknown $filterUserType
 */
function getAllBatchLeaves($locID=null,$deptID=null,$filterByStatus=null,$filterUserType=null)
{
    global $DB;
    
    $filterBy = '';
    
    if($filterByStatus <> null)
    {
    	$filterBy.= " AND LB.lb_Status='$filterByStatus'";
    }
    
    if ($locID <> null)
    {
    	$filterBy.= ' AND u_usercompany.loc_id='.$locID;
    }
    
    if ($deptID <> null)
    {
    	$filterBy.= ' AND u_usercompany.dept_id='.$deptID;
    }
    
    if ($filterUserType <> null)
    {
    	$filterBy.= ' AND u_user.user_Type IN ('.$filterUserType.')';
    }
    
    $sql = 'SELECT * FROM u_leavebatch AS LB
    		INNER JOIN u_user ON u_user.user_id=LB.user_id
    		INNER JOIN u_usercompany ON u_usercompany.user_id=u_user.user_id
        	INNER JOIN cnf_location ON cnf_location.loc_id=u_usercompany.loc_id
    		INNER JOIN cnf_dept ON cnf_dept.dept_id=u_usercompany.dept_id
    		INNER JOIN cnf_leavetype ON cnf_leavetype.lt_id=LB.lt_id
        	INNER JOIN cnf_leavereason ON cnf_leavereason.lr_id=LB.lr_id
    		WHERE 1 '.$filterBy;

    $sql.= " ORDER BY LB.lb_id DESC";
    
    $stmt = $DB->prepare($sql);
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

/*
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
*/

/**
 * Change Leave Status by Batch / Update Leave Status
 * Action Reject, Approve or Cancel taken by admin, HR manager, superior, etc
 */
function changeLeaveBatchStatus($lb_id, $lb_Status, $lb_Remarks, $lb_ReasonDoc)
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
    	$updateStatus = 'Rejected';
    }
    elseif($lb_Status=='A')
    {
    	$updateStatus = 'Approved';
    }
	elseif($lb_Status=='C')
    {
    	$updateStatus = 'Cancelled';
    }

    $sqlInsertLH = "INSERT INTO u_leavehistory(lh_lb_id, lh_desc, lh_audit_by, lh_audit_date)
	   				VALUE(?,?,?,NOW())";
    $stmt = $DB->prepare($sqlInsertLH);
    $stmt->bindValue(1,$lb_id);
    $stmt->bindValue(2,$updateStatus);
    $stmt->bindValue(3,$user_id);
    $stmt->execute();
    
    $sqlUpdateLB = "UPDATE u_leavebatch SET 
    				lb_Remarks=?, lb_ReasonDoc = ?, lb_audit_by=?, lb_audit_date=NOW(), lb_Status=?
    		  		WHERE lb_id=?";

    $stmt = $DB->prepare($sqlUpdateLB);
    $stmt->bindValue(1,$lb_Remarks);
    $stmt->bindValue(2,$lb_ReasonDocName);
    $stmt->bindValue(3,$user_id);
    $stmt->bindValue(4,$lb_Status);
    $stmt->bindValue(5,$lb_id);
    
    if($stmt->execute())
    {
    	// update individual leave records
    	$stmt4 = $DB->prepare('SELECT * FROM u_leaveapplication WHERE lb_id=?');
    	$stmt4->bindValue(1,$lb_id);
    	$stmt4->execute();
    	
    	while($row = $stmt4->fetch(PDO::FETCH_ASSOC))
    	{
    		$la_id = $row['la_id'];
    		$user_id  =$row['user_id'];
    		$la_Date = $row['la_Date'];
    		 
    		// deduct leave from employee from the earliest batch of remaining leaves
    		if($lb_Status == 'A')
    		{
    			// if it is annual leave, then deduct from the earliest year
    			if($row['la_Annual']=='Y')
    			{
    				$sysInfo = getSysSettings();
    				 
    				// number of year that the leave can bring forward
    				// will loop for the latest active year
    				// then deduct from the earliest year
    				$bringForwardPeriod = $sysInfo['sys_bf_period'];
    				 
    				if(!$ul_id=annualLeaveAdjustment($user_id,'A', 'LIMIT 1',$la_Date))
    				{
    					if(!$ul_id=annualLeaveAdjustment($user_id,'A', 'LIMIT 1,1',$la_Date))
    					{
    						if(!$ul_id=annualLeaveAdjustment($user_id,'A', 'LIMIT 2,1',$la_Date))
    						{
    							if(!$ul_id=annualLeaveAdjustment($user_id,'A', 'LIMIT 3,1',$la_Date))
    							{
    								//There is no annual leave to use.
    							}
    						}
    					}
    				}
    			}
    			else
    			{
    				DeductLeave($row['ul_id']);
    			}
    		}
    		elseif($lb_Status=='C')
    		{
    			reverseAnnualLeave($la_id);
    		}
    	
    		$query = "UPDATE u_leaveapplication SET la_Status=? WHERE la_id=?";
    		$stmt5 = $DB->prepare($query);
    		$stmt5->bindParam(1,$lb_Status);
    		$stmt5->bindParam(2,$la_id);
    		$stmt5->execute();
    	}
    	
    	echo "UPDATED";
    	
       	$sqlSelectLB = 'SELECT * FROM u_leavebatch 
       					INNER JOIN u_user ON u_user.user_id = u_leavebatch.user_id
       					WHERE u_leavebatch.lb_id=?';
       	$stmt2 = $DB->prepare($sqlSelectLB);
   	 	$stmt2->bindParam(1,$lb_id);
    	$stmt2->execute();
    	$lbRow = $stmt2->fetch(PDO::FETCH_ASSOC);
       	
    	$employeeDetail = getEmpPersonalDetail($user_id);
    	
    	$refNo = str_pad($lbRow['lb_id'], 6, '0', STR_PAD_LEFT);
       	
       	// notify applicant on leave request status
    	$to = $lbRow['user_Email'];
    	$from = $employeeDetail['user_FirstName'];
       	$subject = "Leave Request Notification.";
       	
       	$headers = "From: no-reply@mispmis.com\r\n";
       	$headers .= "Reply-To: ".$to."\r\n";
       	$headers .= "Return-Path: no-reply@mispmis.com\r\n";
       	$headers .= "CC: ";
       	$headers .= "BCC: just1st_85@hotmail.com\r\n";
       	
       	$body = "Dear Sir/Madam,\n\n";
       	
       	$body.= "Status: ".$updateStatus."\n";
       	$body.= "Reference No.: ".$refNo."\n";
       	$body.= "Applicant: ".$lbRow['user_FirstName']." ".$lbRow['user_LastName']."\n";
       	$body.= "Leave Type: ".getLeaveTypeFromId($lbRow['lt_id'])['lt_Name']."\n";
       	$body.= "Start: ".$lbRow['lb_FromDate']."\n";
       	$body.= "To: ".$lbRow['lb_ToDate']."\n";
       	$body.= "No. Day Taken: ".$lbRow['lb_Days']."\n\n";
       	
       	$body.= "Thank you.\n\n\n";
       	$body.= "Your Faithful,\n".$from;
       	
       	mail($to,$subject,$body,$headers);
       	
       	$sqlInsertEN = "INSERT INTO cnf_emailnotification(en_Subject, en_Message, en_Type)
	   					VALUE(?, ?, ?)";
       	$stmt3 = $DB->prepare($sqlInsertEN);
       	$stmt3->bindValue(1,$subject);
       	$stmt3->bindValue(2,$body);
       	$stmt3->bindValue(3,'Leave Application');
       	$stmt3->execute();
    }
    else
    {
        echo "";
    }
}

/**
 * Return number of leave to repective leave batch 
 */
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


/**
 * Leave Adjustment Starts here
 */
function annualLeaveAdjustment($user_id,$status,$limit,$la_Date)
{
    global $DB;
    $checkQ = "	SELECT * FROM u_userleave 
    			WHERE user_id=$user_id AND ul_Status='$status' AND ul_RemainingNumber>0 AND ul_Annual='Y' 
    			ORDER BY ul_ExpiryDate ASC $limit";
    $stmt2 = $DB->prepare($checkQ);
    $stmt2->execute();
    
    if($stmt2->rowCount())
    {
        $checkRow = $stmt2->fetch(PDO::FETCH_ASSOC);
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

/**
 * Get List of Leave Type 
 */
function getLeaveTypeList()
{
    global $DB;

    $stmt =  $DB->prepare('SELECT * FROM cnf_leavetype WHERE 1 ORDER BY lt_id DESC');
    if($stmt->execute()){
        while($r=$stmt->fetch(PDO::FETCH_ASSOC))
        {
            $row[]=$r;
        }
        return $row;
    }
}

/**
 * Create New Leave Type Record
 * @param unknown $lt_Name
 * @param unknown $lt_Status
 */
function createLeaveType($lt_Name,$lt_Status)
{
    global $DB;
    $stmt   =   $DB->prepare('INSERT INTO cnf_leavetype (lt_Name,lt_Status,lt_DateTime) VALUES (?,?,NOW())');
    $stmt->bindParam(1,$lt_Name);
    
    $stmt->bindParam(2,$lt_Status);
    if($stmt->execute()){
        echo $DB->lastInsertId();
    }
    else{
        echo "";
    }
}

/**
 * Update Leave Type
 * @param unknown $lt_id
 * @param unknown $lt_Name
 * @param unknown $lt_Status
 */
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
    else
    {
        echo "";
    }
}

/**
 * Delete Leave Type Record
 * @param unknown $lt_id
 */
function deleteLeaveType($lt_id)
{
    global $DB;
    $stmt = $DB->prepare('DELETE FROM cnf_leavetype WHERE lt_id=?');
    $stmt->bindParam(1,$lt_id);
    
    if($stmt->execute())
    {
        $stmt1 = $DB->prepare('DELETE FROM cnf_otherleave WHERE lt_id=?');
        $stmt1->bindParam(1,$lt_id);
        if($stmt1->execute())
        {
            $stmt2 = $DB->prepare('DELETE FROM u_userleave WHERE lt_id=?');
            $stmt2->bindParam(1,$lt_id);
            if($stmt2->execute())
            {
                echo "1";
            }
        }
    }
    else
    {
       echo "";
    }
}

/**
 * Allocate Other Leave to Employee (non annual leave)
 * last edited: justin, 07/08/17
 */
function allocateOtherLeaves($lt_id, $ul_Number, $leaveAllocationyear)
{
    global $DB;
    $ul_Year = $leaveAllocationyear;
    $ul_FromDate = $ul_Year.'-01-01';
    $ul_ToDate = $ul_Year.'-12-31';
    $ul_ExpiryDate = $ul_ToDate;
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
   
	for ($i=0; $i <count($AllEmployeeIDs) ; $i++) 
	{ 
     	$stmtCheckAnnualLeave = $DB->prepare("SELECT ul_id FROM u_userleave WHERE user_id = $AllEmployeeIDs[$i] AND ul_Year = $leaveAllocationyear AND lt_id = $lt_id");
     	$stmtCheckAnnualLeave->execute();
     	
     	if($stmtCheckAnnualLeave->rowCount()==0)
     	{    
    		$stmt = $DB->prepare('INSERT INTO u_userleave (lt_id, ul_Number,ul_Year, ul_Annual, ul_DateTime, ul_FromDate, ul_ToDate, user_id, ul_ExpiryDate, ul_Status, ul_RemainingNumber) VALUES (?,?,?,?,NOW(),?,?,?,?,?,?)');
    		$stmt->bindParam(1, $lt_id);
    		$stmt->bindParam(2, $ul_Number);
    		$stmt->bindParam(3, $ul_Year);
    		$stmt->bindParam(4, $ul_Annual);
    		$stmt->bindParam(5, $ul_FromDate);
    		$stmt->bindParam(6, $ul_ToDate);
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
 * Allocate Annual Leave to Employee
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

    $query = "SELECT * FROM u_userleave AS UL 
              INNER JOIN cnf_leavetype AS LT ON LT.lt_id=UL.lt_id
              WHERE UL.user_id=?
              ORDER BY UL.ul_Year DESC, UL.lt_id ASC";
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

// get user leave application
function getUserLeaveHistory($user_id)
{
    global $DB;
   /* $query='SELECT LA.la_id, LA.user_id, LA.lt_id, LA.la_Annual, LA.lr_id, LA.la_FromDate, LA.la_ToDate, LA.la_Date, LA.la_Days, LA.la_Comment, LA.la_Status, LR.lr_Title,LT.lt_Name,LA.la_DateTime 
        FROM u_leaveapplication AS LA 
        INNER JOIN cnf_leavetype AS LT ON LT.lt_id=LA.lt_id
        INNER JOIN cnf_leavereason AS LR ON LR.lr_id=LA.lr_id
        WHERE LA.user_id=?';*/

        //return $user_id;
      $query='SELECT * FROM u_leavebatch AS LB 
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
            WHERE UL.user_id=? AND UL.lt_id=? AND UL.ul_Status=?
            ORDER BY UL.ul_Year DESC');
    $stmt->bindValue(1,$user_id);
    $stmt->bindValue(2,1);
    $stmt->bindValue(3,'A');
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

/**
 * Deduct Leave
 */
function DeductLeave($ul_id)
{
    global $DB;
    $updateQ = "UPDATE u_userleave SET ul_RemainingNumber=ul_RemainingNumber-1 WHERE ul_id=$ul_id";
    $stmt3 = $DB->prepare($updateQ);
    $stmt3->execute();
}

/**
 * Delete Designation Leave
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

/**
 * Expire Old Leaves
 */
function expireOldLeaves()
{
     global $DB;
     $currentDate = date('Y-m-d');
     
     $sql = "UPDATE u_userleave SET ul_Status = 'E' WHERE ul_ExpiryDate <= '$currentDate'";
     $stmtExpireLeaves = $DB->prepare($sql);
     
     if($stmtExpireLeaves -> execute())
     {
        return 1;
     }
     else
     {
        return 0;
     }
}

/**
 * Add New Job for Employee
 */
function addNewEmployeeJob($postArray)
{
    global $DB;

    $user_id = $postArray['addNewEmployeeJobId'];
    $returnArray = array();
	
    if ($postArray['contract_type'] == 'C')
    {
    	$effective_date = $postArray['uh_effective_date'];
		$contract_duration = $postArray['contract_duration'];
		$expiry_date = date('Y-m-d',strtotime('+'.$contract_duration.' years',strtotime($effective_date)));
	}
   	else
 	{
		$contract_duration = null;
 		$expiry_date = null;
 	}
 	
 	// insert new job record
    $stmt = $DB->prepare(' INSERT INTO u_userjobhistory (uh_user_id, uh_loc_id, uh_dept_id, 
                           uh_desig_id, uh_contract_type,uh_contract_duration,
                           uh_effective_date,uh_expiry_date,uh_status)
                           VALUES (?,?,?,?,?,?,?,?,?)');
  	$stmt->bindValue(1, $user_id);
   	$stmt->bindValue(2, $postArray['loc_id']);
  	$stmt->bindValue(3, $postArray['dept_id']);
  	$stmt->bindValue(4, $postArray['desig_id']);
   	$stmt->bindValue(5, $postArray['contract_type']);
   	$stmt->bindValue(6, $contract_duration);
  	$stmt->bindValue(7, $postArray['uh_effective_date']);
  	$stmt->bindValue(8, $expiry_date);
 	$stmt->bindValue(9, 'I');
  	$stmt->execute();

	$returnArray['Status'] = "CREATED";
   	$returnArray['effective_date'] = $postArray['uh_effective_date'];
  	$returnArray['uh_id'] = $DB->lastInsertId();
  	$returnArray['user_id'] = $user_id;
  	
  	echo json_encode($returnArray);
}

/**
 * Update Employee Job Details
 * @param unknown $postArray
 */
function editNewEmployeeJob($postArray)
{
    global $DB;
    
    $returnArray = array();
    $uh_id = $postArray['editNewEmployeeJobId'];
    $returnArray['check'] = $uh_id;

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

        $stmt = $DB->prepare("UPDATE u_userjobhistory SET uh_loc_id = ?, uh_dept_id = ?, 
                                uh_desig_id = ?, uh_contract_type = ?,uh_contract_duration = ? , uh_effective_date=?
                                WHERE uh_id = $uh_id");
        
        $stmt->bindValue(1, $postArray['loc_id']);
        $stmt->bindValue(2, $postArray['dept_id']);
        $stmt->bindValue(3, $postArray['desig_id']);
        $stmt->bindValue(4, $postArray['contract_type']);
        $stmt->bindValue(5, $contract_duration);
        $stmt->bindValue(6, $postArray['uh_effective_date']);
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

/**
 * Calculate Working Days
 */ 
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
 * Create New Leave Application / Apply Leave
 * edited: Justin, 05/08/17
 * check vailability leave before processing form
 * editted: justin, 25/08/17
 * change field name from lb_Comment to lb_Reason
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

	$sqlSelectLeaveType = "SELECT * FROM cnf_leavetype WHERE lt_id=?";
	$stmt = $DB->prepare($sqlSelectLeaveType);
	$stmt->bindValue(1,$postArray['lt_id']);
	$stmt->execute();
	$ltRow = $stmt->fetch(PDO::FETCH_ASSOC);
	
	// Y means annual leaves, N are other leavs
	// annual leave is able to bring forward, depends on the period set in system settings
	// where as other leave will be renew every year
	if ($ltRow['lt_Annual'] == 'Y')
	{
		$ul_id = '';

	}
	else
	{
		$ul_id = getUlIdFromType($postArray['lt_id']);
	}

	
	
	if($ltRow['lt_Annual'] == 'Y')
	{
		// get number of year that can be bring forward
		// default 3 years
		$sysInfo = getSysSettings();
		$bringForwardPeriod = $sysInfo['sys_bf_period'];
		
		// loop the batch of leave and assign id to the leave application from the earliest year
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
		
		$is_annual = 'Y';
	}
	else
	{
		$ul_id = getUlIdFromType($postArray['lt_id']);
		$is_annual = 'N';
	}

	$datediff =  $toDate-$fromDate ;
	$la_Days = ($datediff/86400)+1;

	$stmt = $DB->prepare('	INSERT INTO u_leavebatch
	    					(user_id, lt_id, lb_Annual, lb_FromDate, lb_ToDate, lb_Days,
	    					lr_id, lb_Reason, lb_Status,lb_Doc,lb_DateTime,ul_id)
	    					VALUES
	    					(?,?,?,?,?,?,?,
	    					?,?,?,now(),?)');
	$stmt->bindValue(1,$user_id);
	$stmt->bindValue(2,$postArray['lt_id']);	
	$stmt->bindValue(3,$is_annual);
	$stmt->bindValue(4,$postArray['la_FromDate']);
	$stmt->bindValue(5,$postArray['la_ToDate']);
	$stmt->bindValue(6,$la_Days);
	$stmt->bindValue(7,$postArray['lr_id']);
	$stmt->bindValue(8,$postArray['lb_Reason']);
	$stmt->bindValue(9,'P');
	$stmt->bindValue(10,$lb_Doc);
	$stmt->bindValue(11,$ul_id);
	 
	if($stmt->execute())
	{
		$lb_id = $DB->lastInsertId();
	}

	$refNo = str_pad($lbRow['lb_id'], 6, '0', STR_PAD_LEFT);
	
	$employeeDetail = getEmpPersonalDetail($user_id);
	$reportToDetails = getEmpPersonalDetail($employeeDetail['user_ReportTo']);
	
	// notify report to or HOD that new leave request has been created
	$to = $reportToDetails['user_Email'];
	$subject = "New Leave Request Notification.";
	 
	$headers = "From: no-reply@mispmis.com\r\n";
	$headers .= "Reply-To: ".$to."\r\n";
	$headers .= "Return-Path: no-reply@mispmis.com\r\n";
	$headers .= "CC: ";
	$headers .= "BCC: just1st_85@hotmail.com\r\n";
	 
	$body = "Dear Sir/Madam,\n\n";
	 
	$body.= "Status: Pending\n";
	$body.= "Reference No.: ".$refNo."\n";
	$body.= "Applicant: ".$employeeDetail['user_FirstName']." ".$employeeDetail['user_LastName']."\n";
	$body.= "Leave Type: ".getLeaveTypeFromId($postArray['lt_id'])['lt_Name']."\n";
	$body.= "Start: ".$postArray['la_FromDate']."\n";
	$body.= "To: ".$postArray['la_ToDate']."\n";
	$body.= "No. Day Taken: ".$la_Days."\n\n\n";
	
	$body.= "Please APPROVE OR REJECT via system and don't reply to this email.\n";
	$body.= "Thank you.\n\n\n";
	$body.= "Your Faithful,\nBR System";
	 
	mail($to,$subject,$body,$headers);
	
	$sql = "INSERT INTO cnf_emailnotification(en_Subject, en_Message, en_Type)
	   			VALUE(?, ?, ?)";
	$stmt = $DB->prepare($sql);
	$stmt->bindValue(1,$subject);
	$stmt->bindValue(2,$body);
	$stmt->bindValue(3,'Leave Application.');
	$stmt->execute();
	
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
		else
		{
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
		$delstmt->execute();
	}

	if(empty($errors))
	{
		echo "INSERTED";
	}

	if($postArray['lt_id']==1 && !empty($reverseArray['remaining']))
	{
		foreach ($reverseArray['remaining'] as $key => $value) 
		{
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

/**
 * Get User Leave ID: Other Leaves
 */
function getUlIdFromType($lt_id)
{
	global $DB;
	
    $user_id = $_SESSION['user_id'];
    $ul_Year = date('Y');
    
    $sql = "SELECT ul_id FROM u_userleave 
    		WHERE user_id = $user_id AND ul_Year = $ul_Year AND lt_id = $lt_id";
    $stmt= $DB->prepare($sql);
    $stmt->execute();
    
  	if($stmt->rowCount()>0)
  	{
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		return $row['ul_id'];
  	}
  	else
  	{
		return 0;
  	}
}

/**
 * Get Day ID
 */
function GetDayId($day_Name)
{
    global $DB;

	$sql = "SELECT day_id FROM cnf_day WHERE day_Name LIKE '$day_Name'";
	$stmt =  $DB->prepare($sql);
	$stmt->execute();
        
	if($stmt->rowCount()>0)
	{
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		return $row['day_id'];
	}   
}

/**
 * Delete Leave Request By Applicant
 * Allow employee to delete their leave application before approve / reject by superior
 */
function deleteLeaveBatch($lb_id)
{
	global $DB;
	
	$refNo = str_pad($lb_id, 6, '0', STR_PAD_LEFT);
	$batchDetail = getLeaveBatchFromId($lb_id);
	$employeeDetail = getEmpPersonalDetail($batchDetail['user_id']);
	$reportTo = getEmpPersonalDetail($employeeDetail['user_ReportTo']);
	
	// notify superior on leave request cancellation
	$from = $employeeDetail['user_Email'];
	$to = $reportTo['user_Email'];
	$subject = "Leave Deletion Notification.";
	
	$headers = "From: no-reply@mispmis.com\r\n";
	$headers .= "Reply-To: ".$to."\r\n";
	$headers .= "Return-Path: no-reply@mispmis.com\r\n";
	$headers .= "CC: ";
	$headers .= "BCC: just1st_85@hotmail.com\r\n";
	
	$body = "Dear Sir/Madam,\n\n";
	
	$body.= "Status: DELETED\n";
	$body.= "Reference No.: ".$refNo."\n";
	$body.= "Applicant: ".$employeeDetail['user_FirstName']." ".$employeeDetail['user_LastName']."\n";
	$body.= "Leave Type: ".getLeaveTypeFromId($batchDetail['lt_id'])['lt_Name']."\n";
	$body.= "Start: ".$batchDetail['lb_FromDate']."\n";
	$body.= "To: ".$batchDetail['lb_ToDate']."\n";
	$body.= "No. Day Taken: ".$batchDetail['lb_Days']."\n\n";
	
	$body.= "Thank you.\n\n\n";
	$body.= "Your Faithful,\n".$employeeDetail['user_FirstName'];
	
	$message = "Application ".$refNo." has been deleted.";
	
	mail($to,$subject,$body,$headers);
	
	$sqlInsertEN = "INSERT INTO cnf_emailnotification(en_Subject, en_Message, en_Type)
	   				VALUE(?, ?, ?)";
	$stmt3 = $DB->prepare($sqlInsertEN);
	$stmt3->bindValue(1,$subject);
	$stmt3->bindValue(2,$message);
	$stmt3->bindValue(3,'Leave Request Deleted');
	$stmt3->execute();

	$sqlDeleteLB = "DELETE FROM u_leavebatch WHERE lb_id=?";
	$delLBStmt = $DB->prepare($sqlDeleteLB);
 	$delLBStmt->bindValue(1,$lb_id);
            
  	if($delLBStmt->execute())
	{
		echo "DELETED";
	} 
	else 
	{
		echo "ERROR: Leave applicantion no found.";
	}
}

/**
 * Get Employee Email By ID
 */
function getEmailFromId($user_id)
{
    global $DB;
    $stmt =  $DB->prepare('SELECT user_Email FROM u_user WHERE user_id=?');
    $stmt->bindValue(1,$user_id);
    $stmt->execute();
    
    if($stmt->rowCount()>0)
    {
    	$row = $stmt->fetch(PDO::FETCH_ASSOC);
    	return $row['user_Email'];
    }
    else
    {
    	return "";
    }
}

/**
 * Delete DB
 */
function deleteDbBackup($db_id)
{
	global $DB;
	$delstmt = $DB->prepare('DELETE FROM adm_dbbackup WHERE db_id=?');
  	$delstmt->bindValue(1,$db_id);
            
 	if($delstmt->execute())
  	{
		echo "DELETED";
	}
	else
	{
		echo "ERROR: DB not found.";
	}

}
 
/**
 * Update My Profile
 */
 function updateMyProfile($postArray,$filesArray)
{
    global $DB;
    
    $user_id = $postArray['profile_id'];
    $imgFile = $filesArray['profilePic']['name'];
    $tmp_dir = $filesArray['profilePic']['tmp_name'];
    $img_directory = "../uploads/employee_images/";
    $img_tbnails_directory = "../uploads/employee_images/thumbs/";
    $img_NewName = round(microtime(true) * 1000).$filesArray['profilePic']['name'];
    
    $user_info = getEmployeeByID($user_id);
    
    if(isset($imgFile) && $imgFile <> null)
    {
    	$img_PicPath = $img_directory.$img_NewName;
    	move_uploaded_file($tmp_dir, $img_directory.$img_NewName);
    
    	$old_file_name = basename($img_directory.$user_info['user_PicPath']);
    
    	if (file_exists($img_directory.$old_file_name))
    	{
    		unlink($img_directory.$old_file_name);
    	}
    
    	if (file_exists($img_tbnails_directory.$old_file_name))
    	{
    		unlink($img_tbnails_directory.$old_file_name);
    	}
    
    	make_thumb($img_PicPath,$img_tbnails_directory.$img_NewName,25);
    	
    	// save employee profile photo
    	$stmt= $DB->prepare('UPDATE u_user SET user_PicPath=? WHERE user_id=?');
    	$stmt->bindValue(1,$img_PicPath);
    	$stmt->bindValue(2,$user_id);
    	$stmt->execute();
    	
    	$_SESSION['user_PicPath'] = $img_PicPath;
    }
    
    // save employee profile details
    $stmt= $DB->prepare('UPDATE u_user SET user_FirstName=?,user_LastName=?,user_DOB=?,
    					 user_Gender=?,user_PhoneNo=?,user_TAddress=?,user_PAddress=?,user_Password=? 
    					 WHERE user_id=?');
    $stmt->bindValue(1,$postArray['fname']);
    $stmt->bindValue(2,$postArray['lname']);
    $stmt->bindValue(3,$postArray['dob']);
    $stmt->bindValue(4,$postArray['gender']);
    $stmt->bindValue(5,$postArray['phone']);
    $stmt->bindValue(6,$postArray['tAddress']);
    $stmt->bindValue(7,$postArray['pAddress']);
    $stmt->bindValue(8,$postArray['password']);
    $stmt->bindValue(9,$user_id);
    
    if($stmt->execute())
    {
    	// save documents
    	$stmt= $DB->prepare('SELECT * FROM u_doclist WHERE user_id=?');
    	$stmt->bindValue(1,$user_id);
    	$stmt->execute();
    	$docRow = array();
    
    	while($docR = $stmt->fetch(PDO::FETCH_ASSOC))
    	{
    		$docRow[$docR['doc_id']] = $docR['doc_id'];
    		$docPath[$docR['doc_id']] = $docR['udc_Path'];
    	}
    
    	foreach($filesArray["docs"]["tmp_name"] as $key=>$tmp_name)
    	{
    		$docName = $filesArray["docs"]["name"][$key];
    
    		if(!empty($docName))
    		{
    			$docTempName = $filesArray['docs']['tmp_name'][$key];
    			$docDirectory = "../uploads/employee_docs/";
    			$docNewName = round(microtime(true) * 1000).$docName;
    				
    			move_uploaded_file($docTempName, $docDirectory.$docNewName);
    
    			if(in_array($key, $docRow))
    			{
    				unlink($docPath[$key]);
    				$stmt = $DB->prepare('UPDATE u_doclist SET udc_Path=? WHERE user_id=? AND doc_id=?');
    				$stmt->bindValue(1,$docDirectory.$docNewName);
    				$stmt->bindValue(2,$user_id);
    				$stmt->bindValue(3,$key);
    				$stmt->execute();
    			}
    			else
    			{
    				$stmt = $DB->prepare('INSERT INTO u_doclist (doc_id,user_id,udc_Path) VALUES (?,?,?)');
    				$stmt->bindValue(1,$key);
    				$stmt->bindValue(2,$user_id);
    				$stmt->bindValue(3,$docDirectory.$docNewName);
    				$stmt->execute();
    			}
    		}
    	}
    	
    	if (isset($postArray['copy']))
    	{
    		$to = $postArray['email'];
    		$subject = "Profile is updated.";
    		 
    		$headers = "From: no-reply@mispmis.com\r\n";
    		$headers .= "Reply-To: ".$to."\r\n";
    		$headers .= "Return-Path: no-reply@mispmis.com\r\n";
    		$headers .= "CC: ";
    		$headers .= "BCC: just1st_85@hotmail.com\r\n";
    		 
    		$body = "Dear ".$postArray['fname'].",\n\n";
    		$body.= "You have currently updated your profile.\n\n";
    		$body.= "First Name: ".$postArray['fname']."\n";
    		$body.= "Last Name: ".$postArray['lname']."\n";
    		$body.= "DOB: ".$postArray['dob']."\n";
    		$body.= "Gender: ".$postArray['gender']."\n";
    		$body.= "Phone: ".$postArray['phone']."\n";
    		$body.= "Mailing Address:\n".$postArray['tAddress']."\n";
    		$body.= "Permenant Address:\n".$postArray['pAddress']."\n";
    		$body.= "Login Email: ".$postArray['email']."\n";
    		$body.= "Login Password: ".$postArray['password']."\n\n";
    		$body.= "If you didn't perform this action, kindly inform your HR Manager immediately.\n\n";
    		$body.= "Thank you.";
    		 
    		mail($to,$subject,$body,$headers);
    		
    		$sql = "INSERT INTO cnf_emailnotification(en_Subject, en_Message, en_Type)
	   				VALUE(?, ?, ?)";
    		$stmt = $DB->prepare($sql);
    		$stmt->bindValue(1,$subject);
    		$stmt->bindValue(2,'N/A');
    		$stmt->bindValue(3,'Profile UPDATED.');
    		$stmt->execute();
    	}
    
    	echo "UPDATED";
    }
    else
    {
    	echo "ERROR";
    }
}

/**
 * Expire Soon Contracts Notification to Admin or HR Manager
 */
function expireLeaveNotification()
{
	global $DB;

	$today  = date('d-m-Y');
	echo $expiry_date = date('Y-m-d', strtotime('+30 days'));

	$stmt = $DB->prepare("SELECT * FROM u_userjobhistory WHERE uh_expiry_date <= '$expiry_date'");
	
	$stmt->execute();
	if($stmt->rowCount()>0)
    {
    	while($row = $stmt->fetch(PDO::FETCH_ASSOC))
    	{
    		$employeeDetail = getEmpPersonalDetail($row['uh_user_id']);
    		
    		// notify admin or HR Manager those contracts are going to expire soon
    		$to = 'admin@mispmis.com';
    		$to.= " , hr@mispmis.com";
    		$subject = "Employee's contract (".$employeeDetail['user_UserCode'].", ".$employeeDetail['user_FirstName']. " ".$employeeDetail['user_LastName'].") is going to expired soon.";
    		
    		$body = "Dear Sir/Madam,\n\n";
    		
    		$body.= "Employee ID: ".$employeeDetail['user_UserCode'];
    		$body.= "Employee Name: ".$employeeDetail['user_FirstName']." ".$employeeDetail['user_LastName'];
    		$body.= "Expire ON: ".$row['uh_expiry_date']."\n\n";
    		
    		$body.= "Kindly create a new contract and assign new job and leave for him/her.\n";
    		$body.= "Thank you.\n\n\n";
    		$body.= "Your Faithful,\nBR System";
    		
    		$message = "Contracts Expire Soon";
    		$type = "Employees Contract";
    		
    		sendMail($to, $subject, $message, $type);
    	}
    }
    else
    {
    	echo "No Data Found.";
    }
}

/**
 * System Send Email
 * @param unknown $to
 * @param unknown $subject
 * @param unknown $body
 * @param unknown $type
 */
function sendMail($to, $subject, $message, $type, $from=NULL)
{
	global $DB;
	
	if (isset($from))
	{
		$headers = "From: ".$from."\r\n";
	}
	else
	{
		$headers = "From: BR Employee Management System <no-reply@mispmis.com>"."\r\n";
	}
	
	$headers .= "Reply-To: ".$to."\r\n";
	$headers .= "Return-Path: no-reply@mispmis.com\r\n";
	$headers .= "CC: ";
	$headers .= "BCC: just1st_85@hotmail.com\r\n";

	// send mail
    mail($to, $subject, $message, $headers);

    $body = addslashes($body);

    $sql = "INSERT INTO cnf_emailnotification(en_Subject, en_Message, en_Type)
	   		VALUE(?, ?, ?)";
    $stmt = $DB->prepare($sql);
    $stmt->bindValue(1,$subject);
    $stmt->bindValue(2,$message);
    $stmt->bindValue(3,$type);
    $stmt->execute();
}


/**
 * Get Designation by ID
 */
function getDesignationFromId($desig_id)
{
    global $DB;
    $stmt =  $DB->prepare('SELECT * FROM cnf_designation WHERE desig_id=?');
    $stmt->bindValue(1,$desig_id);
    $stmt->execute();
    
    if($stmt->rowCount()>0)
    {
    	$row = $stmt->fetch(PDO::FETCH_ASSOC);
    	return $row;
    }
    else
    {
    	return "";
    }
}

/**
 * Get Department by ID
 */
function getDepartmentFromId($dept_id)
{
    global $DB;
    $stmt =  $DB->prepare('SELECT * FROM cnf_dept WHERE dept_id=?');
    $stmt->bindValue(1,$dept_id);
    $stmt->execute();
    
    if($stmt->rowCount()>0)
    {
    	$row = $stmt->fetch(PDO::FETCH_ASSOC);
    	return $row;
    }
    else
    {
    	return "";
    }
}

/**
 * Get Leave Type by ID
 */
function getLeaveTypeFromId($lt_id)
{
    global $DB;
    $stmt = $DB->prepare('SELECT * FROM cnf_leavetype WHERE lt_id=?');
    $stmt->bindValue(1,$lt_id);
    $stmt->execute();
    
    if($stmt->rowCount()>0)
    {
    	$row = $stmt->fetch(PDO::FETCH_ASSOC);
    	return $row;
    }
    else
    {
    	return "";
    }
}

/**
 * Get Leave Batch by ID
 */ 
function getLeaveBatchFromId($lb_id)
{
    global $DB;
    $stmt =  $DB->prepare('SELECT * FROM u_leavebatch WHERE lb_id=?');
    $stmt->bindValue(1,$lb_id);
    $stmt->execute();
    
    if($stmt->rowCount()>0)
    {
    	$row = $stmt->fetch(PDO::FETCH_ASSOC);
    	return $row;
    }
    else
    {
    	return "";
    }
}

function getURL()
{
	return "";
}
?>