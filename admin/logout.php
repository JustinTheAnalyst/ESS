<?php
session_start();
session_destroy();
$_SESSION=array();
?>
<script>
window.location.assign('../login.php');
</script>