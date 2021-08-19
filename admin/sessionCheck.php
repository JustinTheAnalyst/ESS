<?php 
session_start();
if((!isset($_SESSION['user_id']) || ($_SESSION['user_Type']!='A')))
{

?>
	<script type="text/javascript">
		window.location = "../login.php";
		
	</script>
<?php
}


?>