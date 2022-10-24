<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/common.php"; ?>
<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/Reply2.class.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/Spam.class.php";
$spam = new Spam(999, 'spam', $_REQUEST);
include "config.php";

$notice = new Reply2($pageRows, $tablename, $_REQUEST);
?>
<!doctype html>
<html lang="ko">
<head>
<? include $_SERVER['DOCUMENT_ROOT']."/include/headHtml.php" ?>
</head>
<body>
<?
if (checkReferer($_SERVER["HTTP_REFERER"])) {
	
	if ($_REQUEST['cmd'] == 'write') {
		$n = $spam->checkWords($_REQUEST);
		if($n > 0){
			echo "<script>alert('부적절한 단어가 존재합니다. 다시 확인해주세요.'); history.back();</script>";
		}else{
			if($_SESSION['capt'] != $_POST['zsfCode']){
				echo "<script>alert('스팸방지코드가 일치하지 않습니다.'); history.back();</script>";
				exit;
			}else{

				$_POST = xss_clean($_POST);

				$_POST = fileupload('filename', $_SERVER['DOCUMENT_ROOT'].$uploadPath, $_POST, true, $maxSaveSize);		// 첨부파일
				$_POST = fileupload('moviename', $_SERVER['DOCUMENT_ROOT'].$uploadPath, $_POST, false, $maxSaveSize);	// 동영상

				$r = $notice->insert($_POST);
				unset($_SESSION['capt']);
				if ($r > 0) {
					echo returnURLMsg($notice->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '정상적으로 저장되었습니다.');
				} else {
					echo returnURLMsg($notice->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '요청처리중 장애가 발생하였습니다.');
				}


			}
		}
		

	} else if ($_REQUEST['cmd'] == 'reply') {
		$n = $spam->checkWords($_REQUEST);
		if($n > 0){
			echo "<script>alert('부적절한 단어가 존재합니다. 다시 확인해주세요.'); history.back();</script>";
		}else{

			if($_SESSION['capt'] != $_POST['zsfCode']){
				echo "<script>alert('스팸방지코드가 일치하지 않습니다.'); history.back();</script>";
				exit;
			}else{

				$_POST = xss_clean($_POST);

				$_POST = fileupload('filename', $_SERVER['DOCUMENT_ROOT'].$uploadPath, $_POST, true, $maxSaveSize);		// 첨부파일
				$_POST = fileupload('moviename', $_SERVER['DOCUMENT_ROOT'].$uploadPath, $_POST, false, $maxSaveSize);		// 동영상

				$r = $notice->answer($_POST);
				if ($r > 0) {
					echo returnURLMsg($notice->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '정상적으로 저장되었습니다.');
				} else {
					echo returnURLMsg($notice->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '요청처리중 장애가 발생하였습니다.');
				}
			}
		}
		

	} else if ($_REQUEST['cmd'] == 'edit') {
		$n = $spam->checkWords($_REQUEST);
		if($n > 0){
			echo "<script>alert('부적절한 단어가 존재합니다. 다시 확인해주세요.'); history.back();</script>";
		}else{

		$_POST = xss_clean($_POST);
		$_POST = fileupload('filename', $_SERVER['DOCUMENT_ROOT'].$uploadPath, $_POST, true, $maxSaveSize);		// 첨부파일
		$_POST = fileupload('moviename', $_SERVER['DOCUMENT_ROOT'].$uploadPath, $_POST, false, $maxSaveSize);	// 동영상
		
		$r = $notice->update($_POST);

		if ($r > 0) {
			echo returnURLMsg($notice->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '정상적으로 수정되었습니다.');
		} else {
			echo returnURLMsg($notice->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '요청처리중 장애가 발생하였습니다.');
		
		}
		}
	} else if ($_REQUEST['cmd'] == 'groupDelete') {

		$no = $_REQUEST['no'];
		
		$r = 0;
		for ($i=0; $i<count($no); $i++) {
			$r += $notice->delete($no[$i]);
		}

		if ($r > 0) {
			echo returnURLMsg($notice->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '총 '.$r.'건이 삭제되었습니다.');
		} else {
			echo returnURLMsg($notice->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '요청처리중 장애가 발생하였습니다.');
		}

	} else if ($_REQUEST['cmd'] == 'delete') {

		$no = $_REQUEST['no'];
		
		$r = $notice->delete($no);

		if ($r > 0) {
			echo returnURLMsg($notice->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '정상적으로 삭제되었습니다.');
		} else {
			echo returnURLMsg($notice->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '요청처리중 장애가 발생하였습니다.');
		}
	}


} else {
	echo returnURLMsg($notice->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '요청처리중 장애가 발생하였습니다.1');
}
?>
</body>
</html>