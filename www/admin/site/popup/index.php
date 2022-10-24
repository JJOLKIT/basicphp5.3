<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/common.php"; ?>
<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/Popup.class.php";

include $_SERVER['DOCUMENT_ROOT']."/admin/include/loginCheck.php";
include "config.php";

$popup = new Popup($pageRows, $tablename, $_REQUEST);
$rowPageCount = $popup->getCount($_REQUEST);
$result = rstToArray($popup->getList($_REQUEST));
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

function ShowDiv(road) {
	$("#"+road).show();
}

var ns4=document.layers;
var ie4=document.all;
var ns6=document.getElementById&&!document.all;
var zCount = 9999;
</script>
</head>


<body>
<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/header.php"; ?>
<!-- s:warp -->
	<div class="contWrap">
		<div class="titWrap">
			<h2><?=$pageTitle ?></h2>
		</div>
		<div class="searchWrap">
			<form method="get" name="searchForm" id="searchForm" action="index.php">
				<table class="searchTable">
					<caption> 게시글검색 </caption>
					<colgroup>
						<col width="10%" />
						<col width="*%" />
					</colgroup>
					<tbody>
						<tr>
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
			<p class="list_tit">전체 <strong><?=$rowPageCount[0]?></strong>건 [<?=$notice->reqPageNo?>/<?=$rowPageCount[1]?>페이지] 
			</p>
			<form name="frm" id="frm" action="process.php" method="post">
			<table>
				<caption> 목록 </caption>
					<colgroup>
						<col width="5%" />
						<col width="5%" />
						<col width="" />
						<col width="20%" />
						<col width="15%" />
						<col width="10%" />
					</colgroup>
				<thead>
					<tr>
						<th scope="col"><input type="checkbox" name="allChk" id="allChk" onClick="check(this, document.frm.no)"/></th>
						<th scope="col">번호</th>
						<th scope="col">제목</th> 
						<th scope="col">기간</th>
						<th scope="col">작성일</th> 
						<th scope="col">팝업확인</th>
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
					 	
					 	for($i = 0; $i < count($result); $i++) {
					 		$row = $result[$i];
							$row = escape_html($row);
					 		$targetUrl = "style='cursor:pointer;' onclick=\"location.href='".$popup->getQueryString('edit.php', $row[no], $_REQUEST)."'\"";
					 			
					 		$tempContents = $row[contents];
					 		$detailBtn = "";
					 		if ($row[relation_url]) {
					 			$detailBtn = "<img src=\"/img/btn_detail.gif\" align=\"absmiddle\" style=\"border:0; margin:2 0 0 0;  text-align:right; cursor:pointer;\" onclick=\"window.open('http://".$row[relation_url]."','_blank','height=600,width=800,top=50,left=50,toolbar=1, directories=1, status=1, menubar=1, scrollbars=1, resizable=1,location=1')\">&nbsp;";
					 		}
					 		if ($row[type] == '1') {
					 			$tempContents = "<img src='".$uploadPath.$row[imagename]."' style='border:0; cursor:pointer;' ";
					 			if ($row[relation_url]) {
					 				$tempContents += "onclick=\"window.open('http://".$row[relation_url]."','_blank','height=600,width=800,top=50,left=50,toolbar=1, directories=1, status=1, menubar=1, scrollbars=1, resizable=1,location=1')\"";
					 			}
					 			$tempContents .= ">";
					 			$detailBtn = "";
					 		}
				?>
					<tr>
						<td class="first"><input type="checkbox" name="no[]" id="no" value="<?=$row[no]?>"/></td>
						<td <?=$targetUrl?>><?=$rowPageCount[0] - (($popup->reqPageNo-1)*$pageRows) - $i?></td>
						<td <?=$targetUrl?>>
							<a href="<?=$popup->getQueryString('edit.php', $row[no], $_REQUEST)?>">
							<?=$row[title]?>
							</a>
						</td>
						<td <?=$targetUrl?>><?=getYMD($row[start_day])?>~<?=getYMD($row[end_day])?></td>
						<td <?=$targetUrl?>><?=$row[registdate]?></td>
						<td class="last">
							<? if ($row[type] == '0' || $row[type] == '1') { ?>
							<button type="button" class="btn hoverbg" onclick="startTime('divPop<?=$row[no]?>', 'popMain<?=$row[no]?>', '<?=$row[area_top]?>', '<?=$row[area_left]?>', '<?=$row[popup_width]?>', '<?=$row[popup_height]?>', '0');ShowDiv('showimage<?=$i?>');">팝업확인</button>
							<? } else { ?>
							<button type="button" class="btn hoverbg" onclick="window.open('/include/popup/popup.php?no=<?=$row[no]?>','divPop<?=$row[no]?>','width=<?=$row[popup_width]?>, height=<?=$row[popup_height]+20?>, top=<?=$row[area_top]?>, left=<?=$row[area_left]?>,toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no');">팝업확인</button>
							<? } ?>
							
						</td>
					</tr>
					<?
							}
						 }
					?>
				</tbody>
			</table>
			<input type="hidden" name="cmd" id="cmd" value="groupDelete"/>
			<input type="hidden" name="stype" id="stype" value="<?=$_REQUEST[stype]?>"/>
			<input type="hidden" name="sval" id="sval" value="<?=$_REQUEST[sval]?>"/>
		</form>
		</div>
		<!-- //list -->
		<div class="btnSet clear">
			<div class="sBtn left">
				<input type="button" value="삭제"   onclick="groupDelete();"/>
			</div>
			<div class="right">
				<a href="write.php" class="btn edit hoverbg">
					<span class="material-icons">edit</span>글쓰기
				</a>
			</div>
		</div>
		<div class="pagenate">
			<?=pageList($popup->reqPageNo, $rowPageCount[1], $popup->getQueryString('index.php', 0, $_REQUEST))?>
		</div>
		<!-- //pagenate -->
	</div>
	<!-- //contents -->
