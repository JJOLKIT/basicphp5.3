<?session_start();
header("Content-Type: text/html; charset=UTF-8");
unset($_SESSION['admin_no']);
unset($_SESSION['admin_id']);
unset($_SESSION['admin_name']);
unset($_SESSION['admin_grade']);
unset($_SESSION['admin_email']);

include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
?>
<script>
alert("로그아웃 되었습니다.");
document.location.href="/admin/index.php";
</script>
