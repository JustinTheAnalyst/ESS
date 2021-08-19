<?php
ob_start();
require_once 'tcpdf.php';
require_once 'inc/PHP/constants.php';
require_once 'inc/PHP/configs.php';
require_once 'sessionCheck.php';

$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->SetMargins(2, 2, 2, true);

$pdf->SetAutoPageBreak(TRUE);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
$pdf->SetAuthor('Bearotech Employee Management System');
$pdf->SetTitle('Summary Leaves Report');

// set font
$pdf->SetFont('times', '', 10);
$pdf->AddPage('P', 'A4');
$html='';

if(isset($_POST['generateReport']))
{
  	$loc_id = $_POST['loc_id'];
  	$dept_id = $_POST['dept_id'];
  	$user_id = 0;
  	$RowNumber = 0;

  	if(!isset($_POST['all_user']))
  	{
		$user_id = $_POST['user_id'];
	}
	else 
	{
		$user_id = 0;
	}
	
  	$preparedBy = $_SESSION['user_id'];

  	global $DB;
  	
   	$sqlSelectEmployeeInfo = "SELECT u_usercompany.user_id , cnf_dept.dept_Name, cnf_designation.desig_Name, cnf_location.loc_name , u_user.user_FirstName , u_user.user_LastName 
   						 	  FROM u_usercompany 
						  	  INNER JOIN u_user ON u_user.user_id = u_usercompany.user_id
						  	  INNER JOIN cnf_designation ON cnf_designation.desig_id = u_usercompany.desig_id
						  	  INNER JOIN cnf_dept ON cnf_dept.dept_id = u_usercompany.dept_id
						  	  INNER JOIN cnf_location ON cnf_location.loc_id = u_usercompany.loc_id
						  	  WHERE 1 AND cnf_location.loc_id='$loc_id' AND u_usercompany.uc_Status='A' ";
  	
  	if ($dept_id != '' || $dept_id != null || $dept_id != 0)
  	{
  		$sqlSelectEmployeeInfo.= " AND cnf_dept.dept_id = '$dept_id'";
  	}
  	
  	if($user_id!=0)
  	{
		$sqlSelectEmployeeInfo.=" AND u_usercompany.user_id = $user_id ";
	}

	$stmt = $DB->prepare($sqlSelectEmployeeInfo);
	$stmt->execute();
	$TotalNumberOfEmployees = $stmt->rowCount();
	
	while($rowSelectEmployeeInfo = $stmt->fetch(PDO::FETCH_ASSOC))
	{
  		++$RowNumber;
	  	$totalRows = $stmt->rowCount();
	  	$preparedBy = $_SESSION['user_FirstName'].' '.$_SESSION['user_LastName'];
	  	$desig_Name = $rowSelectEmployeeInfo['desig_Name'];
	  	$loc_Name = $rowSelectEmployeeInfo['loc_name'];
	  	$dept_Name = $rowSelectEmployeeInfo['dept_Name'];
	  	$user_Name = $rowSelectEmployeeInfo['user_FirstName'].' '.$rowSelectEmployeeInfo['user_LastName'];
	  	$user_id = $rowSelectEmployeeInfo['user_id'];

		$html.='<table width="100%" align="center" cellspacing="0" cellpadding="40" style="margin-top:0px;  margin-left:200px; margin-right:0; background-color:#FFA500;" >
					<tr>
                		<td width="" style="text-align:center; height:80px; font-size:30px">
	                 	Summary Leave Report
                		</td>
              		</tr>
				</table>';
		
		$html.='<table>
				    <tr>
				        <td></td>
				    </tr>
				</table>';
		
		$html.='<table width="100%" style="font-size:16px; line-height: 24px;">
		            <tr>
		                <td width="20%">Name</td>
		                <td>:&nbsp;'.$user_Name.'</td>
		            </tr>
		            <tr>
		                <td width="20%">Location</td>
		                <td>:&nbsp;'.$loc_Name.'</td>
		            </tr>
		            <tr>
		                <td width="20%">Department</td>
		                <td>:&nbsp;'.$dept_Name.'</td>
		            </tr>
		            <tr>
		                <td width="20%">Designation</td>
		                <td>:&nbsp;'.$desig_Name.'</td>
		            </tr>
		            <tr>
		                <td width="20%">Prepared On</td>
		                <td>:&nbsp;'.date('Y-m-d, h:i:s A').'</td>
		            </tr>
		            <tr>
		                <td width="20%">Prepared By</td>
		                <td>:&nbsp;'.$preparedBy.'</td>
		            </tr>
		        </table>';

		$html.='<table style="border-bottom:1px solid black;">
				    <tr>
				        <td></td>
				    </tr>
				</table>';
		
    	$html.='<table width="100%"  style="font-size:20px; margin-top:20px; padding-top:10px;border-bottom:2px solid black; ">
                  	<tr style="font-weight:bold;">
                    	<th width="10%">Year</th>
                     	<th width="10%">Ent.</th>
                      	<th width="15%">From</th>
                      	<th width="15%">To</th>
                      	<th width="20%">Day/s Taken</th>
                      	<th width="15%" style="text-align:center;">Balance</th>
                      	<th width="15%">Expired On</th>
	               	</tr>
    			</table>';    
      
		$html.= '<table width="100%" style=" border-bottom:2px solid black; font-size:20px; padding:5px; font-weight:light">';
		
		$i = 0;
		$TotalBalance = 0;

		$sqlEmployeeAnnualLeaves = "SELECT * FROM u_userleave AS UL
									INNER JOIN cnf_leavetype AS LT on LT.lt_id=UL.lt_id
									WHERE UL.user_id=? AND UL.ul_Annual=? AND UL.ul_Status=?";
		$stmt2 = $DB->prepare($sqlEmployeeAnnualLeaves);
		$stmt2->bindValue(1,$user_id);
		$stmt2->bindValue(2,'Y');
		$stmt2->bindValue(3,'A');
		$stmt2->execute();
		
		while($rowEmployeeAnnualLeaves = $stmt2->fetch(PDO::FETCH_ASSOC))
		{
			/*
		 	$ul_Year = $rowEmployeeAnnualLeaves['ul_Year'];
		  	$sqlDaysTaken = "SELECT SUM(lb_Days) AS DaysTaken FROM u_leavebatch WHERE user_id = $user_id AND YEAR(lb_ToDate) = $ul_Year AND lb_Status = 'A';";
		  	$queryDaysTaken = mysqli_query($con, $sqlDaysTaken);
		  	$rowDaysTaken = mysqli_fetch_assoc($queryDaysTaken);
		  	$lb_Days = $rowDaysTaken['DaysTaken'];
		  	*/

			$DaysTaken = $rowEmployeeAnnualLeaves['ul_Number'] - $rowEmployeeAnnualLeaves['ul_RemainingNumber'];
			$TotalBalance += $rowEmployeeAnnualLeaves['ul_RemainingNumber'];
			
			if($i%2==0)
			{
				$bg_color = 'style="background-color:#d5e0f5;"';
			}
			else 
			{
				$bg_color = 'style="background-color:#ffffff;"';
			}
			
			$html.='<tr nobr="true" '.$bg_color.'>';

			$html.='	<td width="10%">'.$rowEmployeeAnnualLeaves['ul_Year'].'</td>
			          	<td width="10%">'.$rowEmployeeAnnualLeaves['ul_Number'].'</td>
			          	<td width="15%"></td>
			          	<td width="15%"></td>
			          	<td width="15%"></td>
			          	<td width="20%" style="text-align:center;">'.$rowEmployeeAnnualLeaves['ul_RemainingNumber'].'</td>
			          	<td width="15%">'.$rowEmployeeAnnualLeaves['ul_ExpiryDate'].'</td>';

			$html.='</tr>';
			
			$i++;
		}

		$html.= '</table>';

		$html.= '<table style="line-height: 30px; font-size:22px; font-weight:bold;">
					<tr>
						<td width="70%"></td>
						<td width="15%" style="text-align:center;">'.$TotalBalance.'</td>
					</tr> 
				 <table>';

		$html.= '<table width="100%" style="line-height: 30px; font-weight:normal; font-size:16px; border-top: 2px solid black;">';
		
		$lb_balance = $TotalBalance;
		$LeavesTaken = 0;
		$lb_Year = date('Y');
		$sqlLeaveBatch = "	SELECT * FROM u_leavebatch AS LB
        					WHERE 1 AND LB.user_id = $user_id AND YEAR(lb_ToDate) = '$lb_Year' AND lt_id=1 
        					AND lb_Status IN ('A','P')
							ORDER BY LB.lb_id ASC";
		$stmt3 = $DB->prepare($sqlLeaveBatch);
		$stmt3->execute();
		
		while($rowLeaveBatch = $stmt3->fetch(PDO::FETCH_ASSOC))
		{
			$lb_balance -=  $rowLeaveBatch['lb_Days'];
			$LeavesTaken += $rowLeaveBatch['lb_Days'];
			$status = $rowLeaveBatch['lb_Status'];
			
			if ($status == 'A')
			{
				$status = "Approved";
				$bg_color = '';
			}
			else 
			{
				$status = "Pending";
				$bg_color = 'style="background-color:#e7e7e7;"';
			}
			
			$html.= '<tr nobr="true" '.$bg_color.'>
						<td width="20%" style="text-align:center;">Ref. '.$rowLeaveBatch['lb_id'].'</td>
			          	<td width="15%">'.$rowLeaveBatch['lb_FromDate'].'</td>
			          	<td width="15%">'.$rowLeaveBatch['lb_ToDate'].'</td>
			          	<td width="15%" style="text-align:center;">'.$rowLeaveBatch['lb_Days'].'</td>
			          	<td width="20%" style="text-align:center;">'.$lb_balance.'</td>
			          	<td width="15%" style="text-align:center; '.$bg_color.'">'.$status.'</td>
                     </tr>';
		}
		
		$html.= '</table>';
		
		$html.= '<table width="100%" style="line-height: 30px; padding:5px 0px; border-bottom:2px solid black;">
					<tr nobr="true" style="background-color:#4CAF50; ">
    					<td width="20%" style="text-align:center;">Total</td>
			          	<td width="15%"></td>
			          	<td width="15%"></td>
			          	<td width="15%" style="text-align:center;">'.$LeavesTaken.'</td>
			          	<td width="25%" style="text-align:center;">'.$lb_balance.'</td>
			          	<td width="10%"></td>		
     				</tr>
    			 </table>';
        
        if($RowNumber<$totalRows)
        {
  			$html.='<br pagebreak="true"/>';
		}
	}
	
	$file_name = $user_id."_".strtotime(date("Y-m-d"));
}

ob_clean();
// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Output($file_name.'.pdf', 'I');
$pdf->close();
// Free result set
mysqli_free_result($result);
mysqli_close($con);
//============================================================+
// END OF FILE
//============================================================+


?>