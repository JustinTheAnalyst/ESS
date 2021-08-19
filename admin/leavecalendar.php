<?php 
include('inc/PHP/functions.php');
//initilize the page
require_once ("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once ("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

 YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
 E.G. $page_title = "Custom Title" */

$page_title = "Leave Calendar";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
//$page_css[] = "your_style.css";
include ("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["Leave Calendar"]["active"] = true;
include ("inc/nav.php");
?>

<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<div id="main" role="main">

	<?php
		//configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
		//$breadcrumbs["New Crumb"] => "http://url.com"
		$breadcrumbs["configuration"] = "";
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
				<h2>Leave Calendar</h2>
			</header>

			<!-- widget div-->
			<div>		


				<!-- widget content -->
				<div class="widget-body no-padding">
	<br>
	<?php include('connection.php'); ?>
	<?php 
	//WRITE JSON FILE TO READY FOR CALENDAR
	$sql="SELECT LA.la_id, LA.user_id, LA.la_Date,LA.la_Status,u_user.user_FirstName,u_user.user_LastName 
	FROM u_leaveapplication AS LA
	INNER JOIN u_user ON u_user.user_id=LA.user_id
	WHERE 1"; 

	$posts = array();
	$result=mysqli_query($con,$sql);
	while($row=mysqli_fetch_array($result)) 
	{ 
	$title=$row['user_FirstName'].$row['user_LastName']; 
	$start=$row['la_Date']; 
	if($row['la_Status']=='P'){ $color="#5184c1";}
	elseif($row['la_Status']=='A'){$color="#d5452c"; }
	elseif($row['la_Status']=='R'){ $color="#9fa6ad"; }
	elseif($row['la_Status']=='C'){ $color="#c79121"; }
	$posts[] = array('title'=> $title, 'start'=> $start, 'color'=>$color);
	} 
	$fp = fopen('json/leaveCalendar.json', 'w');
	fwrite($fp, json_encode($posts));
	fclose($fp);
	//END OF WRITE JSON FILE

	?>

	<div id='script-warning'>
		<code>Problem Occured while loading the calendar</code> 
	</div>
	<div style="height: 30px;width: 100px;background:#5184c1;float: left;color: white;padding: 5px 0 0 10px;">	Pending</div> 
	<div style="height: 30px;width: 100px;background:#d5452c;float: left;color: white;padding: 5px 0 0 10px;">	Approved</div> 
	<div style="height: 30px;width: 100px;background:#9fa6ad;float: left;color: white;padding: 5px 0 0 10px;">	Rejected</div> 
	<div style="height: 30px;width: 100px;background:#c79121;float: left;color: white;padding: 5px 0 0 10px;">	Cancelled</div> 
	<br><br>
	<div id='loading'>loading...</div>

	<div id='calendar'></div>
			
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

<script src='../lib/moment.min.js'></script>

<script src='../fullcalendar.min.js'></script>
<script>

	$(document).ready(function() {
	
		$('#calendar').fullCalendar({
			header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month,agendaWeek,agendaDay,listWeek'
			},
			//defaultDate: '2016-12-12',
			editable: true,
			navLinks: true, // can click day/week names to navigate views
			eventLimit: true, // allow "more" link when too many events
			events: {
				url: 'php/leaveCalendar.php',
				error: function() {
					$('#script-warning').show();
				}
			},
			loading: function(bool) {
				$('#loading').toggle(bool);
			}
		});
		
	});

</script>
<link href='../fullcalendar.min.css' rel='stylesheet' />
<link href='../fullcalendar.print.min.css' rel='stylesheet' media='print' />
<style>
	#script-warning {
		display: none;
		background: #eee;
		border-bottom: 1px solid #ddd;
		padding: 0 10px;
		line-height: 40px;
		text-align: center;
		font-weight: bold;
		font-size: 12px;
		color: red;
	}

	#loading {
		display: none;
		position: absolute;
		top: 10px;
		right: 10px;
	}

	#calendar {
		max-width: 90%;
		margin: 40px auto;
		padding: 0 10px;
	}
	.jarviswidget #calendar {
	    margin-top: 0px;
	}

</style>
<?php
//include footer
include ("inc/google-analytics.php");

?>