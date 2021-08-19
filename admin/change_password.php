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

$page_title = "Change Password";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include ("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["Dashboard"]["active"] = true;
include ("inc/nav.php");
?>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<div id="main" role="main">

	<?php
		//configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
		//$breadcrumbs["New Crumb"] => "http://url.com"
		$breadcrumbs["Dashboard"] = "";
		include("inc/ribbon.php");
	?>
	<?php 
		$msg = "";
		if(isset($_POST['save'])){

			$user_Password = $_POST['user_Password'];
			$new_Password = $_POST['new_Password'];
			$re_type_Password = $_POST['re_type_Password'];
			$user_id = $_SESSION['user_id'];
			if($user_Password == $_SESSION['user_Password']){

				if($new_Password==$re_type_Password){

					$queryPassword = "UPDATE u_user SET user_Password = $user_Password WHERE user_id =$user_id;";
					$resultPassword = mysqli_query($con, $queryPassword);
					if($queryPassword){

							$msg.='<div class="alert alert-success">Password Changed Successfully</div>';
							$_SESSION['user_Password'] = $new_Password;

					}
				}
				else{

				$msg.='<div class="alert alert-danger">Password Not Match</div>';
						
				}
			}
			else{

				$msg.='<div class="alert alert-danger">Current Password Is Incorrect</div>';
			}
		}

	 ?>
	<style type="text/css">
		.form-group{
			padding: 10px !important;
			margin-bottom: 10px !important;
		}
	</style>

	<!-- MAIN CONTENT -->
	<div id="content">
		
		<!-- widget grid -->
		<section id="widget-grid" class="">
		
<!-- row -->
<div class="row">

	<!-- NEW WIDGET START -->
	<article class="col-xs-12 col-sm-12 col-md-6 col-lg-6">

		<!-- Widget ID (each widget will need unique ID)-->
		<div class="jarviswidget jarviswidget-color-purity" id="wid-id-1" data-widget-editbutton="false">
			<header>				
				<h2>Change Password</h2>
			</header>

			<!-- widget div-->
			<div>		


				<!-- widget content -->
				<div class="widget-body">
					<br>
					<div class="panel panel-primary">
						<div class="panel-heading">
							<h3>
								Change Password
							</h3>
						</div>
						<form action="" method="post">
							<fieldset>
								<?php echo $msg; ?>
								<div class="form-group row">
					                <label for="group_name" class="col-sm-4 control-label"><small>Current Password</small></label>
					                <div class="col-sm-8">
					                   <input type="password" class="form-control" name="user_Password" required="" id="">                
					                </div>
					            </div>
					            <div class="form-group row">
					                <label for="group_name" class="col-sm-4 control-label"><small>New Password</small></label>
					                <div class="col-sm-8">
					                   <input type="password" class="form-control" name="new_Password" required="" id="">                
					                </div>
					            </div>
					            <div class="form-group row">
					                <label for="group_name" class="col-sm-4 control-label"><small>Re Type Password</small></label>
					                <div class="col-sm-8">
					                   <input type="password" class="form-control" name="re_type_Password" required="" id="">                
					                </div>
					            </div>
					             <div class="form-group row">
					                
					                <div class="col-sm-12">
					                   <input class="btn btn-primary pull-right" type="submit" name="save" required="" id="" value="Save">                
					                </div>
					            </div>
							</fieldset>
						</form>
					</div>
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

<?php
//include footer
include ("inc/google-analytics.php");
?>