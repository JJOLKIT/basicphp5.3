<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/common.php"; ?>
<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/Formmail.class.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/email/SendMail.class.php";

include $_SERVER['DOCUMENT_ROOT']."/admin/include/loginCheck.php";
include "config.php";

$notice = new Formmail($pageRows, $tablename, $_REQUEST);
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

		$r = $notice->insert($_POST);
		if ($r > 0) {
			echo returnURLMsg($notice->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '정상적으로 저장되었습니다.');
		} else {
			echo returnURLMsg($notice->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '요청처리중 장애가 발생하였습니다.');
		}

	} else if ($_REQUEST['cmd'] == 'edit') {
		$_POST = xss_clean($_POST);

		$_POST = fileupload('filename', $_SERVER['DOCUMENT_ROOT'].$uploadPath, $_POST, true, $maxSaveSize);		// 첨부파일
		$_POST = fileupload('moviename', $_SERVER['DOCUMENT_ROOT'].$uploadPath, $_POST, false, $maxSaveSize);	// 동영상
		$data = $notice->getData($_POST[no], false);
		
		if ($_POST['sendEmail'] == '1') {
			$title = "[".COMPANY_NAME."] 답변입니다.";
			$contents = "
					<table border='0' cellpadding='0' cellspacing='0' width='100%' style='font-family:굴림; font-size: 12px; color: #4b4b4b; border:#CCCCCC 1px solid;'>
					<tr height='30'>
						<td width=90 style='font-weight:bold; padding-left:5px; border-right:#CCCCCC 1px solid; border-bottom:#CCCCCC 1px solid; background-color: #f4f8fd;'>회사명</td>
						<td style='padding-left:5px; border-bottom:#CCCCCC 1px solid;'>".$data['company']."</td>
					</tr>
					<tr height='30'>
						<td width=90 style='font-weight:bold; padding-left:5px; border-right:#CCCCCC 1px solid; border-bottom:#CCCCCC 1px solid; background-color: #f4f8fd;'>담당자명</td>
						<td style='padding-left:5px; border-bottom:#CCCCCC 1px solid;'>".$data['name']."</td>
					</tr>
					<tr height='30'>
						<td width=90 style='font-weight:bold; padding-left:5px; border-right:#CCCCCC 1px solid; border-bottom:#CCCCCC 1px solid; background-color: #f4f8fd;'>연락처</td>
						<td style='padding-left:5px; border-bottom:#CCCCCC 1px solid;'>".$data['cell']."</td>
					</tr>
					<tr height='30'>
						<td width=90 style='font-weight:bold; padding-left:5px; border-right:#CCCCCC 1px solid; border-bottom:#CCCCCC 1px solid; background-color: #f4f8fd;'>이메일</td>
						<td style='padding-left:5px; border-bottom:#CCCCCC 1px solid;'>".$data['email']."</td>
					</tr>
					<tr height='30'>
						<td style='font-weight:bold; padding-left:5px; border-right:#CCCCCC 1px solid; border-bottom:#CCCCCC 1px solid; background-color: #f4f8fd;'>내용</td>
						<td style='padding-left:5px; border-bottom:#CCCCCC 1px solid;'>".$data['contents']."</td>
					</tr>
					<tr height='30'>
						<td style='font-weight:bold; padding-left:5px; border-right:#CCCCCC 1px solid; border-bottom:#CCCCCC 1px solid; background-color: #f4f8fd;'>답변</td>
						<td style='padding-left:5px; border-bottom:#CCCCCC 1px solid;'>".$_REQUEST['answer']."</td>
					</tr>
					</table>";
			
			$mailForm = getURLMakeMailForm($_SERVER['DOCUMENT_ROOT'], EMAIL_FORM);
			$mailForm = str_replace(":SUBJECT", $title, $mailForm);
			$mailForm = str_replace(":CONTENT", $contents, $mailForm);
			
			$sendmail = new SendMail();
			$sendmail->send(COMPANY_EMAIL, COMPANY_NAME, $data['email'], $title, $mailForm);
			
	
		}

		$r = $notice->update($_POST);

		if ($r > 0) {
			echo returnURLMsg($notice->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '정상적으로 수정되었습니다.');
		} else {
			echo returnURLMsg($notice->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '요청처리중 장애가 발생하였습니다.');
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