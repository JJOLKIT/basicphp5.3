<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/common.php"; ?>
<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

include_once $_SERVER['DOCUMENT_ROOT']."/lib/weblog/CountryLog.class.php";

include $_SERVER['DOCUMENT_ROOT']."/admin/include/loginCheck.php";

$pageRows = 20;
$notice = new CountryLog($pageRows, $_REQUEST);
$gresult = rstToArray($notice->getGroupList($_REQUEST));
$rowPageCount = $notice->getCount($_REQUEST);
$result = $notice->getList($_REQUEST);
$pageTitle = "접속국가 내역";

$countryResult = rstToArray($notice->getCountryList($_REQUEST));

$krresult = rstToArray($notice->getKRList($_REQUEST));

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
	$("#search").attr("action","index.php");
	$("#search").submit();
}

function searchDate(startDay, endDay) {
	var f = document.search;
	f.sdate.value = startDay;
	f.edate.value = endDay;

	goSearch();
}

</script>

<style>
	@media(max-width:1200px){
		td a {max-width:98%; width:100%; display:inline-block; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;}
		
		.searchWrap .searchTable{min-width:820px;}
	}
</style>
<script src="https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js"></script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCTOeqfcreftxv2uiWj-AGj7NRtmUQTBu8&region=ko&language=ko"></script>	

</head>

