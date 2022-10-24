<?session_start();
include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/Popup.class.php";

include "config.php";

$popup = new Popup($pageRows, $tablename, $_REQUEST);
$data = $popup->getData($_REQUEST[no]);
?>
<html>
<head>
<title><?=$data[title]?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<? include $_SERVER['DOCUMENT_ROOT']."/include/headHtml.php" ?>
<? if ($data[type] == 2) { ?>
<script>
$(document).ready(function(){
	window.resizeTo(pop_size.clientWidth+20,pop_size.clientHeight+70);
	$('body').find('img').eq(0).css('max-height','650px');
});
</script>
<? } ?>
</head>

<body leftmargin="0" topmargin="0" onLoad="this.focus();" >
<div id="pop_size" style="width:<?=$data[popup_width]?>px; height:auto;">
	<? if ($data[type] == 2) { ?>
	<!-- 일반 팝업 일때 -->
	<div style="padding:10px; width:<?=$data[popup_width]?>px;" >
		<h2 style="font-size:120%; font-weight:bold; color:#000; style="height:<?=$data[popup_height]?>px; width:<?=$data[popup_width]?>px;"><?=$data[title]?></h2>
		<? if ($data[moviename]) { ?>
		<p style="width:<%=data.getPopup_width()%>px;">
			<?/* <tr>
				<td align=center>
					<SCRIPT type="text/javascript">
					<!--
					tv_adplay_autosize("<?=$uploadPath?><?=$data[moviename]?>", "MoviePlayer");
					//-->
					</SCRIPT>
				</td>
			</tr> */?>
		</p>
		<? } ?>
		<p><?=$data[contents]?></p>
		<? /*
		<? if ($data[filename]) { ?>
		<p><img src="/img/file_icon.gif">&nbsp;<?=$data[filename_org]?>&nbsp;<span class="color2">[<?=getFileSize($data[filesize])?>]</span>&nbsp;
			<a href="/lib/download.php?path=<?=$uploadPath?>&vf=<?=$data['filename']?>&af=<?=$data['filename_org']?>"><img src="/img/file_download.gif"></a>
		</p>
	  	<? } ?>
		*/?>
		<? if ($data[relation_url]) { ?>
		<p style="padding:10px; text-align:center; width:<?=$data[popup_width]?>px;">
			<a href="<?=$data[relation_url]?>" target="_blank"><img src="/img/btn_detail.gif"></a>
		</p>
		<?	} ?>
	</div>
	<? } else { ?>
	<!-- 이미지 팝업 일때 -->
	<? if (!$data[relation_url]) { ?>
	<div><img src="<?=$uploadPath?><?=$data[imagename]?>" alt="<?=$data[image_alt]?>" style="border:0;"></div>
	<?	} else { ?>
	<div><a href="<?=$data[relation_url]?>" target="_blank"><img src="<?=$uploadPath?><?=$data[imagename]?>" alt="<?=$data[image_alt]?>" style="border:0;"></a></div>
	<?	} ?>
	<? } ?>
	<div style="background:#000; color:#fff; padding:5px 10px; vertical-align:middle; width:<?=$data[popup_width]?>px;">
		<input type="checkbox" id="chkbox<?=$data[no]?>" onClick="closeLayer('divPop<?=$data[no]?>', this, '0');"><span style="font-size:12px;">오늘 하루 이 창을 열지 않음</span>
		<a href="javascript:closeLayer('divPop<?=$data[no]?>', getObject('chkbox<?=$data[no]?>'), '0');" class="popup_txt" style="font-size:12px;">[닫기]</a>
	</div>
</div>

</body>
</html>
