<?php
session_start();
//initilize the page
require_once 'inc/init.php';

//require UI configuration (nav, ribbon, etc.)
require_once 'inc/config.ui.php';

require_once 'inc/PHP/functions.php';
global $DB;

//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
$no_main_header = true;
$page_html_prop = array("id"=>"extr-page", "class"=>"animated fadeInDown");

// execute sign-in
if(isset($_POST['submit']) && isset($_POST['user_Email']))
{
	if($_POST['submit'] == "Reset")
	{
		$user_Email = $_POST['user_Email'];
		
		// auto-generate a password and email to user
	}
}
?>
<?php 
require_once 'inc/header.php';
?>

<div id="main" role="main">

	<!-- MAIN CONTENT -->
	<div id="content" class="container">
		<div style="margin:10px;">
			<div class="row text-center">
				<div class="col-xs-12 col-sm-12 col-md-5 col-lg-4 col-md-offset-4">
	  				<img src="<?php echo ASSETS_URL; ?>/img/logo.png" alt="BearoTech"> 
	  			</div>
			</div>
					
			<div class="row text-center" style="font-size:18px">
				<div class="col-xs-12 col-sm-12 col-md-5 col-lg-4 col-md-offset-4">
	  			<?php echo PROJECT_NAME; ?> <br/><small><?php echo SYS_VERSION; ?></small>
	  			</div>
			</div>
		</div>
		
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-5 col-lg-4 col-md-offset-4">
				<div class="well no-padding">
					<form action="<?php echo APP_URL; ?>/login.php" id="login-form" class="smart-form client-form" method="post">
						<header>
							Forgot Password
						</header>

						<fieldset>
							<section>
								<label class="label">Enter your email address</label>
								<label class="input"> <i class="icon-append fa fa-envelope"></i>
									<input type="email" name="user_Email" required/>
									<b class="tooltip tooltip-top-right"><i class="fa fa-envelope txt-color-teal"></i> Please enter email address for password reset</b></label>
							</section>
							<section>
								<div class="note">
									<a href="<?php echo APP_URL; ?>/login.php">I remembered my password!</a>
								</div>
							</section>
						</fieldset>
						<footer>
							<button type="submit" class="btn btn-primary" value="Reset">
								<i class="fa fa-refresh"></i> Reset Password
							</button>
						</footer>
					</form>
				</div>
			</div>
		</div>
	</div>

	<div class="container" style="margin-top:-20px;">
		<div class="row text-center">
			<div class="col-xs-12 col-sm-12 col-md-5 col-lg-4 col-md-offset-4">
				<div class="col-xs-6">
  					<img src="<?php echo ASSETS_URL; ?>/img/customer-logo.png" alt="<?php echo CUSTOMER_NAME; ?>">
  				</div>
  				<div class="col-xs-6" style="padding-top:40px;">
  					<img src="<?php echo ASSETS_URL; ?>/img/customer-logo-2.png" width="100%" alt="Baitulmal"> 
  				</div>
  			</div>
		</div>
	</div>
</div>

<!-- END MAIN PANEL -->

<?php 
	//include required scripts
	include("inc/scripts.php"); 
?>
<!-- PAGE RELATED PLUGIN(S) 
<script src="..."></script>-->

<script type="text/javascript">
	runAllForms();

	$(function() {
		// Validation
		$("#login-form").validate({
			// Rules for form validation
			rules : {
				email : {
					required : true,
					email : true
				},
				password : {
					required : true,
					minlength : 3,
					maxlength : 20
				}
			},

			// Messages for form validation
			messages : {
				email : {
					required : 'Please enter your email address',
					email : 'Please enter a VALID email address'
				},
				password : {
					required : 'Please enter your password'
				}
			},

			// Do not change code below
			errorPlacement : function(error, element) {
				error.insertAfter(element.parent());
			}
		});
	});
</script>
<?php 
//include footer
include("inc/google-analytics.php"); 
?>