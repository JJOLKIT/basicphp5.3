<?
Header("Content-type: text/html; charset=utf-8");
$path = $_GET['path'];
$vf = $_GET['vf'];		// 서버에 저장된 실제 파일명
$af = $_GET['af'];		// 다운로드할 파일명

$filepath = $_SERVER['DOCUMENT_ROOT'].$path.$vf;
$filepath = addslashes($filepath);
if (!is_file($filepath) || !file_exists($filepath))
    echo 'alert("파일이 존재하지 않습니다.")';

//$af = iconv("EUC-KR","cp949//IGNORE", $af);
//$af = urlencode($af);
//$af = iconv("UTF-8", "CP949", $af);

if(preg_match("/msie/i", $_SERVER[HTTP_USER_AGENT]) && preg_match("/5\.5/", $_SERVER[HTTP_USER_AGENT])) {
	
    header("content-type: doesn/matter");
    header("content-length: ".filesize("$filepath"));
    header("content-disposition: attachment; filename=\"$af\"");
    header("content-transfer-encoding: binary");
} else {
    header("content-type: file/unknown");
    header("content-length: ".filesize("$filepath"));
    header("content-disposition: attachment; filename=\"$af\"");
    header("content-description: php generated data");
}
header("pragma: no-cache");
header("expires: 0");
flush();

$fp = fopen("$filepath", "rb");

// 4.00 대체
// 서버부하를 줄이려면 print 나 echo 또는 while 문을 이용한 방법보다는 이방법이...
//if (!fpassthru($fp)) {
//    fclose($fp);
//}

$download_rate = 10;

while(!feof($fp)) {
    //echo fread($fp, 100*1024);
    /*
    echo fread($fp, 100*1024);
    flush();
    */

    print fread($fp, round($download_rate * 1024));
    flush();
    usleep(1000);
}
fclose ($fp);
flush();
?>
