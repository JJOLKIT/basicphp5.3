<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/common.php"; ?>
<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";


include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/Bot.class.php";

include $_SERVER['DOCUMENT_ROOT']."/admin/include/loginCheck.php";
include "config.php";

$product = new Bot($pageRows, $tablename, $_REQUEST);

$today = getToday();
$oneWeek = getDayDateAdd(-7, $today);
$oneMonth = getMonthDateAdd(-1, $today);
$twoMonth = getMonthDateAdd(-2, $today);
$threeMonth = getMonthDateAdd(-3, $today);

$data = $product->getData($_REQUEST['no'], false);


?>
<!doctype html>
<html lang="ko">
<head>
<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/headHtml.php"; ?>
<script>
var oEditors; // 에디터 객체 담을 곳
$(window).load(function() {
	oEditors = setEditor("contents"); // 에디터 셋팅
	
});

function goSave() {

	
	
	oEditors.getById["contents"].exec("UPDATE_CONTENTS_FIELD", []);
	$("#board").submit();
}



</script>

</head>


<body>
<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/header.php"; ?>
<!-- s:warp -->
	<div class="contWrap">
		<div class="titWrap">
			<h2>챗봇 메세지 수정</h2>
		</div>
		<div class="write">
		<form method="post" name="board" id="board" action="<?=getSslCheckUrl($_SERVER['REQUEST_URI'], 'process.php')?>" enctype="multipart/form-data">
			<div class="wr_box">
				<h3>기본정보</h3>
				<table>
					<colgroup>
						<col width="8%">
						<col width="*">
					</colgroup>
					<tbody>
					<tr>
						<th>버튼명</th>
						<td>
							<input type="text" name="title" value="<?=$data['title']?>"/>
						</td>
					</tr>
					<tr>
						<th>메세지</th>
						<td>
							<textarea name="contents" id="contents" rows="5" ><?=$data['contents']?></textarea>
						</td>
					</tr>

					<tr>
						<th>링크버튼명</th>
						<td><input type="text" name="relation_title" value="<?=$data['relation_title']?>"/></td>
					</tr>
					<tr>
						<th>링크</th>
						<td><input type="text" name="relation_url" value="<?=$data['relation_url']?>"/></td>
					</tr>
		
					</tbody>
				</table>
			</div>
			
		</div>
		<input type="hidden" name="cmd" id="cmd" value="edit"/>
		<input type="hidden" name="no" id="no" value="<?=$data['no'] ?>"/>
		<?=$product->getQueryStringToHidden($_REQUEST) ?>
		</form>
		<!-- //write -->
		<div class="btnSet clear">
		<!--
			<a href="write_copy.php?no=<?=$data['no']?>" class="btn">복사하기</a>
			-->
			<a href="javascript:;" class="btn hoverbg save" onclick="goSave();">수정</a>
			<a href="index.php" class="btn hoverbg">취소</a>
		</div>
		<!-- //btnSet -->
	</div>
	<!-- //contents -->
</div>
<!-- e:warp --> 
<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/footer.php"; ?>
</body>
</html>
