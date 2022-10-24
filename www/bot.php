<? include_once $_SERVER['DOCUMENT_ROOT']."/include/common.php"; ?>

<!doctype html>
<html lang="ko">
<head>
<? include_once $_SERVER['DOCUMENT_ROOT']."/include/headHtml.php"; ?>

<script>
$(document).on('click','#openBot',function(){
		//window.open('/bot/index.php', 'chat', 'width=450px, height=722px, scrollbar=no');
		$('.bot_layer').css('display','block');

	});
</script>
<style>
	.size {max-width:1280px; margin:0 auto; padding: 50px ; box-sizing:border-box;}
	#openBot {width:50px; height:50px; display:block;}
	#openBot img {width:100%; }

	.bot_layer {position:fixed; display:none; box-shadow:5px 5px 10px rgba(0,0,0,.5); width:450px; height:720px; right:50px; top:50%; margin-top:-360px;}
	.bot_layer iframe{width:100%; height:100%; }
</style>
</head>
<body>
<div class="size">
	<h2>상담AI</h2>
	<a href="javascript:;" id="openBot"><img src="/img/bot_ico.png"/></a>

	<div class="bot_layer">
		<?include_once $_SERVER['DOCUMENT_ROOT']."/bot/index.php";?>
	</div>
</div>
</body>
</html>

