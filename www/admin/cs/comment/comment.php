<?session_start();
include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/CommentBO.class.php";

$comment = new CommentBO(9999, $tablename, $_REQUEST);
$result = $comment->getList($_REQUEST);

?>
<script>
	function goComment() {
		
		if($("input#name").val()=="" |$("input#name").val()=="이름") {
			alert('이름을 입력해 주세요.');
			$("input#name").focus();
			return false;
		}
		
		if($("#contents").val()=="") {
			alert('내용을 입력해 주세요.');
			$("#contents").focus();
			return false;
		}
		return true;
	}
	
	function goDeleteRe(obj) {
		var str = "댓글을 삭제 하시겠습니까?";
		if(confirm(str)) {
			$("#no").val($(obj).attr("id"));
			return true;
		}else{
			return false;
		}
		$("#delete_frm").submit();
	}
	
	
$(document).ready(function(){
	
	$(".r_delete").click(function(){
		goDeleteRe($(this));	
	});
	
});
</script>

<div class="reple">
	<form name="delete_frm" id="delete_frm" action="/admin/cs/comment/process.php" method="post">
	<? if (mysql_num_rows($result) == 0) { ?>
		<dl>
			<dd class="bbsno" style="text-align:center;">
				등록된 댓글이 없습니다.
			</dd>
		</dl>
	<? } else { ?>
	<? while ($co_row = mysql_fetch_assoc($result)) { ?>
		<dl>
			<dt>
				<?if($co_row['member_fk'] > 0){?>
				<strong id="name" class="color"><?=$co_row['name']?></strong> 
				<?}else{?>
				<strong id="name2"><?=$co_row['name']?></strong>
				<?}?>
				<?=$co_row['registdate']?>
			</dt>
			<dd><?=nl2br($co_row['contents'])?>
				<?
					if($co_row['isbo'] == 0 && $_SESSION['admin_no'] == $co_row['member_fk']){
				?>
				<span class="reEdit">
					<strong class="btn_in inbtn"><input type="submit" class="r_delete hoverbg" id="<?=$co_row['no']?>" value="삭제"/></strong>
				</span>
				<?}?>
			</dd>
			<?
				if($co_row['filename']){
			?>
			<dd>
				첨부파일 : <a href="https://bo.sanggong.co.kr/upload/cs/<?=$co_row['filename']?>" download target="_blank"><?=$co_row[filename_org]?></a>
			</dd>
			<?}?>
			<?if($co_row['filename2']){?>
			<dd>
				첨부파일2 : <a href="https://bo.sanggong.co.kr/upload/cs/<?=$co_row['filename2']?>" download  target="_blank"><?=$co_row[filename2_org]?></a>
			</dd>
			<?}?>
			<?if($co_row['filename3']){?>
			<dd>
				첨부파일3 : <a href="https://bo.sanggong.co.kr/upload/cs/<?=$co_row['filename3']?>" download  target="_blank"><?=$co_row[filename3_org]?></a>
			</dd>
			<?}?>
		</dl>
	<? }
	}
	?>
		<input type="hidden" name="comment_cmd" id="comment_cmd" value="r_delete"/>
		<input type="hidden" name="parent_fk" id="parent_fk" value="<?=$comment->parent_fk?>"/>
		<input type="hidden" name="no" id="no" value=""/>
		<input type="hidden" name="tablename" id="tablename" value="<?=$tablename?>"/>
		<input type="hidden" name="url"	id="url" value="<?$a = explode("?p", $_SERVER["REQUEST_URI"]); echo $a[0];?>"/>
	</form>
	<div class="rego">
		<form name="comment_frm" id="comment_frm" action="/admin/cs/comment/process.php" method="post" onsubmit="return goComment();">
			<dl>
				<dd>
					<textarea class="focus_zone" name="contents" id="contents" title="내용을 입력해주세요"></textarea>
					<a class="btn hoverbg" onclick="$('#comment_frm').submit();"><span class="material-icons">edit_note</span> 댓글입력</a>
				</dd>
			</dl>
			<input type="hidden" name="comment_cmd" id="comment_cmd" value="r_write"/>
			<input type="hidden" name="parent_fk" id="parent_fk" value="<?=$comment->parent_fk?>"/>
			<input type="hidden" name="tablename" id="tablename" value="<?=$tablename?>"/>
			<input type="hidden" name="password_temp" id="password_temp" value=""/>
			<input type="hidden" name="name" id="name" value="<?=COMPANY_NAME ?>"/>
			<input type="hidden" name="url"	id="url" value="<? $a = explode("?p", $_SERVER["REQUEST_URI"]); echo $a[0];?>"/>
			<input type="hidden" name="member_fk" value="<?=$_SESSION['admin_no']?>"/>
		</form>
	</div>
	<!-- //rego -->
</div>