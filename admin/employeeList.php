<?php 
include('inc/PHP/functions.php');
//initilize the page
require_once ("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once ("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

 YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
 E.G. $page_title = "Custom Title" */

$page_title = "Employee List";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
//$page_css[] = "your_style.css";
include ("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["Report"]["sub"]["Employee List"]["active"] = true;
include ("inc/nav.php");
?>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
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
		//configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
		//$breadcrumbs["New Crumb"] => "http://url.com"
		
		include("inc/ribbon.php");
	?>

	<!-- MAIN CONTENT -->
	<div id="content">
		
		<!-- widget grid -->
		<section id="widget-grid" class="">
		
<!-- row -->
<div class="row">

	<!-- NEW WIDGET START -->
	<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
	<div class="row" style="padding-right: 30px; margin-bottom: 10px;">
		
	</div>
		<!-- Widget ID (each widget will need unique ID)-->
		<div class="jarviswidget jarviswidget-color-purity" id="wid-id-1" data-widget-editbutton="false">
			<header>				
				<h2>Employee</h2>
			</header>

			<!-- widget div-->
			<div>		
				<!-- widget content -->
				<div class="widget-body no-padding">
					<table id="datatable_tabletools" class="table table-striped table-bordered" width="100%">

				        <thead>
							
				            <tr id="headRow">
			                    <th data-class="expand">Name</th>
			                    <th data-hide="phone">Father Name</th>
			                    <th data-hide="phone">Email</th>
			                    <th data-hide="phone">Phone No</th>
			                    <th data-hide="phone,tablet">Gender</th>
			                    <th data-hide="phone,tablet">DOB</th>
			                    <th data-hide="phone,tablet">Joining Date</th>
			                    <th data-hide="phone,tablet">Group</th>
			                    <th data-hide="phone,tablet">Department</th>
			                    <th data-hide="phone,tablet">Designation</th>
			                    <th data-hide="phone,tablet">Status</th>
				            </tr>


				            
				        </thead>
				        
				        <tbody>
				        
				            
				            <?php 
				            $row=getEmployeeListPDF();
				            //print_r($row);
				           
				            foreach ($row as $key => $empArray) 
				            {
				            	if($empArray['user_Status']=='A'){ $user_Status="Active"; }else{ $user_Status="In-Active"; }
				            	if($empArray['user_Gender']=='M'){ $user_Gender="Male"; }else{ $user_Gender="Female";}
				            	?>
				            	<tr id="row<?=$empArray['user_id'];?>">
				            		<td class="name"><?=$empArray['user_FirstName']." ".$empArray['user_LastName']; ?></td>
					                <td class="fathername"><?=$empArray['user_FatherName']; ?></td>
					                <td class="email"><?=$empArray['user_Email']; ?></td>
					                <td class="phoneno"><?=$empArray['user_PhoneNo']; ?></td>
					                <td class="gender"><?=$user_Gender; ?></td>

					                <td class="phoneno"><?=$empArray['user_DOB']; ?></td>
					                <td class="phoneno"><?= date('d/m/Y',strtotime($empArray['user_JoiningDateTime'])); ?></td>
					                <td class="phoneno"><?=$empArray['group_Name']; ?></td>

					                <td class="phoneno"><?=$empArray['desig_Name']; ?></td>
					                <td class="phoneno"><?=$empArray['dept_Name']; ?></td>
					               	
					             
					                <td class="status"><?=$user_Status; ?></td>
					                
				            	</tr>
				            	<?php
				            }
				            ?>

				        </tbody>
				
					</table>

				</div>
				<!-- end widget content -->
	
			</div>
			<!-- end widget div -->

		</div>
		<!-- end widget -->


	</article>
	<!-- WIDGET END -->

</div>

<!-- end row -->
		
			<!-- end row -->
		
		</section>
		<!-- end widget grid -->


	</div>
	<!-- END MAIN CONTENT -->

</div>
<!-- END MAIN PANEL -->
<!-- ==========================CONTENT ENDS HERE ========================== -->

<!-- PAGE FOOTER -->
<?php // include page footer
include ("inc/footer.php");
?>
<!-- END PAGE FOOTER -->

<?php //include required scripts
include ("inc/scripts.php");
?>
<script type="text/javascript" src="inc/js/custom2.js"></script>
<!-- PAGE RELATED PLUGIN(S) -->
<script src="<?php echo ASSETS_URL; ?>/js/plugin/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo ASSETS_URL; ?>/js/plugin/datatables/dataTables.colVis.min.js"></script>
<script src="<?php echo ASSETS_URL; ?>/js/plugin/datatables/dataTables.tableTools.min.js"></script>
<script src="<?php echo ASSETS_URL; ?>/js/plugin/datatables/dataTables.bootstrap.min.js"></script>
<script src="<?php echo ASSETS_URL; ?>/js/plugin/datatable-responsive/datatables.responsive.min.js"></script>

<script type="text/javascript">

// DO NOT REMOVE : GLOBAL FUNCTIONS!

$(document).ready(function() {
	
	/* // DOM Position key index //
		
	l - Length changing (dropdown)
	f - Filtering input (search)
	t - The Table! (datatable)
	i - Information (records)
	p - Pagination (paging)
	r - pRocessing 
	< and > - div elements
	<"#id" and > - div with an id
	<"class" and > - div with a class
	<"#id.class" and > - div with an id and class
	
	Also see: http://legacy.datatables.net/usage/features
	*/	

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
    
    // custom toolbar
  /*  $("div.toolbar").html('<div class="text-right"><img src="img/logo.png" alt="SmartAdmin" style="width: 111px; margin-top: 3px; margin-right: 10px;"></div>');*/
    	   
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
	//$('#annualLeaveDiv').hide();
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

<script type="text/javascript" src="inc/js/custom2.js"></script>
<style type="text/css">
	.modal-lg {
    width: 90%; /* respsonsive width */
    /*margin-left:-10%;*/
}
</style>
<!-- Modal -->
  <div class="modal fade" id="employeeModal" role="dialog">
    <div class="modal-dialog modal-lg">
      <!-- Modal content-->
 <form class="form-horizontal" id="create-employee-form" enctype="multipart/form-data" onsubmit="createEmployee()" method="post" accept-charset="utf-8">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 ><span class="glyphicon glyphicon-book"></span> Employee</h4>
        </div>
        <div class="modal-body">
          
       
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
                   <input class="form-control" name="user_FirstName" required="" id="userFirstName">                
                </div>
            </div>
            <div class="form-group">
                <label for="group_name" class="col-sm-3 control-label"><small>Last Name<span class="text-danger"> *</span></small></label>
                <div class="col-sm-9">
                   <input class="form-control" name="user_LastName" required="" id="userLastName">                
                </div>
            </div>
            <div class="form-group">
                <label for="group_name" class="col-sm-3 control-label"><small>Father Name</small></label>
                <div class="col-sm-9">
                   <input class="form-control" name="user_FatherName" id="userFatherName">             
                </div>
            </div>
            <div class="form-group">
                <label for="group_name" class="col-sm-3 control-label"><small>Date of Birth</small></label>
                <div class="col-sm-9">                                       
                    <input type="date" class="form-control" name="user_DOB" id="userDOB">                  
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
                <label for="group_name" class="col-sm-3 control-label"><small>Phone (Temporary)</small></label>
                <div class="col-sm-9">
                   <input type="text" class="form-control" name="user_PhoneNo" id="userPhoneNo">                
                 </div>
            </div>
         
            <div class="form-group">
                <label for="group_name" class="col-sm-3 control-label"><small>Local Address</small></label>
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
            <div class="form-group">
                <div class="col-sm-12">
                    <label for="group_name" class="control-label"><h3>Account Login</h3></label>
                </div>
            </div>
            
            <div class="form-group">
                <label for="group_name" class="col-sm-3 control-label"><small>E-Mail<span class="text-danger"> *</span></small></label>
                <div class="col-sm-9">
                   <input type="email" class="form-control" required="" name="user_Email" id="userEmail" onkeyup="checkUserEmail()">   
                   <div id="user_EmailCheck"></div>             
                </div>
            </div>
            <div class="form-group">
                <label for="group_name" class="col-sm-3 control-label"><small>Password<span class="text-danger"> *</span></small></label>
                <div class="col-sm-9">
                   <input type="password" class="form-control" required="" name="user_Password" id="userPassword">                
                </div>
            </div>
            <div class="form-group">
                <label for="group_name" class="col-sm-3 control-label"><small>User Type<span class="text-danger"> *</span></small></label>
                <div class="col-sm-9">
                   <select class="form-control" name="user_Type">
                   	<option value="E">Employee</option>
                   	<option value="M">Manager</option>
                   	<option value="A">Admin</option>
                   </select>               
                </div>
            </div>
                </div><!--End of box-body-->
               </div><!--End of box-primary-->
            </div><!--End of panel body-->
	   		</div><!--End of panel-->
           </div><!--End of col-sm-6-->
        
        
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
	                   <input type="text" class="form-control" required="" placeholder="39283" name="user_UserCode" id="userUserCode" onkeyup="checkUserCode()">
	                   <div id="user_UserCodeCheck"></div>
	                </div>
	            </div>
	       		<div class="form-group">
                <label for="group_name" class="col-sm-3 control-label"><small>Group</small></label>
                <div class="col-sm-9">
                <select name="group_id" id="groupID" class="form-control">
					<?php 
					$groupList=getActiveGroup();
					foreach ($groupList as $key => $groupArray) 
					{
						?>
						<option value="<?= $groupArray['group_id']; ?>"><?= $groupArray['group_Name']; ?></option>
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
						$deptList=getActiveDepartments();
						foreach ($deptList as $key => $deptArray) 
						{
							?>
							<option value="<?= $deptArray['dept_id']; ?>"><?= $deptArray['dept_Name']; ?></option>
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
						<input type="date" name="user_JoiningDateTime" id="userJoiningDateTime" placeholder="yyyy-mm-dd" class="form-control" value="">                
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
	            
	            </div><!--End of box-body-->
	        </div><!--End of box-danger-->
        </div><!--End of panel body-->
	    </div><!--End of panel-->

	<div class="panel panel-warning">
	      <div class="panel-heading">
		      	<div class="box-header">
	                  <h6 class="box-title"><i class="fa fa-plane"></i>&nbsp;Designation &amp; Leave Assignment</h6>
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
				                 <select name="desig_id" id="desigId" class="form-control" required="">
				                 	<option value="">-- Select One --</option>
									<?php 
									$desigList=getActiveDesignation();
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
		                <?php 
		                	$docList=getActiveDocuments();
		                	foreach ($docList as $key => $docArray) 
		                	{
		                		?>
			                	<div class="form-group">
			                        <label for="group_name" class="col-sm-3 control-label"><small><?=$docArray['doc_Name']; ?></small></label>
			                        <div class="col-sm-9">
			                           <input type="file" name="docs[<?= $docArray['doc_id'];?>]" class="form-control">                 
			                        </div>
			                    </div>
		                		<?php
		                	}
		                ?>
		                </div>    

		        </div><!--End of box-danger-->
		</div><!--End of panel body-->
	    </div><!--End of panel-->
        </div><!--End of col-sm-6-->
    	<div class="form-group text-left">
            <div class="col-sm-offset-2 col-sm-6">
                <input type="hidden" name="user_id" value="0" id="userId">
        		             
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
<!--End of modal-->
<!-- Modal -->
<div class="modal fade" id="viewEmployeeModal" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content" id="viewEmployeeModalContent">

      </div>
    </div>
</div>
<!--End of modal-->
<!-- Modal -->
<div class="modal fade" id="viewEmployeeModal" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content" id="viewEmployeeModalContent">

      </div>
    </div>
</div>
<!--End of modal-->
<!-- Modal -->
<div class="modal fade" id="editEmployeeModal" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content" id="editEmployeeShow">

      </div>
    </div>
</div>
<!--End of modal-->


<div class="modal fade" id="addNewEmployeeJobModal" role="dialog" data-backdrop="static">
    <div class="modal-dialog modal-lg">
      <div class="modal-content" id="addNewEmployeeJob">
      		<div class="modal-body">
      			<div class="panel">
      				<div class="panel-body">
					    <div class="box box-danger">
				                
				        	<div class="box-body">
				            
				       		<div class="form-group row">
				                <label for="group_name" class="col-sm-3 control-label"><small>Group</small></label>
				                <div class="col-sm-9">
				                <select name="group_id" id="groupID" class="form-control">
									<?php 
									$groupList=getActiveGroup();
									foreach ($groupList as $key => $groupArray) 
									{
										?>
										<option value="<?= $groupArray['group_id']; ?>"><?= $groupArray['group_Name']; ?></option>
										<?php
									}
									?>
								</select>                
							</div>
			            </div>

				            <div class="form-group row">
				                <label for="group_name" class="col-sm-3 control-label"><small>Department</small></label>
				                <div class="col-sm-9">
				                <select name="dept_id" id="deptId" class="form-control">
									<?php 
									$deptList=getActiveDepartments();
									foreach ($deptList as $key => $deptArray) 
									{
										?>
										<option value="<?= $deptArray['dept_id']; ?>"><?= $deptArray['dept_Name']; ?></option>
										<?php
									}
									?>
								</select>                
								</div>
				            </div>
				            
				            <div class="form-group row">
				                <label for="group_name" class="col-sm-3 control-label"><small>Contract Type</small></label>
			                   	<div class="col-sm-9">
				                   	<select class="form-control" name="contract_type" id="contractType">
				                   		<option value="P">Permanant</option>
				                   		<option value="C">Contract</option>
				                   	</select>      
			                	</div>
				            </div>
				            <div class="form-group row" id="contractDurationDiv">
				                <label for="group_name" class="col-sm-3 control-label"><small>Contract Duration</small></label>
			                   	<div class="col-sm-9">
				                   	<select class="form-control" name="contract_duration" id="contractDuration">
				                   		<option value="1">1 Year</option>
				                   		<option value="2">2 Years</option>
				                   	</select>      
			                	</div>
				            </div>
				           
				            
				            
				            </div><!--End of box-body-->
				        </div><!--End of box-danger-->
			        </div><!--End of panel body-->
      			</div>
      		</div>
      		<div  class="modal-footer">
      			<button type="submit" class="btn btn-success">
      				Save
      			</button>
      			<button class="btn btn-default" data-dismiss="modal" >
      				Close
      			</button>
      		</div>
      </div>
    </div>
</div>
<!--End of modal-->

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