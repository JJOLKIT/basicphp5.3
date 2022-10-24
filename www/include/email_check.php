<?session_start();
include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

include_once $_SERVER['DOCUMENT_ROOT']."/lib/member/Member.class.php";

$member = new Member(999, "member", $_REQUEST);
$r = "";

if ($_REQUEST['email']) {
	$cnt = $member->checkEmail($_REQUEST['email']);
	if ($cnt == 0) {
		$r = "true";
	} else {
		$r = "false";
	}
}
?>
<?=$r?>