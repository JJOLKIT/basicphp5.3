<? include_once $_SERVER['DOCUMENT_ROOT']."/include/common.php"; ?>
<?
     include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
     include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
     include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";


     $url = $_REQUEST[url] == '' ? LOGIN_AFTER_PAGE : $_REQUEST[url];
     $param = $_REQUEST[param];
?>
<?	
	$p = "";
	$sp = 0;
	$spc= 0;
	$root = $_SERVER['DOCUMENT_ROOT'];
	include_once $root."/header.php";
?>
<SCRIPT type="text/javascript">
$(document).ready(function(e){

	$("#id").focus();
    
	$("#password").bind("keydown", function(e) {
		if (e.keyCode == 13) { // enter key
			$("#board").submit();
			return false
		}
	});
});

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
	   document.cookie = "cookie_userid=null;path=/;expires="+now.getTime();
	}

	return true;
}

function userid_chk() {
	var f=document.board;
	var useridname = CookieVal("cookie_userid");
	console.log("useridname"+useridname);
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

$(function(){
	userid_chk();
});
</SCRIPT>
<div id="sub" class="">
	<div class="size">
		<!-- 여기서부터 게시판--->
		<div class="bbs">
               <form name="board" id="board" method="post" action="/include/login.php" onsubmit="return loginCheck();">
                    <fieldset class="login_form">
                         <input type="hidden" name="url" id="url" value="<?=$url?>"/>
                         <input type="hidden" name="param" id="param" value="<?=$param?>"/>
                         <div class="login_txt">
                              <h3>Login</h3>
                              <p>
                                   로그인을 하시면 다양한 서비스를 이용하실 수 있습니다.<br/>
                                   아직 회원이 아니신 분은 회원가입 후 이용해주세요.
                              </p>
                         </div>
                         <div class="login_box">
                              <p class="id">
                                   <input type="text" name="id" id="id" placeholder="ID"/>
                              </p>
                              <p class="password">
                                   <input type="password" name="password" id="password" placeholder="Password"/>
                              </p>
                         </div>
                         <div class="check_box">
                              <input type="checkbox" name="reg" id="reg"/>
                              <label for="reg">아이디 저장</label>
                         </div>
                         <div class="login_btn">
                              <input type="submit" value="로그인" class="btn"/>
                         </div>
                         <div class="login_util clear">
                              <a href="write.php" class="join">회원가입</a>
                              <div>
                                   <a href="idpwsearch.php?init=id">아이디 찾기</a>
                                   <a href="idpwsearch.php?init=pw">비밀번호 찾기</a>
                              </div>
                         </div>
                    </fieldset>
               </form>
          </div>
		<!-- //여기까지 게시판--->
	</div>
	<!-- //size--->
</div>
<!-- //sub--->
<?
	include_once $root."/footer.php";
?>
