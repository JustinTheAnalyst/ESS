<?php 
include('inc/PHP/functions.php');
//initilize the page
require_once ("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once ("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

 YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
 E.G. $page_title = "Custom Title" */

$page_title = "Edit Employee Profile";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
//$page_css[] = "your_style.css";
include ("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
//$page_nav["Employees"]["active"] = true;
include ("inc/nav.php");
?>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<style type="text/css">
	
#addNewEmployeeJob > .form-control {

	margin-top: 10px;
}
#main{

	background: white;
}
#update-employee-form{

	padding: 20px;
}

</style>
<div id="main" role="main">
<?php
$breadcrumbs["Employees"] = "employee.php";
include("inc/ribbon.php");
	
//editGetEmployee($_GET['user_id']);

if (!isset($_GET['user_id']))
{
	header("Location: employee.php");
	exit;
}
else 
{
	$user_id = $_GET['user_id'];
	
	global $DB;
	$stmt = $DB->prepare('SELECT * FROM u_user WHERE user_id=?');
	$stmt->bindValue(1,$user_id);
	$stmt->execute();
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	
	if ($row['user_PicPath'] == null || $row['user_PicPath'] == '')
	{
		$images = "../uploads/no-image.png";
	}
	else
	{
		$images = $row['user_PicPath'];
	}
}
?>

	<div id="content">
		
		
		<section id="widget-grid">
			<div class="row">
				<form class="form-horizontal" style="padding:0px;" id="update-employee-form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
					<div class="col-sm-6">
				        <div class="panel panel-primary">
				        	<div class="panel-heading">
				                <div class="box-header">
				            		<h6 class="box-title"><i class="fa fa-pencil"></i>&nbsp;Personal Details</h6>
				                </div>
				          	</div>
				          	<input type="hidden" name="u_id" value="<?php echo $user_id; ?>">
				          	<div class="panel-body">
				        		<div class="box box-primary">
				       				<div class="box-body">
							            <div class="form-group">
							                <label for="group_name" class="col-sm-3 control-label"><small>Photo</small></label>
							                <div class="col-sm-9">
							                	<div style="margin:8px 0px;">
								                	<img src="<?php echo $images; ?>" style="height: 120px; width: 120px" id="img">
							                	</div>
							                   	<input type="file" name="user_PicPath" onchange="readURL(this);">
							                   	<input type="hidden" name="user_PicPathOld" value="<?php echo $row['user_PicPath']; ?>">
							                   	<label>(W120 x H120)</label>             
							                </div>
							                
							            </div>
							            <div class="form-group">
							                <label for="group_name" class="col-sm-3 control-label"><small>First Name<span class="text-danger"> *</span></small></label>
							                <div class="col-sm-9">
							                   <input class="form-control" name="user_FirstName" id="userFirstName" value="<?php echo $row['user_FirstName']; ?>">                
							                </div>
							            </div>
							            <div class="form-group">
							                <label for="group_name" class="col-sm-3 control-label"><small>Last Name<span class="text-danger"> *</span></small></label>
							                <div class="col-sm-9">
							                   <input class="form-control" name="user_LastName" id="userLastName" value="<?php echo $row['user_LastName']; ?>">                
							                </div>
							            </div>
							            
							            <div class="form-group">
							                <label for="group_name" class="col-sm-3 control-label"><small>Date of Birth</small></label>
							                <div class="col-sm-9">                                       
							                    <input type="text" class="form-control" name="user_DOB" id="userDOB" value="<?php echo $row['user_DOB']; ?>">                  
							                </div>
							            </div>
							            
							            <div class="form-group">
							                <label for="group_name" class="col-sm-3 control-label"><small>Gender</small></label>
							                <div class="col-sm-9">
							                	<select class="form-control" name="user_Gender" id="userGender">
							                    <?php 
							                    if($row['user_Gender']=='M')
							                    { 
							                    	echo '<option value="M" selected="selected">Male</option>
															<option value="F">Female</option>';
							                    }
							                    else 
							                    {
							                    	echo '<option value="M">Male</option>
															<option value="F" selected="selected">Female</option>';
							                    }
							                    ?>
							                    </select>               
							                 </div>
							            </div>
							            <div class="form-group">
							                <label for="group_name" class="col-sm-3 control-label"><small>Phone</small></label>
							                <div class="col-sm-9">
							                   <input type="text" class="form-control" name="user_PhoneNo" id="userPhoneNo" value="<?php echo $row['user_PhoneNo']; ?>">                
							                 </div>
							            </div>
							         
							            <div class="form-group">
							                <label for="group_name" class="col-sm-3 control-label"><small>Mailing Address</small></label>
							                <div class="col-sm-9">
							                   <textarea  class="form-control" placeholder="Mailing Address" rows="5" name="user_TAddress" id="userTAddress"><?php echo $row['user_TAddress']; ?></textarea>               
							                 </div>
							            </div>
							            <div class="form-group">
							                <label for="group_name" class="col-sm-3 control-label"><small>Permanent Address</small></label>
							                <div class="col-sm-9">
							                   <textarea class="form-control" placeholder="Permanent Address" rows="5" name="user_PAddress" id="userPAddress"><?php echo $row['user_PAddress']; ?></textarea>                
							                 </div>
							            </div>
							            
				       				</div>
				  				</div>
							</div>
						</div>
					</div>
					
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
						                    <label for="group_name" class="col-sm-3 control-label"><small>Employee ID<span class="text-danger"> *</span></small></label>
						                    <div class="col-sm-9">
						                       <input type="text" class="form-control" placeholder="EMP-39283" name="user_UserCode" id="userUserCodeEdit" onkeyup="checkUserCodeEdit(<?php echo $row['user_id']; ?>)" value="<?php echo $row['user_UserCode']; ?>">
						                       <div id="user_UserCodeCheckEdit"></div>
						                    </div>
						                </div>
					           
					           			<div class="form-group">
							                <label for="group_name" class="col-sm-3 control-label"><small>Report To</small></label>
							                <div class="col-sm-9">
							                <select name="report_To" id="reportTo" class="form-control" required>
							                	<option value="0" disabled="disabled">-- Select --</option>
												<?php 
												$reportToList = getActiveReportTo();
												foreach ($reportToList as $key => $reportToArray) 
												{
													if ($row['user_ReportTo'] == $reportToArray['user_id'])
													{
														$selected = 'selected="selected"';
													}
													else 
													{
														$selected = '';
													}
													?>
													<option value="<?php echo  $reportToArray['user_id']; ?>" <?php echo $selected; ?>><?php echo  $reportToArray['user_FirstName']." ".$reportToArray['user_LastName']; ?></option>
													<?php
												}
												?>
											</select>                
											</div>
							            </div>
								            
						                <div class="form-group">
						                    <label for="group_name" class="col-sm-3 control-label"><small>Status</small></label>
						                    <div class="col-sm-9">
						                    	<select class="form-control" name="user_Status" id="userStatus">
						                       	<?php 
						                       	if ($row['user_Status']=='A')
						                       	{
						                       		echo '<option value="A" selected="selected">Active</option>
															<option value="I">In-Active</option>';
						                       	}
						                       	else 
						                       	{
						                       		echo '<option value="A">Active</option>
					 										<option value="I" selected="selected">In-Active</option>';
						                       	}
						                       	?>
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
					            		<div id="ULDiv" style="display:none;"></div>
					                </div>
					            </div>
					        </div>
					  	</div>
					</div>
					
					<div class="col-sm-6">    
					    <div class="panel panel-default">
					    	<div class="panel-heading">
					       		<div class="box-header">
					            	<h6 class="box-title"><i class="fa fa-file"></i>&nbsp;Documents</h6>
					            </div>
					      	</div>
					       	<div class="panel-body">
					    		<div class="box box-danger">
					          		<div class="box-header">
					              		
					        		</div>
					              	<div class="box-body">
					                	<div class="form-group">
					                    	<label class="col-sm-3 control-label"><strong>Documents</strong></label>
					                        <label class="col-sm-1 control-label"><strong>Download</strong></label>
					                    </div>
					                    <?php 
					                    $sql = 'SELECT * FROM u_doclist AS UDC
					                            INNER JOIN cnf_document AS DOC ON DOC.doc_id=UDC.doc_id
					                         	WHERE user_id=?';
					                    $stmt = $DB->prepare($sql);
					                    $stmt->bindValue(1,$user_id);
					                    $stmt->execute();
					                    
					                    while($docRow=$stmt->fetch(PDO::FETCH_ASSOC))
					                    {
					                    ?>
					                    	<div class="form-group">
					                        	<span class="col-sm-3"><small><?php echo $docRow['doc_Name']; ?></small></span>
					                            <div class="col-sm-9">
					                            <?php 
					                            if($docRow['doc_Type']=='F')
					                            {
					                                echo '<a href="'.$docRow['udc_Path'].'" target="_blank">Download</a>';
					                            }
					                            elseif($docRow['doc_Type']=='I')
					                            {
					                                echo '<a href="'.$docRow['udc_Path'].'" target="_blank"><img src="'.$docRow['udc_Path'].'" style="height:60px;width:100px"></a> ';
					                            }
												?>
					                            </div>
					                        </div>
					                   	<?php 
					                    }
					                   	
					                    $docList = getActiveDocuments();
					                    foreach ($docList as $key => $docArray)
					                    {
					                    ?>
					                    	<div class="form-group">
                                    			<label for="group_name" class="col-sm-3 control-label">
                                    				<small>
					                    			<?php echo $docArray['doc_Name'].$docArray['doc_id']; ?>
					                    			</small>
					                    		</label>
                                    			<div class="col-sm-9">
                                       				<input type="file" name="docs[<?php echo $docArray['doc_id']; ?>]" class="form-control">
                                    			</div>
                                			</div>
					                    <?php 
					                    }
					                    ?>
					              	</div>   
								</div>
					        </div>
					  	</div>
					</div>
					
					<div class="col-sm-6">
						<div class="panel panel-default">
					  		<div class="panel-heading">
					       		<div class="box-header">
					         		<h6 class="box-title"><i class="fa fa-briefcase"></i>&nbsp;Login Details</h6>
					        	</div>
					 		</div>
						 	<div class="panel-body">
					            <div class="box box-danger">
					                <div class="box-body">
							            <div class="form-group">
							                <label for="group_name" class="col-sm-3 control-label"><small>E-Mail<span class="text-danger"> *</span></small></label>
							                <div class="col-sm-9">
							                   <input type="email" class="form-control" name="user_Email" id="userEmailEdit" onkeyup="checkUserEmailEdit(<?php echo $row['user_id']; ?>)"  value="<?php echo $row['user_Email']; ?>">   
							                   <div id="user_EmailCheckEdit"></div>             
							                </div>
							            </div>
							            <div class="form-group">
							                <label for="group_name" class="col-sm-3 control-label"><small>Password<span class="text-danger"> *</span></small></label>
							                <div class="col-sm-9">
							                   <input type="password" class="form-control" name="user_Password" id="userPassword" value="<?php echo $row['user_Password']; ?>">                
							                </div>
							            </div>
							            <div class="form-group">
							                <label for="group_name" class="col-sm-3 control-label"><small>User Type<span class="text-danger"> *</span></small></label>
							                <div class="col-sm-9">
							                   	<select class="form-control" name="user_Type" id="userType">
							                   	<?php 
							                   	if($row['user_Type']=='E')
							                   	{ 
							                   		echo '<option value="E" selected="selected">Employee</option>
															<option value="M">Manager</option>
															<option value="C">Clerk</option>
															<option value="T">Top Management</option>
															<option value="A">Admin</option>';
							                    }
							                    elseif ($row['user_Type']=='M') 
							                    {
							                    	echo '<option value="E">Employee</option>
															<option value="M" selected="selected">Manager</option>
															<option value="C">Clerk</option>
															<option value="T">Top Management</option>
															<option value="A">Admin</option>';
							                    }
							                    elseif ($row['user_Type']=='C')
							                    {
							                    	echo '<option value="E">Employee</option>
															<option value="M">Manager</option>
															<option value="C" selected="selected">Clerk</option>
															<option value="T">Top Management</option>
															<option value="A">Admin</option>';
							                    }
							                    elseif ($row['user_Type']=='T')
							                    {
							                    	echo '<option value="E">Employee</option>
															<option value="M">Manager</option>
															<option value="C">Clerk</option>
															<option value="T" selected="selected">Top Management</option>
															<option value="A">Admin</option>';
							                    }
							                    elseif ($row['user_Type']=='A') 
							                    {
							                    	echo '<option value="E">Employee</option>
															<option value="M">Manager</option>
															<option value="C">Clerk</option>
															<option value="T">Top Management</option>
															<option value="A" selected="selected">Admin</option>';
							                    }
							                   	?>
							                    </select>  
							                </div>
							            </div>
							      	</div>
								</div>
							</div>
							<div class="panel-footer">
								<div class="form-group">
									<div class="col-sm-12">
						    			<button type="submit" class="btn btn-default btn-success pull-right" id="update">
						    				<span class="glyphicon glyphicon-floppy-saved"></span> Save
						    			</button>   
						    		</div>
						    	</div>           
							</div>
						</div>
					</div>
				</form>
			</div>
		</section>
		
		<section>
			<div class="row">  
				<div class="col-sm-12">
				 	<div class="panel panel-warning">
				   		<div class="panel-heading">
				        	<div class="box-header">
				          		<h6 class="box-title"><i class="fa fa-file"></i>&nbsp;Jobs</h6>
				         	</div>
				   		</div>
				       	<div class="panel-body">
				        	<div class="box box-danger">
				            	<div class="box-header">
			                		<p>
			                			<button type="button" class="btn btn-primary" data-toggle="modal" data-target = "#addNewEmployeeJobModal" onclick="setUserId(<?php echo $user_id; ?>);">Add New Job</button>
			                		</p>
				                </div>
				                <div class="box-body">
				                	<div class="table-responsive">
				                    	<table class="table table-bordered" id="addNewEmployeeJobTable">
				            				<thead>
					            				<tr>
					            					<td style="width:10%;">Effective</td>
					            					<td style="width:5%;">Location</td>
					            					<td>Department</td>
					            					<td>Designation</td>
					            					<td style="width:15%;">Contract Type</td>
					            					<td style="width:15%;">Status</td>
					            					<td style="width:15%;">Actions</td>
					            				</tr>
				            				</thead>
					            			<tbody>
					                            <tr id="addNewEmployeeJobRow" style="display:none">
					                                <td class="effective_date"></td>
					                                <td class="locName"></td>
					                                <td class="deptName"></td>
					                                <td class="desigName"></td>
					                                <td class="contractType"></td>
					                                <td class="status"></td>
					                                <td>
					                                    <p class="btn btn-primary btn-xs edit" data-toggle="modal" data-target = "#addNewEmployeeJobModal" onclick="setUserJobId(this);">
					                                    	<i class="fa fa-pencil"></i>
					                                    </p>
					                                    <a href="javascript:(0);" class="btn btn-danger btn-xs delete" ><i class="fa fa-trash-o"></i></a>
					                                </td>
					                           	</tr>
								                <?php 
								                $sqlSelectJobHistory = "SELECT * FROM u_userjobhistory AS UH
								            							INNER JOIN cnf_location AS LC ON LC.loc_id = UH.uh_loc_id
								            							INNER JOIN cnf_dept AS DP ON DP.dept_id = UH.uh_dept_id
								            							INNER JOIN cnf_designation AS DE ON DE.desig_id = UH.uh_desig_id
								            							WHERE UH.uh_user_id = ?
																		ORDER BY UH.uh_effective_date DESC";
								                $stmt = $DB->prepare($sqlSelectJobHistory);
								                $stmt->bindValue(1, $user_id);
								                $stmt->execute();      
			                        
						                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
						                        {
						                        	$effective_date = $row['uh_effective_date'];
						                        	$loc = $row['loc_shortName'];
						                        	$dept = $row['dept_Name'];
						                        	$desig = $row['desig_Name'];
						                        	 
						                        	if ($row['uh_contract_type'] == "P")
						                        	{
						                        		$ct = "Permenant";
						                        	}
						                        	elseif ($row['uh_contract_type'] == "C")
						                        	{
						                        		$ct = "Contract - ".$row['uh_contract_duration']." year/s";
						                        	}
						                        	 
						                        	if ($row['uh_status'] == "A")
						                        	{
						                        		$status = "Job Active";
						                        	}
						                        	elseif ($row['uh_status'] == "I")
						                        	{
						                        		$status = "Make Active";
						                        	}
						                        	elseif ($row['uh_status'] == "E")
						                        	{
						                        		$status = "Job Expired";
						                        	}
			                        	 		?>
			                        			<tr id="jobrow<?php echo $row['uh_id']; ?>">
					            					<td class="effective_date"><?php echo $effective_date; ?></td>
					            					<td class="locName"><?php echo $loc; ?></td>
					            					<td class="deptName"><?php echo $dept; ?></td>
					            					<td class="desigName"><?php echo $desig; ?></td>
					            					<td class="contractType"><?php echo $ct; ?></td>
					            					<td class="status">
					            					<?php 
					            					if ($row['uh_status'] == 'A')
					            					{
					            					?>
					            						<a href="javascript:void(0);" type="button"  class="btn btn-success btn-xs"><strong><?php echo $status; ?></strong></a>
					            					<?php 
					            					}
					            					elseif ($row['uh_status'] == 'I') 
					            					{
					            					?>
					            						<a href="javascript:void(0);" type="button"  class="btn btn-info btn-xs" onclick="updateJobStatus(<?php echo $user_id; ?>,<?php echo $row['uh_id']; ?>);"><strong><?php echo $status; ?></strong></a>
					            					<?php 
					            					}
					            					elseif ($row['uh_status'] == 'E')
					            					{
					            					?>
					            						<a href="javascript:void(0);" type="button"  class="btn btn-default btn-xs"><strong><?php echo $status; ?></strong></a>
					            					<?php 
					            					}
					            					?>
					            					</td>
					            					<td>
					            					<?php 
					            					if ($row['uh_status'] == 'A' || $row['uh_status'] == 'I')
					            					{
					            					?>
					            						<p class="btn btn-primary btn-xs edit" data-toggle="modal" data-target = "#addNewEmployeeJobModal" onclick="setUserJobId(this);"
						                                    dept_id ="<?php echo $row['uh_dept_id']; ?>"
						                                    desig_id ="<?php echo $row['uh_desig_id']; ?>"
						                                    loc_id ="<?php echo $row['uh_loc_id']; ?>"
						                                    uh_contract_type ="<?php echo $row['uh_contract_type']; ?>"
						                                    uh_contract_duration ="<?php echo $row['uh_contract_duration']; ?>"
						                                    uh_id="<?php echo $row['uh_id']; ?>"
						                                    uh_effective_date="<?php echo $row['uh_effective_date']; ?>">
				                                    			<i class="fa fa-pencil"></i>
				                                    	</p>
													<?php 
					            					}
					            					
					            					if ($row['uh_status'] == 'I' || $row['uh_status'] == 'E')
					            					{
					            						echo '	<p onclick="del('.$row['uh_id'].')" class="btn btn-danger btn-xs delete" >
																	<i class="fa fa-trash-o"  ></i>
																</p>';
					            					}
													?>
			                                    	</td>
			            						</tr>
			            						<?php 
						                        }
			            						?>
				                        	</tbody>
				            			</table>
				            		</div>
								</div>
				          	</div>
				        </div>
				  	</div>
				</div>
			</div>
		</section>
		
		<section>
			<div class="row">                       
				<div class="col-sm-12">
				 	<div class="panel panel-success">
				   		<div class="panel-heading">
				    		<div class="box-header">
				         		<h6 class="box-title"><i class="fa fa-file"></i>&nbsp;Leaves</h6>
				        	</div>
				     	</div>
				       	<div class="panel-body">
				   			<div class="box box-danger">
				      			<div class="box-header">
				               		<button type="button" class="btn btn-primary" data-toggle="modal" data-target = "#EmployeeLeaveAdjustModal" onclick="setUserId('<?php echo $user_id; ?>')">Add New Leaves</button>
				               	</div>
				             	<div class="box-body">
				             		<h3>Annual Leaves</h3>
				             		<div class="table-responsive">
					             		<table class='table table-bordered' id='AnnualLeaveTable'>
					                    	<thead>
						              			<tr id="">
					                                <td style="width:10%;">Year</td>
					                              	<td>Type</td>
					                               	<td style="width:10%;">Leave Balance</td>
					                              	<td style="width:10%;">Expired On</td>
					                              	<td style="width:15%;">Status</td>
					                               	<td style="width:15%;">Actions</td>
					                          	</tr>
					                       	</thead>
					               			<tbody>
					                    		<tr id='copyLeaveRow' style='display:none'>
					                            	<td class='leaveYear'></td>
					                                <td class='lt_Name'></td>
					                               	<td class='leaveNumber'></td>
					                               	<td class='leaveExpiryDate'></td>
					                               	<td class='leaveStatus'></td>
					                                <td>
					                               		<a href='javascript:(0)' data-toggle='modal' class='btn btn-primary btn-xs edit'>
					                               			<i class='fa fa-pencil' data-toggle='modal' data-target = '#SingleLeaveAdjustModal' onclick='setULId(this);'></i>
					                               		</a>
					                                    <a href='javascript:(0)' class='btn btn-danger btn-xs delete' >
					                                    	<i class='fa fa-trash-o'></i>
					                                    </a>
					                              	</td>
					                            </tr>
								             	<?php 
								               	$sqlSelectUserLeave = "	SELECT * FROM u_userleave AS UL
								                        				INNER JOIN cnf_leavetype AS LT ON LT.lt_id = UL.lt_id
								                        				WHERE UL.user_id = ? AND UL.ul_Annual = ? AND ul_Year BETWEEN DATE_SUB(CURDATE(),INTERVAL 4 YEAR) AND YEAR(CURDATE()) 
																		ORDER BY ul_Year DESC";
								              	$stmt = $DB->prepare($sqlSelectUserLeave);
								             	$stmt->bindValue(1, $user_id);
								               	$stmt->bindValue(2, 'Y');
								               	$stmt->execute();
								               
						                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
						                        {
						                        	$year = $row['ul_Year'];
						                        	$leave_type = $row['lt_Name'];
						                        	$remaining_leave = $row['ul_RemainingNumber'];
						                            $expiry_date = $row['ul_ExpiryDate'];
						                        	$ul_id = $row['ul_id'];
						                        	
						                        	if ($row['ul_Status'] == "A")
						                        	{
						                        		$bg_color = "";
						                        		$status = "<span class='center-block padding-5 label label-success'>Active</span>";
						                        	}
						                        	else
						                        	{
						                        		$bg_color = "background:#FFB6C1;";
						                        		$status = "<span class='center-block padding-5 label label-default'>Expired</span>";
						                        	}
						                        ?>
					                        	<tr id='ULrow<?php echo $ul_id; ?>' style="<?php echo $bg_color;?>">
								            		<td><?php echo $year; ?></td>
								            		<td><?php echo $leave_type; ?></td>
								            		<td class='UL<?php echo $ul_id; ?>'><?php echo $remaining_leave; ?></td>
								            		<td><?php echo $expiry_date; ?></td>
								            		<td><?php echo $status; ?></td>
					                                <td>
					                                <?php 
					                                if ($row['ul_Status'] == 'A')
					                                {
					                                ?>
					                                	<p data-toggle='modal' data-target = '#SingleLeaveAdjustModal' onclick='setULId(this);' class='btn btn-primary btn-xs edit'
					                                	ul_Number='<?php echo $remaining_leave; ?>'
					                                    ul_id = '<?php echo $ul_id; ?>'>
					                                    	<i class='fa fa-pencil'></i>
					                                    </p>
														<?php 
														$stmtCheckLeaves = "SELECT ul_id FROM u_userleave WHERE ul_RemainingNumber!=ul_Number AND ul_id = '$ul_id'";
					                                    $stmtCheckLeaves = $DB->prepare($stmtCheckLeaves);
					                                    $stmtCheckLeaves->execute();
					                                    
					                                    if($stmtCheckLeaves->rowCount()<=0)
					                                    {
					                                    	echo "<p href='#' onclick='delUL(".$row['ul_id'].")' class='btn btn-danger btn-xs delete'><i class='fa fa-trash-o'></i></p>";
					                                   	}
					                                   	?>
					                               	<?php 
					                                }
					                               	?>
					                             	</td>
								           		</tr>
								           		<?php 
						                        }
								           		?>
					                   		</tbody>
					            		</table>
					            	</div>
					                  
					                <hr>
					                
					                <h3>Other Leaves</h3>
					                <div class="table-responsive">
						                <table class='table table-bordered' id='OtherLeaveTable'>
						                  	<thead>
							            		<tr>
						                    		<td style="width:10%;" class='leaveYear'>Year</td>
						                        	<td class='lt_Name'>Type</td>
						                         	<td style="width:10%;" class='leaveNumber'>Leave Balance</td>
						                            <td style="width:10%;" class='leaveExpiryDate'>Expired On</td>
						                          	<td style="width:15%;" class='leaveStatus'>Status</td>
						                       		<td style="width:15%;">Actions</td>
							                   	</tr>
						                   	</thead>
							             	<tbody>
							                <?php 
							                $sqlSelectUserLeave = "	SELECT * FROM u_userleave AS UL
							                        				INNER JOIN cnf_leavetype AS LT ON LT.lt_id = UL.lt_id
							                        				WHERE UL.user_id = ? AND UL.ul_Annual = ? AND ul_Year BETWEEN DATE_SUB(CURDATE(),INTERVAL 2 YEAR) AND YEAR(CURDATE()) 
																	ORDER BY ul_Year DESC";
							                $stmt = $DB->prepare($sqlSelectUserLeave);
							                $stmt->bindValue(1, $user_id);
							                $stmt->bindValue(2, 'N');
							                $stmt->execute();
							                
							                while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
							           		{
							             		$year = $row['ul_Year'];
							                   	$leave_type = $row['lt_Name'];
							                   	$remaining_leave = $row['ul_RemainingNumber'];
							                  	$expiry_date = $row['ul_ExpiryDate'];
							                  	$ul_id = $row['ul_id'];
							                  	
							                  	if ($row['ul_Status'] == "A")
							                  	{
							                  		$bg_color = "";
							                  		$status = "<span class='center-block padding-5 label label-success'>Active</span>";
							                  	}
							                  	else
							                  	{
							                  		$bg_color = "background:#FFB6C1;";
							                  		$status = "<span class='center-block padding-5 label label-default'>Expired</span>";
							                  	}
							              	?>    	
							                	<tr id='ULrow<?php echo $ul_id; ?>' style="<?php echo $bg_color; ?>">
											    	<td><?php echo $year; ?></td>
											        <td><?php echo $leave_type; ?></td>
											        <td class='UL<?php echo $ul_id; ?>'><?php echo $remaining_leave; ?></td>
											        <td><?php echo $expiry_date; ?></td>
											        <td><?php echo $status; ?></td>
							                        <td>
							                        <?php 
							                        if ($row['ul_Status'] == 'A')
							                        {
							                        ?>
							                        	<a href='javascript:(0)' class='btn btn-primary btn-xs edit' onclick='setULId(this)' data-toggle='modal' data-target = '#SingleLeaveAdjustModal' 
							                            ul_Number='".$remaining_leave."'
							                           	ul_id = '".$ul_id."'>
							                           		<i class='fa fa-pencil'></i>
							                           	</a>
							                           	<?php 
							                           	$stmtCheckLeaves = "SELECT ul_id FROM u_userleave WHERE ul_RemainingNumber!=ul_Number AND ul_id = '$ul_id'";
							                           	$stmtCheckLeaves = $DB->prepare($stmtCheckLeaves);
							                           	$stmtCheckLeaves->execute();
							                           	if($stmtCheckLeaves->rowCount()<=0){
							                           	
							                           		echo "<p href='#' onclick='delUL(".$row['ul_id'].")' class='btn btn-danger btn-xs delete' ><i class='fa fa-trash-o'  ></i></p>";
							                           	}
							                           	?>
							                        <?php 
							                        }
							                        ?>
													</td>
												</tr>
											<?php 
							           		}
											?>
							         		</tbody>
							      		</table>
							      	</div>
						    	</div>
						   	</div>
						</div>
				   	</div>
				</div>
			</div>
		</section>
	</div>
