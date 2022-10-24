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
$data = ($faq->getData($_REQUEST[no], $userCon));
?>
<!doctype html>
<html lang="ko">
<head>
<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/headHtml.php"; ?>
<script type="text/javascript">
	function goDelete() {
		var del = confirm ('삭제하시겠습니까?');
		if (del){
			document.location.href = "process.php?no=<?=$data[no]?>&cmd=delete";
		} else {
			return false;
		}
	}
</script>
</head>


<body>
<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/header.php"; ?>
<!-- s:warp -->
	<div class="contWrap">
		<div class="titWrap">
			<h2><?=$pageTitle ?> 글보기</h2>
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
							<?=$data[registdate]?>
						</td>
						<th>노출설정</th>
						<td class="exposure">
							<img src="/img/ico_top.png" alt="TOP공지" /> <?=getTop($data['top']) ?>
							<img src="/img/ico_new.png" alt="새글" class="ico_new"/> <?=getNewIcon($data['newicon']) ?>
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
					<?if($category_use){?>
					<tr>
						<th>분류</th>
						<td>
							<?=$data[category_name]?>
						</td>
					</tr>
					<?}?>
					<tr>
						<th>제목</th>
						<td>
							<?=escape_html($data['title'])?>
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
		</div>
		<!-- //write -->
		<div class="btnSet clear">
			<span class="left">
				<a href="<?=$faq->getQueryString('index.php', 0, $_REQUEST) ?>" class="btn list hoverbg">
					<span class="material-icons">reorder</span>목록
				</a>
			</span>
			<span class="right">
				<a href="<?=$faq->getQueryString('edit.php', $data['no'], $_REQUEST) ?>" class="btn hoverbg">수정</a>
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
