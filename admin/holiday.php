<?php 
include('inc/PHP/functions.php');
//initilize the page
require_once ("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once ("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

 YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
 E.G. $page_title = "Custom Title" */

$page_title = "Manage Holidays";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include ("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["Configuration"]["sub"]["Holiday"]["active"] = true;
include ("inc/nav.php");
?>

<!-- MAIN PANEL -->
<div id="main" role="main">
<?php
$breadcrumbs["configuration"] = "";
include("inc/ribbon.php");
?>
	<div id="content">
		<section id="widget-grid" class="">
			<div class="row" style="margin-bottom: 10px;">
				<div class="col-xs-12">
					<button class="btn btn-info pull-right" data-toggle="modal" data-target="#holidayModal" id="createHolidayFormModal">
						<span class="glyphicon glyphicon-plus"></span> New Holiday
					</button>
				</div>
			</div>
			<div class="row">
				<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<div class="jarviswidget jarviswidget-color-purity" id="wid-id-1" data-widget-editbutton="false">
						<header>				
							<h2>Holiday Listing</h2>
						</header>
						<div>		
							<div class="widget-body no-padding">
								<table id="datatable_fixed_column" class="table table-striped table-bordered" width="100%">
							        <thead>
							            <tr id="headRow">
							            	<th data-class="expand" style="width:15%;">Date</th>	
						                    <th style="width:25%;">Title</th>
						                    <th data-hide="phone">Description</th>
						                    <th style="width:10%;">Status</th>
						                    <th data-hide="phone" style="width:8%;">Action</th>
							            </tr>
							        </thead>
			
							        <tbody>
						            <?php 
						           	$holidayList = getHolidayList();
						           	foreach ($holidayList as $key => $holidayArray) 
						           	{
						           		if($holidayArray['holiday_Status']=='A')
						           		{ 
						           			$status="<span class='center-block padding-5 label label-success'>Active</span>";
						           		} 
						           		else
						           		{ 
						           			$status="<span class='center-block padding-5 label label-danger'>In-Active</span>";
						           		}
						           	?>
							           	<tr id="row<?php echo $holidayArray['holiday_id']; ?>">
							           		<td class="date"><?php echo $holidayArray['holiday_Date']; ?></td>
							           		<td class="title"><?php echo $holidayArray['holiday_Title']; ?></td>
							           		<td class="description"><?php echo nl2br($holidayArray['holiday_Description']); ?></td>
							           		<td class="status" status-value="<?php echo $holidayArray['holiday_Status'];?>"><?php echo $status; ?></td>
							           		<td>
							           			<div class="btn-group">
													<button class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
														Action <span class="caret"></span>
													</button>
													<ul class="dropdown-menu dropdown-menu-right">
														<li>
															<a href="javascript:editGetHoliday(<?php echo $holidayArray['holiday_id']; ?>)" class="edit">
																<i class="fa fa-pencil fa-lg fa-fw txt-color-greenLight"></i> Edit
															</a>
														</li>
														<li>
															<a href="javascript:del(<?php echo $holidayArray['holiday_id']; ?>)" class="delete" >
																<i class="fa fa-trash-o fa-lg fa-fw txt-color-greenLight"></i> Delete
															</a>
														</li>
													</ul>
												</div>
							                </td>
							           	</tr>
							       	<?php
							       	}
							        ?>
							        </tbody>
							        <tfoot>
							        	<tr id="copyRow" style="display: none;">
							        		<td class="date" data-class="expand" style="width:15%;"></td> 	
							                <td class="title" style="width:25%;"></td>
							                <td class="description" data-hide="phone"></td>
							                <td class="status" data-hide="phone" style="width:10%;"></td>				              
							                <td>
							                	<div class="btn-group">
													<button class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
														Action <span class="caret"></span>
													</button>
													<ul class="dropdown-menu dropdown-menu-right">
														<li>
															<a href="javascript:void(0);" class="edit">
																<i class="fa fa-pencil fa-lg fa-fw txt-color-greenLight"></i> Edit
															</a>
														</li>
														<li>
															<a href="javascript:void(0);" class="delete" >
																<i class="fa fa-trash-o fa-lg fa-fw txt-color-greenLight"></i> Delete
															</a>
														</li>
													</ul>
												</div>
							                </td>
							            </tr>
							        </tfoot>
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
<?php // include page footer
include ("inc/footer.php");
?>
<!-- END PAGE FOOTER -->

<?php //include required scripts
include ("inc/scripts.php");
?>

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
<script type="text/javascript">
$(function(){
    $("#holiday_Date").datepicker({ 
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true,
        onSelect: function(date){
            var selectedDate = new Date(date);
            var msecsInADay = 86400000;
            //var endDate = new Date(selectedDate.getTime() + msecsInADay);
            var endDate = new Date(selectedDate.getTime());
			var limitedTo = '+1y';
			
            $("#la_ToDate").datepicker( "option", "minDate", endDate );
            $("#la_ToDate").datepicker( "option", "maxDate", limitedTo );
            $("#la_FromDate").datepicker( "option", "maxDate", limitedTo );
        }
    });
});
</script>
<script type="text/javascript" src="inc/js/custom.js"></script>
<!-- Holiday Modal Form -->
<div class="modal fade" id="holidayModal" role="dialog">
    <div class="modal-dialog">
		<form role="form" id="createHolidayForm" onsubmit="createHoliday()">
      		<div class="modal-content">
		        <div class="modal-header">
		          	<button type="button" class="close" data-dismiss="modal">&times;</button>
		          	<h4 ><span class="glyphicon glyphicon-info-sign"></span> Holiday</h4>
		        </div>
        		<div class="modal-body">
		            <div class="form-group">
		              	<label for="holiday_Title"> Title</label>
		              	<input type="text" class="form-control" id="holiday_Title" name="holiday_Title" required>
		            </div>
		            <div class="form-group">
		              	<label for="holiday_Description"> Description</label>
		             	<textarea class="form-control" name="holiday_Description" id="holiday_Description" rows="5"></textarea>
		            </div>
		            <div class="form-group">
		              	<label for="holiday_Date"> Date</label>
		             	<input type="text" name="holiday_Date" placeholder="yyyy-mm-dd" class="form-control" id="holiday_Date" required>
		            </div>
		           <div class="form-group">
		              	<label for="holiday_Status"> Status</label>
		              	<select class="form-control" id="holiday_Status" name="holiday_Status">
		              		<option value="A">Active</option>
		              		<option value="I">In-Active</option>
		              	</select>
		        	</div>
            		<input type="hidden" name="holiday_id" value="0" id="holiday_id">
        		</div>
        		<div class="modal-footer">
          			<button class="btn btn-default btn-default " data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Cancel</button>
          			<button type="submit" class="btn btn-default btn-success" id="save"><span class="glyphicon glyphicon-floppy-saved"></span> Save</button>
          		</div>
      		</div>
      	</form>
    </div>
</div> 

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
          holiday_id : val,
          is_delete: 'YES',
          
        },
        function(data,status){ 
            if(data.trim()!="")
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
   e.preventDefault();
  }
</script>
<?php
//include footer
include ("inc/google-analytics.php");
?>