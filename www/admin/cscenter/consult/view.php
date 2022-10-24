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
function goDelete() {
	if (confirm("삭제하시겠습니까?")) {
		location.href="<?=$consult->getQueryString("process.php", $data['no'], $_REQUEST) ?>&cmd=delete";
	}
}
</script>
</head>
<body>
<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/header.php"; ?>
<!-- s:warp -->
	<div class="contWrap">
		<div class="titWrap">
			<h2><?=$pageTitle?> 글보기</h2>
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
			<div class="wr_box">
				<h3>답변글</h3>
				<table>
					<colgroup>
						<col width="8%">
						<col width="*">
					</colgroup>
					<tbody>
					<tr>
						<th>답변일시</th>
						<td><?=$data['answer_date']?></td>
					</tr>
					<tr>
						<th>답변자</th>
						<td><?=escape_html($data['answer_name'])?></td>
					</tr>
					<tr>
						<th>답변 제목</th>
						<td><?=escape_html($data['answer_title'])?></td>
					</tr>
					<tr>
						<th>내용</th>
						<td>
							<?=$data['answer'] ?>
						</td>
					</tr>
					<tr>
						<th>첨부파일</th>
						<td>
							<? if ($data['answerfilename']) { ?>
								<a href="/lib/download.php?path=<?=$uploadPath?>&vf=<?=$data['answerfilename']?>&af=<?=urlencode($data['answerfilename_org'])?>" target="_blank"><?=$data[answerfilename_org]?></a>
							<? } ?>
						</td>
					</tr>
					</tbody>
				</table>
			</div>
			<!-- //wr_box -->
		</div>
		<!-- //write -->
		<div class="btnSet clear">
			<span class="left">
				<a href="<?=$consult->getQueryString("index.php", 0, $_REQUEST) ?>" class="btn list hoverbg">
					<span class="material-icons">reorder</span>목록
				</a>
			</span>
			<span class="right">
				<a href="<?=$consult->getQueryString("edit.php", $data['no'], $_REQUEST) ?>" class="btn hoverbg">답변</a>
				<a href="javascript:;" class="btn hoverbg" onclick="goDelete();">삭제</a>
			</span>
		</div>
		<!-- //btnSet -->
	</div>
	<!-- //contents -->
</div>
<!-- e:warp --> 
<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/footer.php"; ?>
</body>
</html>
