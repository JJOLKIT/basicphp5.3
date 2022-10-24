<? include $_SERVER['DOCUMENT_ROOT']."/include/common.php"; ?>
<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

include_once $_SERVER['DOCUMENT_ROOT']."/lib/weblog/pageLog.class.php";

include $_SERVER['DOCUMENT_ROOT']."/admin/include/loginCheck.php";

$pageRows = 9999;
$weblog = new pageLog($pageRows, $_REQUEST);

$today = getToday();
//if ($_REQUEST['edate']) $today = $_REQUEST['edate'];
$pageTitle = "브라우저&OS 내역";

$tm = Date('Y-m');
if(isset($_REQUEST['sdate'])){
	$today = getDayDateAdd(+4, $_REQUEST['sdate']);
	$tm = Date('Y-m', strtotime($_REQUEST['sdate']));
}


$yesterday = getDayDateAdd(-1, $today);
$oneWeek = getDayDateAdd(-7, $today);
$oneMonth = getMonthDateAdd(-1, $today);
$twoMonth = getMonthDateAdd(-2, $today);
$threeMonth = getMonthDateAdd(-3, $today);

if(!isset($_REQUEST['stype'])){
	$_REQUEST['stype'] = "daily";
}

$btotal = $weblog->getChartBrowserCount($_REQUEST);

?>
<!doctype html>
<html lang="ko">
<head>
<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/headHtml.php"; ?>
<script>
//달력부분
$(window).load(function() {
	initCal({id:"sdate",type:"day",today:"y"});			
	initCal({id:"edate",type:"day",today:"y"});
	
	$("input[type=text][name*=sval]").keypress(function(e){
		if(e.keyCode == 13){
			goSearch();
		}
	});
	
	
});

function goSearch() {
	$("#search").attr("action","browser.php");
	$("#search").submit();
}

function resetSearchForm() {
	$(".searchWrap input[type='text']").val("");
	$(".searchWrap input[type='checkbox']").removeAttr("checked");
	$(".searchWrap input[type='radio']").removeAttr("checked");
	$(".searchWrap select").val("all");
	goSearch()();
}

function searchDate(startDay, endDay) {
	var f = document.search;
	f.sdate.value = startDay;
	f.edate.value = endDay;

	goSearch();
}

</script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script>

	 google.charts.load("current", {packages:["corechart"]});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Browser', 'Count'],
          ['Google Chrome',    <?=$btotal['chrome']?>],
          ['Explorer Edge',      <?=$btotal['edge']?>],
          ['Internet Explorer',  <?=$btotal['ie']?>],
		  ['Naver Whale', <?=$btotal['whale']?>],
          ['Mozilla FireFox', <?=$btotal['firefox']?>],
          ['Apple Safri',    <?=$btotal['safari']?>	],
		  ['Opera', <?=$btotal['opera']?>],
		  ['Netscape', <?=$btotal['netscape']?>],
		  ['Others', <?=$btotal['other']?>]
        ]);

        var options = {
          title: '브라우저 통계',
          is3D: true,
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart_3d'));
        chart.draw(data, options);
      }

	  function goSearchAll(){
		$('#sdate').val('');
		goSearch();
	  }
    </script>
	<script>

	 google.charts.load("current", {packages:["corechart"]});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['OS', 'Count'],
          ['Windows',    <?=$btotal['windows']?>],
          ['Linux',      <?=$btotal['linux']?>],
          ['Mac',  <?=$btotal['mac']?>],
		  ['Others', <?=$btotal['os_other']?>]
        ]);

        var options = {
          title: 'OS 통계',
          is3D: true,
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart_3d2'));
        chart.draw(data, options);
      }


    </script>
	<style>
		.list form table{min-width:unset;}
	</style>
</head>

<body>
<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/header.php"; ?>
<!-- s:warp -->
	<div class="contWrap">
		<div class="titWrap">
			<h2>접속 브라우저 &amp; OS 통계</h2>
			<div class="sBtn reset">
				<span class="material-icons">restart_alt</span>
				<input type="button" value="검색초기화"  class="reset"  onclick="resetSearchForm();"/>
			</div>
		</div>
		
		<div class="searchWrap">
			<form method="get" name="search" id="search" action="index.php">
				<table class="searchTable">
					<caption> 검색 </caption>
					<colgroup>
						<col width="10%" />
						<col width="60%" />
					</colgroup>
					<tbody>
						<tr>
							<th>검색일</th>
							<td>
								<p class="calendar">
									<input type="text" name="sdate" id="sdate"  value="<?=$_REQUEST['sdate']?>" class="date" onKeyUp="cvtDate(this);isNumberOrHyphen(this);" maxlength="10"/>
									<span id="CalsdateIcon">
										<span class="material-icons" id="CalsdateIconImg">calendar_month</span>
									</span>
								</p>
								<input type="button" value="전체기간" onclick="goSearchAll();" class="hoverbg" />
								<input type="button" value="검색" onclick="goSearch();" class="hoverbg" />
							</td>

						</tr>
					</tbody>
				</table>
			</form>
		</div>
		<!-- //search -->
		
		<div id="table" class="list browser_list">
			<div class="row clear">
				<div id="piechart_3d" class="chart_browser"></div>
				<form>
					<table>
						<colgroup>
							<col width="50%"/>
							<col width="50%"/>
						</colgroup>
						<thead>
							<tr>
								<th>Browser</th>
								<th>Count</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>Google Chrome</td>
								<td><?=number_format($btotal['chrome'])?></td>
							</tr>
							<tr>
								<td>Explorer Edge</td>
								<td><?=number_format($btotal['edge'])?></td>
							</tr>
							<tr>
								<td>Internet Explorer</td>
								<td><?=number_format($btotal['ie'])?></td>
							</tr>
							<tr>
								<td>Naver Whale</td>
								<td><?=number_format($btotal['whale'])?></td>
							</tr>
							<tr>
								<td>Mozilla FireFox</td>
								<td><?=number_format($btotal['firefox'])?></td>
							</tr>
							<tr>
								<td>Apple Safri</td>
								<td><?=number_format($btotal['safari'])?></td>
							</tr>
							<tr>
								<td>Opera</td>
								<td><?=number_format($btotal['opera'])?></td>
							</tr>
							<tr>
								<td>Netscape</td>
								<td><?=number_format($btotal['netscape'])?></td>
							</tr>
							<tr>
								<td>Others</td>
								<td><?=number_format($btotal['other'])?></td>
							</tr>
						</tbody>
					</table>
				</form>
			</div>
			<div class="row clear">
				<div id="piechart_3d2" class="chart_browser"></div>
				<form>
					<table>
						<colgroup>
							<col width="50%"/>
							<col width="50%"/>
						</colgroup>
						<thead>
							<tr>
								<th>OS</th>
								<th>Count</th>
						</thead>
						<tbody>
							<tr>
								<td>Windows</td>
								<td><?=number_format($btotal['windows'])?></td>
							</tr>
							<tr>
								<td>Linux</td>
								<td><?=number_format($btotal['linux'])?></td>
							</tr>
							<tr>
								<td>Mac</td>
								<td><?=number_format($btotal['mac'])?></td>
							</tr>
							<tr>
								<td>Others</td>
								<td><?=number_format($btotal['os_other'])?></td>
							</tr>
						</tbody>
					</table>
				</form>
			</div>
		</div>

	</div>
</div>
<!-- e:warp --> 
<? include $_SERVER['DOCUMENT_ROOT']."/admin/include/footer.php"; ?>

</body>
</html>
