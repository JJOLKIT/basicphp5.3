<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
/*
* SQL INJECTION  체크 소스
* (주)가비아 작성
*/

/*************************** 변수 수정 방법 설명  ***********************

1. 아래 변수를 수정하시면 이 파일을 include하는 파일에 공통적으로 적용이됩니다.

2. 기본 값과 수정가능부분만 코드 예) 의 형식에 맞게 수정하시기 바랍니다. 코드 예)의 형식과 틀리면 ASP 문법적 오류 발생등으로 홈페이지에 오류가 발생할수 있습니다.

3. SQL INJECTION으로 추정되는 정보를 메일로 받아야 할 경우 G_INJECTOIN_MAIL_ADDR 값에 메일주소를 꼭 기입해야 합니다.

**************************************************************************/


/************* 인자 정보 수정 가능 구간 시작( (주)가비아 )  *************/
if ( trim($G_INJECTION_PATTERN_CHK) == "" ){
	$G_INJECTION_PATTERN_CHK = "Y" ;         //<-- 수정가능부분 : 기본값 Y, Y - injection 패턴 값으로 체크함,    N - 프로그램을 실행안함 
									        //    코드 예) G_INJECTION_PATTERN_CHK = "N"
}

if ( trim($G_INJECTOIN_MAIL_SEND) == "" ){
	$G_INJECTOIN_MAIL_SEND = "Y" ;           //<-- 수정가능부분 : 기본값 Y, Y - injectoin 패턴 값으로 체크될 경우 메일보냄,  N - 메일 안 보냄
											//    코드 예) G_INJECTOIN_MAIL_SEND = "N"
}

if ( trim($G_INJECTOIN_MAIL_ADDR) == "" ){
	$G_INJECTOIN_MAIL_ADDR = "Y" ;           //<-- 수정가능부분 : G_INJECTOIN_MAIL_SEND 값이 Y일 경우 G_INJECTOIN_MAIL_ADDR 정보로 
									        //                   SEQ INJECTION 정보가 보내짐
											//	   코드 예1) 받는 메일주소가 하나일때  : G_INJECTOIN_MAIL_ADDR = "root@gabia.com"
											//	   코드 예2) 받는 메일주소가 여러개일 경우 :  G_INJECTOIN_MAIL_ADDR = "aaa@gabia.com,bbb@gabia.com"   <===  , 로 구분해서 작성하시면 됩니다.
}

if ( trim($G_INJECTOIN_URL_CHANGE) == "" ){
	$G_INJECTOIN_URL_CHANGE = "Y" ;          //<-- 수정가능부분 : 기본값 Y, Y - injectoin 패턴 값으로 체크될 경우 
											//                   G_INJECTOIN_URL_NAME 값으로 페이지 이동, N - URL 이동안함
											//    코드 예) G_INJECTOIN_URL_CHANGE = "N"
}

if ( trim($G_INJECTOIN_URL_NAME) == "" ){
	$G_INJECTOIN_URL_NAME  = COMPANY_URL	;  	        //<-- 수정가능부분 : 기본값 기본도메인, G_INJECTOIN_URL_CHANGE 값이 Y이고 
											//                  injectoin 패턴 값으로 체크되는 경우 이동하는 URL값
											// 코드 예) G_INJECTOIN_URL_NAME = "http://www.gabia.com" <==  http://부터 모두 작성해주셔야 합니다.
}

if ( trim($G_INJECTOIN_URL_ALERT) == "" ){     
	$G_INJECTOIN_URL_ALERT = "유효하지 않은 문자가 체크되어 페이지 이동합니다" ;            //<-- 수정가능부분 : 기본값 "유효하지 않은 문자가 체크 되었습니다." 
	                                         //                   G_INJECTOIN_URL_NAME로 이동하기 전 나오는 메시지
											 //코드 예) G_INJECTOIN_URL_ALERT = "유효하지 않은 문자가 체크되어 페이지 이동합니다."
}
/************** 인자 정보 수정 가능 구간 시작 끝 **************/


/****************** 아래부터는 수정을 하시면 코드가 제대로 실행이 안될 수 있습니다. ***********************/

if ( $G_INJECTION_PATTERN_CHK == "" ) {
	$G_INJECTION_PATTERN_CHK = "Y";
}

