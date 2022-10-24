<? include_once $_SERVER['DOCUMENT_ROOT']."/include/common.php"; ?>
<? include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php"; ?>
<!doctype html>
<html lang="ko">
<head>
<? include_once $_SERVER['DOCUMENT_ROOT']."/include/headHtml.php"; ?>
<SCRIPT type="text/javascript">

function loginCheck(){
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

	if ($("#email").val().trim() == "") {
		alert("이메일 주소를 입력해 주세요.");
		$("#email").focus();
		return false;
	}

	return true;
}
</SCRIPT>
</head>
<body onload="getObject('id').focus();">
<div class="program">
	<h2 class="title">비밀번호 찾기</h2>
					<form name="board" id="board" method="post" action="<?=getSslCheckUrl($_SERVER['REQUEST_URI'], 'process.php')?>" onsubmit="return loginCheck();">
						<div class="member">
							<div class="box">
								<div class="tab">
									<ul>
										<li><a href="idsearch.php">아이디찾기</a></li>
										<li class="on"><a href="pwsearch.php">비밀번호찾기</a></li>
									</ul>
								</div>
								<p>회원정보에 등록된 아이디, 이메일 주소를 입력해주세요
								</p>
								<fieldset class="login_form">
									<legend class="blind">비밀번호 찾기</legend>
									<dl>
										<dt>아이디</dt>
										<dd><input type="text" name="id" id="id"></dd>
									</dl>
									<dl>
										<dt>이름</dt>
										<dd><input type="text" name="name" id="name"></dd>
									</dl>
									<dl>
										<dt>이메일</dt>
										<dd>
											<input type="text" name="email" id="email" value="">
										</dd>
									</dl>
									<div class="search_btn"><input type="submit" value="비밀번호찾기" alt="비밀번호찾기" /></div>
								</fieldset>
							</div>
						</div>
					<input type="hidden" name="cmd" id="cmd" value="searchpw"/>
					</form>
</div>
</body>
</html>
