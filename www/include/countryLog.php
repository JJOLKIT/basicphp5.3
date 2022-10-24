<?php
include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/weblog/CountryLog.class.php";

$countryLog = new CountryLog(999,$countryReq);



$countryReq['ip'] = $_SERVER['REMOTE_ADDR'];

$details = json_decode(file_get_contents_curl("http://ipinfo.io/".$countryReq['ip']));// 받음받음

if(MobileCheck() > 0){
	$countryReq['device'] = "M";
}else{
	$countryReq['device'] = "PC";
}

$c = file_get_contents( $_SERVER['DOCUMENT_ROOT']."/json/country.json" );
if( $c ) $cjson = json_decode( $c, JSON_UNESCAPED_UNICODE);



$code = $details->country;
$countryReq['country'] = $cjson[$code]['CountryNameKR'];
$countryReq['country_en'] = $cjson[$code]['CountryNameEN'];
$countryReq['city'] = $details->city;
$countryReq['region'] = $details->region;
$countryReq['loc'] = $details->loc;
$countryReq['code'] = $code;

if($_SERVER['REMOTE_ADDR'] != "221.151.243.123"){
	$countryLog->insert($countryReq);
}

?>