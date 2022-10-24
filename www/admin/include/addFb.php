<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/common.php"; ?>
<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/Fb.class.php";
include $_SERVER['DOCUMENT_ROOT']."/admin/include/loginCheck.php";


$notice = new Fb(99, 'admin_fb', array());

?>
<?
if (checkReferer($_SERVER["HTTP_REFERER"])) {
	
	$loadUrl = "";

	if ($_REQUEST['cmd'] == 'write') {

		$url = $_REQUEST['rurl'];
		
		$a = strrev($url);
		$a = explode("/", $a);

		

		
		if( trim(strrev($a[0])) == "" || trim(strrev($a[0])) == "index.php" ){

			if( trim(strrev($a[0])) == "index.php"){
				$url = str_replace("index.php", "", $url);
			}

			$loadUrl = $url;
			if(file_exists( $_SERVER['DOCUMENT_ROOT'].$loadUrl."/config.php" ) ){
				include_once $_SERVER['DOCUMENT_ROOT'].$loadUrl."/config.php";
			}

			if( $gubun != "" ){
				$_REQUEST['gubun'] = $gubun;
			}else{
				$_REQUEST['gubun'] = "menu";
			}
		}else{


			for($i = count($a) ; $i > 0; $i--){
				if($i < count($a) ){
					$nurl .= strrev($a[$i])."/";
				}
			}

			$loadUrl = $nurl; 

/*
			if(file_exists( $_SERVER['DOCUMENT_ROOT'].$loadUrl."/config.php" ) ){
				include_once $_SERVER['DOCUMENT_ROOT'].$loadUrl."/config.php";
			}
*/
			$_REQUEST['gubun'] = "menu";

		}


		$req['sval'] = $_REQUEST['name'];
		$req['stype'] = 'name';
		$req['sadmin_fk'] = $_REQUEST['admin_fk'];
		$rowPageCount = $notice->getCount($req);

		if($rowPageCount[0] > 0){
		
			//$r = $notice->deleteByName($_REQUEST['name'], $_REQUEST['admin_fk']);
			$r = $notice->deleteByName($req['sval'], $_REQUEST['admin_fk']);
			if ($r > 0) {
				echo "success2";
			} else {
				echo "fail";
			}

		}else{
 		
			

			$_REQUEST['tablename'] = $tablename;
			$_REQUEST['relation_url'] = $_REQUEST['rurl'];
		
			
			$r = $notice->insert($_REQUEST);

			
			if ($r > 0) {
				echo "success";
			} else {
				echo "fail";
			}
			
			
		}

	} else if ($_REQUEST['cmd'] == 'delete') {

		$no = $_REQUEST['no'];
		
		$r = $notice->delete($no);

		if ($r > 0) {
			echo "success";
		} else {
			echo "fail";
		}
	}
	

} else {
	echo "fail";
}
?>
