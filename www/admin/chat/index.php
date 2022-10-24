<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/common.php"; ?>
<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/Chat.class.php";

include $_SERVER['DOCUMENT_ROOT']."/admin/include/loginCheck.php";
include "config.php";

$today = getToday();

$oneMonth = getMonthDateAdd(-1, $today);
$twoMonth = getMonthDateAdd(-2, $today);
$threeMonth = getMonthDateAdd(-3, $today);


$notice = new Chat($pageRows, $tablename, $_REQUEST);
$rowPageCount = $notice->getAdminCount($_REQUEST);
$result = $notice->getAdminList($_REQUEST);
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

function openchat(session_fk, comdate, name){
	window.open('chat.php?ssession_fk='+session_fk+'&scomdate='+comdate+'&name='+name+'', '_blank', 'width=800px, height=900px, scrollbar=no');
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
		<div class="searchWrap">
		<form method="get" name="searchForm" id="searchForm" action="index.php">
			<table class="searchTable">
				<caption> 게시글검색 </caption>
				<colgroup>
					<col width="10%" />
					<col width="*" />
					<col width="10%" />
					<col width="*" />
				</colgroup>
				<tbody>
					<tr>
						<th>
							날짜
						</th>
						<td>
							<input type="text" name="sstartdate" id="sstartdate"  value="<?=$_REQUEST['sstartdate']?>" class="date" onKeyUp="cvtDate(this);isNumberOrHyphen(this);" maxlength="10"/>
							<span id="CalsstartdateIcon" style="height:22px; line-height:22px;vertical-align:middle;">
							<img src="/admin/img/ico_calendar.gif" id="CalsstartdateIconImg" style="cursor:pointer;"/>
							</span> ~ 
							<input type="text" name="senddate"  id="senddate" value="<?=$_REQUEST['senddate']?>" class="date" onKeyUp="cvtDate(this);isNumberOrHyphen(this);" maxlength="10"/>
							<span id="CalsenddateIcon" style="height:22px; line-height:22px;vertical-align:middle;margin-right:5px;">
							<img src="/admin/img/ico_calendar.gif" id="CalsenddateIconImg" style="cursor:pointer;"/>
							</span>
							<input type="button" value="검색" class="" onclick="goSearch();"/>
							<input type="button" name="" value="1M" class="" onClick="searchDate('<?=$oneMonth?>','<?=$today?>');"/>
							<input type="button" name="" value="2M" class="" onClick="searchDate('<?=$twoMonth?>','<?=$today?>');"/>
							<input type="button" name="" value="3M" class="" onClick="searchDate('<?=$threeMonth?>','<?=$today?>');"/>
						</td>
						<th>검색어</th>
						<td>
							<select name="stype" id="stype">
								<option value="all" <?=getSelected("all", $_REQUEST['stype']) ?>>전체</option>
								<option value="title" <?=getSelected("title", $_REQUEST['stype']) ?>>제목</option>
								<option value="contents" <?=getSelected("contents", $_REQUEST['stype']) ?>>내용</option>
							</select>
							<input type="text" name="sval" id="sval" value="<?=$_REQUEST['sval'] ?>" />
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
			<p class="list_tit clear">전체 <strong><?=$rowPageCount[0]?></strong>건 [<?=$notice->reqPageNo?>/<?=$rowPageCount[1]?>페이지] 
				<span>
					<select name="pageRowsVal" id="pageRowsVal" onchange="$('#pageRows').val($('#pageRowsVal').val());goSearch();">
						<option value="10" <?=getSelected("10", $pageRows) ?>>10개씩보기</option>
						<option value="20" <?=getSelected("20", $pageRows) ?>>20개씩보기</option>
						<option value="50" <?=getSelected("50", $pageRows) ?>>50개씩보기</option>
					</select>
				</span>
			</p>
			<form name="frm" id="frm" action="process.php" method="post">
			<table>
				<caption> 목록 </caption>
					<colgroup>
						<col width="50px" />
						<col width="5%" />
						<col width="*" />
						<col width="8%" />
						<col width="8%" />
						<col width="12%" />
					</colgroup>
				<thead>
					<tr>
						<th scope="col"><input type="checkbox" name="allChk" id="allChk" onClick="check(this, document.frm.no)"/></th>
						<th scope="col">번호</th>
						<th scope="col">채팅방</th>
						<th scope="col">작성자</th>
						<th scope="col">채팅일</th>
						<th scope="col">작성일</th>
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
							//$targetUrl = "style='cursor:pointer;' onclick=\"location.href='".$notice->getQueryString('view.php', $row[no], $_REQUEST)."'\"";
							if ($row[top] == '1') {
								$topClass = "class='topBg'";
							} else {
								$topClass = "";
							}
				?>
					<tr <?=$topClass?>>
						<td><input type="checkbox" value="<?=$row['no']?>" name="no[]" id="no"/></td>
						<td <?=$targetUrl ?>>
						<? if ($row[top] == "1") { ?>
							<img src="/img/ico_top.png" alt="TOP공지" />
						<? } else { ?>
							<?=$rowPageCount[0] - (($notice->reqPageNo-1)*$pageRows) - $i?>
						<? } ?>
						</td>
						<td class="" <?=$targetUrl ?>>
							<a href="javascript:;" onclick="openchat('<?=$row['session_fk']?>', '<?=$row['comdate']?>', '<?=$row['name']?>');">채팅방 열기</a>
						</td>
						<td <?=$targetUrl ?>><?=$row[name]?></td>
						<td <?=$targetUrl ?>><?=$row[comdate]?></td>
						<td <?=$targetUrl ?>><?=$row[registdate]?></td>
					</tr>
					<?
							$i++;
							}
						 }
					?>
				</tbody>
			</table>
			<input type="hidden" name="cmd" id="cmd" value="groupDelete"/>
			<?=$notice->getQueryStringToHidden($_REQUEST) ?>
		</form>
		</div>
		<!-- //list -->
		<div class="sBtn fl_l mt10">
			<input type="button" value="삭제" onclick="groupDelete();"/>
		</div>
		<div class="pagenate btnSet clear">
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
