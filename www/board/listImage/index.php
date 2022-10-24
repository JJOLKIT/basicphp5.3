<? include_once $_SERVER['DOCUMENT_ROOT']."/include/common.php"; ?>
<?
	include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
	include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
	include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
	include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
	include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

	include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/Notice.class.php";

	include "config.php";

	$notice = new Notice($pageRows, $tablename, $_REQUEST);
	$rowPageCount = $notice->getCount($_REQUEST);
	$result = $notice->getList($_REQUEST);
?>
<?	
	$p = "";
	$sp = 0;
	$spc= 0;
	$root = $_SERVER['DOCUMENT_ROOT'];
	include_once $root."/header.php";
?>
<script type="text/javascript">
$(window).resize(function(){
		var titleH = $('image').next('.title').css('height');
		$(".image").css('height' ,titleH);
		/*$(".image").each(function(){ 
   	 		$(this).css('height' ,$(this).parent("tr").next(".title").height());
		});*/
	});
$(window).resize();

$(window).load(function() {
	
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

<div id="sub" class="">
	<div class="size">
		<!-- 여기서부터 게시판--->
		<div class="bbs">
			<table class="list imgList">
				<caption>게시판 목록</caption>
				<colgroup>
					<col width="80px" />
					<col width="80px" />
					<col width="*" />
					<col width="100px" />
					<col width="100px" />
					<col width="100px" />
				</colgroup>
				<thead>
					<tr>
						<th>번호</th>
						<th colspan="2">제목</th>
						<th>작성자</th>
						<th>작성일</th>
						<th>조회수</th>
					</tr>
				</thead>
				<tbody>
				<? if ($rowPageCount[0] == 0) { ?>
					<tr>
						<td colspan="6" align="center">등록된 데이터가 없습니다.</td>
					</tr>
				<?
					 } else {
						$targetUrl = "";
						$topClass = "";
						$i = 0;
						while ($row=mysql_fetch_assoc($result)) {
							$targetUrl = "style='cursor:pointer;' onclick=\"location.href='".$notice->getQueryString('view.php', $row[no], $_REQUEST)."'\"";
							if ($row[top] == '1') {
								$topClass = "class='notice'";
							} else {
								$topClass = "";
							}
				?>
					<tr <?=$topClass?> <?=$targetUrl?>>
						<td class="no">
						<? if ($row[top] == "1") { ?>
							<span class="notice_ico">공지</span>
						<? } else { ?>
							<?=$rowPageCount[0] - (($notice->reqPageNo-1)*$pageRows) - $i?>
						<? } ?>
						</td>
						<td class="image">
						<? if ($row['imagename']) { ?>
							<img src="<?=$uploadPath?><?=$row['imagename']?>" alt="이미지"/>
						<? } ?>
						</td>
						<td class="txt_l title">
							<?=$row[title]?>
							<? if ($isComment) { ?>
								<span class="reNum">[<strong><?=$row[comment_count]?></strong>]</span>
							<? } ?>
							<? if (checkNewIcon($row['registdate'], $row['newicon'], 1)) { ?>
								<img src="/img/ico_new.png" alt="새글" />
							<? } ?>
						</td>
						<td class="name"><?=$row[name]?></td>
						<td class="date"><?=getYMD($row[registdate])?></td>
						<td class="hit"><?=$row[readno]?></td>
					</tr>
				<?
						$i++;
						}
					 }
				?>
				</tbody>
			</table>
			<div class="pagenate clear">
				<?=pageList($notice->reqPageNo, $rowPageCount[1], $notice->getQueryString('index.php', 0, $_REQUEST))?>
			</div>
			<!-- //pagenate -->
			<div class="bbsSearch">
				<form method="get" name="searchForm" id="searchForm" action="index.php">
					<span class="select  srchSelect">
						<select id="stype" name="stype" class="dSelect" title="검색분류 선택">
							<option value="all" <?=getSelected("all", $_REQUEST['stype']) ?>>전체</option>
							<option value="title" <?=getSelected("title", $_REQUEST['stype']) ?>>제목</option>
							<option value="contents" <?=getSelected("contents", $_REQUEST['stype']) ?>>내용</option>
						</select>
					</span>
					<span class="searchWord">
						<input type="text" id="sval" name="sval" value="<?=$_REQUEST['sval'] ?>" title="검색어 입력" onKeypress="">
						<input type="button" id="" value="검색" title="검색" onclick="goSearch();">
					</span>
				</form>
			</div>
		</div>
		<!-- //여기까지 게시판--->
	</div>
	<!-- //size--->
</div>
<!-- //sub--->
<?
	include_once $root."/footer.php";
?>