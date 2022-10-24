<?
/*


*/

include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/db/DBConnection.class.php";

class Log {

	var $tableName;			// 테이블명
	var $conn;

	// 생성자
	function Log($tableName='') {
		$this->tableName = $tableName;
	}

	// 로그DB 데이터 추출
	function setLog($connectid, $no) {
		$dbconn = new DBConnection('log');
		$conn = $dbconn->getConnection();

		$sql = "
			SELECT 
				connectid, urlparam, refhost, refpage, refparam,
        		refsearch, ip,
				(SELECT refname FROM refsite WHERE url=refhost) AS refname, sessionid,
				(SELECT name FROM campaign WHERE sitenum=".WEBLOG_NUMBER." AND  INSTR(urlparam, value) > 0 ORDER BY sitenum DESC LIMIT 1) AS campaigntext
			FROM url
			WHERE connectid = '$connectid'
			";

		$result = mysql_query($sql, $conn);

		mysql_close($conn);
		$row = mysql_fetch_assoc($result);

		$refname = $row[refname];
		$refhost = $row[refhost];
		$refpage = $row[refpage];
		$refparam = $row[refparam];
		$campaigntext = $row[campaigntext];
		$refsearch = $row[refsearch];
		$ip = $row[ip];
		$sessionid = $row[sessionid];

		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();

		$sql2 = "
			UPDATE ".$this->tableName." SET
				connectid='$connectid', refname='$refname', refhost='$refhost', refpage='$refpage', refparam='$refparam',
				campaigntext='$campaigntext', refsearch='$refsearch', ip='$ip', sessionid='$sessionid'
			WHERE
				no = $no ";

		mysql_query($sql2, $conn);

		mysql_close($conn);

	}

	// 목록
	function getData($no=0) {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();

		$sql = "
			SELECT
				connectid, refname, refhost, refpage, refparam,
		    	campaigntext, refsearch, ip, sessionid
			FROM ".$this->tableName."
			WHERE no = ".$no;
		
		$result = mysql_query($sql, $conn);
		mysql_close($conn);
		$data = mysql_fetch_assoc($result);

		return $data;
	}
	

}


?>