<?
include $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";

$loginCheck = false;

if (isset($_SESSION['admin_id'])) {
	$loginCheck = true;
}

if (!$loginCheck) {
	$url = $_SERVER['REQUEST_URI'];
	$param = $_SERVER['QUERY_STRING'];
	$loginUrl = "/admin/index.php";
	$msg = "로그인 하시겠습니까?";

	if ($_SERVER['REQUEST_METHOD'] == 'GET') {
		$_SESSION['loginUrl'] = $loginUrl;
		$_SESSION['url'] = $url;
		$_SESSION['msg'] = $msg;
		echo "
			<script>
				location.replace('/admin/include/alert.php');
			</script>";
	} else {
		$_SESSION['loginUrl'] = $loginUrl;
		$_SESSION['url'] = substr($url, 0, strrpos($url, '/'));
		$_SESSION['msg'] = $msg;
		echo "
			<script>
				location.replace('/admin/include/alert.php');
			</script>";
	}
	
}
?>