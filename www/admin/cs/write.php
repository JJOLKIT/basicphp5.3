<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/common.php"; ?>
<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/Cs.class.php";

include $_SERVER['DOCUMENT_ROOT']."/admin/include/loginCheck.php";
include "config.php";

$notice = new Cs($pageRows, $tablename, $_REQUEST);
?>
<!doctype html>
<html lang="ko">
<head>
<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/headHtml.php"; ?>
<script>
	var oEditors; // 에디터 객체 담을 곳
	jQuery(window).load(function(){
		//oEditors = setEditor("contents"); // 에디터 셋팅
		
		// 달력/
		//initCal({id:"registdate",type:"day",today:"y",timeYN:"y"});
	});
	
	function goSave() {

		if ($("#title").val() == "") {
			alert('제목을 입력해 주세요.');
			$("#title").focus();
			return false;
		}
		/*

		var sHTML = oEditors.getById["contents"].getIR();
		if (sHTML == "") {
			alert('내용을 입력해 주세요.');
			$("#contents").focus();
			return false;
		} else {
			oEditors.getById["contents"].exec("UPDATE_CONTENTS_FIELD", []);	// 에디터의 내용이 textarea에 적용됩니다.
		}
		*/
		if($('#contents').val().trim() == ""){
			alert('내용을 입력해 주세요.');
			$("#contents").focus();
			return false;
		}
			
		


		$('#frm').submit();
	}
	
	
</script>
<?
/*
<?include_once $_SERVER['DOCUMENT_ROOT']."/summernote/note.html";?>
<script>

	$(document).ready(function() {
		$('#contents').summernote(editorOptions);
	});
</script>
*/
?>
</head>


<body>
<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/header.php"; ?>
<!-- s:warp -->
	<div class="contWrap">
		<div class="titWrap">
			<h2><?=$pageTitle ?> 글쓰기</h2>
		</div>
		<div class="information">
			<ul>
				<li>※ 첨부파일 및 이미지 파일은 개당 <b>10mb 이하</b>로 업로드 가능하며, <b>가로사이즈 1200px 이하, 1mb 이하</b>를 권장합니다.</li>
				<li>※ 이미지 사이즈 수정은 그림판, 포토샵 등 여러 그래픽 프로그램을 통해 수정 가능합니다.</li>
				<li>※ 이미지 용량이 너무 클 경우, 사이트 느려짐 현상과 트래픽 초과, 하드 용량 초과의 주 원인이 될 수 있습니다.</li>
				<li>※ 잘못 업로드 하신 파일로 인해 발생한 홈페이지 문제에 대해서는 유지보수 비용이 청구될 수 있습니다.</li>
			</ul>
		</div>
		<div class="write">
			<form method="post" name="frm" id="frm" action="<?=getSslCheckUrl($_SERVER['REQUEST_URI'], 'userProcess.php')?>" enctype="multipart/form-data">
				<!-- //wr_box -->
				<div class="wr_box">
					<h3>문의내용</h3>
					<table>
						<colgroup>
							<col width="8%">
							<col width="*">
						</colgroup>
						<tbody>
						<tr>
							<th>제목</th>
							<td>
								<input type="text" name="title" id="title"  value="문의드립니다." />
							</td>
						</tr>
						
						<tr>
							<th>내용</th>
							<td>
								<textarea name="contents" id="contents" rows="10"  placeholder="유선안내를 원하실 경우, 담당자명 및 연락처를 함께 기재하여 주십시오."></textarea>
							</td>
						</tr>
						<tr>
							<th>첨부파일</th>
							<td>
								<input  type="file" name="filename" id="filename" />
							</td>
						</tr>
						<tr>
							<th>첨부파일2</th>
							<td>
								<input  type="file" name="filename2" id="filename2" />
							</td>
						</tr>
						<tr>
							<th>첨부파일3</th>
							<td>
								<input  type="file" name="filename3" id="filename3" />
							</td>
						</tr>
						</tbody>
					</table>
				</div>
				<!-- //wr_box -->
				<input type="hidden" name="cmd" value="write" />
				<?=$notice->getQueryStringToHidden($_REQUEST) ?>
			</form>
		</div>
		<!-- //write -->
		<div class="btnSet">
			<a href="javascript:;" class="btn hoverbg save" onclick="goSave();">저장</a>
			<a href="<?=$notice->getQueryString('index.php', 0, $_REQUEST)?>" class="btn hoverbg">취소</a>
		</div>
		<!-- //btnSet -->
	</div>
	<!-- //contents -->
</div>
<!-- e:warp --> 
<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/footer.php"; ?>
</body>
</html>
