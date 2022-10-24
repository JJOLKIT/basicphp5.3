<? include_once $_SERVER['DOCUMENT_ROOT']."/include/common.php"; ?>
<?
	include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
	include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
	include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
	include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
	include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

	include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/Post.class.php";

	include "config.php";

	$post = new Post($pageRows, $tablename, $_REQUEST);
	$rowPageCount = $post->getCount($_REQUEST);
	$result = $post->getList($_REQUEST);
?>
<?	
	$p = "";
	$sp = 0;
	$spc= 0;
	$root = $_SERVER['DOCUMENT_ROOT'];
	include_once $root."/header.php";
?>
<script type="text/javascript">
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
			<div class="postSection">
				<ul class="postList clear">
					<?
						while($row = mysql_fetch_assoc($result)){
					?>
					<li>
						<a href="<?=$post->getQueryString('view.php', $row[no], $_REQUEST);?>">
							<? if ($row['imagename']) { ?>
							<div class="imgs" style="background-image:url('<?=$uploadPath?><?=$row['imagename']?>');">
								<? if ($row[top] == "1") { ?>
									<span class="notice_ico">공지</span>
								<? } ?>
							</div>
							<?}else{?>
							<div class="imgs noimgs" style="background-image:url('/admin/img/no_image.jpg');">
								<? if ($row[top] == "1") { ?>
									<span class="notice_ico">공지</span>
								<? } ?>
							</div>
							<?}?>
							<div class="txt">
								<div class="category">
									<?=getPostName($row['post_option'])?>
								</div>
								<div class="title"><?=$row['title']?></div>
								<div class="contxt"><?=utf8_strcut($row['contents'],90,'...');?></div>
								<div class="info clear">
									<ul>
										<li><span class="name">작성자</span><b><?=$row['name']?></b></li>
                                                  <li><span class="date">날짜</span><b><?=getYmd($row['registdate']);?></b></li>
										<li><span class="hit">조회수</span><b><?=$row['readno']?></b></li>
									</ul>
								</div>
							</div>
						</a>
					</li>
					<?}?>
				</ul>
			</div>
			<div class="pagenate clear">
				<?=pageList($post->reqPageNo, $rowPageCount[1], $post->getQueryString('index.php', 0, $_REQUEST))?>
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