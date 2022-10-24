<? include $_SERVER['DOCUMENT_ROOT']."/admin/include/common.php"; ?>
<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

include_once $_SERVER['DOCUMENT_ROOT']."/lib/weblog/pageLog.class.php";

include $_SERVER['DOCUMENT_ROOT']."/admin/include/loginCheck.php";

$pageRows = 20;
$weblog = new pageLog($pageRows, $_REQUEST);
$rowPageCount = $weblog->getCount($_REQUEST);
$result = $weblog->getList($_REQUEST);
$pageTitle = "페이지 내역";

$today = getToday();
//if ($_REQUEST['edate']) $today = $_REQUEST['edate'];

$yesterday = getDayDateAdd(-1, $today);
$oneWeek = getDayDateAdd(-7, $today);
$oneMonth = getMonthDateAdd(-1, $today);
$twoMonth = getMonthDateAdd(-2, $today);
$threeMonth = getMonthDateAdd(-3, $today);



$eachData = $weblog->getPageCount($_REQUEST);
$deviceData = $weblog->getDeviceCount($_REQUEST);

$ADCount = $weblog->getADCount($_REQUEST);
?>
<!doctype html>
<html lang="ko">
<head>
<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/headHtml.php"; ?>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script>
google.charts.load('current', {'packages':['corechart']});
  google.charts.setOnLoadCallback(drawChart);
  google.charts.setOnLoadCallback(drawChart2);
function drawChart(){
	var data = google.visualization.arrayToDataTable([
		['페이지', '방문 수'],
		<?
			for($i = 0 ; $i < count($eachData); $i++){	
			if($i > 0){
				echo ",";
			}
		?>['<?=$eachData[$i][page]?>', <?=$eachData[$i][count]?>]<?}?>
		
	

	]);
	console.log(data);
	var options = {
		title : '페이지별 방문자 통계',
			 pieHole: 0.4
		
	};

	var chart = new google.visualization.PieChart(document.getElementById('chart'));
	chart.draw(data,options);
}

function drawChart2(){
	var data = google.visualization.arrayToDataTable([
		['접속기기', '접속 수'],
		<?
			for($i = 0 ; $i < count($deviceData); $i++){	
			if($i > 0){
				echo ",";
			}
		?>['<?=getDeviceName($deviceData[$i][user_agent])?>', <?=$deviceData[$i][count]?>]<?}?>
		
	

	]);
	var options = {
		title : '접속기기별 방문자 통계',
			 pieHole: 0.4,
		tooltip: {trigger : 'selection'}
		
	};

	var chart = new google.visualization.PieChart(document.getElementById('chart2'));
	chart.draw(data,options);
}
</script>

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

function goSearch() {
	$("#search").attr("action","index.php");
	$("#search").submit();
}

function searchDate(startDay, endDay) {
	var f = document.search;
	f.sstartdate.value = startDay;
	f.senddate.value = endDay;

	goSearch();
}

</script>
<style>
	.searchWrap .searchTable{min-width:830px;}
</style>
</head>

<body>
<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/header.php"; ?>
<!-- s:warp -->
	<div class="contWrap">
		<div class="titWrap">
			<h2>페이지 내역</h2>
		</div>
		<div class="searchWrap">
			<form method="get" name="search" id="search" action="index.php">
				<table class="searchTable">
					<caption> 검색 </caption>
					<colgroup>
						<col width="10%" />
						<col width="50%" />
						<col width="10%" />
						<col width="30%" />
					</colgroup>
					<tbody>
						<tr>
							<th>접속일시</th>
							<td >
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
								<input type="button" value="오늘" onClick="searchDate('<?=$today?>','<?=$today?>');" class="hoverbg" />
								<input type="button" value="어제" onClick="searchDate('<?=$yesterday?>','<?=$yesterday?>');" class="hoverbg" />
								<input type="button" value="1주" onClick="searchDate('<?=$oneWeek?>','<?=$today?>');" class="hoverbg" />
								<input type="button" value="1M" onClick="searchDate('<?=$oneMonth?>','<?=$today?>');" class="hoverbg" />
								<input type="button" value="2M" onClick="searchDate('<?=$twoMonth?>','<?=$today?>');" class="hoverbg" />
								<input type="button" value="3M" onClick="searchDate('<?=$threeMonth?>','<?=$today?>');" class="hoverbg" />
							</td>
							<th>검색어</th>
							<td>
								<input type="text" name="sval" id="sval" value="<?=$_REQUEST['sval']?>" />
								<input type="submit" value="검색" class="btn_search hoverbg" />
							</td>
						</tr>
					</tbody>
				</table>
				<input type="hidden" name="stype" value="all"/>
			</form>
		</div>
		
		<div class="eachResult clear">
			<div id="chart"></div>
			<div id="chart2"></div>
		</div>
		<!-- //search -->
		<div class="list">
			<p class="list_tit">총 <strong class="red"><?=$rowPageCount[0]?></strong>건 [<strong><?=$weblog->reqPageNo?></strong>/<?=$rowPageCount[1]?>페이지]
			</p>
			<p class="list_access">
				일반 접근 : <b><?=$rowPageCount[0]-$ADCount?></b>건 / 네이버 파워광고 : <b class="power"><?=$ADCount?></b>건
			</p>
			<form>
				<table>
					<caption> 목록 </caption>
						<colgroup>
							<col width="50px" />
							<col width="100px" />
							<col width="15%" />
							<col width="150px" />
							<col width="120px" />
							<col width="*"/>
							<col width="200px" />
						</colgroup>
					<thead>
						<tr>
							<th scope="col">번호</th>
							<th scope="col">접속기기</th>
							<th scope="col">브라우저</th>
							<th scope="col">OS</th>
							<th scope="col">아이피</th>
							<th scope="col">페이지</th>
							<th scope="col">일시</th>
						</tr>
					</thead>
					<tbody>
					<? if ($rowPageCount[0] == 0) { ?>
						<tr>
							<td colspan="6">등록된 데이터가 없습니다.</td>
						</tr>
					<?
						 } else {
							$i = 0;
							while ($row=mysql_fetch_assoc($result)) {
					?>
						<tr>
							<td><?=$rowPageCount[0] - (($weblog->reqPageNo-1)*$pageRows) - $i?></td>
							<td ><?=getDeviceName($row['user_agent'])?></td>
							<td ><?=$row['browser']?></td>
							<td><?=$row['os']?></td>
							<td><?=$row['ip']?></td>
							<td class="txt_l wbreak"><?=$row['page']?></td>
							<td><?=$row['registdate']?></td>
						</tr>
					<?
							$i++;
							}
						 }
					?>
					</tbody>
				</table>
			</form>
		</div>
		<!-- //list -->
		<div class="pagenate">
			<?=pageList($weblog->reqPageNo, $rowPageCount[1], $weblog->getQueryString('index.php', 0, $_REQUEST))?>
		</div>
	</div>
</div>
<!-- e:warp --> 
<? include $_SERVER['DOCUMENT_ROOT']."/admin/include/footer.php"; ?>

</body>
</html>
