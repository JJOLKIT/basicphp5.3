<?session_start();
include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/Popup.class.php";

include $_SERVER['DOCUMENT_ROOT']."/admin/include/loginCheck.php";
include "config.php";

$popup = new Popup($pageRows, $tablename, $_REQUEST);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko" lang="ko">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<? include $_SERVER['DOCUMENT_ROOT']."/admin/include/headHtml.php" ?>
</head>
<body>
<?
if (checkReferer($_SERVER["HTTP_REFERER"])) {

	if ($_POST['cmd'] == 'write') {
		$_POST = fileupload('filename', $_SERVER['DOCUMENT_ROOT'].$uploadPath, $_POST, true, $maxSaveSize);		// 첨부파일
		$_POST = fileupload('imagename', $_SERVER['DOCUMENT_ROOT'].$uploadPath, $_POST, false, $maxSaveSize);		// 이미지

		if ($_POST[imagename]) {
			$size = getimagesize($_SERVER['DOCUMENT_ROOT'].$uploadPath.$_POST[imagename]);
			$_POST[popup_width] = $size[0];
			$_POST[popup_height] = $size[1];
		}

		$_POST = xss_clean($_POST);

		

		$r = $popup->insert($_POST);
		if ($r > 0) {
			echo returnURLMsg($popup->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '정상적으로 저장되었습니다.');
		} else {
			echo returnURLMsg($popup->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '요청처리중 장애가 발생하였습니다.');
		}

	} else if ($_POST['cmd'] == 'edit') {

		$_POST = fileupload('filename', $_SERVER['DOCUMENT_ROOT'].$uploadPath, $_POST, true, $maxSaveSize);		// 첨부파일
		$_POST = fileupload('imagename', $_SERVER['DOCUMENT_ROOT'].$uploadPath, $_POST, false, $maxSaveSize);		// 이미지

		if ($_POST[imagename]) {
			$size = getimagesize($_SERVER['DOCUMENT_ROOT'].$uploadPath.$_POST[imagename]);
			$_POST[popup_width] = $size[0];
			$_POST[popup_height] = $size[1];
		}
		$_POST = xss_clean($_POST);
		$r = $popup->update($_POST);

		if ($r > 0) {
			echo returnURLMsg($popup->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '정상적으로 수정되었습니다.');
		} else {
			echo returnURLMsg($popup->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '요청처리중 장애가 발생하였습니다.');
		}
	} else if ($_REQUEST['cmd'] == 'groupDelete') {

		$no = $_REQUEST['no'];
		
		$r = 0;
		for ($i=0; $i<count($no); $i++) {
			$r += $popup->delete($no[$i]);
		}

		if ($r > 0) {
			echo returnURLMsg($popup->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '총 '.$r.'건이 삭제되었습니다.');
		} else {
			echo returnURLMsg($popup->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '요청처리중 장애가 발생하였습니다.');
		}

	} else if ($_REQUEST['cmd'] == 'delete') {

		$no = $_REQUEST['no'];
		
		$r = $popup->delete($no);

		if ($r > 0) {
			echo returnURLMsg($popup->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '정상적으로 삭제되었습니다.');
		} else {
			echo returnURLMsg($popup->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '요청처리중 장애가 발생하였습니다.');
		}
	}


} else {
	echo returnURLMsg($popup->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '요청처리중 장애가 발생하였습니다.1');
}
?>
</body>
</html>