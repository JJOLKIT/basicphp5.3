<? include_once $_SERVER['DOCUMENT_ROOT']."/include/common.php"; ?>
<?
     include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
     include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
     include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
     include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
     include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

     include_once $_SERVER['DOCUMENT_ROOT']."/lib/member/Member.class.php";

     include "config.php";

     $member = new Member($pageRows, "member", $_REQUEST);
?>
<?	
	$p = "";
	$sp = 0;
	$spc= 0;
	$root = $_SERVER['DOCUMENT_ROOT'];
	include_once $root."/header.php";
?>
<SCRIPT type="text/javascript">

function goSave(frm) {

	
	if(!frm.agree_1.checked){
		alert('이용약관에 동의해 주세요.');
		return false;
	}

	if(!frm.agree_2.checked){
		alert('개인정보취급방침에 동의해 주세요.');
		return false;
	}
	if (frm.id.value.trim() == "") {
		alert("아이디를 입력해 주세요.");
		frm.id.focus();
		return false;
	}
	$.ajax({
		type : "POST",
		url: "/include/id_check.php",
		async: false,
		data: {
			id : frm.id.value
		},
		success: function( data ) {
			var r = data.trim();
			if (r == "false") {
				alert("중복된 아이디입니다.");
				frm.id.focus();
				$("#id_duplication").val("1");
			} else if (r == "true") {
				$("#id_duplication").val("0");
			}
		},
		error:function(e) {
			alert(e.responseText);
		}
	});
	if ($("#id_duplication").val() == "1") {
		return false;
	}

	if(!validPassword($("#password"))) return false;
	if ($("#password").val().trim() != $("#password2").val().trim()) {
		alert("비밀번호를 정확하게 입력해 주세요.");
		$("#password2").focus();
		return false;
	}
	if (frm.name.value.trim() == "") {
		alert("이름을 입력해 주세요.");
		frm.name.focus();
		return false;
	}
	if (frm.cell.value.trim() == "") {
		alert("연락처를 입력해 주세요.");
		frm.cell.focus();
		return false;
	}
	if (frm.email1.value.trim() == "") {
		alert("이메일을 입력해 주세요.");
		frm.email1.focus();
		return false;
	}
	if (frm.email2.value.trim() == ""){
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
	$.ajax({
		type : "POST",
		url: "/include/email_check.php",
		async: false,
		data: {
			email : frm.email.value.trim()
		},
		success: function( data ) {
			var r = data.trim();
			if (r == "false") {
				alert("중복된 이메일입니다.");
				frm.email.focus();
				$("#email_duplication").val("1");
			} else if (r == "true") {
				$("#email_duplication").val("0");
			}
		},
		error:function(e) {
			alert(e.responseText);
		}
	});
	if ($("#email_duplication").val() == "1") {
		return false;
	}
	/*
 	if (frm.zipcode.value.trim() == "") {
		alert("우편번호를 입력해 주세요.");
		frm.zipcode.focus();
		return false;
	}
	if (frm.addr0.value.trim() == "") {
		alert("주소를 입력해 주세요.");
		frm.addr0.focus();
		return false;
	}
	*/

	return true;
}

function checkId(){
	if ($("#id").val().trim() == "") {
		alert("아이디를 입력해 주세요.");
		$("#id").focus();
		return false;
	} else {
		$.ajax({
			type : "POST",
			url: "/include/id_check.php",
			async: false,
			data: {
				id : $("#id").val()
			},
			success: function( data ) {
				var r = data.trim();
				if (r == "false") {
					alert("중복된 아이디입니다.");
					$("#id").focus();
					$("#id_duplication").val("1");
				} else if (r == "true") {
					alert("사용가능한 아이디입니다.");
					$("#id_duplication").val("0");
				}
			},
			error:function(e) {
				alert(e.responseText);
			}
		});
	}
}

function checkEmail(){
	if($('#email1').val().trim() == ""){
		alert('이메일을 입력해 주세요.');
		$('#email1').focus();
		return false;
	}
	if($('#email2').val().trim() == ""){
		alert('이메일을 입력해 주세요.');
		$('#email2').focus();
		return false;
	}
	$('#email').val( $('#email1').val().trim() + '@' + $('#email2').val().trim() );

	if($('#email').val().trim() == ""){
		alert('이메일을 입력해 주세요.');
		return false;
	}
	 if ($("#email").val().trim() != "") {
		if(!isValidEmail(getObject("email"))) {
			alert("잘못된 이메일 형식입니다.\\n올바로 입력해 주세요.\\n ex)abcdef@naver.com");
			$("#email").focus();
			return false;
		}
	}

	$.ajax({
		type : "POST",
		url: "/include/email_check.php",
		async: false,
		data: {
			email : $("#email").val().trim()
		},
		success: function( data ) {
			var r = data.trim();
			if (r == "false") {
				alert("중복된 이메일입니다.");
				$("#email").focus();
				$("#email_duplication").val("1");
			} else if (r == "true") {
				alert("사용가능한 이메일입니다.");
				$("#email_duplication").val("0");
			}
		},
		error:function(e) {
			alert(e.responseText);
		}
	});
	


}
</SCRIPT>
<div id="sub" class="">
	<div class="size">
		<!-- 여기서부터 게시판--->
          <div class="bbs">
               <form name="board" id="board" method="post" action="process.php" onsubmit="return goSave(this);">
                    <div class="agree">
                         <div class="agree_con"> 
                              <strong>이용약관</strong>
                              <? include_once $_SERVER['DOCUMENT_ROOT']."/member/agree.html"; ?>
                         </div>
                         <p class="check_box">
                              <input type="checkbox" name="agree_1" id="agree_1" class="required" title="이용약관"/>
                              <label for="agree_1">이용약관에 동의합니다.</label>
                         </p>
                    </div>
                    <!-- //agree--->
                    <div class="agree">
                         <div class="agree_con"> 
                              <strong>개인정보수집방침</strong>
                              <? include_once $_SERVER['DOCUMENT_ROOT']."/member/policy.html"; ?>
                         </div>
                         <p class="check_box">
                              <input type="checkbox" name="agree_2" id="agree_2" class="required" title="개인정보취급방침"/>
                              <label for="agree_2"> 개인정보수집방침에 동의합니다.</label>
                         </p>
                    </div>
                    <!-- //agree--->
                    <h3>회원정보</h3>
                    <table class="write">
                         <caption>회원정보</caption>
                         <colgroup>
                              <col width="15%" />
                              <col width="*" />
                         </colgroup>
                         <tbody>
                              <tr>
                                   <th>아이디 <span class="required">*<span>필수입력</span></span></th>
                                   <td>
                                        <p class="ipt_box">
                                             <input type="text" name="id" id="id" class="max200 required" autocomplete="off" title="아이디"/>
                                             <a href="javascript:;" onclick="checkId();" class="btn intable">중복확인</a>
                                        </p>
                                   </td>
                              </tr>
                              <tr>
                                   <th>비밀번호 <span class="required">*<span>필수입력</span></span></th>
                                   <td class="password">
										<input type="password" name="password" id="password" class="max200 required" autocomplete="off" title="비밀번호"/>
                                        <p class="help">비밀번호는 숫자, 영문 조합으로 8자 이상으로 입력해주세요.</p>
                                   </td>
                              </tr>
                              <tr>
                                   <th>비밀번호 확인 <span class="required">*<span>필수입력</span></span></th>
                                   <td class="password"><input type="password" name="password2" id="password2" class="max200 required" title="비밀번호 확인" autocomplete="off"/></td>
                              </tr>
                              <tr>
                                   <th>이름 <span class="required">*<span>필수입력</span></span></th>
                                   <td><input type="text" name="name" id="name" class="max200 required"> </td>
                              </tr>
                              <tr>
                                   <th>연락처 <span class="required">*<span>필수입력</span></span></th>
                                   <td>
                                        <input type="tel" name="cell" id="cell" class="max300 required" value="" maxlength="15" onkeyup="isNumberOrHyphen(this);cvtPhoneNumber(this);">
                                   </td>
                              </tr>
                              <tr >
                                   <th>이메일 <span class="required">*<span>필수입력</span></span></th>
                                   <td class="email">
                                        <div class="checkBtn">
                                             <input type="text" name="email1" id="email1" value="" class="max200 required" title = "이메일" autocomplete="off"/>
                                             <span class="at">@</span>
                                             <input type="text" name="email2" id="email2" value="" class="max200 required" title="이메일" autocomplete="off"/>
									<span class="select max200">
                                                  <select onchange="document.getElementById('email2').value = this.value;">
												<option value="">직접입력</option>
												<option value="naver.com">naver.com</option>
												<option value="gmail.com">gmail.com</option>
												<option value="nate.com">nate.com</option>
												<option value="daum.net">daum.net</option>
											 </select>
                                             </span>
                                             <a href="javascript:;" onclick="checkEmail();" class="btn intable">중복확인</a>
                                       </div>
                                   </td>
                              </tr>
                              <tr>
                                   <th>주소</th>
                                   <td class="addr">
										 <input type="text" name="zipcode" id="zipcode" value="" class="max100" readonly placeholder="우편번호">
										 <a href="javascript:;" onclick="sample2_execDaumPostcode();" class="btn intable">주소검색</a>
                                        <input type="text" name="addr0" id="addr0" value=""  class="readonly" readonly placeholder="기본주소">
                                        <input type="text" name="addr1" id="addr1" value="" placeholder="나머지 주소를 입력해주세요.">
                                   </td>
                              </tr>
                         </tbody>
                    </table>
                    <!-- //write--->
                    <div class="btnSet clear">
                         <div><input type="submit" value="확인"  class="btn"> <a href="javascript:;" class="btn cancel" onclick="history.back();">취소</a></div>
                    </div>
               <input type="hidden" name="cmd" id="cmd" value="write" />
               <input type="hidden" name="id_duplication" id="id_duplication" value="1" />
			   <input type="hidden" name="email" value="" id="email" readonly/>
			   <input type="hidden" name="email_duplication" id="email_duplication" value="1" />
               </form>
          </div>
          <!-- //여기까지 게시판--->
	</div>
	<!-- //size--->
</div>
<!-- //sub--->
<?
	include_once $root."/include/postcode.php";
	include_once $root."/footer.php";
?>
