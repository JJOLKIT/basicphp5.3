<?
$callback_func = $_REQUEST['callback_func'];
$bNewLine = $_REQUEST['bNewLine'];
$errstr = $_REQUEST['errstr'];
$fileCount = $_REQUEST['fileCnt'];

$sFileNames = $_REQUEST['sFileName'];
$sFileURLs = $_REQUEST['sFileURL'];
$alts = $_REQUEST['alt'];
$widths = $_REQUEST['width'];

$oResult = '';
if ($fileCount > 0) {

	for ($i=0; $i<$fileCount; $i++) {
	    //echo "oResult1".$oResult."\r\n";
		$oResult = $oResult."{callback_func:\"".$callback_func."\", bNewLine:".$bNewLine.",";
		//echo "oResult2".$oResult."\r\n";
		// 이미지 실제이름과 변환이름 받기
		$oResult = $oResult."sFileName:\"".$sFileNames[$i]."\", sFileURL:\"".$sFileURLs[$i]."\"";
		//echo "oResult3".$oResult."\r\n";
		// 이미지 설명 받아오기
		if ($alts) {
			$oResult = $oResult.",alt:\"".$alts[$i]."\"";
		}
		//echo "oResult4".$oResult."\r\n";
		// 마지막 배열값 닫기
		$oResult = $i < $fileCount-1 ? $oResult."}," : $oResult."}";
		//echo "oResult5".$oResult."\r\n";
	}
	
} else {
	$oResult = $oResult."{errstr : \"error\", callback_func:\"".$callback_func."\"}";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="ko">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>FileUploader Callback</title>
    <script type="text/javascript">
    var oResult = [<?=$oResult?>];
    
		if ((oResult[0].errstr || '').length) { // on error
           (parent.jindo.FileUploader._oCallback[oResult[0].callback_func+'_error'])(oResult);
        } else {
	       (parent.jindo.FileUploader._oCallback[oResult[0].callback_func+'_success'])(oResult);
	   	}
    </script>
</head>
<body>

</body>
</html>