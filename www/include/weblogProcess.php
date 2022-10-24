<?php
include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
outlog($_REQUEST['ref']);
$idx = strpos($_REQUEST['ref'], "//");
$tmp;
if ($idx >= -1) {
	$tmp = substr($_REQUEST['ref'], $idx+2);
} else {
	$tmp = $_REQUEST['ref'];
}

$idx = strpos($tmp, "/");
$refhost;
$refpage;
if ($idx >= -1) {
	$refhost = substr($tmp, 0, $idx);
	$refpage = substr($tmp, $idx);
} else {
	$refhost = $tmp;
	$refpage = "/";
}

$idx = strpos($refpage, "?");
$refparam;
if ($idx >= -1) {
	$refparam = substr($refpage, $idx+1);
	$refpage = substr($_REQUEST['ref'], 0, $idx);
} else {
	$refparam = "";
}

$refsearch = "";
$offset = 0;
if ($refparam) {
	$idx = strpos($refparam, "&query=");
	if ($idx >= 0) $offset = 7;
	if ($idx == "") {
		$idx = strpos($refparam, "&Query=");
		$offset = 7;
		if ($idx == "") {
			$idx = strpos($refparam, "&q=");
			$offset = 3;
			if ($idx == "") {
				$idx = strpos($refparam, "&p=");
				$offset = 3;
				if ($idx == "") {
					$idx = strpos($refparam, "&keyword=");
					$offset = 9;
					if ($idx == "") {
						$idx = strpos($refparam, "&DMSKW=");
						$offset = 7;
					}
				}
			}
		}
	}
	if ($idx > -1) {
		$tmp = substr($refparam, $idx+$offset);
		$idx = strpos($tmp, "&");
		if ($idx > -1) {
			$refsearch = substr($tmp, 0, $idx);
		} else {
			$refsearch = $tmp;
		}
	}
}

outlog($refhost);
if ($refhost != "" && $refhost !== str_replace("http://", "", COMPANY_URL) && $refhost !== str_replace("http://www", "", COMPANY_URL)) {
	include_once $_SERVER['DOCUMENT_ROOT']."/lib/weblog/Weblog.class.php";
	$weblog = new Weblog(9999, $_REQUEST);
	outlog("s");
	$weblog->weblogInsert($_REQUEST['connectid'], $_REQUEST['ref'], urldecode($refsearch), $_SERVER['REMOTE_ADDR']);
	outlog("e");
	//$_SESSION['weblog_ref'] = $_REQUEST['ref'];
	//$_SESSION['weblog_refsearch'] = urldecode($resfsearch);
	//$_SESSION['weblog_ip'] = $_SERVER['REMOTE_ADDR'];
}
//outlog($_SESSION);
?>