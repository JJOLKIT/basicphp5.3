<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/common.php"; ?>
<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/Bot.class.php";

include $_SERVER['DOCUMENT_ROOT']."/admin/include/loginCheck.php";
include "config.php";

$product = new Bot($pageRows, $tablename, $_REQUEST);
?>
<!doctype html>
<html lang="ko">
<head>
<? include $_SERVER['DOCUMENT_ROOT']."/admin/include/headHtml.php" ?>
</head>
<body>
<?
if (checkReferer($_SERVER["HTTP_REFERER"])) {

	if ($_REQUEST['cmd'] == 'write') {
		$r = $product->insert($_REQUEST);
		if ($r > 0) {			
			echo returnURLMsg($product->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '정상적으로 저장되었습니다.');
		} else {
			echo returnURLMsg($product->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '요청처리중 장애가 발생하였습니다.');
		}

	} else if ($_REQUEST['cmd'] == 'edit') {


		$r = $product->update($_REQUEST);

		if ($r > 0) {
			echo returnURLMsg($product->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '정상적으로 수정되었습니다.');
		} else {
			echo returnURLMsg('edit.php?no='.$_REQUEST[no], '요청처리중 장애가 발생하였습니다.');
		}
	} else if ($_REQUEST['cmd'] == 'groupDelete') {

		$no = $_REQUEST['no'];
		
		$r = 0;
		for ($i=0; $i<count($no); $i++) {
			$r += $product->delete($no[$i]);
			
		}

		if ($r > 0) {
			echo returnURLMsg($product->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '총 '.$r.'건이 삭제되었습니다.');
		} else {
			echo returnURLMsg($product->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '요청처리중 장애가 발생하였습니다.');
		}

	} else if ($_REQUEST['cmd'] == 'groupMain') {

		$no = $_REQUEST['no'];
		
		$r = 0;
		for ($i=0; $i<count($no); $i++) {
			$r += $product->updateMain($no[$i], 1);
		}

		if ($r > 0) {
			echo returnURLMsg($product->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '총 '.$r.'건이 메인노출되었습니다.');
		} else {
			echo returnURLMsg($product->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '요청처리중 장애가 발생하였습니다.');
		}

	} else if ($_REQUEST['cmd'] == 'groupYesDisplay') {

		$no = $_REQUEST['no'];
		
		$r = 0;
		for ($i=0; $i<count($no); $i++) {
			$r += $product->updateDisplay($no[$i], 1);
		}

		if ($r > 0) {
			echo returnURLMsg($product->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '총 '.$r.'건이 판매함 처리되었습니다.');
		} else {
			echo returnURLMsg($product->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '요청처리중 장애가 발생하였습니다.');
		}

	} else if ($_REQUEST['cmd'] == 'groupNoDisplay') {

		$no = $_REQUEST['no'];
		
		$r = 0;
		for ($i=0; $i<count($no); $i++) {
			$r += $product->updateDisplay($no[$i], 3);
		}

		if ($r > 0) {
			echo returnURLMsg($product->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '총 '.$r.'건이 판매안함 처리되었습니다.');
		} else {
			echo returnURLMsg($product->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '요청처리중 장애가 발생하였습니다.');
		}

	} else if ($_REQUEST['cmd'] == 'delete') {

		$no = $_REQUEST['no'];
		
		$r = $product->delete($no);
		$product->deleteOption($no);

		if ($r > 0) {
			echo returnURLMsg($product->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '정상적으로 삭제되었습니다.');
		} else {
			echo returnURLMsg($product->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '요청처리중 장애가 발생하였습니다.');
		}
	} else if ($_REQUEST['cmd'] == 'deleteRelation') {
	    
	    $no = $_REQUEST['no'];
	    
	    $r = $product->deleteRelation($no);
	    
	    if ($r > 0) {
	        echo "ok";
	    } else {
	        echo "fail";
	    }
	} else if ($_REQUEST['cmd'] == 'insertRelation') {
	    
	    $no = $_REQUEST['no'];
	    
	    $r = 0;
	    for ($i=0; $i<count($no); $i++) {
	        $r += $product->insertRelation($_REQUEST['product_fk'],$no[$i]);
	    }
	    
	    if ($r > 0) {
	        echo "
                <script>
                    window.opener.listRelation();
                    window.close();
                </script>
                ";
	    } else {
	        echo "
                <script>
                    window.opener.listRelation();
                    window.close();
                </script>
                ";
	    }
	}else if ($_REQUEST['cmd'] == 'editInfo') {

		$r = $product->updateInfo($_REQUEST);

		if ($r > 0) {
			echo returnURLMsg($product->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'info.php'), 0, $_REQUEST), '정상적으로 수정되었습니다.');
		} else {
			echo returnURLMsg($product->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'info.php'), 0, $_REQUEST), '요청처리중 장애가 발생하였습니다.');
		}
	}else if($_REQUEST['cmd'] == "insertSeq"){
		$no = $_REQUEST['no'];
		$seq = $_REQUEST['seq'];

		$r = 0;
		for($i = 0; $i < count($no); $i++){
			$r += $product->insertSeq($no[$i], $seq[$i]);
		}

		if($r > 0){
			echo "
			<script>
				alert('정상적으로 저장되었습니다.');
				window.close();
				window.opener.history.go();
			</script>
			";
			
		}else{
			echo "
				<script>
					alert('요청처리중 장애가 발생했습니다.');
					location.href='seq.php';
				</script>
			";
		}
	}


} else {
	echo returnURLMsg($product->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '요청처리중 장애가 발생하였습니다.1');
}
?>
</body>
</html>