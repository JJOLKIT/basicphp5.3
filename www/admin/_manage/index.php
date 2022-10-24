<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/common.php"; ?>
<?
include $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";

$host = SSL_USE ? "https://".$_SERVER["HTTP_HOST"] : "http://".$_SERVER["HTTP_HOST"];
if ($host !== COMPANY_URL) {
	header('Location: '.COMPANY_URL.'/admin');
}

$loginCheck = false;

if (isset($_SESSION['admin_id'])) {
	$loginCheck = true;
}

if (!$loginCheck) {
	$url = $_SESSION['url'];
	if (!$url) $url = START_PAGE;
	$param = $_SESSION['param'];
?>
<!doctype html>
<html lang="ko">
<head>
<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/headHtml.php"; ?>
<script>
function loginCheck(){
	if ( getObject("id").value.length < 1 ) {
		alert("아이디를 입력해주세요.");
		getObject("id").focus();
		return false;
	}
	if ( getObject("password").value.length < 1 ) {
		alert("비밀번호를 입력해주세요.");
		getObject("password").focus();
		return false;
	}
	var f = document.board;
	if (f.reg.checked==true) {
	   document.cookie = "cookie_userid=" + f.id.value + ";path=/;expires=Sat, 31 Dec 2050 23:59:59 GMT;";
	} else {
	   var now = new Date();	
	   document.cookie = "cookie_userid=" + f.id.value + ";path=/;expires="+now.getTime();
	}
	return true;
}

function userid_chk() {
	var f=document.board;
	var useridname = CookieVal("cookie_userid");
	
	if (useridname=="null"){	
		f.id.focus();
		f.id.value="";
	} else {
		f.password.focus();
		f.id.value=useridname;
		f.reg.checked=true;
	}
}

function CookieVal(cookieName) {
	thisCookie = document.cookie.split("; ");
	for (var i = 0; i < thisCookie.length;i++) {
		if (cookieName == thisCookie[i].split("=")[0]) {
			return thisCookie[i].split("=")[1];
		}
	}
	return "null" ;
}
//-->

</script>
</head>

<body onload="$('#id').focus();">
<div id="warp">
	<h1><img src="/admin/img/logo.png" alt="" /></h1>
	<div class="login">
		<form name="board" id="board" method="post" action="/admin/include/login.php" onsubmit="return loginCheck();">
		<fieldset class="login_form">
			<legend class="blind">로그인</legend>
				<dl>
					<dt><label for="id">아이디</label></dt>
					<dd><input type="text" id="id" name="id" value="" /></dd>
				</dl>
				<dl>
					<dt><label for="pw">비밀번호</label></dt>
					<dd><input type="password" id="password" name="password" value="" /></dd>
				</dl>
				<div class="id_save">
					<input type="checkbox" value="" name="reg"/> 아이디 저장
				</div>
				<div class="login_btn">
					<input type="submit" title="로그인" alt="로그인" value="로그인" class=""/>
				</div>
		</fieldset>
		<input type="hidden" name="url" id="url" value=""/>
		<input type="hidden" name="param" id="param" value=""/>
		</form>
	</div>
	<div class="footer">
		<p>Copyright 2017 <a href="<?=COMPANY_URL?>" target="_blank"><?=COMPANY_URL?></a>. All Rights Reserved.</p>
	</div>
</div>
</body>
</html>
<?
} else {
	echo "<script>location.href='".START_PAGE."';</script>";
}
?>