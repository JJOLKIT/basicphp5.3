<? include_once $_SERVER['DOCUMENT_ROOT']."/include/common.php"; ?>
<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/Comment.class.php";

include $_SERVER['DOCUMENT_ROOT']."/admin/include/loginCheck.php";

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko" lang="ko">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
<?
if (checkReferer($_SERVER["HTTP_REFERER"])) {
	$comment = new Comment(9999, $_REQUEST['tablename'], $_REQUEST);
	$comment_cmd = $_REQUEST['comment_cmd'];
	$_REQUEST['member_fk'] = 0;

	if ($comment_cmd == 'r_write') {

		$r = $comment->insert($_REQUEST);
		if ($r > 0) {
			echo returnURLMsg($comment->getQueryString($_REQUEST['url'], $_REQUEST['parent_fk'], $_REQUEST), '정상적으로 저장되었습니다.');
		} else {
			echo returnURLMsg($comment->getQueryString($_REQUEST['url'], $_REQUEST['parent_fk'], $_REQUEST), '요청처리중 장애가 발생하였습니다.');
		}
		

	} else if ($comment_cmd == 'r_delete') {
		$no = $_REQUEST['no'];
		$r = $comment->delete($no);
	
		if ($r > 0) {
			echo returnURLMsg($comment->getQueryString($_REQUEST['url'], $_REQUEST['parent_fk'], $_REQUEST), '정상적으로 삭제되었습니다.');
		} else {
			echo returnURLMsg($comment->getQueryString($_REQUEST['url'], $_REQUEST['parent_fk'], $_REQUEST), '요청처리중 장애가 발생하였습니다.');
		}
		
	} 

} else {
	echo returnURLMsg($intranet->getQueryString($_REQUEST['url'], $_REQUEST['parent_fk'], $_REQUEST), '요청처리중 장애가 발생하였습니다.1');
}


?>
</body>
</html>