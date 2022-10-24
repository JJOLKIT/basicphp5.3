<?
/*


*/

include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/dbConfig.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/db/DBConnection.class.php";

class Email {

	// 검색 파라미터 (초기 개발시 검색조건 세팅필요)
	var $param = array (
					"stype",
					"sval",
					"startday",
					"endday"
				);

	var $tableName;			// 테이블명
	var $listTableName;		// 보낸 메일의 발송자 대상명단
	var $pageRows;			// 페이지 로우수
	var $startPageNo=0;		// limit 시작페이지
	public $reqPageNo=1;	// 요청페이지
	var $conn;

	// 생성자
	function Email($pageRows=0, $tableName='', $listTableName='', $request='') {
		$this->pageRows = $pageRows;
		$this->tableName = $tableName;
		$this->listTableName = $listTableName;
		$this->reqPageNo = ($request['reqPageNo'] == 0) ? 1 : $request['reqPageNo'];	// 요청페이지값 없을시 1로 세팅
		if ($request['reqPageNo'] > 0) {
			$this->startPageNo = ($request['reqPageNo']-1) * $this->pageRows;
		}
	}

	// 검색 파라미터 queryString 생성
	function getQueryString($page="", $no=0, $request='') {	
		$str = '';
		
		for ($i=0; $i<count($this->param); $i++) {
			if ($request[$this->param[$i]]) {
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

	// sql WHERE절 생성
	function getWhereSql($p) {
		$whereSql = " WHERE 1=1 ";
		if ($p['startday']) {
			if ($p['endday']) {
				$whereSql .= " AND registdate BETWEEN '".$p['startday']." 00:00:00' AND '".$p['endday']." 23:59:59' ";
			}
		}

		if ($p['sval']) {
			if ($p['stype'] == 'all') {
				$whereSql = $whereSql." AND ( (title LIKE '%".$p['sval']."%' ) OR (contents LIKE '%".$p['sval']."%' ) ) ";
			} else if ($p['stype'] == 'sendmail') {
				$whereSql .= " AND sendmail LIKE '%".$p['sval']."%' ";
			} else if ($p['stype'] == 'receiveman') {
				$whereSql .= " AND no IN (SELECT emailhistory_fk FROM ".$this->listTableName." WHERE receiveemail LIKE '%".$p['sval']."%') ";
			}
		}

		return $whereSql;
	}


	// 전체로우수, 페이지카운트
	function getCount($param = "") {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
		
		$whereSql = $this->getWhereSql($param);	// where절
		$sql = " SELECT COUNT(*) AS cnt FROM ".$this->tableName.$whereSql;

		$result = mysql_query($sql, $conn);
		mysql_close($conn);

		$row=mysql_fetch_array($result);
		$totalCount = $row['cnt'];
		$pageCount = getPageCount($this->pageRows, $totalCount);

		$data[0] = $totalCount;
		$data[1] = $pageCount;

		return $data;
	}

	// 목록
	function getList($param='') {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
		
		$whereSql = $this->getWhereSql($param);	// where절

		$sql = "
			SELECT * FROM ".$this->tableName."
			".$whereSql."
			ORDER BY no DESC LIMIT ".$this->startPageNo.", ".$this->pageRows." ";
		
		$result = mysql_query($sql, $conn);
		mysql_close($conn);

		return $result;
	}

	// 등록
	function insert($req="") {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();

		$sql = "
			INSERT INTO ".$this->tableName."
				(
				sendman, title, contents,
	        	filename, filesize,
				filename2, filesize2,
				filename3, filesize3,
				filename_org, filename2_org,
				filename3_org, 
				registdate
				)
			VALUES
				(
				'$req[sendman]', '$req[title]', '$req[contents]',
	        	'$req[filename]', $req[filesize],
				'$req[filename2]', $req[filesize2],
				'$req[filename3]', $req[filesize3],
				'$req[filename_org]', '$req[filename2_org]',
				'$req[filename3_org]', 
				NOW()
				)";
		mysql_query($sql, $conn);

		$sql = "SELECT LAST_INSERT_ID() AS lastNo";
		$result = mysql_query($sql, $conn);
		$row = mysql_fetch_array($result);
		$lastNo = $row['lastNo'];
		mysql_close($conn);
		return $lastNo;
	}

	// 수정
	function updateTotalcount($totalcount=0, $no=0) {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();

		$sql = "
			UPDATE ".$this->tableName." SET
				totalcount = $totalcount
			WHERE no = ".$no;

		$result = mysql_query($sql, $conn);
		mysql_close($conn);
		return $result;
	}

	// 삭제
	function delete($no=0) {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();

		$sql = " DELETE FROM ".$this->tableName." WHERE no = ".$no;

		$result = mysql_query($sql, $conn);
		mysql_close($conn);
		return $result;
	}

	//
	function getData($no=0) {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
		
		$whereSql = $this->getWhereSql($param);	// where절

		$sql = "
			SELECT 
				*
			FROM ".$this->tableName."
			WHERE no = ".$no;
		
		$result = mysql_query($sql, $conn);
		mysql_close($conn);
		$data = mysql_fetch_assoc($result);

		return $data;
	}

	// 총발송수
	function getTotalcount($no=0) {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
		
		$sql = "
			SELECT 
				SUM(totalcount) AS totalcount
			FROM ".$this->tableName.$whereSql;

		$result = mysql_query($sql, $conn);
		mysql_close($conn);
		$row = mysql_fetch_assoc($result);
		$totalcount = $row['totalcount'];

		return $totalcount;
	}

	// 등록
	function insertMailList($emailhistory_fk="", $receiveemail="") {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();

		$emailList = split(",", $receiveemail);
		if (sizeof($emailList) > 0) {
			for ($i=0; $i<sizeof($emailList); $i++) {
				$sql = "
				INSERT INTO ".$this->listTableName."
					(
					emailhistory_fk, receiveemail
					)
				VALUES
					(
					$emailhistory_fk, '$emailList[$i]'
					)";
				mysql_query($sql, $conn);
			}
		}

		
		mysql_close($conn);
	}

	// 목록
	function getListMailList($emailhistory_fk=0) {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
		
		$whereSql = $this->getWhereSql($param);	// where절

		$sql = "
			SELECT * FROM ".$this->listTableName."
			WHERE emailhistory_fk = ".$emailhistory_fk."
			ORDER BY no DESC ";
		
		$result = mysql_query($sql, $conn);
		mysql_close($conn);

		return $result;
	}

}


?>