<?
/*
기존 com.vizensoft.util.Function.java
by withsky 2014.11.18

*/

include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";

// 메시지를 alert하고 rurl로 이동
function returnURLMsg($rurl = "", $msg = "") {
	$return = 
		"<script>
			alert('".$msg."');
			document.location.href='".$rurl."';
		</script>";
	return $return;
}

// 메시지를 alert하고 rurl로 이동
function returnURLMsgRefresh($rurl = "", $msg = "") {
	$return = 
		"<script>
			alert('".$msg."');
			opener.location.reload();
			document.location.href='".$rurl."';
		</script>";
	return $return;
}

// rurl로 이동
function returnURL($rurl = "") {
	$return = 
		"<script>
			document.location.href='".$rurl."';
		</script>";
	return $return;
}

// rurl로 이동 후 창을 닫는다.
function returnURLClose($rurl = "") {
	$return = 
		"<script>
			document.location.href='".$rurl."';
			window.close();
		</script>";
	return $return;
} 

// 메시지 alert, rurl로 이동 후 창을 닫는다.
function returnURLCloseMsg($rurl = "", $msg = "") {
	$return = 
		"<script>
			alert('".$msg."');
			document.location.href='".$rurl."';
			window.close();
		</script>";
	return $return;
}

// 메시지 alert 이전페이지로 이동
function returnHistory($msg = "") {
	$return = 
		"<script>
			alert('".$msg."');
			history.back();
		</script>";
	return $return;
}

// 메세지를 alert하고 opener를 reload한 후 창을 닫는다.
function popupCloseRefresh($msg = "") {
	$return = 
		"<script>
			alert('".$msg."');
			opener.location.reload();
			window.close();
		</script>";
	return $return;
}

// 메시지를 확인하면 rurl로 이동, 취소하면 이전페이지로 이동
function returnConfirm($rurl="", $msg="") {
	$return = 
		"<script>
			sure=confirm('".$msg."');
			if (sure)
				location.href='".$rurl."';
			else
				history.back();
		</script>";
	return $return;
}

// 메시지를 확인하면 rurl로 이동, 취소하면 burl로 이동
function returnUrlConfirm($rurl="", $burl="", $msg="") {
	$return = 
		"<script>
			sure=confirm('".$msg."');
			if (sure)
				location.href='".$rurl."';
			else
				location.href='".$burl."';
		</script>";
	return $return;
}

// 파일사이즈를 계산해서 알맞은 단위로 변경한다.
function getFileSize($fileSize = 0) {
	$fSize = "";
	if ($fileSize > 1024 && ($fileSize < 1024*1024)) {
		$fSize = round(($fileSize/1024),2)."KB";
	} else if ($fileSize >= 1024*1024) {
		$fSize = round(($fileSize/(1024*1024)),2)."MB";
	} else {
		$fSize = $fileSize."Bytes";
	}
	return $fSize;
}

// checkbox 체크여부
function getChecked($s, $w) {
	$r = "";
	if ($w == '') $w = -1;	// 빈문자열인 경우 0과 같으므로 검색조건 selectbox 문제로 강제로 -1로 변경
	if ($s == $w) $r = "checked";
	return $r;
}

// selectbox 체크여부
function getSelected($s, $w) {
	$r = "";
	if ($w == '') $w = -1;	// 빈문자열인 경우 0과 같으므로 검색조건 selectbox 문제로 강제로 -1로 변경
	if ($s == $w) $r = "selected";
	return $r;
}

// 이미지사이즈 비교
// 실제 이미지 width값과 비교해서 작을 경우 reWidth값으로 리턴
function getImgReSize($img = "", $reWidth=0) {
	$r = 0;
	try {
		$info = getimagesize($_SERVER['DOCUMENT_ROOT'].$img);
		$size = substr($info[3], strpos($info[3], "\"")+1);
		$size = substr($size, 0, strpos($size, "\""));
		if ($size > $reWidth) {
			$r = $reWidth;
		} else {
			$r = $size;
		}
	} catch (Exception $e) {
		$r = 0;
	}

	return $r;
}

