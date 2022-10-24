<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/common.php"; ?>
<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

include $_SERVER['DOCUMENT_ROOT']."/admin/include/loginCheck.php";
$pageTitle			= "대시보드";

include_once $_SERVER['DOCUMENT_ROOT']."/lib/weblog/Weblog.class.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/Fb.class.php";

$fbreq['sgubun'] = "board";
$fbreq['sadmin_fk'] = $_SESSION['admin_no'];
$dashfb = new Fb(10, 'admin_fb', $fbreq);

$dashfbcount = $dashfb->getCount($fbreq);
$dashfbresult = rstToArray($dashfb->getDashList($fbreq));


$weblog = new Weblog(1, array());
$dashlogData = $weblog->getDashData();

$today = Date('Y-m-d');


?>
<!doctype html>
<html lang="ko">
<head>
<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/headHtml.php"; ?>
</head>
<body>
<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/header.php"; ?>

<div class="contWrap dashboard">
	<div class="titWrap">
		<h2><?=$pageTitle ?></h2>
	</div>
	

	<?
		//기본정보
		if( $HostingInfo ){
	?>
	<div class="hostingWrap">
		<div class="list dash_list">
			<h3>기본 정보</h3>
			<form>
				<table>
					<colgroup>
						<col width="10%"/>
						<col width="40%"/>
						<col width="10%"/>
						<col width="40%"/>
					</colgroup>
					<tbody>
						<tr>
							<th>도메인</th>
							<td colspan="3"><a href="<?=COMPANY_URL?>" target="_blank"><?=COMPANY_URL?></a></td>
						</tr>
						<tr>
							<th>호스팅</th>
							<td>	
								<? 

								$firstDay = HOSTING_SDT;
								$thisYear = Date('Y');

								$diffY = $thisYear - Date('Y', strtotime($firstDay));

				
								$e =  Date("Y-m-d", strtotime(HOSTING_SDT." +".$diffY." years -1 days"));
								$ecountday = floor( (strtotime($e) - strtotime($today)) / (24*60*60) );

								if($ecountday <= 0){
									$diffY ++;
									$e =  Date("Y-m-d", strtotime(HOSTING_SDT." +".$diffY." years -1 days"));
									$ecountday = floor( (strtotime($e) - strtotime($today)) / (24*60*60) );
									
								}

									
								?>
								시작일 : <?=HOSTING_SDT?> / 만료일 : <b><?=$e?></b>
								<?
									//15일 처리
									$enddt = strtotime($e." -15 days");
									if( $enddt <= strtotime($today) ) {
										if($ecountday > 0){
								?>
								<em>(만료일까지 <?=$ecountday?>일 남았습니다.)</em>
										<?}else if($ecountday == 0){	?>
								<em>(오늘 만료)</em>
										<?}else if($ecountday < 0){?>
								<em>(만료되었습니다.)</em>
										<?}?>
								<?
									}
								?>

							</td>
							<th>SSL</th>
							<td>
								<?if(SSL_USE){?>
								
								<?

									$sfirstDay = SSL_SDT;
									$sthisYear = Date('Y');

									$sdiffY = $sthisYear - Date('Y', strtotime($sfirstDay));
									$se = Date("Y-m-d", strtotime(SSL_SDT." +".$sdiffY." years -1 days"));
									$ecountday = floor(strtotime($se) - strtotime($today)) / (24*60*60);

									if($ecountday <= 0){
										$sdiffY ++;
										$se = Date("Y-m-d", strtotime(SSL_SDT." +".$sdiffY." years -1 days"));
										$ecountday = floor(strtotime($se) - strtotime($today)) / (24*60*60);
									}


								?>
								시작일 : <?=SSL_SDT?> / <b>만료일 <?=$se?></b>

								<?
								//15일 처리
									$senddt = strtotime($se." -15 days");
									if( $senddt <= strtotime($today) ){
										
								
										if($ecountday > 0){
								?>
								<em>(만료일까지 <?=$ecountday?>일 남았습니다.)</em>
										<?}else if($ecountday == 0){?>
								<em>(오늘 만료)</em>
										<?}else if($ecountday < 0){?>
								<em>(만료되었습니다.)</em>
										<?}?>
								<?
									}
								?>

								<?}else{?>
								-
								<?}?>

							</td>
						</tr>
					</tbody>
				</table>
			</form>
		</div>
	</div>
	<?}?>


	<div class="weblogWrap">
		<div class="list dash_list">
			<h3>방문자 현황</h3>
			<form>
				<table>
					<colgroup>
						<col width="10%"/>
						<col width="40%"/>
						<col width="10%"/>
						<col width="40%"/>
					</colgroup>
					<tbody>
						<tr>
							<th>당일</th>
							<td><?=number_format($dashlogData['cnt1'])?></td>
							<th>전일</th>
							<td><?=number_format($dashlogData['cnt2'])?></td>
						</tr>
						<tr>
							<th>당월</th>
							<td><?=number_format($dashlogData['cnt3'])?></td>
							<th>전월</th>
							<td><?=number_format($dashlogData['cnt4'])?></td>
						</tr>
						<tr>
							<th>총계</th>
							<td colspan="3"><?=number_format($dashlogData['cnt5'])?></td>
						</tr>
					</tbody>
				</table>
			</form>
		</div>
	</div>

	<?if( $dashfbcount[0] > 0 ){?>
	<div class="boardWrap">
		<h3>최근게시글 <span>즐겨찾기에 등록된 게시판 4개까지 노출됩니다</span></h3>
		<!-- 즐겨찾기 게시판 -->
		<div class="clear listWrap">
			<?
				for($ft = 0; $ft < count($dashfbresult); $ft++){
					$fbbreq['tablename'] = $dashfbresult[$ft]['tablename'];
					$fbbrowPageCount = $dashfb->getDashBoardCount($fbbreq);
					$fbbresult = $dashfb->getDashBoardList($fbbreq);
			?>
			<div class="list">
				<div class="list_tit">
					<h4 class="color"><?=$dashfbresult[$ft]['name']?><!--<span class="material-icons star">star</span>--></h4>
					<a href="<?=$dashfbresult[$ft]['relation_url']?>">
						<span class="material-icons">add_circle_outline</span>
					</a>
				</div>				
				<form>
					<table>
						<caption> 목록 </caption>
							<colgroup>
								<col width="*" />
								<col width="10%" />
								<col width="20%" />
							</colgroup>
						<!--<thead>
							<tr>
								<th scope="col">제목</th>
								<th scope="col">작성자</th>
								<th scope="col">작성일</th>
							</tr>
						</thead>-->
						<tbody>
						<? if ($fbbrowPageCount == 0) { ?>
							<tr>
								<td colspan="6" align="center">등록된 데이터가 없습니다.</td>
							</tr>
						<?
							 } else {
								$targetUrl = "";
								$topClass = "";
								$i = 0;

								//while ($row=mysql_fetch_assoc($fbbresult)) {
								while ($row=mysql_fetch_assoc($fbbresult)) {

									if( strpos($fbbreq['tablename'], "formmail") !== false ){
										$targetUrl = "style=\"cursor:pointer;\" onclick=\"location.href='".$dashfbresult[$ft]['relation_url']."answer.php?no=".$row['no']."'\"";

									}else{
										$targetUrl = "style=\"cursor:pointer;\" onclick=\"location.href='".$dashfbresult[$ft]['relation_url']."view.php?no=".$row['no']."'\"";
									}

									if ($row[top] == '1') {
										$topClass = "class='topBg'";
									} else {
										$topClass = "";
									}
						?>
							<tr <?=$topClass?>>
								<td class="txt_l" <?=$targetUrl ?>>
								<?=$row[title]?>
		
								<? if (checkNewIcon($row['registdate'], $row['newicon'], 1)) { ?>
									<img src="/img/ico_new.png" alt="새글" />
								<? } ?>
								</td>
								<td <?=$targetUrl ?>><?=$row[name]?></td>
								<td <?=$targetUrl ?>><?=getYMD($row[registdate])?></td>
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
			<?}?>
		</div>
	</div>
	<?}?>

</div>
<!-- //contWrap -->
</div>
<!-- //wrapeer --> 
<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/footer.php"; ?>
</body>
</html>
