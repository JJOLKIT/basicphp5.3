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
$data = $notice->getData($_REQUEST[no], $userCon);
?>
<!doctype html>
<html lang="ko">
<head>
<? include_once $_SERVER['DOCUMENT_ROOT']."/include/headHtml.php"; ?>
</head>
<body>
<div class="program">
	<h2 class="title">동영상게시판</h2>
				<!--s:내용-->
					<div class="bbs">
						<div class="view">
							<div class="title">
								<dl>
									<dt><?=$data['title']?></dt>
									<dd class="date"><?=getYMD($data['registdate'])?></dd>
								</dl>
							</div>
							<!-- //title---> 
							<div class="cont"> <?=$data['contents']?>
							<? if ($data[moviename]) { ?>
							<link href="http://vjs.zencdn.net/5.4.4/video-js.css" rel="stylesheet">
							<script src="http://vjs.zencdn.net/5.4.4/video.js"></script>
							<video class="video-js vjs-default-skin" controls preload="auto" width="auto" height="auto" data-setup="{}">
								<source src='<?=$uploadPath?><?=$data[moviename]?>'  />
							</video>
							<? } ?>
							</div>
							<!-- //cont--->
							<? if ($isComment) { ?>
							<? include_once $_SERVER['DOCUMENT_ROOT']."/include/comment/comment.php" ?>
							<? } ?>
							<div class="btnSet clear">
								<a href="<?=$notice->getQueryString('index.php', 0, $_REQUEST)?>" class="btn">목록으로</a>
							</div>
						</div>
						<!-- //view---> 
					</div>
				<!--e:내용-->
</div>
</body>
</html>
