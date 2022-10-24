<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/common.php"; ?>
<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/Bot.class.php";

include $_SERVER['DOCUMENT_ROOT']."/admin/include/loginCheck.php";
include "config.php";

$today = getToday();
$oneWeek = getDayDateAdd(-7, $today);
$oneMonth = getMonthDateAdd(-1, $today);
$twoMonth = getMonthDateAdd(-2, $today);
$threeMonth = getMonthDateAdd(-3, $today);




$product = new Bot($pageRows, $tablename, $_REQUEST);
$rowPageCount = $product->getCount($_REQUEST);
$result = $product->getList($_REQUEST);


?>
<!doctype html>
<html lang="ko">
<head>

<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/headHtml.php"; ?>
<style>
	tr.todayMenu {background:#9fc54d50;}
</style>
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

	changeCategory2("<?=$_REQUEST['sct2']?>");
	changeCategory3("<?=$_REQUEST['sct2']?>", "<?=$_REQUEST['sct3']?>");
	
});
</script>
<script>
function groupDelete() {	
	if ( isSeleted(document.frm.no) ){
		if (confirm("선택한 항목을 삭제하시겠습니까?")) {
			$("#cmd").val("groupDelete");
			document.frm.submit();
		}
	} else {
		alert("삭제할 항목을 하나 이상 선택해 주세요.");
	}
}

function groupMain() {	
	if ( isSeleted(document.frm.no) ){
		if (confirm("선택한 항목을 메인노출하시겠습니까?")) {
			$("#cmd").val("groupMain");
			document.frm.submit();
		}
	} else {
		alert("메인노출할 항목을 하나 이상 선택해 주세요.");
	}
}

function groupYesDisplay() {	
	if ( isSeleted(document.frm.no) ){
		if (confirm("선택한 항목을 노출 처리하시겠습니까?")) {
			$("#cmd").val("groupYesDisplay");
			document.frm.submit();
		}
	} else {
		alert("노출 처리할 항목을 하나 이상 선택해 주세요.");
	}
}

function groupNoDisplay() {	
	if ( isSeleted(document.frm.no) ){
		if (confirm("선택한 항목을 미노출 처리하시겠습니까?")) {
			$("#cmd").val("groupNoDisplay");
			document.frm.submit();
		}
	} else {
		alert("미노출 처리할 항목을 하나 이상 선택해 주세요.");
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

//상품구분
function checkStype() {
	$("#stypeAll").prop('checked', false) ;
}
// 상품구분 전체
function uncheckStype() {
	if ($("#stypeAll").prop('checked')) {
		$("#stype1").prop('checked', true) ;
		$("#stype2").prop('checked', true) ;
		$("#stype3").prop('checked', true) ;
		$("#stype4").prop('checked', true) ;
	} else {
    	$("#stype1").prop('checked', false) ;
    	$("#stype2").prop('checked', false) ;
    	$("#stype3").prop('checked', false) ;
    	$("#stype4").prop('checked', false) ;
	}
}

//중분류 셀렉트박스 div에 출력
function changeCategory2(sct2){
	if($('#sct1').val() == '' || parseInt('<?=$_REQUEST[sct1]?>') != sct2){
		$('select[name="sct2"]').val('');
	}
	$.get("cate2SelectList.php?type=index&name=sct2&depth1_no="+$("#sct1").val()+"&sct2="+sct2, function (data, status) {
		$("#cate2Select").html(data);
	});
	//goSearch();
}
//소분류 셀렉트박스 div에 출력

function changeCategory3(sct2, sct3){
	$.get("cate3SelectList.php?type=index&name=sct3&depth1_no="+$("#sct1").val()+"&depth2_no="+sct2+"&sct3="+sct3, function (data, status) {
		$("#cate3Select").html(data);
	});
	//goSearch();
}


function changeSeq(){
	window.open('seq.php?stype4=1', '_blank', 'width=1600, height=800,scrollbar=yes, left=20, top=20');
}
</script>
</head>


<body>
<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/header.php"; ?>
<!-- s:warp -->
	<div class="contWrap">
		<div class="titWrap">
			<h2><?=$pageTitle?></h2>
			<div class="sBtnWrap">	
				<!--<input type="button" value="엑셀 다운로드"  class="reset" onclick="goExcel();"/>-->
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
								<select name="stype" id="stype" class="select_drop">
									<option value="all" <?=getSelected("all", $_REQUEST['stype']) ?>>전체</option>
									<option value="title" <?=getSelected("title", $_REQUEST['stype']) ?>>제목</option>
									<option value="contents" <?=getSelected("contents", $_REQUEST['stype']) ?>>내용</option>
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
			<div class="list_tit">전체 <strong class="blue"><?=$rowPageCount[0]?></strong>건 
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
				<caption> 상품목록 </caption>
					<colgroup>
						<col width="50px" />
						<col width="4%" />
						<col width="20%"/>
						<col width="*"/>
						<col width="12%"/>
					</colgroup>
				<thead>
					<tr>
						<th scope="col"><input type="checkbox" name="allChk" id="allChk" onClick="check(this, document.frm.no)"/></th>
						<th scope="col">번호</th>
						<th scope="col">버튼명</th>
						<th scope="col">답변</th>
						<th scope="col">등록일</th>
					</tr>
				</thead>
				<tbody>
				<? if ($rowPageCount[0] == 0) { ?>
					<tr>
						<td colspan="5" align="center">등록된 상품이 없습니다.</td>
					</tr>
				<?
					 } else {
						$targetUrl = "";
						$i = 0;
						while ($row=mysql_fetch_assoc($result)) { 
							$row = escape_html($row);


							$targetUrl = "style='cursor:pointer;' onclick=\"location.href='".$product->getQueryString('edit.php', $row[no], $_REQUEST)."'\"";

							if($row['top'] == 1){
								$cls = "class='bg'";
							}else{
								$cls = "";	
							}
				?>

					<tr <?=$cls?>>
						<td><input type="checkbox" value="<?=$row['no']?>" name="no[]" id="no"/></td>
						<td <?=$targetUrl?>><?=$rowPageCount[0] - (($product->reqPageNo-1)*$pageRows) - $i?></td>
						
						<td <?=$targetUrl?>><?=$row['title']?></td>
						<td <?=$targetUrl?>><?=mb_strimwidth(strip_tags($row['contents']), 0, 160, "...", "UTF-8")?></td>
						<td <?=$targetUrl?>><?=$row['registdate']?></td>
					</tr>
				<?
						$i++;
						}
					 }
				?>
				</tbody>
			</table>
			<input type="hidden" name="cmd" id="cmd" value="groupDelete"/>
			<?=$product->getQueryStringToHidden($_REQUEST) ?>
			</form>
		</div>
		<!-- //list -->
		<div class="btnSet clear">
			<div class="sBtn left">
				<input type="button" value="삭제" onclick="groupDelete();"/>
			</div>
			<div class="right">
				<a href="write.php" class="btn edit hoverbg">
					<span class="material-icons">edit</span>메세지등록
				</a>
			</div>
		</div>
		<div class="pagenate">
			<?=pageList($product->reqPageNo, $rowPageCount[1], $product->getQueryString('index.php', 0, $_REQUEST))?>
		</div>
		<!-- //pagenate -->
	</div>
	<!-- //contents -->
</div>
<!-- e:warp --> 
<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/footer.php"; ?>
</body>
</html>
