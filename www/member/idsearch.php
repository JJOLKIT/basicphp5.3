<? include_once $_SERVER['DOCUMENT_ROOT']."/include/common.php"; ?>
<? include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php"; ?>
<!doctype html>
<html lang="ko">
<head>
<? include_once $_SERVER['DOCUMENT_ROOT']."/include/headHtml.php"; ?>
<SCRIPT type="text/javascript">

function idCheck(){
	var con = true;
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

function pwCheck(){
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
<body onload="getObject('name').focus();">
<div class="program">
	<h2 class="title">아이디 찾기</h2>
			<!--
					<form name="board" id="board" method="post" action="<?=getSslCheckUrl($_SERVER['REQUEST_URI'], 'process.php')?>" onsubmit="return loginCheck();">
						<div class="member">
							<div class="box">
								<div class="tab">
									<ul>
										<li class="on"><a href="idsearch.php">아이디찾기</a></li>
										<li><a href="pwsearch.php">비밀번호찾기</a></li>
									</ul>
								</div>
								<p>회원정보에 등록된 이름, 이메일 주소를 입력해주세요
								</p>
								<fieldset class="login_form">
									<legend class="blind">아이디 찾기</legend>
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
									<div class="search_btn"><input type="submit" value="아이디찾기" alt="아이디찾기" /></div>
								</fieldset>
							</div>
						</div>
					<input type="hidden" name="cmd" id="cmd" value="searchid"/>
					</form>
			-->
	
					<div class="find_wrap">
						<div class="find_tab">
							<ul class="clear">
								<li>
									<a href="javascript:;" class="on">아이디 찾기</a>
								</li>
								<li>
									<a href="javascript:;">비밀번호 찾기</a>
								</li>
							</ul>
						</div>
						<div class="find_id">
							<form name="board" id="board" method="post" action="/include/login.php" onsubmit="return idCheck(this);" class="login_form">
								<fieldset>
									<input type="hidden" name="cmd" value="searchid"/>
									
									<div class="login_txt">
										<h4>아이디 찾기</h4>
										<p>
											회원정보에 등록된 이름, 이메일 주소를 입력해주세요
										</p>
									</div>
									<div class="login_box">
										<p>
											<input type="text" name="name" placeholder="이름"/>
										</p>
										<p>
											<input type="text" name="email" placeholder="이메일"/>
										</p>
									</div>
									<div class="login_btn">
										<input type="submit" value="아이디 찾기"/>
									</div>
								</fieldset>
							</form>
						</div>
						<div class="find_pw">
							<form name="board" id="board" method="post" action="/include/login.php" onsubmit="return pwCheck();" class="login_form">
								<fieldset>
									<input type="hidden" name="cmd" value="searchid"/>
									
									<div class="login_txt">
										<h4>비밀번호 찾기</h4>
										<p>
											회원정보에 등록된 이름, 이메일 주소를 입력해주세요
										</p>
									</div>
									<div class="login_box">
										<p>
											<input type="text" name="id" placeholder="아이디"/>
										</p>
										<p>
											<input type="text" name="name" placeholder="이름"/>
										</p>
										<p>
											<input type="text" name="email" placeholder="이메일"/>
										</p>
									</div>
									<div class="login_btn">
										<input type="submit" value="비밀번호 찾기"/>
									</div>
								</fieldset>
							</form>
						</div>
					</div>
</div>
</body>
</html>
