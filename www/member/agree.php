<? include_once $_SERVER['DOCUMENT_ROOT']."/include/common.php"; ?>
<?	
	$p = "";
	$sp = 0;
	$spc= 0;
	$root = $_SERVER['DOCUMENT_ROOT'];
	include_once $root."/header.php";
?>
<div id="sub" class="">
	<div class="size">
		<!-- 여기서부터 게시판--->
		<div class="member">
               <div class="agree">
				 <? include_once $_SERVER['DOCUMENT_ROOT']."/member/agree.html"; ?>
               </div>
          </div>
          <!-- //여기까지 게시판--->
	</div>
	<!-- //size--->
</div>
<!-- //sub--->
<?
	include_once $root."/footer.php";
?>