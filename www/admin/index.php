<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/common.php"; ?>
<?
include $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";

$host = SSL_USE ? "https://".$_SERVER["HTTP_HOST"] : "http://".$_SERVER["HTTP_HOST"];
if ($host !== COMPANY_URL) {
	header('Location: '.COMPANY_URL.'/admin/');
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
		var h = CryptoJS.AES.encrypt(f.id.value, "<?=REFERER_URL?>");
	   document.cookie = "cookie_adminid=" + h + ";path=/;expires=Sat, 31 Dec 2050 23:59:59 GMT;";
	} else {
	   var now = new Date();	
	   document.cookie = "cookie_adminid=" + f.id.value + ";path=/;expires="+now.getTime();
	}
	return true;
}

function userid_chk() {
	var f=document.board;
	var useridname = CookieVal("cookie_adminid");
	
	if (useridname=="null"){	
		f.id.focus();
		f.id.value="";
	} else {
		f.password.focus();
		var h = CryptoJS.AES.decrypt( useridname, "<?=REFERER_URL?>" );
		h = h.toString(CryptoJS.enc.Utf8);

		f.id.value=h;
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
function labelChk(){
	if($('#id_save').is(':checked')){
		$('.id_save label').removeClass('beforebg');
	}else{
		$('.id_save label').addClass('beforebg');
	}
}
$(function(){
	userid_chk();
	
	if($('#id_save').is(':checked')){
		$('.id_save label').addClass('beforebg');
	}else{
		$('.id_save label').removeClass('beforebg');
	}
});
</script>
</head>

<body onload="$('#id').focus();">
<div id="bodyWrap" class="loginWrap">
	<div class="tb">
		<div class="tbc">
			<div class="login_tit">
				<h1><?=COMPANY_NAME?> 관리자모드</h1>
				<p>관리자 계정으로 로그인 후 이용하실 수 있습니다.</p>
			</div>
			<!--//login_tit-->
			<div class="login">
				<form name="board" id="board" method="post" action="/admin/include/login.php" onsubmit="return loginCheck();">
					<fieldset class="login_form">
						<legend class="blind">로그인</legend>
						<!--<dt><label for="id">아이디</label></dt>-->
						<p class="login_id"><span class="material-icons">person_outline</span><input type="text" id="id" name="id" placeholder="ID" /></p>
						<!--<dt><label for="pw">비밀번호</label></dt>-->
						<p class="login_pw"><span class="material-icons">lock_outline</span><input type="password" id="password" name="password" placeholder="Password" /></p>
						<div class="id_save">
							<input type="checkbox" name="reg" id="id_save"/>
							<label for="id_save" onClick="labelChk();">아이디 저장</label>
						</div>
						<div class="login_btn">
							<input type="submit" title="로그인" alt="로그인" value="LOG IN" class="bgcolor"/>
						</div>
					</fieldset>
					<input type="hidden" name="url" id="url"/>
					<input type="hidden" name="param" id="param"/>
				</form>
			</div>
			<!--//login-->
			<div class="login_footer">
				<img src="/admin/img/logo_login.png"/>
				<p>Copyright Sanggong. All rights reserved.</p>
			</div>
			<!--//footer-->
		</div>
	</div>
</div>
<!--//bodyWrap-->
</body>
</html>
<?
} else {
	echo "<script>location.href='".START_PAGE."';</script>";
}
?>