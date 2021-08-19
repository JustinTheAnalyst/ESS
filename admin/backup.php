<?php
include('sessionCheck.php');
include('connection.php');

 //initilize the page
require_once ("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once ("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

 YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
 E.G. $page_title = "Custom Title" */

$page_title = "Backup";

/* ---------------- END PHP Custom Scripts ------------- */

//include header

include ("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["Backup"]["active"] = true;
include ("inc/nav.php");
?>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->

<style type="text/css">
  
.jarviswidget{

  margin-bottom: -2px !important;
}

</style>

<div id="main" role="main">

  <?php
    //configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
    //$breadcrumbs["New Crumb"] => "http://url.com"
   
    include("inc/ribbon.php");
  ?>

  <?php 
  $downloadFile = "";
    if(isset($_POST['submit'])){


      $tables = array();
      $query = mysqli_query($con, 'SHOW TABLES');
      while($row = mysqli_fetch_row($query)){
           $tables[] = $row[0];
      }

      $result = "";
      foreach($tables as $table){
      $query = mysqli_query($con, 'SELECT * FROM '.$table);
      $num_fields = mysqli_num_fields($query);

      $result .= 'DROP TABLE IF EXISTS '.$table.';';
      $row2 = mysqli_fetch_row(mysqli_query($con, 'SHOW CREATE TABLE '.$table));
      $result .= "\n\n".$row2[1].";\n\n";

      for ($i = 0; $i < $num_fields; $i++) {
      while($row = mysqli_fetch_row($query)){
         $result .= 'INSERT INTO '.$table.' VALUES(';
           for($j=0; $j<$num_fields; $j++){
             $row[$j] = addslashes($row[$j]);
             $row[$j] = str_replace("\n","\\n",$row[$j]);
             if(isset($row[$j])){
                 $result .= '"'.$row[$j].'"' ; 
              }else{ 
                  $result .= '""';
              }
              if($j<($num_fields-1)){ 
                  $result .= ',';
              }
          }
          $result .= ");\n";
      }
      }
      $result .="\n\n";
      }

      //Create Folder
      $folder = 'backupDatabse/';
      if (!is_dir($folder))
      mkdir($folder, 0777, true);
      chmod($folder, 0777);

      $date = date('d_m_Y'); 
      $filename = $folder."db_backup_".$date; 
      $downloadFile = '<a href="'.$filename.'.sql" download class="btn btn-primary btn-lg">Download Backup File</a>';



      $db_Name = "LMS DB Backup (".date('d M, Y').")" ;
      $db_FilePath = $filename.'.sql';
           $handle = fopen($filename.'.sql','w+');
      fwrite($handle,$result);
      fclose($handle);

function create_zip($files = array(),$destination = '',$overwrite = false) {
	//if the zip file already exists and overwrite is false, return false
	if(file_exists($destination) && !$overwrite) { return false; }
	//vars
	$valid_files = array();
	//if files were passed in...
	if(is_array($files)) {
		//cycle through each file
		foreach($files as $file) {
			//make sure the file exists
			if(file_exists($file)) {
				$valid_files[] = $file;
			}
		}
	}
	//if we have good files...
	if(count($valid_files)) {
		//create the archive
		$zip = new ZipArchive();
		if($zip->open($destination,$overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true) {
			return false;
		}
		//add the files
		foreach($valid_files as $file) {
			$zip->addFile($file,$file);
		}
		//debug
		//echo 'The zip archive contains ',$zip->numFiles,' files with a status of ',$zip->status;
		
		//close the zip -- done!
		$zip->close();
		
		//check to make sure the file exists
		return file_exists($destination);
	}
	else
	{
		return false;
	}
}

$zipFilPath = $folder."db_backup_".$date.".zip";
//$dbFileName = "db_backup_".$date.".sql";

    $files_to_zip = array(
	 $db_FilePath
);
//if true, good; if false, zip creation failed
$result = create_zip($files_to_zip,$zipFilPath);

              $sqlAddPayroll = "INSERT INTO 
                adm_dbbackup
              (db_Name, db_FilePath,db_Date, db_DateTime)
                VALUES
              ('$db_Name', '$zipFilPath' , NOW()  , NOW());";
              $queryAddPayroll = exe_Query($sqlAddPayroll);
              if($queryAddPayroll){

                $_SESSION['msg'] = '<div class="alert alert-info">Record Added Successfully</div>';
                ?>
                  <script type="text/javascript">
                    window.location ="./backup.php";
                  </script>
                <?php  die();

              }

              else{

                $_SESSION['msg'] = '<div class="alert alert-danger">Problen While Adding Record</div>';
              }

                
            



 
    }
   ?>
 
  <!-- MAIN CONTENT -->
  <div id="content">

    
    <!-- widget grid -->
    <section id="widget-grid" class="">
    
      <!-- row -->
      <div class="row">
        <article class="col-sm-12 col-md-12 col-lg-12 sortable-grid ui-sortable">

          <div class="jarviswidget" id="wid-id-0">
            
            <header>
              <h2>Backup</h2>        
              
            </header>

                
              <!-- widget content -->
            <div class="widget-body">
              <div>   
    <br>
<ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#list" aria-controls="list" role="tab" data-toggle="tab" style="color:black !important">Lists</a></li>
    <li role="presentation"><a href="#add" aria-controls="add" role="tab" data-toggle="tab" style="color:black !important">Add New</a></li>
</ul>
 <?php if(!empty($_SESSION['msg'])) {echo $_SESSION['msg']; unset($_SESSION['msg']); } ?>
<div class="tab-content" >
    
    <div role="tabpanel" class="tab-pane active" id="list">


          <table id="dt_basic" class="table table-striped table-bordered" width="100%">

                <thead>
              <!--<tr>
                <th class="hasinput" style="width:6%">
                  Sr #
                </th>
                <th class="hasinput" style="width:20%">
                  <input type="text" class="form-control" placeholder="Name" />
                </th>
                
                
                <th class="hasinput" style="width:20%">
                  <input type="text" class="form-control" placeholder="Date" />
                </th>

                
                
                <th class="hasinput" style="width:20%">
                  Action
                </th>
                  
                
              </tr>-->
                    <tr>
                      <th>
                        Sr #
                      </th>
                          <th data-class="expand">Name</th>
                           
                          <th data-hide="phone">Date</th>
                          
                         
                          <th data-hide="phone">Action</th>
                         
                    </tr>
                </thead>

                <tbody>
                <?php 


                  $sqlSelectDbBackup = "SELECT 
                                    *
                            FROM
                            adm_dbbackup
                            
                            ORDER BY adm_dbbackup.db_id ASC;";
                  $querySelectDbBackup = mysqli_query($con, $sqlSelectDbBackup);
                  if(mysqli_num_rows($querySelectDbBackup) > 0){
                    $SrNo = 0;
                    while ($rowSelectDbBackup = mysqli_fetch_assoc($querySelectDbBackup)) 
                    {
                        
                        
                 ?>
                  <tr id="row<?php echo $rowSelectDbBackup['db_id']; ?>">
                    <td><?php echo ++$SrNo ?></td>
                    <td> <?php echo $rowSelectDbBackup['db_Name'] ?></td>
                    <td><?php echo $rowSelectDbBackup['db_Date'] ?></td>
                    <td><a href="<?php echo $rowSelectDbBackup['db_FilePath']; ?>" class="btn btn-primary" download>Download</a>
                   
                    
                    <a href="javascript:del(<?=$rowSelectDbBackup['db_id'];?>)" class="btn btn-danger"><i class="fa fa-trash"></i>Delete</a>
                    </td>
                    
                  </tr>
                  <?php 

                        }
                      }
                   ?>
                </tbody>
            </table>
        </div>
    
      <div role="tabpanel" class="tab-pane" id="add" >
     
              <form id="checkout-form" class="smart-form" novalidate="novalidate" enctype="multipart/form-data" method="post" action="">  
                <fieldset>
                  <div class="row" style="padding-left: 10px">
                    
                      

                        
                        
                        <div class="row" style="margin-top:5px; margin-bottom: 5px;">
                          <div class="col-lg-12" >
                            <h2 align="center">
                              Take Backup <br/>
                              <?php echo $downloadFile ?>
                            </h2> 

                            
                          </div>
                          
                        </div>  

                        

                        
                      
                     </div>
                </fieldset>
                <footer style="margin-top:5px;">
                  <button type="submit" name="submit" value="Generate"  class="btn btn-primary">  Generate </button>
                </footer>
              </form>
          </div>
          
        
              </div>
            </div>  <!-- end widget body -->          
              
          
            
          </div><!-- End of Div wid-id-1-->
        </article>
    
      </div><!--End of div row-->
    
    </section>
    <!-- end widget grid -->
  </div>
  <!-- END MAIN CONTENT -->

</div>
<!-- END MAIN PANEL -->
<!-- ==========================CONTENT ENDS HERE ========================== -->

<!-- PAGE FOOTER -->
<?php // include page footer
//include ("inc/footer.php");
?>
<!-- END PAGE FOOTER -->

<?php //include required scripts
include ("inc/scripts.php");
?>
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
          db_id : val,
          is_delete: true
          
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
<?php
//include footer
include ("inc/google-analytics.php");
?>