</div>

<!-- PAGE FOOTER -->
<?php // include page footer
include ("inc/footer.php");
?>
<!-- END PAGE FOOTER -->

<?php //include required scripts
include ("inc/scripts.php");
?>

<script type="text/javascript" src="inc/js/custom.js"></script>

<!-- PAGE RELATED PLUGIN(S) -->
<script src="<?php echo ASSETS_URL; ?>/js/plugin/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo ASSETS_URL; ?>/js/plugin/datatables/dataTables.colVis.min.js"></script>
<script src="<?php echo ASSETS_URL; ?>/js/plugin/datatables/dataTables.tableTools.min.js"></script>
<script src="<?php echo ASSETS_URL; ?>/js/plugin/datatables/dataTables.bootstrap.min.js"></script>
<script src="<?php echo ASSETS_URL; ?>/js/plugin/datatable-responsive/datatables.responsive.min.js"></script>

<script type="text/javascript">
$(function(){

	$("#order-form").validate({
		rules : {
			name : {
				required : true
			},
			email : {
				required : true,
				email : true
			},
			phone : {
				required : true
			},
			interested : {
				required : true
			},
			budget : {
				required : true
			}
		},
		messages : {
			name : {
				required : 'Please enter your name'
			},
			email : {
				required : 'Please enter your email address',
				email : 'Please enter a VALID email address'
			},
			phone : {
				required : 'Please enter your phone number'
			},
			interested : {
				required : 'Please select interested service'
			},
			budget : {
				required : 'Please select your budget'
			}
		},
		errorPlacement : function(error, element) {
			error.insertAfter(element.parent());
		}
	});

	// START AND FINISH DATE
	$('#startdate').datepicker({
		dateFormat : 'dd.mm.yy',
		prevText : '<i class="fa fa-chevron-left"></i>',
		nextText : '<i class="fa fa-chevron-right"></i>',
		onSelect : function(selectedDate) {
			$('#finishdate').datepicker('option', 'minDate', selectedDate);
		}
	});
	
	$('#finishdate').datepicker({
		dateFormat : 'dd.mm.yy',
		prevText : '<i class="fa fa-chevron-left"></i>',
		nextText : '<i class="fa fa-chevron-right"></i>',
		onSelect : function(selectedDate) {
			$('#startdate').datepicker('option', 'maxDate', selectedDate);
		}
	});
	
	$("#userDOB").datepicker({ 
        dateFormat: 'yy-mm-dd',
        prevText : '<i class="fa fa-chevron-left"></i>',
		nextText : '<i class="fa fa-chevron-right"></i>',
        changeMonth: true,
        changeYear: true,
        yearRange: '1900:' + new Date().getFullYear()
    });

	
			 
    $("#uh_effective_date").datepicker({ 
        dateFormat: 'yy-mm-dd',
        prevText : '<i class="fa fa-chevron-left"></i>',
		nextText : '<i class="fa fa-chevron-right"></i>',
        changeMonth: true,
        changeYear: true,
        yearRange: '-100:' + (new Date().getFullYear() + 1)
    }).datepicker("setDate", new Date());

    $("#userEmail").keyup(function(){
    	var userEmail =$("#userEmail").val();

    	if (userEmail.length > 0) {
    		checkUserEmail();
    	}
    });
});
</script>
<script type="text/javascript">
$(document).ready(function() {
	$('#contractDurationDiv').hide();
	$('#contractType').change(function(){
		var contID = this.value;
		if(contID === 'C'){
			$('#contractDurationDiv').show('slow');
		}else{
			$('#contractDurationDiv').hide('slow');
		}
	});
});

