<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
/*
*SQL INJECTION  체크 파일 include하는 파일, 체크 파일명 : g_sql_injection_filter.php
*(주)가비아 작성
*/

/*************** 인자 설명  (주)가비아 *************** 
1. 아래의 인자변수들은 특정페이지에서 g_sql_injection_filter.php 파일안의 인자변수와 다른 값으로 처리하고 싶은 경우 사용됩니다. 
   따라서 없거나 공백인 변수들은 g_sql_injection_filter.php 안의 변수값에 따라 처리가 됩니다.

2. g_sql_injection_filter.php 을 include하는 부분위에 위치하면 됩니다.

3. 아래의 인자변수들은 g_sql_injection_filter.php 안에 있는 변수보다 우선합니다.

4. 기본값이라는 것은 그대로 두시면 기본값대로 프로그램이 실행이 된다는 뜻입니다.

5. 아래 변수들은 "" 안의 값을 아래 설명을 참조하시어 수정하시면 됩니다. 코드 예) $G_INJECTION_PATTERN_CHK = "N" 

6. 아래 변수 수정시 코드 예)와 다른 형식으로 수정시 프로그램상에서 오류가 발생할 수 있습니다.
*******************************************************/

/************* 인자 정보 수정 가능 구간 시작 *************/
// 수정하실 변수에서 ""의 값만 수정하시면 됩니다.

$G_INJECTION_PATTERN_CHK = "Y";    //기본값 Y, Y - injection 패턴 값으로 체크함,    N - 프로그램을 실행안함 

$G_INJECTOIN_MAIL_SEND   = "Y";    //기본값 Y, Y - injectoin 패턴 값으로 체크될 경우 메일보냄,  N - 메일 안 보냄 

$G_INJECTOIN_MAIL_ADDR   = "";    //$G_INJECTOIN_MAIL_SEND 값이 Y일 경우 $G_INJECTOIN_MAIL_ADDR 정보로 SEQ INJECTION 정보가 보내짐
								 //코드 예1) 받는 메일주소가 하나일때  : $G_INJECTOIN_MAIL_ADDR = "root@gabia.com"
							     //코드 예2) 받는 메일주소가 여러개일 경우 :  $G_INJECTOIN_MAIL_ADDR = "aaa@gabia.com,bbb@gabia.com"   <===              , 로 구분해서 작성하시면 됩니다.

$G_INJECTOIN_URL_CHANGE  = "Y";   //기본값 Y, Y - injectoin 패턴 값으로 체크될 경우 $G_INJECTOIN_URL_NAME 값으로 페이지 이동,  N - URL이동안함

$G_INJECTOIN_URL_NAME    = COMPANY_URL;   //기본값 기본도메인, $G_INJECTOIN_URL_CHANGE 값이 Y이고 injectoin 패턴 값으로 체크되는 경우 이동하는 URL값
								 //코드 예) http://www.gabia.com <==  http://부터 모두 작성해주셔야 합니다.

$G_INJECTOIN_URL_ALERT   = "유효하지 않은 문자가 체크 되었습니다.";   //'기본값 "유효하지 않은 문자가 체크 되었습니다.", $G_INJECTOIN_URL_NAME로 이동하기 전 나오는 메시지

/************** 인자 정보 수정 가능 구간 시작 끝 **************/



//아래는 파일을 include 하는 부분입니다
include($_SERVER[DOCUMENT_ROOT]."/include/g_sql_injection_filter.php");  //()안의 파일 경로를 수정하시면 됩니다.

//여기서 부터는 php 소스입니다.
//echo  "여기부터는 php 소스입니다.";
?>