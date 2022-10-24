<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/common.php"; ?>
<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/environment/Admin.class.php";

include $_SERVER['DOCUMENT_ROOT']."/admin/include/loginCheck.php";
include "config.php";

$admin = new Admin($pageRows, "admin", $_REQUEST);
$rowPageCount = $admin->getCount($_REQUEST);
$result = $admin->getList($_REQUEST);
?>
<!doctype html>
<html lang="ko">
<head>
<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/headHtml.php"; ?>
<script>
function groupDelete() {	
	if ( isSeleted(document.frm.no) ){
		if (confirm("삭제하시겠습니까?")) {
			document.frm.submit();
		}
	} else {
		alert("삭제할 항목을 하나 이상 선택해 주세요.");
	}
}
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
									<option value="memo" <?=getSelected($_GET['stype'], "memo") ?>>메모</option>
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
						<col width="50px" />
						<col width="5%" />
						<col width="15%" />
						<col width="20%" />
						<col width="10%" />
						<col width="20%" />
						<col width="*" />
					</colgroup>
				<thead>
					<tr>
						<th scope="col"><input type="checkbox" name="allChk" id="allChk" onClick="check(this, document.frm.no)"/></th>
						<th scope="col">번호</th>
						<th scope="col">아이디</th>
						<th scope="col">이름</th>
						<th scope="col">연락처</th>
						<th scope="col">이메일</th>
						<th scope="col">등록일</th>
					</tr>
				</thead>
				<tbody>
				<? if ($rowPageCount[0] == 0) { ?>
					<tr>
						<td class="cp" colspan="7">등록된 관리자가 없습니다.</td>
					</tr>
				<?
					 } else {
						$targetUrl = "";
						while ($row = mysql_fetch_array($result)) {
							$row = escape_html($row);
							$targetUrl = "onclick=\"location.href='".$admin->getQueryString('view.php', $row['no'], $_REQUEST)."'\" ";
				?>
					<tr class="cp">
						<td><input type="checkbox" name="no[]" id="no" value="<?=$row['no']?>" /></td>
						<td <?=$targetUrl ?>><?=$row['no']?></td>
						<td <?=$targetUrl ?>><?=$row['id']?></td>
						<td <?=$targetUrl ?>><?=$row['name']?></td>
						<td <?=$targetUrl ?>><?=$row['tel']?></td>
						<td <?=$targetUrl ?>><?=$row['email']?></td>
						<td <?=$targetUrl ?>><?=$row['registdate']?></td>
					</tr>
				<?
						}
					 }
				?>
				</tbody>
			</table>
			<input type="hidden" name="cmd" id="cmd" value="groupDelete"/>
			<input type="hidden" name="sgrade" id="sgrade" value="<?=$_GET['sgrade']?>"/>
			<input type="hidden" name="stype" id="stype" value="<?=$_GET['stype']?>"/>
			<input type="hidden" name="sval" id="sval" value="<?=$_GET['sval']?>"/>
			</form>
		</div>
		<!-- //list -->
		<div class="btnSet clear">
			<div class="sBtn left">
				<input type="button" value="삭제" onclick="groupDelete();"/>
			</div>
			<div class="right">
			<a href="write.php" class="btn edit hoverbg">
				<span class="material-icons">edit</span>관리자등록
			</a>
		</div>
		</div>
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
