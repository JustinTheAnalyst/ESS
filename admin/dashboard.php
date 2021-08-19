<?php
include('inc/PHP/functions.php');
//initilize the page
require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Dashboard";

/* ---------------- END PHP Custom Scripts ------------- */

//include header

include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["Dashboard"]["active"] = true;
include("inc/nav.php");

?>
<?php include('connection.php'); ?>
<style type="text/css">
	#widget-grid-icons{

	}
	#widget-grid-icons .items{

		padding: 10px;
		background: white;
		margin-bottom: 10px;

	}

	#widget-grid-icons .items p{
		margin-top: 10px;
	}

	    
	#widget-grid-icons .items .icon-class{
		    border-radius: 50%;
		    text-align: center;
		    padding: 10px 0px;
		    height: 60px;
		    font-size: 25px;
		    color: white;
		    width: 60px;
		

	}

	span.info-box-number {
    font-size: 20px;
}

.jarviswidget-editbox .widget-body > ul{

	list-style-type: none;
}

</style>
<link rel="stylesheet" type="text/css" href="css/customstyle.css">
<!-- ==========================CONTENT STARTS HERE ========================== -->

		<!-- MAIN PANEL -->
		<div id="main" role="main">

			<?php
				//configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
				//$breadcrumbs["New Crumb"] => "http://url.com"
				//$breadcrumbs["Dashboard"] = "";
				include("inc/ribbon.php");
			?>
			<!-- MAIN CONTENT -->
			<div id="content">
				<section id="widget-grid-icons" class="">
					<div class="row">
						<div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
						<?php 
						global $DB;
						$sql = "SELECT COUNT(user_id) AS Employees FROM u_user;";
						$stmt =  $DB->prepare($sql);
						$stmt->execute();
						$row = $stmt->fetch(PDO::FETCH_ASSOC);
						
						$value = $row['Employees'];
						$label = "Total Employees";
						$icon = 'fa fa-users';
						
						$rand = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f');
						$background = '#'.$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)];
						?>
							<div class="col-xs-12 items">
								<div class="col-xs-5">
									<div class="icon-class item-employee" style="background-color:<?php echo $background ?>;">
										<i class="<?php echo $icon ?>"></i>
									</div>
								</div>
								
								<div class="col-xs-7">
									<div class="">
										  <p><b><span class="info-box-number"><?php echo $value ?></span></b></p>
						                  <p><span class="info-box-text"><?php echo $label ?></span></p>
						            </div><!-- /.info-box-content -->
								</div>
							</div>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
						<?php
						global $DB;
						$sql = "SELECT COUNT(dept_id) AS Department FROM cnf_dept;";
						$stmt =  $DB->prepare($sql);
						$stmt->execute();
						$row = $stmt->fetch(PDO::FETCH_ASSOC);
						
						$value = $row['Department'];
						$label = "Department";
						$icon = 'fa fa-bank';
						$rand = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f');
						$background = '#'.$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)];
						?>
							<div class="col-xs-12 items">
								<div class="col-xs-5">
									<div class="icon-class item-employee" style="background-color:<?php echo $background ?>;">
										<i class="<?php echo $icon ?>"></i>
									</div>
								</div>
								
								<div class="col-xs-7">
									<div class="">
										  <p><b><span class="info-box-number"><?php echo $value ?></span></b></p>
						                  <p><span class="info-box-text"><?php echo $label ?></span></p>
						            </div><!-- /.info-box-content -->
								</div>
							</div>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
						<?php 
						global $DB;
						$sql = "SELECT COUNT(desig_id) AS Designation FROM cnf_designation;";
						$stmt =  $DB->prepare($sql);
						$stmt->execute();
						$row = $stmt->fetch(PDO::FETCH_ASSOC);
						
						$value = $row['Designation'];
						$label = "Designation";
						$icon = 'fa fa-fw fa-tasks';
						$rand = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f');
						$background = '#'.$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)];
						?>
							<div class="col-xs-12 items">
								<div class="col-xs-5">
									<div class="icon-class item-employee" style="background-color:<?php echo $background ?>;">
										<i class="<?php echo $icon ?>"></i>
									</div>
								</div>
								
								<div class="col-xs-7">
									<div class="">
										  <p><b><span class="info-box-number"><?php echo $value ?></span></b></p>
						                  <p><span class="info-box-text"><?php echo $label ?></span></p>
						            </div><!-- /.info-box-content -->
								</div>
							</div>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
						<?php 
						global $DB;
						$sql = "SELECT COUNT(la_id) AS Leaves, la_Status
								FROM u_leaveapplication 
								WHERE DATE(la_Date) = DATE(NOW()) AND la_Status='A';";
						$stmt =  $DB->prepare($sql);
						$stmt->execute();
						$row = $stmt->fetch(PDO::FETCH_ASSOC);
						
						$value = $row['Leaves'];
						$label = "Leaves";
						$icon = 'fa fa-user';
						$rand = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f');
						$background = '#'.$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)];
						?>
							<div class="col-xs-12 items">
								<div class="col-xs-5">
									<div class="icon-class item-employee" style="background-color:<?php echo $background ?>;">
										<i class="<?php echo $icon ?>"></i>
									</div>
								</div>
								
								<div class="col-xs-7">
									<div class="">
										  <p><b><span class="info-box-number"><?php echo $value ?></span></b></p>
						                  <p><span class="info-box-text"><?php echo $label ?></span></p>
						            </div><!-- /.info-box-content -->
								</div>
							</div>
						</div>
					</div>
				</section>
				
				<section id="widget-grid" class="">
					<div class="row">
						<article class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
							<div class="jarviswidget jarviswidget-color-blueDark" data-widget-attstyle="jarviswidget-color-blueDark" id="wid-id-6" data-widget-colorbutton="false" data-widget-editbutton="false">
								<header>
									<h2>Role Wise Employees</h2>				
								</header>

								<div>
									<div class="jarviswidget-editbox">
										<input class="form-control" type="text">	
									</div>
									
									<div class="widget-body">
										<script type="text/javascript">
										var pieData = [
											<?php
											$sqlTypeWiseEmployees = "SELECT 'Manager', COUNT(user_id) FROM u_user WHERE user_Type = 'M'
																	 UNION ALL 
																	 SELECT 'Employee', COUNT(user_id) FROM u_user WHERE user_Type = 'E'
																	 UNION ALL
																	 SELECT 'Clerk', COUNT(user_id) FROM u_user WHERE user_Type = 'C'
																	 UNION ALL
																	 SELECT 'Top Management', COUNT(user_id) FROM u_user WHERE user_Type = 'T'";
											
											$sqlGetChart = mysqli_query($con, $sqlTypeWiseEmployees);
																	
										  	$number=0;
										  	$label=array();
										  	$numRows = mysqli_num_rows($sqlGetChart);
											while($rowGetChart=mysqli_fetch_array($sqlGetChart))
											{
												$label[$number]=$rowGetChart[0];
												$values[$number]=$rowGetChart[1];
												$rand = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f');
									    		$color[$number] = '#'.$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)]; 
											
												echo '
										                {
										                        value: '.$values[$number].',
										                        color: "'.$color[$number].'",
										                        highlight: "'.$color[$number].'",
										                        label: "'.$label[$number].'"

										                 } ';

												if($number!=$numRows ) 
												{ 
													echo ','; 
												}

												$number++;

											}
											?>
										];
										</script>
										<ul style="list-style-type: none; padding-bottom:36px;">
										<?php 
					                   	for ($i=0; $i <$numRows ; $i++) 
					                   	{ 
					                   	?>
					                   		<li style="float:left; padding-right:10px;"><span class="glyphicon glyphicon-stop" style="color:<?php echo $color[$i] ?>"></span><?php echo $label[$i] ?></li>
					                  	<?php
					                 	}
					                   	?>
					                   	</ul>
					                   	<canvas id="pieChart" height="120"></canvas>
									</div>
								</div>
							</div>
						</article>

						<article class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
							<div class="jarviswidget jarviswidget-color-blueDark" data-widget-attstyle="jarviswidget-color-blueDark" id="wid-id-4" data-widget-colorbutton="false" data-widget-editbutton="false">
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

									<h2> Gender Wise Employees </h2>	
									
								</header>

								<!-- widget div-->
								<div>
									
									<!-- widget edit box -->
									<div class="jarviswidget-editbox">
										<!-- This area used as dropdown edit box -->
										<input class="form-control" type="text">	
									</div>
									<!-- end widget edit box -->
									
									<!-- widget content -->
									<div class="widget-body">
										<script type="text/javascript">
											var doughnutData = [
											      <?php

												  $sqlTypeWiseEmployees = "SELECT 'Male' , COUNT(user_id)  FROM u_user WHERE user_Gender = 'M'
																				UNION ALL 
																			SELECT 'Female' , COUNT(user_id) FROM u_user WHERE user_Gender = 'F'";
																	$sqlGetChart = mysqli_query($con, $sqlTypeWiseEmployees);
																	
												  $number=0;
												  $label=array();
												  $numRows = mysqli_num_rows($sqlGetChart);
												   while($rowGetChart=mysqli_fetch_array($sqlGetChart)){


												     $label[$number]=$rowGetChart[0];
												     $values[$number]=$rowGetChart[1];
												     $rand = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f');
									    		$color[$number] = '#'.$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)]; 
												     
												      ?>


														      <?php

														      

														      echo '
														                {
														                        value: '.$values[$number].',
														                        color: "'.$color[$number].'",
														                        highlight: "'.$color[$number].'",
														                        label: "'.$label[$number].'"

														                      } 
														                ';

														                if($number!=$numRows ) { echo ','; }

														                $number++;

														              }
														      # code...
														   

														    ?>
												    ];
										</script>
										<ul style="list-style-type: none; padding-bottom:36px;">
										<?php 
					                  	for ($i=0; $i <$numRows ; $i++) 
					                  	{ 
					                	?>

					                            <li style="float:left; padding-right:10px;"><span class="glyphicon glyphicon-stop" style="color:<?php echo $color[$i] ?>"></span><?php echo $label[$i] ?></li>

					                      <?php
					                     }
					                   ?>
					                   </ul>
                        
										<canvas id="doughnutChart" height="120"></canvas>

									</div>
									<!-- end widget content -->
									
								</div>
								<!-- end widget div -->
								
							</div>
						</article>
					</div>
				</section>
				
				<section id="widget-grid" class="">
					<div class="row">
						<article class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
							<div class="jarviswidget jarviswidget-color-blueDark" data-widget-attstyle="jarviswidget-color-blueDark" id="wid-id-7" data-widget-colorbutton="false" data-widget-editbutton="false">
								<header>
									<h2>MIS Leaves</h2>				
								</header>

								<!-- widget div-->
								<div>
									
									<!-- widget edit box -->
									<div class="jarviswidget-editbox">
										<!-- This area used as dropdown edit box -->
										<input class="form-control" type="text">	
									</div>
									<!-- end widget edit box -->
									
									<!-- widget content -->
									<div class="widget-body">
									<?php
									$today = date('D/m/Y');
									$listdata = array();
									$dataset = array();
									$dataset[0] = NULL;
									$dataset[1] = NULL;
									$fieldNames = array();
									$number=1;
									for($k=0; $k <7 ; $k++) 
									{ 
									$fieldNames = NULL;
									$afterWeek = date('y-m-d' , strtotime('+'.($k).' days'));
									
									$sqlLeave  = "SELECT COUNT(u_leaveapplication.la_id) AS Leaves FROM u_leaveapplication 
									  				LEFT JOIN u_usercompany ON u_leaveapplication.user_id = u_usercompany.user_id 
									  				WHERE u_leaveapplication.la_Status = 'A' AND u_leaveapplication.la_Date = '$afterWeek' AND u_usercompany.loc_id = 1 ;"; 
									$sqlGetChart = mysqli_query($con, $sqlLeave);
									$rowGetChart=mysqli_fetch_assoc($sqlGetChart);

									while($field=mysqli_fetch_field($sqlGetChart))
									{
						                 $fieldNames[]= $field->name;
						           	}
						           	
									$holiday_Date = $afterWeek;
						         	$holidaLabel = "";
						           	$sqlHoliday = "SELECT holiday_id FROM cnf_holiday WHERE holiday_Date = '$holiday_Date';";
						          	$queryHoliday = mysqli_query($con, $sqlHoliday);
						               
						          	if(mysqli_num_rows($queryHoliday) > 0)
						          	{
						          		$holidaLabel = ' (Holiday) ';
						          	}
						               	
								                $dataset[1].='"'.$rowGetChart[$fieldNames[0]];
								               	$dataset[1].='"';
								               	$dataset[0].= '"'.date('d/m/Y' , strtotime('+'.($k).' days')).$holidaLabel.'"';
								                 if($number!=7){
								                  $dataset[0].=",";
								                  $dataset[1].=",";

								                  
								                } 
											

						            	$number++;
						            }
						            		for ($i=0; $i < count($dataset); $i++){


									               	 $rand = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f');
									    $color[$i] = '#'.$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)]; 
									             }
											for ($i=0; $i < count($dataset); $i++) { 

									              $dataset[$i];
									              if(!empty( $dataset[$i])){
									              $listdata[$i]='{
									              label: "Electronics",
									              fillColor: "'.$color[$i].'",
									              strokeColor: "'.$color[$i].'",
									              pointColor: "'.$color[$i].'",
									              pointStrokeColor: "'.$color[$i].'",
									              pointHighlightFill: "'.$color[$i].'",
									              pointHighlightStroke: "'.$color[$i].'",
									              data: ['.$dataset[$i].']
									            }' ;

									      		 }
									              # code...
									            }

									$putdata="";

									for ( $i = 1; $i < count($listdata); $i++) {
									          	 $putdata.=$listdata[$i];
									          	if($i+1!=count($listdata)){
									          			 $putdata.=',';

									          	}
									          };
						          ?>
						          		<script type="text/javascript">
						          			
						          			   var barData1 = {
										          labels: [<?php echo $dataset[0] ?>],
										          datasets: [
										          <?php  
										          echo $putdata;
										           ?>
										            
										          ]
										        };

						          		</script>
										<ul class="chart-legend clearfix" style="list-style:none">
					                  	
										<?php 
					                     for ($i=1; $i <count($fieldNames) ; $i++) { 
					                      ?>

					                            <li><span class="glyphicon glyphicon-stop" style="color:<?php echo $color[$i] ?>"></span> <?php echo $fieldNames[$i] ?></li>

					                      <?php
					                     }
					                   ?>
					                  
					                        
					                      </ul>
										<!-- this is what the user will see -->
										
											<canvas id="barChart1"></canvas>
										

									</div>
									<!-- end widget content -->
									
								</div>
								<!-- end widget div -->
								
							</div>
							<!-- end widget -->
						</article>
						
						<article class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
							<div class="jarviswidget jarviswidget-color-blueDark" data-widget-attstyle="jarviswidget-color-blueDark" id="wid-id-8" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-sortable="false">
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

									<h2>JIS Leaves</h2>				
									
								</header>

								<!-- widget div-->
								<div>
									
									<!-- widget edit box -->
									<div class="jarviswidget-editbox">
										<!-- This area used as dropdown edit box -->
										<input class="form-control" type="text">	
									</div>
									<!-- end widget edit box -->
									
									<!-- widget content -->
									<div class="widget-body">
										
									<?php

								$today = date('D/m/Y');
								$listdata = array();
								$dataset = array();
								$dataset[0] = NULL;
								$dataset[1] = NULL;
								$fieldNames = array();
								$number=1;
								for($k=0; $k <7 ; $k++) { 

									$fieldNames = NULL;
									$afterWeek = date('y-m-d' , strtotime('+'.($k).' days'));
									  $sqlLeave  = "SELECT COUNT(u_leaveapplication.la_id) AS Leaves FROM u_leaveapplication 
									  					LEFT JOIN u_usercompany ON u_leaveapplication.user_id = u_usercompany.user_id 
									  					WHERE u_leaveapplication.la_Status = 'A' AND u_leaveapplication.la_Date = '$afterWeek'AND u_usercompany.loc_id = 2 ;"; 
								$sqlGetChart = mysqli_query($con, $sqlLeave);
								$rowGetChart=mysqli_fetch_assoc($sqlGetChart);

								while($field=mysqli_fetch_field($sqlGetChart)){

						                 $fieldNames[]= $field->name;

						                }
									 $holiday_Date = $afterWeek;
						               $holidaLabel = "";
						               $sqlHoliday = "SELECT holiday_id FROM cnf_holiday WHERE holiday_Date = '$holiday_Date';";
						               $queryHoliday = mysqli_query($con, $sqlHoliday);
						               if(mysqli_num_rows($queryHoliday) > 0){

						               		$holidaLabel = ' (Holiday) ';
						               }
						               	
								                $dataset[1].='"'.$rowGetChart[$fieldNames[0]];
								               	$dataset[1].='"';
								               	$dataset[0].= '"'.date('d/m/Y' , strtotime('+'.($k).' days')).$holidaLabel.'"';
								                 if($number!=7){
								                  $dataset[0].=",";
								                  $dataset[1].=",";

								                  
								                } 
											

						            	$number++;
						            }
						            		for ($i=0; $i < count($dataset); $i++){


									               	 $rand = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f');
									    $color[$i] = '#'.$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)]; 
									             }
											for ($i=0; $i < count($dataset); $i++) { 

									              $dataset[$i];
									              if(!empty( $dataset[$i])){
									              $listdata[$i]='{
									              label: "Electronics",
									              fillColor: "'.$color[$i].'",
									              strokeColor: "'.$color[$i].'",
									              pointColor: "'.$color[$i].'",
									              pointStrokeColor: "'.$color[$i].'",
									              pointHighlightFill: "'.$color[$i].'",
									              pointHighlightStroke: "'.$color[$i].'",
									              data: ['.$dataset[$i].']
									            }' ;

									      		 }
									              # code...
									            }

									$putdata="";

									for ( $i = 1; $i < count($listdata); $i++) {
									          	 $putdata.=$listdata[$i];
									          	if($i+1!=count($listdata)){
									          			 $putdata.=',';

									          	}
									          };
						          ?>
						          		<script type="text/javascript">
						          			
						          			   var barData2 = {
										          labels: [<?php echo $dataset[0] ?>],
										          datasets: [
										          <?php  
										          echo $putdata;
										           ?>
										            
										          ]
										        };

						          		</script>
										<ul class="chart-legend clearfix" style="list-style:none">
					                  	
										<?php 
					                     for ($i=1; $i <count($fieldNames) ; $i++) { 
					                      ?>

					                            <li><span class="glyphicon glyphicon-stop" style="color:<?php echo $color[$i] ?>"></span> <?php echo $fieldNames[$i] ?></li>

					                      <?php
					                     }
					                   ?>
					                  
					                        
					                      </ul>
										<!-- this is what the user will see -->
										
											<canvas id="barChart2"></canvas>
										

									</div>
									<!-- end widget content -->
									
								</div>
								<!-- end widget div -->
								
							</div>
							<!-- end widget -->
						</article>

						<article class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
							<div class="jarviswidget jarviswidget-color-blueDark" data-widget-attstyle="jarviswidget-color-blueDark" id="wid-id-9" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-sortable="false">
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

									<h2>BPP Leaves</h2>				
									
								</header>

								<!-- widget div-->
								<div>
									
									<!-- widget edit box -->
									<div class="jarviswidget-editbox">
										<!-- This area used as dropdown edit box -->
										<input class="form-control" type="text">	
									</div>
									<!-- end widget edit box -->
									
									<!-- widget content -->
									<div class="widget-body">
										
								<?php

								$today = date('D/m/Y');
								$listdata = array();
								$dataset = array();
								$dataset[0] = NULL;
								$dataset[1] = NULL;
								$fieldNames = array();
								$number=1;
								for($k=0; $k <7 ; $k++) { 

									$fieldNames = NULL;
									$afterWeek = date('y-m-d' , strtotime('+'.($k).' days'));
									  $sqlLeave  = "SELECT COUNT(u_leaveapplication.la_id) AS Leaves FROM u_leaveapplication 
									  					LEFT JOIN u_usercompany ON u_leaveapplication.user_id = u_usercompany.user_id 
									 			 		WHERE u_leaveapplication.la_Status = 'A' AND u_leaveapplication.la_Date = '$afterWeek'AND u_usercompany.loc_id = 3 ;"; 
								$sqlGetChart = mysqli_query($con, $sqlLeave);
								$rowGetChart=mysqli_fetch_assoc($sqlGetChart);

								while($field=mysqli_fetch_field($sqlGetChart)){

						                 $fieldNames[]= $field->name;

						                }
									 $holiday_Date = $afterWeek;
						               $holidaLabel = "";
						               $sqlHoliday = "SELECT holiday_id FROM cnf_holiday WHERE holiday_Date = '$holiday_Date';";
						               $queryHoliday = mysqli_query($con, $sqlHoliday);
						               if(mysqli_num_rows($queryHoliday) > 0){

						               		$holidaLabel = ' (Holiday) ';
						               }
						               	
								                $dataset[1].='"'.$rowGetChart[$fieldNames[0]];
								               	$dataset[1].='"';
								               	$dataset[0].= '"'.date('d/m/Y' , strtotime('+'.($k).' days')).$holidaLabel.'"';
								                 if($number!=7){
								                  $dataset[0].=",";
								                  $dataset[1].=",";

								                  
								                } 
											

						            	$number++;
						            }
						            		for ($i=0; $i < count($dataset); $i++){


									               	 $rand = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f');
									    $color[$i] = '#'.$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)]; 
									             }
											for ($i=0; $i < count($dataset); $i++) { 

									              $dataset[$i];
									              if(!empty( $dataset[$i])){
									              $listdata[$i]='{
									              label: "Electronics",
									              fillColor: "'.$color[$i].'",
									              strokeColor: "'.$color[$i].'",
									              pointColor: "'.$color[$i].'",
									              pointStrokeColor: "'.$color[$i].'",
									              pointHighlightFill: "'.$color[$i].'",
									              pointHighlightStroke: "'.$color[$i].'",
									              data: ['.$dataset[$i].']
									            }' ;

									      		 }
									              # code...
									            }

									$putdata="";

									for ( $i = 1; $i < count($listdata); $i++) {
									          	 $putdata.=$listdata[$i];
									          	if($i+1!=count($listdata)){
									          			 $putdata.=',';

									          	}
									          };
						          ?>
						          		<script type="text/javascript">
						          			
						          			   var barData3 = {
										          labels: [<?php echo $dataset[0] ?>],
										          datasets: [
										          <?php  
										          echo $putdata;
										           ?>
										            
										          ]
										        };

						          		</script>
										<ul class="chart-legend clearfix" style="list-style:none">
					                  	
										<?php 
					                     for ($i=1; $i <count($fieldNames) ; $i++) { 
					                      ?>

					                            <li><span class="glyphicon glyphicon-stop" style="color:<?php echo $color[$i] ?>"></span> <?php echo $fieldNames[$i] ?></li>

					                      <?php
					                     }
					                   ?>
					                  
					                        
					                      </ul>
										<!-- this is what the user will see -->
										
											<canvas id="barChart3"></canvas>
										

									</div>
									<!-- end widget content -->
									
								</div>
								<!-- end widget div -->
								
							</div>
							<!-- end widget -->
						</article>
						
						<article class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
						<!-- Widget ID (each widget will need unique ID)-->
							<div class="jarviswidget jarviswidget-color-blueDark" data-widget-attstyle="jarviswidget-color-blueDark" id="wid-id-10" data-widget-colorbutton="false" data-widget-editbutton="false">
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

									<h2>IIC Leaves</h2>				
									
								</header>

								<!-- widget div-->
								<div>
									
									<!-- widget edit box -->
									<div class="jarviswidget-editbox">
										<!-- This area used as dropdown edit box -->
										<input class="form-control" type="text">	
									</div>
									<!-- end widget edit box -->
									
									<!-- widget content -->
									<div class="widget-body">
										
								<?php

								$today = date('D/m/Y');
								$listdata = array();
								$dataset = array();
								$dataset[0] = NULL;
								$dataset[1] = NULL;
								$fieldNames = array();
								$number=1;
								for($k=0; $k <7 ; $k++) { 

									$fieldNames = NULL;
									$afterWeek = date('y-m-d' , strtotime('+'.($k).' days'));
									  $sqlLeave  = "SELECT COUNT(u_leaveapplication.la_id) AS Leaves FROM u_leaveapplication 
									  					LEFT JOIN u_usercompany ON u_leaveapplication.user_id = u_usercompany.user_id 
									  					WHERE u_leaveapplication.la_Status = 'A' AND u_leaveapplication.la_Date = '$afterWeek'AND u_usercompany.loc_id = 4 ;"; 
								$sqlGetChart = mysqli_query($con, $sqlLeave);
								$rowGetChart=mysqli_fetch_assoc($sqlGetChart);

								while($field=mysqli_fetch_field($sqlGetChart)){

						                 $fieldNames[]= $field->name;

						                }
									 $holiday_Date = $afterWeek;
						               $holidaLabel = "";
						               $sqlHoliday = "SELECT holiday_id FROM cnf_holiday WHERE holiday_Date = '$holiday_Date';";
						               $queryHoliday = mysqli_query($con, $sqlHoliday);
						               if(mysqli_num_rows($queryHoliday) > 0){

						               		$holidaLabel = ' (Holiday) ';
						               }
						               	
								                $dataset[1].='"'.$rowGetChart[$fieldNames[0]];
								               	$dataset[1].='"';
								               	$dataset[0].= '"'.date('d/m/Y' , strtotime('+'.($k).' days')).$holidaLabel.'"';
								                 if($number!=7){
								                  $dataset[0].=",";
								                  $dataset[1].=",";

								                  
								                } 
											

						            	$number++;
						            }
						            		for ($i=0; $i < count($dataset); $i++){


									               	 $rand = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f');
									    $color[$i] = '#'.$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)]; 
									             }
											for ($i=0; $i < count($dataset); $i++) { 

									              $dataset[$i];
									              if(!empty( $dataset[$i])){
									              $listdata[$i]='{
									              label: "Electronics",
									              fillColor: "'.$color[$i].'",
									              strokeColor: "'.$color[$i].'",
									              pointColor: "'.$color[$i].'",
									              pointStrokeColor: "'.$color[$i].'",
									              pointHighlightFill: "'.$color[$i].'",
									              pointHighlightStroke: "'.$color[$i].'",
									              data: ['.$dataset[$i].']
									            }' ;

									      		 }
									              # code...
									            }

									$putdata="";

									for ( $i = 1; $i < count($listdata); $i++) {
									          	 $putdata.=$listdata[$i];
									          	if($i+1!=count($listdata)){
									          			 $putdata.=',';

									          	}
									          };
						          ?>
						          		<script type="text/javascript">
						          			
						          			   var barData4 = {
										          labels: [<?php echo $dataset[0] ?>],
										          datasets: [
										          <?php  
										          echo $putdata;
										           ?>
										            
										          ]
										        };

						          		</script>
										<ul class="chart-legend clearfix" style="list-style:none">
										<?php 
					                    for ($i=1; $i <count($fieldNames) ; $i++) { 
					                    ?>
					                    	<li><span class="glyphicon glyphicon-stop" style="color:<?php echo $color[$i] ?>"></span> <?php echo $fieldNames[$i] ?></li>
					                      <?php
					                     }
					                   	?>
					                    </ul>
										<!-- this is what the user will see -->
										<canvas id="barChart4"></canvas>
									</div>
								</div>
							</div>
						</article>
					</div>
				</section>
				
				<section id="widget-grid" class="">
					<div class="row">
						<article class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
							<div class="jarviswidget jarviswidget-color-blueDark" data-widget-attstyle="jarviswidget-color-blueDark" id="wid-id-1" data-widget-colorbutton="false" data-widget-editbutton="false">
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
								<header role="heading">
									<span class="widget-icon"> <i class="fa fa-calendar"></i> </span>
									<h2> Leave Calendar </h2>
									<span class="jarviswidget-loader">
										<i class="fa fa-refresh fa-spin"></i>
									</span>
								</header>
								
								<div>		
									<!-- widget content -->
									<div class="widget-body no-padding">
									<?php 
									$user_id = $_SESSION['user_id'];
									//WRITE JSON FILE TO READY FOR CALENDAR
									
									global $DB;
									$sql = "SELECT * FROM u_leavebatch AS LB
											INNER JOIN u_user ON u_user.user_id=LB.user_id
											INNER JOIN cnf_leavetype ON cnf_leavetype.lt_id=LB.lt_id
											WHERE 1 AND LB.lb_Status=?"; 
					
									$stmt = $DB->prepare($sql);
									$stmt->bindValue(1,'P');
									$stmt->execute();
									$posts = array();
									
									if($stmt->rowCount())
									{
										while($row = $stmt->fetch(PDO::FETCH_ASSOC))
										{
											$leaveID = str_pad($row['lb_id'], 6, '0', STR_PAD_LEFT);
											$title = "#".$leaveID; 
											$start = $row['lb_FromDate']; 
											$end = $row['lb_ToDate'];
											$color="#5184c1";
											$description = $row['lb_Reason'];
											$applicant = $row['user_FirstName'];
											
											$posts[] = array('title'=> $title, 'start'=> $start, 'end'=> $end, 'color'=>$color, 'description'=>$description, 'applicant'=>$applicant);
										}
									}
					
									/************* OFF DAY (WEEKEND)*****************/
									$offDayQ = "SELECT * FROM cnf_workingday 
												INNER JOIN cnf_day ON cnf_day.day_id=cnf_workingday.day_id
												WHERE cnf_workingday.wd_On=?";
									$stmt = $DB->prepare($offDayQ);
									$stmt->bindValue(1,'N');
									$stmt->execute();
									
									if($stmt->rowCount())
									{
										while($offDayRow=$stmt->fetch(PDO::FETCH_ASSOC))
										{
											$today = date('Y-m-d');
											//Create array for next 5 OFF OFF-DAYS.
											for($i=1;$i<=5;$i++)
											{
												$nextOffDate=strtotime('next '.$offDayRow['day_Name'], strtotime($today));
									 			$today = date('Y-m-d',$nextOffDate);
									 			$title='Weekend'; 
												$start=$today; 
												$color='#5fba7d';
												$posts[] = array('title'=> $title, 'start'=> $start, 'color'=>$color);
											}
										}
									}
									/*************END OF OFF DAY (WEEKEND)*****************/
									
									/*************EVENT HOLIDAY*****************/
									$holidayQ = "SELECT * FROM cnf_holiday WHERE holiday_Status=?";
									$stmt = $DB->prepare($holidayQ);
									$stmt->bindValue(1,'A');
									$stmt->execute();
									
									if($stmt->rowCount())
									{
										while($holidayRow=$stmt->fetch(PDO::FETCH_ASSOC))
										{
											$posts[] = array('title'=> $holidayRow['holiday_Title'], 'start'=> $holidayRow['holiday_Date'], 'color'=>'#739e73');
										}
									}
									/*************END OF EVENT HOLIDAY*****************/
									
									$fp = fopen('json/leaveCalendar.json', 'w');
									fwrite($fp, json_encode($posts));
									fclose($fp);
									//END OF WRITE JSON FILE
					
									?>
										<div class="row pull-right" style="padding:10px;">
											<div class="col-md-12" style="text-align:center;"> 
												<div style="width:100px;background:#5184c1;float: left;color: white;padding: 5px; margin-right:5px;">Approved</div> 
												<div style="width:100px;background:#739e73;float: left;color: white;padding: 5px;">PH</div>
										    </div>
										</div>
										<div class="row" style="padding:50px 10px 10px 10px;">
											<div id='loading'>loading...</div>
					
											<div id='calendar'></div>
										</div>
									</div>
								</div>
							</div>
						</article>
						
						<article class="col-xs-12 col-sm-12 col-md-12 col-lg-4">
							<div class="jarviswidget jarviswidget-color-blueDark" data-widget-attstyle="jarviswidget-color-blueDark" id="wid-id-2" data-widget-colorbutton="false" data-widget-editbutton="false">
								<header>
									<span class="widget-icon"> <i class="fa fa-bullhorn"></i> </span>					
									<h2>Notice Board</h2>
								</header>
								
								<div>		
									<div class="widget-body">
										<div class="contentHolder ps-container">
										<?php 
										$noticeArray = getNoticeBoard();
										if(empty($noticeArray))
										{
											echo "No Announcement";
										}
										
										foreach ($noticeArray as $key => $noticeRow) 
										{
										?>
											<div class="profile-event" id="div<?php echo $noticeRow['notice_id'];?>">
												<div class="date-formats">
													<span class="glyphicon glyphicon-star"></span>
													<small><?php echo date('Y-m-d',strtotime($noticeRow['notice_DateTime'])); ?></small>
												</div>
												<div class="overflow-h">
													<h3 class="heading-xs">
														<a href="javascript:void(0)" onclick="getNoticeBoard(<?php echo $noticeRow['notice_id'];?>)"  data-toggle="modal" data-target="#showNoticeBoard">
														<?php echo getExcerpt($noticeRow['notice_Title'],0,40); ?></a>
													</h3>
													<p><?php echo getExcerpt($noticeRow['notice_Description'],0,80); ?></p>
													<input type="hidden" class="notice_title" value="<?php echo $noticeRow['notice_Title'];?>">
													<input type="hidden" class="notice_description" value="<?php echo $noticeRow['notice_Description']; ?>">
												</div>
											</div>
										<?php 
										}
										?>
										</div>
									</div>
				                </div>
				         	</div>
						</article>
						
						<article class="col-xs-12 col-sm-12 col-md-12 col-lg-4">
							<div class="jarviswidget jarviswidget-color-blueDark" data-widget-attstyle="jarviswidget-color-blueDark" id="wid-id-3" data-widget-colorbutton="false" data-widget-editbutton="false">
								<header>
									<span class="widget-icon"> <i class="fa fa-send"></i> </span>					
									<h2>Upcoming Holidays</h2>
								</header>
								
								<div>		
									<div class="widget-body">
										<div class="contentHolder ps-container">
											<div class="table-responsive"> 	
											    <table class="table">
												    <tbody>
												    <?php 
												    $holidayArrays = getHolidays();
												    if(empty($holidayArrays))
												    {
												    	echo "No Upcoming Holidays";
												    }
												    foreach ($holidayArrays as $key => $holidayRow) 
												    {
												    ?>
													    <tr>
															<td width="70%" align="left"><strong><span class="text-danger"><?php echo $holidayRow['holiday_Title'];?></span></strong></td>
															<td width="30%" align="center"><strong><span class="text-default"><?php echo date('Y-m-d',strtotime($holidayRow['holiday_Date'])); ?></span></strong></td>
													    </tr>
													<?php 
													}
													?>
												    </tbody>
											    </table>
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