If ( $G_INJECTION_PATTERN_CHK == "Y" ){


	// 인자 체크
	if( trim($G_INJECTOIN_MAIL_SEND) == "" ){
		$G_INJECTOIN_MAIL_SEND = "Y";
	}

	if( trim($G_INJECTOIN_URL_CHANGE) == "" ){
		$G_INJECTOIN_URL_CHANGE = "Y";
	}


	if( trim($G_INJECTOIN_URL_ALERT) == "" ){
		$G_INJECTOIN_URL_ALERT = "유효하지 않은 문자가 체크 되었습니다.";
	}


	if( trim($G_INJECTOIN_URL_NAME) == "" ){
		$G_INJECTOIN_URL_NAME = "http://".$HTTP_HOST;
	}
	
	//패턴 정의
	$G_INJECTION_PATTERN = "/delete[[:space:]]+from|drop[[:space:]]+database|drop[[:space:]]+table|drop[[:space:]]+column|drop[[:space:]]+procedure| create[[:space:]]+table|union[[:space:]]+all|update.+set.+=|insert[[:space:]]+into.+values|select.+from|bulk[[:space:]]+insert|or.+1[[:space:]]*=[[:space:]]1|alter[[:space:]]+table|into[[:space:]]+outfile|\/\*|\*\//";

	//기본 변수 정의
	$G_INJECTION_CHECK_VALUE = false;
	$G_INJECTION_CHECK_LIST  = "";
	$G_INJECTION_CHECK_ITEM  = "";
		
	//post값 조사
	foreach($_POST as $key => $post_value){
		if( preg_match($G_INJECTION_PATTERN, $post_value) ){
			$G_INJECTION_CHECK_LIST  =  $post_value;
			$G_INJECTION_CHECK_ITEM  = 'POST';
			$G_INJECTION_CHECK_VALUE = true;
			break;
		}			
		
	}
	//echo 'get 조사합니다.';

	//get값 조사
	if( $G_INJECTION_CHECK_VALUE == False){
		foreach($_GET as $key => $get_value){
			if( preg_match($G_INJECTION_PATTERN, $get_value) ){
				$G_INJECTION_CHECK_LIST  =  $get_value;
				$G_INJECTION_CHECK_ITEM  = 'GET';
				$G_INJECTION_CHECK_VALUE = true;
				break;
			}			
		}
	}

	if( $G_INJECTION_CHECK_VALUE == False){
		$REQUEST_URI_ARRAY = explode("?",$_SERVER["REQUEST_URI"]);
		$req_value = $REQUEST_URI_ARRAY[1];
		if( preg_match($G_INJECTION_PATTERN,$req_value) ){
			$G_INJECTION_CHECK_LIST  =  $req_value;
			$G_INJECTION_CHECK_ITEM  = 'REQUEST_URI';
			$G_INJECTION_CHECK_VALUE = true;
			break;
		}	
	}

	
	//메일보내기, 조건 :  메일주소가 있고 메일주소 보내기 값이 Y이고 injection 패턴에 체크되었을 경우 
	if ( $G_INJECTOIN_MAIL_ADDR != ""  &&   $G_INJECTOIN_MAIL_SEND == "Y" &&  $G_INJECTION_CHECK_VALUE == true ){
		
		//메일 보내기
		$POST_MESSAGE = print_r ($HTTP_POST_VARS, true);
		$GET_MESSAGE  = print_r ($HTTP_GET_VARS, true);

		$mail_to = $G_INJECTOIN_MAIL_ADDR;							//받는메일주소
		$mail_subject = "SQL INJECTION 추정 페이지($HTTP_HOST)";	//제목
		$mail_body = 
						"<body>
						<table width=500 cellpadding=5 cellspacing=0 border=1 style='border-collapse:collapse;' bordercolor='#CBC4B1'>
						<tr bgcolor='#F2F0EE'><td colspan='2'><b>Exception 내역</b></td></tr>
						<tr><td width=200 bgcolor='#F9F8F7'>호스트(도메인)명</td><td>".$HTTP_HOST."</td></tr>
						<tr><td bgcolor='#F9F8F7'>호출(체크)한 페이지</td><td>".$SCRIPT_FILENAME."</td></tr>
						<tr><td bgcolor='#F9F8F7'>refer(이전) 페이지 </td><td>".$HTTP_REFERER."</td></tr>
						<tr><td bgcolor='#F9F8F7'>POST Parameter</td><td><pre>".$POST_MESSAGE."</pre></td></tr>
						<tr><td bgcolor='#F9F8F7'>GET Parameter</td><td><pre>".$GET_MESSAGE."</pre></td></tr>
						<tr><td bgcolor='#F9F8F7'>체크된 값</td><td>".$G_INJECTION_CHECK_LIST."</td></tr>
						<tr><td bgcolor='#F9F8F7'>체크한 패턴</td><td>".$G_INJECTION_PATTERN."</td></tr>
						<tr><td bgcolor='#F9F8F7'>체크된 종류</td><td>".$G_INJECTION_CHECK_ITEM."</td></tr>
						</table>
						<body>";

		$mail_header = "From: root@$HTTP_HOST\r\n";				//보내는 메일주소
		$mail_header .= "Reply-to: root@$HTTP_HOST\r\n";
		$mail_header .= "Content-Type: text/html\r\n";

		mail($mail_to,$mail_subject,$mail_body,$mail_header);
	}

	// url 이동하기, 조건 :  url값이 존재하고 url이동하는값이 Y injection 패턴에 체크되었을 경우
	if ( $G_INJECTOIN_URL_CHANGE != "" && $G_INJECTOIN_URL_CHANGE == "Y" && $G_INJECTION_CHECK_VALUE == true ){
		echo "<script language='javascript'>\n";
		echo "alert('$G_INJECTOIN_URL_ALERT')\n";
		echo "parent.location.href='$G_INJECTOIN_URL_NAME'\n";
		echo "</script>\n";
		exit;
	}

}//If ( $G_INJECTION_PATTERN_CHK == "Y" ){