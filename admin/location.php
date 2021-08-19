<?php 
include('inc/PHP/functions.php');
//initilize the page
require_once ("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once ("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

 YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
 E.G. $page_title = "Custom Title" */

$page_title = "Manage Locations";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include ("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["Configuration"]["sub"]["Location"]["active"] = true;
include ("inc/nav.php");
?>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<div id="main" role="main">
<?php
//configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
//$breadcrumbs["New Crumb"] => "http://url.com"
$breadcrumbs["Configuration"] = "";
include("inc/ribbon.php");
?>

	<!-- MAIN CONTENT -->
	<div id="content">
		<section id="widget-grid" class="">
			<div class="row">
				<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<div class="jarviswidget jarviswidget-color-purity" id="wid-id-1" data-widget-editbutton="false">
						<header>				
							<h2>Location Listing</h2>
						</header>
			
						<div>		
							<div class="widget-body no-padding">
								<table id="datatable_fixed_column" class="table table-striped table-bordered" width="100%">
							        <thead>
							             <tr id="headRow">
							             	<th data-class="expand">Name</th>
							             	<th>Short Name</th>
						                    <th style="width:10%;">Status</th>
						                    <th data-hide="phone" style="width:8%;">Action</th>
							            </tr>
							        </thead>
							        <tbody>
							        <?php 
						           	$locList = getLocationList();
						           	foreach ($locList as $key => $locArray) 
						           	{
						           		//($locArray['loc_status']=='A') ? $status="Active" : $status="In-Active";
						           		
						           		if($locArray['loc_status']=='A')
						           		{
						           			$status="<span class='center-block padding-5 label label-success'>Active</span>";
						           		}
						           		elseif($locArray['loc_status']=='I')
						           		{
						           			$status="<span class='center-block padding-5 label label-danger'>In-Active</span>";
						           		}
						           	?>
							           	<tr id="row<?php echo $locArray['loc_id']; ?>">
							           		<td class="name"><?php echo $locArray['loc_name']; ?></td>
							           		<td class="shortname"><?php echo $locArray['loc_shortName']; ?></td>
							           		<td width="10%" class="status" status-value="<?php echo $locArray['loc_status'];?>"><?php echo $status; ?></td>
							           		<td width="5%">
							                	<a href="javascript:editGetLocation(<?php echo $locArray['loc_id']; ?>)" class="btn btn-primary btn-xs edit"><i class="fa fa-pencil"></i> Edit</a>
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
    	"ordering":false,
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
<script type="text/javascript" src="inc/js/custom2.js"></script>
<!-- Modal -->
<div class="modal fade" id="locModal" role="dialog">
    <div class="modal-dialog">
      	<div class="modal-content">
	        <div class="modal-header">
	          	<button type="button" class="close" data-dismiss="modal">&times;</button>
	          	<h4 ><span class="glyphicon glyphicon-book"></span> Location</h4>
	        </div>
        	<div class="modal-body">
	          	<form role="form" id="create-location-form">
		          	<div class="form-group">
		              	<label for="groupName"> Location Name</label>
		              	<input type="text" class="form-control" id="locName" name="loc_Name">
		            </div>
		          	<div class="form-group">
		              	<label for="groupShortName"> Location Short Name</label>
		             	<input type="text" class="form-control" name="loc_ShortName" id="locShortName">
		            </div>
	            	<div class="form-group">
	              		<label for="groupStatus"> Location Status</label>
	              		<select class="form-control" id="locStatus" name="loc_Status">
	              			<option value="A">Active</option>
	              			<option value="I">In-Active</option>
	              		</select>
	            	</div>
	            	<input type="hidden" name="loc_id" id="loc_id" value="0">
	          	</form>
        	</div>
        	<div class="modal-footer">
          		<button type="submit" class="btn btn-default btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Cancel</button>
          		<button type="submit" class="btn btn-default btn-success" id="save" onclick="updateLoaction();"><span class="glyphicon glyphicon-floppy-saved"></span> Save</button>
        	</div>
      	</div>
	</div>
</div> 
<!--End of modal-->

<?php
//include footer
include ("inc/google-analytics.php");
?>