<? include_once $_SERVER['DOCUMENT_ROOT']."/include/common.php"; ?>
<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/Bot.class.php";

$bot = new Bot(9999, 'bot', $_REQUEST);
$result = $bot->getList($_REQUEST);

?>


<!doctype html>
<html lang="ko">
<head>
<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/headHtml.php"; ?>
<style>
	.wrapper {max-width:1200px; margin:0 auto; position:relative;}
	.inner {max-width:515px; height:720px; margin:0 auto; background:rgba(178,199,217,.8); overflow:auto;}
	.botlist {position:relative; }
	.botlist .bot {padding-left:10px; box-sizing:border-box; padding-right:10px; padding-top:15px; padding-bottom:15px; }
	.botlist .bot .title {padding-left:50px; height:40px; position:relative; box-sizing:border-box;  word-break:keep-all; overflow:hidden;display:table; width:100%;}
	.botlist .bot .title span{font-size:14px;letter-spacing:-.5px;line-height:20px;display:table-cell; width:100%; height:100%; vertical-align:middle;}
	.botlist .bot .title:before {width:40px; height:40px; border-radius:40px; background-color:#2d2d2f ;background-image:url('/img/logo.png');background-repeat:no-repeat; background-size:60%; background-position:center center;display:block; clear:both; content:'';position:absolute; left:0; top:0; }
	.botlist .bot ul {margin-top:40px; box-sizing:border-box; width:102%; margin-left:-1%; margin-top:0%;}
	.botlist .bot ul:after {clear:both; content:''; display:block;}
	.botlist .bot ul li {margin-left:2%;margin-top:2%; display:inline-block; }
	.botlist .bot ul li a {display:block; height:35px; padding:0 10px;letter-spacing:-.5px;background:#333; color:#fff; text-align:center; border-radius:5px; line-height:35px; font-size:13px;}
	.botlist .mymsg {padding: 10px 20px 0; box-sizing:border-box; text-align:right;}
	.botlist .mymsg .title {display:inline-block; line-height:20px; background:#fff; border-radius:5px; padding:10px; font-weight:bold;font-size:14px}
	.botlist .bot img {max-width:100%;margin-top:10px;}
	.botlist .bot .msg {font-size:14px;background:#fff; border-radius:10px;padding:15px ;word-break:keep-all;margin-top:5px;margin-left:40px; width:calc(90% - 40px);letter-spacing:-.5px;padding-bottom:20px;}
	.botlist .bot .msg p span{letter-spacing:-.25px;display:inline-block; font-size:15px;border-bottom:2px solid #3deedf; margin-bottom:20px ; padding-bottom:10px;font-weight:bold;padding-right:15px;line-height:20px;}
	.botlist .bot .msg .bt_wrap{margin-left:-6%; width:106%;margin-top:15px; }
	.botlist .bot .msg .bt_wrap:after{clear:both; content:''; display:block;}
	.botlist .bot .msg .bt_wrap a{display:block;background:rgba(50,50,50,0.8);border-radius:5px;transition:all .4s; -webkit-transition:all .4s;height:40px; line-height:40px;font-size:14px;;color:#fff;width:44%;margin-left:6%; float:left;text-align:center;}
	.botlist .bot .msg .bt_wrap a:hover{color: #3deedf;background:rgba(50,50,50,1);}
</style>
<script>
	function nextMsg(no, btn){
		var myMsg = '';
		myMsg += '<div class="mymsg"><p class="title">'+btn+'</p></div>';
		$('.botlist').append(myMsg);
		//$(".inner").scrollTop($('.botlist').height());


		$.ajax({
			url : 'botajax.php',
			data : {
				'no' : no
			},
			success : function(data){
				var r = data.trim();
				$('.botlist').append(r);
				setTimeout(function(){

					//$(".inner").scrollTop((Number($('.botlist').height())-$('.bot:last').outerHeight()));
					$('.inner').scrollTop($('.botlist').height()-$('.bot:last').height() - $('.bot:last').prev('.bot').height() - 110 );
				}, 50);
			}
		});
	}

	function goLocation(url){

		window.opener.location.href = url;
		window.close();
	}

</script>
</head>
<body>
	<div class="wrapper">
		<div class="inner">
			<div class="botlist">
				<div class="bot">
					<p class="title"><span>반갑습니다! 상공 상담AI입니다. 무엇을 도와드릴까요?</span></p>
					<ul>
						
						<?while($row=mysql_fetch_assoc($result)){?>
						<li><a href="javascript:;" onclick="nextMsg(<?=$row['no']?>, '<?=$row[title]?>');"><?=$row['title']?></a></li>
						<?}?>
					</ul>
				</div>
			</div>
		</div>
	</div>
</body>
</html>
