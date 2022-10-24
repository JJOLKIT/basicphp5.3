<?
	$pageTitle			= "갤러리";
	$tablename			= "gallery";
	$pageRows			= 15;
	$uploadPath			= "/upload/gallery/";			// 파일, 동영상 첨부 경로

	$gubun = "board"; //즐겨찾기 형태 //board(대시보드 출력), menu
	
	$maxSaveSize		= 50*1024*1024;					// 50Mb
	$userCon			= false;
	$isComment			= false;							// 댓글 사용여부
	$useFile  			= true;							// 파일 첨부 [사용: true, 사용 안함 : false]
	$useMovie  	 		= false;							// 파일 첨부 [사용: true, 사용 안함 : false]
	$useRelationurl		= false;							// 관련URL 첨부 [사용: true, 사용 안함 : false]
	$useMain			= false;							//메인 노출 사용

	if ($_REQUEST['pageRows'] != "") $pageRows = $_REQUEST['pageRows'];
?>