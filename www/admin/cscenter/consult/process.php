<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/common.php"; ?>
<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/Consult.class.php";

include $_SERVER['DOCUMENT_ROOT']."/admin/include/loginCheck.php";
include "config.php";

$consult = new Consult($pageRows, $tablename, $_REQUEST);

include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/Spam.class.php";
$spam = new Spam(999, 'spam', $_REQUEST);
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
		$n = $spam->checkWords($_REQUEST);
		
		$_POST = xss_clean($_POST);
		$r = $consult->insert($_POST);
		if ($r > 0) {
			echo returnURLMsg($consult->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '정상적으로 저장되었습니다.');
		} else {
			echo returnURLMsg($consult->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '요청처리중 장애가 발생하였습니다.');
		}
		

	} else if ($_REQUEST['cmd'] == 'edit') {
		$n = $spam->checkWords($_REQUEST);

		$_POST = xss_clean($_POST);
		$r = $consult->update($_POST);

		if ($r > 0) {
			echo returnURLMsg($consult->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '정상적으로 수정되었습니다.');
		} else {
			echo returnURLMsg($consult->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '요청처리중 장애가 발생하였습니다.');
		}
		
	} else if ($_REQUEST['cmd'] == 'answer') {
		
		$_POST = xss_clean($_POST);
		$_POST = fileupload('answerfilename', $_SERVER['DOCUMENT_ROOT'].$uploadPath, $_POST, true, $maxSaveSize);		// 첨부파일
		
		$r = $consult->answer($_POST);

		if ($r > 0) {
			echo returnURLMsg($consult->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '정상적으로 수정되었습니다.');
		} else {
			echo returnURLMsg($consult->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '요청처리중 장애가 발생하였습니다.');
		}
		
	} else if ($_REQUEST['cmd'] == 'groupDelete') {

		$no = $_REQUEST['no'];
		
		$r = 0;
		for ($i=0; $i<count($no); $i++) {
			$r += $consult->delete($no[$i]);
		}

		if ($r > 0) {
			echo returnURLMsg($consult->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '총 '.$r.'건이 삭제되었습니다.');
		} else {
			echo returnURLMsg($consult->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '요청처리중 장애가 발생하였습니다.');
		}

	} else if ($_REQUEST['cmd'] == 'delete') {

		$no = $_REQUEST['no'];
		
		$r = $consult->delete($no);

		if ($r > 0) {
			echo returnURLMsg($consult->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '정상적으로 삭제되었습니다.');
		} else {
			echo returnURLMsg($consult->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '요청처리중 장애가 발생하였습니다.');
		}
	} else if($_REQUEST['cmd'] == "groupUnSpam"){
		$no = $_REQUEST['no'];
		$isspam = 1;
		$r = 0;
		for($i = 0 ; $i < count($no); $i++){
			$r += $consult->updateSpam($no[$i], $isspam);
		}

		if ($r > 0) {
			echo returnURLMsg($consult->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '총 '.$r.'건이 스팸해제되었습니다.');
		} else {
			echo returnURLMsg($consult->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '요청처리중 장애가 발생하였습니다.');
		}

	} else if($_REQUEST['cmd'] == "groupSpam"){
		$no = $_REQUEST['no'];
		$isspam = 2;
		$r = 0;
		for($i = 0 ; $i < count($no); $i++){
			$r += $consult->updateSpam($no[$i], $isspam);
		}

		if ($r > 0) {
			echo returnURLMsg($consult->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '총 '.$r.'건이 스팸처리되었습니다.');
		} else {
			echo returnURLMsg($consult->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '요청처리중 장애가 발생하였습니다.');
		}

	}


} else {
	echo returnURLMsg($consult->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '요청처리중 장애가 발생하였습니다.1');
}
?>
</body>
</html>