<body>
<? include $_SERVER['DOCUMENT_ROOT']."/admin/include/header.php"; ?>
<!-- s:warp -->
	<div class="contWrap">
		<div class="titWrap">
			<h2>전세계 접속현황</h2>
		</div>
		<div id="map" class="map_country">
		</div>

		<div class="titWrap">
			<h2>접속 국가 현황</h2>
		</div>

		<!-- //search -->
		<div class="searchWrap">
			<form method="get" name="search" id="search" action="index.php">
			
				<table class="searchTable">
					<caption> 검색 </caption>
					<colgroup>
						<col width="10%" />
						<col width="*" />
					</colgroup>
					<tbody>
						<tr>
							<th>접속국가</th>
							<td>
								<span class="select">
									<select name="scode" onchange="goSearch();">
										<option value="">선택</option>
										<?for($i = 0; $i < count($countryResult); $i++){
											$row = $countryResult[$i];
										?>
										<option value="<?=$row['code']?>" <?=getSelected($row['code'], $_REQUEST['scode'])?>><?=$row['country']?></option>
										<?}?>
									</select>
								</span>
							</td>
							<th>접속일시</th>
							<td>
								<p class="calendar">
									<input type="text" name="sdate" id="sdate"  value="<?=$_REQUEST['sdate']?>" class="date" onKeyUp="cvtDate(this);isNumberOrHyphen(this);" maxlength="10"/>
									<span id="CalsdateIcon">
										<span class="material-icons" id="CalsdateIconImg">calendar_month</span>
									</span>
								</p>
								<span>~</span>
								<p class="calendar">
									<input type="text" name="edate"  id="edate" value="<?=$_REQUEST['edate']?>" class="date" onKeyUp="cvtDate(this);isNumberOrHyphen(this);" maxlength="10"/>
									<span id="CaledateIcon">
										<span class="material-icons" id="CaledateIconImg">calendar_month</span>
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
						</tr>
					</tbody>
				</table>
			
				<input type="hidden" name="stype" value="all"/>
			</form>
		</div>
		
		
		<div class="chartWrap clear">
			<div class="row clear">
				<div class="list">
					<table>
						<thead>
							<tr>
								<th>국가명</th>
								<th>방문 수</th>
							</tr>
						</thead>
						<tbody>
							<?for($i = 0; $i < count($gresult); $i++){ $row = $gresult[$i]; ?>
							<tr><td><?=$row['country']?></td><td><?=$row['cnt']?></td></tr>
							<?}?>
						</tbody>
					</table>
				</div>
				<div class="chart_country">
					<canvas id="myChart"></canvas>
				</div>
			</div>
			<div class="row clear">
				<div class="chart_country">
					<canvas id="myChart2"></canvas>
				</div>
				<div class="list">
					<table>
						<thead>
							<tr>
								<th>도시명</th>
								<th>방문 수</th>
							</tr>
						</thead>
						<tbody>
							<?for($i = 0; $i < count($krresult); $i++){ $row = $krresult[$i]; ?>
							<tr><td><?=$row['city']?></td><td><?=$row['cnt']?></td></tr>
							<?}?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		
		<div class="list">
			<p class="list_tit">총 <strong class="red"><?=$rowPageCount[0]?></strong>건 [<strong><?=$notice->reqPageNo?></strong>/<?=$rowPageCount[1]?>페이지]</p>
			<form>
				<table style="table-layout:fixed;">
					<caption> 목록 </caption>
						<colgroup>
							<col width="100px" />
							<col width="15%" />
							<col width="15%" />
							<col width="15%" />
							<col width="10%" />
							<col width="8%" />
							<col width="*" />
						</colgroup>
					<thead>
						<tr>
							<th scope="col">번호</th>
							<th scope="col">국가명</th>
							<th scope="col">도시명</th>
							<th scope="col">지역명</th>
							<th scope="col">아이피</th>
							<th>접속기기</th>
							<th>접속일시</th>
						</tr>
					</thead>
					<tbody>
					<? if ($rowPageCount[0] == 0) { ?>
						<tr>
							<td colspan="7">등록된 데이터가 없습니다.</td>
						</tr>
					<?
						 } else {
							$i = 0;
							while ($row=mysql_fetch_assoc($result)) {
					?>
						<tr id="no<?=$i?>">
							<td><?=$rowPageCount[0] - (($notice->reqPageNo-1)*$pageRows) - $i?></td>
							<td><?=$row['country']?></td>
							<td><?=$row['city']?></td>
							<td><?=$row['region']?></td>
							<td><a href="/connect/index.php?sval=<?=$row['ip']?>"><?=$row['ip']?></a></td>
							<td><?=$row['device']?></td>
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
			<?=pageList($notice->reqPageNo, $rowPageCount[1], $notice->getQueryString('index.php', 0, $_REQUEST))?>
		</div>



	</div>
</div>
<?$now = Date('Y-m-d');?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>

var bgColor = ['rgba(255,64,105,0.5)', 'rgba(255,159,64,0.5)', 'rgba(255,205,86,0.5)', 'rgba(75,192,192,0.5)', 'rgba(72,170,237,0.5)', 'rgba(116,230,96,0.5)', 'rgba(229,238,248,0.5)', 'rgba(212,171,212,0.5)'];

const data = {
  labels: [
	  <?
			for($i = 0; $i < count($gresult); $i++){		
				if($i > 0){ echo ","; }
				echo "'";
				echo $gresult[$i]['country'];
				echo "'";
			}
	  ?>
  ],
  datasets: [
    {
      label: 'Dataset 1',
      data: [
	 <?
			for($i = 0; $i < count($gresult); $i++){		
				if($i > 0){ echo ","; }
				echo $gresult[$i]['cnt'];
			}
	  ?>
	  ],
      backgroundColor: bgColor,
	  borderWidth : 1,
      
    }
  ]
};


const config = {
  type: 'doughnut',
  data: data,
  options: {
    responsive: true,
    plugins: {
      legend: {
        position: 'top',
      },
      title: {
        display: true,
        text: '접속 국가 현황 TOP8'
      }
    }
  },
};



var ctx = document.getElementById('myChart');
var myChart = new Chart(ctx, config);



var bgColor = ['rgba(255,64,105,0.5)', 'rgba(255,159,64,0.5)', 'rgba(255,205,86,0.5)', 'rgba(75,192,192,0.5)', 'rgba(72,170,237,0.5)', 'rgba(116,230,96,0.5)', 'rgba(229,238,248,0.5)', 'rgba(212,171,212,0.5)'];

const data2 = {
  labels: [
	  <?
			for($i = 0; $i < count($krresult); $i++){		
				if($i > 0){ echo ","; }
				echo "'";
				echo $krresult[$i]['region'];
				echo "'";
			}
	  ?>
  ],
  datasets: [
    {
      label: 'city',
      data: [
	 <?
			for($i = 0; $i < count($krresult); $i++){		
				if($i > 0){ echo ","; }
				echo $krresult[$i]['cnt'];
			}
	  ?>
	  ],
      backgroundColor: bgColor,
	  borderWidth : 1,
      
    }
  ]
};


const config2 = {
  type: 'doughnut',
  data: data2,
  options: {
    responsive: true,
    plugins: {
      legend: {
        position: 'top',
      },
      title: {
        display: true,
        text: '<?=$_REQUEST[scode] == "" ? "국내 " : ""?>접속 도시 TOP8'
      }
    }
  },
};


var ctx2 = document.getElementById('myChart2');
var myChart2 = new Chart(ctx2, config2);
</script>
<script>


$(function(){
	setTimeout(function(){
		
		init();
		
	}, 1500);

});

var map;

function init(){
	


		var map_center = new google.maps.LatLng(37.6520491,126.8741968);
		var mapOptions = {
			center : map_center,
			zoom : 3,
			zoomControl : true,
			minZoom : 3,
			scrollwheel : true,
			gestureHandling : 'greedy',
			mapTypeControl : false
		};
		
		
		//var markers = [];
		map = new google.maps.Map(document.getElementById('map'), mapOptions);
		var markers = [];
		geocoder =  new google.maps.Geocoder();

		var param = '<?=http_build_query($_REQUEST)?>';

		$.getJSON('getLoc.php?'+param,function(res){
		
			$.each(res,function(key, val){
				
				var t = this;
				var latlng = new google.maps.LatLng(t.lat,t.lng);
				var marker = new google.maps.Marker({
					position : latlng,
					//icon : '/upload/contents/'+t.imagename,
					map : map,
					title : t.title
				});

				markers.push(marker);
					
					/*
				google.maps.event.addListener(marker,'click',function(){
					if (t.city_no == '0') {
						window.location.href="/contents/country.php?scontinent_no="+t.continent_no+"&scountry_no="+t.country_no+"&sgbdefault=1";
					} else {
						window.location.href="/post/index.php?scontinent_no="+t.continent_no+"&scountry_no="+t.country_no+"&scity_no="+t.city_no+"&sgbdefault=1";
					}
				});
					*/
				
				
				
			});

			var styles = [
				{
					width : 50, 
					height : 50,
					backgroundPosition : 'center, center',
					//url : '/img/cluster/1.png'
					url : "https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m1.png"
				},
				{
					width : 50, 
					height : 50,
					backgroundPosition : 'center, center',
					//url : '/img/cluster/2.png'
					url : "https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m2.png"
				},{
					width : 50, 
					height : 50,
					backgroundPosition : 'center, center',
					//url : '/img/cluster/3.png'
					url : "https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m3.png"
				}	
			];
			
			var markerCluster = new MarkerClusterer(map, markers,
            {	
				gridSize : 20,
				styles : styles
				
			});

			
		});
		
		


	}
</script>
<!-- e:warp --> 
<? include $_SERVER['DOCUMENT_ROOT']."/admin/include/footer.php"; ?>

</body>
</html>
