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
<!doctype html>
<html lang="ko">
<head>
<? include_once $_SERVER['DOCUMENT_ROOT']."/include/headHtml.php"; ?>
<SCRIPT type="text/javascript">

function goSave() {
	

	if($('#id').val().trim() == ""){
		alert('아이디를 입력해 주세요.');
		$('#id').focus();
		return false;
	}

	if($('#name').val().trim() == ""){
		alert('이름을 입력해 주세요.');
		$('#name').focus();
		return false;
	}
	if($('#email').val().trim() == ""){
		alert('이메일을 입력해 주세요.');
		$('#email').focus();
		return false;
	}
	
	if( $('#duplicate').val() == 2){
		alert('이메일 인증을 완료해 주세요.');
		return false;
	}



	
	$("#board").submit();
}


function codeMail(){
	if($('#id').val().trim() == ""){
		alert('아이디를 입력해 주세요.');
		$('#id').focus();
		return false;
	}

	if($('#name').val().trim() == ""){
		alert('이름을 입력해 주세요.');
		$('#name').focus();
		return false;
	}
	if($('#email').val().trim() == ""){
		alert('이메일을 입력해 주세요.');
		$('#email').focus();
		return false;
	}

	$.ajax({
		url : 'garbage_code.php',
		data : {
			'id' : $('#id').val().trim(),
			'name' : $('#name').val().trim(),
			'email' : $('#email').val().trim()
		},
		success : function(data){
			var r = data.trim();	
			if(r == "success"){
				alert('인증메일이 발송되었습니다.');
				$('#codeSend').html('발송완료').attr('onclick', '');
				$('.codetr').css('display','table-row');

			}else{
				alert('정보가 일치하지 않거나, 존재하지 않습니다.');

			}
		}
	});
	
}

function checkCode(){
	if($('#email_code').val().trim() == ""){
		alert('메일로 발송된 인증코드를 입력해 주세요.');
		$('#email_code').focus();
		return false;
	}else{
		$.ajax({
			url : 'codeCheck.php',
			data : {
				'email_code' : $('#email_code').val().trim(),
			},
			success : function(data){
				var r = data.trim();
				if (r == "success")
				{
					alert('인증이 완료되었습니다.');
					$('.codetr').css('display','none');
					$('#duplicate').val(1);
				}else{
					$('#duplicate').val(2);
					alert('인증이 실패하였습니다.');
				}
			}
		});
	}
}
</SCRIPT>
<style>
	.codetr{display:none;}
</style>
</head>
<body>
<div class="program">
	<h2 class="title">휴면계정 해제</h2>
					<form name="board" id="board" method="post" action="/member/process.php">
						<div class="member bbs">
							<!-- //agree--->
							<h3 class="mt30">가입정보</h3>
							<table class="write join">
								<caption>가입정보</caption>
								<colgroup>
									<col width="20%" />
									<col width="*" />
								</colgroup>
								<tbody>
									<tr>
										<th>아이디</th>
										<td>
											<p class="ipt_box">
												<input type="text" name="id" id="id" class="ipt">
											</p>
										</td>
									</tr>
									<tr>
										<th>이름</th>
										<td><input type="text" name="name" id="name" class="ipt"> </td>
									</tr>
									<tr>
										<th>이메일</th>
										<td class="emailtd">
											<p class="ipt_box">
												<input type="text" name="email" id="email" value="" class="ipt2" >
												<input type="text" name="code" id="code" value="" style="display:none;"/>
												<a href="javascript:;" onclick="codeMail();" class="ipt_btn" id="codeSend">인증메일 발송</a>

											</p>
										</td>
									</tr>
									<tr class="codetr">
										<th>인증코드</th>
										<td>
											<input type="text" name="email_code" id="email_code" value="" class="wid200"/>
											<input type="button" value="인증확인" onclick="checkCode();"/>
										</td>
									</tr>
			
								</tbody>
							</table>
							<!-- //write--->
							<div class="btnSet clear">
								<div><a href="javascript:;" class="btn" onclick="goSave();">확인</a> 
								<a href="javascript:;" class="btn" onclick="history.back();">취소</a></div>
							</div>
						</div>
					<input type="hidden" name="cmd" id="cmd" value="return" />
					<input type="hidden" name="duplicate" id="duplicate" value="2"/>
					</form>
					<!--e:내용-->
</div>
</body>
</html>