<!-- ==========================CONTENT ENDS HERE ========================== -->

<!-- PAGE FOOTER -->
<?php
	// include page footer
	include("inc/footer.php");
?>
<!-- END PAGE FOOTER -->

<?php 
	//include required scripts
	include("inc/scripts.php"); 
?>

<script type="text/javascript" src='inc/js/custom.js'></script>
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
		forceEventDuration: true,
		allDay: true,
		editable: false,
		navLinks: true, // can click day/week names to navigate views
		eventLimit: true, // allow "more" link when too many events
		events: {
			url: 'php/leaveCalendar.php',
			error: function() {
				$('#script-warning').show();
			}
		},
		eventRender: function (event, element) {
	        element.attr('href', 'javascript:void(0);');
	        element.click(function() {
	        	$("#calendarModal").modal();
	        	$("#title").html(event.title);
	        	$("#applicant").html(event.applicant);
	            $("#startTime").html(moment(event.start).format('YYYY-MM-DD'));
	            $("#endTime").html(moment(event.end).format('YYYY-MM-DD'));
	            
	            $("#eventInfo").html(event.description);
	            //$("#eventLink").attr('href', event.url);
	            
	        });
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
		padding: 0 10px;
	}
	.jarviswidget #calendar {
	    margin-top: 0px;
	}