function updateJobStatus(userID, jobHistoryID){
	$.ajax({
		url:"ajax/updateJobStatus.php",
	   	method:"POST",
	   	data:{uid:userID, uhid:jobHistoryID},
	   	success:function(data){
	   		$('#message').html(data);

	    	$("#messageModal").modal("show");
	    	$("#messageModal").on("shown.bs.modal", function () {
    	    	window.setTimeout(function () {
    	        	$("#messageModal").modal("hide");
    	        	location.reload(); 
    	    	}, 
    	    	1000);                 
	    	});
	   	}
	});
}

function setUserId(id){
	$("#addNewEmployeeJobForm").attr("onsubmit", "addNewEmployeeJob()");
	$("#addNewEmployeeJobId").val(id);
	$("#editNewEmployeeJobId").val(0);
}
	
function setUserJobId(editEmployeeJobBtn){
	$("#editNewEmployeeJobId").val($(editEmployeeJobBtn).attr("uh_id"));
	$("#addNewEmployeeJobId").val(0);

	var effectiveDate = $(editEmployeeJobBtn).attr("uh_effective_date");
	var locID = $(editEmployeeJobBtn).attr("loc_id");
	var desigID = $(editEmployeeJobBtn).attr("desig_id");
	var deptID = $(editEmployeeJobBtn).attr("dept_id");
	var conTypeID = $(editEmployeeJobBtn).attr("contractType");
	var conDurID = $(editEmployeeJobBtn).attr("contractDuration");
	
	$("#uh_effective_date").val(effectiveDate);
	$("#locID option[value='"+locID+"']").attr('selected', true);
	$("#desigID option[value='"+desigID+"']").attr("selected", true);
	$("#deptID option[value='"+deptID+"']").attr("selected", true);
	$("#contractType option[value='"+conTypeID+"']").attr("selected", true);
	$("#contractDuration option[value='"+conDurID+"']").attr("selected", true);
}
/*
	$('#desigID').change(function(){
		var designID = this.value;
		if(designID > 0){
			//$("#userAnnualLeave").removeAttr('disabled');
			$("#userAnnualLeave").prop("disabled", false);
			//$('#annualLeaveDiv').show('slow');
		}else{
			$("#userAnnualLeave").val('');
			$("#userAnnualLeave").prop("disabled", true);
			//$('#annualLeaveDiv').hide('slow');
		}
		
	    $.ajax({
	    	type: 'post',
	        url:'ajax/getDesignAnnualLeave.php',
	        data: { did : designID },
	        success:function(data,success)
	        {
	        	// show the number of leave requested 
			    $('#userAnnualLeave').val(data);
	        }

	    })
	});*/
