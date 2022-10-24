$(function(){
	// Mobile Ui 
	
	var mobile = {
		open : function(){
			$('.mo_menu').stop().animate({'left':'0'},400,'easeInOutQuad');
		},
		close : function(){
			$('.mo_menu').stop().animate({'left':'-200%'}, 400, 'easeInOutQuad');
		},
		down : function(target){
			$(target).addClass('on');
			$(target).next().stop().slideDown(400,'easeInOutQuad');
			
		},
		up : function(target){
			$(target).removeClass('on');
			$(target).next().stop().slideUp(400, 'easeInOutQuad');
		},
		siblingsUp : function(target){
			$(target).parent().siblings('li').children('a').removeClass('on');
			$(target).parent().siblings('li').children('ul').stop().slideUp(400, 'easeInOutQuad');
		},
		bgOn : function(){
			$('.mbg').stop().fadeIn(400);
		},
		bgOff : function(){
			$('.mbg').stop().fadeOut(400);
		}
	}

	
	$('.mo_open').on('click',function(){
		mobile.open();
		mobile.bgOn();
		//$('html,body').css({'overflow':'hidden' , 'height' : '100%'});
		//$.fn.fullpage.setAutoScrolling(false);
	});
	$('.mo_close').on('click',function(){
		mobile.close();
		mobile.bgOff();
		//$('html,body').css({'overflow':'visible' , 'height' : 'initial'});
	});

	$('.mgnb .depth1 > li > a').on('click',function(){
		mobile.siblingsUp(this);
		if($(this).hasClass('on')){
			mobile.up(this);
		}else{
			mobile.down(this);
		}
	});


});





/* 삭제하지 말것 */
String.prototype.replaceAll = function(org, dest) {
    return this.split(org).join(dest);
}

function refresh_captcha(){
	document.getElementById("capt_img").src="/include/captcha.php?waste="+Math.random(); 
	return false;
}


/* - - - - - - - - - */ 