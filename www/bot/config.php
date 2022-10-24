<?
$pageRows		= 20;
$pageTitle		= "챗봇관리";
$tablename = "bot";
$uploadPath		= "/upload/bot/";
$maxSaveSize	= 50*1024*1024;					// 10Mb

if ($_REQUEST['pageRows'] != "") $pageRows = $_REQUEST['pageRows'];
?>