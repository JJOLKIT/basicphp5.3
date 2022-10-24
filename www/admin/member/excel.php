<?
session_start();
header("Content-Type: text/html; charset=UTF-8");

include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

include_once $_SERVER['DOCUMENT_ROOT']."/lib/member/Member.class.php";

include $_SERVER['DOCUMENT_ROOT']."/admin/include/loginCheck.php";

$pageRows = 99999;
$member = new Member($pageRows, "member", $_REQUEST);
$rowPageCount = $member->getCount($_REQUEST);
$result = $member->getList($_REQUEST);

$today = getToday();
$oneWeek = getDayDateAdd(-7, $today);
$oneMonth = getMonthDateAdd(-1, $today);
$twoMonth = getMonthDateAdd(-2, $today);
$threeMonth = getMonthDateAdd(-3, $today);

header( "Content-type: application/vnd.ms-excel; charset=utf-8");
header( "Content-Disposition: attachment; filename = member.xls" );
header( "Content-Description: PHP4 Generated Data" );
?>
<!doctype html>
<html lang="ko">
<head>
<meta charset="utf-8">
</head>
<body>
			<table border="1">
					<tr>
						<th scope="col">번호</th>
						<th scope="col">아이디</th>
						<th scope="col">병원명</th>
						<th scope="col">사업자번호</th>
						<th scope="col">이름</th>
						<th scope="col">연락처</th>
						<th scope="col">이메일</th>
						<th scope="col">주소</th>
					</tr>
				<tbody>
				<? if ($rowPageCount[0] == 0) { ?>
					<tr>
						<td colspan="9" align="center">등록된 회원이 없습니다.</td>
					</tr>
				<?
					 } else {
						$i = 0;
						while ($row=mysql_fetch_assoc($result)) { 
				?>
					<tr>
						<td><?=$rowPageCount[0] - (($member->reqPageNo-1)*$pageRows) - $i?></td>
						<td><?=$row['id'] ?></td>
						<td><?=$row['hospital_name'] ?></td>
						<td><?=$row['companynumber1']?>-<?=$row['companynumber2']?>-<?=$row['companynumber3']?></td>
						<td><?=$row['name'] ?></td>
						<td><?=$row['cell'] ?></td>
						<td><?=$row['email'] ?></td>
						<td><?=$row['addr0'] ?> <?=$row['addr1'] ?></td>
					</tr>
				<?
						$i++;
						}
					 }
				?>
				</tbody>
			</table>
</body>
</html>
