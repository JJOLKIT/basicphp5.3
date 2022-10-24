<?session_start();
include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/Popup.class.php";

include $_SERVER['DOCUMENT_ROOT']."/include/popup/config.php";

$popup = new Popup($pageRows, $tablename, $_REQUEST);
$popupmain = $popup->getMainList($_REQUEST);
?>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript" src="/js/function.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.0.0/crypto-js.min.js"></script>
<? 
	$i=0;
	while ($row=mysql_fetch_assoc($popupmain)) { 
?>
<script type="text/javascript">

$( function() {
    $( "#showimage<?=$i?>" ).draggable();
} );
</script>


<?

	$tempContents = $row[contents];
	$detailBtn = "";

	if ($row[relation_url]) {
		$detailBtn = "<img src=\"/img/btn_detail.gif\" alt='상세보기' align=\"absmiddle\" style=\"border:0; margin:2 0 0 0;  text-align:right; cursor:hand;\" onclick=\"window.open('".$row[relation_url]."','_blank','height=600,width=800,top=50,left=50,toolbar=1, directories=1, status=1, menubar=1, scrollbars=1, resizable=1,location=1')\">&nbsp;";
	}

	if ($row[type] == '1') {
		$tempContents = "<img src=\"".$uploadPath.$row[imagename]."\" alt='".$row[image_alt]."' style=\"border:0;\" style=\"cursor:hand;\" ";
		outlog($tempContents);
		if ($row[relation_url]) {
			$tempContents .= "onclick=\"window.open('".$row[relation_url]."','_blank','height=600,width=800,top=50,left=50,toolbar=1, directories=1, status=1, menubar=1, scrollbars=1, resizable=1,location=1')\"";
		}
		outlog($tempContents);
		$tempContents .= ">";
		outlog($tempContents);
		$detailBtn = "";
	}

	if ($row[type] == '0' || $row[type] == '1') {
?>

	<div id="showimage<?=$i?>" >
	<? $border = $row[type]=='1' ? "" : "border:".$row[border_color]." 5px solid; "; ?>

		<div id="divPop<?=$row[no]?>">
			<div id="dragbar<?=$i?>" style="<?=$border?> background:#fff; width:auto; height:auto; overflow:hidden;">
				<div id="popMain<?=$row[no]?>">
					<p><?=$tempContents?></p>
					<p><?=$detailBtn?></p>
				</div>
			</div>
			<div style="background:#000000; color:#fff; vertical-align:middle; text-align:right; padding:3px 10px; box-sizing:border-box;">
				<input type="checkbox" id="chkbox<?=$row[no]?>" onclick="closeLayer('divPop<?=$row[no]?>', this, '1','showimage<?=$i?>');"/><span style="font-size:12px;">오늘 하루 이 창을 열지 않음</span>
				<a href="javascript:closeLayer('showimage<?=$i?>', getObject('chkbox<?=$row[no]?>'), '1','showimage<?=$i?>');" style="color:#fff;font-size:12px;">[닫기]</a>
			</div>
		</div>
	</div>
	<SCRIPT type="text/javascript">
	<!--
	startTime('divPop<?=$row[no]?>', 'popMain<?=$row[no]?>', '<?=$row[area_top]?>', '<?=$row[area_left]?>', '<?=$row[popup_width]?>', '<?=$row[popup_height]?>', '1');
	//-->
	</SCRIPT>
	<?
		if($row['center_yn'] == 1){
			$ml = $row[popup_width] / 2;
			echo "
				<style>
					#showimage".$i." {left:50% ; top:10% ; margin-left:-".$ml."px; z-index:999999; width:".$row[popup_width]."px; height:".$row[popup_height]."px; position:absolute;}
					@media(max-width:768px){
						#showimage".$i."{top:5% !important;}
					}
				</style>
			";
		}else{
			echo "
				<style>
					#showimage".$i." {position:absolute; left:".$row[area_left]."px; top:".$row[area_top]."px; z-index:999999; width:".$row[popup_width]."px; height:".$row[popup_height]."px;}
				</style>

			";
		}

		echo "<style>
				
				#showimage".$i.", #popMain".$row[no]." {height: auto !important;}

				#divPop".$row[no]." {top:initial !important; left:initial !important; width:auto !important; height: auto !important;}
				#divPop".$row[no]." {height: auto !important;}
			@media(max-width:768px){
				#showimage".$i." {max-width:".$row[popup_width]."px; left:5% !important;  width:90% !important; margin-left:0; }
				#showimage".$i." img {max-width:100%; height:auto; }
			}
		</style>";
	?>
<?
	} else {
?>
	<SCRIPT type="text/javascript">

	<!--
	if ( !getCookie("divPop<?=$row[no]?>")) {     
		window.open('/include/popup/popup.php?no=<?=$row[no]?>','divPop<?=$row[no]?>','width=<?=$row[popup_width]?>, height=<?=$row[popup_height]?>, top=<?=$row[area_top]?>, left=<?=$row[area_left]?>,toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no');
	 } 
	//-->
	</SCRIPT>
<?
	}
	$i++;
 } 
?>

