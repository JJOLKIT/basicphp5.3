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
$result = $business->getConSearchList($_REQUEST);
$pageTitle = "키워드 내역";

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
	initCal({id:"sstartdate",type:"day",today:"y"});			
	initCal({id:"senddate",type:"day",today:"y"});
	
	$("input[type=text][name*=sval]").keypress(function(e){
		if(e.keyCode == 13){
			goSearch();
		}
	});
	
});
</script>

</head>

<body>
<? include $_SERVER['DOCUMENT_ROOT']."/admin/include/header.php"; ?>
<!-- s:warp -->
	<div class="contWrap">
		<div class="titWrap">
			<h2>키워드 통계</h2>
		</div>
		<div class="list">
			<p class="list_tit">상위 500 키워드</p>
			<form>
				<table>
					<caption> 목록 </caption>
						<colgroup>
							<col width="50px" />

							<col width="*" />
							<col width="20%"/>
						</colgroup>
					<thead>
						<tr>
							<th scope="col">번호</th>
							<th scope="col">키워드</th>
							<th scope="col">검색횟수</th>
						</tr>
					</thead>
					<tbody>
					<?
							$i = 1;
							while ($row=mysql_fetch_assoc($result)) {
					?>
						<tr>
							<td><?=$i?></td>
							<td class="txt_l"><?=$row['con_search'] == "" ? "링크(기타, 직접, 잡코리아, 굿웹 등)" : $row['con_search'] ?></td>
							<td><?=$row['cnt']?></td>
						</tr>
					<?
							$i++;
							}
						 
					?>
					</tbody>
				</table>
			</form>
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
