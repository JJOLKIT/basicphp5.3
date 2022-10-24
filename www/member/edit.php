<? include_once $_SERVER['DOCUMENT_ROOT']."/include/common.php"; ?>
<?
     include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
     include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
     include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
     include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
     include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

     include_once $_SERVER['DOCUMENT_ROOT']."/lib/member/Member.class.php";

     $member = new Member(9999, "member", $_REQUEST);
?>
<?
	if (!$loginCheck) {
?>
	<script type="text/javascript">
		var yesno = confirm("로그인이 필요합니다.\r\n로그인 하시겠습니까?");
		if(yesno) { document.location = "/member/login.php"; }
		else { history.go(-1); }
	</script>
<? 
	} else {
		$_REQUEST['no'] = $_SESSION['member_no'];
		$data = $member->getData($_REQUEST);
?>
<?	
	$p = "";
	$sp = 0;
	$spc= 0;
	$root = $_SERVER['DOCUMENT_ROOT'];
	include_once $root."/header.php";
?>
<SCRIPT type="text/javascript">
function goSave() {
	if(!validPassword($("#password"))) return false;
	if ($("#password").val().trim() != $("#password2").val().trim()) {
		alert("비밀번호를 정확하게 입력해 주세요.");
		$("#password2").focus();
		return false;
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
	if ($("#email").val().trim() == "") {
		alert("이메일을 입력해 주세요.");
		$("#email").focus();
		return false;
	}
	if ($("#email").val().trim() != "") {
		if(!isValidEmail(getObject("email"))) {
			alert("잘못된 이메일 형식입니다.\\n올바로 입력해 주세요.\\n ex)abcdef@naver.com");
			$("#email").focus();
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
	if ($("input[name='door_password_type']:checked").val() == "1") {
		if ($("#door_password").val().trim() == "") {
			alert("현관비밀번호를 입력해 주세요.");
			$("#door_password").focus();
			return false;
		}
	}
	$("#board").submit();
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
               <form name="board" id="board" method="post" action="/member/process.php">
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
                                   <td><input type="password" name="password" id="password" class="max200"> 
                                        <p class="help">비밀번호는 숫자, 영문 조합으로 8자 이상으로 입력해주세요.</p>    
                                   </td>
                              </tr>
                              <tr>
                                   <th>비밀번호확인 <span class="required">*<span>필수입력</span></span></th>
                                   <td><input type="password" name="password2" id="password2" class="max200"></td>
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
                                        <input type="text" name="email" id="email" value="<?=$data['email']?>" class="max300">
                                   </td>
                              </tr>

                              <tr>
                                   <th>주소</th>
                                   <td class="addr">
                                        <p class="ipt_box">
                                             <input type="text" name="zipcode" id="zipcode" value="<?=$data['zipcode']?>" class="max100" readonly>
                                             <a href="javascript:;" onclick="zipcodeapi();" class="btn intable">주소검색</a>
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
               </form>
          </div>
          <!-- //여기까지 게시판--->
	</div>
	<!-- //size--->
</div>
<!-- //sub--->
<? } ?>