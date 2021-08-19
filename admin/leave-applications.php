<?php 
//initilize the page
require_once ("inc/init.php");
require_once('inc/PHP/functions.php');
require_once ("inc/config.ui.php");

$page_title = "Apply Leave";

$page_css[] = "your_style.css";
include ("inc/header.php");

$page_nav["My Leave"]["sub"]["Apply Leave"]["active"] = true;
include ("inc/nav.php");
?>

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
							<h2>Application Info</h2>
						</header>
			
						<div>		
							<div class="widget-body">
								<div class="row">
									<?php $user_id=$_SESSION['user_id']; ?>
									<div class="col col-lg-6 col-sm-12 table-responsive">
										<table class="table table-striped table-hover table-bordered">
											<thead class="thead-inverse">
												<tr>
													<th style="">Type</th>
													<th style="width: 20%; text-align: center;">Remaining</th>
													<th style="width: 20%; text-align: center;">Pending</th>
													<th style="width: 20%; text-align: center;">Balance</th>
												</tr>
											</thead>
											<tbody>
											<?php 
											$userLeaveStat = emp_getLeavesStats($user_id);
											foreach ($userLeaveStat as $key => $ulRow) 
											{
												$balance = $ulRow['ul_RemainingNumber'] - $ulRow['pending'];
												$bg_color = "label-success";
												$bg_color2 = "label-success";
													
												if ($ulRow['ul_RemainingNumber'] <= 0)
												{
													$bg_color = "label-danger";
												
													
												}
												
												if ($balance == 0)
												{
													$bg_color2 = "label-warning";
												}
												elseif ($balance < 0)
												{
													$bg_color2 = "label-danger";
												}
												else 
												{
													$bg_color2 = "label-success";
												}
												
												if($ulRow['lt_id']==1)
												{ 
												?>
												<tr>
													<td><?php echo $ulRow['lt_Name']; ?></td>	
													<td>
														<span class="label <?php echo $bg_color; ?> col-xs-6" style="font-size: 14px; width: 100%;text-align: center;">
														<?php echo $ulRow['ul_RemainingNumber']; ?>
														</span>
													</td>	
													<td>
														<span class="label label-success col-xs-6" style="font-size: 14px; width: 100%;text-align: center;">
														<?php echo $ulRow['pending']; ?>
														</span>
													</td>						
													<td>
														<span class="label <?php echo $bg_color2; ?> col-xs-6" style="font-size: 14px; width: 100%;text-align: center;">
														<?php echo $balance; ?>
														</span>
													</td>									
												</tr>
												<?php 
												}
												else
												{
												?>
												<tr>
													<td><?php echo $ulRow['lt_Name']; ?></td>	
													<td>
														<span class="label <?php echo $bg_color; ?> col-xs-6" style="font-size: 14px; width: 100%;text-align: center;">
														<?php echo $ulRow['ul_RemainingNumber']; ?>
														</span>
													</td>	
													<td>
														<span class="label label-success col-xs-6" style="font-size: 14px; width: 100%;text-align: center;">
														<?php echo $ulRow['pending']; ?>
														</span>
													</td>						
													<td>
														<span class="label <?php echo $bg_color2; ?> col-xs-6" style="font-size: 14px; width: 100%;text-align: center;">
														<?php echo $balance; ?>
														</span>
													</td>									
												</tr>
											<?php 
												}
											}
											?>
											</tbody>
										</table>
									</div>
									
									<div class="col col-lg-6 col-sm-12">
										<form id="leave-application-form" class="smart-form" method="post" action="" enctype="multipart/form-data" >
											<fieldset style="padding: 0px;">
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
															<option value="<?php echo $ltRow['lt_id'] ?>">
															<?php echo $ltRow['lt_Name']; ?>
															</option>
														<?php
														}
														?>
														</select>
													</div>
												</div>
												<div class="row" style="margin-bottom: 5px">
													<div class="col col-lg-3">
														From Date
													</div>
													<div class="col col-lg-9">
														<div class="input-group">
															<input type="text" name="la_FromDate" id="la_FromDate"  placeholder="Select a date" class="form-control" data-dateformat="yy-mm-dd" required>
															<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
														</div>
													</div>
												</div>
												<div class="row" style="margin-bottom: 5px">
													<div class="col col-lg-3">
														To Date
													</div>
													<div class="col col-lg-9">
														<div class="input-group">
															<input type="text" name="la_ToDate" id="la_ToDate" placeholder="Select a date" class="form-control" data-dateformat="yy-mm-dd" required>
															<span class="input-group-addon"><i class="fa fa-calendar" ></i></span>
														</div>
													</div>
												</div>
												<div class="row" style="margin-bottom: 5px">
													<div class="col col-lg-3">Reason Type</div>
													<div class="col col-lg-9">
														<select class="select2" name="lr_id">
															<?php 
															$lrArray = getActiveLeaveReason();
															foreach ($lrArray as $key => $lrRow) 
															{
															?>
																<option value="<?php echo $lrRow['lr_id'];?>">
																<?php echo $lrRow['lr_Title']; ?>
																</option>
															<?php
															}
															?>
															
														</select>
													</div>
												</div>
												<div class="row" style="margin-bottom: 5px">
													<div class="col col-lg-3">Reason</div>
													<div class="col col-lg-9">
														<textarea class="form-control" name="la_Comment" rows="5"></textarea>
													</div>
												</div>
												<div class="row" style="margin-bottom: 5px">
													<div class="col col-lg-3">Attachment</div>
													<div class="col col-lg-9">
														<input type="file" name="lb_Doc" />
													</div>
												</div>
											</fieldset>
											<footer>
												<input type="submit" class="btn btn-primary" name="submit"  value="Save"/>
											</footer>
										</form>
									</div>
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
$(function(){
    $("#la_FromDate").datepicker({ 
        dateFormat: 'yy-mm-dd',
        prevText : '<i class="fa fa-chevron-left"></i>',
		nextText : '<i class="fa fa-chevron-right"></i>',
        changeMonth: true,
        minDate: new Date(),
        maxDate: '+1y',
        onSelect: function(date){

            var selectedDate = new Date(date);
            var msecsInADay = 86400000;
            //var endDate = new Date(selectedDate.getTime() + msecsInADay);
            var endDate = new Date(selectedDate.getTime());
			var limitedTo = '+1y';
			
            $("#la_ToDate").datepicker( "option", "minDate", endDate );
            $("#la_ToDate").datepicker( "option", "maxDate", limitedTo );
            $("#la_FromDate").datepicker( "option", "maxDate", limitedTo );
        }
    });

    $("#la_ToDate").datepicker({ 
        dateFormat: 'yy-mm-dd',
        prevText : '<i class="fa fa-chevron-left"></i>',
		nextText : '<i class="fa fa-chevron-right"></i>',
        changeMonth: true
    });
});
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
<script type="text/javascript" src="inc/js/custom.js"></script>

<?php
//include footer
include ("inc/google-analytics.php");
?>