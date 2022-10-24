<?
ini_set("memory_limit", '-1');
session_start();
header("Content-Type: text/html; charset=UTF-8");

include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/member/Member.class.php";
$pageRows = 99999;


$notice = new Member($pageRows, 'member', $_REQUEST);
$result = $notice->getList($_REQUEST);


$today = getToday();

/** PHPExcel */
require_once $_SERVER['DOCUMENT_ROOT']."/lib/PHPExcel.php";
$objPHPExcel = new PHPExcel();
$sheet = $objPHPExcel->getActiveSheet();

$objPHPExcel->setActiveSheetIndex(0)
->setCellValue("A1", "번호")
->setCellValue("B1", "아이디")
->setCellValue("C1", "이름")
->setCellValue("D1", "닉네임")
->setCellValue("E1", "연락처")
->setCellValue("F1", "이메일")
->setCellValue("G1", "응모권")
->setCellValue("H1", "스탬프1")
->setCellValue("I1", "스탬프2")
->setCellValue("J1", "가입일")

;

// Add some data
$len = 1;
for ($i=2; $row=mysql_fetch_array($result); $i++) {
    //$objPHPExcel->getActiveSheet()->getStyle('B'.$i)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
    
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);

	$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);

	$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(25);
	
	$un = number_format($row['board1'] + ($row['board2']*2));

	
    // Add some data
    $objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue("A$i", $len)
	->setCellValue("B$i", $row['id'])
    ->setCellValue("C$i", $row['name'])
    ->setCellValue("D$i", $row['nickname'])
	->setCellValueExplicit("E$i", $row['cell'], PHPExcel_Cell_DataType::TYPE_STRING)
	->setCellValue("F$i", $row['email'])

	->setCellValueExplicit("G$i", $un, PHPExcel_Cell_DataType::TYPE_STRING)
	->setCellValueExplicit("H$i", number_format($row['board1']), PHPExcel_Cell_DataType::TYPE_STRING)
	->setCellValueExplicit("I$i", number_format($row['board2']), PHPExcel_Cell_DataType::TYPE_STRING)
	->setCellValue("J$i", ($row[registdate]))
	;
    
    $len++;
}

// Rename sheet
$objPHPExcel->getActiveSheet()->setTitle('회원목록');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

//$objPHPExcel->getActiveSheet()->getStyle('A1:L3')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
// 보더 스타일 지정

$defaultBorder = array(
    'style' => PHPExcel_Style_Border::BORDER_THIN,
    'color' => array('rgb'=>'000000')
);

$headBorder = array(
    'borders' => array(
        'bottom' => $defaultBorder,
        'left'   => $defaultBorder,
        'top'    => $defaultBorder,
        'right'  => $defaultBorder
    )
);

// 다중 셀 보더 스타일 적용
for ($j=1; $j<=$len; $j++) {
    foreach(range('A','J') as $i => $cell){
        $sheet->getStyle($cell.$j)->applyFromArray( $headBorder );
    }
}


// 파일의 저장형식이 utf-8일 경우 한글파일 이름은 깨지므로 euc-kr로 변환해준다.
$filename = iconv("UTF-8", "EUC-KR", "Member_".$today);


// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header("Content-Disposition: attachment;filename=".$filename.".xls");
header('Cache-Control: max-age=0');



$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');

exit;
?>