function setULId(ulInput){
	$("#SingleLeaveAdjustId").val($(ulInput).attr("ul_id"));
	$("#ul_NumberSingle").val($(ulInput).attr("ul_Number"));
}
</script>

<script type="text/javascript">

 /*
  * SmartAlerts
  */
  // With Callback
  function del(val){

   $.SmartMessageBox({
    title : "Attention required!",
    content : "This is a confirmation box. Do you want to delete the Record?",
    buttons : '[No][Yes]'
   }, function(ButtonPressed) {
    if (ButtonPressed === "Yes") {


		 $.post("config-process.php",
        {
          uh_id : val,
          is_delete:"YES",
          
        },
        function(data,status){ 
            if(data.trim()=="DELETED")
            {
            	 $('#jobrow'+val).remove();

               $.smallBox({
		         title : "Delete Status",
		         content : "<i class='fa fa-clock-o'></i> <i>Record Deleted successfully...</i>",
		         color : "#659265",
		         iconSmall : "fa fa-check fa-2x fadeInRight animated",
		         timeout : 4000
		        });
            }
            else
            {
            	 $.smallBox({
		         title : "Delete Status",
		         content : "<i class='fa fa-clock-o'></i> <i>Problem Deleting Record...</i>",
		         color : "#C46A69",
		         iconSmall : "fa fa-times fa-2x fadeInRight animated",
		         timeout : 4000
		        });
            }
        });             
    }
    if (ButtonPressed === "No") {
     $.smallBox({
      title : "Delete Status",
      content : "<i class='fa fa-clock-o'></i> <i>You pressed No...</i>",
      color : "#C46A69",
      iconSmall : "fa fa-times fa-2x fadeInRight animated",
      timeout : 4000
     });
    }
 
   });
   //e.preventDefault();
  }
