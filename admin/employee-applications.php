<?php 
include('inc/PHP/functions.php');
//initilize the page
require_once ("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once ("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

 YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
 E.G. $page_title = "Custom Title" */

$page_title = "Manage Leave Applications";

// not accessible by Employee and Clerk user type
if ($_SESSION['user_Type'] == 'E' || $_SESSION['user_Type'] == 'C')
{
	header("Location: error404.php");
	exit;
}
else
{
	$user_id = $_SESSION['user_id'];
	$empInfo = getEmpCompanyDetail($user_id);
	
	$locID = $empInfo['loc_id'];
	$deptID = $empInfo['dept_id'];
}
/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include ("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["Leave Applications"]["active"] = true;
include ("inc/nav.php");
?>
<style>
<!--
.disabled a {
	pointer-event: none;
	color:grey;
}
-->
</style>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<div id="main" role="main">
<?php
//configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
//$breadcrumbs["New Crumb"] => "http://url.com"
//$breadcrumbs["Leaves"] = "";
include("inc/ribbon.php");
?>

	<!-- MAIN CONTENT -->
	<div id="content">
		<section id="widget-grid" class="">
			<div class="row">
				<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<div class="jarviswidget jarviswidget-color-purity" id="wid-id-1" data-widget-editbutton="false">
						<header>	
							<h2>
							<?php 
							if ($_SESSION['user_Type'] == 'T')
							{
								echo $empInfo['loc_shortName']; 
							}
							elseif ($_SESSION['user_Type'] == 'M') 
							{
								echo $empInfo['loc_shortName']." (".$empInfo['dept_ShortName'].")";
							}
							?> 
							Leave Application Listing
							</h2>			
						</header>
			
						<div>		
							<div class="widget-body no-padding">
								
								<table id="dt_basic" class="table table-striped table-bordered" width="100%">
									<thead>
							            <tr id="headRow">
							            	<th data-class="expand">Ref.#</th>
						                    <th>Name</th>
						                    <th data-hide="phone">Loc</th>
						                    <th data-hide="phone">Dept</th>
						                    <th data-hide="phone">Type</th>
						                   	<th data-hide="phone">Start</th>
											<th data-hide="phone">End</th>
						                    <th data-hide="phone">Total Day</th>
						                    <th data-hide="phone">Applied Date</th>
						                   	<th width="10%">Status</th>
						                    <th width="5%">Action</th>
							            </tr>
							        </thead>
				
							        <tbody>
							        <?php 
							        $leaveArray = getAllBatchLeaves();
							        
							        if ($_SESSION['user_Type'] == 'M')
							        {
							        	$leaveArray = getAllBatchLeaves($locID,$deptID,null,'"E","C"');
							        }
							        elseif ($_SESSION['user_Type'] == 'T')
							        {
							        	$leaveArray = getAllBatchLeaves($locID,null,'P',null);
							        }
							        
							        
							        foreach ($leaveArray as $key => $leaveRow) 
							        {
										if($leaveRow['lb_Status']=='A')
										{ 
											$status="<span class='center-block padding-5 label label-success'>Approved</span>";
											$disabled = "";
										}
										elseif($leaveRow['lb_Status']=='P')
										{ 
											$status="<span class='center-block padding-5 label label-info'>Pending</span>";
											$disabled = "";
										}
										elseif($leaveRow['lb_Status']=='R')
										{ 
											$status="<span class='center-block padding-5 label label-warning'>Rejected</span>";
											$disabled = "disabled";
										}
										elseif($leaveRow['lb_Status']=='C')
										{ 
											$status="<span class='center-block padding-5 label label-danger'>Cancelled</span>";
											$disabled = "disabled";
										}
										
										$ref = str_pad($leaveRow['lb_id'], 6, '0', STR_PAD_LEFT);
									?>
							        	<tr id="row<?php $leaveRow['lb_id'];?>">
							        		<td><?php echo $ref; ?></td>
							        		<td><?php echo $leaveRow['user_FirstName'];?></td>
							        		<td><?php echo $leaveRow['loc_shortName'];?></td>
							        		<td><?php echo $leaveRow['dept_ShortName'];?></td>
							                <td><?php echo $leaveRow['lt_Name']; ?></td>
							                <td><?php echo $leaveRow['lb_FromDate']; ?></td>
							                <td><?php echo $leaveRow['lb_ToDate']; ?></td>
											<td><?php echo $leaveRow['lb_Days']; ?></td>
											<td><?php echo date("Y-m-d", strtotime($leaveRow['lb_DateTime'])); ?></td>
							                <td class="status"><?php echo $status; ?></td>
							                
							                <!-- 
							                <td>
							                <?php 
							                if($leaveRow['lb_Doc']!="")
							                { 
							                ?>
							                	<a href="../uploads/Leave_Docs/<?php echo $leaveRow['lb_Doc']; ?>" target="_blank">Attachment</a>
							                <?php 
							                } 
							                ?>
							                </td> -->
							                <td>
								                <div class="btn-group">
													<button class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
														Action <span class="caret"></span>
													</button>
													<ul class="dropdown-menu dropdown-menu-right">
														<li class="<?php echo $disabled; ?>">
															<a href="javascript:void(0);" id="<?php echo $leaveRow['lb_id'];?>" class="edit" data-toggle="modal" data-target="#changeLeaveStatusModal">
																<i class="fa fa-pencil fa-lg fa-fw txt-color-greenLight"></i>Edit
															</a>
														</li>
													
									                	<li class="divider"></li>
														<li>
															<a href="generate-pdf-leave-application.php?uid=<?php echo $leaveRow['user_id']; ?>&lbid=<?php echo $leaveRow['lb_id']; ?>" target="_blank"><i class="fa fa-file fa-lg fa-fw txt-color-greenLight"></i>Export PDF</a>
														</li>
													</ul>
												</div>
							                </td>
							            </tr>
							        	<?php
							        }
							        ?>
							        </tbody>
								</table>
							</div>
						</div>
					</div>
				</article>
			</div>
		</section>
	</div>
</div>
<!-- END MAIN PANEL -->

<!-- PAGE FOOTER -->
<?php 
include ("inc/footer.php");
?>
<!-- END PAGE FOOTER -->

<?php
include ("inc/scripts.php");
?>

<script type="text/javascript" src="inc/js/custom.js"></script>
<!-- Modal -->
<div class="modal fade" id="changeLeaveStatusModal" role="dialog">
	
</div>
<!--End of modal-->
<script type="text/javascript">
$(document).ready(function() {
	$('.edit').on('click', function(){
		var lbID = $(this).attr("id");

		$('li.disabled').find('a').removeAttr('data-toggle');
		$('li.disabled').find('a').removeAttr('data-target');
		
	    $.ajax
	    ({
	       type: "POST",
	       url: "ajax/getLeaveBatchDetails.php",
	       data: 'lbID='+lbID,
	       cache: false,
	       success: function(data)
	       {
	          $("#changeLeaveStatusModal").html(data);
	       } 
	    });
	});
});
</script>

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
			"aaSorting": [ [0,'desc'] ],
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
    	"ordering": false,
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