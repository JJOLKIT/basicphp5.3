<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/common.php"; ?>
<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/Reply.class.php";

include $_SERVER['DOCUMENT_ROOT']."/admin/include/loginCheck.php";
include "config.php";

$notice = new Reply($pageRows, $tablename, $_REQUEST);
$data = $notice->getData($_REQUEST[no], $userCon);

$memberUrl = "";
if ($data['member_fk'] > 0) $memberUrl = "style='cursor:pointer;' onclick=\"window.open('/admin/member/view.php?no=".$data['member_fk']."','member','');\"";

?>
<!doctype html>
<html lang="ko">
<head>
<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/headHtml.php"; ?>
<script>
function goDelete() {
	if (confirm("삭제하시겠습니까?")) {
		location.href="<?=$notice->getQueryString("process.php", $data['no'], $_REQUEST) ?>&cmd=delete";
	}
}
</script>
</head>


<body>
<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/header.php"; ?>
<!-- s:warp -->
<div id="warp">
	<div class="contents">
		<h2 class="fl_l"><?=$pageTitle?> 보기</h2>
		<div class="write">
			<div class="wr_box">
				<h3>등록정보</h3>
				<table>
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
						<td <?=$memberUrl ?>>
							<?=$data['name'] ?>
						</td>
					</tr>
					<tr>
						<th>노출설정</th>
						<td colspan="3">
							<span class="h_line"></span> <img src="/img/ico_new.png" alt="새글" /> <?=getNewIcon($data['newicon']) ?> <span class="h_line"></span>
							<img src="/img/secret.gif" alt="비밀글" /> <?=getSecretName($data['secret']) ?>
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
					<tr>
						<th>제목</th>
						<td>
							<?=$data['title'] ?>
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
			<? if ($isComment) { ?>
				<? include $_SERVER['DOCUMENT_ROOT']."/admin/board/comment/comment.php" ?><!-- 댓글 -->
			<? } ?>
		</div>
		<!-- //write -->
		<div class="btnSet">
			<span class="fl_l"><a href="<?=$notice->getQueryString('index.php', 0, $_REQUEST) ?>" class="btn">목록</a></span>
			<span class="fl_r">
				<a href="<?=$notice->getQueryString('reply.php', $data['no'], $_REQUEST) ?>" class="btn">답변</a>
				<a href="<?=$notice->getQueryString('edit.php', $data['no'], $_REQUEST) ?>" class="btn">수정</a>
				<a href="javascript:;" class="btn" onclick="goDelete();">삭제</a>
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
