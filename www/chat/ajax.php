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

	
	if ($_REQUEST['cmd'] == 'write') {
		
		$n = $spam->checkWords($_REQUEST);
		if($n > 0){
			echo "spam";
		}else{
			
			$r = $chat->insert($_REQUEST);
			if($r > 0){
				echo "success";
			}else{
				echo "fail";
			}
			
		}
		

	}else if($_REQUEST['cmd'] == "list"){
		
		$_REQUEST['sgb'] = 1;

		$data = $chat->getLast();
		$rowPageCount = $chat->getCount($_REQUEST);
		//$_REQUEST['offset'] = $rowPageCount[0] - $rowPageCount[1];
		$_REQUEST['sstartdate'] = Date("Y-m-d H:i:s");
		$result = $chat->getList($_REQUEST);

		while($row = mysql_fetch_assoc($result)){
			?>
			<li class="clear response">
				<p><?=$row['registdate']?></p>
				<span><?=$row['name']?></span>
				<div class="msg"><?=$row['msg']?></div>
			</li>
			<?
		}


	}


} else {
	echo "error";
}
?>
