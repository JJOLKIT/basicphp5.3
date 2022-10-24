<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/common.php"; ?>
<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/Popup.class.php";

include $_SERVER['DOCUMENT_ROOT']."/admin/include/loginCheck.php";
include "config.php";

$popup = new Popup($pageRows, $tablename, $_REQUEST);
$data = $popup->getData($_REQUEST[no]);
?>
<!doctype html>
<html lang="ko">
<head>
<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/headHtml.php"; ?>
<link href="/admin/css/colorPicker.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/admin/js/jquery.colorPicker.js"></script>

<script>
	var oEditors; // 에디터 객체 담을 곳
	jQuery(window).load(function(){
		oEditors = setEditor("contents"); // 에디터 셋팅
		
		initCal({id:"start_day",type:"day",today:"y"});
		initCal({id:"end_day",type:"day",today:"y"});

		goType(<?=$data[type]?>);
	});
	
	$(document).ready(function(){
		$(".img_alt").focus(function(){
			focusAltRemove($(this));
		});
		
		$(".img_alt").blur(function(){
			blurAltInsert($(this));
		});
		
		$('#border_color').colorPicker({pickerDefault: "<?=str_replace('#','',$data[border_color])?>"});
		$('#bg_color').colorPicker({pickerDefault: "<?=str_replace('#','',$data[bg_color])?>"});

	});
	
	function goSave() {
		var f= document.frm;
		
	 	if (getRadioValue(f.type) == "0" || getRadioValue(f.type) == "2") {
			if ($("#popup_width").val() == false || $("#popup_width").val() == '0') {
				alert('가로사이즈 입력하세요.(0 입력 불가)');
				$("#popup_width").focus();
				return false;
			} 
			
			if ($("#popup_height").val() == false || $("#popup_height").val() == '0') {
				alert('세로사이즈를 입력하세요.(0 입력 불가)');
				$("#popup_height").focus();
				return false;
			} 
		} 
		if ($("#area_left").val() == false) {
			if($('#center_yn').is(':checked') == true){
				
			}else{
				alert('가로위치를 입력하세요.');
				$("#area_left").focus();
				return false;
			}
		} 
		if ($("#area_top").val() == false) {
			if($('#center_yn').is(':checked') == true){
			}else{
				alert('세로위치를 입력하세요.');
				$("#area_top").focus();
				return false;
			}
		} 
		
		if ($("#start_day").val() == false || $("#start_day").val().length < 9) {
			alert('시작일을 입력하세요.');
			$("#start_day").focus();
			return false;
		}
		 if ($("#end_day").val() == false || $("#end_day").val().length < 9) {
			 alert('종료일 입력하세요.');
				$("#end_day").focus();
			return false;
		}
		if ($("#title").val() == "") {
			alert('제목을 입력해 주세요.');
			$("#title").focus();
			return false;
		}
		if (getRadioValue(f.type) == "0" || getRadioValue(f.type) == "2") {
			var sHTML = oEditors.getById["contents"].getIR();
			
			if (sHTML == "") {
				alert('내용을 입력해 주세요.');
				$("#contents").focus();
				return false;
			} else {
				oEditors.getById["contents"].exec("UPDATE_CONTENTS_FIELD", []);	// 에디터의 내용이 textarea에 적용됩니다.
			}
		}
		else {
			if ($("#imagename").val() != "") {
				if (checkImgFormatPopup(document.getElementById("imagename"))) {
					$("#imagename").focus();
					return false;
				}
			}
		}

		$("#frm").submit();
	}

	function goType(type) {
		if (type == "0") {
			$(".normalPopup").show();
			$("#size").show();
			$(".imagePopup").hide();
			$("#fileMovie").hide();
			$(".color").show();
			$("#borderDl").show();
			$("#bgDl").hide();
			$('#center').show();
		} else if (type == "1") {
			$(".normalPopup").hide();
			$("#size").hide();
			$(".imagePopup").show();
			$("#fileMovie").hide();
			$(".color").show();
			$("#borderDl").hide();
			$("#bgDl").show();
			$('#center').show();
		} else if (type == "2") {
			$(".normalPopup").show();
			$("#size").show();
			$(".imagePopup").hide();
			$("#fileMovie").show();
			$(".color").hide();
			$('#center').hide();
			$('.posXY').show();
			$('#center input').prop('checked',false);
		} else if (type == "3") {
			$(".normalPopup").hide();
			$("#size").hide();
			$(".imagePopup").show();
			$("#fileMovie").hide();
			$(".color").hide();
			$('#center').hide();
			$('.posXY').show();
			$('#center input').prop('checked',false);
		}
	}
	function chkCenter(chk){
		if( $(chk).is(':checked') == true ){
			$('.posXY').hide();
		}else{
			$('.posXY').show();
		}
	}

	function goDelete() {
		var del = confirm ('삭제하시겠습니까?');
		if (del){
			document.location.href = "process.php?no=<?=$data[no]?>&cmd=delete";
		} else {
			return false;
		}
	}
	
