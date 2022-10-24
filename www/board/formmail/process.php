<? include_once $_SERVER['DOCUMENT_ROOT']."/include/common.php"; ?>
<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/Formmail.class.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/email/SendMail.class.php";

include "config.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/Spam.class.php";
$spam = new Spam(999, 'spam', $_REQUEST);
$notice = new Formmail($pageRows, $tablename, $_REQUEST);
?>
<!doctype html>
<html lang="ko">
<head>
<? include $_SERVER['DOCUMENT_ROOT']."/include/headHtml.php" ?>
</head>
<body>
<?
if (checkReferer($_SERVER["HTTP_REFERER"])) {
	$n = $spam->checkWords($_REQUEST);
	if ($_REQUEST['cmd'] == 'write') {
		if($n > 0){
			echo "<script>alert('부적절한 단어가 존재합니다.'); history.back();</script>";
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
					$title = "폼메일";
					$contents = "
							<table border='0' cellpadding='0' cellspacing='0' width='100%' style='font-family:굴림; font-size: 12px; color: #4b4b4b; border:#CCCCCC 1px solid;'>
							<tr height='30'>
								<td width=90 style='font-weight:bold; padding-left:5px; border-right:#CCCCCC 1px solid; border-bottom:#CCCCCC 1px solid; background-color: #f4f8fd;'>회사명</td>
								<td style='padding-left:5px; border-bottom:#CCCCCC 1px solid;'>".$_REQUEST['company']."</td>
							</tr>
							<tr height='30'>
								<td width=90 style='font-weight:bold; padding-left:5px; border-right:#CCCCCC 1px solid; border-bottom:#CCCCCC 1px solid; background-color: #f4f8fd;'>담당자명</td>
								<td style='padding-left:5px; border-bottom:#CCCCCC 1px solid;'>".$_REQUEST['name']."</td>
							</tr>
							<tr height='30'>
								<td width=90 style='font-weight:bold; padding-left:5px; border-right:#CCCCCC 1px solid; border-bottom:#CCCCCC 1px solid; background-color: #f4f8fd;'>연락처</td>
								<td style='padding-left:5px; border-bottom:#CCCCCC 1px solid;'>".$_REQUEST['cell']."</td>
							</tr>
							<tr height='30'>
								<td width=90 style='font-weight:bold; padding-left:5px; border-right:#CCCCCC 1px solid; border-bottom:#CCCCCC 1px solid; background-color: #f4f8fd;'>이메일</td>
								<td style='padding-left:5px; border-bottom:#CCCCCC 1px solid;'>".$_REQUEST['email']."</td>
							</tr>
							<tr height='30'>
								<td style='font-weight:bold; padding-left:5px; border-right:#CCCCCC 1px solid; border-bottom:#CCCCCC 1px solid; background-color: #f4f8fd;'>내용</td>
								<td style='padding-left:5px; border-bottom:#CCCCCC 1px solid;'>".$_REQUEST['contents']."</td>
							</tr>
							</table>";
						
					//$mailForm = getURLMakeMailForm(COMPANY_URL, EMAIL_FORM);
					$mailForm = file_get_contents_curl(COMPANY_URL.EMAIL_FORM);
					$mailForm = str_replace(":SUBJECT", $title, $mailForm);
					$mailForm = str_replace(":CONTENT", $contents, $mailForm);
						
					$sendmail = new SendMail();
					$sendmail->send(COMPANY_EMAIL, COMPANY_NAME, $_REQUEST['email'], $title, $mailForm);
					echo returnURLMsg($notice->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '정상적으로 저장되었습니다.');
				} else {
					echo returnURLMsg($notice->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '요청처리중 장애가 발생하였습니다.');
				}
			}

		}
	}


} else {
	echo returnURLMsg($notice->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '요청처리중 장애가 발생하였습니다.1');
}
?>
</body>
</html>