<? include_once $_SERVER['DOCUMENT_ROOT']."/include/common.php"; ?>
<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

include_once $_SERVER['DOCUMENT_ROOT']."/lib/member/Member.class.php";

include_once $_SERVER['DOCUMENT_ROOT']."/lib/email/SendMail.class.php";
include "config.php";

$member = new Member(99, 'member', $_REQUEST);

$cnt = $member->getCheckEmailName($_REQUEST['id'], $_REQUEST['name'], $_REQUEST['email']);

if($cnt > 0){
	$code = generateRandomString(10);

	$_SESSION['code'] = $code;

	$title = "[".COMPANY_NAME."] 휴면 해제 인증코드입니다.";
	$contents = "
				<table border='0' cellpadding='0' cellspacing='0' width='100%' style='font-family:굴림; font-size: 12px; color: #4b4b4b; border:#CCCCCC 1px solid;'>
				<tr height='30'>
					<td style='padding-left:5px; border-bottom:#CCCCCC 1px solid;' colspan='2'>인증코드 : ".$code."</td>
				</tr>
				<tr height='30'>
					<td style='padding-left:5px; border-bottom:#CCCCCC 1px solid;' colspan='2'>홈페이지에서 인증을 완료해 주세요!</td>
				</tr>

				</table>";
	
	$mailForm = file_get_contents_curl(COMPANY_URL.EMAIL_FORM);
	$mailForm = str_replace(":SUBJECT", $title, $mailForm);
	$mailForm = str_replace(":CONTENT", $contents, $mailForm);
		
	$sendmail = new SendMail();

	$sendmail->send(COMPANY_EMAIL, COMPANY_NAME, $_REQUEST['email'], $title, $mailForm);


	echo "success";
}else{
	echo "fail";
}



?>