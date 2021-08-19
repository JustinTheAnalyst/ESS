<?php 
require_once ('inc/PHP/functions.php');
require_once ("inc/init.php");
require_once ("inc/config.ui.php");

$page_title = "Summary Leave Report";

$page_css[] = "your_style.css";
include ("inc/header.php");

$page_nav["Report"]["sub"]["Summary Leave Report"]["active"] = true;
include ("inc/nav.php");
?>
<style type="text/css">
	

</style>

<div id="main" role="main">
<?php
$breadcrumbs["Report"] = "";
include("inc/ribbon.php");
?>
	<div id="content">
		<section id="widget-grid" class="">
			<div class="row">
				<article class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
					<div class="jarviswidget jarviswidget-color-purity" id="wid-id-1" data-widget-editbutton="false">
						<header>				
							<h2>Summary Leave Report</h2>
						</header>
						<div>		
							<div class="widget-body no-padding">
								<form role="form" action="Print_Report.php" target="_blank" method="post" style="padding: 20px;">
						            <fieldset>
										<div class="row" style="margin-top: 10px">    
					                      <div class="col col-lg-3">
					                          <label class="input">Location</label>
					                      </div>
					                      <div class="col col-lg-9">
												<select class="select2" name="loc_id" id="loc_id" onchange="FilterEmployee();" required>
					                         		<option value="" selected="" disabled="">-- Select Location --</option>
					                         		<?php
													$locList = getLocationList();
													
										           	foreach($locList as $loc)
										           	{
										            ?>
								            			<option value="<?php echo $loc['loc_id'];?>" ><?php echo $loc['loc_shortName']." - ".$loc['loc_name']; ?></option>
										            <?php
										           	}
										            ?>
				                          		</select>
					                      	</div>
					                    </div>
			
			                 			<div class="row" style="margin-top: 10px">    
					                      	<div class="col col-lg-3">
					                          	<label class="input">Department</label>
					                      	</div>
					                      	<div class="col col-lg-9">
					                          	<select class="select2" name="dept_id" id="dept_id" onchange="FilterEmployee();">
					                          		<option value="" selected="" disabled="">-- Select Department --</option>
					                          	 	<?php
													$AllDepartments = getActiveDepartments();
													
										        	foreach($AllDepartments as $dept)
										        	{
										            ?>
								            			<option value="<?php echo $dept['dept_id'];?>" ><?php echo $dept['dept_Name'];?></option>
										            <?php
										           	}
										            ?>
				                          		</select>
					                      	</div>
					              		</div>
			
					                    <div class="row" style="margin-top: 10px" id="divEmp">    
					                      	<div class="col col-lg-3">
					                          	<label class="input">Employee</label>
					                      	</div>
					                      	<div class="col col-lg-9">
					                          	<select class="select2" name="user_id" id="user_id" >
					                          		<option value="" selected="" disabled="">-- Select Employee --</option>
					                          	 	
				                          		</select>
					                      	</div>
					             		</div>
					             		<!-- 
					                    <select id="userCopy" style="display: none;">
					            		<?php
										$AllEmployees = getEmployeeListDetail();
									
										foreach($AllEmployees as $user)
										{
										?>
								  			<option class="loc<?php echo $user['loc_id']; ?>dept<?php echo $user['dept_id']; ?>" value="<?php echo $user['user_id'];?>"  style="display: none;">
								  			<?php echo $user['user_FirstName'];?>/ <?php echo $user['user_LastName'];?>
								  			</option>
										<?php
										}
										?>
					                    </select>
					                    -->
					                    <div class="row" style="margin-top: 10px">    
					                      	<div class="col col-lg-3">
					                          	<label class="input">Generate For All Employees </label>
					                      	</div>
					                      	<div class="col col-lg-9">
					                          	<input type="checkbox" name="all_user" id="all_user">
					                      	</div>
					                    </div>
					                    <div class="row pull-right">    
						  					<div class="col col-lg-12">
						          				<input type="submit" name="generateReport" class="btn btn-primary" value="Generate Report">
						          			</div>
						      			</div>
					                </fieldset>
						         </form>
							</div>
						</div>
					</div>
				</article>
			</div>
		</section>
	</div>
</div>

<?php include ("inc/footer.php"); ?>

<?php include ("inc/scripts.php"); ?>

<script type="text/javascript">
$(document).ready(function(){
    $('#loc_id').on('change',function(){
        
        var locID = $(this).val();
        
        if(locID){
            $.ajax({
                type:'POST',
                url:'ajax/getEmployeeByLocation.php',
                data:'loc_id='+locID,
                success:function(html){
                    $('#user_id').html(html);
                }
            }); 
        }else{
            $('#user_id').html('<option value="">-- Select a Location First --</option>');
        }
    });
    
    $('#dept_id').on('change',function(){
        var deptID = $(this).val();
        var locID = $('#loc_id').val();
		
        if(locID=''){
	        if(deptID){
	            $.ajax({
	                type:'POST',
	                url:'ajax/getEmployeeListing.php',
	                data:'dept_id='+deptID,
	                success:function(html){
	                    $('#user_id').html(html);
	                }
	            });
	        }else{
	            $('#user_id').html('<option value="">-- Select a Department First --</option>');
	        } 
        }else{
        	$.ajax({
                type:'POST',
                url:'ajax/getEmployeeListing.php',
                data:'loc_id='+locID+'&dept_id='+deptID,
                success:function(html){
                    $('#user_id').html(html);
                }
            });
        }
    });

    $('#all_user').on('change',function(){
    	if ($('#all_user').is(':checked')){
    		$('#divEmp').fadeOut('slow');
    	}
    	else{
    		$('#divEmp').fadeIn('slow');
    	}
        	
    });
});
</script>

<?php
include ("inc/google-analytics.php");
?>