<?php 
include('inc/PHP/functions.php');
//initilize the page
require_once ("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once ("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

 YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
 E.G. $page_title = "Custom Title" */

$page_title = "Leave Summary Report";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include ("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["Report"]["sub"]["Leave Summary Report"]["active"] = true;
include ("inc/nav.php");
?>
<style type="text/css">
	

</style>
<?php 



 ?>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<div id="main" role="main">

	<?php
		//configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
		//$breadcrumbs["New Crumb"] => "http://url.com"
		$breadcrumbs["Report"] = "";
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
				<h2>Leave Summary Report</h2>
			</header>

			<!-- widget div-->
			<div>		


				<!-- widget content -->
				<div class="widget-body no-padding">
				<br>
					<form role="form" id="" action="Print_Report.php" target="_blank" method="post" style="padding: 20px;">
			            <fieldset>
							<div class="row" style="margin-top: 10px">    
		                      <div class="col col-lg-2">
		                          <label class="input">Select Group: </label>
		                      </div>
		                      <div class="col col-lg-4">
		                          <select class="select2" name="group_id" id="group_id" onchange="FilterEmployee()" required="" >
		                          <option value="">
		                          	Select Group
		                          </option>
		                          	 <?php
											$AllGroups = getActiveGroup();
										?>
		                          		 <?php 
							                foreach($AllGroups as $group){

							            ?>
					            			<option value="<?php echo $group['group_id'];?>" ><?php echo $group['group_Name'];?></option>
							            <?php
							                }
							            ?>
	                          	</select>
		                      </div>
		                    </div>

		                    <div class="row" style="margin-top: 10px">    
		                      <div class="col col-lg-2">
		                          <label class="input">Select Department: </label>
		                      </div>
		                      <div class="col col-lg-4">
		                          <select class="select2" name="dept_id" id="dept_id" required="" onchange="FilterEmployee()">
		                          <option value="" >
		                          	Select Group
		                          </option>
		                          	 <?php
											$AllDepartments = getActiveDepartments();
										?>
		                          		 <?php 
							                foreach($AllDepartments as $dept){

							            ?>
					            			<option  value="<?php echo $dept['dept_id'];?>" ><?php echo $dept['dept_Name'];?></option>
							            <?php
							                }
							            ?>
	                          	</select>
		                      </div>
		                    </div>

		                    <div class="row" style="margin-top: 10px" id="">    
		                      <div class="col col-lg-2">
		                          <label class="input">Select Employee: </label>
		                      </div>
		                      <div class="col col-lg-4">
		                          <select class="select2" name="user_id" id="user_id" >
		                          <option value="">Select Employee</option>
		                          	 
	                          	</select>
		                      </div>
		                    </div>
		                    <select id="userCopy" style="display: none;">
		                    	<?php
											$AllEmployees = getEmployeeListDetail();
										?>
		                          		 <?php 
							                foreach($AllEmployees as $user){

							            ?>
					            			<option class="group<?php echo $user['group_id'] ?>dept<?php echo $user['dept_id'] ?>" value="<?php echo $user['user_id'];?>"  style="display: none;"><?php echo $user['user_FirstName'];?>/ <?php echo $user['user_LastName'];?></option>
							            <?php
							                }
							            ?>
		                    </select>
		                    <div class="row" style="margin-top: 10px">    
		                      <div class="col col-lg-2">
		                          <label class="input">Generate For All Employees </label>
		                      </div>
		                      <div class="col col-lg-4">
		                          <input type="checkbox" name="all_user" id="all_user" onclick="SelectAllUsers()">
		                      </div>
		                    </div>
		                </fieldset>

		                <footer>
		                	<div class="row" style="margin-top: 10px">    
		                      
		                      <div class="col col-lg-4 pull-right">
		                          <input type="submit" name="generateReport" class="btn btn-primary" value="Generate Report">
		                      </div>
		                    </div>
		                </footer>

			         </form>
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

<script type="text/javascript">
	

function SelectAllUsers(){
	
	if(document.getElementById('all_user').checked == true){

		$("#user_id").hide();
	}
	else{

		$("#user_id").show();
	}
}

function FilterEmployee(){
var options = ""
var dept_id = $("#dept_id option:selected").val();
var group_id = $("#group_id option:selected").val();

$('#user_id')
    .find('option')
    .remove()
    .end()
    .append('<option value="">Select Employee</option>');

$("#userCopy .group"+group_id+"dept"+dept_id).each(function(){

	$('#user_id').append($('<option>',
		 {
		    value: $(this).val(),
		    text : $(this).html()
		}));
});

$("user_id").html(options);

if(dept_id!="" && group_id!=""){



}
}

</script>

<?php //include required scripts
include ("inc/scripts.php");
?>

<!-- PAGE RELATED PLUGIN(S) -->
<script src="<?php echo ASSETS_URL; ?>/js/plugin/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo ASSETS_URL; ?>/js/plugin/datatables/dataTables.colVis.min.js"></script>
<script src="<?php echo ASSETS_URL; ?>/js/plugin/datatables/dataTables.tableTools.min.js"></script>
<script src="<?php echo ASSETS_URL; ?>/js/plugin/datatables/dataTables.bootstrap.min.js"></script>
<script src="<?php echo ASSETS_URL; ?>/js/plugin/datatable-responsive/datatables.responsive.min.js"></script>


<?php
//include footer
include ("inc/google-analytics.php");
?>