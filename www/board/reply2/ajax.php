<? include_once $_SERVER['DOCUMENT_ROOT']."/include/common.php"; ?>
<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/Reply2.class.php";

include "config.php";
 
$notice = new Reply2($pageRows, $tablename, $_REQUEST);
/*
$data = $notice->getData($_REQUEST[no], $userCon);*/

/*
if($data['password'] == DB_ENCRYPTION($_POST['pwd'])){
	echo DB_ENCRYPTION($_POST['pwd']);
}else{
	echo DB_ENCRYPTION($_POST['pwd']);
}*/
$_POST = xss_clean($_POST);
$cnt = $notice->checkPassword($_POST);

if($cnt > 0){
	echo "success";
}else{
	echo "fail";
}

?>



