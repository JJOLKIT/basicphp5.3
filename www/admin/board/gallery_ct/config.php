<?
	$pageTitle			= "갤러리_카테고리";
	$tablename			= "gallery_ct";
	$category_tablename = "gallery_category";
	$pageRows			= 15;
	$uploadPath			= "/upload/gallery_ct/";			// 파일, 동영상 첨부 경로
	$gubun = "board";

	$maxSaveSize		= 50*1024*1024;					// 50Mb
	$userCon			= false;
	$isComment			= false;							// 댓글 사용여부
	$useFile  			= true;							// 파일 첨부 [사용: true, 사용 안함 : false]
	$useMovie  	 		= false;							// 파일 첨부 [사용: true, 사용 안함 : false]
	$useRelationurl		= false;							// 관련URL 첨부 [사용: true, 사용 안함 : false]
	$useMain			= false;							//메인 노출 사용

	if ($_REQUEST['pageRows'] != "") $pageRows = $_REQUEST['pageRows'];
?>