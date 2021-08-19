<?php
include('connection.php');
include('inc/PHP/functions.php');
 //initilize the page
require_once ("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once ("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

 YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
 E.G. $page_title = "Custom Title" */

$page_title = "Working Days";

/* ---------------- END PHP Custom Scripts ------------- */

//include header

include ("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["Configuration"]["sub"]["Days"]["active"] = true;
include ("inc/nav.php");
?>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<style type="text/css">
	input[type=time] {
    padding-left: 10px;
}
</style>
<div id="main" role="main">

	<?php
		//configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
		//$breadcrumbs["New Crumb"] => "http://url.com"
		$breadcrumbs["Configuration"] = "";
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
				<h2>Working Days</h2>
			</header>

			<!-- widget div-->
			<div>		


				<!-- widget content -->
				<div class="widget-body no-padding">
<script type="text/javascript">
$(document).ready(function(){
    $('input[type="radio"]').click(function(){
        if ($("#sameTime1").is(":checked")) 
        	{
			   /*
			   $("#mainDiv").hide();*/
			   $("#sameTimeDiv").show();
			   setTime();
			  // $("#mainDiv").find('input[type="time"]').val("");
			}
			else
			{
				/*
				$("#mainDiv").show();*/
				/*$("#sameTimeStart").val("");
				$("#sameTimeEnd").val("");*/
				$("#sameTimeDiv").hide();
				$("#mainDiv").find('.wd_StartTime').attr('readonly', false);
				$("#mainDiv").find('.wd_EndTime').attr('readonly', false);
			}
    });
});
</script>
<script type="text/javascript">
	function setTime()
	{
		var sameTimeStart=$("#sameTimeStart").val();
		var sameTimeEnd=$("#sameTimeEnd").val();
		$("#mainDiv").find('.wd_StartTime').val(sameTimeStart);
		$("#mainDiv").find('.wd_EndTime').val(sameTimeEnd);
		$("#mainDiv").find('.wd_StartTime').attr('readonly', true);
		$("#mainDiv").find('.wd_EndTime').attr('readonly', true);
	}
</script>
<?php 
$errors=array();
$msg="";
if (isset($_POST['submit'])) 
{

/*	echo "<pre>";
	print_r($_POST);
	echo "</pre>";
	die();*/
	$dayArray=$_POST['day_id'];
	$wd_StartTimeArray=$_POST['wd_StartTime'];
	$wd_EndTimeArray =$_POST['wd_EndTime'];
	$wd_day_idArray =$_POST['wd_day_id'];
	$wd_SameTime=$_POST['sameTime'];
	foreach ($dayArray as $day_id) 
	{
		$day_id=$day_id;
		$wd_StartTime=NULL;
		$wd_EndTime=NULL;
		if(isset($wd_StartTimeArray[$day_id]))
		{
			$wd_StartTime =  $wd_StartTimeArray[$day_id];
		}
		if(isset($wd_EndTimeArray[$day_id]))
		{
			$wd_EndTime =  $wd_EndTimeArray[$day_id];
		}
		
		$wd_On='N';
		if(isset($wd_day_idArray[$day_id]))
		{
			$wd_On='Y';
		}

		$updateQ="UPDATE cnf_workingday SET wd_SameTime='$wd_SameTime',wd_On='$wd_On',wd_StartTime='$wd_StartTime',wd_EndTime='$wd_EndTime',wd_Status='A' WHERE day_id=$day_id";
		if(mysqli_query($con,$updateQ))
		{

		}
		else
		{
			$errors[]='Problem updating day:'.$day_id;
		}

	}
	if(empty($errors)){ $msg="Days updated successfully"; }
}
?>
<?php 
if(!empty($msg)){ echo "<div class='alert alert-info'>".$msg."</div>"; }
if(!empty($errors)){ print_r($errors); }
?>
<?php 
$days=getDays();
?>
			<form id="checkout-form" class="smart-form" novalidate="novalidate" method="post" action="">
				<fieldset>
					<div class="row" style="margin-bottom: 15px;">
						<div class="col col-lg-2">
							Office Time
						</div>
						<div class="col col-lg-2">
							<input type="radio" name="sameTime" id="sameTime1" value="Y" <?php if($days[0]['wd_SameTime']=='Y'){ echo "checked='checked'"; } ?>> Every Day Same Time
						</div>
						<div class="col col-lg-4">
							<input type="radio" name="sameTime" id="sameTime1" value="N"  <?php if($days[0]['wd_SameTime']=='N'){ echo "checked='checked'"; } ?>> Every Day Different Time
						</div>
					</div><!--End of row-->
					<div class="row" style="margin-bottom: 20px; <?php if($days[0]['wd_SameTime']=='N'){ echo 'display: none;';}?>" id="sameTimeDiv">
					<div class="col col-lg-2"></div>
						<div class="col col-lg-2">
							<b>Start: </b>
							<input type="time" name="sameTimeStart" id="sameTimeStart" class="form-control" value="<?=$days[0]['wd_StartTime'];?>">
						</div>
						<div class="col col-lg-2">
						<b>End: </b>
							<input type="time" name="sameTimeEnd" id="sameTimeEnd" class="form-control" value="<?=$days[0]['wd_EndTime'];?>">
						</div>

						<div class="col col-lg-1" style="    margin-top: 18px;">

							<p class="btn btn-primary glyphicon glyphicon-ok-circle" style="padding-left: 10px;padding-right: 10px;padding-top: 5px;padding-bottom: 5px;" onclick="setTime()"></p>
						</div>
					</div><!--End of row-->
<div id="mainDiv">
					<?php 
					foreach ($days as $dayRow) 
					{
						?>
						<div class="row" style="margin-bottom: 5px;">
						<div class="col-lg-2"></div>
							<div class="col col-lg-2">
								 <input type="checkbox" name="wd_day_id[<?=$dayRow['day_id']; ?>]" <?php if($dayRow['wd_On']=='Y'){ echo "checked='checked'";} ?>>

								 <?php echo $dayRow['day_Name']; ?>
							</div>
							<!-- <div class="col-lg-1"><b>Start:</b></div> -->
							<div class="col col-lg-2">
							
								<input type="time" name="wd_StartTime[<?=$dayRow['day_id']; ?>]" class="form-control wd_StartTime" value="<?=$dayRow['wd_StartTime'];?>">
							</div>
							<!-- <div class="col-lg-1"><b>End:</b></div> -->
							<div class="col col-lg-2">
							
								<input type="time" name="wd_EndTime[<?=$dayRow['day_id']; ?>]" class="form-control wd_EndTime" value="<?=$dayRow['wd_EndTime'];?>">
							</div>
							<input type="hidden" name="day_id[<?php echo $dayRow['day_id']; ?>]" value="<?php echo $dayRow['day_id']; ?>">
						</div><!--End of row-->
						<?php
					}
					?>
</div><!--End of main div-->
				</fieldset>
				<footer>
					<button type="submit" class="btn btn-primary" name="submit" >Save </button>
				</footer>
			</form>

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

<?php
//include footer
include ("inc/google-analytics.php");
?>