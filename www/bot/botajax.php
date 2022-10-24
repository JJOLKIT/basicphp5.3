<? include_once $_SERVER['DOCUMENT_ROOT']."/include/common.php"; ?>
<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/Bot.class.php";

$bot = new Bot(9999, 'bot', $_REQUEST);
$result = $bot->getList($_REQUEST);
$data = $bot->getData($_REQUEST['no'], false);
?>
<div class="bot">
	<p class="title"><span>상공 고객센터</span></p>
	<div class="msg">
	<p><span><?=$data['title']?></span></p>
	
	<?=$data['contents']?>
	
	<?
		if($data['relation_url'] != ""){
	?>
	<!--
	<p class="bt_wrap"><a href="javascript:;" onclick="goLocation('<?=$data['relation_url']?>');" style="width:94%;"><?=$data['relation_title']?></a></p>
	-->
	<p class="bt_wrap"><a href="<?=$data['relation_url']?>" target="_blank" style="width:94%;"><?=$data['relation_title']?></a></p>
	<?}else{?>
	<!--
	<p class="bt_wrap "><a href="javascript:;" onclick="goLocation('http://sanggong.co.kr');">제작문의</a><a href="tel:02.6925.6865">02.6925.6865</a></p>
	-->
	<p class="bt_wrap "><a href="http://sanggong.co.kr" target="_blank">제작문의</a><a href="tel:02.6925.6865">02.6925.6865</a></p>
	<?}?>
	</div>

	

</div>

<div class="bot">
	<p class="title"><span>반갑습니다! 상공 상담AI입니다. 무엇을 도와드릴까요?<span></p>
	<ul>
		
		<?while($row=mysql_fetch_assoc($result)){?>
		<li><a href="javascript:;" onclick="nextMsg(<?=$row['no']?>, '<?=$row[title]?>');"><?=$row['title']?></a></li>
		<?}?>
	</ul>
</div>