// jquery ajax로 form submit
function scriptAjaxSubmit($target="", $formName="", $msg="", $href="") {
	$rHtml = " $.ajax({
				type: \"POST\",
				url: \"".$target."\",
				async:false,
				data:$(\"".$formName."\").serialize(),
				dataType:\"html\",
				success: function(html){
				result = html.trim();
				alert(\"".$msg."\");
				location.href = '".$href."';
				},
				error : function(request, status, error) {
					alert(\"code : \"+request.status+\"message : \"+request.responseText);
				}
				}); ";
	return $rHtml;
}

// SSL 사용여부 체크
function getSslCheckUrl($request_uri="", $page="") {

	$request_uri = substr($request_uri, 0, strrpos($request_uri, '/'));
	$url = COMPANY_URL;
	if (SSL_USE) $url = COMPANY_SSL_URL;

	$returnUrl = $url.$request_uri.'/'.$page;

	return $returnUrl;
}

// SSL 사용여부 체크 
// 심볼릭 링크 lib 가 모바일과 데스크탑경로가 같을 경우
// COMPANY_URL 을 
function getSslCheckUrl2($request_uri="", $page="") {

	$request_uri = substr($request_uri, 0, strrpos($request_uri, '/'));

	if( strlen(stristr($_SERVER["HTTP_HOST"], "m.ndental") ) ){
//	if( strlen(stristr($request_uri, "m.ndental") ) ){
		$url = COMPANY_M_URL;
	}else{
		$url = COMPANY_URL;
	}
	if (SSL_USE) $url = COMPANY_SSL_URL;

	$returnUrl = $url.$request_uri.'/'.$page;

	return $returnUrl;
}

// 페이지카운트
function getPageCount($pageRows=0, $totalCount=0) {
	$pageCount = $totalCount / $pageRows;
	if ($totalCount % $pageRows > 0) $pageCount++;
	return (int)$pageCount;
}

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

// https 주소 제거
function getRemoviSslUrl($request_uri='', $page='') {

	$request_uri = substr($request_uri, 0, strrpos($request_uri, '/'));
	$url = COMPANY_URL;
	
	$returnUrl = $url.$request_uri.'/'.$page;

	return $returnUrl;
}

// 파일명 변환 밀리세컨초_인코딩%제외
function getRandFileName($fileName='') {
	// 확장자가 실행파일인 경우 확장자에 -x를 붙임(웹실행방지 보안을 위해)
	$fileName = preg_replace("/\.(php|phtm|htm|cgi|pl|exe|jsp|asp|inc|cab|js)/i", "$0-x", $fileName);

	// 랜덤문자 3개 생성
	$chars_array = array_merge(range(0,9), range('a','z'), range('A','Z'));
	$shuffle = implode("", $chars_array);
	$rand_char = "";
	for ($i=0; $i<3; $i++) {
		$rand_char .= $shuffle[mt_rand(0,strlen($shuffle))];
	}
	$result = round(microtime(true))."_".$rand_char."_".str_replace('%', '', urlencode(str_replace(' ', '_', $fileName)));
	return $result;
}

// newiconf 표기여부 체크
function checkNewIcon($registdate='', $newicon='', $day=0) {
	$img = false;
	$r = (microtime(true)-strtotime($registdate))/60/60/24;
	if ($newicon == '1' || ($newicon == '2' && ($r < $day))) {
		$img = true;
	}
	return $img;
}

// 값체크 없으면 0리턴 (숫자인 경우)
function chkIsset($val='') {
	$r;
	if (isset($val) && $val != '') {	// 값이 존재하고 빈문자열('')이 아닌 경우에만 파라미터 리턴
		$r = $val;
	} else {
		$r = 0;
	}
	return $r;
}

// DB insert, update 시, 빈문자열 0으로 치환
function changeEmptyValue($req, $array) {

	for ($i=0; $i<sizeof($array); $i++) {
		$req[$array[$i]] = (int)$req[$array[$i]];
	}

	return $req;
}

// 이메일 폼 생성
function getURLMakeMailForm($url, $email_form) {
	$request = $url.$email_form;
	$response = file_get_contents($request);

	return $response;
}

function file_get_contents_curl($url) {
	if(strpos($url, "https://")){
		$url = str_replace("https://","http://", $url);
	}

	$ch = curl_init();

	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //Set curl to return the data instead of printing it to the browser.
	curl_setopt($ch, CURLOPT_URL, $url);

	$data = curl_exec($ch);
	curl_close($ch);

	return $data;
}

/* 
로그 출력 
/www/log 파일 생성
권한필요
파일명은 날짜별로 생성
사용여부는 siteProperty.php의 IS_LOGFILE 변수 true시 적용
한글깨질 시 웹사이트 페이지 캐릭터셋과 서버 캐릭터셋 확인 후 siteProperty.php 변경
*/

function outlog($message) {
	if(is_array($message)){
		$message = iconv(LOG_PAGE_CHAR, LOG_SERVER_CHAR, print_r($message, true));	// 인코딩 필요
	} else {
		$message = iconv(LOG_PAGE_CHAR, LOG_SERVER_CHAR, $message);	// 인코딩 필요
	}
	
	$filename = date('Y-m-d').LOG_FILENAME;
	$p = pathinfo($_SERVER['PHP_SELF']);
	$location = $p['dirname']."/".$p['basename'];
	$log = date('H:i:s')." ".$_SERVER['REMOTE_ADDR']." ".$location."\r\n".$message."\r\n";

	if (IS_LOGFILE) {
		$f = fopen(LOG_PATH."/".$filename, "a");
		fwrite($f, $log);
		fclose($f);
	}

}

/*
파일 업로드 처리 함수
해당 첨부파일을 실파일명, 원파일명, 파일사이즈를 구해 $_REQUEST에 세팅 후 리턴
$filename : 첨부파일명, $req : $_REQUEST, $isSize : 사이즈 구하기, $maxSaveSize : 최대 파일사이즈(config.php)
파일사이즈 명명 규칙 : 파일명이 filename3인 경우 filesize3
*/
function fileupload($filename, $uploadFullPath, $req, $isSize, $maxSaveSize) {
	$file_ext = explode(".", strrev($filename)); // . 으로 구분
	$file_ext = strrev($file_ext[0]); 
	$file_ext = strtolower($file_ext); // 확장자명 소문자로 변환

	// 업로드 확장자 블랙리스트
	$black_list = array("html", "htm", "php", "js", "jsp", "asp", "HTML", "HTM", "PHP", "JS", "JSP", "ASP", "php-x", "PHP-X", "exe", "EXE");
	if(in_array($file_ext, $black_list)){
		echo "
			<script>
				alert('업로드 할 수 없는 파일 형식입니다.');
				window.location.replace('index.php');
			</script>";
		
	}else{

		$files = $_FILES[$filename];
		$tmp_name = $files['tmp_name'];
		if ($tmp_name) {

			if ($files['size'] <= $maxSaveSize) {
			
				$org_name = $files['name'];
				$file_name = getRandFileName($org_name);
				if (!$files['error']) {
					move_uploaded_file($tmp_name, $uploadFullPath.$file_name);
				}

				// db insert value
				$req[$filename] = $file_name;
				$req[$filename.'_org'] = $org_name;
				if ($isSize) {
					$tmp = substr($filename, -1);
					if ((int)$tmp > 0) {
						$req['filesize'.$tmp] = $files['size'];
					} else {
						$req['filesize']=$files['size'];
					}
				}
			} else {
				echo "
				<script>
					alert('파일첨부 최대용량을 초과했습니다.');
					window.location.replace('index.php');
				</script>";
			}
		}


	return $req;

	}
}

function downloadUrl($uploadPath, $filename, $filename_org) {
	$url = "/lib/download.php?path=".$uploadPath."&vf=".$filename."&af=".$filename_org;
	return $url;
}

// utf8 문자열 길이구하기
function utf8_length($str) {
	$len = strlen($str);
	for ($i = $length = 0; $i < $len; $length++) {
		$high = ord($str{$i});
		if ($high < 0x80)//0<= code <128 범위의 문자(ASCII 문자)는 인덱스 1칸이동
			$i += 1;
		else if ($high < 0xE0)//128 <= code < 224 범위의 문자(확장 ASCII 문자)는 인덱스 2칸이동
			$i += 2;
		else if ($high < 0xF0)//224 <= code < 240 범위의 문자(유니코드 확장문자)는 인덱스 3칸이동 
			$i += 3;
		else//그외 4칸이동 (미래에 나올문자)
			$i += 4;
	}
	return $length;
}

// utf8문자열 자르고 뒤에 ... 붙이기
function utf8_strcut($str, $chars, $tail = '...') {
	$str = strip_tags($str);	// html 태그 제거
	if (utf8_length($str) <= $chars)//전체 길이를 불러올 수 있으면 tail을 제거한다.
		$tail = '';
	else
		$chars -= utf8_length($tail);//글자가 잘리게 생겼다면 tail 문자열의 길이만큼 본문을 빼준다.
		$len = strlen($str);
	for ($i = $adapted = 0; $i < $len; $adapted = $i) {
		$high = ord($str{$i});
		if ($high < 0x80)
			$i += 1;
		else if ($high < 0xE0)
			$i += 2;
		else if ($high < 0xF0)
			$i += 3;
		else
			$i += 4;
		if (--$chars < 0)
			break;
	}
	return trim(substr($str, 0, $adapted)) . $tail;
}

// 랜덤문자열 생성
function getRandomStr($length)	{  
	$characters  = "0123456789";  
	$characters .= "abcdefghijklmnopqrstuvwxyz";  
	$characters .= "ABCDEFGHIJKLMNOPQRSTUVWXYZ";  
	  
	$string_generated = "";  
	  
	$nmr_loops = $length;  
	while ($nmr_loops--)  
	{  
		$string_generated .= $characters[mt_rand(0, strlen($characters))];  
	}  
	  
	return $string_generated;  
}

function getDifferChecked($s, $w) {
	return ($s != $w) ? "checked" : "";
}

// 지점별 예약달력에서 가능여부 체크
function reserPosibleTime($rt, $data, $lunch, $dinner) {
	$result = 0;

	if ($data) {
		// 예약시간 int형으로 바꾸기 ex) 0900
		$reser = ($rt) ? (int)(substr($rt, 0, 2).substr($rt, 3, 2)) : 0;

		if ($data['yoil_start_time'] && $data['yoil_end_time']) {
			$startT = (int)$data['yoil_start_time'];
			$endT = (int)$data['yoil_end_time'];

			// 시작, 끝시간에 포함되면 가능(0) 넘거나 모자르면 불가능(1)
			$result = $startT <= $reser && $reser <= $endT ? $result : $result+1;
		}

		// 점심시간
		if ($lunch) {
			if ($data['lunch_start_time'] && $data['lunch_end_time']) {
				$startT = (int)$data['lunch_start_time'];
				$endT = (int)$data['lunch_end_time'];

				// 점심시간 시작시간 보다 작고 끝시간 보다 크면 가능(0), 포함이면 불가능(10)
				$result = $startT <= $reser && $reser < $endT ? $result+10 : $result;
			}
		}

		// 저녁시간
		if ($dinner) {
			if ($data['dinner_start_time'] && $data['dinner_end_time']) {
				$startT = (int)$data['dinner_start_time'];
				$endT = (int)$data['dinner_end_time'];

				// 저녁시간 시작보다 작고 끝시간 보다 크면 가능(0), 포함이면 불가능(100)
				$result = $startT <= $reser && $reser < $endT ? $result+100 : $result;
			}
		}
	}
	return $result;
}

// row 중 특정배열의 필드값 가져오기
function getRowValue($result, $i, $name) {
	mysql_data_seek($result, $i);
	$row = mysql_fetch_array($result);
	return $row[$name];
}

// 관리자 SMS발송여부 체크
function timeLimit($chk, $start, $end) {
	$tf = false;

	if ($chk == "0") {
		$tf = true;
	} else {
		$time = time();
		$sTime = strtotime($start);
		$eTime = strtotime($end);

		if ($sTime <= $time && $time <= $eTime) {
			$tf = true;
		}
	}
	return $tf;
}

/**
 * DB result 값 array 배열로 변경
 * @param unknown $rst
 * @return multitype:
 */
function rstToArray($rst){
	$arr = array();
	if($rst != null && mysql_num_rows($rst) > 0){
		while($row = mysql_fetch_assoc($rst)){
			array_push($arr, $row);
		}
	}
	return $arr;
}

/**
 * json_decode 거치면 json_array list 객체들은 obj형식으로 변형됨.
 * @param unknown $obj
 * @return multitype:
 */
function objToArray($obj){
	if(is_object($obj)){
		$obj = get_object_vars($obj);
	}
	
	if(is_array($obj)){
		return array_map(__FUNCTION__, $obj);
	} else {

		return $obj;
	}
}




function MobileCheck() { 
    global $HTTP_USER_AGENT; 
	$HTTP_USER_AGENT = $_SERVER['HTTP_USER_AGENT'];
    $MobileArray  = array("iphone","lgtelecom","skt","mobile","samsung","nokia","blackberry","android","android","sony","phone", "ipad", "ipod", "nexus", "lg", "macintosh", "ios");

    $checkCount = 0; 
        for($i=0; $i<sizeof($MobileArray); $i++){ 
            if(preg_match("/$MobileArray[$i]/", strtolower($HTTP_USER_AGENT))){ $checkCount++; break; } 
        } 
   return ($checkCount >= 1); 
}

function getImgFromContents($contents="") {
	
	// 정규식을 이용해서 img 태그 전체 / src 값만 추출하기
	preg_match_all("/<img[^>]*src=[\"']?([^>\"']+)[\"']?[^>]*>/i", $contents, $matches);
	
	return $matches[1][0];
}

// 파라미터 ' 슬래쉬 추가
function getReqAddSlash($req) {
    foreach ($req as $key => $value) {
		if (!is_array($value)) {
			if($key != "contents" && $key != "contents_en"){
				$req[$key] = addslashes($value);
			}
		} else {
			foreach ($value as $innerKey => $innerValue) {
				if($req[$key][$innerKey] != "contents" && $req[$key][$innerkey] != "contents_en"){
					$req[$key][$innerKey] = addslashes($innerValue);
				}
			}
		}
	}
    return $req;
}

function getYoutubeCode($src){
	if(strpos($src, "watch?v=")){
		$src2 = explode("watch?v=", $src);
	

		if(strpos($src2[1],"&")){
			$url = explode("&", $src2[1]);
		}else{
			$url = $src2[1];
		}

	}else if(strpos($src, "youtu.be/")){
		$src2 = explode("youtu.be/", $src);

		if(strpos($src2[1], "&")){
			$url = explode("&", $src2[1]);
		}else{
			$url = $src2[1];
		}
	}

	return $url;

	
}


function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}



