<?
$pageRows		= 20;
$pageTitle		= "상담AI 관리";

$tablename = "bot";
$category_tablename = "bot_category";
$uploadPath		= "/upload/bot/";
$maxSaveSize	= 50*1024*1024;					// 10Mb

if ($_REQUEST['pageRows'] != "") $pageRows = $_REQUEST['pageRows'];
?>