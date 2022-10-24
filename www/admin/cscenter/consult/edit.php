<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/common.php"; ?>
<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/Consult.class.php";

include $_SERVER['DOCUMENT_ROOT']."/admin/include/loginCheck.php";
include "config.php";

$consult = new Consult($pageRows, $tablename, $_REQUEST);
$data = ($consult->getData($_REQUEST[no], $userCon));
?>
<!doctype html>
<html lang="ko">
<head>
<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/headHtml.php"; ?>
<script>
var oEditors; // 에디터 객체 담을 곳
jQuery(window).load(function(){
	oEditors = setEditor("answer"); // 에디터 셋팅
	
});
function goSave() {

	if($('#frm #answer_name').val().trim() == ""){
		alert('답변자명을 입력해 주세요.');
		$('#frm #answer_name').focsu();
		return false;
	}

	if($('#frm #answer_title').val().trim() == ""){
		alert('답변 제목을 입력해 주세요.');
		$('#frm #answer_title').focus();
		return false;
	}


	

	if (confirm("저장하시겠습니까?")) {
		oEditors.getById["answer"].exec("UPDATE_CONTENTS_FIELD", []);	// 에디터의 내용이 textarea에 적용됩니다.
		$("#frm").submit();
	}
}
</script>
</head>


<body>
<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/header.php"; ?>
<!-- s:warp -->
	<div class="contWrap">
		<div class="titWrap">
			<h2><?=$pageTitle ?> 답변</h2>
		</div>
		<div class="write">
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
							<?=$data['registdate'] ?>
						</td>
						<th>작성자</th>
						<td>
							<?=escape_html($data['name']) ?>
						</td>
					</tr>
					<tr>
						<th>연락처</th>
						<td>
							<?=escape_html($data['cell']) ?>
						</td>
						<th>이메일</th>
						<td>
							<?=escape_html($data['email']) ?>
						</td>
					</tr>
					</tbody>
				</table>
			</div>
			<!-- //wr_box -->
			<div class="wr_box">
				<h3>상담글</h3>
				<table>
					<colgroup>
						<col width="8%">
						<col width="*">
					</colgroup>
					<tbody>
					<tr>
						<th>제목</th>
						<td>
							<?=escape_html($data['title']) ?>
						</td>
					</tr>
					<tr>
						<th>내용</th>
						<td>
							<?=$data['contents'] ?>
						</td>
					</tr>
					<tr>
						<th>첨부파일</th>
						<td>
							<? if ($data['filename']) { ?>
								<a href="/lib/download.php?path=<?=$uploadPath?>&vf=<?=$data['filename']?>&af=<?=urlencode($data['filename_org'])?>" target="_blank"><?=$data[filename_org]?></a>
							<? } ?>
						</td>
					</tr>
					</tbody>
				</table>
			</div>
			<!-- //wr_box -->
			<form name="frm" id="frm" action="process.php" method="post" enctype="multipart/form-data">
			<div class="wr_box">
				<h3>답변글</h3>
				<table>
					<colgroup>
						<col width="8%">
						<col width="*">
					</colgroup>
					<tbody>
					<tr>
						<th>답변자</th>
						<td><input type="text" name="answer_name" id="answer_name" value="<?=$_SESSION['admin_name']?>" class="wid200"/></td>
					</tr>
					<tr>
						<th>제목</th>
						<td><input type="text" name="answer_title" id="answer_title" value="<?=$data['answer_title']?>"/></td>
					</tr>
					<tr>
						<th>내용</th>
						<td>
							<textarea name="answer" id="answer" rows="10" ><?=$data['answer'] ?></textarea>
						</td>
					</tr>
					<tr>
						<th>첨부파일</th>
						<td>
							<? if ($data['answerfilename']) { ?>
								<input type="checkbox" name="answerfilename_chk" value="1"/> 기존파일삭제</br/>
								<a href="/lib/download.php?path=<?=$uploadPath?>&vf=<?=$data['answerfilename']?>&af=<?=$data['answerfilename_org']?>" target="_blank"><?=$data[answerfilename_org]?></a><br/>
							<? } ?>
							<input type="file" name="answerfilename" id="answerfilename"/>
						</td>
					</tr>
					</tbody>
				</table>
			</div>
			<input type="hidden" name="cmd" id="cmd" value="answer"/>
			<input type="hidden" name="no" id="no" value="<?=$data['no']?>"/>
			<?=$consult->getQueryStringToHidden($_REQUEST) ?>
			</form>
			<!-- //wr_box -->
		</div>
		<!-- //write -->
		<div class="btnSet clear">
			<a href="javascript:;" class="btn hoverbg save" onclick="goSave();">저장</a>
			<a href="<?=$consult->getQueryString("index.php", 0, $_REQUEST) ?>" class="btn hoverbg">취소</a>
		</div>
		<!-- //btnSet -->
	</div>
	<!-- //contents -->
</div>
<!-- e:warp --> 
<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/footer.php"; ?>
</body>
</html>
