<?php 
require_once ("inc/init.php");
include('inc/PHP/functions.php');
require_once ("inc/config.ui.php");

$page_title = "Leave Allocation";

if (isset($_POST['submit']))
{
	// process non annual leave allocation
	for ($i=0; $i <count($_POST['lt_id']) ; $i++)
	{
		allocateOtherLeaves($_POST['lt_id'][$i], $_POST['other_leaves'][$i], $_POST['leaveAllocationyear']);
	}

	// process annual leave allocation
	for ($i=0; $i <count($_POST['lt_id2']) ; $i++)
	{
		allocateAnnualLeaves($_POST['desig_id'][$i],$_POST['lt_id2'][$i], $_POST['annual_leaves'][$i], $_POST['leaveAllocationyear']);
	}
}

include ("inc/header.php");

$page_nav["Configuration"]['sub']['Leave Allocation']["active"] = true;
include ("inc/nav.php");
?>
<style type="text/css">
form label {
	font-weight:bold
}
</style>

<!-- MAIN PANEL -->
<div id="main" role="main">
<?php
$breadcrumbs["Leave Allocation"] = "";
include("inc/ribbon.php");
?>
	<div id="content">
		<section id="widget-grid">
			<div class="jarviswidget" id="wid-id-3" data-widget-fullscreenbutton="false" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-custombutton="false">
				<header>
					<span class="widget-icon"> <i class="fa fa-edit"></i> </span>
					<h2>Leave Allocation Form </h2>				
				</header>
				
				<div>
					<div class="jarviswidget-editbox">
						<!-- This area used as dropdown edit box -->
						
					</div>
					
					<div class="widget-body no-padding">
						<form method="post" action="" class="smart-form">
							<fieldset>
								<div class="row">
									<section class="col col-6">
										<div class="form-group">
											<label>Year</label>
											<select name="leaveAllocationyear" class="select2">
												<option value="0" selected="" disabled="">-- Select Year --</option>
												<?php 
												for ($i=2014; $i <= date('Y') ; $i++) 
												{ 
													$currentYear = date("Y");
													
													if ($currentYear == $i)
													{
														$selected = "selected='selected'";
													}
													else 
													{
														$selected = "";
													}
													
													echo '<option '.$selected.'>'.$i.'</option>';
												}
												?>
											</select>
										</div>
									</section>
								</div>
							</fieldset>
							
							<header>
								Other Leaves Allocation
							</header>

							<fieldset>
								<div class="row">
								<?php 
								$leaveList = getLeaveTypeList();
										
								for ($i=0; $i <count($leaveList) ; $i++) 
								{ 
									if($leaveList[$i]['lt_Annual']!='Y')
									{
								?>
									<section class="col col-6">
										<div class="form-group">
									    	<label for="<?php echo $leaveList[$i]['lt_Name'] ?>"><?php echo $leaveList[$i]['lt_Name'] ?></label>
									        <input type="hidden" name="lt_id[]" value="<?php echo  $leaveList[$i]['lt_id'] ?>">
											<input type="number" name="other_leaves[]" class="form-control" id="">
								     	</div>
								     </section>
								<?php
									}
								}
								?>
								</div>
							</fieldset>

							<header>
								Annual Leaves Allocation
							</header>
							
							<fieldset>
								<div class="row">
								<?php 
								$sysInfo = getSysSettings();
								$default_no_AL = $sysInfo['sys_annual_leave'];
								
								$leaveList = getLeaveTypeList();
								$getDesignationList = getDesignationList();
								
								for ($i=0; $i <count($leaveList) ; $i++) 
								{ 
									if($leaveList[$i]['lt_Annual']=='Y')
									{
										for ($j=0; $j <count($getDesignationList) ; $j++) 
										{ 
								?>
											<section class="col col-6">
												<div class="form-group">
											    	<label for="<?php echo $getDesignationList[$j]['desig_Name'] ?>"><?php echo $getDesignationList[$j]['desig_Name'] ?></label>
											        <input type="hidden" name="desig_id[]" value="<?php echo  $getDesignationList[$j]['desig_id'] ?>">
									              	<input type="hidden" name="lt_id2[]" value="<?php echo  $leaveList[$i]['lt_id'] ?>">
									              	<input type="number" name="annual_leaves[]" class="form-control" id="" value="<?php echo $default_no_AL; ?>">
										     	</div>
										     </section>
								<?php
										}
									}
								}
								?>
								</div>
							</fieldset>
							
							<footer>
								<input type="submit" class="btn btn-primary pull-right" name="submit">
							</footer>
						</form>
					</div>
				</div>
			</div>
		</section>
	</div>
</div>

<?php include ("inc/footer.php"); ?>

<?php include ("inc/scripts.php"); ?>

<script type="text/javascript" src="inc/js/custom.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	
});

</script>
<?php
//include footer
include ("inc/google-analytics.php");
?>