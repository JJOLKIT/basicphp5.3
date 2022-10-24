<? include_once $_SERVER['DOCUMENT_ROOT']."/include/common.php"; ?>
<?
	include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
	include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
	include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
	include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
	include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

	include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/Reply2.class.php";

	include "config.php";

	$notice = new Reply2($pageRows, $tablename, $_REQUEST);
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
$(document).ready(function(e){
	oEditors = setEditor("contents"); // 에디터 셋팅
});

function goSave(frm) {
	<?if(!$loginCheck){?>
	if(frm.agree_2.checked == false){
		alert('개인정보 수집방침에 동의해 주세요.');
		frm.agree_2.focus();
		return false;
	}
	
	
	<?}?>
	
	if(frm.name.value.trim() == ""){
		alert('이름을 입력해 주세요.');
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
			frm.email2.focus();
			return false;
		}
	}



	if(frm.title.value.trim() == ""){
		alert('제목을 입력해 주세요.');
		frm.title.focus();
		return false;
	}

	var sHTML = oEditors.getById["contents"].getIR();
	if (sHTML.replace("<p><br></p>","") == "") {
		alert('내용을 입력해 주세요.');
		frm.contents.focus();
		return false;
	} else {
		oEditors.getById["contents"].exec("UPDATE_CONTENTS_FIELD", []);	// 에디터의 내용이 textarea에 적용됩니다.
	}
	

	<?if(!$loginCheck){?>
	if(frm.password.value.trim() == ""){
		alert('비밀번호를 입력해 주세요.');
		frm.password.focus();
		return false;
	}
	<?}?>
	
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
                <form method="post" name="frm" id="frm" action="<?=getSslCheckUrl($_SERVER['REQUEST_URI'], 'process.php')?>" enctype="multipart/form-data" onsubmit="return goSave(this);">

				<?
					if(!$loginCheck){
				?>
                <div class="agree">
                    <div class="agree_con"> 
                        <strong>개인정보수집방침</strong>
                        <? include_once $_SERVER['DOCUMENT_ROOT']."/member/policy.html"; ?>
                    </div>
                    <p class="check_box">
                        <input type="checkbox" name="agree_2" id="agree_2"/>
                        <label for="agree_2"> 개인정보수집방침에 동의합니다.</label>
                    </p>
                </div>
				<?}?>
                <!-- //agree--->
				<table class="write">
                    <caption>글쓰기</caption>
                    <colgroup>
                        <col width="15%" />
                        <col width="*" />
                    </colgroup>
                    <tbody>
                        <tr>
                            <th>이름 <span class="required">*<span>필수입력</span></span></th>
                            <td><input type="text" name="name" id="name" class="max200" value="<?=$_SESSION['member_name']?>"/></td>
                        </tr>
                        <tr>
                            <th>연락처 <span class="required">*<span>필수입력</span></span></th>
                            <td><input type="tel" class="max300" name="cell" id="cell" value="<?=$_SESSION['member_cell']?>" maxlength="15" onkeyup="isNumberOrHyphen(this);cvtPhoneNumber(this);" placeholder="숫자만 입력해주세요."/></td>
                        </tr>
                        <tr>
                            <th>이메일 <span class="required">*<span>필수입력</span></span></th>
                            <td class="email">
                                 <input type="text" name="email1" id="email1" value="<?=explode("@", $_SESSION['member_email'])[0]?>" class="max200" autocomplete="off"/>
                                 <span class="at">@</span>
                                 <input type="text" name="email2" id="email2" value="<?=explode("@", $_SESSION['member_email'])[1]?>" class="max200" autocomplete="off"/>
                                 <span class="select max200">
                                             <select onchange="document.getElementById('email2').value = this.value;" class="max200">
												<option value="">직접입력</option>
												<option value="naver.com">naver.com</option>
												<option value="gmail.com">gmail.com</option>
												<option value="nate.com">nate.com</option>
												<option value="daum.net">daum.net</option>
					                    </select>
                                   </span>
                             </td>
                        </tr>
                        <tr>
                            <th>제목 <span class="required">*<span>필수입력</span></span></th>
                            <td><input type="text" name="title" id="title" />  </td>
                        </tr>
                        <tr class="txtarea">
                            <th>내용 <span class="required">*<span>필수입력</span></span></th>
                            <td><textarea name="contents" id="contents"></textarea></td>
                        </tr>

                        <tr>
                            <th>첨부<br />파일</th>
                            <td>
                                <div class="fileBox">
                                    <div class="inputBox">
                                        <input type="text" id="addFile" disabled="" value="">
                                    </div>
                                    <div class="fileBtn">
                                        <label>찾아보기<input type="file" name="filename" onchange="document.getElementById('addFile').value = this.files[0].name;"></label>
                                    </div>
                                </div>
                                <p class="help">첨부파일은 5MB 이하의 문서 파일만 가능합니다.</p>
                            </td>
                        </tr>
                        <?
                              if(!$loginCheck){
                         ?>
                         <tr>
                            <th>비밀번호 <span class="required">*<span>필수입력</span></span></th>
                            <td colspan="3">
                                <input type="password" name="password" id="password" class="max200" placeholder=""/>
                                <p class="help">글 수정, 삭제시 필요합니다.</p>
							</td>
                        </tr>
                         <?}?>
                        <tr>
                            <th>스팸방지코드 <span class="required">*<span>필수입력</span></span></th>
                            <td colspan="3">
                                <div class="spam">
							 <img src="/include/captcha.php?date=<?echo date('h:i:s')?>" id="capt_img">
							 <input type="button" value="새로고침" onclick="refresh_captcha();" class="spam_refresh"/>
                                    <input type="text" id="zsfCode" name="zsfCode" class="max100" maxlength='6' onkeyup='' autocomplete="off">
                                </div>
                            </td>
                        </tr>

                    </tbody>
                </table>
                <!-- //write--->
                <div class="btnSet clear">
                    <div><input type="submit" value="저장" class="btn"/> <a href="javascript:;" onclick="history.back();" class="btn cancel">취소</a></div>
                </div>
                 <input type="hidden" name="secret" value="1">
                 <input type="hidden" name="cmd" value="write" />
                 <input type="hidden" name="member_fk" value="<?=$_SESSION['member_no']?>" />
				 <input type="hidden" name="email" id="email" value="<?=$_SESSION['member_email']?>"/>
                 <?=$notice->getQueryStringToHidden($_REQUEST) ?>
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

				