</script>

<!-- Add or Edit Job Modal Form -->
<div class="modal fade" id="addNewEmployeeJobModal" role="dialog" >
    <div class="modal-dialog modal-lg">
    	<form action="" method="post" id="addNewEmployeeJobForm">
			<div class="modal-content">
        		<div class="modal-header">
          			<button type="button" class="close" data-dismiss="modal">&times;</button>
          			<h4><span class="glyphicon glyphicon-info-sign"></span> Job Information</h4>
        		</div>
        		
    			<input type="hidden" name="addNewEmployeeJobId" id="addNewEmployeeJobId" value="0">
    			<input type="hidden" name="editNewEmployeeJobId" id="editNewEmployeeJobId" value="0">
      			
	      		<div class="modal-body">
	      			<div class="panel">
	      				<div class="panel-body">
						    <div class="box box-danger">
					            <div class="box-body">
						            <div class="form-group row">
						                <label class="col-sm-3 control-label"><small>Effective Date</small></label>
						                <div class="col-sm-9">
						                	<input type="text" class="form-control" placeholder="YYYY-MM-DD" name="uh_effective_date" id="uh_effective_date">          
										</div>
									</div>
						       		<div class="form-group row">
						                <label class="col-sm-3 control-label"><small>Location</small></label>
						                <div class="col-sm-9">
							                <select name="loc_id" id="locID" class="form-control">
											<?php 
											$locList = getLocationList();
											foreach ($locList as $key => $locArray) 
											{
												?>
												<option value="<?php echo $locArray['loc_id']; ?>">
												<?php echo $locArray['loc_shortName']; ?>
												</option>
												<?php
											}
											?>
											</select>                
										</div>
					            	</div>
	
						            <div class="form-group row">
						                <label class="col-sm-3 control-label"><small>Department</small></label>
						                <div class="col-sm-9">
							                <select name="dept_id" id="deptID" class="form-control">
											<?php 
											$deptList = getActiveDepartments();
											foreach ($deptList as $key => $deptArray) 
											{
											?>
												<option value="<?php echo $deptArray['dept_id']; ?>">
												<?php echo $deptArray['dept_Name']; ?>
												</option>
											<?php
											}
											?>
											</select>                
										</div>
						            </div>
						            <div class="form-group row">
						                <label class="col-sm-3 control-label"><small>Designation<span class="text-danger"> *</span></small></label>
						                <div class="col-sm-9">
						                 <select name="desig_id" id="desigID" class="form-control">
											<?php 
											$desigList = getActiveDesignation();
											foreach ($desigList as $key => $desigArray) 
											{
											?>
												<option value="<?php echo $desigArray['desig_id'];?>">
												<?php echo $desigArray['desig_Name']; ?>
												</option>
											<?php
											}
											?>	
										</select>                
										</div>
						            </div>
						            <div class="form-group row">
						                <label class="col-sm-3 control-label"><small>Contract Type</small></label>
					                   	<div class="col-sm-9">
						                   	<select class="form-control" name="contract_type" id="contractType">
						                   		<option value="P">Permanant</option>
						                   		<option value="C">Contract</option>
						                   	</select>      
					                	</div>
						            </div>
						            <div class="form-group row" id="contractDurationDiv">
						                <label class="col-sm-3 control-label"><small>Contract Duration</small></label>
					                   	<div class="col-sm-9">
						                   	<select class="form-control" name="contract_duration" id="contractDuration">
						                   		<option value="1">1 Year</option>
						                   		<option value="2">2 Years</option>
						                   	</select>      
					                	</div>
						            </div>
					            </div>
					        </div>
				        </div>
	      			</div>
	      		</div>
	      		
	      		<div class="modal-footer">
		          	<button type="submit" class="btn btn-default btn-default " data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Cancel</button>
		          	<button type="submit" class="btn btn-default btn-success"><span class="glyphicon glyphicon-floppy-saved"></span> Save</button>
		        </div>
      		</div>
      	</form>
    </div>
