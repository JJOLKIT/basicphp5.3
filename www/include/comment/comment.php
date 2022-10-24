<?php
include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/Comment.class.php";

$comment = new Comment(9999, $tablename, $_REQUEST);
$result = $comment->getList($_REQUEST);

?>
<script>
	function goCommentSave() {
		<? if ($loginCheck) { ?>
		if($("#name").val()=="") {
			alert('이름을 입력해 주세요.');
			$("#name").focus();
			return false;
		}
		
		if($("#contents").val()=="") {
			alert('내용을 입력해 주세요.');
			$("#contents").focus();
			return false;
		}
		$("#comment_frm").submit();
		<? } else { ?>
		alert("댓글은 로그인 후 작성가능합니다.");
		return false;
		<? } ?>
	}
	
	function goCommentDelete(obj) {
		var str = "댓글을 삭제 하시겠습니까?";
		if(confirm(str)) {
			$("#no").val($(obj).attr("id"));
			$("#delete_frm").submit();
		}else{
			return false;
		}
	}
	
	var pagex;
	var pagey;
	
	
$(document).ready(function(){
	
	$(".bt_delete").click(function(e){
		goCommentDelete($(this));	
	});
		
});
</script>
<div class="reply">
	<form name="delete_frm" id="delete_frm" action="/include/comment/process.php" method="post" >
		<div class="recommend">
			<p class="tit"><span class="number"><?=mysql_num_rows($result)?></span>개의 댓글이 있습니다.</p>
			<ul class="re_list">
			<? if (mysql_num_rows($result) == 0) { ?>
				<li>등록된 댓글이 없습니다.</li>
			<? } else { ?>
			<? while ($co_row = mysql_fetch_assoc($result)) { ?>
				<li>
					<dl>
						<dt><?=$co_row['name']?> <span class="date"><?=getYMD($co_row['registdate'])?></span>
							<? if ($co_row['member_fk'] == $_SESSION['member_no']) { ?>
							<input type="button" class="bt_delete" id="<?=$co_row[no]?>" title="삭제">
							<? } ?>
						</dt>
						<dd><?=nl2br($co_row['contents'])?></dd>
					</dl>
				</li>
			<? }
			}
			?>
			</ul>
		</div>
		<input type="hidden" name="comment_cmd" value="r_delete"/>
		<input type="hidden" name="parent_fk" value="<?=$comment->parent_fk?>"/>
		<input type="hidden" name="no" id="no"  value=""/>
		<input type="hidden" name="tablename"  value="<?=$tablename?>"/>
		<input type="hidden" name="url"	value="<?=$_SERVER["REQUEST_URI"]?>"/>
	</form>
	<? if ($loginCheck) { ?>
	<form name="comment_frm" id="comment_frm" action="/include/comment/process.php" method="post">
		<div class="reply_write">
			<div class="tit_area">
				<span class="tit">댓글을 남겨주세요!</span>
			</div>
			<div class="write_area">
				<div class="input"><input type="text" name="name" id="name" value="<?=$_SESSION['member_name']?>" /></div>
				<div class="textarea">
					<textarea name="contents" id="contents" maxlength="200" onclick=""></textarea>
				</div>
				<div class="bt_box">
					<ul>
						<li><a href="javascript:;" onclick="goCommentSave();">댓글작성</a></li>
					</ul>
				</div>
			</div>
		</div>
		<input type="hidden" name="comment_cmd" value="r_write"/>
		<input type="hidden" name="parent_fk" value="<?=$comment->parent_fk?>"/>
		<input type="hidden" name="member_fk"  value="<?=$_SESSION[member_no]?>"/>
		<input type="hidden" name="tablename"  value="<?=$tablename?>"/>
		<input type="hidden" name="url" value="<?=$_SERVER["REQUEST_URI"]?>"/>
	</form>
	<? } ?>
</div>