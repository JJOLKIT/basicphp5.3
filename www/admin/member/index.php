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

$member = new Member($pageRows, "member", $_REQUEST);
$rowPageCount = $member->getCount($_REQUEST);
$result = $member->getList($_REQUEST);

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
	$("#searchForm").attr("action","index.php");
	$("#searchForm").submit();
}

function resetSearchForm() {
	$(".searchWrap input[type='text']").val("");
	$(".searchWrap input[type='checkbox']").removeAttr("checked");
	$(".searchWrap input[type='radio']").removeAttr("checked");
	$(".searchWrap select").val("all");
	goSearch();
}

function goExcel() {
	$("#searchForm").attr("action","excel.php");
	$("#searchForm").submit();
}
</script>
</head>


<body>
<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/header.php"; ?>
<!-- s:warp -->
	<div class="contWrap">
		<div class="titWrap">
			<h2>회원관리</h2>
			<div class="sBtnWrap">
				<div class="sBtn reset">
					<span class="material-icons">restart_alt</span>
					<input type="button" value="검색초기화" class="reset" onclick="resetSearchForm();"/>
				</div>
				<!--//sBtn-->
			</div>
			<!--//sBtnWrap-->
		</div>
		<div class="searchWrap">
			<form method="get" name="searchForm" id="searchForm" action="index.php">
				<table class="searchTable">
					<caption> 검색 </caption>
					<colgroup>
						<col width="5%" />
						<col width="10%" />
						<col width="5%" />
						<col width="40%" />
						<col width="5%" />
						<col width="*" />
					</colgroup>
					<tbody>
						<tr>
							<th>
								회원상태
							</th>
							<td>								
								<select name="ssecession"  onchange="goSearch();" class="select_drop">
									<option value="">전체</option>
									<?=getMemberStateType($_REQUEST['ssecession'])?>
								</select>								
							</td>
							<th>
								가입일
							</th>
							<td>
								<p class="calendar">
									<input type="text" name="sstartdate" id="sstartdate"  value="<?=$_REQUEST['sstartdate']?>" class="date" onKeyUp="cvtDate(this);isNumberOrHyphen(this);" maxlength="10"/>
									<span id="CalsstartdateIcon">
										<span class="material-icons" id="CalsstartdateIconImg">calendar_month</span>
									</span>
								</p>
								<span>~</span>
								<p class="calendar">
									<input type="text" name="senddate"  id="senddate" value="<?=$_REQUEST['senddate']?>" class="date" onKeyUp="cvtDate(this);isNumberOrHyphen(this);" maxlength="10"/>
									<span id="CalsenddateIcon">
										<span class="material-icons" id="CalsenddateIconImg">calendar_month</span>
									</span>
								</p>
								<input type="button" value="검색" onclick="goSearch();" class="hoverbg" />
								<input type="button" value="1M" onClick="searchDate('<?=$oneMonth?>','<?=$today?>');" class="hoverbg" />
								<input type="button" value="2M" onClick="searchDate('<?=$twoMonth?>','<?=$today?>');" class="hoverbg" />
								<input type="button" value="3M" onClick="searchDate('<?=$threeMonth?>','<?=$today?>');" class="hoverbg" />
							</td>
							<th>검색어</th>
							<td>								
								<select name="stype" class="select_drop">
									<option value="all" <?=getSelected("all", $_REQUEST['stype']) ?>>전체</option>
									<option value="name" <?=getSelected("name", $_REQUEST['stype']) ?>>이름</option>
									<option value="id" <?=getSelected("id", $_REQUEST['stype']) ?>>아이디</option>
									<option value="email" <?=getSelected("email", $_REQUEST['stype']) ?>>이메일</option>
									<option value="cell" <?=getSelected("cell", $_REQUEST['stype']) ?>>연락처</option>
									<option value="address" <?=getSelected("address", $_REQUEST['stype']) ?>>주소</option>
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
					<span class="select">
						<select name="pageRowsVal" id="pageRowsVal" onchange="$('#pageRows').val($('#pageRowsVal').val());goSearch();">
							<option value="10" <?=getSelected("10", $pageRows) ?>>10개씩</option>
							<option value="20" <?=getSelected("20", $pageRows) ?>>20개씩</option>
							<option value="50" <?=getSelected("50", $pageRows) ?>>50개씩</option>
						</select>
					</span>
				</div>
				<!--//select_box-->
			</div>
			<form name="frm" id="frm" action="process.php" method="post">
			<table>
				<caption> 회원목록 </caption>
					<colgroup>
						<col width="50px" />
						<col width="5%" />
						<col width="7%" />
						<col width="5%" />
						<col width="10%" />
						<col width="15%" />
						<col width="*" />
						<col width="10%" />
					</colgroup>
				<thead>
					<tr>
						<th scope="col"><input type="checkbox" name="allChk" id="allChk" onClick="check(this, document.frm.no)"/></th>
						<th scope="col">번호</th>
						<th scope="col">아이디</th>
						<th scope="col">이름</th>
						<th scope="col">연락처</th>
						<th scope="col">이메일</th>
						<th scope="col">주소</th>
						<th scope="col">가입일</th>
					</tr>
				</thead>
				<tbody>
				<? if ($rowPageCount[0] == 0) { ?>
					<tr>
						<td colspan="9" align="center">등록된 회원이 없습니다.</td>
					</tr>
				<?
					 } else {
						$targetUrl = "";
						$i = 0;
						while ($row=mysql_fetch_assoc($result)) { 
							$row = escape_html($row);
							$targetUrl = "style='cursor:pointer;' onclick=\"location.href='".$member->getQueryString('view.php', $row[no], $_REQUEST)."'\"";
				?>
					<tr class="cp">
						<td><input type="checkbox" value="<?=$row['no']?>" name="no[]" id="no"/></td>
						<td <?=$targetUrl?>><?=$rowPageCount[0] - (($member->reqPageNo-1)*$pageRows) - $i?></td>
						<td <?=$targetUrl?> class="txt_l"><?=$row['id'] ?></td>
						<td <?=$targetUrl?>><?=$row['name'] ?></td>
						<td <?=$targetUrl?>><?=$row['cell'] ?></td>
						<td <?=$targetUrl?> class="txt_l"><?=$row['email'] ?></td>
						<td <?=$targetUrl?> class="txt_l"><?=$row['addr0'] ?> <?=$row['addr1'] ?></td>
						<td <?=$targetUrl?>><?=getYMD($row['registdate'])?></td>
					</tr>
				<?
						$i++;
						}
					 }
				?>
				</tbody>
			</table>
			<input type="hidden" name="cmd" id="cmd" value="groupDelete"/>
			<?=$member->getQueryStringToHidden($_REQUEST) ?>
			</form>
		</div>
		<!-- //list -->
		<div class="btnSet clear">
			<div class="sBtn left">
				<input type="button" value="삭제" onclick="groupDelete();" />
			</div>
			<div class="right">
			<a href="write.php" class="btn edit hoverbg">
				<span class="material-icons">edit</span>회원등록
			</a>
		</div>
		</div>
		<div class="pagenate">
			<?=pageList($member->reqPageNo, $rowPageCount[1], $member->getQueryString('index.php', 0, $_REQUEST))?>
		</div>
		<!-- //pagenate -->
	</div>
	<!-- //contents -->
</div>
<!-- e:warp --> 
<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/footer.php"; ?>
</body>
</html>
