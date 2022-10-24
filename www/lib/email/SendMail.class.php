<?
/*


*/

include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/db/DBConnection.class.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/email/class.phpmailer.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/email/class.smtp.php";

class SendMail {

	/*
		$email : 보내는 메일주소
		$name : 보내는이
		$mailto : 받는 메일주소
		$subject : 제목
		$content : 내용
	*/
	function send($email='', $name='', $mailto='', $subject='', $content=''){
		$smtp = SMTP_HOST;

		try {
			$mail = new PHPMailer(true);
			$mail->IsSMTP();
			$mail->SMTPAuth=true;
			$mail->MailerDebug = false;
			//$mail->SMTPDebug = 1;
			$mail->Host=$smtp;
			//$mail->Port=587;
			$mail->Port=SMTP_PORT;
			$mail->SMTPSecure = SMTP_SECURE; // SSL을 사용함
			$mail->Username=SMTP_USER;
			$mail->Password=SMTP_PASSWORD;
			$mail->SetFrom($email, $name);
			$mail->addAddress($mailto,'');
			$mail->Subject=$subject;
			$mail->MsgHTML($content);
			//$mail->AddAttachment($_SERVER['DOCUMENT_ROOT'].'/img/bbs_img07.gif');	//첨부파일
			$mail->Send();
		} catch (phpmailerException $e) {
			echo 1;
			echo $e->errorMessage();
		} catch (Exception $e) {
			echo 2;
			echo $e->getMessage();
		}
	}

	/*
		$email : 보내는 메일주소
		$name : 보내는이
		$mailto : 받는 메일주소
		$subject : 제목
		$content : 내용
		$uploadpath : 첨부파일경로
		$filename : 첨부파일명
		$filename_org : 원본 첨부파일명
	*/
	function sendFile($email='', $name='', $mailto='', $subject='', $content='', $uploadpath='', $filename=array(), $filename_org=array()){
		$emailList = split(",", $mailto);
		if (sizeof($emailList) > 0) {
			for ($i=0; $i<sizeof($emailList); $i++) {
				$this->mailSend($email, $name, $emailList[$i], $subject, $content, $uploadpath, $filename, $filename_org);
			}
		} else {
			$this->mailSend($email, $name, $mailto, $subject, $content, $uploadpath, $filename, $filename_org);
		}
	}

	// 실제 메일발송
	function mailSend($email='', $name='', $mailto='', $subject='', $content='', $uploadpath='', $filename=array(), $filename_org=array()) {
		$smtp = SMTP_HOST;
		try {
			$mail = new PHPMailer(true);
			$mail->IsSMTP();
			$mail->MailerDebug = false;
			$mail->Host=$smtp;
			//$mail->SMTPAuth=true;
			$mail->Port=25;
			$mail->Username=SMTP_USER;
			$mail->Password=SMTP_PASSWORD;
			$mail->SetFrom($email, $name);
			
			$mail->Subject=$subject;
			$mail->MsgHTML($content);
			
			for ($i=0; $i<count($filename); $i++) {
				$mail->AddAttachment($_SERVER['DOCUMENT_ROOT'].$uploadpath.$filename[$i], $filename_org[$i]);	//첨부파일
			}
			$mail->addAddress($mailto, '');
			$mail->Send();
		} catch (phpmailerException $e) {
			echo $e->errorMessage();
		} catch (Exception $e) {
			echo 2;
			echo $e->getMessage();
		}
	}


}


?>