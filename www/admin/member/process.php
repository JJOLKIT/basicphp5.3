<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/common.php"; ?>
<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

include_once $_SERVER['DOCUMENT_ROOT']."/lib/member/Member.class.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/order/Point.class.php";

include $_SERVER['DOCUMENT_ROOT']."/admin/include/loginCheck.php";
include "config.php";

$member = new Member($pageRows, "member", $_REQUEST);
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

		$r = $member->insert($_REQUEST);
		if ($r > 0) {
			echo returnURLMsg($member->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '정상적으로 저장되었습니다.');
		} else {
			echo returnURLMsg($member->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '요청처리중 장애가 발생하였습니다.');
		}

	} else if ($_REQUEST['cmd'] == 'edit') {

		$r = $member->update($_REQUEST);

		if ($r > 0) {
			echo returnURLMsg($member->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '정상적으로 수정되었습니다.');
		} else {
			echo returnURLMsg($member->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '요청처리중 장애가 발생하였습니다.');
		}
	} else if ($_REQUEST['cmd'] == 'secession') {	// 탈퇴처리
		$delNo = $_REQUEST['no'];
		$data = $member->getData($_REQUEST['no']);
		$no = $member->insertSecede($data);
		if ($no) {
			$r = $member->delete($delNo);
			if ($r > 0) {
				echo returnURLMsg($member->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '정상적으로 탈퇴되었습니다.');
			} else {
				echo returnURLMsg($member->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '탈퇴처리를 실패하였습니다.');
			}
		} else {
			echo returnURLMsg($member->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '요청처리중 장애가 발생하였습니다.');
		}

	} else if ($_REQUEST['cmd'] == 'groupDelete') {

		$no = $_REQUEST['no'];
		
		$r = 0;
		for ($i=0; $i<count($no); $i++) {
			$r += $member->delete($no[$i]);
		}

		if ($r > 0) {
			echo returnURLMsg($member->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '총 '.$r.'건이 삭제되었습니다.');
		} else {
			echo returnURLMsg($member->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '요청처리중 장애가 발생하였습니다.');
		}

	} else if ($_REQUEST['cmd'] == 'delete') {

		$no = $_REQUEST['no'];
		
		$r = $member->delete($no);

		if ($r > 0) {
			echo returnURLMsg($member->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '정상적으로 삭제되었습니다.');
		} else {
			echo returnURLMsg($member->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '요청처리중 장애가 발생하였습니다.');
		}
	}  else if ($_REQUEST['cmd'] == 'groupDeleteSecede') {

		$no = $_REQUEST['no'];
		
		$r = 0;
		for ($i=0; $i<count($no); $i++) {
			$r += $member->deleteSecede($no[$i]);
		}

		if ($r > 0) {
			echo returnURLMsg($member->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '총 '.$r.'건이 삭제되었습니다.');
		} else {
			echo returnURLMsg($member->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '요청처리중 장애가 발생하였습니다.');
		}
	} else if ($_REQUEST['cmd'] == 'writePoint') {
		$point = new Point($pageRows, $_REQUEST);
		$r = $point->insert($_REQUEST);
		if ($r > 0) {
			if ($_REQUEST['type'] == 1) {
				$point->plusPoint($_REQUEST['point'], $_REQUEST['member_fk']);
			} else {
				$point->minusPoint($_REQUEST['point'], $_REQUEST['member_fk']);
			}
			echo returnURLMsgRefresh($point->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'pointList.php'), 0, $_REQUEST), '정상적으로 저장되었습니다.');
		} else {
			echo returnURLMsgRefresh($point->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'pointList.php'), 0, $_REQUEST), '요청처리중 장애가 발생하였습니다.');
		}

	}


} else {
	echo returnURLMsg($member->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '요청처리중 장애가 발생하였습니다.1');
}
?>
</body>
</html>