function loginConfirmURL($url=''){	
	if($url == ""){
		$backUrl = "history.back(-1)";
	}

	$a = "<script>
		if(confirm('로그인이 필요한 서비스입니다.\\n로그인 페이지로 이동하시겠습니까?')){
			location.href = '/member/login.php?url='+encodeURIComponent(location.href);
		}else{
			".$backUrl.";
		}
	</script>";

	echo $a;
}


function img_resize($ori_img_path, $copy_img_path, $quality) {

	$size = getimagesize($ori_img_path);
	if ($size['mime'] == 'image/jpeg') 
		$ori_image = imagecreatefromjpeg($ori_img_path);
	elseif ($size['mime'] == 'image/gif') 
		$ori_image = imagecreatefromgif($ori_img_path);
	elseif ($size['mime'] == 'image/png') 
		$ori_image = imagecreatefrompng($ori_img_path);
	imagejpeg($ori_image, $copy_img_path, $quality);
	return $copy_img_path;
}



//escape
function escape_string($req) {
	/*
    $reqKeyArray = array_keys($req);
    for ($i=0; $i<count($reqKeyArray); $i++) {
        $req[$reqKeyArray[$i]] = @addslashes($req[$reqKeyArray[$i]]);
    }
    return $req;
	*/
	
	if( !empty($req) ){

		foreach ($req as $key => $value) {

			if (!is_array($value)) {
				$req[$key] = mysql_real_escape_string($value);
			} else {
				foreach ($value as $innerKey => $innerValue) {
					$req[$key][$innerKey] = mysql_real_escape_string($innerValue);
				}
			}
			
		}
	}
	

	return $req;
}

