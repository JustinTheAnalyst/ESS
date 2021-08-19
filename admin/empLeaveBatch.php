<?php 
//initilize the page
require_once ("inc/init.php");
include('inc/PHP/functions.php');
//require UI configuration (nav, ribbon, etc.)
require_once ("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

 YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
 E.G. $page_title = "Custom Title" */

$page_title = "Employee Leaves Batch";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
//$page_css[] = "your_style.css";
include ("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["Employee Leave Batch"]["active"] = true;
include ("inc/nav.php");
?>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<div id="main" role="main">

	<?php
		//configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
		//$breadcrumbs["New Crumb"] => "http://url.com"
		$breadcrumbs["Leaves"] = "";
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

		<!-- Widget ID (each widget will need unique ID)-->
		<div class="jarviswidget jarviswidget-color-purity" id="wid-id-1" data-widget-editbutton="false">
			<header>				
				<h2>Employee Leaves Batchwise</h2>
			</header>

			<!-- widget div-->
			<div>		


				<!-- widget content -->
				<div class="widget-body no-padding">
	<br>

			<div class="tab-content" >
				<table id="dt_basic" class="table table-striped table-bordered" width="100%">

				        <thead>
							<!-- <tr>
								<th></th>
								<th class="hasinput" style="width:16%">
									<input type="text" class="form-control" placeholder="First Last Name" />
								</th>
								<th class="hasinput" style="width:10%">
										<input type="text" class="form-control" placeholder="Leave Type" />
								</th>
								
									
								<th class="hasinput icon-addon" >
									<input id="dateselect_filter" type="text" placeholder="From Date" class="form-control datepicker" data-dateformat="yy-mm-dd">
									<label for="dateselect_filter" class="glyphicon glyphicon-calendar no-margin padding-top-15" rel="tooltip" title="" data-original-title="Filter Date"></label>
								</th>

								<th class="hasinput icon-addon">
									<input id="dateselect_filter1" type="text" placeholder="To Date" class="form-control datepicker" data-dateformat="yy-mm-dd">
									<label for="dateselect_filter1" class="glyphicon glyphicon-calendar no-margin padding-top-15" rel="tooltip" title="" data-original-title="Filter Date"></label>
								</th>

								<th class="hasinput" style="width:16%">
									<input type="text" class="form-control" placeholder="Leave Days" />
								</th>

								<th class="hasinput" style="width:16%">
									<input type="text" class="form-control" placeholder="Leave Reason" />
								</th>

								<th class="hasinput" style="width:10%">
									<input type="text" class="form-control" placeholder="Applied On" />
								</th>
								<th class="hasinput" style="width:10%">
									<input type="text" class="form-control" placeholder="Status" />
								</th>
								
								
								<th>Attachment</th>

								<th class="hasinput" style="width:7%">
									Action
								</th>
							</tr> -->
				            <tr>
				            	<th>Reference#</th>

			                    <th>Name</th>
			                    <th data-hide="phone">Leave Type</th>
			                   

			                    <th data-hide="phone,tablet">From Date</th>

			                     <th data-hide="phone,tablet">To Date</th>
			                      <th data-hide="phone">Leave Days</th>
			                      <th data-hide="phone">Leave Reason</th>
			                      <th>Applied Date</th>
			                    	<th data-hide="phone,tablet">Status</th>
			                    
			                   
			                    <th>Attachment</th>
			                    
			                    <th>Action</th>
				            </tr>
				        </thead>

				        <tbody>
				        <?php 
				        $leaveArray = getAllBatchLeaves();
				        foreach ($leaveArray as $key => $leaveRow) 
				        {
						if($leaveRow['lb_Status']=='A'){ $status="<span class='label label-success'>Approved</span>"; }
						elseif($leaveRow['lb_Status']=='P'){ $status="<span class='label label-info'>Pending</span>"; }
						elseif($leaveRow['lb_Status']=='R'){ $status="<span class='label label-warning'>Rejected</span>"; }
						elseif($leaveRow['lb_Status']=='C'){ $status="<span class='label label-danger'>Cancelled</span>"; }

						    
				        	?>
				        	<tr id="row<?= $leaveRow['lb_id'];?>">
				        	<td><?= $leaveRow['lb_id'] ?></td>
				        		
				                
				                <td><?= $leaveRow['user_FirstName']." ".$leaveRow['user_LastName'];?></td>
				                <td><?= $leaveRow['lt_Name']; ?></td>
				                 <td><?=$leaveRow['lb_FromDate']; ?></td>
				                 <td><?=$leaveRow['lb_ToDate']; ?></td>

				                 <td><?=$leaveRow['lb_Days']; ?></td>
				                <td><?= $leaveRow['lr_Title']; ?></td>

				                <td><?=$leaveRow['lb_DateTime']; ?></td>
				                
				               
				                <td class="status"><?= $status; ?></td>
				                
				                <td>
				                <?php if($leaveRow['lb_Doc']!=""){ ?>
				                <a href="../uploads/Leave_Docs/<?php echo $leaveRow['lb_Doc']; ?>" target="_blank">Attachment</a></td>
				                <?php } ?>
				                <td>
				                <?php if($leaveRow['lb_Status']!='R' && $leaveRow['lb_Status'] != 'C'){ ?>
				                	<a href="javascript:" class="btn btn-primary btn-xs edit" data-toggle="modal" data-target="#changeLeaveStatusModal" onclick="set_id(<?=$leaveRow['lb_id']; ?>, '<?php echo $leaveRow['lb_Status'] ?>', '<?=$leaveRow['lb_FromDate']; ?>' , '<?=$leaveRow['lb_ToDate']; ?>' , '<?= $leaveRow['lt_Name']; ?>', <?= $leaveRow['lb_Days']; ?>, '<?= $leaveRow['lb_ReasonDoc'] ?>')" >Edit</a>
			                   		<!-- <a href="" class="btn btn-danger btn-xs delete">Delete</a> -->
			                   		<?php } ?>
				                </td>
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
<script type="text/javascript">
	function set_id(lb_id, lb_Status, lb_FromDate, lb_ToDate , lt_Name, lb_Days, lb_ReasonDoc){
		$("#lb_id").val(lb_id);
		$("#lb_ToDate").val(lb_ToDate);
		$("#lb_FromDate").val(lb_FromDate);

		$("#leaveType").html(lt_Name);
		$("#daysTaken").html(lb_Days);
		$("#LeaveFrom").html(lb_FromDate);
		$("#LeaveTo").html(lb_ToDate);
		if(lb_ReasonDoc!=""){

			$("#lb_ReasonDocLink").attr("href", '../uploads/Leave_Reason_Docs/'+lb_ReasonDoc);
			$("#lb_ReasonDocLink").html("Attachment Download");
		}
		else{

			$("#lb_ReasonDocLink").html("");

		}



		$("#lb_Status").find("option").remove();

		if(lb_Status=='P'){
			$('#lb_Status').append($('<option>', {
			    value: 'A',
			    text: 'Approve'
			}));
			$('#lb_Status').append($('<option>', {
			    value: 'R',
			    text: 'Reject'
			}));
		}
		else if(lb_Status=='A'){
			$('#lb_Status').append($('<option>', {
			    value: 'C',
			    text: 'Cancel'
			}));
			
		}
	}
</script>
<!-- Modal -->
  <div class="modal fade" id="changeLeaveStatusModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Leave Application</h4>
        </div>
        <div class="modal-body">
          <form id="change-leave-batch-status">
          <div class="form-group">
          		<label>Leave Type: <strong id="leaveType"></strong></label><br/>
          		<label>No. of Day Taken: <strong id="daysTaken"></strong></label><br/>
          		<label>From: <strong id="LeaveFrom"></strong></label><br/>
          		<label>To: <strong id="LeaveTo"></strong></label>
          		
          	</div>
          	<div class="form-group">
          		<label for="lb_Status">Change Status</label>
          		<select name="lb_Status" id="lb_Status" class="form-control">
          			<option value="A">Approve</option>
          			<option value="R">Reject</option>
          			<option value="C">Cancel</option>
          		</select>
          	</div>
          	<div class="form-group">
          		<label for="lb_Reason">Comments</label>
          		<textarea name="lb_Reason" class="form-control"></textarea>
          	</div>
          	<div class="form-group">
          		<label for="lb_ReasonDoc">Attachment</label>
          		<input type="file" name="lb_ReasonDoc" class="form-control">
          	</div>

          	<div class="form-group">
          		<label for="lb_ReasonDocLink">Supporting Document:</label>
          		<a href="" id="lb_ReasonDocLink" class="btn btn-link" target="_blank"></a>
          	</div>

          		
          		<input type="hidden" name="lb_id" id="lb_id" value="0">
          		<input type="hidden" name="lb_FromDate" id="lb_FromDate" value="0">
          		<input type="hidden" name="lb_ToDate" id="lb_ToDate" value="0">
          		

          </form>
        </div>
        <div class="modal-footer">
        <p class="btn btn-primary" onclick="changeLeaveBatchStatus()">Save</p>	
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
<!--End of modal-->
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
		    'ordering':false,
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
    	'ordering':false,
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

})

</script>

<?php
//include footer
include ("inc/google-analytics.php");
?>