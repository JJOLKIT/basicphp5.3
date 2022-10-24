<?
	$pageTitle = "FAQ";
	$pageTitleCategory = "FAQ분류관리";
	$tablename = "faq";
	$category_tablename = "faq_category";
	$category_use		= true;								// 카테고리 사용여부
	$pageRows		= 10;
	$uploadPath		= "/upload/faq/";					// 파일, 동영상 첨부 경로
	$maxSaveSize	= 50*1024*1024;						// 50Mb
	
	$userCon		= false;
	$useFile  		= true;								// 파일 첨부 [사용: true, 사용 안함 : false]

	if ($_REQUEST['pageRows'] != "") $pageRows = $_REQUEST['pageRows'];
?>