function pkcs7Padding($rawString) {
		$blockSize = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB);
		$paddingSize = $blockSize - (strlen($rawString) % $blockSize);
		$paddingChar = chr($paddingSize);
		#print($rawString . str_repeat($paddingChar, $paddingSize));
		return $rawString . str_repeat($paddingChar, $paddingSize);
}

// 복호화시 Padding 삭제처리
function pkcs7PaddingDe($rawString) {
		$blockSize = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB);
		$lastChar = substr($rawString, -1, 1);
		$paddingSize = ord($lastChar);
		#$originSize = $paddingSize - $blockSize;
  	return substr($rawString, 0, -($paddingSize));
}
function Encrypt($key, $data) {
	  $iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128,MCRYPT_MODE_ECB), MCRYPT_RAND);
	  return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, pkcs7Padding($data), MCRYPT_MODE_ECB, $iv));
}

function Decrypt($key, $data) {
	  $data = base64_decode($data);
	  $iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128,MCRYPT_MODE_ECB), MCRYPT_RAND);
	  $data = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $data, MCRYPT_MODE_ECB,$iv);
	  return pkcs7PaddingDe($data);
  
}


function escape_html($req){
	if( !empty($req) ){

		if(!is_array($req)){
			$req = htmlspecialchars($req, ENT_QUOTES, "UTF-8");
		}else{

			foreach ($req as $key => $value) {

				if (!is_array($value)) {
					$req[$key] = htmlspecialchars($value, ENT_QUOTES, "UTF-8");
				} else {
					foreach ($value as $innerKey => $innerValue) {

						$req[$key][$innerKey] = htmlspecialchars($innerValue, ENT_QUOTES, "UTF-8");
					}
				}
				
			}

		}
	}

	return $req;
}

function xss_clean_value($data){
	$data = str_replace(array('&amp;','&lt;','&gt;'), array('&amp;amp;','&amp;lt;','&amp;gt;'), $data);
	$data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
	$data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
	$data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');

	// Remove any attribute starting with "on" or xmlns
	$data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $data);

	// Remove javascript: and vbscript: protocols
	$data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $data);
	$data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $data);
	$data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $data);

	// Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
	$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
	$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
	$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $data);

	// Remove namespaced elements (we do not need them)
	$data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);

	do
	{
		// Remove really unwanted tags
		$old_data = $data;
		$data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
	}
	while ($old_data !== $data);

	// we are done...


	return $data;
}
function xss_clean($req){ // html_decode 함수의 일종
		if(!empty($req))
		{
			if(!is_array($req)){
				$req = xss_clean_value($req);
			}else{

				foreach($req as $key => $value){

					if(!is_array($value)){
						$req[$key] = xss_clean_value($value);

					}else{

						foreach ($value as $innerKey => $innerValue) {
							$req[$key][$innerKey] = xss_clean_value($innerValue);
						}
					}
				}

			}
		}
        return $req;
}
?>