</div>

<!-- Assign New Leave To Employee Form -->
<div class="modal fade" id="EmployeeLeaveAdjustModal" role="dialog" >
    <div class="modal-dialog modal-lg">
    	<form action="" method="post" onsubmit="EmployeeLeaveAdjust()" id="EmployeeLeaveAdjustForm">
      		<div class="modal-content" id="EmployeeLeaveAdjust">
      			<div class="modal-header">
          			<button type="button" class="close" data-dismiss="modal">&times;</button>
          			<h4><span class="glyphicon glyphicon-info-sign"></span> New Leaves</h4>
        		</div>
	      		<div class="modal-body">
	      			<div class="panel">
	      				<div class="panel-body">
						    <div class="box box-danger">
					        	<div class="box-body">
					           <?php 
					           		$user_id = $_GET['user_id'];
					           	?>
					           		<input type="hidden" name="EmployeeLeaveAdjustId" value="<?php echo $user_id ?>">
					           		<input type="hidden" name="CurrentYear" id="CurrentYear" value="<?php echo date('Y'); ?>">
					           	<?php
					           		$DATA="";
	
					           		global $DB;
					           		 $userLeaveQ="SELECT UL.ul_id,UL.lt_id,UL.ul_Year,LT.lt_Name,UL.ul_Number
						            FROM `u_userleave` AS UL 
						            INNER JOIN cnf_leavetype AS LT ON LT.lt_id=UL.lt_id
						            WHERE UL.ul_year=now() AND UL.ul_Status=? AND UL.user_id=?";
						            $stmtUL  = $DB->prepare($userLeaveQ);
						            $stmtUL->bindValue(1,'A');
						            $stmtUL->bindValue(2,$user_id);
						            $stmtUL->execute();
						            if($stmtUL->rowCount())
						            {
						                while($ULRow=$stmtUL->fetch(PDO::FETCH_ASSOC))
						                {
						                    
						                    $DATA.='<div class="form-group row">
						                                <label for="group_name" class="col-sm-3 control-label">'.$ULRow['lt_Name'].'</label>
						                                <div class="col-sm-9">
						                                    <input type="text" name="userleave['.$ULRow['ul_id'].']" value="'.$ULRow['ul_Number'].'" class="form-control">
						                                </div>
						                            </div>';
						                }
						            }
						            echo $DATA;
					            ?>
					            
					            
					            </div><!--End of box-body-->
					        </div><!--End of box-danger-->
				        </div><!--End of panel body-->
	      			</div>
	      		</div>
      			<div class="modal-footer">
		          	<button type="submit" class="btn btn-default btn-default " data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Cancel</button>
		          	<button type="submit" class="btn btn-default btn-success"><span class="glyphicon glyphicon-floppy-saved"></span> Save</button>
		        </div>
      		</div>
      	</form>
    </div>
