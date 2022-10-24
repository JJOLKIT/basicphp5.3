<?php
include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/weblog/pageLog.class.php";

$pageLog = new pageLog(999,$_REQUEST);

function getBrowserInfo()  
{ 
  $userAgent = $_SERVER["HTTP_USER_AGENT"];  
  if(preg_match('/MSIE/i',$userAgent) && !preg_match('/Opera/i',$u_agent)){ 
    $browser = 'Internet Explorer'; 
  } 
  else if(preg_match('/Firefox/i',$userAgent)){ 
    $browser = 'Mozilla Firefox'; 
  } 
  else if(preg_match('/Whale/i', $userAgent)){
	$browser = "Naver Whale";
  }
  else if(preg_match('/Edg/i', $userAgent)){
	$browser = "Explorer Edge";
  }
  else if (preg_match('/Chrome/i',$userAgent)){ 
    $browser = 'Google Chrome'; 
  } 
  else if(preg_match('/Safari/i',$userAgent)){ 
    $browser = 'Apple Safari'; 
  } 
  elseif(preg_match('/Opera/i',$userAgent)){ 
    $browser = 'Opera'; 
  } 
  elseif(preg_match('/Netscape/i',$userAgent)){ 
    $browser = 'Netscape'; 
  } 
 
  else{ 
    $browser = "Other"; 
  } 

  return $browser; 
}

 

function getOsInfo() 
{ 
  $userAgent = $_SERVER["HTTP_USER_AGENT"];  

  if (preg_match('/linux/i', $userAgent)){  
    $os = 'linux';} 
  elseif(preg_match('/macintosh|mac os x/i', $userAgent)){ 
    $os = 'mac';} 
  elseif (preg_match('/windows|win32/i', $userAgent)){ 
    $os = 'windows';} 
  else { 
    $os = 'Other';

  } 

  return $os; 
}



if(isset($_SERVER['HTTPS'])){
	$pagelogurl = "https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
}else{
	$pagelogurl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
}
$_REQUEST['os'] = getOsInfo();
$_REQUEST['browser'] = getBrowserInfo();
$_REQUEST['ip'] = $_SERVER['REMOTE_ADDR'];
$_REQUEST['page'] = $pagelogurl;
if(MobileCheck() > 0){
	$_REQUEST['user_agent'] = "M";
}else{
	$_REQUEST['user_agent'] = "PC";
}
if(strpos($pagelogurl, "?n_media=") !== false){
	$_REQUEST['advertise'] = "1";
}

if($_SERVER['REMOTE_ADDR'] !== "221.151.243.123"){
	$pageLog->insert($_REQUEST);
}

