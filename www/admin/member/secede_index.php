<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/common.php"; ?>
<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

include_once $_SERVER['DOCUMENT_ROOT']."/lib/member/Member.class.php";

include $_SERVER['DOCUMENT_ROOT']."/admin/include/loginCheck.php";
include "config.php";
$pageTitle = "탈퇴".$pageTitle;
$member = new Member($pageRows, "member", $_REQUEST);
$rowPageCount = $member->getSecedeCount($_REQUEST);
$result = $member->getSecedeList($_REQUEST);

$today = getToday();
$oneWeek = getDayDateAdd(-7, $today);
$oneMonth = getMonthDateAdd(-1, $today);
$twoMonth = getMonthDateAdd(-2, $today);
$threeMonth = getMonthDateAdd(-3, $today);
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

$(document).ready(function(){
	$("#excel").click(function(){
		$("#searchForm")[0].action = "excel.php";
		$("#searchForm").submit();
	});
});
</script>
</head>


<body>
<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/header.php"; ?>
<!-- s:warp -->
	<div class="contWrap">
		<div class="titWrap">
			<h2><?=$pageTitle?></h2>
		</div>
		<div class="searchWrap">
			<form method="get" name="searchForm" id="searchForm" action="index.php">
				<table class="searchTable">
					<caption> 검색 </caption>
					<colgroup>
						<col width="7%" />
						<col width="*" />
					</colgroup>
					<tbody>
						<tr>
							<th>검색어</th>
							<td>								
								<select class="select_drop">
									<option>전체</option>
									<option value="all" <?=getSelected("all", $_REQUEST['stype']) ?>>전체</option>
									<option value="name" <?=getSelected("name", $_REQUEST['stype']) ?>>이름</option>
									<option value="id" <?=getSelected("id", $_REQUEST['stype']) ?>>아이디</option>
								</select>								
								<input type="text" name="sval" id="sval" value="<?=$_REQUEST['sval'] ?>" />
								<input type="submit" value="검색" class="btn_search hoverbg" />
							</td>
						</tr>
					</tbody>
				</table>
			<input type="hidden" name="pageRows" id="pageRows" value="<?=$pageRows ?>"/>
			</form>
		</div>
		<!-- //search_warp -->
		<div class="list">
			<div class="list_tit">전체 <strong><?=$rowPageCount[0]?></strong>건 [<?=$faq->reqPageNo?>/<?=$rowPageCount[1]?>페이지] 
				<div class="select_box clear">					
					<select name="pageRowsVal" id="pageRowsVal" onchange="$('#pageRows').val($('#pageRowsVal').val());goSearch();" class="select_drop">
						<option value="10" <?=getSelected("10", $pageRows) ?>>10개씩</option>
						<option value="20" <?=getSelected("20", $pageRows) ?>>20개씩</option>
						<option value="50" <?=getSelected("50", $pageRows) ?>>50개씩</option>
					</select>				
				</div>
				<!--//select_box-->
			</div>
			<form name="frm" id="frm" action="process.php" method="post">
			<table>
				<caption> 회원목록 </caption>
					<colgroup>
						<col width="80px" />
						<col width="10%" />
						<col width="15%" />
						<col width="15%" />
						<col width="*" />
						<col width="*" />
					</colgroup>
				<thead>
					<tr>
						<th scope="col"><input type="checkbox" name="allChk" id="allChk" onClick="check(this, document.frm.no)"/></th>
						<th scope="col">번호</th>
						<th scope="col">아이디</th>
						<th scope="col">이름</th>
						<th scope="col">가입일</th>
						<th scope="col">탈퇴일</th>
					</tr>
				</thead>
				<tbody>
				<? if ($rowPageCount[0] == 0) { ?>
					<tr>
						<td colspan="10" align="center">등록된 탈퇴회원이 없습니다.</td>
					</tr>
				<?
					 } else {
						$i = 0;
						while ($row=mysql_fetch_assoc($result)) { 
				?>
					<tr class="cp">
						<td><input type="checkbox" value="<?=$row['no']?>" name="no[]" id="no"/></td>
						<td><?=$rowPageCount[0] - (($member->reqPageNo-1)*$pageRows) - $i?></td>
						<td class="txt_l"><?=$row['id'] ?></td>
						<td><?=$row['name'] ?></td>
						<td><?=$row['registdate'] ?></td>
						<td><?=$row['secededate'] ?></td>
					</tr>
				<?
						$i++;
						}
					 }
				?>
				</tbody>
			</table>
			<input type="hidden" name="cmd" id="cmd" value="groupDeleteSecede"/>
			<input type="hidden" name="stype" id="stype" value="<?=$_REQUEST['stype']?>"/>
			<input type="hidden" name="sval" id="sval" value="<?=$_REQUEST['sval']?>"/>
			</form>
		</div>
		<!-- //list -->
		<div class="btnSet clear">
			<div class="sBtn left">
				<input type="button" value="삭제" onclick="groupDelete();" />
			</div>
		</div>
		<div class="pagenate">
			<?=pageList($member->reqPageNo, $rowPageCount[1], $member->getQueryString('secede_index.php', 0, $_REQUEST))?>
		</div>
		<!-- //pagenate -->
	</div>
	<!-- //contents -->
</div>
<!-- e:warp --> 
<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/footer.php"; ?>
</body>
</html>
