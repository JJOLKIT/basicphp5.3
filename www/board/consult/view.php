<? include_once $_SERVER['DOCUMENT_ROOT']."/include/common.php"; ?>
<?
     include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
     include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
     include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
     include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
     include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

     include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/Consult.class.php";

     include "config.php";

     $consult = new Consult($pageRows, $tablename, $_REQUEST);
     $data = ($consult->getData($_REQUEST[no], $userCon));
?>
<?	
	$p = "";
	$sp = 0;
	$spc= 0;
	$root = $_SERVER['DOCUMENT_ROOT'];
	include_once $root."/header.php";
?>
<script>
	function goDelete(){
		<?if(!$loginCheck){?>
			alert('본인글만 삭제 가능합니다.');
		<?}else{?>
			if( '<?=$data[member_fk]?>' != '<?=$_SESSION[member_no]?>' ){
				alert('본인글만 삭제 가능합니다.');
			}else{
				if(confirm('정말 삭제하시겠습니까?')){
					location.href = 'process.php?cmd=delete&no=<?=$data[no]?>';
				}else{
					return false;
				}
			}
		<?}?>
	}

</script>
<div id="sub" class="">
	<div class="size">
		<!-- 여기서부터 게시판--->
          <div class="bbs">
               <div class="view">
                    <div class="title">
                         <dl>
                              <dt><?=$data['title'] ?> </dt>
                              <dd><span class="name">작성자</span><?=escape_html($data['name'])?></dd>
                              <dd><span class="date">날짜</span><?=getYMD($data['registdate'])?></dd>
							  <!--
                              <dd><span class="hit">조회수</span><?=$data[readno]?></dd>
							  -->
                         </dl>
                    </div>
                    <!-- //title---> 
                    <div class="cont"> <?=$data['contents'] ?> </div>
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
                    <? if ($data['state'] == 2) { ?>
                    <div class="answer">
                         <div class="title">
                               <dl>
                                   <dt><span class="answer_ico">답변</span><?=$data['answer_title'] ?> </dt>
                                   <dd><span class="name">작성자</span><?=$data['answer_name']?></dd>
                                   <dd><span class="date">날짜</span><?=getYMD($data['answer_date'])?></dd>
                               </dl>
                         </div>
                         <div class="cont"><?=$data['answer'] ?></div>
                         <div class="link">
                              <? if ($data['answerfilename']) { ?>
                              <dl>
                                   <dt class="file">첨부파일</dt>
                                   <dd><a href="/lib/download.php?path=<?=$uploadPath?>&vf=<?=$data['answerfilename']?>&af=<?=urlencode($data['answerfilename_org'])?>" target="_blank"><?=$data[answerfilename_org]?></a></dd>
                              </dl>
                              <? } ?>
                              <!-- //file--->
                         </div>
                    </div>
                    <!-- //reply---> 
                    <? } ?>
                    <div class="btnSet clear">
                         <div class="fl_l"><a href="<?=$consult->getQueryString("index.php", 0, $_REQUEST) ?>" class="btn">목록으로</a></div>
                         <div class="fl_r"><a href="<?=$consult->getQueryString("edit.php", $data['no'], $_REQUEST) ?>" class="btn">수정</a>
						 <a href="javascript:;" onclick = "goDelete();" class="btn">삭제</a></div>
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

				