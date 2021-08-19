<?php 
include('inc/PHP/functions.php');
//initilize the page
require_once ("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once ("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

 YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
 E.G. $page_title = "Custom Title" */

$page_title = "Apply Leave";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include ("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["My Leave"]['sub']["Apply Leave"]["active"] = true;
include ("inc/nav.php");
?>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<div id="main" role="main">

	<?php
		//configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
		//$breadcrumbs["New Crumb"] => "http://url.com"
		$breadcrumbs["My Leave"] = "";
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
				<h2>Leave Application</h2>
			</header>

			<!-- widget div-->
			<div>		
				<!-- widget content -->
				<div class="widget-body no-padding">
				<br>
				<div class="row">
					<?php $user_id=$_SESSION['user_id']; ?>
					<div class="col col-lg-6 col-sm-12" style="padding-left: 30px;     border-right: 1px solid #EEE;">
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
							$userLeaveStat = emp_getLeavesStats($user_id);
							foreach ($userLeaveStat as $key => $ulRow) 
							{
								/*$balance =$ulRow['ul_RemainingNumber'] - ($ulRow['pending']+$ulRow['approved'])*/
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
									<td style="color: green;font-size: 16px"><?= $ulRow['ul_RemainingNumber']?></td>				
								</tr>
								<?php endif; ?>
								<?php
							}
							?>
							
						</table>
					</div><!--End of col-lg-4-->
					<div class="col col-lg-6 col-sm-12">
						<form  id="leave-application-form" class="smart-form" method="post" action="" enctype="multipart/form-data" onsubmit="createLeaveApplication();">
						<fieldset style="    padding: 3px 14px 5px;">
							<div class="row" style="margin-bottom: 5px">
								<div class="col col-lg-3">
									Leave Type
								</div>
								<div class="col col-lg-9">
									<select class="select2" name="lt_id" id="lt_id">
									<?php 
									$user_id=$_SESSION['user_id'];
									$ltArray = getActiveLeaveType($user_id);
									foreach ($ltArray as $key => $ltRow) 
									{
										?>
										<option value="<?=$ltRow['lt_id'] ?>"><?=$ltRow['lt_Name']; ?></option>
										<?php
									}
									?>
									</select>
								</div>
							</div><!--End of row-->
							<div class="row" style="margin-bottom: 5px">
								<div class="col col-lg-3">
									From Date
								</div>
								<div class="col col-lg-9">
									<div class="input-group">
										<input type="text" name="la_FromDate" placeholder="Select a date" class="form-control datepicker" data-dateformat="yy-mm-dd" required="">
										<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
									</div>
								</div>
							</div><!--End of row-->
							<div class="row" style="margin-bottom: 5px">
								<div class="col col-lg-3">
									To Date
								</div>
								<div class="col col-lg-9">
									<div class="input-group">
										<input type="text" name="la_ToDate" placeholder="Select a date" class="form-control datepicker" data-dateformat="yy-mm-dd" required="">
										<span class="input-group-addon"><i class="fa fa-calendar" ></i></span>
									</div>
								</div>
							</div><!--End of row-->
							<div class="row" style="margin-bottom: 5px">
								<div class="col col-lg-3">
									Leave Reason
								</div>
								<div class="col col-lg-9">
									<select class="select2" name="lr_id">
										<?php 
										$lrArray = getActiveLeaveReason();
										foreach ($lrArray as $key => $lrRow) 
										{
											?>
											<option value="<?=$lrRow['lr_id'];?>"><?=$lrRow['lr_Title']; ?></option>
											<?php
										}
										?>
										
									</select>
								</div>
							</div><!--End of row-->
							<div class="row" style="margin-bottom: 5px">
								<div class="col col-lg-3">
									Leave Document
								</div>
								<div class="col col-lg-9">
									<input type="file" name="lb_Doc" >
									
								</div>
							</div><!--End of row-->
							<div class="row" style="margin-bottom: 5px">
								<div class="col col-lg-3">
									Leave Comments
								</div>
								<div class="col col-lg-9">
									<textarea class="form-control" name="la_Comment" rows="5"></textarea>
									
								</div>
							</div><!--End of row-->

						</fieldset>
						<footer>
							<button type="submit" class="btn btn-primary" name="submit"  >Save</button>
						</footer>
						</form>
					</div><!--End of col-lg-8-->
				</div><!--End of main row-->
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
<script type="text/javascript" src="inc/js/custom2.js"></script>

<?php
//include footer
include ("inc/google-analytics.php");
?>