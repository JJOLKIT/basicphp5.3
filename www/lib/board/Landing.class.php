<?
/*


*/

include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/dbConfig.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/db/DBConnection.class.php";

class Landing {

	// 검색 파라미터 (초기 개발시 검색조건 세팅필요)
	var $param = array (
					"stype",
					"sval",
					"shospital_fk",
					"sclinic_fk",
					"sevent_fk"
				);

	var $tableName;			// 테이블명
	var $result_tablename;	// 문의 테이블명
	var $pageRows;			// 페이지 로우수
	var $startPageNo=0;		// limit 시작페이지
	public $reqPageNo=1;	// 요청페이지
	var $conn;

	// 생성자
	function Landing($pageRows=0, $tableName='', $result_tablename='', $request='') {
		$this->pageRows = $pageRows;
		$this->tableName = $tableName;
		$this->result_tablename = $result_tablename;
		$this->reqPageNo = ($request['reqPageNo'] == 0) ? 1 : $request['reqPageNo'];	// 요청페이지값 없을시 1로 세팅
		if ($request['reqPageNo'] > 0) {
			$this->startPageNo = ($request['reqPageNo']-1) * $this->pageRows;
		}
	}

	// 검색 파라미터 queryString 생성
	function getQueryString($page="", $no=0, $request='') {	
		$str = '';
		
		for ($i=0; $i<count($this->param); $i++) {
			if (chkIsset($request[$this->param[$i]])) {
				$str .= $this->param[$i]."=".$request[$this->param[$i]]."&";
			}
		}

		if ($no > 0) $str .= "no=".$no;			// no값이 있을 경우에만 파라미터 세팅 (페이지 이동시 no필요 없음)

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
		$whereSql = " WHERE 1=1";
		if ($p['sval']) {
			if ($p['stype'] == 'all') {
				$whereSql .= " AND ( (name like '%".$p['sval']."%' ) or (title like '%".$p['sval']."%' ) or (contents like '%".$p['sval']."%') ) ";
			} else {
				$whereSql .= " AND (".$p['stype']." LIKE '%".$p['sval']."%' )";
			}
		}
		if ($p['shospital_fk']) {
			$whereSql .= " AND hospital_fk = ".$p['shospital_fk'];
		}
		if ($p['sclinic_fk']) {
			$whereSql .= " AND clinic_fk = ".$p['sclinic_fk'];
		}
		if ($p['sevent_fk']) {
			$whereSql .= " AND event_fk = ".$p['sevent_fk'];
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

	// 전체로우수, 페이지카운트
	function getCountResult($param = "") {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
		
		$whereSql = $this->getWhereSql($param);	// where절
		$sql = " SELECT COUNT(*) AS cnt FROM ".$this->result_tablename.$whereSql;

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
			SELECT *, 
				(SELECT name FROM hospital WHERE hospital.no = hospital_fk) AS hospital_name,
				(SELECT name FROM clinic WHERE clinic.no = clinic_fk) AS clinic_name FROM ".$this->tableName."
			".$whereSql."
			ORDER BY registdate DESC LIMIT ".$this->startPageNo.", ".$this->pageRows." ";

		$result = mysql_query($sql, $conn);
		mysql_close($conn);

		return $result;
	}

	// 목록
	function getListResult($param='') {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
		
		$whereSql = $this->getWhereSql($param);	// where절

		$sql = "
			SELECT *, 
				(SELECT name FROM hospital WHERE hospital.no = hospital_fk) AS hospital_name,
				(SELECT name FROM clinic WHERE clinic.no = clinic_fk) AS clinic_name,
				(SELECT title FROM ".$this->tableName." where ".$this->tableName.".no = event_fk) as event_name FROM ".$this->result_tablename."
			".$whereSql."
			ORDER BY registdate DESC LIMIT ".$this->startPageNo.", ".$this->pageRows." ";

		$result = mysql_query($sql, $conn);
		mysql_close($conn);

		return $result;
	}

	// 등록
	function insert($req="") {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();

		$sql = "
			INSERT INTO ".$this->tableName." (
				hospital_fk, clinic_fk, title, contents, url, state, registdate
			) VALUES (
			".chkIsset($req[hospital_fk]).", ".chkIsset($req[clinic_fk]).",
			'$req[title]',
			'$req[contents]',
			'$req[url]',
			".chkIsset($req[state]).",
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
	function update($req="") {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();

		$sql = "
			UPDATE ".$this->tableName." SET 
				hospital_fk=$req[hospital_fk], clinic_fk=$req[clinic_fk], title='$req[title]', contents='$req[contents]', url='$req[url]', state=$req[state]
			WHERE no = ".$req['no'];

		$result = mysql_query($sql, $conn);
		mysql_close($conn);
		return $result;
	}

	// 수정(메모)
	function updateResult($req="") {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();

		$sql = "
			UPDATE ".$this->result_tablename." SET 
				answer='$req[answer]'
			WHERE no = ".$req['no'];

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

	// 삭제
	function deleteResult($no=0) {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();

		$sql = " DELETE FROM ".$this->result_tablename." WHERE no = ".$no;

		$result = mysql_query($sql, $conn);
		mysql_close($conn);
		return $result;
	}

	// 상세
	function getData($no=0) {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();

		$sql = "
			SELECT *, 
				(SELECT name FROM hospital WHERE hospital.no = hospital_fk) AS hospital_name,
				(SELECT name FROM clinic WHERE clinic.no = clinic_fk) AS clinic_name FROM ".$this->tableName."
			WHERE no = ".$no;
		
		$result = mysql_query($sql, $conn);
		mysql_close($conn);
		$data = mysql_fetch_assoc($result);

		return $data;
	}

	// 등록
	function insertResult($req="") {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();

		//$gno = $this->getMaxGno();
		$sql = "
			INSERT INTO ".$this->result_tablename." (
				event_fk, hospital_fk, clinic_fk, title, contents, area, registdate, email, name, cell
			) VALUES (
			".chkIsset($req[event_fk]).", ".chkIsset($req[hospital_fk]).", ".chkIsset($req[clinic_fk]).",
			'$req[title]',
			'$req[contents]',
			'$req[area]',
			NOW(),
			'$req[email]',
			'$req[name]',
			'$req[cell]'
			)";
		mysql_query($sql, $conn);

		$sql = "SELECT LAST_INSERT_ID() AS lastNo";
		$result = mysql_query($sql, $conn);
		$row = mysql_fetch_array($result);
		$lastNo = $row['lastNo'];
		mysql_close($conn);
		return $lastNo;
	}

	// 상세
	function getDataResult($no=0) {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();

		$sql = "
			SELECT *, 
				(SELECT name FROM hospital WHERE hospital.no = hospital_fk) AS hospital_name,
				(SELECT name FROM clinic WHERE clinic.no = clinic_fk) AS clinic_name,
				(SELECT title FROM ".$this->tableName." WHERE ".$this->tableName.".no = ".$this->result_tablename.".event_fk) as event_name
			FROM ".$this->result_tablename."
			WHERE no = ".$no;
		
		$result = mysql_query($sql, $conn);
		mysql_close($conn);
		$data = mysql_fetch_assoc($result);

		return $data;
	}
	

}


?>