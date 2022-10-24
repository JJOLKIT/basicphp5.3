<?
/*


*/

include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/dbConfig.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/db/DBConnection.class.php";

class Stipulation {

	var $conn;

	// 생성자
	function Admin() {
	}


	// 수정
	function update($req="") {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();

		$sql = "
			UPDATE stipulation SET
				privacy_text='".$req['privacy_text']."', privacy_mini_text='".$req['privacy_mini_text']."', join_text='".$req['join_text']."', editname='".$req['editname']."', editdate=now()
			WHERE no = 1";

		$result = mysql_query($sql, $conn);
		mysql_close($conn);
		return $result;
	}

	// 목록
	function getData() {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();

		$sql = "
			SELECT 
				no, privacy_text, privacy_mini_text, join_text, editname, editdate
			FROM stipulation
			WHERE no=1";
		
		$result = mysql_query($sql, $conn);
		mysql_close($conn);
		$data = mysql_fetch_assoc($result);

		return $data;
	}

	
	

}


?>