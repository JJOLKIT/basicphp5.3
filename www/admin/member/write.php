<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/common.php"; ?>
<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

include_once $_SERVER['DOCUMENT_ROOT']."/lib/member/Member.class.php";

include $_SERVER['DOCUMENT_ROOT']."/admin/include/loginCheck.php";
include "config.php";

$member = new Member($pageRows, "member", $_REQUEST);
?>
<!doctype html>
<html lang="ko">
<head>
<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/headHtml.php"; ?>
<script>
function goSave() {
	if ($("#id").val().trim() == "") {
		alert("아이디를 입력해 주세요.");
		$("#id").focus();
		return false;
	}
	if ($("#name").val().trim() == "") {
		alert("이름을 입력해 주세요.");
		$("#name").focus();
		return false;
	}
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
	if ($("#cell").val().trim() == "") {
		alert("휴대폰 번호를 입력해 주세요.");
		$("#cell").focus();
		return false;
	}

	$("#board").submit();
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

</script>
<!-- 다음우편번호API -->
<script src="//t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>
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
</head>


<body>
<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/header.php"; ?>
<!-- s:warp -->
	<div class="contWrap">
		<div class="titWrap">
			<h2>회원등록</h2>
		</div>
		<div class="write">
			<form method="post" name="board" id="board" action="<?=getSslCheckUrl($_SERVER['REQUEST_URI'], 'process.php')?>">
				<div class="wr_box">
					<h3>개인정보</h3>
					<table class="row_line">
						<colgroup>
							<col width="8%">
							<col width="42%">
							<col width="8%">
							<col width="42%">
						</colgroup>
						<tbody>
						<tr>
							<th>아이디</th>
							<td><input type="text" name="id" id="id" value="" class="wid200" /> <input type="button" value="중복확인"  name="" onclick="checkId();" class="hoverbg"/></td>
							<th>이름</th>
							<td><input type="text" name="name" id="name" value="" class="wid200" /></td>
						</tr>
						<tr>
							<th>비밀번호</th>
							<td><input type="password" name="password" id="password" value=""  class="wid200"/></td>
							<th>비밀번호 확인</th>
							<td><input type="password" name="password2" id="password2" value=""  class="wid200"/></td>
						</tr>
						<tr>
							<th>이메일</th>
							<td><input type="text" name="email" id="email" value="" onkeyup="isValidEmailing(this);"/></td>
							<th>휴대전화</th>
							<td><input type="text" name="cell" id="cell" value="" class="wid200" maxlength="13" onkeyup="isNumberOrHyphen(this);cvtPhoneNumber(this);"/></td>
						</tr>
						<tr>
							<th>주소</th>
							<td colspan="3" class="inbr">
								<input type="text" name="zipcode" id="zipcode" value="" class="wid100" maxlength="10" readonly/>  <input type="button" value="우편번호 검색"  name="" onclick="zipcodeapi();" class="hoverbg"/><br/>
								<input type="text" name="addr0" id="addr0" value="" class="wid40" readonly/><br/>
								<input type="text" name="addr1" id="addr1" value="" class="wid40"/>
							</td>
						</tr>
						</tbody>
					</table>
				</div>			
			<input type="hidden" name="cmd" id="cmd" value="write"/>
			<input type="hidden" name="id_duplication" id="id_duplication" value=""/>
			<?=$member->getQueryStringToHidden($_REQUEST) ?>
			</form>
		</div>
		<!-- //write -->
		<div class="btnSet clear">
			<a href="javascript:;" class="btn hoverbg save" onclick="goSave();">저장</a>
			<a href="<?=$member->getQueryString('index.php', 0, $_REQUEST)?>" class="btn hoverbg">취소</a>
		</div>
		<!-- //btnSet -->
	</div>
	<!-- //contents -->
</div>
<!-- e:warp --> 
<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/footer.php"; ?>
</body>
</html>
