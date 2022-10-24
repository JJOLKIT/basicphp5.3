
<script type="text/javascript">
var sessionid = "SESSIONID_newweblog";
var connectid = "CONNECTID_newweblog";
var refid	  = "REFID_newweblog";

var appname = navigator.userAgent.toLowerCase();
var os;
var osversion;
var browser;
var browsername;
var isfirst = false;

// 체크 해상도
var vn_screenx = screen.width; // 화면스크린 넓이
var vn_screeny = screen.height; // 화면스크린 높이

var resolution = vn_screenx + " * " + vn_screeny;

// 체크 타임존
var tz = new Date();
var tzStr = (tz.getTimezoneOffset()/60) * -1;

// 체크 사용언어별
var lang;

if (navigator.appName == "Netscape")
	lang = navigator.language;
else
	lang = navigator.userLanguage;

var mobileKeyWords = new Array('iPhone', 'iPod', 'iPad','BlackBerry', 'Android', 'Windows CE', 'LG', 'MOT', 'SAMSUNG', 'SonyEricsson');

// Check OS
if(appname.indexOf("iphone")>0) { os = "iPhone"; osversion="iPhone"; }
else if(appname.indexOf("ipad")>0) { os = "iPad"; osversion="iPad"; }
else if(appname.indexOf("blackberry")>0) { os = "Black Berry"; osversion="Black Berry"; }
else if(appname.indexOf("sonyericsson")>0) { os = "Sony Ericsson"; osversion="Sony Ericsson"; }
else if(appname.indexOf("mot")>0) { os = "motorola"; osversion="motorola"; }
else if(appname.indexOf("samsung")>0) { os = "SAMSUNG smartphone"; osversion="SAMSUNG smartphone"; }
else if(appname.indexOf("lg")>0) { os = "LG smartphone"; osversion="LG smartphone"; }
else if(appname.indexOf("android")>0) { os = "Android"; osversion="Android"; }
else if(appname.indexOf("windows ce")>0) { os = "Windows CE"; osversion="Windows CE"; }
else if(appname.indexOf("nt 5.0")>0) { os = "Windows 2000"; osversion = "2000"; }
else if(appname.indexOf("nt 6.1")>0) { os = "Windows 7"; osversion = "7"; }
else if(appname.indexOf("nt 6.0")>0) { os = "Windows Vista"; osversion = "VISTA"; }
else if(appname.indexOf("nt 5.1")>0) { os = "Windows XP"; osversion = "XP"; }
else if(appname.indexOf("nt 5.2")>0) { os = "Windows 2003"; osversion = "2003"; }
else if(appname.indexOf("98")>0) { os = "Windows 98"; osversion = "98"; }
else if(appname.indexOf("me")>0) { os = "Windows ME"; osversion = "ME"; }
else if(appname.indexOf("mac os")>0) { os = "Mac OS"; osversion = "Mac"; }
else if(appname.indexOf("linu")>0) { os = "LINUX"; osversion=""; }
else { os = "ETC"; osversion="-"; }

if(appname.indexOf("msie 9.0")>0) { browser="MSIE 9.0"; browsername="Internet Explorer"; }
else if(appname.indexOf("msie 8.0")>0) { browser="MSIE 8.0"; browsername="Internet Explorer"; }
else if(appname.indexOf("msie 7.0")>0) { browser="MSIE 7.0"; browsername="Internet Explorer"; }
else if(appname.indexOf("msie 6.0")>0) { browser="MSIE 6.0"; browsername="Internet Explorer"; }
else if(appname.indexOf("msie 5.5")>0) { browser="MSIE 5.5"; browsername="Internet Explorer"; }
else if(appname.indexOf("msie 5.0")>0) { browser="MSIE 5.0"; browsername="Internet Explorer"; }
else if(appname.indexOf("msie")>0) { browser="MSIE"; browsername="Internet Explorer"; }
else if(appname.indexOf("opera")>0) {browser = "OPERA"; browsername="Opera";}
else if(appname.indexOf("firefox")>0) { browser="FIREFOX"; browsername="Firefox"; }
else if(appname.indexOf("safari")>0) { browser="SAFARI"; browsername="Safari"; }
else if(appname.indexOf("chrome")>0) { browser="CHROME"; browsername="Chrome"; }
else if(appname.indexOf("gecko")>0) { browser="GECKO"; browsername=navigator.appName;}
else { browser = "ETC"; browsername=navigator.platform; }

var url = document.URL
var urls = url.split("?");
var urlhost = urls[0];
var urlquery = urls.length>1 ? urls[1] : "";
//alert(typeof("parent.document"));

var pref = "";//document==null ? "" : parent.document.referrer;
var ref = document.referrer;
var refs, refhost="", refquery="";

