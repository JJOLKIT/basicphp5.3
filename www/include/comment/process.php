<? include_once $_SERVER['DOCUMENT_ROOT']."/include/common.php"; ?>
<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/Comment.class.php";

include $_SERVER['DOCUMENT_ROOT']."/include/loginCheck.php";

?>
<!doctype html>
<html lang="ko">
<head>
<? include $_SERVER['DOCUMENT_ROOT']."/include/headHtml.php" ?>
</head>
<body>
<?
if (checkReferer($_SERVER["HTTP_REFERER"])) {
	$comment = new Comment(9999, $_REQUEST['tablename'], $_REQUEST);
	$comment_cmd = $_REQUEST['comment_cmd'];
	$_REQUEST['member_fk'] = chkIsset($_REQUEST['member_fk']);

	if ($comment_cmd == 'r_write') {

		$r = $comment->insert($_REQUEST);
		if ($r > 0) {
			echo returnURLMsg($comment->getQueryString($_REQUEST['url'], $_REQUEST['parent_fk'], $_REQUEST), '정상적으로 저장되었습니다.');
		} else {
			echo returnURLMsg($comment->getQueryString($_REQUEST['url'], $_REQUEST['parent_fk'], $_REQUEST), '요청처리중 장애가 발생하였습니다.');
		}
		

	} else if ($comment_cmd == 'r_delete') {
		$cp = $comment->checkPassword($_REQUEST[no], $_REQUEST[password]);
		if ($cp) {
			$no = $_REQUEST['no'];
			$r = $comment->delete($no);
		
			if ($r > 0) {
				echo returnURLMsg($comment->getQueryString($_REQUEST['url'], $_REQUEST['parent_fk'], $_REQUEST), '정상적으로 삭제되었습니다.');
			} else {
				echo returnURLMsg($comment->getQueryString($_REQUEST['url'], $_REQUEST['parent_fk'], $_REQUEST), '요청처리중 장애가 발생하였습니다.');
			}
		} else {
			echo returnURLMsg($comment->getQueryString($_REQUEST['url'], $_REQUEST['parent_fk'], $_REQUEST), '비밀번호가 올바르지 않습니다.');
		}
		
	} 

} else {
	echo returnURLMsg($comment->getQueryString($_REQUEST['url'], $_REQUEST['parent_fk'], $_REQUEST), '요청처리중 장애가 발생하였습니다.1');
}


?>
</body>
</html>