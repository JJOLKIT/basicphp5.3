<? include_once $_SERVER['DOCUMENT_ROOT']."/include/common.php"; ?>
<? include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php"; ?>
<?	
	$p = "";
	$sp = 0;
	$spc= 0;
	$root = $_SERVER['DOCUMENT_ROOT'];
	include_once $root."/header.php";
?>
<SCRIPT type="text/javascript">

function loginCheck(frm){

	if( frm.id.value.trim()==""){
		alert('아이디를 입력해주세요');
		frm.id.focus();
		return false;
	}

	if( frm.password.value.trim()=="" ){
		alert('비밀번호를 입력해주세요');
		frm.password.focus();
		return false;
	}
	return true;
}
</SCRIPT>
<div id="sub" class="">
	<div class="size">
		<!-- 여기서부터 게시판--->
          <div class="bbs">
               <form name="board" id="board" method="post" action="<?=getSslCheckUrl($_SERVER['REQUEST_URI'], 'process.php')?>" onsubmit="return loginCheck(this);">
                    <fieldset  class="login_form">
                         <input type="hidden" name="cmd" id="cmd" value="secede"/>
                         <div class="login_txt">
                              <h3>회원탈퇴</h3>
                              <p>
                                   회원탈퇴를 하시면 회원정보가 모두 삭제됩니다.<br />
                                   회원탈퇴 후 회원서비스를 이용하실 수 없습니다.
                              </p>
                         </div>
                         <div class="login_box">
                              <p class="id">
                                   <input type="text" name="id" id="id" placeholder="아이디"/>
                              </p>
                              <p class="password">
                                   <input type="password" name="password" id="password" placeholder="비밀번호"/>
                              </p>
                         </div>
                         <div class="login_btn">
                              <input type="submit" value="회원탈퇴" class="btn"/>
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
				