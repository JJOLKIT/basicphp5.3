<? include_once $_SERVER['DOCUMENT_ROOT']."/include/common.php"; ?>
<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/Chat.class.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/Spam.class.php";
$spam = new Spam(999, 'spam', $_REQUEST);
include "config.php";

$chat = new Chat($pageRows, $tablename, $_REQUEST);

if (checkReferer($_SERVER["HTTP_REFERER"])) {
	if($_REQUEST['cmd'] == "list"){
		if(!$_REQUEST['sstartdate'])
		{
			$_REQUEST['sstartdate'] = date('Y-m-d H:i:s');
		}

		$result = $chat->getList($_REQUEST);

		$date = $_REQUEST['sstartdate'];
		$data = array();
		while($row = mysql_fetch_assoc($result))
		{
			$date = $row['registdate'];
			$data[] = $row;
		}

		echo json_encode(array('date' => $date, 'data' => $data));
	}
	else if($_REQUEST['cmd'] == "write"){
		$r = $chat->insert($_REQUEST);

		


	}
}

?>