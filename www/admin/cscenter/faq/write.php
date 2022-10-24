<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/common.php"; ?>
<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/Faq.class.php";

include $_SERVER['DOCUMENT_ROOT']."/admin/include/loginCheck.php";
include "config.php";

$faq = new Faq($pageRows, $tablename, $category_tablename, $_REQUEST);
$rowPageCount = $faq->getCount($_REQUEST);
$result = $faq->getList($_REQUEST);
$category_result = $faq->getCategoryList($_REQUEST);
?>
<!doctype html>
<html lang="ko">
<head>
<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/headHtml.php"; ?>
<script>
	var oEditors; // 에디터 객체 담을 곳
	jQuery(window).load(function(){
		oEditors = setEditor("contents"); // 에디터 셋팅

		// 달력
		initCal({id:"registdate",type:"day",today:"y",timeYN:"y"});
	});
	
	function goSave() {
		<? if($category_use) { ?>
		if ($("#category_fk").val() == 0) {
			alert('카테고리를 선택해 주세요.');
			$("#category_fk").focus();
			return false;
		}
		<? } ?>
		if ($("#title").val() == "") {
			alert('제목을 입력해 주세요.');
			$("#title").focus();
			return false;
		}
		var sHTML = oEditors.getById["contents"].getIR();
		if (sHTML == "") {
			alert('내용을 입력해 주세요.');
			$("#contents").focus();
			return false;
		} else {
			oEditors.getById["contents"].exec("UPDATE_CONTENTS_FIELD", []);	// 에디터의 내용이 textarea에 적용됩니다.
		}
		$("#frm").submit();
	}
</script>
</head>


<body>
<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/header.php"; ?>
<!-- s:warp -->
	<div class="contWrap">
		<div class="titWrap">
			<h2><?=$pageTitle ?> 글쓰기</h2>
		</div>
		<div class="write">
		<form method="post" name="frm" id="frm" action="<?=getSslCheckUrl($_SERVER['REQUEST_URI'], 'process.php')?>" enctype="multipart/form-data">
			<div class="wr_box">
				<h3>등록정보</h3>
				<table class="row_line">
					<colgroup>
						<col width="8%">
						<col width="42%">
						<col width="8%">
						<col width="42%">
					</colgroup>
					<tbody>
					<tr>
						<th>작성일</th>
						<td>
							<p class="calendar">
								<input type="text" name="registdate" id="registdate"  value="" class="dateTime" />
								<span id="CalregistdateIcon">
									<span class="material-icons" id="CalregistdateIconImg">calendar_month</span>
								</span>
							</p>
						</td>
						<th>노출설정</th>
						<td>
							<input type="checkbox" name="top" value="1" /> 탑공지 (상단노출) <span class="h_line"></span>
							<input type="checkbox" name="newicon" value="1" /> 새글 (New 아이콘 노출)
						</td>
					</tr>
					</tbody>
				</table>
			</div>
			<!-- //wr_box -->
			<div class="wr_box">
				<h3>게시글</h3>
				<table>
					<colgroup>
						<col width="8%">
						<col width="*">
					</colgroup>
					<tbody>
					<? if($category_use) { ?>
					<tr>
						<th>분류</th>
						<td>
							<span class="select">
								<select name="category_fk" id="category_fk">
									<? while ($row=mysql_fetch_assoc($category_result)) { ?>
									<option value="<?=$row[no]?>"/><?=$row[name]?></option>
									<? } ?>
								</select>
							</span>
						</td>
					</tr>
					<? } ?>
					<tr>
						<th>제목</th>
						<td>
							<input type="text" name="title" id="title"  value="" />
						</td>
					</tr>
					<tr>
						<th>내용</th>
						<td>
							<textarea name="contents" id="contents" rows="10" ></textarea>
						</td>
					</tr>
					<tr>
						<th>첨부파일</th>
						<td>
							<input  type="file" name="filename" id="filename"  />
						</td>
					</tr>
					</tbody>
				</table>
			</div>
			<!-- //wr_box -->
		</div>
		<input type="hidden" name="cmd" value="write" />
		</form>
		<!-- //write -->
		<div class="btnSet">
			<a href="javascript:;" class="btn hoverbg save" onclick="goSave();">저장</a>
			<a href="<?=$faq->getQueryString('index.php', 0, $_REQUEST)?>" class="btn hoverbg">취소</a>
		</div>
		<!-- //btnSet -->
	</div>
	<!-- //contents -->
</div>
<!-- e:warp --> 
<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/footer.php"; ?>
</body>
</html>
