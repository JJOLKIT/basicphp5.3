<? include_once $_SERVER['DOCUMENT_ROOT']."/include/common.php"; ?>
<?
     include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
     include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
     include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
     include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
     include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

     include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/Formmail.class.php";

     include "config.php";

     $notice = new Formmail($pageRows, $tablename, $_REQUEST);
?>
<?	
	$p = "";
	$sp = 0;
	$spc= 0;
	$root = $_SERVER['DOCUMENT_ROOT'];
	include_once $root."/header.php";
?>
<script type="text/javascript">
var oEditors; // 에디터 객체 담을 곳
	$(window).load(function(){ 
		oEditors = setEditor("contents"); // 에디터 셋팅
	});

function goSave(frm) {

	if(frm.agree_yn.checked == false){
		alert('개인정보 수집방침에 동의해 주세요.');
		frm.agree_yn.focus();
		return false;
	}
	if(frm.company.value.trim() == ""){
		alert('업체명을 입력해 주세요.');
		frm.company.focus();
		return false;
	}
	if(frm.name.value.trim() == ""){
		alert('담당자명을 입력해 주세요.');
		frm.name.focus();
		return false;
	}
	if(frm.cell.value.trim() == ""){
		alert('연락처를 입력해 주세요.');
		frm.cell.focus();
		return false;
	}
	if(frm.email1.value.trim() == ""){
		alert('이메일을 입력해 주세요.');
		frm.email1.focus();
		return false;
	}
	if(frm.email2.value.trim() == ""){
		alert('이메일을 입력해 주세요.');
		frm.email2.focus();
		return false;
	}

	frm.email.value = frm.email1.value.trim() + '@' + frm.email2.value.trim();



	if (frm.email.value.trim() != "") {
		if(!isValidEmail(getObject("email"))) {
			alert("잘못된 이메일 형식입니다.\\n올바로 입력해 주세요.\\n ex)abcdef@naver.com");
			frm.email.focus();
			return false;
		}
	}

	var sHTML = oEditors.getById["contents"].getIR();
	if (sHTML.replace("<p><br></p>","") == "") {
		alert('내용을 입력해 주세요.');
		frm.contents.focus();
		return false;
	} else {
		oEditors.getById["contents"].exec("UPDATE_CONTENTS_FIELD", []);	// 에디터의 내용이 textarea에 적용됩니다.
	}
	

	if(frm.zsfCode.value.trim() == ""){
		alert('스팸방지코드를 입력해 주세요.');
		frm.zsfCode.focus();
		return false;
	}

	return true;
}
</script>
<div id="sub" class="">
	<div class="size">
		<!-- 여기서부터 게시판--->
          <div class="bbs">
               <form name="frm" id="frm" action="<?=getSslCheckUrl($_SERVER['REQUEST_URI'], 'process.php')?>" method="post" onsubmit="return goSave(this);">
                    <div class="agree">
                         <div class="agree_con"> 
                             <strong>개인정보수집방침</strong>
                             <? include_once $_SERVER['DOCUMENT_ROOT']."/member/policy.html"; ?>
                         </div>
                         <p class="check_box">
                             <input type="checkbox" name="agree_yn" id="agree_yn"/>
                             <label for="agree_yn"> 개인정보수집방침에 동의합니다.</label>
                         </p>
                    </div>
                    <!-- //agree--->
                    <table class="write">
                         <caption>글쓰기</caption>
                         <colgroup>
                         <col width="15%" />
                         <col width="*" />
                         <col width="15%" />
                         <col width="*" />
                         </colgroup>
                         <tbody>
                              <tr>
                                   <th>업체명 <span class="required">*<span>필수입력</span></span></th>
                                   <td><input type="text" name="company" id="company" class="max300"></td>
                                   <th>담당자 <span class="required">*<span>필수입력</span></span></th>
                                   <td><input type="text" name="name" id="name" class="max300"></td>
                              </tr>
                              <tr>
                                   <th>부서 <span class="required">*<span>필수입력</span></span></th>
                                   <td><input type="text" name="department" id="department" class="max300"></td>
                                   <th>지역</th>
                                   <td><input type="text" name="area" id="area" class="max300"></td>
                              </tr>
                              <tr>
                                   <th>연락처 <span class="required">*<span>필수입력</span></span></th>
                                   <td colspan="3"><input type="text" class="max300" name="cell" id="cell" value="" maxlength="15" onkeyup="isNumberOrHyphen(this);cvtPhoneNumber(this);"></td>
                              </tr>
                              <tr>
                                   <th>이메일 <span class="required">*<span>필수입력</span></span></th>
                                   <td colspan="3" class="email">
                                        <input type="text" name="email1" id="email1" value="" class="max200" autocomplete="off"/>
                                        <span class="at">@</span>
                                        <input type="text" name="email2" id="email2" value="" class="max200" autocomplete="off"/>
                                        <span class="select max200">
                                             <select onchange="document.getElementById('email2').value = this.value;">
												<option value="">직접입력</option>
												<option value="naver.com">naver.com</option>
												<option value="gmail.com">gmail.com</option>
												<option value="nate.com">nate.com</option>
												<option value="daum.net">daum.net</option>
					                    </select>
                                        </span>
                                   </td>
                              </tr>
                              <tr class="txtarea">
                                   <th>문의내용 <span class="required">*<span>필수입력</span></span></th>
                                   <td colspan="3"><textarea name="contents" id="contents" ></textarea></td>
                              </tr>
                              <tr>
                                   <th>스팸방지코드 <span class="required">*<span>필수입력</span></span></th>
                                   <td colspan="3">
                                        <div class="spam">
                                             <img src="/include/captcha.php?date=<?echo date('h:i:s')?>" id="capt_img">
                                             <input type="button" value="새로고침" onclick="refresh_captcha();"  class="spam_refresh"/>
                                             <input type="text" id="zsfCode" name="zsfCode" class="max200" maxlength='6' onkeyup='' autocomplete="off">
                                        </div>
                                   </td>
                              </tr>
                         </tbody>
                    </table>
                    <!-- //write--->
                    <div class="btnSet clear">
                         <div><input type="submit" value="저장" class="btn"/> <a href="javascript:;" class="btn cancel">취소</a></div>
                    </div>
                    <input type="hidden" name="cmd" value="write"/>
                     <input type="hidden" name="email" value="" id="email" readonly/>
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

				