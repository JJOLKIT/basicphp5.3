<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/common.php"; ?>
<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/environment/Admin.class.php";

include $_SERVER['DOCUMENT_ROOT']."/admin/include/loginCheck.php";

$admin = new Admin(10, "admin", $_REQUEST);
$rowPageCount = $admin->getCountLoginHistory($_REQUEST);
$result = $admin->getLoginHistoryList($_REQUEST);
$pageTitle = "관리자 접속이력";
?>
<!doctype html>
<html lang="ko">
<head>
<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/headHtml.php"; ?>
</head>
<body>
<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/header.php"; ?>
<!-- s:warp -->
	<div class="contWrap">
		<div class="titWrap">
			<h2>관리자 접속이력</h2>
		</div>
		<div class="searchWrap">
			<form name="searchForm" id="searchForm" action="index.php" method="get">
				<table class="searchTable">
					<colgroup>
						<col width="10%" />
						<col width="*" />
					</colgroup>
					<tbody>
						<tr>
							<th>검색어</th>
							<td>								
								<select name="stype" class="select_drop">
									<option value="all" <?=getSelected($_GET['stype'], "all") ?>>전체</option>
									<option value="id" <?=getSelected($_GET['stype'], "id") ?>>아이디</option>
									<option value="name" <?=getSelected($_GET['type'], "name") ?>>이름</option>
									<option value="ip" <?=getSelected($_GET['stype'], "ip") ?>>아이피</option>
								</select>								
								<input type="text" name="sval" value="<?=$_GET['sval']?>" />
								<input type="submit" value="검색" class="btn_search hoverbg" />
							</td>
						</tr>
					</tbody>
				</table>
			</form>
		</div>
		<!-- //search_warp -->
		<div class="list">
			<p class="list_tit">전체 <strong><?=$rowPageCount[0]?></strong>명 [<?=$admin->reqPageNo?>/<?=$rowPageCount[1]?>페이지]</p>
			<form name="frm" id="frm" action="process.php" method="post">
			<table>
				<caption> 회원목록 </caption>
					<colgroup>
						<col width="10%" />
						<col width="15%" />
						<col width="20%" />
						<col width="20%" />
						<col width="*" />
					</colgroup>
				<thead>
					<tr>
						<th scope="col">번호</th>
						<th scope="col">아이디</th>
						<th scope="col">이름</th>
						<th scope="col">아이피</th>
						<th scope="col">접속일</th>
					</tr>
				</thead>
				<tbody>
				<? if ($rowPageCount[0] == 0) { ?>
					<tr>
						<td class="cp" colspan="6">등록된 접속이력이 없습니다.</td>
					</tr>
				<?
					 } else {
						$targetUrl = "";
						while ($row = mysql_fetch_array($result)) {
							//$targetUrl = "onclick=\"location.href='".$admin->getQueryString('view.php', $row['no'], $_REQUEST)."'\" ";
				?>
					<tr class="cp" <?=$targetUrl ?>>
						<td><?=$row['no']?></td>
						<td class="txt_l"><?=$row['id']?></td>
						<td><?=$row['name']?></td>
						<td class="txt_l"><?=$row['ip']?></td>
						<td class="txt_l"><?=$row['logindate']?></td>
					</tr>
				<?
						}
					 }
				?>
				</tbody>
			</table>
			<input type="hidden" name="cmd" id="cmd" value="groupDelete"/>
			<input type="hidden" name="sgrade" id="sgrade" value="<?=$_GET['sgrade']?>>"/>
			<input type="hidden" name="stype" id="stype" value="<?=$_GET['stype']?>"/>
			<input type="hidden" name="sval" id="sval" value="<?=$_GET['sval']?>"/>
			</form>
		</div>
		<!-- //list -->
		<div class="pagenate">
			<?=pageList($admin->reqPageNo, $rowPageCount[1], $admin->getQueryString('index.php', 0, $_REQUEST))?>
		</div>
		<!-- //pagenate -->
	</div>
	<!-- //contents -->
</div>
<!-- e:warp --> 
<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/footer.php"; ?>
</body>
</html>
