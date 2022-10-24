<?session_start();
	include $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";

	$loginUrl = $_SESSION['loginUrl'];
	if (!$loginUrl) $loginUrl = '/admin/index.php'; // 이동할 주소
	$msg = $_SESSION['msg'];						// confirm 메세지 내용
	$url = $_SESSION['url'];						// 로그인 후 이동 할 주소
	$param = $_SESSION['param'];					// 로그인 후 이동 할 주소의 파라메터
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="/css/style.css" rel="stylesheet" type="text/css">
</head>
<body topmargin=0 leftmargin=0>
<form name="board" action="<?=$loginUrl?>" method="post">
	<input type="hidden" name="url" value="<?=$url?>">
	<input type="hidden" name="param" value="<?=$param?>">
</form>

</body>
<SCRIPT LANGUAGE="JavaScript">
<!--
 if (confirm("<?=$msg?>")) {
	document.board.submit();
 } else {
	location.href = "/admin/index.php";
 }
//-->
</SCRIPT>
</html>