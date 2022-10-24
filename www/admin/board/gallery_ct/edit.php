<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/common.php"; ?>
<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/GalleryCt.class.php";

include $_SERVER['DOCUMENT_ROOT']."/admin/include/loginCheck.php";
include "config.php";

$notice = new GalleryCt($pageRows, $tablename, $category_tablename, $_REQUEST);

$data = $notice->getData($_REQUEST[no], $userCon);
$result = $notice->getCategoryList($_REQUEST);
?>
<!doctype html>
<html lang="ko">
<head>
<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/headHtml.php"; ?>
<script>
var oEditors; // 에디터 객체 담을 곳
jQuery(window).load(function(){
	oEditors = setEditor("contents"); // 에디터 셋팅
	
	// 달력
	initCal({id:"registdate",type:"day",today:"y",timeYN:"y"});
});
	
	function goSave() {
		if($('#category').val() == ""){
			alert('카테고리를 선택해 주세요.');
			$('#category').focus();
			return false;
		}
		if ($("#title").val() == "") {
			alert('제목을 입력해 주세요.');
			$("#title").focus();
			return false;
		}
		var sHTML = oEditors.getById["contents"].getIR();
		if (sHTML == "") {
			alert('내용을 입력해 주세요.');
			$("#contents").focus();
			return false;
		} else {
			oEditors.getById["contents"].exec("UPDATE_CONTENTS_FIELD", []);	// 에디터의 내용이 textarea에 적용됩니다.
		}
		$('#frm').submit();
	}
	
</script>
</head>


<body>
<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/header.php"; ?>
<!-- s:warp -->
	<div class="contWrap">
		<div class="titWrap">
			<h2><?=$pageTitle?> 수정</h2>
		</div>
		<div class="write">
		<form method="post" name="frm" id="frm" action="<?=getSslCheckUrl($_SERVER['REQUEST_URI'], 'process.php')?>" enctype="multipart/form-data">
			<div class="wr_box">
				<h3>등록정보</h3>
				<table>
					<colgroup>
						<col width="8%">
						<col width="*">
					</colgroup>
					<tbody>
					<tr>
						<th>카테고리</th>
						<td>
							<span class="select">
								<select name="category" id="category">
									<option value="">선택</option>
									<?while($row = mysql_fetch_assoc($result)){?>
									<option value="<?=$row['no']?>" <?=getSelected($row['no'], $data['category'])?>><?=$row['title']?></option>
									<?}?>
								</select>
							</span>
						</td>
					</tr>
					<tr>
						<th>작성자</th>
						<td>
							<input type="text" name="name" id="name"  value="<?=$data['name'] ?>" class="wid200"/>
						</td>
					</tr>
					<tr>
						<th>제목</th>
						<td>
							<input type="text" name="title" id="title"  value="<?=$data['title']?>" />
						</td>
					</tr>
					<tr>
						<th>내용</th>
						<td>
							<textarea name="contents" id="contents" rows="10"><?=$data['contents'] ?></textarea>
						</td>
					</tr>
					<tr>
						<th>노출설정</th>
						<td>
							<input type="checkbox" name="top" value="1" <?=getChecked(1, $data['top']) ?>/> 탑공지 (상단노출) 
						</td>
					</tr>
					<tr>
						<th>이미지</th>
						<td>
							<? if ($data['imagename']) { ?>
								<input type="checkbox" name="imagename_chk" value="1"/> 기존파일삭제</br/>
								<a href="/lib/download.php?path=<?=$uploadPath?>&vf=<?=$data['imagename']?>&af=<?=$data['imagename_org']?>" target="_blank"><?=$data[imagename_org]?></a><br/>
							<? } ?>
							<input  type="file" name="imagename" id="imagename" />
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
					<tr>
						<th>관련링크</th>
						<td>
							<input type="text" name="relation_url" id="relation_url"  value="<?=$data['relation_url']?>" />
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
		<div class="btnSet clear">
			<a href="javascript:;" class="btn hoverbg save" onclick="goSave();">저장</a>
			<a href="<?=$notice->getQueryString('index.php', 0, $_REQUEST)?>" class="btn hoverbg">취소</a>
		</div>
		<!-- //btnSet -->
	</div>
	<!-- //contents -->
</div>
<!-- e:warp --> 
<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/footer.php"; ?>
</body>
</html>