</script>
</head>


<body>
<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/header.php"; ?>
<!-- s:warp -->
	<div class="contWrap">
		<div class="titWrap">
			<h2>공지사항 글쓰기</h2>
		</div>
		<div class="write">
		<form method="post" name="frm" id="frm" action="<?=getSslCheckUrl($_SERVER['REQUEST_URI'], 'process.php')?>" enctype="multipart/form-data">
			<div class="wr_box">
				<h3>등록정보</h3>
				<table class="row_line">
					<colgroup>
						<col width="8%">
						<col width="42%">
						<col width="8%">
						<col width="42%">
					</colgroup>
					<tbody>
					<tr>
						<th>팝업종류</th>
						<td colspan="3">
							<!--
							<input name="type" type="radio" id="type2" value="2" onclick="goType('2');"onfocus="goType('2');" <?=getChecked($data[type], "2")?> />
							<label for="type2">일반 팝업</label>                                                                 
							<input name="type" type="radio" id="type3" value="3" onclick="goType('3');"onfocus="goType('3');" <?=getChecked($data[type], "3")?>/>
							<label for="type3">이미지 팝업</label>                                                                
							<input name="type" type="radio" id="type0" value="0" onclick="goType('0');"onfocus="goType('0');" <?=getChecked($data[type], "0")?>/>
							<label for="type0">일반레이어 팝업</label>          
							-->
							<input name="type" type="radio" id="type1" value="1" onclick="goType('1');"onfocus="goType('1');" <?=getChecked($data[type], "1")?>/>
							<label for="type1">이미지레이어 팝업</label>
						</td>
					</tr>
					<tr id="size">
						<th>가로사이즈</th>
						<td>
							<input type="text" name="popup_width" id="popup_width" value="<?=$data[popup_width]?>" maxlength="4" onkeydown="isOnlyNumber(this)" onkeyup="isOnlyNumber(this)" style="width:70px;"/> px 
						</td>
						<th>세로사이즈</th>
						<td>
							<input type="text" name="popup_height" id="popup_height" value="<?=$data[popup_height]?>" maxlength="4" onkeydown="isOnlyNumber(this)" onkeyup="isOnlyNumber(this)" style="width:70px;"/> px 
						</td>
					</tr>
					<tr id="borderDl">
						<th>테두리 색</th>
						<td colspan="3">
							<input id="border_color" name="border_color" value="" style="width:50px;"/>
						</td>
					</tr>
					<tr id="bgDl">
						<th>배경 색</th>
						<td colspan="3">
							<input id="bg_color" name="bg_color" value="" style="width:50px;"/>
						</td>
					</tr>
					<tr id="center" style="display:none;">
						<th>가운데정렬</th>
						<td colspan="3"><input type="checkbox" name="center_yn" value="1" id="center_yn" <?if($data['center_yn'] == 1) { echo "checked"; }?> onclick="chkCenter(this);"/><label for="center_yn">가운데정렬</label></td>
					</tr>
					<tr class="posXY">
						<th>가로위치</th>
						<td>
							<input type="text" name="area_left" id="area_left" value="<?=$data[area_left]?>"  maxlength="4"  onkeydown="isOnlyNumber(this)" onkeyup="isOnlyNumber(this)" style="width:70px;"/> px
						</td>
						<th>세로위치</th>
						<td>
							<input type="text" name="area_top" id="area_top" value="<?=$data[area_top]?>" maxlength="4"  onkeydown="isOnlyNumber(this)" onkeyup="isOnlyNumber(this)" style="width:70px;"/> px
						</td>
					</tr>
					<tr>
						<th>시작일</th>
						<td>
							<p class="calendar">
								<input type="text" id="start_day" name="start_day" maxlength="10" value="<?=substr($data[start_day],0,10)?>" title="시작일을 입력해주세요" readonly/>
								<span id="Calstart_dayIcon">
									<span class="material-icons" id="Calstart_dayIconImg">calendar_month</span>
								</span>
							</p>
						</td>
						<th>종료일</th>
						<td>
							<p class="calendar">
								<input type="text" id="end_day" name="end_day" maxlength="10" value="<?=substr($data[end_day],0,10)?>" title="종료일을 입력해주세요" readonly/>
								<span id="Calend_dayIcon">
									<span class="material-icons" id="Calend_dayIconImg">calendar_month</span>
								</span>
							</p>
						</td>
					</tr>
					<tr>
						<th>제목</th>
						<td colspan="3">
							<input type="text" id="title" name="title" class="input92p" value="<?=$data[title]?>" title="제목을 입력해주세요" /><br/>
							<font color="red">※ 제목은 팝업 상단에 노출됩니다.</font>
						</td>
					</tr>
					<tr class="normalPopup">
						<th>내용</th>
						<td colspan="3">
							<textarea id="contents" name="contents" title="내용을 입력해주세요" style="width:100%;"><?=$data[contents]?></textarea>	
						</td>
					</tr>
					<tr class="imagePopup" style="display:none;">
						<th>이미지</th>
						<td colspan="3">
							<? if ($data[imagename]) { ?>
							기존파일 : <?=$data[imagename_org]?>&nbsp;&nbsp;<input type="checkbox" name="imagename_chk" id="imagename_chk" value="0"/>기존파일삭제<br/>
							<? } ?>
							<input type="file" id="imagename" name="imagename" class="input92p" title="이미지파일을 업로드 해주세요." /><br/>
							<font color="red">※ 이미지 사이즈는 적당한 사이즈로 미리 변경하신 후에 저장하세요!</font>
						</td>
					</tr>
					<tr class="imagePopup" style="display:none;">
						<th>이미지 설명</th>
						<td colspan="3">
							<input type="text" id="img_alt" name="img_alt" title="이미지 설명을 입력해주세요." value="<?=$data[image_alt]?>"/>
						</td>
					</tr>
					<tr class="imagePopup" style="display:none;">
						<th>상세보기 URL</th>
						<td colspan="3">
							<input type="text" id="relation_url" name="relation_url" value="<?=$data[relation_url]?>"/>
						</td>
					</tr>
					</tbody>
				</table>
			</div>
			<!-- //wr_box -->
		<input type="hidden" name="cmd" value="edit"/>
		<input type="hidden" name="no" value="<?=$data[no]?>"/>
		<input type="hidden" name="stype" value="<?=$data[stype]?>"/>
		<input type="hidden" name="sval" value="<?=$data[sval]?>"/>
		<input type="hidden" name="reqPageNo" value="<?=$popup->reqPageNo?>"/>
		</form>
		</div>
		<!-- //write -->
		<div class="btnSet clear">
			<a href="javascript:;" class="btn hoverbg save" onclick="goSave();">저장</a>
			<a href="javascript:;" class="btn hoverbg" onclick="goDelete();">삭제</a>
			<a href="<?=$popup->getQueryString('index.php', 0, $_REQUEST)?>" class="btn hoverbg">취소</a>
		</div>
		<!-- //btnSet -->
	</div>
	<!-- //contents -->
</div>
<!-- e:warp --> 
<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/footer.php"; ?>
</body>
</html>
