<?
/*


*/

include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/db/DBConnection.class.php";

class Comment {

	// 검색 파라미터 (초기 개발시 검색조건 세팅필요)
	var $param = array (
					"parent_fk"
				);

	var $pageRows;			// 페이지 로우수
	var $startPageNo=0;		// limit 시작페이지
	public $reqPageNo=1;	// 요청페이지
	var $conn;
	var $parent_fk;
	var $tablename;

	// 생성자
	function Comment($pageRows=0, $tablename, $request='') {
		$this->pageRows = $pageRows;
		$this->reqPageNo = ($request['reqPageNo'] == 0) ? 1 : $request['reqPageNo'];	// 요청페이지값 없을시 1로 세팅
		if ($request['reqPageNo'] > 0) {
			$this->startPageNo = ($request['reqPageNo']-1) * $this->pageRows;
		}
		$this->parent_fk = $request['no'];
		$this->tablename = $tablename;
	}

	// 검색 파라미터 queryString 생성
	function getQueryString($page="", $no=0, $request='') {	
		$str = '';
		
		for ($i=0; $i<count($this->param); $i++) {
			if (chkIsset($request[$this->param[$i]])) {
				$str = $str.$this->param[$i]."=".$request[$this->param[$i]]."&";
			}
		}

		if ($no > 0) $str = $str."no=".$no;			// no값이 있을 경우에만 파라미터 세팅 (페이지 이동시 no필요 없음)

		$return = '';
		if ($str) {
			$return = $page.'?'.$str;
		} else {
			$return = $page;
		}

		return $return;
	}

	// 목록
	function getCount($param='') {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
	
		$sql = "
			SELECT COUNT(*) AS cnt FROM comment
			WHERE parent_fk=".$this->parent_fk." AND tablename='".$this->tablename."' ";
	
		$result = mysql_query($sql, $conn);
		mysql_close($conn);
		$row=mysql_fetch_array($result);
		$totalCount = $row['cnt'];
	
		return $totalCount;
	}
	
	// 목록
	function getList($param='') {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();

		$sql = "
			SELECT * FROM comment
			WHERE parent_fk=".$this->parent_fk." AND tablename='".$this->tablename."'
			ORDER BY no ASC ";
		
		$result = mysql_query($sql, $conn);
		mysql_close($conn);

		return $result;
	}

	// 관리자 등록
	function insert($req="") {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();

		$sql = "
			INSERT INTO comment
				(parent_fk, tablename, member_fk, password, name, contents, registdate)
			VALUES
				($req[parent_fk], '$req[tablename]', $req[member_fk], ".DB_ENCRYPTION."('".$req[password]."'), '$req[name]', '$req[contents]', NOW())";
		mysql_query($sql, $conn);

		$sql = "SELECT LAST_INSERT_ID() AS lastNo";
		$result = mysql_query($sql, $conn);
		$row = mysql_fetch_array($result);
		$lastNo = $row['lastNo'];
		mysql_close($conn);
		return $lastNo;
	}

	// 수정
	function update($req="") {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();

		$sql = "
			UPDATE comment SET
			name='$req[name]', contents='$req[contents]'
			WHERE no = ".$req['no'];

		$result = mysql_query($sql, $conn);
		mysql_close($conn);
		return $result;
	}

	// 삭제
	function delete($no=0) {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();

		$sql = " DELETE FROM comment WHERE no = ".$no;

		$result = mysql_query($sql, $conn);
		mysql_close($conn);
		return $result;
	}

	// 비밀번호 확인
	function checkPassword($no=0, $password='') {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
		
		$sql = "
			SELECT 
				count(*) as cnt
			FROM comment
			WHERE no='".$no."' and password=".DB_ENCRYPTION."('".$password."')";

		$result = mysql_query($sql, $conn);
		mysql_close($conn);
		$row = mysql_fetch_assoc($result);
		$cnt = $row[cnt];

		return $cnt;
	}

}


?>