try{ pref = parent.document.referrer;}catch(_e){ try{ pref = top.document.referrer; }catch(_e){ pref = ""} }

if(pref != ref)
{
	ref = pref;
}

// 모바일 처리
if(url.indexOf("ref_val=") > -1) {
	ref = url.substring(url.indexOf("ref_val=")+8);
	url = url.substring(0, url.indexOf("ref_val=")-1);	
}

if(ref==url && url.indexOf("applicationDirect=on")== -1)
{
	ref = "";	
}
else
{

	if(url.indexOf("applicationDirect=on") > -1) {
		ref = url;
	}

	refs = ref.split("?");
	refhost = refs[0];
	refquery = refs.length>1 ? refs[1] : "";
}

// 유입경로별 인서트 로직추가
var cururl = url;
var cururl2 = "";

if(cururl.indexOf("http://www.") > -1) {
	cururl2 = cururl.substring(11, cururl.substring(11).indexOf("/") + 11);
} else if(cururl.indexOf("http://") > -1) {
	cururl2 = cururl.substring(7, cururl.substring(7).indexOf("/") + 7);
} else if(cururl.indexOf("https://www") > -1) {
	cururl2 = cururl.substring(12, cururl.substring(12).indexOf("/") + 12);
} else if(cururl.indexOf("https://") > -1) {
	cururl2 = cururl.substring(8, cururl.substring(8).indexOf("/") + 8);
}

var refurl = ref;
var refurl2 = "";

if(refurl.indexOf("http://www.") > -1) {
	refurl2 = refurl.substring(11, refurl.substring(11).indexOf("/") + 11);
} else if(refurl.indexOf("http://") > -1) {
	refurl2 = refurl.substring(7, refurl.substring(7).indexOf("/") + 7);
} else if(refurl.indexOf("https://www") > -1) {
	refurl2 = refurl.substring(12, refurl.substring(12).indexOf("/") + 12);
} else if(refurl.indexOf("https://") > -1) {
	refurl2 = refurl.substring(8, refurl.substring(8).indexOf("/") + 8);
}
// 유입경로별 인서트 로직추가 끝

var expire         = new Date();
var sessionid_val  = getCookie(sessionid);
var connectid_val  = getCookie(connectid);
var refid_val	   = getCookie(refid);
//var curdate        = new Date(expire.getMilliseconds() );
var curdate        = new Date();

expire.setTime(curdate.getTime() + 1000 * 3600 * 24 * 365);

if(sessionid_val=="")
{
	setCookie(sessionid, ""+curdate.getTime(), expire);
	sessionid_val = ""+curdate.getTime();
}

if(connectid_val == "")
{
	connectid_val = ""+curdate.getTime();
	setCookie(connectid,connectid_val,null);
	isfirst = true;
}

// 유입경로별 인서트 로직추가
if(refid_val == "") {
	if(refurl2 == "") {
		refid_val = "BOOKMARK";
	} else {
		refid_val = refurl2;
	}
	setCookie(refid,refid_val,null);
}

if( (refurl2 != "" && cururl2 != refurl2) && (refurl2 != refid_val) ) {
	
	connectid_val = ""+curdate.getTime();
	setCookie(connectid,connectid_val,null);
	setCookie(refid,refurl2,null);

	isfirst = true;
}
// 유입경로별 인서트 로직추가 끝	

// 이미지 링크할 때는 절대 경로로 합니다. HOST명까지 적어 주어야 합니다.
var img = "/include/weblogProcess.php?cururl="+cururl2+"&isfirst="+isfirst+"&sessionid="+sessionid_val+"&connectid="+connectid_val+"&os="+escape(os)+"&osversion="+escape(osversion)+"&browser="+escape(browser)+"&browsername="+escape(browsername)+"&ref="+encodeURIComponent((ref))+"&url="+escape((url))+"&resolution="+resolution+"&lang="+lang+"&tzone="+tzStr;

document.write("<img src='"+img+"' border=0 width=0 height=0>");


// COOKIE 설정
function getCookie(name)
{
	var nameOfCookie = name + "=";
	var x = 0;
	while (x <= document.cookie.length)
	{
		var y = (x+nameOfCookie.length);
		if (document.cookie.substring (x, y) == nameOfCookie)
		{
			if ((endOfCookie = document.cookie.indexOf (";", y)) == -1)
				endOfCookie = document.cookie.length;
			return unescape (document.cookie.substring(y, endOfCookie));
		}
		x = document.cookie.indexOf (" ", x) + 1;
		if (x == 0)
			break;
	}
	return "";
}

function setCookie (name, value, expires)
{
	if(expires==null) document.cookie = name + "=" + escape (value) + "; path=/";
	else document.cookie = name + "=" + escape (value) + "; path=/; expires=" + expires.toGMTString();
}
</script>
