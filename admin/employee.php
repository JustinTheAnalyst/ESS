<?php 
//initilize the page
require_once ("inc/init.php");
include("inc/PHP/functions.php");
//require UI configuration (nav, ribbon, etc.)
require_once ("inc/config.ui.php");

$page_title = "Manage Employees";

// include header
include ("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["Employees"]["active"] = true;
include ("inc/nav.php");
?>
<style type="text/css">
#addNewEmployeeJob > .form-control {
	margin-top: 10px;
}

.control-label{
	padding-top: 0 !important;
}
</style>
<div id="main" role="main">
<?php
include("inc/ribbon.php");
?>
	<div id="content">
		<section id="widget-grid" class="">
			<div class="row">
				<div class="row" style="padding-right: 30px; margin-bottom: 10px;">
					<button type="button" class="btn btn-info pull-right" data-toggle="modal" data-target="#employeeModal" id="createEmployeeBtn">
						<span class="glyphicon glyphicon-plus"></span> New Employee
					</button>
				</div>
				<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<div class="jarviswidget jarviswidget-color-purity" id="wid-id-1" data-widget-editbutton="false">
						<header>				
							<h2>Employee Listing</h2>
						</header>
			
						<div>		
							<!-- widget content -->
							<div class="widget-body no-padding">
								<table id="dt_basic" class="table table-striped table-bordered" width="100%">
							        <thead>
							            <tr id="headRow">
							            	<th style="width:10%;" data-class="expand">Emp. No.</th>
						                    <th style="width:30%;">Name</th>
						                    <th style="width:5%;">Loc</th>
						                    <th style="width:10%;" data-hide="phone">Dept</th>
						                    <th style="width:10%;" data-hide="phone">Email</th>
						                    <th style="width:10%;" data-hide="phone">Gender</th>
						                    <th style="width:7%;" data-hide="phone">Con. Type</th>
						                    <th style="width:10%;">Status</th>
						                    <th style="width:8%;" data-hide="phone">Action</th>
							            </tr>
							        </thead>
							        
							        <tbody>
						            <?php 
						            $row = getEmployeeList();
						           
						            foreach ($row as $key => $empArray) 
						            {
						            	if($empArray['user_Status']=='A')
						            	{ 
						            		$user_Status="<span class='center-block padding-5 label label-success'>Active</span>"; 
						            	}
						            	else
						            	{ 
						            		$user_Status="<span class='center-block padding-5 label label-danger'>In-Active</span>"; 
						            	}
						            	
						            	if($empArray['user_Gender']=='M')
						            	{ 
						            		$user_Gender="Male"; 
						            	}
						            	else
						            	{ 
						            		$user_Gender="Female";
						            	}
						            	
						            	if ($empArray['uc_ContractType'] == 'P')
						            	{
						            		$cType = 'Permenant';
						            	}
						            	else 
						            	{
						            		$cType = 'Contract';
						            	}
						            	?>
						            	<tr id="row<?php echo $empArray['user_id'];?>">
						            		<td class="code"><?php echo $empArray['user_UserCode']; ?></td>
						            		<td class="name"><?php echo $empArray['user_FirstName']." ".$empArray['user_LastName']; ?></td>
							                <td class="loc"><?php echo $empArray['loc_shortName']; ?></td>
							                <td class="dept"><?php echo $empArray['dept_ShortName']; ?></td>
							                <td class="email"><?php echo $empArray['user_Email']; ?></td>
							                <td class="gender"><?php echo $user_Gender; ?></td>
							                <td class="ctype"><?php echo $cType; ?></td>
							                <td class="status"><?php echo $user_Status; ?></td>
							                <td>
								                <div class="btn-group">
													<button class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
														Action <span class="caret"></span>
													</button>
													<ul class="dropdown-menu dropdown-menu-right">
														<li>
															<a href="javascript:viewEmployee(<?php echo $empArray['user_id'];?>)" class="view"><i class="fa fa-eye fa-lg fa-fw txt-color-greenLight"></i> View</a>
														</li>
													
														<li>
															<a href="employee-edit.php?user_id=<?php echo $empArray['user_id'];?>" class="edit"><i class="fa fa-pencil fa-lg fa-fw txt-color-greenLight"></i> Edit</a>
														</li>
														
														<li>
															<a href="javascript:del(<?php echo $empArray['user_id'];?>)" class="delete"><i class="fa fa-trash-o fa-lg fa-fw txt-color-greenLight"></i> Delete</a>
														</li>
														<li class="divider"></li>
														<li>
															<a href="javascript:void(0);"><i class="fa fa-file fa-lg fa-fw txt-color-greenLight"></i>Export PDF</a>
														</li>
													</ul>
												</div>
							                </td>
						            	</tr>
						            	<?php
						            }
						            ?>
							        </tbody>
							        <tfoot>
							        	<tr id="copyRow" style="display: none;">
							        		<td class="code"></td>
							                <td class="name"></td>
							                <td class="loc"></td>
							                <td class="dept"></td>
							                <td class="email"></td>
							                <td class="gender"></td>
							                <td class="status"></td>
							                <td>
							                	<div class="btn-group">
													<button class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
														Action <span class="caret"></span>
													</button>
													<ul class="dropdown-menu dropdown-menu-right">
														<li>
															<a href="" class="view"><i class="fa fa-eye fa-lg fa-fw txt-color-greenLight"></i> View</a>
														</li>
													
														<li>
															<a href="" class="edit"><i class="fa fa-pencil fa-lg fa-fw txt-color-greenLight"></i> Edit</a>
														</li>
														
														<li>
															<a href="" class="delete"><i class="fa fa-trash-o fa-lg fa-fw txt-color-greenLight"></i> Delete</a>
														</li>
														<li class="divider"></li>
														<li>
															<a href="javascript:void(0);"><i class="fa fa-file fa-lg fa-fw txt-color-greenLight"></i>Export PDF</a>
														</li>
													</ul>
												</div>
							                </td>
							            </tr>
							        </tfoot>
								</table>
							</div>
						</div>
					</div>
				</article>
			</div>
			<!-- 
			<div class="row">
				<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<div class="jarviswidget jarviswidget-color-purity" id="wid-id-1" data-widget-editbutton="false">
						<header>				
							<h2>Leave Summary</h2>
						</header>
			
						<div>		
							<div class="widget-body no-padding">
								<table id="dt_basic" class="table table-striped table-bordered" width="100%">
							        <thead>
							            <tr id="headRow">
						                    <th data-class="expand">Leave Type</th>
						                    <th>Period</th>
						                    <th>Balance</th>
						                    <th>Expired On</th>
							            </tr>
							        </thead>
							        
							        <tbody>
						            <?php 
						    		$allAnnualLeaves = allAnnualLeaves(150);
			
						    		$balance = 0;
						    		if($allAnnualLeaves)
						    		{
						    			foreach ($allAnnualLeaves as $key => $leave) 
							    		{
							    			if($leave['ul_Status']=='A')
							    			{
							    				$balance+=$leave['ul_RemainingNumber'];
							    			}
							    			?>
						    			<tr>
						    				<td><?php echo $leave['lt_Name']." (".$leave['ul_Year'].")"; ?></td>
						    				<td><?php echo $leave['ul_FromDate']." - ".$leave['ul_ToDate']; ?></td>
						    				<td><?php echo $leave['ul_RemainingNumber']; ?></td>
						    				<td><?php echo $leave['ul_ExpiryDate']; ?></td>
						    			</tr>
							    			<?php
							    		}
							    		?>
							    		<tr>
							    			<td colspan="2" style="text-align: right;"><b>Total</b></td>
							    			<td colspan="2"><?php echo $balance; ?></td>
							    		</tr>
							    		<?php
						    			}
							    		
						    			$current_year = date("Y");
						    			
						    			global $DB;
						    			$stmt2 = $DB->prepare('SELECT * FROM u_userleave AS UL
													          INNER JOIN cnf_leavetype AS LT on LT.lt_id=UL.lt_id
													          WHERE UL.user_id=? AND UL.lt_id!=1 AND UL.ul_Year=?
													          ORDER BY UL.lt_id');
						    			$stmt2->bindValue(1,150);
						    			$stmt2->bindValue(2,$current_year);
						    			$stmt2->execute();

						    			if($stmt2->rowCount())
						    			{
						    				while($row = $stmt2->fetch(PDO::FETCH_ASSOC))
						    				{
						    			?>
						    				<tr>
							    				<td><?php echo $row['lt_Name']; ?></td>
							    				<td><?php echo $row['ul_FromDate']." - ".$leave['ul_ToDate']; ?></td>
							    				<td><?php echo $row['ul_RemainingNumber']; ?></td>
							    				<td><?php echo $row['ul_ExpiryDate']; ?></td>
							    			</tr>
							    			<?php 
						    				}
						    			}
						    		?>
							        </tbody>
								</table>
							</div>
						</div>
					</div>
				</article>
			</div>
			-->
		</section>
	</div>
</div>

<!-- PAGE FOOTER -->
<?php 
//include page footer						           
include ("inc/footer.php");
?>
<!-- END PAGE FOOTER -->

<?php 
//include required scripts
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
$(document).ready(function() {
	/* BASIC ;*/
	var responsiveHelper_dt_basic = undefined;
	var responsiveHelper_datatable_fixed_column = undefined;
	var responsiveHelper_datatable_col_reorder = undefined;
	var responsiveHelper_datatable_tabletools = undefined;
	
	var breakpointDefinition = {
		tablet : 1024,
		phone : 480
	};

	$('#dt_basic').dataTable({
		"sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-12 hidden-xs'l>r>"+
			"t"+
			"<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
		"autoWidth" : true,
		"preDrawCallback" : function() {
			// Initialize the responsive datatables helper once.
			if (!responsiveHelper_dt_basic) {
				responsiveHelper_dt_basic = new ResponsiveDatatablesHelper($('#dt_basic'), breakpointDefinition);
			}
		},
		"rowCallback" : function(nRow) {
			responsiveHelper_dt_basic.createExpandIcon(nRow);
		},
		"drawCallback" : function(oSettings) {
			responsiveHelper_dt_basic.respond();
		}
	});
	/* END BASIC */
    
	/* COLUMN FILTER  */
    var otable = $('#datatable_fixed_column').DataTable({
    	//"bFilter": false,
    	//"bInfo": false,
    	//"bLengthChange": false
    	//"bAutoWidth": false,
    	//"bPaginate": true,
    	//"bStateSave": true // saves sort state using localStorage
    	/*'aoColumnDefs': [{
        'bSortable': false,
        'aTargets': [-7] // 1st one, start by the right 
    	}], */
		"sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6 hidden-xs'f><'col-sm-6 col-xs-12 hidden-xs'<'toolbar'>>r>"+
				"t"+
				"<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
		"autoWidth" : true,
		"preDrawCallback" : function() {
			// Initialize the responsive datatables helper once.
			if (!responsiveHelper_datatable_fixed_column) {
				responsiveHelper_datatable_fixed_column = new ResponsiveDatatablesHelper($('#datatable_fixed_column'), breakpointDefinition);
			}
		},
		"rowCallback" : function(nRow) {
			responsiveHelper_datatable_fixed_column.createExpandIcon(nRow);
		},
		"drawCallback" : function(oSettings) {
			responsiveHelper_datatable_fixed_column.respond();
		}
    });
    	   
    // Apply the filter
    $("#datatable_fixed_column thead th input[type=text]").on( 'keyup change', function () {
    	
        otable
            .column( $(this).parent().index()+':visible' )
            .search( this.value )
            .draw();
            
    } );
    /* END COLUMN FILTER */  
    
    /* TABLETOOLS */
	$('#datatable_tabletools').dataTable({
		
		// Tabletools options: 
		//   https://datatables.net/extensions/tabletools/button_options
		"sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-6 hidden-xs'T>r>"+
				"t"+
				"<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-sm-6 col-xs-12'p>>",
        "oTableTools": {
        	 "aButtons": [
             "copy",
             "csv",
             "xls",
                {
                    "sExtends": "pdf",
                    "sTitle": "SmartAdmin_PDF",
                    "sPdfMessage": "SmartAdmin PDF Export",
                    "sPdfSize": "letter"
                },
             	{
                	"sExtends": "print",
                	"sMessage": "Generated by SmartAdmin <i>(press Esc to close)</i>"
            	}
             ],
            "sSwfPath": "js/plugin/datatables/swf/copy_csv_xls_pdf.swf"
        },
		"autoWidth" : true,
		"preDrawCallback" : function() {
			// Initialize the responsive datatables helper once.
			if (!responsiveHelper_datatable_tabletools) {
				responsiveHelper_datatable_tabletools = new ResponsiveDatatablesHelper($('#datatable_tabletools'), breakpointDefinition);
			}
		},
		"rowCallback" : function(nRow) {
			responsiveHelper_datatable_tabletools.createExpandIcon(nRow);
		},
		"drawCallback" : function(oSettings) {
			responsiveHelper_datatable_tabletools.respond();
		}
	}); 

})

</script>
<script type="text/javascript">
$(document).ready(function() {
	$('#desigId').change(function(){
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
	}); 

	$('#contractDurationDiv').hide();
	$('#contractType').change(function(){
		var contID = this.value;
		if(contID === 'C'){
			$('#contractDurationDiv').show('slow');
		}else{
			$('#contractDurationDiv').hide('slow');
		}
	});
})
</script>

<style type="text/css">
	.modal-lg {
    width: 90%; /* respsonsive width */
    /*margin-left:-10%;*/
}
</style>
<!-- Add Employee Modal Form -->
<div class="modal fade" id="employeeModal" role="dialog">
	<div class="modal-dialog modal-lg">
 		<form class="form-horizontal" id="create-employee-form" enctype="multipart/form-data" onsubmit="createEmployee();" method="post" accept-charset="utf-8">
      		<div class="modal-content">
        		<div class="modal-header">
          			<button type="button" class="close" data-dismiss="modal">&times;</button>
          			<h4 ><span class="glyphicon glyphicon-book"></span> Employee</h4>
        		</div>
        		<div class="modal-body">
        			<div class="row">
	          			<div class="col-sm-6">
	        				<div class="panel panel-primary">
		      					<div class="panel-heading">
			      					<div class="box-header">
		                  				<h6 class="box-title"><i class="fa fa-pencil"></i>&nbsp;Personal Details</h6>
		            				</div>
		      					</div>
		      					<div class="panel-body" style="margin-bottom:19px;">
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
								                   <input class="form-control" name="user_FirstName" required id="userFirstName">                
								                </div>
								            </div>
								            <div class="form-group">
								                <label for="group_name" class="col-sm-3 control-label"><small>Last Name<span class="text-danger"> *</span></small></label>
								                <div class="col-sm-9">
								                   <input class="form-control" name="user_LastName" required id="userLastName">                
								                </div>
								            </div>
								            <!-- <div class="form-group">
								                <label for="group_name" class="col-sm-3 control-label"><small>Father Name</small></label>
								                <div class="col-sm-9">
								                   <input class="form-control" name="user_FatherName" id="userFatherName">             
								                </div>
								            </div> -->
								            <div class="form-group">
								                <label for="group_name" class="col-sm-3 control-label"><small>Date of Birth</small></label>
								                <div class="col-sm-9">                                       
								                    <input type="text" class="form-control" placeholder="yyyy-mm-dd" name="user_DOB" id="userDOB">   
								                                   
								                </div>
								            </div>
								            <div class="form-group">
								                <label for="group_name" class="col-sm-3 control-label"><small>Gender</small></label>
								                <div class="col-sm-9">
								                   	<select class="form-control" name="user_Gender" id="userGender">
														<option value="M">Male</option>
														<option value="F">Female</option>
													</select>               
												 </div>
								            </div>
								            <div class="form-group">
								                <label for="group_name" class="col-sm-3 control-label"><small>Phone</small></label>
								                <div class="col-sm-9">
								                   <input type="text" class="form-control" name="user_PhoneNo" id="userPhoneNo">                
								                 </div>
								            </div>
	         
								            <div class="form-group">
								                <label for="group_name" class="col-sm-3 control-label"><small>Mailing Address</small></label>
								                <div class="col-sm-9">
								                   <textarea  class="form-control" placeholder="Temporary Address" cols="30" rows="6" name="user_TAddress" id="userTAddress"></textarea>               
								                 </div>
								            </div>
								            <div class="form-group">
								                <label for="group_name" class="col-sm-3 control-label"><small>Permanent Address</small></label>
								                <div class="col-sm-9">
								                   <textarea  class="form-control" placeholder="Permanent Address" cols="30" rows="6" name="user_PAddress" id="userPAddress"></textarea>                
								                 </div>
								            </div>
	                					</div>
	               					</div>
	            				</div>
		   					</div>
	           			</div>
           			
	           			<div class="col-sm-6">
	           				<div class="panel panel-default">
		      					<div class="panel-heading">
			      					<div class="box-header">
		                  				<h6 class="box-title"><i class="fa fa-lock"></i>&nbsp;Login Details</h6>
		            				</div>
		      					</div>
		      					<div class="panel-body">
			    					<div class="box box-danger">
		        						<div class="panel-body">
				        					<div class="box box-primary">
					        					<div class="box-body">
										            <div class="form-group">
										                <label for="group_name" class="col-sm-3 control-label"><small>E-Mail<span class="text-danger"> *</span></small></label>
										                <div class="col-sm-9">
										                   <input type="email" class="form-control" placeholder="employeeName@mispmis.com" name="user_Email" id="userEmail" required>   
										                   <div id="user_EmailCheck"></div>             
										                </div>
										            </div>
										            <div class="form-group">
										                <label for="group_name" class="col-sm-3 control-label"><small>Password<span class="text-danger"> *</span></small></label>
										                <div class="col-sm-9">
										                   <input type="password" class="form-control" required name="user_Password" id="userPassword">                
										                </div>
										            </div>
										            <div class="form-group">
										                <label for="group_name" class="col-sm-3 control-label"><small>User Type<span class="text-danger"> *</span></small></label>
										                <div class="col-sm-9">
										                   <select class="form-control" name="user_Type">
										                   	<option value="E">Employee</option>
										                   	<option value="M">Manager</option>
										                   	<option value="C">Clerk</option>
										                   	<option value="T">Top Management</option>
										                   	<option value="A">Admin</option>
										                   </select>               
										                </div>
										            </div>
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
								                   <input type="text" class="form-control" placeholder="39283" name="user_UserCode" id="userUserCode" onkeyup="checkUserCode();" required>
								                   <div id="user_UserCodeCheck"></div>
								                </div>
								            </div>
		       								<div class="form-group">
	                							<label for="group_name" class="col-sm-3 control-label"><small>Location</small></label>
	                							<div class="col-sm-9">
								                	<select name="loc_id" id="locID" class="form-control">
													<?php 
													$locList = getLocationList();
													foreach ($locList as $key => $locArray) 
													{
														?>
														<option value="<?php echo  $locArray['loc_id']; ?>"><?php echo $locArray['loc_shortName']." - ".$locArray['loc_name']; ?></option>
														<?php
													}
													?>
													</select>                
												</div>
	            							</div>
	
		            						<div class="form-group">
								                <label for="group_name" class="col-sm-3 control-label"><small>Department</small></label>
								                <div class="col-sm-9">
								                <select name="dept_id" id="deptId" class="form-control">
													<?php 
													$deptList = getActiveDepartments();
													foreach ($deptList as $key => $deptArray) 
													{
														?>
														<option value="<?php echo  $deptArray['dept_id']; ?>"><?php echo  $deptArray['dept_Name']; ?></option>
														<?php
													}
													?>
												</select>                
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
														?>
														<option value="<?php echo  $reportToArray['user_id']; ?>"><?php echo  $reportToArray['user_FirstName']." ".$reportToArray['user_LastName']; ?></option>
														<?php
													}
													?>
												</select>                
												</div>
								            </div>
								            
								            <div class="form-group">
								                <label for="group_name" class="col-sm-3 control-label"><small>Contract Type</small></label>
							                   	<div class="col-sm-9">
								                   	<select class="form-control" name="contract_type" id="contractType">
								                   		<option value="P">Permanant</option>
								                   		<option value="C">Contract</option>
								                   	</select>      
							                	</div>
								            </div>
								            <div class="form-group" id="contractDurationDiv">
								                <label for="group_name" class="col-sm-3 control-label"><small>Contract Duration</small></label>
							                   	<div class="col-sm-9">
								                   	<select class="form-control" name="contract_duration" id="contractDuration">
								                   		<option value="1">1 Year</option>
								                   		<option value="2">2 Years</option>
								                   	</select>      
							                	</div>
								            </div>
								            <div class="form-group">
								                <label for="group_name" class="col-sm-3 control-label"><small>Joining Date</small></label>
								                <div class="col-sm-9">                    
													<input type="text" name="user_JoiningDateTime" id="userJoiningDateTime" placeholder="yyyy-mm-dd" class="form-control" value="">                
								                </div>
								            </div>
								            <div class="form-group">
								                <label for="group_name" class="col-sm-3 control-label"><small>Status</small></label>
								                <div class="col-sm-9">
								                   <select class="form-control" name="user_Status" id="userStatus">
								                   		<option value="A">Active</option>
								                   		<option value="I">In-Active</option>
								                   </select>      
								                </div>
								            </div>
							            </div>
							        </div>
						        </div>
							</div>
						</div>
					</div>
					
					<div class="row">
						<div class="col-sm-6">
							<div class="panel panel-warning">
						      	<div class="panel-heading">
							      	<div class="box-header">
						                  <h6 class="box-title"><i class="fa fa-plane"></i>&nbsp; Leave Entitlement</h6>
						            </div>
						      	</div>
						      	<div class="panel-body">
							        <div class="box box-danger">
							           <div class="box-header">
							                </div>
							                <div class="box-body">
							                	<div class="form-group">
									                <label for="group_name" class="col-sm-3 control-label"><small>Designation<span class="text-danger"> *</span></small></label>
									                <div class="col-sm-9">
									                 <select name="desig_id" id="desigId" class="form-control" required>
									                 	<option value="">-- Select --</option>
														<?php 
														$desigList = getActiveDesignation();
														foreach ($desigList as $key => $desigArray) 
														{
															?>
															<option value="<?php echo $desigArray['desig_id'];?>"><?php echo $desigArray['desig_Name']; ?></option>
															<?php
														}
														?>	
													</select>                
													</div>
									            </div>
							                	<div class="form-group" id="annualLeaveDiv">
					                                <label for="group_name" class="col-sm-3 control-label">Annual Leave</label>
					                                <div class="col-sm-9">
					                                    <input type="text" placeholder="Please select a designation first." name="user_annualleave" id="userAnnualLeave" value="" class="form-control" disabled>
					                                </div>
					                            </div>
							                <?php 
					            			$sqlSelectLeaveType = "	SELECT * FROM cnf_leavetype AS LT
																	INNER JOIN cnf_otherleave AS OL ON OL.lt_id = LT.lt_id
																	WHERE LT.lt_Status = ? AND LT.lt_Annual = 'N' AND OL.ol_Year = ?";
									        $stmtUL  = $DB->prepare($sqlSelectLeaveType);
									        $stmtUL->bindValue(1,'A');
									        $stmtUL->bindValue(2,date("Y"));
									        $stmtUL->execute();
									            
								            if($stmtUL->rowCount())
								            {
								            	$currentMonth = date('m');
								            	
								                while($ULRow=$stmtUL->fetch(PDO::FETCH_ASSOC))
								                {
								                	// ONLY apply this after created account for the existing employee
								                	//$dl_Number = ceil(($ULRow['ol_Number']/12)*(12-$currentMonth));
								                	$dl_Number = $ULRow['ol_Number'];
								                	
								                    echo '<div class="form-group">
								                                <label for="group_name" class="col-sm-3 control-label">'.$ULRow['lt_Name'].'</label>
								                                <div class="col-sm-9">
								                                    <input type="text" name="userleave['.$ULRow['lt_id'].']" value="'.$dl_Number.'" class="form-control">
								                                </div>
								                            </div>';
								                }
								            }
											?>
							                </div>    
							        </div>
								</div>
		    				</div>
	   					</div>
	   					
	   					<div class="col-sm-6">
					     	<div class="panel panel-success">
					      		<div class="panel-heading">
							      	<div class="box-header">
						           		<h6 class="box-title"><i class="fa fa-file"></i>&nbsp;Documents</h6>
						            </div>
					      		</div>
						      	<div class="panel-body">
							        <div class="box box-danger">
						              	<div class="box-body">
						                <?php 
					                	$docList = getActiveDocuments();
					                	foreach ($docList as $key => $docArray) 
					                	{
					                		?>
						                	<div class="form-group">
						                        <label for="group_name" class="col-sm-3 control-label"><small><?php echo $docArray['doc_Name']; ?></small></label>
						                        <div class="col-sm-9">
						                           <input type="file" name="docs[<?php echo $docArray['doc_id'];?>]" class="form-control">                 
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
				    	<div class="form-group text-left">
				            <div class="col-sm-offset-2 col-sm-6">
				                <input type="hidden" name="user_id" value="0" id="userId">      
				            </div>
				        </div>
				  	</div>
  				</div>
			 	<div class="modal-footer">
			   		<button type="submit" class="btn btn-default btn-default " data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Cancel</button>
					<button type="submit" class="btn btn-default btn-success" id="save" ><span class="glyphicon glyphicon-floppy-saved"></span> Save</button>
				</div>
			</div>
		</form>
    </div>
</div> 

<!-- View Employee Profile Modal -->
<div class="modal fade" id="viewEmployeeModal" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content" id="viewEmployeeModalContent">

      </div>
    </div>
</div>

<!-- Edit Employee Profile Modal -->
<div class="modal fade" id="editEmployeeModal" role="dialog">
    <div class="modal-dialog modal-lg">
      	<div class="modal-content" id="editEmployeeShow">

      	</div>
    </div>
</div>

<script type="text/javascript">
$(function(){
	$("#userDOB").datepicker({ 
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true,
        yearRange: '1900:' + new Date().getFullYear(),
        beforeShow: function(input, inst) {
            $(document).off('focusin.bs.modal');
        },
        onClose:function(){
            $(document).on('focusin.bs.modal');
        }
    }).datepicker('setDate', new Date());
     
    $("#userJoiningDateTime").datepicker({ 
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true,
        yearRange: '1900:' + new Date().getFullYear()
    }).datepicker('setDate', new Date());

    $("#userEmail").keyup(function(){
    	var userEmail =$("#userEmail").val();

    	if (userEmail.length > 0) {
    		checkUserEmail();
    	}
    });
});
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
          user_id : val,
          is_delete:"YES",
          
        },
        function(data,status){ 
            if(data.trim()=="DELETED")
            {
            	 $('#row'+val).remove();
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
   e.preventDefault();
  }
</script>
<?php
//include footer
include ("inc/google-analytics.php");
?>