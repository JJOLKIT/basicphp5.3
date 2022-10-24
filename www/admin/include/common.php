<?
session_start();
header("Content-Type: text/html; charset=UTF-8");

if($request_check != 1 && $_SESSION['SECURITY_LEVEL'] == 3)
{
	unset($arr_request);
	unset($arr_post);
	unset($arr_get);
	unset($arr_illegal);
	unset($arr_replaced);

	$arr_request = $_REQUEST;
	$arr_post = $_POST;
	$arr_get = $_GET;

	reset($arr_request);
	reset($arr_post);
	reset($arr_get);

	/** 한글 표현 때문에 ; 를 무조건 제거: semi-colon 사용하기 위해서 |mMm|59 라고 치환하시면 됩니다. **/
	$arr_illegal = array("&#",";","#","&","|mMm|59","|mMm|#35;","--","/*","*/","<",">","(",")","|mMm|special",'\"',"\'","'","document","cookie","script"," onload","xp_","1=1","passwd","iframe"," onerror"," onmouse"," onkey"," onclick"," oncontextmenu"," ondblclick"," ondragstart"," onreadystatechange");
	$arr_replaced = array("|mMm|special","","|mMm|#35;","&#38;","&#59;","&#34;","&#45;&#45;","","","&lt;","&gt;","&#40;","&#41;","&#","","","","do_cument","co_okie","sc_ript"," on_load","x_p_","1_=1","pass_wd","i_frame"," on_error"," on_mouse"," on_key"," on_click"," on_contextmenu"," on_dblclick"," on_dragstart"," on_readystatechange");

	$arr_request = str_ireplace($arr_illegal,$arr_replaced,$arr_request);
	$arr_post = str_ireplace($arr_illegal,$arr_replaced,$arr_post);
	$arr_get = str_ireplace($arr_illegal,$arr_replaced,$arr_get);

	$_REQUEST = $arr_request;
	$_POST = $arr_post;
	$_GET = $arr_get;

	unset($arr_request);
	unset($arr_post);
	unset($arr_get);
	unset($arr_illegal);
	unset($arr_replaced);

	$request_check = 1;
}
$mycookie = $_COOKIE;
$mysession = $_SESSION;


include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/MySecurity.php";

$security = new MySecurity();
$mycookie = $security->xss_clean($mycookie);
$mysession = $security->xss_clean($mysession);

unset($security);

// castle적용
include_once($_SERVER['DOCUMENT_ROOT']."/include/castle.php");

$chatUse = false; //챗봇


$HostingInfo = true; //호스팅 만료정보 표기 siteproperty.php


?>