<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/common.php"; ?>
<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/Faq.class.php";

include $_SERVER['DOCUMENT_ROOT']."/admin/include/loginCheck.php";
include "config.php";

$faq = new Faq($pageRows, $tablename, $category_tablename, $_REQUEST);
?>
<!doctype html>
<html lang="ko">
<head>
<? include $_SERVER['DOCUMENT_ROOT']."/admin/include/headHtml.php" ?>
</head>
<body>
<?
if (checkReferer($_SERVER["HTTP_REFERER"])) {

	if ($_REQUEST['cmd'] == 'write') {
		$_POST = xss_clean($_POST);
		$_POST = fileupload('filename', $_SERVER['DOCUMENT_ROOT'].$uploadPath, $_POST, true, $maxSaveSize);		// 첨부파일
		$_POST = fileupload('moviename', $_SERVER['DOCUMENT_ROOT'].$uploadPath, $_POST, false, $maxSaveSize);	// 동영상

		$r = $faq->insert($_POST);
		if ($r > 0) {
			echo returnURLMsg($faq->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '정상적으로 저장되었습니다.');
		} else {
			echo returnURLMsg($faq->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '요청처리중 장애가 발생하였습니다.');
		}

	} else if ($_REQUEST['cmd'] == 'edit') {
		$_POST = xss_clean($_POST);
		$_POST = fileupload('filename', $_SERVER['DOCUMENT_ROOT'].$uploadPath, $_POST, true, $maxSaveSize);		// 첨부파일
		$_POST = fileupload('moviename', $_SERVER['DOCUMENT_ROOT'].$uploadPath, $_POST, false, $maxSaveSize);	// 동영상

		$r = $faq->update($_POST);

		if ($r > 0) {
			echo returnURLMsg($faq->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '정상적으로 수정되었습니다.');
		} else {
			echo returnURLMsg($faq->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '요청처리중 장애가 발생하였습니다.');
		}
	} else if ($_REQUEST['cmd'] == 'groupDelete') {

		$no = $_REQUEST['no'];
		
		$r = 0;
		for ($i=0; $i<count($no); $i++) {
			$r += $faq->delete($no[$i]);
		}

		if ($r > 0) {
			echo returnURLMsg($faq->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '총 '.$r.'건이 삭제되었습니다.');
		} else {
			echo returnURLMsg($faq->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '요청처리중 장애가 발생하였습니다.');
		}

	} else if ($_REQUEST['cmd'] == 'delete') {

		$no = $_REQUEST['no'];

		$r = $faq->delete($no[$i]);

		if ($r > 0) {
			echo returnURLMsg($faq->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '정상적으로 삭제되었습니다.');
		} else {
			echo returnURLMsg($faq->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '요청처리중 장애가 발생하였습니다.');
		}
	} else if ($_REQUEST['cmd'] == 'writeCategory') {

		$r = $faq->insertCategory($_REQUEST);
		if ($r > 0) {
			echo "<script>alert('정상적으로 저장되었습니다.'); location.href='category.php';</script>";
		} else {
			echo "<script>alert('요청처리중 장애가 발생했습니다.'); location.href='category.php';</script>";
		}

	} else if ($_REQUEST['cmd'] == 'editCategory') {
	
		$r = $faq->updateCategory($_REQUEST);

		if ($r > 0) {
			//echo returnURLMsg($faq->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'category.php'), 0, $_REQUEST), '정상적으로 수정되었습니다.');
			echo "<script>alert('정상적으로 수정되었습니다.'); location.href='category.php';</script>";
		} else {
			echo "<script>alert('요청처리중 장애가 발생했습니다.'); location.href='category.php';</script>";
		}
	} else if ($_REQUEST['cmd'] == 'deleteCategory') {

		$no = $_REQUEST['no'];
		
		$r = $faq->deleteCategory($no[$i]);

		if ($r > 0) {
			echo returnURLMsg($faq->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'category.php'), 0, $_REQUEST), '정상적으로 삭제되었습니다.');
		} else {
			echo returnURLMsg($faq->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'category.php'), 0, $_REQUEST), '요청처리중 장애가 발생하였습니다.');
		}
	}


} else {
	echo returnURLMsg($notice->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '요청처리중 장애가 발생하였습니다.1');
}
?>
</body>
</html>