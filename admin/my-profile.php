<?php 
include('inc/PHP/functions.php');
//initilize the page
require_once ("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once ("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

 YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
 E.G. $page_title = "Custom Title" */

$page_title = "My Profile";

if (!isset($_SESSION['user_id']))
{
	header("Location: dashboard.php");
	exit;
}
else
{
	$user_id = $_SESSION['user_id'];

	global $DB;
	$stmt = $DB->prepare('SELECT * FROM u_user WHERE user_id=?');
	$stmt->bindValue(1,$user_id);
	$stmt->execute();
	$row = $stmt->fetch(PDO::FETCH_ASSOC);

	if ($row['user_PicPath'] == null || $row['user_PicPath'] == '')
	{
		$images = "../uploads/no-image.png";
	}
	else
	{
		$images = $row['user_PicPath'];
	}
}

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
//$page_css[] = "your_style.css";
include ("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
//$page_nav["Employees"]["active"] = true;
$page_nav["My Profile"]["active"] = true;
include ("inc/nav.php");
?>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<style type="text/css">
	
</style>
<div id="main" role="main">
<?php
include("inc/ribbon.php");
?>
	<div id="content">	
		<div class="row">
			<div class="col-xs-12 col-sm-9 col-md-9 col-lg-6">
				<div class="alert alert-block alert-success">
					<a class="close" data-dismiss="alert" href="#">×</a>
					<h4 class="alert-heading"><i class="fa fa-check-square-o"></i> Note:</h4>
					<p>
						Please make sure all the info are correctly key in.
					</p>
				</div>
			</div>
			
			<!-- <div class="col-xs-12 col-sm-3 col-md-3 col-lg-6">
				
				<a href="ajax/modal-content/model-content-2.html" data-toggle="modal" data-target="#remoteModal" class="btn btn-success btn-lg pull-right header-btn hidden-mobile">
					<i class="fa fa-circle-arrow-up fa-lg"></i> 
					Launch form modal
				</a>
				
				<div class="modal fade" id="remoteModal" tabindex="-1" role="dialog" aria-labelledby="remoteModalLabel" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content"></div>
					</div>
				</div>
				
			</div> -->
		</div>

		

		<!-- widget grid -->
		<section id="widget-grid" class="">
			<div class="row">
				<article class="col-sm-12 col-md-12 col-lg-6">
					<div class="jarviswidget" id="wid-id-8" data-widget-deletebutton="false" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-custombutton="false">
						<!-- widget options:
							usage: <div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false">
							
							data-widget-colorbutton="false"	
							data-widget-editbutton="false"
							data-widget-togglebutton="false"
							data-widget-deletebutton="false"
							data-widget-fullscreenbutton="false"
							data-widget-custombutton="false"
							data-widget-collapsed="true" 
							data-widget-sortable="false"
							
						-->
						<header>
							<span class="widget-icon"> <i class="fa fa-edit"></i> </span>
							<h2>My Profile </h2>				
							
						</header>
		
						<div>
							<div class="jarviswidget-editbox">
								<!-- This area used as dropdown edit box -->
								
							</div>
							
							<div class="widget-body no-padding">
								<form action="" method="post" id="update-my-profile-form" class="smart-form">
									<header>General Information</header>
									
									<fieldset>	
										<div class="row">
											<section class="col col-12">	
								                <img src="<?php echo $images; ?>" style="height: 120px; width: 120px" id="img">	
								                (photo size: 120px x 120px)
											</section>
										</div>
										
										<div class="row">
											<section class="col col-6">	
												<label class="label">Profile Photo</label>
								                <label class="input">
								                	<input type="file" name="profilePic" onchange="readURL(this);" />
									                <input type="hidden" name="oldProfilePic" value="<?php echo $row['user_PicPath']; ?>">
								                </label>	
											</section>
											
											<section class="col col-6">
							                    <label class="label">Employee ID <small><span class="text-danger">*</span></small></label>
							                    <label class="input state-disabled">
							                       <input type="text" class="form-control" name="id" id="id" value="<?php echo $row['user_UserCode']; ?>" disabled="disabled" />
							                       <input type="hidden" class="form-control" name="profile_id" id="profile_id" value="<?php echo $user_id; ?>" />
							                    </label>
											</section>
										</div>
										
										<div class="row">
											<section class="col col-6">
												<label class="label">First Name <small><span class="text-danger">*</span></small></label>
												<label class="input">
													<i class="icon-append fa fa-user"></i>
													<input class="form-control" type="text" name="fname" id="fname" value="<?php echo $row['user_FirstName']; ?>">
												</label>
											</section>
											<section class="col col-6">
												<label class="label">Last Name <small><span class="text-danger"> *</span></small></label>
												<label class="input">
													<i class="icon-append fa fa-user"></i>
													<input class="form-control" type="text" name="lname" id="lname" value="<?php echo $row['user_LastName']; ?>">
												</label>
											</section>
										</div>
										
										<div class="row">
											<section class="col col-6">
												<label class="label">DOB</label>
												<label class="input">
													<i class="icon-append fa fa-calendar"></i>
													<input type="text" name="dob" id="dob" placeholder="Date of birth" value="<?php echo $row['user_DOB']; ?>">
												</label>
											</section>
											<section class="col col-6">
												<label class="label">Gender <small><span class="text-danger"> *</span></small></label>
												<label class="select">
													<select name="gender" id="gender">
														<option value="0" selected="selected" disabled="disabled">-- Select --</option>
														<?php 
									                    if($row['user_Gender']=='M')
									                    { 
									                    	echo '<option value="M" selected="selected">Male</option>
																	<option value="F">Female</option>';
									                    }
									                    else 
									                    {
									                    	echo '<option value="M">Male</option>
																	<option value="F" selected="selected">Female</option>';
									                    }
									                    ?>
													</select> <i></i> 
												</label>
											</section>
										</div>
										
										<div class="row">
											<section class="col col-6">
												<label class="label">Phone <small><span class="text-danger"> *</span></small></label> 
												<label class="input">
													<i class="icon-append fa fa-phone"></i>
													<input type="tel" name="phone" id="phone" value="<?php echo $row['user_PhoneNo']; ?>" placeholder="Phone" data-mask="(999) 999-9999" class="valid">
												</label>
											</section>
										</div>
										
										<section>
											<label class="label">Mailing Address</label>
											<label class="textarea">
												<textarea rows="5" name="tAddress" id="tAddress"><?php echo $row['user_TAddress']; ?></textarea>
											</label>
										</section>
										
										<section>
											<label class="label">Permenant Address</label>
											<label class="textarea">
												<textarea rows="5" name="pAddress" id="pAddress"><?php echo $row['user_PAddress']; ?></textarea>
											</label>
										</section>
										
										<section>
											<label class="checkbox"><input type="checkbox" name="copyTAddress" id="copyTAddress"><i></i>Same as mailing address.</label>
										</section>
									</fieldset>
									
									<header>Login Information</header>
									
									<fieldset>		
										
										<section>
											<label class="label">E-mail <small><span class="text-danger"> *</span></small></label>
											<label class="input state-disabled">
												<i class="icon-append fa fa-envelope-o"></i>
												<input type="email" name="email" id="email" value="<?php echo $row['user_Email']; ?>" disabled="disabled">
											</label>
										</section>
										
										<section>
											<label class="input">Password <small><span class="text-danger"> *</span></small></label>
											<label class="input">
												<i class="icon-append fa fa-lock"></i>
												<input type="password" name="password" id="password" placeholder="Password" value="<?php echo $row['user_Password']; ?>">
												<b class="tooltip tooltip-bottom-right">Don't forget your password</b> 
											</label>
										</section>
										
										<section>
											<label class="input">Confirm Password </label>
											<label class="input">
												<input type="password" name="passwordConfirm" id="passwordConfirm" placeholder="Confirm password" value="<?php echo $row['user_Password']; ?>">
												<b class="tooltip tooltip-bottom-right">Don't forget your password</b> 
											</label>
										</section>
									</fieldset>
										
									<header>Document Information</header>
									
									<fieldset>		
										<section>
											<div class="table-responsive">
												<table class="table table-bordered">
													<thead>
														<tr>
															<th>Type</th>
															<th>Name</th>
														</tr>
													</thead>
													<tbody>
													<?php 
								                    $docList = getActiveDocuments();
								                    foreach ($docList as $key => $docArray)
								                    {
								                    	$sql = 'SELECT * FROM cnf_document AS DOC
								                            	INNER JOIN u_doclist AS UDC ON UDC.doc_id=DOC.doc_id
								                         		WHERE UDC.user_id=? AND UDC.doc_id=?';
								                    	$stmt2 = $DB->prepare($sql);
								                    	$stmt2->bindValue(1,$user_id);
								                    	$stmt2->bindValue(2,$docArray['doc_id']);
								                    	$stmt2->execute();
								                    	$docInfo = $stmt2->fetch(PDO::FETCH_ASSOC);
								                    ?>
								                    	<tr>
                    	                               		<td>
                    						                    <?php echo $docArray['doc_Name']; ?>
                    						                </td>
                    	                                    <td>
	                    	                                    <?php 
	                    	                                    if ($docInfo['udc_Path'])
	                    	                                    {
										                            if($docArray['doc_Type']=='F')
										                            {
										                                echo '<label class="label"><a href="'.$docInfo['udc_Path'].'" target="_blank">Download</a></label>';
										                            }
										                            elseif($docArray['doc_Type']=='I')
										                            {
										                                echo '<label class="label"><a href="'.$docInfo['udc_Path'].'" target="_blank"><img src="'.$docInfo['udc_Path'].'" style="height:60px;width:100px"></a></label>';
										                            }
										                            
										                            echo '<input type="hidden" name="user_PicPathOld" value="'.$docInfo['udc_Path'].'">';
	                    	                                    }
	                    	                                    else 
	                    	                                    {
	                    	                                    	echo '';
	                    	                                    }
																?>
																<label class="input">
												                	<input type="file" name="docs[<?php echo $docArray['doc_id']; ?>]" class="form-control">
												                </label>
                    	                                 	</td>
                    	                                </tr>
                    	                            <?php 
								                    }
								                    ?>
													</tbody>
												</table>
											</div>
										</section>
										<section>
											<label class="checkbox"><input type="checkbox" name="copy" id="copy"><i></i>Send a copy to my e-mail address</label>
										</section>
									</fieldset>
									
									<footer>
										<div class="pull-right">
											<div id="divMsg" class="pull-left" style="padding:15px; display:none;"><img src="../img/select2-spinner.gif" alt="Please wait.." /></div>
											
											<button type="submit" name="btnUpdateMyProfile" id="btnUpdateMyProfile" class="btn btn-primary">
												<i class="fa fa-save"></i> Save
											</button>
										</div>
									</footer>
									
									<div class="message">
										<i class="fa fa-thumbs-up"></i>
										<p>Your message was successfully sent!</p>
									</div>
								</form>						
							</div>
						</div>
					</div>
				</article>
			</div>
		</section>
	</div>
</div>
		
<?php // include page footer
include ("inc/footer.php");
?>
<!-- END PAGE FOOTER -->

<?php //include required scripts
include ("inc/scripts.php");
?>

<script type="text/javascript" src="inc/js/custom.js"></script>

<!-- SCRIPTS ON PAGE EVENT -->
<script type="text/javascript">
	
	/* DO NOT REMOVE : GLOBAL FUNCTIONS!
	 *
	 * pageSetUp(); WILL CALL THE FOLLOWING FUNCTIONS
	 *
	 * // activate tooltips
	 * $("[rel=tooltip]").tooltip();
	 *
	 * // activate popovers
	 * $("[rel=popover]").popover();
	 *
	 * // activate popovers with hover states
	 * $("[rel=popover-hover]").popover({ trigger: "hover" });
	 *
	 * // activate inline charts
	 * runAllCharts();
	 *
	 * // setup widgets
	 * setup_widgets_desktop();
	 *
	 * // run form elements
	 * runAllForms();
	 *
	 ********************************
	 *
	 * pageSetUp() is needed whenever you load a page.
	 * It initializes and checks for all basic elements of the page
	 * and makes rendering easier.
	 *
	 */

	pageSetUp();
	
	
	// PAGE RELATED SCRIPTS

	// pagefunction
	
	var pagefunction = function() {

		var $myProfileForm = $("#my-profile-form").validate({

			// Rules for form validation
			rules : {
				fname : {
					required : true
				},
				lname : {
					required : true
				},
				gender : {
					required : true
				},
				phone : {
					required : true
				},
				email : {
					required : true,
					email : true
				},
				password : {
					required : true,
					minlength : 3,
					maxlength : 20
				},
				passwordConfirm : {
					required : true,
					minlength : 3,
					maxlength : 20,
					equalTo : '#password'
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
				},
				passwordConfirm : {
					required : 'Please enter your password one more time',
					equalTo : 'Please enter the same password as above'
				},
				firstname : {
					required : 'Please select your first name'
				},
				lastname : {
					required : 'Please select your last name'
				},
				gender : {
					required : 'Please select your gender'
				},
				mAddress : {
					required : 'Please fill in your mailing address'
				},
				pAddress : {
					required : 'Please fill in your permenant address'
				}
			},

			// Do not change code below
			errorPlacement : function(error, element) {
				error.insertAfter(element.parent());
			}
		});

		var $checkoutForm = $('#checkout-form').validate({
		// Rules for form validation
			rules : {
				fname : {
					required : true
				},
				lname : {
					required : true
				},
				email : {
					required : true,
					email : true
				},
				phone : {
					required : true
				},
				country : {
					required : true
				},
				city : {
					required : true
				},
				code : {
					required : true,
					digits : true
				},
				address : {
					required : true
				},
				name : {
					required : true
				},
				card : {
					required : true,
					creditcard : true
				},
				cvv : {
					required : true,
					digits : true
				},
				month : {
					required : true
				},
				year : {
					required : true,
					digits : true
				}
			},
	
			// Messages for form validation
			messages : {
				fname : {
					required : 'Please enter your first name'
				},
				lname : {
					required : 'Please enter your last name'
				},
				email : {
					required : 'Please enter your email address',
					email : 'Please enter a VALID email address'
				},
				phone : {
					required : 'Please enter your phone number'
				},
				country : {
					required : 'Please select your country'
				},
				city : {
					required : 'Please enter your city'
				},
				code : {
					required : 'Please enter code',
					digits : 'Digits only please'
				},
				address : {
					required : 'Please enter your full address'
				},
				name : {
					required : 'Please enter name on your card'
				},
				card : {
					required : 'Please enter your card number'
				},
				cvv : {
					required : 'Enter CVV2',
					digits : 'Digits only'
				},
				month : {
					required : 'Select month'
				},
				year : {
					required : 'Enter year',
					digits : 'Digits only please'
				}
			},
	
			// Do not change code below
			errorPlacement : function(error, element) {
				error.insertAfter(element.parent());
			}
		});
				
		var $registerForm = $("#smart-form-register").validate({

			// Rules for form validation
			rules : {
				username : {
					required : true
				},
				email : {
					required : true,
					email : true
				},
				password : {
					required : true,
					minlength : 3,
					maxlength : 20
				},
				passwordConfirm : {
					required : true,
					minlength : 3,
					maxlength : 20,
					equalTo : '#password'
				},
				firstname : {
					required : true
				},
				lastname : {
					required : true
				},
				gender : {
					required : true
				},
				terms : {
					required : true
				}
			},

			// Messages for form validation
			messages : {
				login : {
					required : 'Please enter your login'
				},
				email : {
					required : 'Please enter your email address',
					email : 'Please enter a VALID email address'
				},
				password : {
					required : 'Please enter your password'
				},
				passwordConfirm : {
					required : 'Please enter your password one more time',
					equalTo : 'Please enter the same password as above'
				},
				firstname : {
					required : 'Please select your first name'
				},
				lastname : {
					required : 'Please select your last name'
				},
				gender : {
					required : 'Please select your gender'
				},
				terms : {
					required : 'You must agree with Terms and Conditions'
				}
			},

			// Do not change code below
			errorPlacement : function(error, element) {
				error.insertAfter(element.parent());
			}
		});

		var $reviewForm = $("#review-form").validate({
			// Rules for form validation
			rules : {
				name : {
					required : true
				},
				email : {
					required : true,
					email : true
				},
				review : {
					required : true,
					minlength : 20
				},
				quality : {
					required : true
				},
				reliability : {
					required : true
				},
				overall : {
					required : true
				}
			},

			// Messages for form validation
			messages : {
				name : {
					required : 'Please enter your name'
				},
				email : {
					required : 'Please enter your email address',
					email : '<i class="fa fa-warning"></i><strong>Please enter a VALID email addres</strong>'
				},
				review : {
					required : 'Please enter your review'
				},
				quality : {
					required : 'Please rate quality of the product'
				},
				reliability : {
					required : 'Please rate reliability of the product'
				},
				overall : {
					required : 'Please rate the product'
				}
			},

			// Do not change code below
			errorPlacement : function(error, element) {
				error.insertAfter(element.parent());
			}
		});
		
		var $commentForm = $("#comment-form").validate({
			// Rules for form validation
			rules : {
				name : {
					required : true
				},
				email : {
					required : true,
					email : true
				},
				url : {
					url : true
				},
				comment : {
					required : true
				}
			},

			// Messages for form validation
			messages : {
				name : {
					required : 'Enter your name',
				},
				email : {
					required : 'Enter your email address',
					email : 'Enter a VALID email'
				},
				url : {
					email : 'Enter a VALID url'
				},
				comment : {
					required : 'Please enter your comment'
				}
			},

			// Ajax form submition
			submitHandler : function(form) {
				$(form).ajaxSubmit({
					success : function() {
						$("#comment-form").addClass('submited');
					}
				});
			},

			// Do not change code below
			errorPlacement : function(error, element) {
				error.insertAfter(element.parent());
			}
		});

		var $contactForm = $("#contact-form").validate({
			// Rules for form validation
			rules : {
				name : {
					required : true
				},
				email : {
					required : true,
					email : true
				},
				message : {
					required : true,
					minlength : 10
				}
			},

			// Messages for form validation
			messages : {
				name : {
					required : 'Please enter your name',
				},
				email : {
					required : 'Please enter your email address',
					email : 'Please enter a VALID email address'
				},
				message : {
					required : 'Please enter your message'
				}
			},

			// Ajax form submition
			submitHandler : function(form) {
				$(form).ajaxSubmit({
					success : function() {
						$("#contact-form").addClass('submited');
					}
				});
			},

			// Do not change code below
			errorPlacement : function(error, element) {
				error.insertAfter(element.parent());
			}
		});

		var $orderForm = $("#order-form").validate({
			// Rules for form validation
			rules : {
				name : {
					required : true
				},
				email : {
					required : true,
					email : true
				},
				phone : {
					required : true
				},
				interested : {
					required : true
				},
				budget : {
					required : true
				}
			},

			// Messages for form validation
			messages : {
				name : {
					required : 'Please enter your name'
				},
				email : {
					required : 'Please enter your email address',
					email : 'Please enter a VALID email address'
				},
				phone : {
					required : 'Please enter your phone number'
				},
				interested : {
					required : 'Please select interested service'
				},
				budget : {
					required : 'Please select your budget'
				}
			},

			// Do not change code below
			errorPlacement : function(error, element) {
				error.insertAfter(element.parent());
			}
		});

		// START AND FINISH DATE
		$('#dob').datepicker({
			dateFormat : 'yy-mm-dd',
			prevText : '<i class="fa fa-chevron-left"></i>',
			nextText : '<i class="fa fa-chevron-right"></i>'
		});

		$("#copyTAddress").on("change", function(){
		    if (this.checked) {
		      	$("[name='pAddress']").val($("[name='tAddress']").val());
		    }else{
		    	$("[name='pAddress']").val('');
		    }
		});
		
		// START AND FINISH DATE
		$('#startdate').datepicker({
			dateFormat : 'dd.mm.yy',
			prevText : '<i class="fa fa-chevron-left"></i>',
			nextText : '<i class="fa fa-chevron-right"></i>',
			onSelect : function(selectedDate) {
				$('#finishdate').datepicker('option', 'minDate', selectedDate);
			}
		});
		
		$('#finishdate').datepicker({
			dateFormat : 'dd.mm.yy',
			prevText : '<i class="fa fa-chevron-left"></i>',
			nextText : '<i class="fa fa-chevron-right"></i>',
			onSelect : function(selectedDate) {
				$('#startdate').datepicker('option', 'maxDate', selectedDate);
			}
		});

		
		
	};
	
	// end pagefunction
	
	// Load form valisation dependency 
	loadScript("js/plugin/jquery-form/jquery-form.min.js", pagefunction);

</script>