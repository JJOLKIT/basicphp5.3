<? include_once $_SERVER['DOCUMENT_ROOT']."/include/common.php"; ?>
<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";

include_once $_SERVER['DOCUMENT_ROOT']."/lib/member/Member.class.php";
?>
<!doctype html>
<html lang="ko">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<? include $_SERVER['DOCUMENT_ROOT']."/include/headHtml.php"; ?>
</head>
<body>
<?
$url = $_REQUEST['url'];			// 전달 받은 주소
$param = $_REQUEST['param'];		// 전달 받은 파라메터

// 파라메터가 있을 경우에는 url 뒷에 결합한다.
if ($param) {
	$url = $url."?".param;
}

$member = new Member(999, 'member', $_REQUEST);

//휴면계정 확인

$gcnt = $member->checkLoginGarbage($_REQUEST['id'], $_REQUEST['password']);


if($gcnt > 0){
	echo returnHistory("장기간 미접속으로 휴면계정처리 되었습니다.\\n휴면계정 해제후 이용 가능합니다.");
}else{
	$cnt = $member->checkLogin($_REQUEST['id'], $_REQUEST['password']);
	if ($cnt > 0) {
		$data = $member->loginMember($_REQUEST['id']);

		//로그인시간 저장
		$member->updateLoginTime($data['no']);


		$_SESSION['member_no'] = $data['no'];
		$_SESSION['member_id'] = $data['id'];
		$_SESSION['member_name'] = $data['name'];
		$_SESSION['member_email'] = $data['email'];
		$_SESSION['member_cell'] = $data['cell'];

		echo returnURL($url);
	} else {

		echo returnHistory("아이디 비밀번호를 확인해주세요.");
	}
}
?>
</body>
</html>