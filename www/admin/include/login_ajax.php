<?session_start();
include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";

include_once $_SERVER['DOCUMENT_ROOT']."/lib/environment/Admin.class.php";

?>
<?
$url = $_REQUEST['url'];
$param = $_REQUEST['param'];

// 파라메터가 있을 경우에는 url 뒷에 결합한다.
if ($param) {
	$url = $url."?".$param;
} else {
	$url = START_PAGE;
}

$admin = new Admin(0, "admin", $_REQUEST);
$result = $admin->loginCheck($_REQUEST);
$row = mysql_fetch_array($result);
$loginCheck = $row['cnt'];

if ($loginCheck>0) {
	$result = $admin->getLoginSessionInfo($_REQUEST);
	$row = mysql_fetch_array($result);

	$_SESSION['admin_no'] = $row['no'];
	$_SESSION['admin_id'] = $row['id'];
	$_SESSION['admin_name'] = $row['name'];
	$_SESSION['admin_grade'] = $row['grade'];
	$_SESSION['admin_email'] = $row['email'];
	$_SESSION['admin_branch'] = $row['branch'];
	$_SESSION['admin_type'] = $row['type'];

	$url = START_PAGE;
	
	echo "1";
} else {
	echo "0";
}

?>
