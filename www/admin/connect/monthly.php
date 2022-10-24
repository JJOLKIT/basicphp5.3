<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/common.php"; ?>
<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

include_once $_SERVER['DOCUMENT_ROOT']."/lib/weblog/Weblog.class.php";



include $_SERVER['DOCUMENT_ROOT']."/admin/include/loginCheck.php";


$pageRows = 100;
$business = new Weblog($pageRows, $_REQUEST);
$monthlyCount = $business->getMonthlyCount($_REQUEST);
//$bmonthlyCount = $business->getMonthlyBusiness($_REQUEST);
$pageTitle = "월별 내역";

$today = getToday();
//if ($_REQUEST['edate']) $today = $_REQUEST['edate'];

$yesterday = getDayDateAdd(-1, $today);
$oneWeek = getDayDateAdd(-7, $today);
$oneMonth = getMonthDateAdd(-1, $today);
$twoMonth = getMonthDateAdd(-2, $today);
$threeMonth = getMonthDateAdd(-3, $today);

?>
<!doctype html>
<html lang="ko">
<head>
<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/headHtml.php"; ?>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
			['월', '유입수'],
			['1월',  <?=$monthlyCount[cnt1]?>],
			['2월',  <?=$monthlyCount[cnt2]?>],
			['3월',  <?=$monthlyCount[cnt3]?>],
			['4월',  <?=$monthlyCount[cnt4]?>],
			['5월',  <?=$monthlyCount[cnt5]?>],
			['6월',  <?=$monthlyCount[cnt6]?>],
			['7월',  <?=$monthlyCount[cnt7]?>],
			['8월',  <?=$monthlyCount[cnt8]?>],
			['9월',  <?=$monthlyCount[cnt9]?>],
			['10월',  <?=$monthlyCount[cnt10]?>],
			['11월',  <?=$monthlyCount[cnt11]?>],
			['12월',  <?=$monthlyCount[cnt12]?>]
        ]);

        var options = {
          title: '<?=Date("Y")?>년 월별 통계',
          hAxis: {title: 'Monthly',  titleTextStyle: {color: '#333'}},
          vAxis: {minValue: 0}
        };

        var chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
        chart.draw(data, options);
      }
    </script>
</head>

<body>
<? include $_SERVER['DOCUMENT_ROOT']."/admin/include/header.php"; ?>
<!-- s:warp -->
	<div class="contWrap">
		<div class="titWrap">
			<h2>월간 통계</h2>
		</div>
		<div class="list">
			<div id="chart_div" class="chart_monthly"></div>
		</div>
		<!-- //list -->
		<div class="pagenate">
			<?=pageList($business->reqPageNo, $rowPageCount[1], $business->getQueryString('index.php', 0, $_REQUEST))?>
		</div>
	</div>
</div>
<!-- e:warp --> 
<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/footer.php"; ?>
</body>
</html>
