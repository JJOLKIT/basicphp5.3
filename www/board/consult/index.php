<? include_once $_SERVER['DOCUMENT_ROOT']."/include/common.php"; ?>
<?
     include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
     include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
     include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
     include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
     include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

     include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/Consult.class.php";

     include "config.php";

     $_REQUEST['sisspam'] = 1;
     $consult = new Consult($pageRows, $tablename, $_REQUEST);
     $rowPageCount = $consult->getCount($_REQUEST);
     $result = ($consult->getList($_REQUEST));
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
               <table class="list">
                    <caption>상담 목록</caption>
                    <colgroup>
                         <col width="80px" />
                         <col width="*" />
                         <col width="120px" />
                         <col width="120px" />
                         <col width="80px" />
                    </colgroup>
                    <thead>
                         <tr>
                              <th>번호</th>
                              <th>제목</th>
                              <th>작성자</th>
                              <th>작성일</th>
                              <th>답변</th>
                         </tr>
                    </thead>
                    <tbody>
                         <tbody>
                         <? if ($rowPageCount[0] == 0) { ?>
                              <tr>
                                   <td colspan="5" align="center">등록된 상담이 없습니다.</td>
                              </tr>
                         <?
                               } else {
                                   $topClass = "";
                                   $i = 0;
                                   while ($row=mysql_fetch_assoc($result)) {
									   $row = escape_html($row);
                                        $targetUrl = "style='cursor:pointer;' onclick=\"location.href='".$consult->getQueryString('view.php', $row[no], $_REQUEST)."'\"";
                         ?>
                              <tr <?=$targetUrl ?>>
                                   <td class="no"><?=$rowPageCount[0] - (($consult->reqPageNo-1)*$pageRows) - $i?></td>
                                   <td class="txt_l title"><?=$row['title'] ?></td>
                                   <td class="name"><?=$row['name']?></td>
                                   <td class="date"><?=getYMD($row['registdate']) ?></td>
                                   <td class="state">
                                   <? if ($row['state'] == 1) { ?>
                                        <span class="waiting"><b>답변</b>대기</span>
                                   <? } else if($row['state'] == 2) { ?>
                                        <span class="complete"><b>답변</b>완료</span>
                                   <? } ?>
                                   </td>
                              </tr>
                         <?
                                   $i++;
                                   }
                               }
                         ?>
                         </tbody>
                    </tbody>
               </table>
               <div class="btnSet clear">
                    <div class="fl_r"><a href="write.php" class="btn">글쓰기</a></div>
               </div>
               <div class="pagenate clear">
                    <?=pageList($consult->reqPageNo, $rowPageCount[1], $consult->getQueryString('index.php', 0, $_REQUEST))?>
               </div>
               <!-- //pagenate -->
               <div class="bbsSearch">
                    <form method="get" name="searchForm" id="searchForm" action="index.php">
                         <span class="srchSelect">
                             <select id="stype" name="stype" class="dSelect" title="검색분류 선택">
                                 <option value="all" <?=getSelected("all", $_REQUEST['stype']) ?>>전체</option>
                                 <option value="title" <?=getSelected("title", $_REQUEST['stype']) ?>>제목</option>
                                 <option value="contents" <?=getSelected("contents", $_REQUEST['stype']) ?>>내용</option>
                                 <option value="name" <?=getSelected("name", $_REQUEST['stype']) ?>>작성자</option>
                             </select>
                         </span>
                         <span class="searchWord">
                             <input type="text" id="sval" name="sval" value="<?=$_REQUEST['sval'] ?>" title="검색어 입력" onKeypress="">
                             <input type="button" id="" value="검색" title="검색" onclick="goSearch();">
                         </span>
                     </form>
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
