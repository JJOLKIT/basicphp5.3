<?
	/*######################*/
	/*SSL*/
	/*######################*/
	if($_SERVER['HTTPS'] !== "on"){
		header('Location: '.COMPANY_URL."/admin/");
	}
?>