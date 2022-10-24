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

$notice = new GalleryCt($pageRows, $tablename, $category_tablename, $_REQUEST);
$data = $notice->getData($_REQUEST[no], $userCon);
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
	<div class="contWrap">
		<div class="titWrap">
			<h2><?=$pageTitle?> 보기</h2>
		</div>
		<div class="write">
			<div class="wr_box">
				<h3>등록정보</h3>
				<table>
					<colgroup>
						<col width="8%">
						<col width="*">
					</colgroup>
					<tbody>
					<tr>
						<th>카테고리</th>
						<td><?=$data['category_title']?></td>
					</tr>
					<tr>
						<th scope="col">제목</th>
						<td scope="col">
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
						<th>노출설정</th>
						<td class="exposure">
							<img src="/img/ico_top.png" alt="TOP공지" /> <?=getTop($data['top']) ?>
						</td>
					</tr>
					<tr>
						<th>작성일</th>
						<td>
							<?=$data['registdate'] ?>
						</td>
					</tr>
					<tr>
						<th>이미지</th>
						<td>
							<? if ($data[imagename]) { ?>
							<img src="<?=$uploadPath?><?=$data[imagename]?>" alt="<?=$data[image_alt]?>" style="max-width:500px;"/>
							<? } ?>
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
					<tr>
						<th>관련링크</th>
						<td>
							<a href="<?=$data['relation_url']?>" title="새 창 열림" target="_blank"><?=$data['relation_url']?></a>
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
				<a href="<?=$notice->getQueryString('index.php', 0, $_REQUEST) ?>" class="btn list hoverbg">
					<span class="material-icons">reorder</span>목록
				</a>
			</span>
			<span class="right">
				<a href="<?=$notice->getQueryString('edit.php', $data['no'], $_REQUEST) ?>" class="btn hoverbg">수정</a>
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
