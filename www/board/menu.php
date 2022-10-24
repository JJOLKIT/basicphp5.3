<? include_once $_SERVER['DOCUMENT_ROOT']."/include/common.php"; ?>
<!doctype html>
<html lang="ko">
<head>
<? include_once $_SERVER['DOCUMENT_ROOT']."/include/headHtml.php"; ?>
<script type="text/javascript">
$(function() {
    $(' .m').click(function() {
        var obj = $(this).next('.s');
        obj.slideToggle();
        $('.s').not(obj).slideUp();
    });
});
</script>
<script>
	$(document).on('click','#openChat',function(){
		window.open('/chat/index.php', 'chat', 'width=500px, height=800px, scrollbar=no');
	});
	$(document).on('click','#openBot',function(){
		window.open('/bot/index.php', 'chat', 'width=515px, height=722px, scrollbar=no');

	});
</script>
<style>
	#openChat {position:absolute; display:block; right:20px; bottom:20px; width:60px;}
	#openChat img {width:100%;}
	#openBot {position:absolute; display:block; right:100px; bottom:20px; width:60px;}
	#openBot img {max-width:100%;}


</style>
</head>
<body>
<div class="program_menu">
	<h1><img src="/img/sanggong_logo.png" /></h1>
	<ul>
		<li>
			<div class="m">게시판</div>
			<ul class="s" style="display:block;">
				<li><a href="/board/notice/index.php" target="main">공지사항</a></li>
				<li><a href="/board/listImage/index.php" target="main">목록이미지</a></li>
				<li><a href="/board/postImage/index.php" target="main">포스트형갤러리</a></li>
				<li><a href="/board/gallery/index.php" target="main">일반갤러리</a></li>
				<li><a href="/board/gallery_ct/index.php" target="main">카테고리갤러리</a></li>
				<li><a href="/board/grid/" target="main">그리드갤러리</a></li>
				<li><a href="/board/faq/index.php" target="main">FAQ</a></li>
				<!--
				<li><a href="/board/reply/index.php" target="main">답변게시판</a></li>
				-->
				<li><a href="/board/reply2/index.php" target="main">답변게시판</a></li>
				<li><a href="/board/movie/index.php" target="main">동영상게시판</a></li>
				<li><a href="/board/popup.php" target="main">팝업</a></li>
				<li><a href="/board/formmail/index.php" target="main">폼메일</a></li>
				<li><a href="/board/consult/index.php" target="main">온라인상담</a></li>
			</ul>
		</li>
		<li>
			<div class="m">회원</div>
			<ul class="s">
				<li><a href="/member/write.php" target="main">회원가입</a></li>
				<li><a href="/member/login.php" target="main">로그인</a></li>
				<li><a href="/member/idpwsearch.php?init=id" target="main">아이디 찾기</a></li>
				<li><a href="/member/idpwsearch.php?init=pw" target="main">비밀번호 찾기</a></li>
				<li><a href="/mypage/index.php" target="main">회원정보수정</a></li>
				<li><a href="/member/secede.php" target="main">회원탈퇴</a></li>
                    <li><a href="/member/agree.php" target="main">이용약관</a></li>
                    <li><a href="/member/policy.php" target="main">개인정보취급방침</a></li>
                    <li><a href="/include/logout.php" target="main">로그아웃</a></li>
			</ul>
		</li>
          <li>
			<div class="m">가이드</div>
			<ul class="s">
				<li><a href="/board/guide/index.php" target="main">스타일 가이드</a></li>
                    <li><a href="/board/guide/font.php" target="main">폰트 사이즈</a></li>
                    <li><a href="/board/guide/slide/" target="main">슬라이드</a></li>
                    
			</ul>
		</li>
          <li>
			<div class="m">쇼핑몰</div>
			<ul class="s">
				<li><a href="/shop/product/index.php" target="main">상품목록</a></li>
				<li><a href="/shop/product/view.php" target="main">상품보기</a></li>
				<li><a href="/shop/cart/index.php" target="main">장바구니</a></li>
				<li><a href="/shop/order/index.php" target="main">주문내역</a></li>
				<li><a href="/shop/keep/index.php" target="main">보관상품</a></li>
			</ul>
		</li>
	</ul>

	<a href="javascript:;" id="openBot"><img src="/img/bot_ico.png"/></a>
	<a href="javascript:;" id="openChat"><img src="/img/chat_ico.png"/></a>
	<a href="http://nfvforum.sanggong.net:8080/">네트워크</a>


</div>
</body>
</html>
