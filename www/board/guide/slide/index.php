<? include_once $_SERVER['DOCUMENT_ROOT']."/include/common.php"; ?>
<? include_once $_SERVER['DOCUMENT_ROOT']."/header.php"; ?>

<div>
          <style>
               .main_visual .visual_wrap{height:800px; width:100%;background-image:url('/img/main_vs01.jpg');background-size:cover; background-repeat:no-repeat;background-position:center;}

               .main_visual .visual_wrap .txt{text-align:left;padding-top:340px; padding-left:10%;}
               .main_visual .visual_wrap .txt span{font-size:45px; font-weight: 300; letter-spacing: -1px;}
               .main_visual .visual_wrap .txt p{font-size:16px; font-weight: 500;}
               .main_visual .visual_wrap .txt .more{width:120px; display:block;height:38px;line-height:38px; text-align:center;box-sizing:border-box; border:1px solid rgba(0,0,0,.6); font-weight:500; font-size:14px; margin-top:30px;}
               .main_visual .visual_wrap .txt .more:hover{border:1px solid rgba(0,0,0,1.0); background:#000; color:#fff;}
               
               .main_visual .swiper-button-prev, .main_visual .swiper-button-next{opacity:1;color:transparent;width:40px; height:74px;  background-image:url('/img/main_prev.png'); background-repeat:no-repeat; background-position:center center; }
               .main_visual .swiper-button-next{background-image:url('/img/main_next.png');right:50px}
               .main_visual .swiper-button-prev{left:50px;}
               .main_visual .swiper-pagination {bottom:20px;}
               .main_visual .swiper-pagination-bullet{opacity:1; width:16px; height:16px; border:2px solid #fff; background-color:transparent; box-sizing: border-box; border-radius: 8px;}
               .main_visual .swiper-pagination-bullet-active{width:16px; height:16px; background:#fff; border:2px solid #fff;  border-radius: 8px; }
          </style>
               <div class="main_visual">
                    <div class="swiper-container">
                         <div class="swiper-wrapper">
                              <div class="swiper-slide">
                                   <a href="">
                                   <div class="visual_wrap tb pvisual" style="background-image:url('/img/main_vs01.jpg'); background-color:aqua;">
                                        <div class="txt">
                                             <span>Title01</span>
                                             <p>subtitle</p>
                                             <a href="" class="more">view more</a>
                                        </div>
                                   </div>
                                   </a>
                              </div>
                              <div class="swiper-slide">
                                   <a href="">
                                   <div class="visual_wrap tb pvisual" style="background-image:url('/img/main_vs02.jpg'); background-color: antiquewhite;">
                                        <div class="txt">
                                             <span>Title02</span>
                                             <p>subtitle</p>
                                             <a href="" class="more">view more</a>
                                        </div>
                                   </div>
                                   </a>
                              </div>
                              <div class="swiper-slide">
                                   <a href="">
                                   <div class="visual_wrap tb pvisual" style="background-image:url('/img/main_vs03.jpg'); background-color: aquamarine;">
                                        <div class="txt">
                                             <span>Title013</span>
                                             <p>subtitle</p>
                                             <a href="" class="more">view more</a>
                                        </div>
                                   </div>
                                   </a>
                              </div>
                              <div class="swiper-slide">
                                   <a href="">
                                   <div class="visual_wrap tb pvisual" style="background-image:url('/img/main_vs04.jpg'); background-color: cornflowerblue">
                                        <div class="txt">
                                             <span>Title04</span>
                                             <p>subtitle</p>
                                             <a href="" class="more">view more</a>
                                        </div>
                                   </div>
                                   </a>
                              </div>
                              <div class="swiper-slide">
                                   <a href="">
                                   <div class="visual_wrap tb pvisual" style="background-image:url('/img/main_vs05.jpg'); background-color: bisque;">
                                        <div class="txt">
                                             <span>Title05</span>
                                             <p>subtitle</p>
                                             <a href="" class="more">view more</a>
                                        </div>
                                   </div>
                                   </a>
                              </div>
                         </div>
                         <!-- Add Pagination -->
                         <div class="swiper-pagination"></div>
                         <!-- Add Arrows -->
                         <div class="swiper-button-next"></div>
                         <div class="swiper-button-prev"></div>
                    </div>
               </div>



          <!-- Initialize Swiper -->
          <script>
            $(function(){   
               if($('.main_visual').length > 0){
                     var main = new Swiper('.main_visual .swiper-container', {
                         loop:true,
                         speed : 1000,
                         autoplay:{
                              delay:4000,
                         },
                         pagination: {
                              el: '.swiper-pagination',
                              clickable: true,
                         },
                         navigation: {
                              nextEl: '.swiper-button-next',
                              prevEl: '.swiper-button-prev',
                         },

                    });
               }
          });
          </script>
</div>

<? include_once $_SERVER['DOCUMENT_ROOT']."/footer.php";?>