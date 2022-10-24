<?
	$pageTitle 		= "팝업관리";
	$tablename 		= "popup";
	$pageRows		= 10;									// 리스트에 보여질 로우 수
	$gradeno		= 2;									// 관리자 권한 기준 [지점 사용시]
	$uploadPath		= "/upload/popup/";						// 파일, 동영상 첨부 경로
	$maxSaveSize	= 50*1024*1024;							// 50Mb

	// castle적용
	include_once($_SERVER['DOCUMENT_ROOT']."/include/castle.php");
?>

