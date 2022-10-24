<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/common.php"; ?>
<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

include $_SERVER['DOCUMENT_ROOT']."/admin/include/loginCheck.php";
$pageTitle			= "유지보수관리";
?>
<!doctype html>
<html lang="ko">
<head>
<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/headHtml.php"; ?>
</head>
<body>
<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/header.php"; ?>

<div class="contWrap">
	<div class="titWrap">
		<h2><?=$pageTitle ?></h2>
	</div>

</div>
<!-- //contWrap -->
</div>
<!-- //wrapeer --> 
<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/footer.php"; ?>
</body>
</html>
