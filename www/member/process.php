<? include_once $_SERVER['DOCUMENT_ROOT']."/include/common.php"; ?>
<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

include_once $_SERVER['DOCUMENT_ROOT']."/lib/member/Member.class.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/email/SendMail.class.php";

$member = new Member(9999, "member", $_REQUEST);
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
		$_POST = xss_clean($_POST);


		$r = $member->insert($_POST);
		if ($r > 0) {

			$data = $member->loginMember($_POST['id']);

			//로그인시간 저장
			$member->updateLoginTime($data['no']);


			$_SESSION['member_no'] = $data['no'];
			$_SESSION['member_id'] = $data['id'];
			$_SESSION['member_name'] = $data['name'];
			$_SESSION['member_email'] = $data['email'];
			$_SESSION['member_cell'] = $data['cell'];

			echo returnURLMsg(LOGIN_AFTER_PAGE, '정상적으로 회원가입되었습니다.');
		} else {
			//echo returnURLMsg('write.php', '요청처리중 장애가 발생하였습니다.');
		}

	} else if ($_REQUEST['cmd'] == 'edit') {
		$_POST = xss_clean($_POST);

		$r = $member->update($_POST);
		if ($r > 0) {
			echo returnURLMsg('/member/index.php', '정상적으로 수정되었습니다.');
		} else {
			echo returnURLMsg('/member/index.php', '요청처리중 장애가 발생하였습니다.');
		}

	} else if ($_REQUEST['cmd'] == 'secede') {	// 탈퇴처리
		if ($member->checkLogin($_REQUEST['id'], $_REQUEST['password']) > 0) {
			$data = $member->loginMember($_REQUEST[id]);
			$r = $member->updateSecession($data[no]);
			
			if ($r > 0) {
				echo returnURLMsg('secede.php', '정상적으로 탈퇴되었습니다.');
			} else {
				echo returnURLMsg('secede.php', '탈퇴처리를 실패하였습니다.');
			}
		} else {
			echo returnURLMsg('secede.php', '아이디를 확인해주세요.');
		}
	} else if ($_REQUEST[cmd] == 'searchid') {	// 아이디 찾기
		$_POST = xss_clean($_POST);

		$data = $member->searchid($_POST[name], $_POST[email]);

		if ($data) {
			$title = "[".COMPANY_NAME."]".$data[name]."님의 아이디입니다.";
			$contents = "<br/>아이디 : ".$data[id];

			//$mailForm = getURLMakeMailForm(COMPANY_URL, EMAIL_FORM);
			$mailForm = file_get_contents_curl(COMPANY_URL.EMAIL_FORM);
			$mailForm = str_replace(":SUBJECT", $title, $mailForm);
			$mailForm = str_replace(":CONTENT", $contents, $mailForm);

			$sendmail = new SendMail();
			$sendmail->send(COMPANY_EMAIL, COMPANY_NAME, $_POST['email'], $title, $mailForm);

			echo returnURLMsg('idpwsearch.php?init=id', '메일 발송이 정상적으로 처리되었습니다.');

		} else {
			echo returnURLMsg('idpwsearch.php?init=id', '입력하신 정보는 존재 하지 않습니다.');
		}

	} else if ($_REQUEST[cmd] == 'searchpw') {	// 패스워드 찾기
		$_POST = xss_clean($_POST);

		$data = $member->searchPw($_POST[name], $_POST[email], $_POST[id]);
		$temppass = getRandomStr(10);


		if ($temppass && $member->updateTempPass($data[no], $temppass)) {
			$title = "[".COMPANY_NAME."]".$data[name]."님의 임시비밀번호입니다.";
			$contents = "<br/>임시비밀번호 : ".$temppass;

			//$mailForm = getURLMakeMailForm(COMPANY_URL, EMAIL_FORM);
			$mailForm = file_get_contents_curl(COMPANY_URL.EMAIL_FORM);
			$mailForm = str_replace(":SUBJECT", $title, $mailForm);
			$mailForm = str_replace(":CONTENT", $contents, $mailForm);

			$sendmail = new SendMail();
			$sendmail->send(COMPANY_EMAIL, COMPANY_NAME, $_POST['email'], $title, $mailForm);

			echo returnURLMsg('idpwsearch.php?init=pw', '임시비밀번호가 정상적으로 메일발송되었습니다.');
		} else {
			echo returnURLMsg('idpwsearch.php?init=pw', '임시비밀번호 생성 실패');
		}
	} else if($_REQUEST['cmd'] == "return"){
		$r = $member->returnGarbage($_REQUEST);
		
		if($r > 0){
			$title = "[".COMPANY_NAME."]".$_REQUEST['name']."님의 계정이 휴면 해제 되었습니다.";
			$contents = "
					<table border='0' cellpadding='0' cellspacing='0' width='100%' style='font-family:굴림; font-size: 12px; color: #4b4b4b; border:#CCCCCC 1px solid;'>
					
					<tr height='30'>
						<td style='font-weight:bold; padding-left:5px; border-right:#CCCCCC 1px solid; border-bottom:#CCCCCC 1px solid; background-color: #f4f8fd;'>내용</td>
						<td style='padding-left:5px; border-bottom:#CCCCCC 1px solid;'>".$_REQUEST['name']."님의 계정이 정상적으로 휴면 해제처리 되었습니다. <br/><br/>지금부터 정상적으로 서비스 이용이 가능합니다. <br/><br/>감사합니다.</td>
					</tr>
					</table>";

			//$mailForm = getURLMakeMailForm(COMPANY_URL, EMAIL_FORM);
			$mailForm = file_get_contents_curl(COMPANY_URL.EMAIL_FORM);
			$mailForm = str_replace(":SUBJECT", $title, $mailForm);
			$mailForm = str_replace(":CONTENT", $contents, $mailForm);

			$sendmail = new SendMail();
			$sendmail->send(COMPANY_EMAIL, COMPANY_NAME, $_REQUEST['email'], $title, $mailForm);

			echo returnURLMsg("/member/login.php", '정상적으로 휴면 해제 되었습니다.');
		}else{
			echo returnURLMsg("garbage.php", '요청처리중 장애가 발생하였습니다.');
		}
	}

} else {
	echo returnURLMsg("/index.php", '요청처리중 장애가 발생하였습니다.1');
}
?>
</body>
</html>