</style>



		<!-- PAGE RELATED PLUGIN(S) -->

		<!-- DYGRAPH -->
		<script src="<?php echo ASSETS_URL; ?>/js/plugin/chartjs/chart.min.js"></script>
		
		<script type="text/javascript">
			
			$(document).ready(function() {

				/*
				 * PAGE RELATED SCRIPTS
				 */
				
				 // reference: http://www.chartjs.org/docs/

				// LINE CHART
				

			    // BAR CHART

			    var barOptions = {
				    //Boolean - Whether the scale should start at zero, or an order of magnitude down from the lowest value
				    scaleBeginAtZero : true,
				    //Boolean - Whether grid lines are shown across the chart
				    scaleShowGridLines : true,
				    //String - Colour of the grid lines
				    scaleGridLineColor : "rgba(0,0,0,.05)",
				    //Number - Width of the grid lines
				    scaleGridLineWidth : 1,
				    //Boolean - If there is a stroke on each bar
				    barShowStroke : true,
				    //Number - Pixel width of the bar stroke
				    barStrokeWidth : 1,
				    //Number - Spacing between each of the X value sets
				    barValueSpacing : 5,
				    //Number - Spacing between data sets within X values
				    barDatasetSpacing : 1,
				    //Boolean - Re-draw chart on page resize
			        responsive: true,
				    //String - A legend template
				    legendTemplate : "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].lineColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>"
			    }

			 

			    // render chart
			    var ctx = document.getElementById("barChart1").getContext("2d");
			    var myNewChart = new Chart(ctx).Bar(barData1, barOptions);

			    var ctx = document.getElementById("barChart2").getContext("2d");
			    var myNewChart = new Chart(ctx).Bar(barData2, barOptions);

			    var ctx = document.getElementById("barChart3").getContext("2d");
			    var myNewChart = new Chart(ctx).Bar(barData2, barOptions);

			    var ctx = document.getElementById("barChart4").getContext("2d");
			    var myNewChart = new Chart(ctx).Bar(barData2, barOptions);
			    

			    // END BAR CHART

			    

			    // render chart
			    var doughnutOptions = {
				    //Boolean - Whether we should show a stroke on each segment
				    segmentShowStroke : true,
				    //String - The colour of each segment stroke
				    segmentStrokeColor : "#fff",
				    //Number - The width of each segment stroke
				    segmentStrokeWidth : 2,
				    //Number - The percentage of the chart that we cut out of the middle
				    percentageInnerCutout : 50, // This is 0 for Pie charts
				    //Number - Amount of animation steps
				    animationSteps : 100,
				    //String - Animation easing effect
				    animationEasing : "easeOutBounce",
				    //Boolean - Whether we animate the rotation of the Doughnut
				    animateRotate : true,
				    //Boolean - Whether we animate scaling the Doughnut from the centre
				    animateScale : false,
				    //Boolean - Re-draw chart on page resize
			        responsive: true,
				    //String - A legend template
				    legendTemplate : "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<segments.length; i++){%><li><span style=\"background-color:<%=segments[i].fillColor%>\"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>"
			    };
			    var ctx = document.getElementById("doughnutChart").getContext("2d");
			    var myNewChart = new Chart(ctx).Doughnut(doughnutData, doughnutOptions);

			    // END DOUGHNUT CHART

			    

			    // PIE CHART

			    var pieOptions = {
			    	//Boolean - Whether we should show a stroke on each segment
			        segmentShowStroke: true,
			        //String - The colour of each segment stroke
			        segmentStrokeColor: "#fff",
			        //Number - The width of each segment stroke
			        segmentStrokeWidth: 2,
			        //Number - Amount of animation steps
			        animationSteps: 100,
			        //String - types of animation
			        animationEasing: "easeOutBounce",
			        //Boolean - Whether we animate the rotation of the Doughnut
			        animateRotate: true,
			        //Boolean - Whether we animate scaling the Doughnut from the centre
			        animateScale: false,
			        //Boolean - Re-draw chart on page resize
			        responsive: true,
			        //String - A legend template
					legendTemplate : "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<segments.length; i++){%><li><span style=\"background-color:<%=segments[i].fillColor%>\"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>"
			    };

			    

			    // render chart
			    var ctx = document.getElementById("pieChart").getContext("2d");
			    var myNewChart = new Chart(ctx).Doughnut(pieData, doughnutOptions);

			   // var myNewChart = new Chart(ctx).Pie(pieData, pieOptions);

			    // END PIE CHART		
			
			})
		</script>
