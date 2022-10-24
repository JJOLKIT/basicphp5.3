<?session_start();
include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body onload="document.frm.submit();"><!--   -->
<?
$savePath = $_SERVER['DOCUMENT_ROOT'].EDITOR_UPLOAD_PATH;
$callback = $_REQUEST['callback'];
$callback_func = $_REQUEST['callback_func'];
$fileCount = $_POST['fileCount'];

$url = $callback."?callback_func=".$callback_func;
$imgAlts = $_REQUEST['image_alt'];
$widths = $_REQUEST['width'];


?>

<form action="<?=$callback?>" method="post" id="frm" name="frm">
	<input type="hidden" name="callback_func" value="<?=$callback_func?>" />
	<input type="hidden" name="bNewLine" value="true" />
<?
if ($fileCount > 0) {
	for ($i=0; $i<$fileCount; $i++) {
		$files = $_FILES['Filedata'.$i];
		$tmp_name = $files['tmp_name'];
		$org_value = $files['name'];
		$upfile_error = $files['error'];

		$file_name = getRandFileName($org_value);

		if (!$upfile_error) {
			move_uploaded_file($tmp_name, $savePath.$file_name);
		}
?>
		<input type="hidden" name="sFileName[]" value="<?=$file_name?>" />
		<input type="hidden" name="sFileURL[]" value="/upload/editor/<?=$file_name?>" />
		<? if ($imgAlts) { ?>
		<input type="hidden" name="alt[]" value="<?=$imgAlts[$i]?>" />
		<? } ?>
		<? if ($widths) { ?>
		<input type="hidden" name="width" value="<?=$widths[$i]?>" />
		<? } ?>
<?
	}
?>

	<input type="hidden" name="fileCnt" value="<?=$fileCount?>" />
<?
}else{
?>
	<input type="hidden" name="errstr" value="error" />
<?
}
?>
</form>
</body>
</html>
