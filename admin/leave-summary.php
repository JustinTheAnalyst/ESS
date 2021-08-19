<?php 
include('inc/PHP/functions.php');
//initilize the page
require_once ("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once ("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

 YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
 E.G. $page_title = "Custom Title" */

$page_title = "Leave Summary";

$_GET['user_id'] = $_SESSION['user_id'];
/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
//$page_css[] = "your_style.css";
include ("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["My Leave"]["sub"]["Leave Summary"]["active"] = true;
include ("inc/nav.php");
?>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<div id="main" role="main">
<?php
include("inc/ribbon.php");
?>

	<!-- MAIN CONTENT -->
	<div id="content">
		<section id="widget-grid" class="">
			<div class="row">
				<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<div class="jarviswidget jarviswidget-color-purity" id="wid-id-1" data-widget-editbutton="false">
						<header>				
							<h2>Leave History Details</h2>
						</header>
			
						<div>		
							<div class="widget-body">
								<ul class="nav nav-tabs" style="padding-bottom:1px;">
									<li class="active"><a data-toggle="tab" href="#home">Active Leaves</a></li>
								    <!-- <li><a data-toggle="tab" href="#menu1">This Year</a></li> -->
								    <li><a data-toggle="tab" href="#menu3">All Leaves</a></li>
								</ul>
			
								<div class="tab-content" >
								<?php 
								if(isset($_GET['user_id']))
								{
									$user_id=$_GET['user_id'];
								?>
									<div id="home" class="tab-pane fadein active">
								    	<table class="table table-bordered">
								    		<tr style="background: #03a9f4;color: white;">
								    			<td>#</td>
								    			<td>Leave Type</td>
								    			<td>Given</td>
								    			<td>Expired On</td>
								    			<td style="width:20%;">Leave Balance</td>
								    		</tr>
								    		<?php 
								    		$allAnnualLeaves = allAnnualLeaves($user_id);
					
								    		$count=$balance=0;
								    		if($allAnnualLeaves)
								    		{
								    			$total = 0;
								    			
								    			foreach ($allAnnualLeaves as $key => $leave) 
									    		{
									    			$count++;
									    			
									    			$ul_id = $leave['ul_id'];
									    			$year = $leave['ul_Year'];
									    			?>
									    			<tr>
									    				<td><?php echo $count; ?></td>
									    				<td><?php echo $leave['lt_Name'] ." (".$year.")"; ?></td>
									    				<td><?php echo $leave['ul_Number']; ?></td>
									    				<td><?php echo $leave['ul_ExpiryDate']; ?></td>
									    				<td><?php echo $leave['ul_RemainingNumber'];?></td>
									    			</tr>
									    			<?php
									    			$total = $total + $leave['ul_RemainingNumber'];
									    		}
									    		?>
									    		<tr>
									    			<td colspan="4" style="text-align: right;"><b>Total</b></td>
									    			<td colspan="1" class="label-default"><span style="font-size:14px; font-weight:bold;"><?php echo $total; ?></span></td>
									    		</tr>
									    		<?php
								    		}
								    		
								    		$allLeaves = getAllUserLeaves($user_id);
								    		foreach ($allLeaves as $key => $leave) 
								    		{
								    			$count++;
								    		?>
								    			<tr>
								    				<td><?php echo $count; ?></td>
								    				<td><?php echo $leave['lt_Name']; ?></td>
								    				<td><?php echo $leave['ul_Number']; ?></td>
								    				<td><?php echo $leave['ul_ExpiryDate']; ?></td>
								    				<td><?php echo $leave['ul_RemainingNumber']; ?></td>
								    			</tr>
								    		<?php
								    		}
								    		?>
								    	</table>
								    </div>
								    
								    <div id="menu3" class="tab-pane fade">
								    <?php
									$userLeaves = getUserLeaves($user_id);
									$i=$thisYear=0;
									
									foreach ($userLeaves as $key => $leaveRow) 
									{
										//If current year looping the don't add th again
										if($thisYear!=$leaveRow['ul_Year']) 
										{
											$i=0; //Serial Number 
											$next=1; // Moving to the next year
											$thisYear=$leaveRow['ul_Year'];
									?>
										<table class="table table-bordered">
											<tr class="info">
												<td colspan="7">
													<strong>Leaves For Year <?php echo $leaveRow['ul_Year']; ?></strong>
												</td>
											</tr>
											<tr style="background: #03a9f4;color: white;">
												<th>#</th>
												<th>Leave Type</th>
												<th>Given</th>
												<th>Expiry Date</th>
												<th>Status</th>
											</tr>
										<?php
										}
										
										$i++;
										?>
											<tr>
												<td><?php echo $i; ?></td>
												<td><?php echo $leaveRow['lt_Name']; ?></td>
												<td><?php echo $leaveRow['ul_Number']; ?></td>
												<td style="width:10%;">
													<?php 
													if(!empty($leaveRow['ul_ExpiryDate']))
													{ 
														echo date('Y-m-d',strtotime($leaveRow['ul_ExpiryDate'])); 
													}
													?>
												</td>
												<td style="width:15%;">
													<?php
													if($leaveRow['ul_Status']=='A') 
													{ 
														echo "<span class='center-block padding-5 label label-success'>Active</span>";
													}
													elseif($leaveRow['ul_Status']=='I') 
													{ 
														echo "<span class='center-block padding-5 label label-danger'>In-Active</span>"; 
													}
													elseif($leaveRow['ul_Status']=='E') 
													{ 
														echo "<span class='center-block padding-5 label label-default'>Expired</span>"; 
													}
													?>
												</td>
											</tr>
										<?php
										//If(Year has been changed e.g 2017 to 2018 then close the table)
										if($next==2) 
										{
											$next=1;
											echo "</table>";
										}
									}
									?>
										</table>
								    </div>
						    
						    		<!-- 
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
												$balance =$ulRow['ul_Number'] - ($ulRow['pending']+$ulRow['approved']);
												
												if($ulRow['lt_id']==1)
												{
											?>
													<tr>
														<td><?php echo $ulRow['lt_Name'];?></td>
														<td><?php echo $ulRow['ul_Number'];?></td>
														<td><?php echo $ulRow['rejected'];?></td>
														<td><?php echo $ulRow['pending'];?></td>
														<td><?php echo $ulRow['approved'];?></td>									
														<td style="color: green;font-size: 16px">
															<?php echo $ulRow['ul_RemainingNumber'];?>
														</td>									
													</tr>
												<?php 
												}
												else
												{
												?>
													<tr>
														<td><?php echo $ulRow['lt_Name'];?></td>
														<td><?php echo $ulRow['ul_Number'];?></td>
														<td><?php echo $ulRow['rejected'];?></td>
														<td><?php echo $ulRow['pending'];?></td>
														<td><?php echo $ulRow['approved'];?></td>									
														<td style="color: green; font-size: 16px;">
															<?php echo $ulRow['ul_RemainingNumber'];?>
														</td>				
													</tr>
											<?php 
												}
											}
											?>
										</table>
						    		</div>
						    		-->
								<?php 
								}
								else
								{
									echo "<div class='alert alert-info'>No Data!</div>";
								}
								?>  
								</div>
							</div>
						</div>
					</div>
				</article>
			</div>
		</section>
	</div>
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