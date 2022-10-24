<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

$filename = "filename";
$uploadFullPath = $_SERVER['DOCUMENT_ROOT']."/upload/ckeditor/";
$maxSaveSize = 50*1024*1024;

//outlog("fileupload = ".$_FILES);



$file_ext = explode(".", strrev($filename)); // . 으로 구분
$file_ext = strrev($file_ext[0]); 
$file_ext = strtolower($file_ext); // 확장자명 소문자로 변환
$isSize = false;


// 업로드 확장자 블랙리스트
$black_list = array("html", "htm", "php", "js", "jsp", "asp", "HTML", "HTM", "PHP", "JS", "JSP", "ASP", "php-x", "PHP-X", "exe", "EXE");
if(in_array($file_ext, $black_list)){
	exit;
	
}else{

	$files = $_FILES[$filename];
	$tmp_name = $files['tmp_name'];
	if ($tmp_name) {

		if ($files['size'] <= $maxSaveSize) {
		
			$org_name = $files['name'];
			$file_name = getRandFileName($org_name);
			if (!$files['error']) {
				move_uploaded_file($tmp_name, $uploadFullPath.$file_name);
			}

			// db insert value
			$req[$filename] = $file_name;
			$req[$filename.'_org'] = $org_name;
			if ($isSize) {
				$tmp = substr($filename, -1);
				if ((int)$tmp > 0) {
					$req['filesize'.$tmp] = $files['size'];
				} else {
					$req['filesize']=$files['size'];
				}
			}
		} else {
			exit;
		}
	}


$arr = array('url'=> "/upload/ckeditor/".$req['filename'] );
echo json_encode($arr);
exit;
}