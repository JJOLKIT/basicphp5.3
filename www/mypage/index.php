<? include_once $_SERVER['DOCUMENT_ROOT']."/include/common.php"; ?>
<?
     include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
     include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
     include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
     include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
     include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

     include_once $_SERVER['DOCUMENT_ROOT']."/lib/member/Member.class.php";

     $member = new Member(9999, "member", $_REQUEST);
	

	if(!$loginCheck){
		loginConfirmURL('/');
	}else{
		$data = $member->getData($_SESSION['member_no']);
		
	$p = "";
	$sp = 0;
	$spc= 0;
	$root = $_SERVER['DOCUMENT_ROOT'];

	include_once $root."/header.php";
?>
<SCRIPT type="text/javascript">
function goSave() {
	if($('#pwdChange').val() == 1){
		if(!validPassword($("#password"))) return false;
		if ($("#password").val().trim() != $("#password2").val().trim()) {
			alert("비밀번호를 정확하게 입력해 주세요.");
			$("#password2").focus();
			return false;
		}
	}
	if ($("#name").val().trim() == "") {
		alert("이름을 입력해 주세요.");
		$("#name").focus();
		return false;
	}
	if ($("#cell").val().trim() == "") {
		alert("연락처를 입력해 주세요.");
		$("#cell").focus();
		return false;
	}
	if ($("#email1").val().trim() == "") {
		alert("이메일을 입력해 주세요.");
		$("#email1").focus();
		return false;
	}
	if ($("#email2").val().trim() == "") {
		alert("이메일을 입력해 주세요.");
		$("#email2").focus();
		return false;
	}
	$('#email').val( $('#email1').val().trim() + '@' + $('#email2').val().trim() );
	if ($("#email").val().trim() != "") {
		if(!isValidEmail(getObject("email"))) {
			alert("잘못된 이메일 형식입니다.\\n올바로 입력해 주세요.\\n ex)abcdef@naver.com");
			$("#email").focus();
			return false;
		}
	}

	if( $('#email').val().trim() != '<?=$data[email]?>' ){
		if($('#email_duplication').val() == 1){
			alert('이메일 중복체크를 해주세요.');
			return false;
		}
		$.ajax({
			type : "POST",
			url: "/include/email_check.php",
			async: false,
			data: {
				email : $('#email').val().trim()
			},
			success: function( data ) {
				var r = data.trim();
				if (r == "false") {
					alert("중복된 이메일입니다.");
					$('#email1').focus();
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

	
	}

 	if ($("#zipcode").val().trim() == "") {
		alert("우편번호를 입력해 주세요.");
		$("#zipcode").focus();
		return false;
	}
	if ($("#addr0").val().trim() == "") {
		alert("주소를 입력해 주세요.");
		$("#addr0").focus();
		return false;
	}

	$("#board").submit();
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

function changeEmail(){
	var orgEmail = '<?=$data[email]?>';

	var newEmail = $('#email1').val().trim() + '@' + $('#email2').val().trim();

	if(orgEmail != newEmail){
		$('#checkEmailBtn').css('display','inline-block');
		$('#email_duplication').val("1");
	}else{
		$('#checkEmailBtn').css('display','none');
		$('#email_duplication').val("0");
	}
}

function showPwdIpt(obj){
	$('tr.pwdIpt').css('display','table-row');
	$('div.pwdIpt').css('display','block');
	$('#pwdChange').val('1');
}
</SCRIPT>
<script src="https://t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>
<script>
    function zipcodeapi() {
        new daum.Postcode({
            oncomplete: function(data) {
                // 팝업에서 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분.

                // 각 주소의 노출 규칙에 따라 주소를 조합한다.
                // 내려오는 변수가 값이 없는 경우엔 공백('')값을 가지므로, 이를 참고하여 분기 한다.
                var fullAddr = ''; // 최종 주소 변수
                var extraAddr = ''; // 조합형 주소 변수

                // 사용자가 선택한 주소 타입에 따라 해당 주소 값을 가져온다.
                if (data.userSelectedType === 'R') { // 사용자가 도로명 주소를 선택했을 경우
                    fullAddr = data.roadAddress;

                } else { // 사용자가 지번 주소를 선택했을 경우(J)
                    fullAddr = data.jibunAddress;
                }

                // 사용자가 선택한 주소가 도로명 타입일때 조합한다.
                if(data.userSelectedType === 'R'){
                    //법정동명이 있을 경우 추가한다.
                    if(data.bname !== ''){
                        extraAddr += data.bname;
                    }
                    // 건물명이 있을 경우 추가한다.
                    if(data.buildingName !== ''){
                        extraAddr += (extraAddr !== '' ? ', ' + data.buildingName : data.buildingName);
                    }
                    // 조합형주소의 유무에 따라 양쪽에 괄호를 추가하여 최종 주소를 만든다.
                    fullAddr += (extraAddr !== '' ? ' ('+ extraAddr +')' : '');
                }

                // 우편번호와 주소 정보를 해당 필드에 넣는다.
                $('#zipcode').val(data.zonecode); //5자리 새우편번호 사용
                $('#addr0').val(fullAddr);

                // 커서를 상세주소 필드로 이동한다.
                $('#addr1').focus();
            }
        }).open();
    }
</script>
<div id="sub" class="">
	<div class="size">
		<!-- 여기서부터 게시판--->
           <div class="bbs">
               <form name="board" id="board" method="post" action="process.php">
                    <table class="write">
                         <caption>회원정보</caption>
                         <colgroup>
                              <col width="20%" />
                              <col width="*" />
                         </colgroup>
                         <tbody>
                              <tr>
                                   <th>아이디 <span class="required">*<span>필수입력</span></span></th>
                                   <td><?=$data['id']?></td>
                              </tr>
                              <tr>
                                   <th>비밀번호 <span class="required">*<span>필수입력</span></span></th>
                                   <td>
										<a href="javascript:;" class="btn intable" onclick="showPwdIpt(this);">새 비밀번호 변경</a>
										<div class="pwdIpt">
											<input type="password" name="password" id="password" class="max200" autocomplete="off"/> 
											<p class="help">비밀번호는 숫자, 영문 조합으로 8자 이상으로 입력해주세요.</p>    
										</div>
                                   </td>
                              </tr>
                              <tr class="pwdIpt">
                                   <th>비밀번호확인 <span class="required">*<span>필수입력</span></span></th>
                                   <td><input type="password" name="password2" id="password2" class="max200" autocomplete="off"/></td>
                              </tr>
                              <tr>
                                   <th>이름 <span class="required">*<span>필수입력</span></span></th>
                                   <td><input type="text" name="name" id="name" value="<?=$data['name']?>" class="max200"> </td>
                              </tr>
                              <tr>
                                   <th>연락처 <span class="required">*<span>필수입력</span></span></th>
                                   <td>
                                        <input type="text" name="cell" id="cell" value="<?=$data['cell']?>" maxlength="15" onkeyup="isNumberOrHyphen(this);cvtPhoneNumber(this);" class="max300">
                                   </td>
                              </tr>
                              <tr>
                                   <th>이메일 <span class="required">*<span>필수입력</span></span></th>
                                   <td class="email">
                                         <input type="text" name="email1" id="email1" value="<?=explode("@", $data['email'])[0]?>" class="max200" autocomplete="off" onkeyup="changeEmail();"/>
                                         <span class="at">@</span>
                                         <input type="text" name="email2" id="email2" value="<?=explode("@", $data['email'])[1]?>" class="max200" autocomplete="off" onkeyup="changeEmail();"/>
                                         <span class="select max200">
										 <select onchange="document.getElementById('email2').value = this.value; changeEmail();" class="max200">
												<option value="">직접입력</option>
												<option value="naver.com">naver.com</option>
												<option value="gmail.com">gmail.com</option>
												<option value="nate.com">nate.com</option>
												<option value="daum.net">daum.net</option>
										  </select>
                                          </span>
										   <a href="javascript:;" onclick="checkEmail();" class="btn intable" style="display:none;" id="checkEmailBtn">중복확인</a>
                                    </td>
                              </tr>
                              <tr>
                                   <th>주소</th>
                                   <td class="addr">
                                        <p class="ipt_box">
                                             <input type="text" name="zipcode" id="zipcode" value="<?=$data['zipcode']?>" class="max100" readonly>
                                             <a href="javascript:;" onclick="sample2_execDaumPostcode();" class="btn intable">주소검색</a>
                                        </p>
                                        <input type="text" name="addr0" id="addr0" value="<?=$data['addr0']?>" readonly>
                                        <input type="text" name="addr1" id="addr1" value="<?=$data['addr1']?>">
                                   </td>
                              </tr>
                         </tbody>
                    </table>
                    <!-- //write--->
                    <div class="btnSet clear">
                         <div><a href="javascript:;" class="btn" onclick="goSave();">확인</a> <a href="javascript:;" class="btn cancel" onclick="history.back();">취소</a></div>
                    </div>
                    <input type="hidden" name="cmd" id="cmd" value="edit" />
                    <input type="hidden" name="no" id="no" value="<?=$data['no']?>" />
					<input type="hidden" name="email" id="email" value="<?=$data['email']?>"/>
					<input type="hidden" name="email_duplication" id="email_duplication" value="0"/>
					<input type="hidden" class="pwdChange" id="pwdChange" value="0"/>
               </form>
          </div>
          <!-- //여기까지 게시판--->
	</div>
	<!-- //size--->
</div>
<!-- //sub--->
<? }

include_once $root."/include/postcode.php";
include_once $root."/footer.php";

?>