</div>
<!-- e:warp --> 
<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/footer.php"; ?>
<?
for($i = 0; $i < count($result); $i++) {
	$row = $result[$i];
	
	$targetUrl = "style='cursor:pointer;' onclick=\"location.href='".$popup->getQueryString('edit.php', $row[no], $_REQUEST)."'\"";
											
	$tempContents = $row[contents];
	$detailBtn = "";
	if ($row[relation_url]) {
		$detailBtn = "<img src=\"/img/btn_detail.gif\" align=\"absmiddle\" style=\"border:0; margin:2 0 0 0;  text-align:right; cursor:pointer;\" onclick=\"window.open('http://".$row[relation_url]."','_blank','height=600,width=800,top=50,left=50,toolbar=1, directories=1, status=1, menubar=1, scrollbars=1, resizable=1,location=1')\">&nbsp;";
	}
	if ($row[type] == '1') {
		$tempContents = "<img src='".$uploadPath.$row[imagename]."' style='border:0; cursor:pointer;' ";
		if ($row[relation_url]) {
			$tempContents .= "onclick=\"window.open('http://".$row[relation_url]."','_blank','height=600,width=800,top=50,left=50,toolbar=1, directories=1, status=1, menubar=1, scrollbars=1, resizable=1,location=1')\"";
		}
		$tempContents .= ">";
		$detailBtn = "";
	}								
	
	if ($row[type] == '0' || $row[type] == '1') {
		$border = "";
		if ($row[type] != '1') {
			$border = "border:".$row[border_color]." 5px solid;";
		}
?>
	<div id="showimage<?=$i?>" style="position:absolute;left:<?=$row[area_left]?>px;top:<?=$row[area_top]?>px;z-index:999999; display:none;">
		<div id="divPop<?=$row[no]?>">
			<div id="dragbar<?=$i?>" style="<?=$border?> background:#fff; overflow:hidden;">
				<div id="popMain<?=$row[no]?>"><p><?=$tempContents?></p><p><?=$detailBtn?></p></div>
			</div>
			<div style="background:#000000; color:#fff; vertical-align:middle; text-align:right; padding:3px 10px;">
				<input type="checkbox" id="chkbox<?=$row[no]?>" onclick="closeLayer('divPop<?=$row[no]?>', this, '1', 'showimage<?=$i?>');"/>오늘 하루 이 창을 열지 않음 test
				<a onclick="closeLayer('divPop<?=$row[no]?>', 'chkbox<?=$row[no]?>', '1', 'showimage<?=$i?>');" style="color:#fff; cursor:pointer;">[닫기]</a>
			</div>
		</div>
	</div>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<script type="text/javascript">
	$( function() {
	    $( "#showimage<?=$i?>" ).draggable();
	} );
	/*
		var	crossobj<?=$i?>;
		var	dragapproved<?=$i?>;
		
		function drag_drop<?=$i?>(e){
			if (ie4&&dragapproved<?=$i?>){
				crossobj<?=$i?>.style.left = tempx+event.clientX-offsetx+"px";
				crossobj<?=$i?>.style.top = tempy+event.clientY-offsety+"px";
				return false;
			} else if (ns6&&dragapproved<?=$i?>){
				crossobj<?=$i?>.style.left = tempx+e.clientX-offsetx+"px";
				crossobj<?=$i?>.style.top = tempy+e.clientY-offsety+"px";
				return false;
			}
		}
		
		 function initializedrag<?=$i?>(e){
			crossobj<?=$i?> = $("#showimage<?=$i?>")[0];
		
			var firedobj=ns6? e.target : event.srcElement;
			var topelement=ns6? "HTML" : "BODY";
			while (firedobj.tagName!=topelement&&firedobj.id!="dragbar<?=$i?>"){
			  firedobj=ns6? firedobj.parentNode : firedobj.parentElement;
			}
		
			if (firedobj.id=="dragbar<?=$i?>"){
			  offsetx=ie4? event.clientX : e.clientX;
			  offsety=ie4? event.clientY : e.clientY;
		
			  tempx=parseInt(crossobj<?=$i?>.style.left);
			  tempy=parseInt(crossobj<?=$i?>.style.top);
		
			  dragapproved<?=$i?>=true;
			  document.onmousemove=drag_drop<?=$i?>;
			}
		  }    
		
		  function initDrags<?=$i?>() {
			  zCount++;
			  document.onmousedown=initializedrag<?=$i?>;
			  document.onmouseup=new Function("dragapproved<?=$i?>=false");
			  document.getElementById("showimage<?=$i?>").style.zIndex = zCount;
		  }
		 */
		</script>							
<?
	}
}
?>
</body>
</html>
