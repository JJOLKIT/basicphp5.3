<? include_once $_SERVER['DOCUMENT_ROOT']."/include/common.php"; ?>
<? include_once $_SERVER['DOCUMENT_ROOT']."/header.php"; ?>

<div id="sub" class="">
	<div class="size">
          <h2>폼</h2>
          <div>
               <label for="name">텍스트</label><input type="text" id="name" name="name" />
          </div>
          <div>
               <label for="password">비밀번호</label><input type="password" id="password" name="password" />
          </div>
          <div class="check_box">
               <input type="checkbox" id="ch01" name="ch01" value="" /><label for="ch01">체크</label>
          </div>
         
          <div class="radio_box">
               <input type="radio" id="ra01" name="ra" value="1"/><label for="ra01">예</label>
               <input type="radio" id="ra02" name="ra" value="2"/><label for="ra02">아니오</label>
          </div>
          <div>
               선택
              <div class="select">
                    <select id="stype" name="stype" >
                         <option value="">카테고리1</option>
                         <option value="">카테고리2</option>
                    </select>
               </div>
          </div>
     </div>
</div>

<? include_once $_SERVER['DOCUMENT_ROOT']."/footer.php";?>