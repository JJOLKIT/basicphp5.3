<?session_start();
include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/Comment.class.php";

$comment = new Comment(9999, $tablename, $_REQUEST);
$result = $comment->getList($_REQUEST);

?>
<script>
	function goComment() {
		
		if($("#name").val()=="" |$("#name").val()=="이름") {
			alert('이름을 입력해 주세요.');
			$("#name").focus();
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
	<form name="delete_frm" id="delete_frm" action="/admin/board/comment/process.php" method="post">
	<? if (mysql_num_rows($result) == 0) { ?>
		<dl>
			<dd class="bbsno" style="text-align:center;">
				등록된 댓글이 없습니다.
			</dd>
		</dl>
	<? } else { ?>
	<? while ($co_row = mysql_fetch_assoc($result)) { ?>
		<dl>
			<dt><strong><?=$co_row['name']?></strong> <?=$co_row['registdate']?></dt>
			<dd><?=nl2br($co_row['contents'])?>
				<span class="reEdit">
					<strong class="btn_in inbtn"><input type="submit" class="r_delete" id="<?=$co_row['no']?>" value="삭제"/></strong>
				</span>
			</dd>
		</dl>
	<? }
	}
	?>
		<input type="hidden" name="comment_cmd" id="comment_cmd" value="r_delete"/>
		<input type="hidden" name="parent_fk" id="parent_fk" value="<?=$comment->parent_fk?>"/>
		<input type="hidden" name="no" id="no" value=""/>
		<input type="hidden" name="tablename" id="tablename" value="<?=$tablename?>"/>
		<input type="hidden" name="url"	id="url" value="<?=$_SERVER["REQUEST_URI"]?>"/>
	</form>
	<div class="rego">
		<form name="comment_frm" id="comment_frm" action="/admin/board/comment/process.php" method="post" onsubmit="return goComment();">
			<dl>
				<dd>
					<textarea class="focus_zone" name="contents" id="contents" title="내용을 입력해주세요"></textarea>
					<a class="btn hoverbg" onclick="$('#comment_frm').submit();"><span class="material-icons">edit_note</span>댓글입력</a>
				</dd>
			</dl>
			<input type="hidden" name="comment_cmd" id="comment_cmd" value="r_write"/>
			<input type="hidden" name="parent_fk" id="parent_fk" value="<?=$comment->parent_fk?>"/>
			<input type="hidden" name="tablename" id="tablename" value="<?=$tablename?>"/>
			<input type="hidden" name="password_temp" id="password_temp" value=""/>
			<input type="hidden" name="name" id="name" value="<?=$_SESSION['admin_name'] ?>"/>
			<input type="hidden" name="url"	id="url" value="<?=$_SERVER["REQUEST_URI"]?>"/>
		</form>
	</div>
	<!-- //rego -->
</div>