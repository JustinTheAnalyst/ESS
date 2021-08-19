<?php
include 'inc/PHP/constants.php';
include 'inc/PHP/configs.php';

$uploadFolderPath = '../uploads/';
$folder = $_GET['key'].'/';
$filePath = $uploadFolderPath.$folder;

if (isset($_GET['fn']) && file_exists($filePath.$_GET['fn']))
{
	$fileName = $_GET['fn'];
	$file = $filePath.$_GET['fn'];
	
	header('Content-type: application/pdf');
	readfile($file);
	exit;
}
?>