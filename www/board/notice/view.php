<? include_once $_SERVER['DOCUMENT_ROOT']."/include/common.php"; ?>
<?
	include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
	include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
	include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
	include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
	include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

	include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/Notice.class.php";

	include "config.php";

	$notice = new Notice($pageRows, $tablename, $_REQUEST);
	$data = ($notice->getData($_REQUEST[no], $userCon));

?>

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
		<div class="bbs">
			<div class="view">
				<div class="title">
					<dl>
						<dt><?=$data['title']?></dt>
						<dd class="admin"><span class="name">작성자</span><?=escape_html($data['name'])?></dd>
						<dd><span class="date">날짜</span><?=getYMD($data['registdate'])?></dd>
						<dd><span class="hit">조회수</span><?=$data[readno]?></dd>
					</dl>
				</div>
				<!-- //title---> 
				<div class="cont"> <?=$data['contents']?> </div>
				<!-- //cont--->
				<div class="link">
					<? if ($data['filename']) { ?>
					<dl>
						<dt class="file">첨부파일</dt>
						<dd><a href="/lib/download.php?path=<?=$uploadPath?>&vf=<?=$data['filename']?>&af=<?=urlencode($data['filename_org'])?>" target="_blank"><?=$data[filename_org]?></a></dd>
					</dl>
					<? } ?>
					<!-- //file--->
					<?if($data['relation_url']){?>
					<dl>
						<dt class="url">관련링크</dt>
						<dd><a href="<?=$data['relation_url']?>" target="_blank" title="새 창 열림"><?=$data['relation_url']?></a></dd>
					</dl>
					<?}?>
					<!-- //link--->
				</div>
				<? if ($isComment) { ?>
				<? include_once $_SERVER['DOCUMENT_ROOT']."/include/comment/comment.php" ?>
				<? } ?>

				<?
					$rownum = $notice->getRowNum($_REQUEST);
					$next = $notice->getNextRowNum($_REQUEST, $rownum);
					$prev = $notice->getPrevRowNum($_REQUEST, $rownum);

					///////////////////////////////////////////////////////////////////////////
					/*

						이전글 다음글만 prev || next에 걸리도록 
						목록버튼이 안에 들어가면 안나옴.
						주의

					*/
					///////////////////////////////////////////////////////////////////////////
					if($prev || $next){
				?>
				<div>
					<ul class="viewNavi">
						<?if($next){?>
						<li><span class="next">다음글</span><a href="<?=$notice->getQueryString('view.php', $next[next_no], $_REQUEST)?>"><?=$next['next_title']?></a></li>
						<?}?>
						<?if($prev){?>
						<li><span class="prev">이전글</span><a href="<?=$notice->getQueryString('view.php', $prev[prev_no], $_REQUEST)?>"><?=$prev['prev_title']?></a></li>
						<?}?>
					</ul>
				</div>
				<?}?>
				<div class="btnSet clear">
					<div><a href="<?=$notice->getQueryString('index.php', 0, $_REQUEST) ?>" class="btn">목록으로</a></div>
				</div>
			</div>
			<!-- //view---> 
		</div>
		<!-- //여기까지 게시판---> 
	</div>
	<!-- //size--->
</div>
<!-- //sub--->
<?
	include_once $root."/footer.php";
?>