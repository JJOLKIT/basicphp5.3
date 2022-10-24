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
	$(".searchWrap input[type='text']").val("");
	$(".searchWrap input[type='checkbox']").removeAttr("checked");
	$(".searchWrap input[type='radio']").removeAttr("checked");
	$(".searchWrap select").val("all");
	goSearch()();
}

</script>
<style>
	.list form{border:none;}
</style>

</head>

<body>
<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/header.php"; ?>


<!-- s:warp -->
	<div class="contWrap">
		<div class="titWrap">
			<h2><?=$pageTitle ?></h2>
			<div class="sBtnWrap">
				<div class="sBtn reset">
					<span class="material-icons">restart_alt</span>
					<input type="button" value="검색초기화"  class="reset"  onclick="resetSearchForm();"/>
				</div>
			</div>
			<!--//sBtnWrap-->
		</div>
		<div class="searchWrap">
			<form method="get" name="searchForm" id="searchForm" action="index.php">
				<table class="searchTable">
					<caption> 게시글검색 </caption>
					<colgroup>
						<col width="10%" />
						<col width="40%" />
						<col width="10%" />
						<col width="40%" />
					</colgroup>
					<tbody>
						<tr>
							<th>
								날짜
							</th>
							<td>
								<p class="calendar">
									<input type="text" name="sstartdate" id="sstartdate"  value="<?=$_REQUEST['sstartdate']?>" class="date" onKeyUp="cvtDate(this);isNumberOrHyphen(this);" maxlength="10"/>
									<span id="CalsstartdateIcon">
										<span class="material-icons" id="CalsstartdateIconImg">calendar_month</span>
									</span>
								</p>	
								<span>~ </span>
								<p class="calendar">
									<input type="text" name="senddate"  id="senddate" value="<?=$_REQUEST['senddate']?>" class="date" onKeyUp="cvtDate(this);isNumberOrHyphen(this);" maxlength="10"/>
									<span id="CalsenddateIcon">
										<span class="material-icons" id="CalsenddateIconImg">calendar_month</span>
									</span>
								</p>
								<input type="button" value="검색" onclick="goSearch();" class="hoverbg"/>
								<input type="button" value="1M" onClick="searchDate('<?=$oneMonth?>','<?=$today?>');" class="hoverbg" />
								<input type="button" value="2M" onClick="searchDate('<?=$twoMonth?>','<?=$today?>');" class="hoverbg" />
								<input type="button" value="3M" onClick="searchDate('<?=$threeMonth?>','<?=$today?>');" class="hoverbg" />
							</td>
							<th>검색어</th>
							<td>								
								<select name="stype" id="stype" class="select_drop">
									<option value="all" <?=getSelected("all", $_REQUEST['stype']) ?>>전체</option>
									<option value="title" <?=getSelected("title", $_REQUEST['stype']) ?>>제목</option>
									<option value="contents" <?=getSelected("contents", $_REQUEST['stype']) ?>>내용</option>
								</select>					
								<input type="text" name="sval" id="sval" value="<?=$_REQUEST['sval'] ?>" />
								<input type="submit" value="검색" class="btn_search hoverbg" />
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
				<p class="all">전체 선택 <input type="checkbox" name="allChk" id="allChk" onClick="check(this, document.frm.no)"/></p>
				<ul class="clear">
				<?
				$i = 0;
				while ($row=mysql_fetch_assoc($result)) {
					$row = escape_html($row);
		
				?>
					<li>
						<p class="chk"><input type="checkbox" name="no[]" id="no" value="<?=$row[no]?>"/> <?=$rowPageCount[0] - (($notice->reqPageNo-1)*$pageRows) - $i?></p>
						<a href="<?=$notice->getQueryString('view.php', $row[no], $_REQUEST)?>">
						<dl>
							<? if ($row[imagename]) { ?>
									<dt style="background-image:url(<?=$uploadPath.$row[imagename]?>);" class="back_img">
							<? }else if($row['youtube_url']){ ?>
									<dt style="background-image:url('https://img.youtube.com/vi/<?=getYoutubeCode($row[youtube_url])?>/0.jpg');" class="back_img">
							<? } else {?>
								<dt style="background-image:url('/admin/img/no_image.jpg');" class="back_img">
							<?}?>
									<img src="/admin/img/no_image.jpg" alt="no_image" class="basic_img"/>
							</dt>
							<dd class="title">
								<?=mb_strimwidth($row['title'], '0', '80', '...','utf-8')?>
							</dd>
							<dd class="info">
								<ul>
									<li>작성자 : <?=$row['name']?></li>
									<li>등록일 : <?=getYMD($row['registdate'])?></li>
									<li>조회수 : <?=$row['readno']?></li>
								</ul>
							
								<? if (checkNewIcon($row['registdate'], $row['newicon'], 1)) { ?>
									<img src="/img/ico_new.png" alt="NEW" />
								<? } ?>
							</dd>
							</dl>
						</a>
					</li>
				<?
				$i ++;
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
		<div class="btnSet clear">
			<div class="sBtn left">
				<input type="button" value="삭제"  onclick="groupDelete();"/>
			</div>
			<div class="right">
				<a href="write.php" class="btn edit hoverbg">
					<span class="material-icons">edit</span>글쓰기
				</a>
			</div>
		</div>
		<div class="pagenate">
			<?=pageList($notice->reqPageNo, $rowPageCount[1], $notice->getQueryString('index.php', 0, $_REQUEST))?>
		</div>
		<!-- //pagenate -->
	</div>
	<!-- //contents -->
</div>
<!-- e:warp --> 

<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/footer.php"; ?>
</body>
</html>
