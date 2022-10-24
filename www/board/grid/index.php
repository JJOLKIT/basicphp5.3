<? include_once $_SERVER['DOCUMENT_ROOT']."/include/common.php"; ?>
<?
	include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
	include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
	include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
	include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
	include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

	include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/Gallery.class.php";

	include "config.php";

	$notice = new Gallery($pageRows, $tablename, $_REQUEST);
	$rowPageCount = $notice->getCount($_REQUEST);
	$result = $notice->getList($_REQUEST);
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
            <link rel="stylesheet" type="text/css" href="inc/component.css" />
            <script src="inc/modernizr.custom.js"></script>
            <div id="grid-gallery" class="grid-gallery">
                <section class="grid-wrap">
                    <ul class="grid">
                        <!--<li class="grid-sizer"></li> for Masonry column width -->
                        <? if($rowPageCount[0] == 0) { ?>
                            
                                <figure style="cursor:default;">
                                    등록된 글이 없습니다.
                                </figure>
                           
                        <? } else { 
                            $targetUrl = "";
                            while($row = mysql_fetch_assoc($result)){
                                $targetUrl = "onclick=\"location.href='".$notice->getQueryString('view.php', $row[no], $_REQUEST)."'\"";
                        ?>
                        <li <?=$targetUrl?>>
                            <figure>
                                <img src="<?=$uploadPath.$row['imagename']?>" alt="img01"/>
                                <figcaption>
                                   <div class="title"><?=$row['title']?></div>
                                   <div class="contxt"><?=utf8_strcut($row['contents'],90,'...');?></div>
                                   <div class="info clear">
                                        <p><span class="name">작성자</span><b><?=$row['name']?></b></p>
                                        <p><span class="date">날짜</span><b><?=getYmd($row['registdate']);?></b></p>
                                        <p><span class="hit">조회수</span><b><?=$row['readno']?></b></p>
                                   </div>
                                </figcaption>
                            </figure>
                        </li>
                        <?}
                        }
                        ?>
                    </ul>
                </section><!-- // grid-wrap -->
            </div><!-- // grid-gallery -->
            <div class="pagenate clear">
                <?=pageList($notice->reqPageNo, $rowPageCount[1], $notice->getQueryString('index.php', 0, $_REQUEST))?>
            </div>
            <!-- //pagenate -->
            <div class="bbsSearch">
                <form method="get" name="searchForm" id="searchForm" action="index.php">
                    <span class="select srchSelect">
                        <select id="stype" name="stype" class="dSelect" title="검색분류 선택">
                            <option value="all" <?=getSelected("all", $_REQUEST['stype']) ?>>전체</option>
                            <option value="title" <?=getSelected("title", $_REQUEST['stype']) ?>>제목</option>
                            <option value="contents" <?=getSelected("contents", $_REQUEST['stype']) ?>>내용</option>
                        </select>
                    </span>
                    <span class="searchWord">
                        <input type="text" id="sval" name="sval" value="<?=$_REQUEST['sval'] ?>" title="검색어 입력" onKeypress="">
                        <input type="button" id="" value="검색" title="검색" onclick="goSearch();">
                    </span>
                    <input type="hidden" name="sclass_fk" value="<?=$_REQUEST['sclass_fk']?>"/>
                </form>
            </div>
        </div>
        <script src="inc/imagesloaded.pkgd.min.js"></script>
        <script type="text/javascript" src="inc/masonry.pkg.min.js"></script>
        <script src="inc/classie.js"></script>
        <script src="inc/cbpGridGallery.js"></script>
        <script>
            new CBPGridGallery( document.getElementById( 'grid-gallery' ) );
        </script>
		<!-- //여기까지 게시판--->
	</div>
	<!-- //size--->
</div>
<!-- //sub--->
<?
	include_once $root."/footer.php";
?>

