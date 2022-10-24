<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/common.php"; ?>
<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/GalleryCt.class.php";

include $_SERVER['DOCUMENT_ROOT']."/admin/include/loginCheck.php";
include "config.php";

$pageTitle .= " 관리";

$notice = new GalleryCt($pageRows, $tablename, $category_tablename, $_REQUEST);
$rowPageCount = $notice->getCategoryCount($_REQUEST);
$category_result = $notice->getCategoryList($_REQUEST);
?>
<!doctype html>
<html lang="ko">
<head>
<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/headHtml.php"; ?>
<script>
function goSave() {
	if ($("#wri_name").val() == '') {
		alert("분류명을 입력하세요");
		$("#wri_name").focus();
		return false;
	}
	$("#wri").submit();
}

function groupDelete() {	
	if (isSeleted(document.frm.no) ){
		document.frm.submit();
	} else {
		alert("삭제할 항목을 하나 이상 선택해 주세요.");
	}
}

function goEdit(no, name) {
	var tName = $("#frm #"+name+"").val();
	location.href="process.php?cmd=editCategory&no="+no+"&title="+tName;
}
</script>
</head>


<body>
<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/header.php"; ?>
<!-- s:warp -->
	<div class="contWrap">
		<div class="titWrap">
			<h2><?=$pageTitle?></h2>
		</div>
		<div class="searchWrap">
			<table class="searchTable">
				<caption> 카테고리 등록 </caption>
					<colgroup>
						<col width="8%" />
						<col width="*" />
					</colgroup>
				<tbody>
				<form name="wri" id="wri" action="<?=getSslCheckUrl($_SERVER['REQUEST_URI'], 'process.php')?>" method="post" enctype="multipart/form-data" >
					<tr>
						<th>카테고리명</th>
						<td>
							<input type="text" name="title" id="wri_name"/>
							<input type="button" value="등록" onclick="goSave();" class="hoverbg"/>
						</td>
					</tr>
					<input type="hidden" name="cmd" value="writeCategory"/>
				</form>
				</tbody>
			</table>
		</div>
		<div class="write mt30">
			<div class="wr_box">
				<h3>카테고리 목록</h3>
				<form name="frm" id="frm" action="process.php" method="post">
				<table>
					<colgroup>
						<col width="8%">
						<col width="*">
						<col width="10%">
					</colgroup>
					<tbody>
					<? if ($rowPageCount[0] == 0) { ?>
						<tr>
							<td colspan="3" align="center">등록된 분류가 없습니다.</td>
						</tr>
					<?
						 } else {
							 $i = 0;
							 while ($row=mysql_fetch_assoc($category_result)) {
					?>
					<tr>
						<th>카테고리명</th>
						<td>
							<input type="text" name="name<?=$i?>" id="name<?=$i?>"  value="<?=$row[title]?>" />
						</td>
						<td>
							<input type="button" value="수정" onclick="goEdit('<?=$row[no]?>', 'name<?=$i?>');" class="hoverbg"/>
							<input type="button" value="삭제" onclick="delConfirm('삭제하시겠습니까?','process.php?cmd=deleteCategory&no=<?=$row[no]?>')" class="hoverbg"/>
						</td>
					</tr>
					<?
							$i++;
							}
						 }
					?>
					</tbody>
				</table>
				<input type="hidden" name="cmd" id="cmd" value="groupDelete"/>
				</form>
			</div>
			<!-- //wr_box -->
		</div>
		<!-- //write -->
		<div class="btnSet clear">
			<a href="index.php" class="btn hoverbg">갤러리 목록</a>
		</div>
		<!-- //btnSet -->
	</div>
	<!-- //contents -->
</div>
<!-- e:warp --> 
<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/footer.php"; ?>
</body>
</html>
