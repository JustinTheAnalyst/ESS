<?php
session_start();
//initilize the page
require_once 'inc/init.php';

//require UI configuration (nav, ribbon, etc.)
require_once 'inc/config.ui.php';

require_once 'inc/PHP/functions.php';
global $DB;

$page_title = "Login";
// execute sign-in
if(isset($_POST['submit']) && isset($_POST['user_Email']) && isset($_POST['user_Password']))
{
	if($_POST['submit'] == "Login")
	{
		$user_Email = $_POST['user_Email'];
		$user_Password = $_POST['user_Password'];

	  	$sqlCheckLogin = "SELECT * FROM u_user WHERE user_Email = ? AND user_Password = ?";
	  	$stmt = $DB->prepare($sqlCheckLogin);
	  	$stmt->bindValue(1,$user_Email);
	  	$stmt->bindValue(2,$user_Password);
	  	$stmt->execute();
		
	  	if ($stmt->rowCount()>0)
	  	{
			$rowCheckLogin = $stmt->fetch(PDO::FETCH_ASSOC);
			$user_id = $rowCheckLogin['user_id'];
				
       		$companyQ = "SELECT * FROM u_usercompany WHERE user_id=? AND uc_Status=?";
       		$stmt2 = $DB->prepare($companyQ);
       		$stmt2->bindValue(1,$user_id);
       		$stmt2->bindValue(2,'A');
      		$companyRow = $stmt2->fetch(PDO::FETCH_ASSOC);
     
			$_SESSION['user_Email'] = $user_Email ;
			$_SESSION['user_Password'] = $user_Password ;
			$_SESSION['user_id'] = $user_id;
			$_SESSION['user_Type']=$rowCheckLogin['user_Type'];
			$_SESSION['user_PicPath']=$rowCheckLogin['user_PicPath'];
			$_SESSION['user_FirstName']=$rowCheckLogin['user_FirstName'];
			//$_SESSION['user_LastName']=$rowCheckLogin['user_LastName'];
	      	$_SESSION['user_PhoneNo']=$rowCheckLogin['user_PhoneNo'];
	      	$_SESSION['user_UserCode']=$rowCheckLogin['user_UserCode'];
	      	$_SESSION['user_Status']=$rowCheckLogin['user_Status'];
	      	
	      	$_SESSION['user_DOB']=$rowCheckLogin['user_DOB'];
	      	$_SESSION['user_Gender']=$rowCheckLogin['user_Gender'];
	      	$_SESSION['user_JoiningDateTime']=$rowCheckLogin['user_JoiningDateTime'];
	      	$_SESSION['loc_id']=$companyRow['loc_id'];
	      	$_SESSION['dept_id']=$companyRow['dept_id'];
	      	$_SESSION['desig_id']=$companyRow['desig_id'];
	      	$_SESSION['uc_Status']=$companyRow['uc_Status'];
			
	      	if($rowCheckLogin['user_Type']=='A')
	      	{
	        	$redirectionPage="admin/dashboard.php";
	      	}
	      	else
	      	{
	        	$redirectionPage="employee/dashboard.php";
	      	}

	      	echo '	<script type="text/javascript">
						window.location.href="'.$redirectionPage.'";
					</script>';
		}
		else
		{
			echo '	<script type="text/javascript">
						alert("Wrong Email or Password");
					</script>';
		}
	}
}
?>
<?php 
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
$no_main_header = true;
$page_html_prop = array("id"=>"extr-page", "class"=>"animated fadeInDown");

require_once 'inc/header.php';
?>
<div id="main" role="main" style="margin:0px;">
	<!-- MAIN CONTENT -->
	<div id="content">
		
		<div class="row text-center" style="font-weight:bold; font-size:24px">
			<div class="col-xs-12 col-sm-12 col-md-5 col-lg-4 col-md-offset-4">
  			<?php echo PROJECT_NAME; ?> <small><?php echo SYS_VERSION; ?></small>
  			</div>
		</div>
		
		<div class="row text-center" style="padding:20px;">
			<div class="col-xs-12 col-sm-12 col-md-5 col-lg-4 col-md-offset-4">
				<div class="col-xs-6">
  					<img src="<?php echo ASSETS_URL; ?>/img/customer-logo.png" alt="<?php echo CUSTOMER_NAME; ?>" style="width: 64px;">
  				</div>
  				<div class="col-xs-6" style="margin-top:20px;">
  					<img src="<?php echo ASSETS_URL; ?>/img/customer-logo-2.png" width="100%" alt="Baitulmal" style="width: 128px;"> 
  				</div>
  			</div>
		</div>
					
		<div class="row">
			<div class="col-md-12 col-md-4 col-md-offset-4">
				<div class="well no-padding">
					<form action="" id="login-form" class="smart-form client-form" method="post">
						<header>
							Sign In
						</header>

						<fieldset>
							<section>
								<label class="label">E-mail</label>
								<label class="input"> <i class="icon-append fa fa-user"></i>
								<input type="email" name="user_Email" required/>
								<b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-teal"></i> Please enter email address/username</b></label>
							</section>

							<section>
								<label class="label">Password</label>
								<label class="input"> <i class="icon-append fa fa-lock"></i>
									<input type="password" name="user_Password" required>
									<b class="tooltip tooltip-top-right"><i class="fa fa-lock txt-color-teal"></i> Enter your password</b> </label>
								<!-- div class="note">
									<a href="<?php echo APP_URL; ?>/forgot-password.php">Forgot password?</a>
								</div -->
							</section>
							
							<!-- section>
								<label class="checkbox">
									<input type="checkbox" name="remember" checked="checked">
									<i></i>Stay signed in</label>
							</section -->
							
						</fieldset>
						<footer>
							<button type="submit" class="btn btn-primary" name="submit" value="Login">
								Sign in
							</button>
						</footer>
					</form>
				</div>
			</div>
		</div>
		
		<div class="row text-center">
			<div class="col-xs-12 col-sm-12 col-md-5 col-lg-4 col-md-offset-4">
  				<a href="https://www.bearotech.com" target="_blank"><img src="<?php echo ASSETS_URL; ?>/img/logo.png" alt="BR Solutions" style="width:96px;"></a>
  				<p>Be Better - https://www.bearotech.com</p> 
  			</div>
		</div>
	</div>
</div>

<!-- END PAGE FOOTER -->

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