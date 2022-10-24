<? include_once $_SERVER['DOCUMENT_ROOT']."/include/common.php"; ?>
<?
	include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
	include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
	include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
	include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
	include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

	include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/Notice.class.php";
	include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/Gallery.class.php";
	include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/Faq.class.php";


	$_REQUEST['stitle'] = 1;
	$_REQUEST['sname'] = "가나다";
	$notice = new Notice(10, 'notice', $_REQUEST);



	//gallery
	$greq = $_REQUEST;
	$greq['sgrade'] = 3;
	$gallery = new Gallery(20, 'my_gallery', $greq);
	

	//faq
	$freq['stitle'] = "3";
	$faq = new Faq(5, 'user_faq', 'user_faq_category', $freq);
	

	//자료실
	$data_notice = new Notice(8, 'data_notice', array());
	$dresult = $data_notice->getList(array());

	//1:1문의 
	$creq['sname'] = "상공";

	$consult = new Gallery(10, 'consult', $creq);
	$cresult = $consult->getList($creq);


















?>



