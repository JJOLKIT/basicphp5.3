<? include_once $_SERVER['DOCUMENT_ROOT']."/include/common.php"; ?>
<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/email/SendMail.class.php";

include_once $_SERVER['DOCUMENT_ROOT']."/lib/member/Member.class.php";

$member = new Member(999, 'member', array());


//1년 지난 회원 분리처리
$r = $member->updateGarbage(); 

if($r != 0 ){
	
	$title = "회원님의 계정이 장기간 미접속으로 휴면처리 되었습니다.";
	$contents = "
				<table border='0' cellpadding='0' cellspacing='0' width='100%' style='font-family:굴림; font-size: 12px; color: #4b4b4b; border:#CCCCCC 1px solid;'>
				<tr height='30'>
					<td style='font-weight:bold; padding-left:5px; border-right:#CCCCCC 1px solid; border-bottom:#CCCCCC 1px solid; background-color: #f4f8fd;'>내용</td>
					<td style='padding-left:5px; border-bottom:#CCCCCC 1px solid;'>고객님의 계정이 장기간 미접속으로 휴면처리 되었습니다.</td>
				</tr>
				<tr height='50'>	
					<td colspan='2' >
						고객님의 개인 정보 보호를 위해 유효기간(1년) 동안 서비스 이용기록이 없는 회원의 개인 정보를 파기 또는 분리보관 시켜야 하는 제도입니다. 로그인 기록을 기준으로 하여, <b>로그인 기록이 1년 동안 없는 회원</b>들은 <b>자동으로 휴면 회원</b>으로 처리하여 개인 정보를 분리 보관합니다.
						<br/><br/>
						휴면 시작일 : ".Date('Y-m-d')."
					</td>
				</tr>
				<tr height='30'>
					<td colspan='2'>".COMPANY_NAME."은 회원님의 소중한 개인정보 보호를 위해 항상 노력하고 있습니다.<br/><br/>감사합니다.</td>
				</tr>
				</table>";
	
	$mailForm = file_get_contents_curl(COMPANY_URL.EMAIL_FORM);
	$mailForm = str_replace(":SUBJECT", $title, $mailForm);
	$mailForm = str_replace(":CONTENT", $contents, $mailForm);
		
	$sendmail = new SendMail();

	while($row = mysql_fetch_assoc($r)){
		$sendmail->send(COMPANY_EMAIL, COMPANY_NAME, $row['email'], $title, $mailForm);

		//메일 횟수 설정
		//중복 메일 발송 방지
		$member->updateCntMail($row['no']);

	}

}







//3년지난 회원 완전삭제
$r2 = $member->deleteRealGarbage();

if($r2 != 0){
	$title = "회원님의 계정이 완전 삭제 처리되었습니다.";
	$contents = "
				<table border='0' cellpadding='0' cellspacing='0' width='100%' style='font-family:굴림; font-size: 12px; color: #4b4b4b; border:#CCCCCC 1px solid;'>
				<tr height='30'>
					<td style='font-weight:bold; padding-left:5px; border-right:#CCCCCC 1px solid; border-bottom:#CCCCCC 1px solid; background-color: #f4f8fd;'>내용</td>
					<td style='padding-left:5px; border-bottom:#CCCCCC 1px solid;'>고객님의 계정이 개인정보보호법에 의거, 3년간 미접속으로 계정이 완전 삭제 되었습니다.</td>
				</tr>
				
				</table>";
	
	$mailForm = file_get_contents_curl(COMPANY_URL.EMAIL_FORM);
	$mailForm = str_replace(":SUBJECT", $title, $mailForm);
	$mailForm = str_replace(":CONTENT", $contents, $mailForm);
		
	$sendmail = new SendMail();

	while($row = mysql_fetch_assoc($r2)){
		$sendmail->send(COMPANY_EMAIL, COMPANY_NAME, $row['email'], $title, $mailForm);
	}



}




//1년 메일 처리

$m1 = $member->month12Mail();
if($m1 != 0){
	$title = "회원님의 계정이 휴면상태 입니다.";
	$contents = "
				<table border='0' cellpadding='0' cellspacing='0' width='100%' style='font-family:굴림; font-size: 12px; color: #4b4b4b; border:#CCCCCC 1px solid;'>
				<tr height='30'>
					<td style='font-weight:bold; padding-left:5px; border-right:#CCCCCC 1px solid; border-bottom:#CCCCCC 1px solid; background-color: #f4f8fd;'>내용</td>
					<td style='padding-left:5px; border-bottom:#CCCCCC 1px solid;'>고객님의 계정이 휴면상태이며, 3년간 미접속시 고객님의 정보는 완전 파기 됩니다.</td>
				</tr>
				</table>";
	
	$mailForm = file_get_contents_curl(COMPANY_URL.EMAIL_FORM);
	$mailForm = str_replace(":SUBJECT", $title, $mailForm);
	$mailForm = str_replace(":CONTENT", $contents, $mailForm);
		
	$sendmail = new SendMail();

	while($row = mysql_fetch_assoc($m1)){
		$sendmail->send(COMPANY_EMAIL, COMPANY_NAME, $row['email'], $title, $mailForm);

		$member->updateMonth12($row['no']);
	}
}


//2년 메일 처리
/*
$m1 = $member->month24Mail();
if($m1 != 0){
	$title = "회원님의 계정이 휴면상태 입니다.";
	$contents = "
				<table border='0' cellpadding='0' cellspacing='0' width='100%' style='font-family:굴림; font-size: 12px; color: #4b4b4b; border:#CCCCCC 1px solid;'>
				<tr height='30'>
					<td style='font-weight:bold; padding-left:5px; border-right:#CCCCCC 1px solid; border-bottom:#CCCCCC 1px solid; background-color: #f4f8fd;'>내용</td>
					<td style='padding-left:5px; border-bottom:#CCCCCC 1px solid;'>고객님의 계정이 2년째 휴면상태이며, 3년간 미접속시 고객님의 정보는 완전 파기 됩니다.</td>
				</tr>
				</table>";
	
	$mailForm = file_get_contents_curl(COMPANY_URL.EMAIL_FORM);
	$mailForm = str_replace(":SUBJECT", $title, $mailForm);
	$mailForm = str_replace(":CONTENT", $contents, $mailForm);
		
	$sendmail = new SendMail();

	while($row = mysql_fetch_assoc($m1)){
		$sendmail->send(COMPANY_EMAIL, COMPANY_NAME, $row['email'], $title, $mailForm);

		$member->updateMonth24($row['no']);
	}
}
*/


?>

