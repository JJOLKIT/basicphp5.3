<? include_once $_SERVER['DOCUMENT_ROOT']."/include/common.php"; ?>
<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/Gallery.class.php";

include "config.php";
 
$notice = new Gallery($pageRows, $tablename, $_REQUEST);
$rowPageCount = $notice->getCount($_REQUEST);
$result = $notice->getList($_REQUEST);
?>
<!doctype html>
<html lang="ko">
<head>
<? include_once $_SERVER['DOCUMENT_ROOT']."/include/headHtml.php"; ?>
<script type="text/javascript">
	$(window).load(function(){ 
		$("input[type=text][name*=sval]").keypress(function(e){
			if(e.keyCode == 13){
				goSearch();
			}
		});
	});

	function goSearch() {
		$("#searchForm").submit();
	}
</script>
</head>
<body>
<div class="program">
	<h2 class="title">동영상게시판</h2>
				<!--s:내용-->
					<div class="bbs">
						<div class="gallery">
							<ul>
							<? if($rowPageCount[0] == 0) { ?>
								<li>등록된 글이 없습니다.</li>
							<? } else { ?>
								<?
								while ($row=mysql_fetch_assoc($result)) {
									//print_r($row);
								?>
								<li>
									<a href="<?=$notice->getQueryString('view.php', $row[no], $_REQUEST)?>">
									<dl>
										
										<? if ($row[imagename]) { ?>
										<dt class="img" style="text-align:center;">
											<img src="<?=$uploadPath?><?=$row[imagename]?>" alt="<?=$row[image_alt]?>"/>
										</dt>
										<? }else{ ?>
										<dt class="noimg">
											<img src="/admin/img/no_image.jpg" alt="no_image"/>
										</dt>
										<? } ?>
										

										<dd class="date"><?=getYMD($row[registdate])?></dd>
										<dd class="title">
											<?=$row[title]?>
											<? if ($isComment) { ?>
											<span class="reNum">[<strong><?=$row[comment_count]?></strong>]</span>
											<? } ?>
										</dd>
									</dl>
									</a>
								</li>
							<?
									}
								} 
							?>
							</ul>
						</div>
						<!-- //galley -->
						<div class="pagenate clear">
							<?=pageList($notice->reqPageNo, $rowPageCount[1], $notice->getQueryString('index.php', 0, $_REQUEST))?>
						</div>
						<!-- //pagenate -->
						<div class="bbsSearch">
						<form method="get" name="searchForm" id="searchForm" action="index.php">
							<span class="srchSelect">
								<select id="stype" name="stype" class="dSelect" title="검색분류 선택">
									<option value="all" <?=getSelected("all", $_REQUEST['stype']) ?>>제목+내용</option>
									<option value="title" <?=getSelected("title", $_REQUEST['stype']) ?>>제목</option>
									<option value="contents" <?=getSelected("contents", $_REQUEST['stype']) ?>>내용</option>
								</select>
							</span>
							<span class="searchWord">
								<input type="text" id="sval" name="sval" value="<?=$_REQUEST['sval'] ?>" title="검색어 입력" onKeypress="">
								<input type="button" id="" value="검색" title="검색" onclick="goSearch();">
							</span>
						<input type="hidden" name="sclass_fk" value="<?=$_REQUEST['sclass_fk']?>"/>
						</form>
						</div>
					</div>
				<!--e:내용-->
</div>
</body>
</html>
