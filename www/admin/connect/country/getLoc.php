<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/common.php"; ?>
<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

include_once $_SERVER['DOCUMENT_ROOT']."/lib/weblog/CountryLog.class.php";




$pageRows = 99999;
$notice = new CountryLog($pageRows, $_REQUEST);
$result = ($notice->getGroupList2($_REQUEST));

$arr = array();
$i = 0;
while($row = mysql_fetch_assoc($result)){
	$arr[$i]['lat'] = explode(",", $row['loc'])[0];
	$arr[$i]['lng'] = explode(",", $row['loc'])[1];
	$arr[$i]['title'] = $row['country']." ".$row['city'];
	$i++;
}

echo json_encode($arr);





?>