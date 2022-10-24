<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";


//Header("Content-type: text/html; charset=utf-8");
$path = $_GET['path'];
$vf = $_GET['vf'];		// 서버에 저장된 실제 파일명
$af = $_GET['af'];		// 다운로드할 파일명


// 레퍼러 주소체크
function checkReferer($referer) {
	$result = true;
	if (CHECK_REFERER) {
		if (!strpos($referer,REFERER_URL)) {
			$result = false;
		}
	}
	return $result;
}

if(checkReferer($_SERVER['HTTP_REFERER'])){

	if(strpos($path, "upload/") === false){
		echo '비정상적인 경로입니다.';
	}else{
		$filepath = $_SERVER['DOCUMENT_ROOT'].$path.$vf;
		$filepath = addslashes($filepath);
		if (!is_file($filepath) || !file_exists($filepath)){
			echo '파일이 존재하지 않습니다';
		}else{
			
			if(strpos($af, 'DBConnection') !== false || strpos($vf, 'DBConnection') !== false ){
				echo '비정상적인 경로입니다.';
				
			}else{


				//$af = iconv("EUC-KR","cp949//IGNORE", $af);
				//$af = urlencode($af);
				//$af = iconv("UTF-8", "CP949", $af);

				function is_ie() {
					if(!isset($_SERVER['HTTP_USER_AGENT']))return false;
					if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false) return true; // IE8
					if(strpos($_SERVER['HTTP_USER_AGENT'], 'Trident') !== false) return true; // IE11
					return false;
				}

				if(is_ie()) {
					$a = urlencode($af);
					$a = iconv("UTF-8","cp949//IGNORE", $a);
					//header("content-type: doesn/matter");
					header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
					header("content-length: ".filesize("$filepath"));
					header("content-disposition: attachment; filename=\"$a\"");
					header("content-transfer-encoding: binary");
					header('Pragma: public');
				} else {
					header("content-type: file/unknown");
					header("content-length: ".filesize("$filepath"));
					header("content-disposition: attachment; filename=\"$af\"");
					header("content-description: php generated data");
					header("pragma: no-cache");
				}
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
			}
		}
	}
}
?>
