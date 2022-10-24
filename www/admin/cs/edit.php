<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/common.php"; ?>
<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/Cs.class.php";

include $_SERVER['DOCUMENT_ROOT']."/admin/include/loginCheck.php";
include "config.php";

$notice = new Cs($pageRows, $tablename, $_REQUEST);
$data = escape_html($notice->getData($_REQUEST[no], $userCon));
?>
<!doctype html>
<html lang="ko">
<head>
<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/headHtml.php"; ?>
<script>
	var oEditors; // 에디터 객체 담을 곳
	jQuery(window).load(function(){
		//oEditors = setEditor("contents"); // 에디터 셋팅
		
		// 달력
		//initCal({id:"registdate",type:"day",today:"y",timeYN:"y"});
	});
	
	function goSave() {
	
		if ($("#title").val() == "") {
			alert('제목을 입력해 주세요.');
			$("#title").focus();
			return false;
		}
		if($('#contents').val().trim() == ""){
			alert('내용을 입력해 주세요.');
			$("#contents").focus();
			return false;
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
			<h2><?=$pageTitle ?> 수정</h2>
		</div>
		<div class="write">
			<form method="post" name="frm" id="frm" action="<?=getSslCheckUrl($_SERVER['REQUEST_URI'], 'process.php')?>" enctype="multipart/form-data">

				<!-- //wr_box -->
				<div class="wr_box">
					<h3>문의내용</h3>
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
						<tr>
							<th>첨부파일2</th>
							<td>
								<? if ($data['filename2']) { ?>
									<input type="checkbox" name="filename2_chk" value="1"/> 기존파일삭제</br/>
									<a href="/lib/download.php?path=<?=$uploadPath?>&vf=<?=$data['filename2']?>&af=<?=$data['filename2_org']?>" target="_blank"><?=$data[filename2_org]?></a><br/>
								<? } ?>
								<input  type="file" name="filename2" id="filename2" />
							</td>
						</tr>
						<tr>
							<th>첨부파일3</th>
							<td>
								<? if ($data['filename3']) { ?>
									<input type="checkbox" name="filename3_chk" value="1"/> 기존파일삭제</br/>
									<a href="/lib/download.php?path=<?=$uploadPath?>&vf=<?=$data['filename3']?>&af=<?=$data['filename3_org']?>" target="_blank"><?=$data[filename3_org]?></a><br/>
								<? } ?>
								<input  type="file" name="filename3" id="filename3" />
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
