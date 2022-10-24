<?
	/*######################*/
	/*SSL*/
	/*######################*/
	if($_SERVER['HTTPS'] !== "on"){
		header('Location: '.COMPANY_URL);
	}
	
	if(SSL_USE){
		$host = "https://".$_SERVER['HTTP_HOST'];
	}else{
		$host = "http://".$_SERVER['HTTP_HOST'];
	}
	
	if($host != COMPANY_URL){
		header('Location: '.COMPANY_URL);
	}
?>