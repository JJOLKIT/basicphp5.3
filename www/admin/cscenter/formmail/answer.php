<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/common.php"; ?>
<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/Formmail.class.php";

include $_SERVER['DOCUMENT_ROOT']."/admin/include/loginCheck.php";
include "config.php";

$notice = new Formmail($pageRows, $tablename, $_REQUEST);
$data = ($notice->getData($_REQUEST[no], $userCon));
?>
<!doctype html>
<html lang="ko">
<head>
<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/headHtml.php"; ?>
<script>
	var oEditors; // 에디터 객체 담을 곳
	jQuery(window).load(function(){
		oEditors = setEditor("answer"); // 에디터 셋팅
		
		// 달력
		initCal({id:"registdate",type:"day",today:"y",timeYN:"y"});
	});
	
	function goSave() {
		oEditors.getById["answer"].exec("UPDATE_CONTENTS_FIELD", []);	// 에디터의 내용이 textarea에 적용됩니다.
		$('#frm').submit();
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
						<th>업체명</th>
						<td><?=escape_html($data['company']) ?></td>
						<th>담당자</th>
						<td><?=escape_html($data['name']) ?></td>
					</tr>
					<tr>
						<th>부서</th>
						<td><?=escape_html($data['department']) ?></td>
						<th>지역</th>
						<td><?=escape_html($data['area']) ?></td>
					</tr>
					<tr>
						<th>연락처</th>
						<td><?=escape_html($data['cell']) ?></td>
						<th>이메일</th>
						<td><?=escape_html($data['email']) ?></td>
					</tr>
					<tr>
						<th>작성일</th>
						<td colspan="3"><?=$data['registdate'] ?></td>
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
					<tr>
						<th>문의내용</th>
						<td><?=$data['contents'] ?> </td>
					</tr>
					<tr>
						<th>답변</th>
						<td>
							<input type="checkbox" name="sendEmail" value="1"/> 이메일발송
							<textarea name="answer" id="answer" rows="10"><?=$data['answer'] ?></textarea>
						</td>
					</tr>
					</tbody>
				</table>
			</div>
			<!-- //wr_box -->
		</div>
		<!-- //write -->
		<div class="btnSet clear">
			<a href="javascript:;" class="btn hoverbg save" onclick="goSave();">저장</a>
			<a href="<?=$notice->getQueryString('index.php', 0, $_REQUEST)?>" class="btn hoverbg">취소</a>
			
		</div>
		<!-- //btnSet -->
		<input type="hidden" name="cmd" value="edit"/>
		<input type="hidden" name="no" value="<?=$data['no'] ?>" />
		<?=$notice->getQueryStringToHidden($_REQUEST) ?>
		</form>
	</div>
	<!-- //contents -->
</div>
<!-- e:warp --> 
<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/footer.php"; ?>
</body>
</html>
