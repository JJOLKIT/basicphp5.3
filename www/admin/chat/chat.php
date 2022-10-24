<? include_once $_SERVER['DOCUMENT_ROOT']."/include/common.php"; ?>
<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/Chat.class.php";


include "config.php";
$today = getToday();
$notice = new Chat($pageRows, $tablename, $_REQUEST);
$rowPageCount = $notice->getCount($_REQUEST);
$result = $notice->getList($_REQUEST);

$_REQUEST['state'] = 1;
$_REQUEST['session_fk'] = $_REQUEST['ssession_fk'];
$r = $notice->updateState($_REQUEST);
?>
<!doctype html>
<html lang="ko">
<head>
<? include_once $_SERVER['DOCUMENT_ROOT']."/include/headHtml.php"; ?>

<style>
html, body {overflow:hidden;}

#list{
	overflow-y		: scroll;
	width			: 100%;
	height			: 600px;
	margin			: 0;
	padding			: 5px;
	border			: 1px solid #ccc;
	font-size		: 9pt;
	font-family		: 'dotum', '돋움';
}
form{
	width			: 100%;
	padding			: 5px;
	margin-top		: 5px;
	border			: 1px solid #ffdd88;
	background		: #ffffe3;
	font-size		: 0;
	box-sizing:border-box;
}
form #name {height:35px; padding:0 10px; border:1px solid #d1d1d1; box-sizing:border-box; display:inline-block; vertical-align:middle; width:100px;}
form #msg {width:calc(100% - 170px); height:35px; padding:0 10px; border:1px solid #d1d1d1; box-sizing:border-box; display:inline-block; margin-left:10px; margin-right:10px;}
form #btn {display:inline-block; vertical-align:middle; height:35px; padding:0 5px; box-sizing:border-box; background:#333; border:1px solid #333;  width:50px; cursor:pointer; color:#fff; }

	.bbs .chatlist {min-height:600px; border:1px solid #aaa ;box-sizing:border-box; width:100%; position:relative; padding:20px; overflow:hidden; background:rgba(178,199,217,.8); overflow:auto; max-height:600px;}
	.bbs .chatlist dl {min-height:25px;  margin-top:20px;  width: 100%; line-height:25px; float:none;}
	.bbs .chatlist dl:first-child {margin-top:0;}
	.clear:after {clear:both; content:''; display:block;}
	.bbs .chatlist dl dt {display:inline-block;  font-weight:bold;}
	.bbs .chatlist dl .msg { width:auto; display:inline-block; padding:5px 10px; border-radius:5px; position:relative;}
	.bbs .chatlist .response {}
	.bbs .chatlist .request { text-align:right;}
	.bbs .chatlist .response .msg {background:#fff; margin-left:10px;}
	.bbs .chatlist .request .msg{background:rgba(255,235,51,.8); width:auto; margin-left:10px; }
	.bbs .chatlist .msg:before {display:block; position:absolute; left:-10px; top:50%; margin-top:-4px; width:0; height:0; border-top:10px solid #fff; border-left:10px solid transparent; clear:both; content:'';}
	.bbs .chatlist .request .msg:before {border-top-color:rgba(255,235,51,.8);}
	.frm {margin-top:30px;}
	#msg {width:500px;}
	.no_data {text-align:center; border-top:1px dotted #111; clear:both; font-size:15px; padding:5px 0; margin-top:10px;} 

</style>
<script>
$(window).load(function() {
	
	//$(".chatlist").scrollTop($('.chatlist').height());
	var o = document.getElementById('list');
	o.scrollTop = o.scrollHeight;


});
var chatManager = new function(){

	var idle 		= true;
	var interval	= 500;
	var xmlHttp		= new XMLHttpRequest();
	var finalDate	= '';

	// Ajax Setting
	xmlHttp.onreadystatechange = function()
	{
		if (xmlHttp.readyState == 4 && xmlHttp.status == 200)
		{
			// JSON 포맷으로 Parsing
			res = JSON.parse(xmlHttp.responseText);
			finalDate = res.date;
			
			// 채팅내용 보여주기
			chatManager.show(res.data);
			
			// 중복실행 방지 플래그 OFF
			idle = true;
		}
	}

	// 채팅내용 가져오기
	this.proc = function()
	{
		// 중복실행 방지 플래그가 ON이면 실행하지 않음
		if(!idle)
		{
			return false;
		}

		// 중복실행 방지 플래그 ON
		idle = false;

		// Ajax 통신
		//console.log(finalDate);
		xmlHttp.open("GET", "process.php?sstartdate=" + encodeURIComponent(finalDate) + "&ssession_fk=<?=$_REQUEST[ssession_fk]?>&cmd=list&scomdate=<?=$today?>", true);
		xmlHttp.send();
	}

	// 채팅내용 보여주기
	this.show = function(data)
	{
		var o = document.getElementById('list');
		var dt, dd, dd2;

		// 채팅내용 추가
		for(var i=0; i<data.length; i++)
		{
			dl = document.createElement('dl');
			if(data[i].gb == 1){
				dl.classList.add('request');
			}else{
				dl.classList.add('response');
			}
		
			dt = document.createElement('dt');
			dt.appendChild(document.createTextNode(data[i].name));

			dl.appendChild(dt);

			//o.appendChild(dt);

			dd = document.createElement('dd');
			dd.appendChild(document.createTextNode(data[i].msg));
			dd.classList.add('msg');

			dd2 = document.createElement('dd');
			dd2.appendChild(document.createTextNode(data[i].registdate));
			dd2.classList.add('date');
			//o.appendChild(dd);

			dl.appendChild(dd);
			dl.appendChild(dd2);

			o.appendChild(dl);
			o.scrollTop = o.scrollHeight;
		}

		// 가장 아래로 스크롤
		
	}

	// 채팅내용 작성하기
	this.write = function(frm)
	{
		var xmlHttpWrite	= new XMLHttpRequest();
		var name			= frm.name.value;
		var msg				= frm.msg.value;
		var param			= [];
		
		// 이름이나 내용이 입력되지 않았다면 실행하지 않음
		if(name.length == 0 || msg.length == 0)
		{
			return false;
		}
		
		// POST Parameter 구축
		param.push("name=" + encodeURIComponent(name));
		param.push("msg=" + encodeURIComponent(msg));
		param.push("cmd=write");
		param.push("session_fk=<?=$_REQUEST['ssession_fk']?>");
		param.push("comdate=<?=$today?>");
		param.push("gb=1");
		// Ajax 통신
		xmlHttpWrite.open("POST", "process.php", true);
		xmlHttpWrite.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xmlHttpWrite.send(param.join('&'));
		
		// 내용 입력란 비우기
		frm.msg.value = '';
		
		// 채팅내용 갱신
		chatManager.proc();
		

	}

	// interval에서 지정한 시간 후에 실행
	setInterval(this.proc, interval);
}

</script>
<div class="program">

	
	<h2 class="title"><?=$_REQUEST['name']?>님과 채팅</h2>

		<div class="bbs">
			<div id="list" class="chatlist">
			<?if($rowPageCount[0] > 0){?>
			<?while($row=mysql_fetch_assoc($result)){?>
			<dl <?if($row['gb'] == 1){ echo "class='request'";}else{ echo "class='response'";}?>>
				<dt><?=$row['name']?></dt>
				<dd class="msg"><?=$row['msg']?></dd>
				<dd class="date"><?=$row['registdate']?></dd>
			</dl>
				<?}?>
				<?}?>

				<?
					if($_REQUEST['scomdate'] < $today){
				?>
				<p class="no_data">날짜가 지나 종료된 채팅방입니다.</p>
				<?}?>
			</div>
			<?
				if($_REQUEST['scomdate'] >= $today){
			?>
	
			<form onsubmit="chatManager.write(this); return false;">
			<input name="name" id="name" type="text" value="<?=$_SESSION['admin_name']?>" readonly/>
			<input name="msg" id="msg" type="text" />
			
			<input name="btn" id="btn" type="submit" value="입력" />
			</form>
			<?}?>
		</div>


</div>
</body>
</html>
