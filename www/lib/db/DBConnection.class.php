<?
/*


*/

class DBConnection {

	var $hostname;
	var $username;
	var $password;
	var $database;


	function DBConnection($target = "default") {
		// 기본 DB접속
		
		$db['default']['hostname'] = '127.0.0.1';
		$db['default']['username'] = 'root';
		$db['default']['password'] = 'sanggong1121';
		$db['default']['database'] = 'basicphp3';

		// SMS DB접속
		$db['sms']['hostname'] = '121.254.189.4';
		$db['sms']['username'] = 'vizensms';
		$db['sms']['password'] = 'vizen1208#';
		$db['sms']['database'] = 'vizensms';

		// 방문자통계 접속
		$db['log']['hostname'] = '211.115.91.134';
		$db['log']['username'] = 'monitor2';
		$db['log']['password'] = 'monitor09';
		$db['log']['database'] = 'dbo2';

		$this->hostname = $db[$target]['hostname'];
		$this->username = $db[$target]['username'];
		$this->password = $db[$target]['password'];
		$this->database = $db[$target]['database'];
	}

	function getConnection() {
		$conn = mysql_connect($this->hostname, $this->username, $this->password) or die ('DB연결 실패');
		mysql_select_db($this->database, $conn);
//		if ($this->hostname != 
		mysql_query("SET NAMES 'utf8'");
		return $conn;
	}

}
?>