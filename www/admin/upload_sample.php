<?
ini_set("memory_limit", '-1');
session_start();
header("Content-Type: text/html; charset=UTF-8");

include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/common.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";
require_once $_SERVER['DOCUMENT_ROOT']."/lib/PHPExcel.php"; // PHPExcel.php을 불러와야 하며, 경로는 사용자의 설정에 맞게 수정해야 한다.

include_once $_SERVER['DOCUMENT_ROOT']."/lib/member/MemberDuplicate.class.php";

$data = new MemberDuplicate(9999, 'member_duplicate', $_REQUEST);

$objPHPExcel = new PHPExcel();

require_once  $_SERVER['DOCUMENT_ROOT']."/lib/PHPExcel/IOFactory.php"; // IOFactory.php을 불러와야 하며, 경로는 사용자의 설정에 맞게 수정해야 한다.

$uploadPath = "/upload/member/";
$maxSaveSize	= 50*1024*1024;					// 30Mb

$UpFile = $_FILES["file"];
$UpFileName = $UpFile["name"];


$UpFilePathInfo = pathinfo($UpFileName);
$UpFileExt = strtolower($UpFilePathInfo["extension"]);



if($UpFileExt != "xls" && $UpFileExt != "xlsx") {
	echo "엑셀파일만 업로드 가능합니다. (xls, xlsx 확장자의 파일포멧)";
	exit;
}
//-- 읽을 범위 필터 설정 (아래는 A열만 읽어오도록 설정함  => 속도를 중가시키기 위해)
class MyReadFilter implements PHPExcel_Reader_IReadFilter
{
	public function readCell($column, $row, $worksheetName = '') {
	// Read rows 1 to 7 and columns A to E only
	if (in_array($column,range('A','Z'))) {
		return true;
	}
		return false;
	}
}


$filterSubset = new MyReadFilter();

//$upload_path = $_SERVER["DOCUMENT_ROOT"]."/upload/order/Excel_".date("Ymd");
$upload_path = $_SERVER["DOCUMENT_ROOT"]."/upload/member";
//$upfile_path = $upload_path."/".date("Ymd_His")."_".$UpFileName;
$upfile_path = $upload_path."/".date("Ymd_His")."_".round(microtime(true)*1000).".".$UpFileExt;

// if(!is_dir($upload_path)){
// 	mkdir($upload_path, 777, true);
// }

if($UpFileExt == "xls") {
    $inputFileType = 'Excel5';
}else if( $UpFileExt == 'xlsx' ){
    $inputFileType = 'Excel2007';
}

if(is_uploaded_file($UpFile["tmp_name"])) {
	if(!move_uploaded_file($UpFile["tmp_name"],$upfile_path)) {
		echo returnURLMsg($data->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '업로드 중 오류가 발생하였습니다.1');
		exit;
	}

	try {


		// 업로드 된 엑셀 형식에 맞는 Reader객체를 만든다.
		$objReader = PHPExcel_IOFactory::createReaderForFile($upfile_path);

		// 읽기전용으로 설정
		$objReader->setReadDataOnly(true);
		$objReader->setReadFilter($filterSubset);

		// 엑셀파일을 읽는다
		$objExcel = $objReader->load($upfile_path);

		// 첫번째 시트를 선택
		$objExcel->setActiveSheetIndex(0);
		$objWorksheet = $objExcel->getActiveSheet();
		$rowIterator = $objWorksheet->getRowIterator();

		foreach ($rowIterator as $row) { // 모든 행에 대해서
			$cellIterator = $row->getCellIterator();
			$cellIterator->setIterateOnlyExistingCells(false); 
		}

		$maxRow = $objWorksheet->getHighestRow();
		$r = 0;

		for ($i = 2 ; $i <= $maxRow ; $i++) {
			/*
			$req['secretkey'] = $objWorksheet->getCell('A'. $i)->getValue();
 			$req['email'] = $objWorksheet->getCell('B' . $i)->getValue(); // A열
			$req['id'] = $objWorksheet->getCell('C' . $i)->getValue(); // B열
			$req['name'] = $objWorksheet->getCell('D' . $i)->getValue(); // D열
			$req['cell'] = $objWorksheet->getCell('E'. $i)->getValue();
			*/


			$req['secretkey'] = $objWorksheet->getCell('A'. $i)->getValue();
			$req['email'] = $objWorksheet->getCell('B'. $i)->getValue();
			$req['id'] = $objWorksheet->getCell('C'. $i)->getValue();
			$req['name'] = $objWorksheet->getCell('D'. $i)->getValue();
			$req['cell'] = $objWorksheet->getCell('G'. $i)->getValue();
			$req['birthday'] = $objWorksheet->getCell('N'. $i)->getValue();
			
			
			$r += $data->insert($req);
			

		}
		



		if($r > 0){
			unlink($upfile_path);
			echo returnURLMsg($data->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '정상적으로 업로드 되었습니다.');
		  }else{
			echo returnURLMsg($data->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '업로드 중 오류가 발생하였습니다.2');
		  }
	} catch (exception $e) {
		echo returnURLMsg($data->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '엑셀파일을 읽는도중 오류가 발생하였습니다.');
	}
}
​?>