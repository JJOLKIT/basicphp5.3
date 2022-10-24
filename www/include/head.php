<?
	include_once $_SERVER['DOCUMENT_ROOT']."/include/common.php";
	include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";


?>
<!doctype html>
<html lang="ko">
<head>


<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0, user-scalable=no"> 
<meta name="format-detection" content="telephone=no, address=no, email=no">
<meta name="title" content="">
<meta name="keywords" content="">
<meta name="description" content="">
<meta name="url" content="<?=COMPANY_URL?>">
<meta name="image" content="<?=COMPANY_URL?>/img/og_img.jpg">
<!-- <meta name="image" content="http://sanggong.net/img/sns_img.png"> -->
<meta property="og:title" content="">
<meta property="og:keywords" content="">
<meta property="og:description" content="">
<meta property="og:url" content="<?=COMPANY_URL?>">
<meta property="og:image" content="<?=COMPANY_URL?>/img/og_img.jpg"/>
<meta name="og:type" charset=""content="website"/>
<meta name="image:width"	content="800"/>
<meta name="image:height" content="400"/>
<link rel="canonical" href="<?=COMPANY_URL?>"/>


<title>상공</title>            
<link rel="icon" type="image/x-icon" href="/favicon.ico"/>
<!-- Styles -->  
<link rel="stylesheet" href="/css/reset.css?v=<?=time()?>">
<link rel="stylesheet" href="/css/common.css?v=<?=time()?>">
<link rel="stylesheet" href="/css/content.css?v=<?=time()?>">
<link rel="stylesheet" href="/css/program.css?v=<?=time()?>">
<link rel="stylesheet" href="/css/responsive.css?v=<?=time()?>">


<!-- scripts -->         
<script src="/js/jquery-1.12.0.min.js" type="text/javascript"></script>
<script src="/js/easing.js" type="text/javascript"></script>
<script type="text/javascript" src="/js/common.js"></script>
<script type="text/javascript" src="/js/function_jquery.js"></script>
<script type="text/javascript" src="/js/function.js"></script>
<script type="text/javascript" src="/smarteditor/js/HuskyEZCreator.js"></script>
<script type="text/javascript" src="/js/calendar_beans_v2.0.js"></script>
<!--<script type="text/javascript" src="/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="/js/jquery.validate.unobtrusive.min.js"></script>
-->
<script type="text/javascript" src="/js/jquery.form.js"></script>
<script type="text/javascript" src="/js/jquery-ui.min.js"></script>
<script type="text/javascript" src="/js/swiper.min.js"></script>
<link rel="stylesheet" href="/css/swiper.css"/>

</head>
<?
	$gnb= file_get_contents( $_SERVER['DOCUMENT_ROOT']."/json/gnb.json" );
    if( $gnb ) $json = json_decode( $gnb, true );
    $pgc = array('company', 'business', 'support', 'contact');
?>
	
<body>
	<div class="wrapper">

