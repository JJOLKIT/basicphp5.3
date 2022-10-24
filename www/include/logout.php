<? include_once $_SERVER['DOCUMENT_ROOT']."/include/common.php"; ?>
<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include $_SERVER['DOCUMENT_ROOT']."/include/loginCheck.php"
?>
<!doctype html>
<html lang="ko">
<head>
<title><?=COMPANY_NAME?> | 로그아웃</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
<?
	$url = $_REQUEST[url] == '' ? '/index.php' : $_REQUEST[url];
	
	unset($_SESSION['member_no']);
	unset($_SESSION['member_id']);
	unset($_SESSION['member_name']);
	unset($_SESSION['member_email']);
	unset($_SESSION['member_cell']);
	
	echo returnURLMsg($url, '로그아웃되었습니다.');
?>
</body>
</html>