<?php 
	//include footer
	include("inc/google-analytics.php"); 
?>
<script type="text/javascript">
function getNoticeBoard(val)
{		
	var notice_title=$("#div"+val).find(".notice_title").val();
	var notice_description=$("#div"+val).find(".notice_description").val();
	
	$("#showNoticeBoard").find('#notice_title').text(notice_title);
	$("#showNoticeBoard").find('#notice_description').html(notice_description.replace(/\n\r?/g, "<br />"));
}
</script>
<!-- Notice Board Modal -->
<div class="modal fade" id="showNoticeBoard" role="dialog">
    <div class="modal-dialog">
      	<div class="modal-content">
        	<div class="modal-header showNoticeModalHeader">
          		<button type="button" class="close" data-dismiss="modal">&times;</button>
          		<h4 class="modal-title" id="notice_title"></h4>
        	</div>
        	<div class="modal-body">
          		<p id="notice_description"></p>
        	</div>
        	<div class="modal-footer">
          		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        	</div>
      	</div>
    </div>
</div>

<!-- Event Modal -->
			
<div id="calendarModal" class="modal fade">
	<div class="modal-dialog">
	    <div class="modal-content">
	        <div class="modal-header showNoticeModalHeader">
          		<button type="button" class="close" data-dismiss="modal">&times;</button>
          		<h4 class="modal-title" id="title"></h4>
        	</div>
	        <div id="modalBody" class="modal-body"> 
	        	Applicant: <span id="applicant"></span><br>
	        	Start: <span id="startTime"></span><br>
			    End: <span id="endTime"></span><br><br>
			    <p id="eventInfo"></p>
			    <!-- <p><strong><a id="eventLink" href="" target="_blank">Read More</a></strong></p> -->
	        </div>
	        <div class="modal-footer">
	            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	        </div>
	    </div>
	</div>
</div>       	