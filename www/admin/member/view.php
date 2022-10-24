<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/common.php"; ?>
<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

include_once $_SERVER['DOCUMENT_ROOT']."/lib/member/Member.class.php";

include $_SERVER['DOCUMENT_ROOT']."/admin/include/loginCheck.php";
include "config.php";

$member = new Member($pageRows, "member", $_REQUEST);
$data = $member->getData($_REQUEST['no']);

?>
<!doctype html>
<html lang="ko">
<head>
<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/headHtml.php"; ?>
<script>
function goDelete(){
	sure = confirm("삭제하시겠습니까?");
	if (sure) { $("#frm").submit(); }
	else { return false; }
}

function secession(){
	sure = confirm("회원을 탈퇴시키시겠습니까?");
	if (sure) { 
		$("#cmd").val("secession");
		$("#frm").submit();
	}
	else { return false; }
}

function openPoint() {
	window.open("pointList.php?smember_fk=<?=$data['no'] ?>", "point", "width=700, height=600, scrollbars=1, toolbar=0, location=0, status=0, menubar=0");
}
</script>
</head>


<body>
<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/header.php"; ?>
<!-- s:warp -->
	<div class="contWrap">
		<div class="titWrap">
			<h2>회원정보</h2>
		</div>
		<div class="write">
			<div class="wr_box">
				<h3>개인정보</h3>
				<table class="row_line">
					<colgroup>
						<col width="8%">
						<col width="42%">
						<col width="8%">
						<col width="42%">
					</colgroup>
					<tbody>
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
						<td><?=$data['cell'] ?></td>
					</tr>
					<tr>
						<th>주소</th>
						<td colspan="3" class="inbr">
							<?=$data['zipcode'] ?> <?=$data['addr0'] ?> <?=$data['addr1'] ?>
						</td>
					</tr>
					<tr>
						<th>회원상태</th>
						<td><?=getMemberStateTypeName($data['secession'])?></td>
						<th>가입일</th>
						<td><?=$data['registdate'] ?>
					</tr>
					<tr>
						<th>포인트</th>
						<td colspan="3">0원 <a href="javascript:;" onclick="window.open('pointList.php?smember_fk=76', '_blank', 'width=800, height=800, scrollbar=yes');" style="display:inline-block; padding:5px; border:1px solid #ababab; ">포인트 내역</a></td>
					</tr>
					</tbody>
				</table>
			</div>
		</div>
		<!-- //write -->
		<div class="btnSet clear">
			<span class="left">
				<a href="<?=$member->getQueryString('index.php', 0, $_REQUEST) ?>" class="btn list hoverbg">
					<span class="material-icons">reorder</span>목록
				</a>
			</span>
			<span class="right">
			<? if($data['secession'] == 1) { ?>
				<a href="javascript:;" class="btn" onclick="return secession();">탈퇴</a>
			<? } ?>
				<a href="<?=$member->getQueryString('edit.php', $data['no'], $_REQUEST) ?>" class="btn hoverbg">수정</a>
				<a href="javascript:;" class="btn hoverbg" onclick="goDelete();">삭제</a>
			</span>
		</div>
		<!-- //btnSet -->
		<form id="frm" name="frm" method="post" action="<?=getSslCheckUrl($_SERVER['REQUEST_URI'], 'process.php')?>" onsubmit="return">
			<fieldset>
			<input type="hidden" name="stype" id="stype" value="<?=$_REQUEST['stype']?>"/>
			<input type="hidden" name="sval" id="sval" value="<?=$_REQUEST['sval']?>"/>
			<input type="hidden" name="ssecession" id="ssecession" value="<?=$_REQUEST['ssecession']?>"/>
			<input type="hidden" name="sdatetype" id="sdatetype" value="<?=$_REQUEST['sdatetype']?>"/>
			<input type="hidden" name="sstartdate" id="sstartdate" value="<?=$_REQUEST['sstartdate']?>"/>
			<input type="hidden" name="senddate" id="senddate" value="<?=$_REQUEST['senddate']?>"/>
			<input type="hidden" name="no" id="no" value="<?=$data['no']?>"/>
			<input type="hidden" name="registdate" id="registdate" value="<?=$data['registdate']?>"/>
			<input type="hidden" name="name" id="name" value="<?=$data['name']?>"/>
			<input type="hidden" name="id" id="id" value="<?=$data['id']?>"/>
			<input type="hidden" name="cmd" id="cmd" value="groupDelete"/>
			</fieldset>
		</form>
	</div>
	<!-- //contents -->
</div>
<!-- e:warp --> 
<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/footer.php"; ?>
</body>
</html>
