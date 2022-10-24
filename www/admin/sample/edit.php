<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/common.php"; ?>
<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/Notice.class.php";

include $_SERVER['DOCUMENT_ROOT']."/admin/include/loginCheck.php";
include "config.php";

$notice = new Notice($pageRows, $tablename, $_REQUEST);
$data = $notice->getData($_REQUEST[no], $userCon);
?>
<!doctype html>
<html lang="ko">
<head>
<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/headHtml.php"; ?>
<script>
	var oEditors; // 에디터 객체 담을 곳
	jQuery(window).load(function(){
		//oEditors = setEditor("contents"); // 에디터 셋팅
		oEditors = setCkEditor('contents');
		// 달력
		//initCal({id:"registdate",type:"day",today:"y",timeYN:"y"});
		$( "#registdate" ).datetimepicker({
			//dateFormat: 'yy-mm-dd',
			format : 'Y-m-d H:m:s',
			//timeFormat : 'HH:mm:ss',
			prevText: '이전 달',
			nextText: '다음 달',
			monthNames: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],
			monthNamesShort: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],
			dayNames: ['일','월','화','수','목','금','토'],
			dayNamesShort: ['일','월','화','수','목','금','토'],
			dayNamesMin: ['일','월','화','수','목','금','토'],
			showMonthAfterYear: true,
			changeMonth: true,
			changeYear: true,
			yearSuffix: '년',
			//minDate : '+0d',
			isRTL: false,
			
		});
	});
	
	function goSave() {
		var regex=/^[0-9a-zA-Z]([-_\.]?[0-9a-zA-Z])*@[0-9a-zA-Z]([-_\.]?[0-9a-zA-Z])*\.[a-zA-Z]{2,3}$/i;
		if ($("#name").val() == "") {
			alert('작성자를 입력해 주세요.');
			$("#name").focus();
			return false;
		}
		/*
		if ($("#registdate").val() != "") {
			var regex2=/[0-9]{4}[\-][0-1][0-9][\-][0-3][0-9]\s[0-2][0-9]:[0-6][0-9]:[0-6][0-9]$/i; 
			if(!regex2.test($("#registdate").val())){
				alert('잘못된 날짜 형식입니다.\\n올바로 입력해 주세요.\\n ex)2013-02-14 03:28:85');
				$("#registdate").focus();
				return false;
			} 
		}
		*/
		if ($("#title").val() == "") {
			alert('제목을 입력해 주세요.');
			$("#title").focus();
			return false;
		}
		
		/*
		var sHTML = oEditors.getById["contents"].getIR();
		if (sHTML == "") {
			alert('내용을 입력해 주세요.');
			$("#contents").focus();
			return false;
		} else {
			oEditors.getById["contents"].exec("UPDATE_CONTENTS_FIELD", []);	// 에디터의 내용이 textarea에 적용됩니다.
		}
		*/
		var shtml = myeditor.getData();
		if(shtml.trim() == ""){
			alert('내용을 입력해 주세요.');
			myeditor.editing.view.focus();
			return false;
		}
		$('#frm').submit();
	}
	
	
</script>
</head>


<body>
<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/header.php"; ?>
<!-- s:warp -->
<div id="warp">
	<div class="contents">
		<h2 class="fl_l"><?=$pageTitle ?> 수정</h2>
		<div class="write">
		<form method="post" name="frm" id="frm" action="<?=getSslCheckUrl($_SERVER['REQUEST_URI'], 'process.php')?>" enctype="multipart/form-data">
			<div class="wr_box">
				<h3>등록정보</h3>
				<table>
					<colgroup>
						<col width="8%">
						<col width="42%">
						<col width="8%">
						<col width="42%">
					</colgroup>
					<tbody>
					<tr>
						<th>작성일</th>
						<td>
							<input type="text" name="registdate" id="registdate"  value="<?=$data['registdate'] ?>" class="dateTime" autocomplete="off"/>
							<span id="CalregistdateIcon" style="height:22px; line-height:22px;vertical-align:middle;">
							<img src="/admin/img/ico_calendar.gif" id="CalregistdateIconImg" style="cursor:pointer;"/>
							</span>
						</td>
						<th>작성자</th>
						<td>
							<input type="text" name="name" id="name"  value="<?=$data['name'] ?>" class="wid200"/>
						</td>
					</tr>
					<tr>
						<th>노출설정</th>
						<td colspan="3">
							<input type="checkbox" name="top" value="1" <?=getChecked(1, $data['top']) ?>/> 탑공지 (상단노출) <span class="h_line"></span>
							<input type="checkbox" name="newicon" value="1" <?=getChecked(1, $data['newicon']) ?>/> 새글 (New 아이콘 노출)
						</td>
					</tr>
					</tbody>
				</table>
			</div>
			<!-- //wr_box -->
			<div class="wr_box">
				<h3>게시글</h3>
				<table>
					<colgroup>
						<col width="8%">
						<col width="*">
					</colgroup>
					<tbody>
					<tr>
						<th>제목</th>
						<td>
							<input type="text" name="title" id="title"  value="<?=$data['title'] ?>" />
						</td>
					</tr>
					<tr>
						<th>내용</th>
						<td>
							<textarea name="contents" id="contents" rows="10"><?=$data['contents'] ?></textarea>
						</td>
					</tr>
					<tr>
						<th>첨부파일</th>
						<td>
							<? if ($data['filename']) { ?>
								<input type="checkbox" name="filename_chk" value="1"/> 기존파일삭제</br/>
								<a href="/lib/download.php?path=<?=$uploadPath?>&vf=<?=$data['filename']?>&af=<?=$data['filename_org']?>" target="_blank"><?=$data[filename_org]?></a><br/>
							<? } ?>
							<input  type="file" name="filename" id="filename" />
						</td>
					</tr>
					
					</tbody>
				</table>
			</div>
			<!-- //wr_box -->
		<input type="hidden" name="cmd" value="edit" />
		<input type="hidden" name="no" value="<?=$data['no'] ?>" />
		<?=$notice->getQueryStringToHidden($_REQUEST) ?>
		</form>
		</div>
		<!-- //write -->
		<div class="btnSet">
			<a href="<?=$notice->getQueryString('index.php', 0, $_REQUEST)?>" class="btn">목록</a>
			<a href="javascript:;" class="btn" onclick="goSave();">저장</a>
		</div>
		<!-- //btnSet -->
	</div>
	<!-- //contents -->
</div>
<!-- e:warp --> 
<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/footer.php"; ?>
</body>
</html>
