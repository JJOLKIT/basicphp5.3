<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/common.php"; ?>
<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

include_once $_SERVER['DOCUMENT_ROOT']."/lib/environment/Admin.class.php";

include $_SERVER['DOCUMENT_ROOT']."/admin/include/loginCheck.php";
include "config.php";

$admin = new Admin($pageRows, "admin", $_REQUEST);
$data = $admin->getData($_REQUEST);
?>
<!doctype html>
<html lang="ko">
<head>
<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/headHtml.php"; ?>
<script type="text/javascript">
	function goDelete() {
		var del = confirm ('삭제하시겠습니까?');
		if (del){
			document.location.href = "process.php?no=<?=$data['no']?>&cmd=delete";
		} else {
			return false;
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
		<div class="write">
			<div class="wr_box">
				<h3>관리자 기본정보</h3>
				<table class="row_line">
					<colgroup>
						<col width="8%">
						<col width="42%">
						<col width="8%">
						<col width="42%">
					</colgroup>
					<tbody>
					<tr>
						<th>권한등급</th>
						<td colspan="3">
							<?=$data['grade_name'] ?>
						</td>
					</tr>
					<tr>
						<th>아이디</th>
						<td><?=$data['id'] ?></td>
						<th>이름</th>
						<td><?=$data['name'] ?></td>
					</tr>
					<tr>
						<th>이메일</th>
						<td><?=$data['email'] ?></td>
						<th>휴대전화</th>
						<td><?=$data['tel'] ?></td>
					</tr>
					<tr>
						<th>메모</th>
						<td colspan="3" class="inbr">
							<?=$data['memo'] ?>
						</td>
					</tr>
					</tbody>
				</table>
			</div>
		</div>
		<!-- //write -->
		<div class="btnSet clear">
			<span class="left">
				<a href="index.php" class="btn hoverbg list">
					<span class="material-icons">reorder</span>목록
				</a>
			</span>
			<span class="right">
				<a href="<?=$admin->getQueryString('edit.php', $data['no'], $_REQUEST)?>" class="btn hoverbg">수정</a>
				<a href="javascript:;" class="btn hoverbg" onClick="goDelete();">삭제</a>
			</span>
		</div>
		<!-- //btnSet -->
	</div>
	<!-- //contents -->
</div>
<!-- e:warp --> 
<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/footer.php"; ?>
</body>
</html>
