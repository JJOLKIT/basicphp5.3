<?session_start();
include $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include $_SERVER['DOCUMENT_ROOT']."/lib/dbConfig.php";

$loginCheck = false;

if (isset($_SESSION['admin_id'])) {
	$loginCheck = true;
}

if (!$loginCheck) {
	$url = $_SERVER['REQUEST_URI'];
	$param = $_SERVER['QUERY_STRING'];
	$loginUrl = "/manage/index.php";
	$msg = "로그인 하시겠습니까?";

	if ($_SERVER['REQUEST_METHOD'] == 'GET') {
		$_SESSION['loginUrl'] = $loginUrl;
		$_SESSION['url'] = $url;
		$_SESSION['msg'] = $msg;
		echo "
			<script>
				location.replace('/manage/include/alert.php');
			</script>";
	} else {
		$_SESSION['loginUrl'] = $loginUrl;
		$_SESSION['url'] = substr($url, 0, strrpos($url, '/'));
		$_SESSION['msg'] = $msg;
		echo "
			<script>
				location.replace('/manage/include/alert.php');
			</script>";
	}
	
}
?>