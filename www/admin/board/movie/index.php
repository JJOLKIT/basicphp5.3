<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/common.php"; ?>
<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/Gallery.class.php";

include $_SERVER['DOCUMENT_ROOT']."/admin/include/loginCheck.php";
include "config.php";
 
$today = getToday();
$oneMonth = getMonthDateAdd(-1, $today);
$twoMonth = getMonthDateAdd(-2, $today);
$threeMonth = getMonthDateAdd(-3, $today);

$notice = new Gallery($pageRows, $tablename, $_REQUEST);
$rowPageCount = $notice->getCount($_REQUEST);
$result = $notice->getList($_REQUEST);
?>
<!doctype html>
<html lang="ko">
<head>
<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/headHtml.php"; ?>
<script>
//달력부분
$(window).load(function() {
	initCal({id:"sstartdate",type:"day",today:"y"});			
	initCal({id:"senddate",type:"day",today:"y"});
	
	$("input[type=text][name*=sval]").keypress(function(e){
		if(e.keyCode == 13){
			goSearch();
		}
	});

});
</script>
<script>
function groupDelete() {	
	if ( isSeleted(document.frm.no) ){
		if (confirm("선택한 항목을 삭제하시겠습니까?")) {
			document.frm.submit();
		}
	} else {
		alert("삭제할 항목을 하나 이상 선택해 주세요.");
	}
}

function searchDate(startDay, endDay) {
	var f = document.searchForm;
	f.sstartdate.value = startDay;
	f.senddate.value = endDay;

	goSearch();
}

function goSearch() {
	$("#searchForm").submit();
}

function resetSearchForm() {
	$("#sstartdate").val("");
	$("#senddate").val("");
	$("#stype").val("all");
	$("#sval").val("");
	goSearch()();
}

</script>
</head>


<body>
<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/header.php"; ?>
<!-- s:warp -->
<div id="wrapper">
	<div class="contWrap">
		<div class="titWrap">
			<h2><?=$pageTitle ?></h2>
			<div class="sBtn">
				<input type="button" value="검색초기화"  name="" class="reset"  onclick="resetSearchForm();"/>
			</div>
		</div>
		<div class="searchWarp">
		<form method="get" name="searchForm" id="searchForm" action="index.php">
			<table class="searchTable">
				<caption> 게시글검색 </caption>
				<colgroup>
					<col width="7%" />
					<col width="7%" />
					<col width="7%" />
					<col width="10%" />
					<col width="10%" />
					<col width="10%" />
					<col width="7%" />
					<col width="7%" />
					<col width="*" />
					<col width="7%" />
				</colgroup>
				<tbody>
					<tr>
						<th class="bno">검색어</th>
						<td class="bno">
							<select name="stype" id="stype">
								<option value="all" <?=getSelected("all", $_REQUEST['stype']) ?>>전체</option>
								<option value="title" <?=getSelected("title", $_REQUEST['stype']) ?>>제목</option>
								<option value="contents" <?=getSelected("contents", $_REQUEST['stype']) ?>>내용</option>
							</select>
						</td>
						<td class="bno">
							<input type="text" name="sval" id="sval" value="<?=$_REQUEST['sval'] ?>" />
						</td>
						<td class="bno">
							<input type="submit" value="검색" class="btn_search" />
						</td>
					</tr>
				</tbody>
			</table>
		<input type="hidden" name="pageRows" id="pageRows" value="<?=$pageRows ?>"/>
		</form>
		</div>
		<!-- //search_warp -->
		<div class="list">
			<p class="list_tit">전체 <strong><?=$rowPageCount[0]?></strong>건 [<?=$notice->reqPageNo?>/<?=$rowPageCount[1]?>페이지]</p>
			<form name="frm" id="frm" action="process.php" method="post">
			<div class="gallery">
			<? if($rowPageCount[0] == 0) { ?>
				<div class="bbsno">
					<p>등록된 글이 없습니다.</p>
				</div>
			<? } else { ?>
				<ul class="clear">
				<?
				while ($row=mysql_fetch_assoc($result)) {
				?>
					<li>
						<dl>
							<dt>
								<div class="photo">
									<p>
									<? if ($row[imagename]) { ?>
										<a href="<?=$notice->getQueryString('view.php', $row[no], $_REQUEST)?>"><img src="<?=$uploadPath?><?=$row[imagename]?>" alt="<?=$row[image_alt]?>" width="302" height="252" class="back_img"/></a>
									<? }else{ ?>
										<a href="<?=$notice->getQueryString('view.php', $row[no], $_REQUEST)?>"><img src="/admin/img/no_image.jpg" alt="no_image" width="302" height="252" class="noimg basic_img"/></a>
									<? } ?>
									</p>
								</div>
								<!-- //photo --> 
							</dt>
							<dd>
								<p class="photoday">
									<span style="margin-right: 1px;;"><input type="checkbox" name="no[]" id="no" value="<?=$row[no]?>"/></span>
									<span style="margin-right: 4px;"><strong>Date.</strong> <?=substr($row[registdate],0, 10)?></span>
									<span><strong>Hit.</strong> <?=$row[readno]?></span>
								</p>
								<a href="<?=$notice->getQueryString('read.php', $row[no], $_REQUEST)?>">
								<? if ($row[top] == '1') { ?>
									<img src="/img/ico_top.png" alt="공지" />
								<? } ?>
								<? if ($row[top] == '1') { ?>
								<span style="font-weight:bold; padding-left:5px"><?=$row[title]?></span>
								<? } else { ?>
								<?=$row[title]?>
								<? } ?>
								<? if ($isComment) { ?>
								<span class="reNum">[<strong><?=$row[comment_count]?></strong>]</span>
								<? } ?>
								</a>
								<? if (checkNewIcon($row['registdate'], $row['newicon'], 1)) { ?>
									<img src="/img/ico_new.png" alt="NEW" />
								<? } ?>
							</dd>
						</dl>
					</li>
				<?
						}
					} 
				?>
				</ul>
			</div>
			<input type="hidden" name="cmd" id="cmd" value="groupDelete"/>
			<?=$notice->getQueryStringToHidden($_REQUEST) ?>
		</form>
		</div>
		<!-- //list -->
		<div class="pagenate btnSet clear">
			<div class="sBtn left">
				<input type="button" value="삭제"  name=""  onclick="groupDelete();"/>
			</div>
			<?=pageList($notice->reqPageNo, $rowPageCount[1], $notice->getQueryString('index.php', 0, $_REQUEST))?>
			<div class="right"><a href="write.php" class="btn">글쓰기</a></div>
		</div>
		<!-- //pagenate -->
	</div>
	<!-- //contents -->
</div>
<!-- e:warp --> 
<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/footer.php"; ?>
</body>
</html>
