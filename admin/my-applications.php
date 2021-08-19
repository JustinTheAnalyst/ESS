<?php 
//initilize the page
require_once ("inc/init.php");
include('inc/PHP/functions.php');

//require UI configuration (nav, ribbon, etc.)
require_once ("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

 YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
 E.G. $page_title = "Custom Title" */

$page_title = "My Applications";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
//$page_css[] = "your_style.css";
include ("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["My Leave"]["sub"]["My Applications"]["active"] = true;
include ("inc/nav.php");
?>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<div id="main" role="main">

	<?php
		//configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
		//$breadcrumbs["New Crumb"] => "http://url.com"
		include("inc/ribbon.php");
	?>

	<!-- MAIN CONTENT -->
	<div id="content">
		<section id="widget-grid" class="">
			<div class="row">
				<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<div class="jarviswidget jarviswidget-color-purity" id="wid-id-1" data-widget-editbutton="false">
						<header>				
							<h2>Applications</h2>
						</header>

						<div class="tab-content" >	
							<div class="widget-body no-padding">
								<table id="dt_basic" class="table table-striped table-bordered" width="100%">
							        <thead>
							            <tr>
							            	<th data-hide="phone">Ref.#</th>
						                    <th>Name</th>
						                    <th data-hide="phone">Type</th>
						                   	<th>Start</th>
											<th>End</th>
						                    <th data-hide="phone">Total Day</th>
						                    <th data-hide="phone">Applied Date</th>
						                   	<th>Status</th>
						                   	<!-- <th data-hide="phone,tablet">Attachment</th> -->
						                    <th>Action</th>
							            </tr>
							        </thead>
			
							        <tbody>
							        <?php 
							        $user_id = $_SESSION['user_id'];
							        $leaveHistoryArray = getUserLeaveHistory($user_id);
							        $srNo = 0;
							        foreach ($leaveHistoryArray as $key => $leaveRow) 
							        {
							        ?>
							            <tr id="row<?php echo $leaveRow['lb_id']  ?>">
							            	<td><?php echo $leaveRow['lb_id']; ?></td>
											<td><?php echo $_SESSION['user_FirstName']; ?></td>
							                <td><?php echo $leaveRow['lt_Name']; ?></td>
							                <td><?php echo $leaveRow['lb_FromDate']; ?></td>
							                <td><?php echo $leaveRow['lb_ToDate']; ?></td>
							                <td><?php echo $leaveRow['lb_Days']; ?></td>
							                <td><?php echo date("Y-m-d",strtotime($leaveRow['lb_DateTime'])); ?></td>
							                <td>
							                	<?php 
							                	if($leaveRow['lb_Status']=='P')
							                	{
							                	?>
							                		<label class="center-block padding-5 label label-info">Pending</label>
							                	<?php 
							                	}
							                	elseif($leaveRow['lb_Status']=='A')
							                	{
							                	?>
							                		<label class="center-block padding-5 label label-success">Approved</label>
							                	<?php 
							                	}
							                	elseif($leaveRow['lb_Status']=='R')
							                	{
							                	?>
							                		<label class="center-block padding-5 label label-warning">Rejected</label>
							                	<?php 
							                	}
							                	elseif($leaveRow['lb_Status']=='C')
							                	{
							                	?>
							                		<label class="center-block padding-5 label label-danger">Cancelled</label>
							                	<?php 
							                	}
							                	?>
							                </td>	
							                <!-- <td>
							                <?php 
							                /*if($leaveRow['lb_Doc']!="")
							                { 
							                ?>
							                	<a href="../uploads/Leave_Docs/<?php echo $leaveRow['lb_Doc']; ?>" download>Attachment</a>
							                <?php 
							                }*/ 
							                ?>
							                </td> -->			     
							                <td>
							                <?php 
							                if($leaveRow['lb_Status']=='P')
							                { 
							                ?>
							                	<a href="javascript:del(<?php echo $leaveRow['lb_id'] ?>)" class="btn btn-danger btn-xs delete" ><i class="fa fa-trash-o"> Delete</i></a>
							                <?php 
							                } 
							                ?>
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
	<!-- END MAIN CONTENT -->
</div>
<!-- END MAIN PANEL -->


<!-- PAGE FOOTER -->
<?php // include page footer
include ("inc/footer.php");
?>
<!-- END PAGE FOOTER -->

<?php //include required scripts
include ("inc/scripts.php");
?>
<script type="text/javascript" src="inc/js/custom.js"></script>
<!-- PAGE RELATED PLUGIN(S) -->
<script src="<?php echo ASSETS_URL; ?>/js/plugin/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo ASSETS_URL; ?>/js/plugin/datatables/dataTables.colVis.min.js"></script>
<script src="<?php echo ASSETS_URL; ?>/js/plugin/datatables/dataTables.tableTools.min.js"></script>
<script src="<?php echo ASSETS_URL; ?>/js/plugin/datatables/dataTables.bootstrap.min.js"></script>
<script src="<?php echo ASSETS_URL; ?>/js/plugin/datatable-responsive/datatables.responsive.min.js"></script>

<script type="text/javascript">
 /*
  * SmartAlerts
  */
  // With Callback
  function del(val){

   $.SmartMessageBox({
    title : "Attention required!",
    content : "This is a confirmation box. Do you want to delete the Record?",
    buttons : '[No][Yes]'
   }, function(ButtonPressed) {
    if (ButtonPressed === "Yes") {


		 $.post("config-process.php",
        {
          lb_id : val,
          is_delete:"YES",
          
        },
        function(data,status){ 

            if(data.trim()=="DELETED")
            {
            	 $('#row'+val).remove();
                 $.smallBox({
		         title : "Delete Status",
		         content : "<i class='fa fa-clock-o'></i> <i>Record Deleted successfully...</i>",
		         color : "#659265",
		         iconSmall : "fa fa-check fa-2x fadeInRight animated",
		         timeout : 4000
		        });
            }
            else
            {
            	 $.smallBox({
		         title : "Delete Status",
		         content : "<i class='fa fa-clock-o'></i> <i>Problem Deleting Record...</i>",
		         color : "#C46A69",
		         iconSmall : "fa fa-times fa-2x fadeInRight animated",
		         timeout : 4000
		        });
            }
        });             
    }
    if (ButtonPressed === "No") {
     $.smallBox({
      title : "Delete Status",
      content : "<i class='fa fa-clock-o'></i> <i>You pressed No...</i>",
      color : "#C46A69",
      iconSmall : "fa fa-times fa-2x fadeInRight animated",
      timeout : 4000
     });
    }
 
   });
  }
</script>
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
    	//"bStateSave": true // saves sort state using localStorage
    	'ordering':false,
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