</div>

<!-- Edit Employee Leave Form -->
<div class="modal fade" id="SingleLeaveAdjustModal" role="dialog" >
    <div class="modal-dialog modal-lg">
    	<form action="" method="post" onsubmit="SingleLeaveAdjust()" id="SingleLeaveAdjustForm">
      		<div class="modal-content" id="SingleLeaveAdjust">
      			<div class="modal-header">
          			<button type="button" class="close" data-dismiss="modal">&times;</button>
          			<h4><span class="glyphicon glyphicon-info-sign"></span> New Job</h4>
        		</div>
      		
	      		<div class="modal-body">
	      			<div class="panel">
	      				<div class="panel-body">
						    <div class="box box-danger">
					                
					        	<div class="box-body">
					            
					       		
					           <?php 
					           		$user_id = $_GET['user_id'];
					           	?>
					           		<input type="hidden" name="SingleLeaveAdjustId" id="SingleLeaveAdjustId">
					           		<input type="hidden" name="CurrentYear" id="CurrentYear" value="<?php echo date('Y'); ?>">
					           	<div class="form-group row">
					                <label for="group_name" class="col-sm-3 control-label"><small>Leave Number</small></label>
				                   	<div class="col-sm-9">
					                   	<input type="number" name="ul_Number" id="ul_NumberSingle" class="form-control"> 
				                	</div>
					            </div>
					            
					            </div><!--End of box-body-->
					        </div><!--End of box-danger-->
				        </div><!--End of panel body-->
	      			</div>
	      		</div>
	      		<div class="modal-footer">
			  		<button type="submit" class="btn btn-default btn-default " data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Cancel</button>
			    	<button type="submit" class="btn btn-default btn-success"><span class="glyphicon glyphicon-floppy-saved"></span> Save</button>
	      		</div>
      		</div>
      	</form>
    </div>
</div>		

<div class="modal fade" id="messageModal" role="dialog">
    <div class="modal-dialog modal-md">
    	<div class="modal-content">
      		<div class="modal-header">
     			<button type="button" class="close" data-dismiss="modal">&times;</button>
       			<h4><span class="glyphicon glyphicon-info-sign"></span> Message</h4>
    		</div>
      		
	 		<div class="modal-body">
	 			<div id="message">
	 			
	 			</div>
	      	</div>
	      		
    		<div class="modal-footer">
				<button type="submit" class="btn btn-default btn-default " data-dismiss="modal">
					<span class="glyphicon glyphicon-remove"></span> Close
				</button>
			</div>
    	</div>
    </div>
</div>	