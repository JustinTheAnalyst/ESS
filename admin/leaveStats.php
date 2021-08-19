<?php 
include('inc/PHP/functions.php');
//initilize the page
require_once ("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once ("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

 YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
 E.G. $page_title = "Custom Title" */

$page_title = "Leave Stats";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
//$page_css[] = "your_style.css";
include ("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["Employees"]["sub"]["More Details"]["active"] = true;
include ("inc/nav.php");
?>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<div id="main" role="main">

	<?php
		//configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
		//$breadcrumbs["New Crumb"] => "http://url.com"
		$breadcrumbs["Leave"] = "";
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
				<h2>Leave Stats</h2>
			</header>

			<!-- widget div-->
			<div>		


				<!-- widget content -->
				<div class="widget-body no-padding">
	<br>
			<ul class="nav nav-tabs">
			    <li class="active"><a data-toggle="tab" href="#home">All Leaves</a></li>
			    <li><a data-toggle="tab" href="#menu1">This Year</a></li>
			    <li><a data-toggle="tab" href="#menu2">Applications</a></li>
			    <li><a data-toggle="tab" href="#menu3">Leaves</a></li>
			    <li><a data-toggle="tab" href="#menu4">Job History</a></li>
			</ul>

			<div class="tab-content">
			<?php if(isset($_GET['user_id']))
			{
			?>
			    <div id="home" class="tab-pane fade in active">
			      <?php
			     		$user_id=$_GET['user_id'];
						$userLeaves = getUserLeaves($user_id);
						$i=$thisYear=0;
						foreach ($userLeaves as $key => $leaveRow) 
						{
							if($thisYear!=$leaveRow['ul_Year']) //If current year looping the don't add th again
							{
								$i=0; //Serial Number 
								$next=1; // Moving to the next year
								$thisYear=$leaveRow['ul_Year'];
								?>
		
								<table class="table table-bordered">
									<tr class="info"><td colspan="7">Leave For the Year <?=$leaveRow['ul_Year']; ?></td></tr>
								<tr>
									<th>#</th>
									<th>Leave Type</th>
									<th>Total Leaves</th>
									<th>From Date</th>
									<th>To Date</th>
									<th>Expiry Date</th>
									<th>Status</th>
								</tr>
								<?php
							}
							$i++;
							?>
							
							<tr>
								<td><?=$i; ?></td>
								<td><?=$leaveRow['lt_Name']; ?></td>
								<td><?=$leaveRow['ul_Number'] ?></td>
								<td>
									<?php if(!empty($leaveRow['ul_FromDate'])){ echo date('Y-m-d',strtotime($leaveRow['ul_FromDate'])); } ?></td>
								<td>
								<?php if(!empty($leaveRow['ul_ToDate'])){ echo date('Y-m-d',strtotime($leaveRow['ul_ToDate'])); }?></td>
								<td>
									<?php if(!empty($leaveRow['ul_ExpiryDate'])){ echo date('Y-m-d',strtotime($leaveRow['ul_ExpiryDate'])); }?></td>
								<td><?php
									if($leaveRow['ul_Status']=='A') { echo "Active";}
									elseif($leaveRow['ul_Status']=='I') { echo "In-Active"; }
									elseif($leaveRow['ul_Status']=='E') { echo "Expired"; }
								?></td>
							</tr>
							

							<?php
							if($next==2) //If(Year has been changed e.g 2017 to 2018 then close the table)
							{
								$next=1;
								?>
								</table>
								<?php
							}
						}
						?>
							
						</table>
			    </div>
			    <div id="menu1" class="tab-pane fade">
			    	<table class="table table-bordered">
							<tr style="background: #03a9f4;color: white">
								<th>Type</th>
								<th>Total</th>
								<th>Rejected</th>
								<th>Pending</th>
								<th>Approved</th>
								<th>Balance</th>
							</tr>
							<?php 
							$userLeaveStat = mng_getLeavesStats($user_id);
							foreach ($userLeaveStat as $key => $ulRow) 
							{
								$balance =$ulRow['ul_Number'] - ($ulRow['pending']+$ulRow['approved'])
								?>
								<?php if($ulRow['lt_id']==1): ?>
								<tr>
									<td><?= $ulRow['lt_Name'];?></td>
									<td><?= $ulRow['ul_Number'];?></td>
									<td><?= $ulRow['rejected'];?></td>
									<td><?= $ulRow['pending'];?></td>
									<td><?= $ulRow['approved'];?></td>									
									<td style="color: green;font-size: 16px"><?= $ulRow['ul_RemainingNumber'];?></td>									
								</tr>
								<?php else: ?>
								<tr>
									<td><?= $ulRow['lt_Name'];?></td>
									<td><?= $ulRow['ul_Number'];?></td>
									<td><?= $ulRow['rejected'];?></td>
									<td><?= $ulRow['pending'];?></td>
									<td><?= $ulRow['approved'];?></td>									
									<td style="color: green;font-size: 16px"><?= $ulRow['ul_RemainingNumber'];?></td>				
								</tr>
								<?php endif; ?>
								<?php
							}
							?>
							
						</table>
			    </div>
			    <div id="menu2" class="tab-pane fade">
			      	<table id="datatable_fixed_column" class="table table-striped table-bordered" width="100%">

			      	<thead>
				         <tr>
				           		<th>Reference#</th>
			                    <th>From</th>
			                    <th>To</th>
			                    <th>Leave Days</th>
			                    <th data-hide="phone">Applied On</th>
			                    <th data-hide="phone,tablet">Status</th>
			                    <th>Details</th>
			                    
				            </tr>
				        </thead>

				        <tbody>
				        <?php 
				        //$user_id=$_SESSION['user_id'];
				        $leaveHistoryArray = getUserLeaveHistory($user_id);
				       
				        $srNo = 0;
				        foreach ($leaveHistoryArray as $key => $leaveRow) 
				        {
				        	?>
				            <tr id="row<?php echo $leaveRow['lb_id']  ?>">
				            	<td><?= $leaveRow['lb_id']; //++$srNo;   ?></td>
				                
				                <td><?=$leaveRow['lb_FromDate']; ?></td>
				                <td><?=$leaveRow['lb_ToDate']; ?></td>
				                <td><?=$leaveRow['lb_Days'] ?></td>
				                
				                <td><?=$leaveRow['lb_DateTime'] ?></td>

				                <td>
				                	<?php if($leaveRow['lb_Status']=='P'): ?>
				                		<label class="label label-default">Pending</label>
				                	<?php elseif($leaveRow['lb_Status']=='A'): ?>
				                		<label class="label label-success">Approved</label>
				                	<?php elseif($leaveRow['lb_Status']=='R'): ?>
				                		<label class="label label-warning">Rejected</label>
				                	<?php elseif($leaveRow['lb_Status']=='C'): ?>
				                		<label class="label label-danger">Cancelled</label>
				                	<?php endif; ?>
				                </td>	
				               
				              <td>
				                <a href="javascript:" onclick="details('<?php echo $leaveRow['lt_Name'] ?>', '<?php echo $leaveRow['lr_Title'] ?>', '<?php echo $leaveRow['lb_Comment'] ?>', '<?php echo $leaveRow['lb_Doc'] ?>', '<?php echo $leaveRow['lb_Reason'] ?>', '<?php echo $leaveRow['lb_ReasonDoc'] ?>')" class="btn btn-primary btn-xs edit" data-toggle="modal" data-target="#LeaveBatchDetailModal"  ><i class="fa fa-eye"></i> Details</a>
				               
				                </td>  
				                       
				            </tr>
				            <?php
				        }
				        ?>

				        </tbody>
			      	<?php  /*  ?>
				        <thead>
						
				            <tr>
			                    <th data-class="expand">Applied On</th>
			                    <th>Leave Date</th>
			                    <th data-hide="phone">Leave Rason</th>
			                    <th data-hide="phone">Leave Type</th>
			                    <th data-hide="phone,tablet">Status</th>
			                    
				            </tr>
				        </thead>

				        <tbody>
				        <?php 
				  
				        $leaveHistoryArray = getUserLeaveHistory($user_id);
				        foreach ($leaveHistoryArray as $key => $leaveRow) 
				        {
				        	?>
				            <tr>
				            	<td><?=$leaveRow['la_DateTime'] ?></td>
				                <td><?=date('d-m-Y',strtotime($leaveRow['la_Date'])); ?></td>
				                <td><?=$leaveRow['lr_Title']; ?></td>
				                <td><?=$leaveRow['lt_Name']; ?></td>
				                <td>
				                	<?php if($leaveRow['la_Status']=='P'): ?>
				                		<label class="label label-default">Pending</label>
				                	<?php elseif($leaveRow['la_Status']=='A'): ?>
				                		<label class="label label-success">Approved</label>
				                	<?php elseif($leaveRow['la_Status']=='R'): ?>
				                		<label class="label label-warning">Rejected</label>
				                	<?php endif; ?>
				                </td>				               
				            </tr>
				            <?php
				        }
				        ?>

				        </tbody>
				        <?php */ ?>
				
					</table>
			    </div>
			    <div id="menu3" class="tab-pane fade">
			    	<table class="table table-bordered">
			    		<tr style="background: #03a9f4;color: white">
			    			<td>#</td>
			    			<td>Leave Type</td>
			    			<td>Date</td>
			    			<td>Leave</td>
			    			<td>Balance</td>
			    			<td>Expired On</td>
			    		</tr>
			    		<?php 
			    		$allLeaves = getAllUserLeaves($user_id);
			    		$allAnnualLeaves = allAnnualLeaves($user_id);

			    		$count=$balance=0;
			    		if($allAnnualLeaves)
			    		{
			    			foreach ($allAnnualLeaves as $key => $leave) 
				    		{
				    			$count++;
				    			if($leave['ul_Status']=='A')
				    			{
				    				$balance+=$leave['ul_RemainingNumber'];
				    			}
				    			?>
			    			<tr>
			    				<td><?php echo $count; ?></td>
			    				<td><?php echo $leave['lt_Name']; ?></td>
			    				<td><?php echo $leave['ul_FromDate']." - ".$leave['ul_ToDate']; ?></td>
			    				<td><?php echo $leave['ul_Number'];?></td>
			    				<td><?php echo $leave['ul_RemainingNumber']; ?></td>
			    				<td><?php echo $leave['ul_ExpiryDate']; ?></td>
			    			</tr>
				    			<?php
				    		}
				    		?>
				    		<tr>
				    			<td colspan="4" style="text-align: right;"><b>Total</b></td>
				    			<td colspan="2"><?php echo $balance; ?></td>
				    		</tr>
				    		<?php
			    		}
			    		
			    		foreach ($allLeaves as $key => $leave) 
			    		{
			    			$count++;
			    			$balance = $leave['ul_Number']-$leave['utilized'];
			    			?>
		    			<tr>
		    				<td><?php echo $count; ?></td>
		    				<td><?php echo $leave['lt_Name']; ?></td>
		    				<td><?php echo $leave['ul_FromDate']." - ".$leave['ul_ToDate']; ?></td>
		    				<td><?php echo $leave['ul_Number'];?></td>
		    				<td><?php echo  $leave['ul_RemainingNumber']; ?></td>
		    				<td><?php echo $leave['ul_ExpiryDate']; ?></td>
		    			</tr>
			    			<?php
			    		}
			    		?>
			    	</table>
			    </div>
			    <div id="menu4" class="tab-pane fade">
			    	<table class="table table-bordered">
						<tr style="background: #03a9f4;color: white">
							<th>#</th>
							<th>From</th>
							<th>To</th>
							<th>Position</th>
						</tr>
						<?php 
						$historyArray = getUserJobHistory($user_id);
						$count=0;
						foreach ($historyArray as $key => $job) 
						{
							$count++;
							if($job['uc_Status']=='A'){
								$uc_ExitDate = "Present";
							}
							else{
								$uc_ExitDate=date('d-m-Y',strtotime($job['uc_ExitDate']));
							}
							?>
							<tr>
								<td><?php echo $count; ?></td>
								<td><?php echo date('d-m-Y',strtotime($job['uc_JoiningDate'])); ?></td>
								<td><?php echo $uc_ExitDate; ?></td>
								<td><?php echo $job['desig_Name']; ?></td>
							</tr>
							<?php
						}
						?>
						
						<tr>
							
						</tr>
					</table>
			    </div>
			<?php 
			}
			else
			{
				echo "<div class='alert alert-info'>Invalid Request!</div>";
			}
			?>  
			</div><!--End of tab-content-->

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


<!-- Modal -->
  <div class="modal fade" id="LeaveBatchDetailModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Leave Batch Detail</h4>
        </div>
        <div class="modal-body">
          <form id="change-leave-batch-status">
          <div class="form-group">

          		<label>Leave Type: <strong id="lt_Name"></strong></label><br/>
          		<label>Leave Reason <strong id="lr_Title"></strong></label><br/>
          		<label>Leave Comments By Employee: <strong id="lb_Comment"></strong></label><br/>
          		
          		<label for="lb_Doc">Leave Document uploaded by Employee:</label><a href="" id="lb_Doc" class="btn btn-link" target="_blank"></a><br/>

          		<label>Comments by Admin/Manager: <strong id="lb_Reason"></strong></label><br/>

          		<label for="lb_Doc">Attachments upload by Admin/Manager:</label><a href="" id="lb_ReasonDoc" class="btn btn-link" target="_blank"></a><br/>


          		
          	</div>
          

          	<div class="form-group">
          		
          		
          	</div>

          		<p class="btn btn-primary form-control" onclick="changeLeaveBatchStatus()">Save</p>
          		<input type="hidden" name="lb_id" id="lb_id" value="0">
          		<input type="hidden" name="lb_FromDate" id="lb_FromDate" value="0">
          		<input type="hidden" name="lb_ToDate" id="lb_ToDate" value="0">
          		

          </form>
        </div>
        <div class="modal-footer">
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

<!-- PAGE RELATED PLUGIN(S) -->
<script src="<?php echo ASSETS_URL; ?>/js/plugin/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo ASSETS_URL; ?>/js/plugin/datatables/dataTables.colVis.min.js"></script>
<script src="<?php echo ASSETS_URL; ?>/js/plugin/datatables/dataTables.tableTools.min.js"></script>
<script src="<?php echo ASSETS_URL; ?>/js/plugin/datatables/dataTables.bootstrap.min.js"></script>
<script src="<?php echo ASSETS_URL; ?>/js/plugin/datatable-responsive/datatables.responsive.min.js"></script>

<script type="text/javascript">
	
	function details(lt_Name, lr_Title, lb_Comment, lb_Doc, lb_Reason, lb_ReasonDoc){

		$("#lt_Name").html(lt_Name);
		$("#lr_Title").html(lr_Title);
		$("#lb_Comment").html(lb_Comment);
		$("#lb_Reason").html(lb_Reason);

		if(lb_ReasonDoc!=""){

			$("#lb_ReasonDoc").attr("href", '../uploads/Leave_Reason_Docs/'+lb_ReasonDoc);
			$("#lb_ReasonDoc").html("Attachment Download");
		}
		else{

			$("#lb_ReasonDoc").html("");

		}

		if(lb_Doc!=""){

			$("#lb_Doc").attr("href", '../uploads/Leave_Docs/'+lb_Doc);
			$("#lb_Doc").html("Attachment Download");
		}
		else{

			$("#lb_Doc").html("");

		}

	}

</script>

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
    	//"bPaginate": false,
    	//"bStateSave": true // saves sort state using localStorage
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