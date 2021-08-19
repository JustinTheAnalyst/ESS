<?php 
include '../inc/PHP/constants.php';
include '../inc/PHP/configs.php';

global $DB;

$lbID = $_POST['lbID'];

$getLBDetails = "SELECT * FROM u_leavebatch 
				 INNER JOIN cnf_leavetype ON cnf_leavetype.lt_id = u_leavebatch.lt_id
				 WHERE 1 AND lb_id = ?";
$stmt = $DB->prepare($getLBDetails);
$stmt->bindValue(1, $lbID);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if($row['lb_Doc']!="")
{
	$docName = $row['lb_Doc'];
	$docLink = '../uploads/Leave_Docs/'.$row['lb_Doc'];
}
else
{
	$docName = 'N/A';
	$docLink = '';
}

if($row['lb_ReasonDoc']!="")
{
	$supDocName = $row['lb_ReasonDoc'];
	$supDocLink =  '../uploads/Leave_Reason_Docs/'.$row['lb_ReasonDoc'];
}
else
{
	$supDocName = 'N/A';
	$supDocLink = '';
}
?>
<div class="modal-dialog">
	<form role="form" id="change-leave-batch-status">
		<div class="modal-content">
			<div class="modal-header">
			  	<button type="button" class="close" data-dismiss="modal">&times;</button>
			    <h4 class="modal-title">Leave Application</h4>
			</div>
		        		
		    <div class="modal-body">
		    	<div class="form-group">
         			<label>Leave Type: <strong id="leaveType"><?php echo $row['lt_Name']; ?></strong></label><br/>
        			<label>From: <strong id="LeaveFrom"><?php echo $row['lb_FromDate']; ?></strong></label><br/>
          			<label>To: <strong id="LeaveTo"><?php echo $row['lb_ToDate']; ?></strong></label><br/>
          			<label>No. Day Taken: <strong id="daysTaken"><?php echo $row['lb_Days']; ?></strong></label><br/>
     			</div>  
     			<div class="form-group">
					<label for="lb_Reason">Reason:</label>
					<div><?php echo nl2br($row['lb_Reason']); ?></div>
				</div>    			
				<hr>
				<div class="form-group">
					<label for="lb_DocLink">Attachment by applicant:</label>
					<div>
					<?php 
					if ($docLink <> '')
					{
					?>
						<a href="<?php echo $docLink; ?>" id="lb_DocLink" target="_blank"><?php echo $docName; ?></a>
					<?php 
					}
					else 
					{
						echo $docName;
					}
					?>
					</div>
				</div>
				<hr>
	          	<div class="form-group">
	          		<label for="lb_Status">Change Status</label>
	          		<?php 
	          		if ($row['lb_Status'] == 'R' || $row['lb_Status'] == 'C')
	          		{
	          			$disabled = "disabled='disabled'";
	          		}
	          		else
	          		{
	          			$disabled = '';
	          		}
	          		
	          		?>
	          		<select name="lb_Status" id="lb_Status" class="form-control" <?php echo $disabled; ?>>
	          		<?php 
	          		if ($row['lb_Status'] == 'P')
	          		{
	          			echo '	<option value="A" selected="selected">Approve</option>
			          			<option value="R">Reject</option>';
	          		}
	          		elseif ($row['lb_Status'] == 'A') 
	          		{
	          			echo '	<option value="C">Cancel</option>';
	          		}
	          		?>
	          		</select>
	          	</div>
				<hr>
	          	<div class="form-group">
	          		<label for="lb_Remarks">Remarks</label>
	          		<textarea name="lb_Remarks" class="form-control" rows="5" <?php echo $disabled; ?>><?php echo $row['lb_Remarks']; ?></textarea>
	          	</div>
		  		<div class="form-group">
		   			<label for="lb_ReasonDoc">Supporting Document: <a href="" id="lb_ReasonDocLink" class="btn btn-link" target="_blank"></a></label>
		       		<div>
		       		<?php 
		       		if ($supDocLink <> '')
		       		{
		       		?>
		       			<a href="<?php echo $supDocLink; ?>" id="lb_SupDocLink" target="_blank"><?php echo $supDocName; ?></a>
		       		<?php 
		       		}
		       		else 
		       		{
		       			echo $supDocName;
		       		}
		       		?>
		       			<input type="file" name="lb_ReasonDoc" class="form-control" <?php echo $disabled; ?>>
		       		</div>
          		</div>
				<input type="hidden" name="lb_id" id="lb_id" value="<?php echo $row['lb_id']; ?>">
				<input type="hidden" name="lb_FromDate" id="lb_FromDate" value="<?php echo $row['lb_FromDate']; ?>">
		    	<input type="hidden" name="lb_ToDate" id="lb_ToDate" value="<?php echo $row['lb_ToDate']; ?>">
			</div>
		        	
        	<div class="modal-footer">
          		<button type="button" class="btn btn-default btn-default " data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Cancel</button>
          		<?php 
          		if ($row['lb_Status'] == 'P' || $row['lb_Status'] == 'A')
          		{
          		?>
          			<button type="submit" class="btn btn-default btn-success" id="save"><span class="fa fa-save"></span> Save</button>
          		<?php 
          		}
          		?>
        	</div>
		</div>
	</form>
</div>