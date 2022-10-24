<?
/*


*/

include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/db/DBConnection.class.php";

class Admin {

	// 검색 파라미터 (초기 개발시 검색조건 세팅필요)
	var $param = array (
					"stype",
					"sval",
					"sgrade"
				);

	var $tableName;			// 테이블명
	var $pageRows;			// 페이지 로우수
	var $startPageNo=0;		// limit 시작페이지
	public $reqPageNo=1;	// 요청페이지
	var $conn;

	// 생성자
	function Admin($pageRows=0, $tableName='', $request='') {
		$this->pageRows = $pageRows;
		$this->tableName = $tableName;
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
		$whereSql = " WHERE no > 1 ";
		if ($p['sval']) {
			if ($p['stype'] == 'all') {
				$whereSql = $whereSql." AND ( (name like '%".$p['sval']."%' ) or (id like '%".$p['sval']."%' ) or (memo like '%".$p['sval']."%') ) ";
			} else {
				$whereSql = $whereSql." AND (".$p['stype']." LIKE '%".$p['sval']."%' )";
			}
		}
		if ($p['sgrade'] != "") {
			$whereSql .= " AND grade = ".$p['sgrade'];
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
			SELECT *,
			IFNULL((SELECT grade_name FROM admin_grade WHERE no = admin.grade),'') AS grade_name
			FROM ".$this->tableName."
			".$whereSql."
			ORDER BY no DESC LIMIT ".$this->startPageNo.", ".$this->pageRows." ";
		outlog($sql);
		$result = mysql_query($sql, $conn);
		mysql_close($conn);

		return $result;
	}

	// 관리자 등록
	function insert($req="") {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();

		$sql = "
			INSERT INTO ".$this->tableName."
				(id, password, email, grade, name, tel, memo, registdate)
			VALUES
				('".$req['id']."', ".DB_ENCRYPTION."('".$req['password']."'),'".$req['email']."', $req[grade], '".$req['name']."', '".$req['tel']."', '".$req['memo']."', NOW())";
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
			UPDATE ".$this->tableName." SET ";
		if ($req['password']) $sql = $sql." password = ".DB_ENCRYPTION."('".$req['password']."'),";		// password 값이 있는경우에만 update
		$sql = $sql."
				email='".$req['email']."', grade=$req[grade], name='".$req['name']."', tel='".$req['tel']."', memo='".$req['memo']."'
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

	// 목록
	function getData($req='') {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
		
		$whereSql = $this->getWhereSql($param);	// where절

		$sql = "
			SELECT 
				*, 
				IFNULL((select grade_name from admin_grade where no = admin.grade),'') as grade_name
			FROM ".$this->tableName."
			WHERE no>1 AND no = ".$req['no'];
		
		$result = mysql_query($sql, $conn);
		mysql_close($conn);
		$data = mysql_fetch_assoc($result);

		return $data;
	}

	// 로그인체크
	function loginCheck($req='') {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
		
		$req = escape_string($req);
		$sql = "
			SELECT 
				count(*) as cnt
			FROM ".$this->tableName."
			WHERE id='".$req['id']."' and password=".DB_ENCRYPTION."('".$req['password']."')";

		$result = mysql_query($sql, $conn);
		mysql_close($conn);

		return $result;
	}

	// 로그인 세션정보 구하기
	function getLoginSessionInfo($req='') {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
		$req = escape_string($req);

		$sql = "
			SELECT 
				*
			FROM ".$this->tableName."
			WHERE id='".$req['id']."' and password=".DB_ENCRYPTION."('".$req['password']."')";
		
		$result = mysql_query($sql, $conn);
		mysql_close($conn);

		return $result;
	}

	// 관리자 로그인 접속이력 sql WHERE절 생성
	function getHistoryWhereSql($p) {
		$whereSql = " WHERE id != 'sanggong' ";
		if ($p['sval']) {
			if ($p['stype'] == 'all') {
				$whereSql = $whereSql." AND ( (name like '%".$p['sval']."%' ) or (id like '%".$p['sval']."%' ) or (ip like '%".$p['sval']."%' ) ) ";
			} else {
				$whereSql = $whereSql." AND (".$p['stype']." LIKE '%".$p['sval']."%' )";
			}
		}
		return $whereSql;
	}

	// 관리자 로그인 접속이력 전체로우수, 페이지카운트
	function getCountLoginHistory($param = "") {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
		$param = escape_string($param);
		
		$whereSql = $this->getHistoryWhereSql($param);	// where절
		$sql = " SELECT COUNT(*) AS cnt FROM admin_loginhistory ".$whereSql;

		$result = mysql_query($sql, $conn);
		mysql_close($conn);

		$row=mysql_fetch_array($result);
		$totalCount = $row['cnt'];
		$pageCount = getPageCount($this->pageRows, $totalCount);

		$data[0] = $totalCount;
		$data[1] = $pageCount;

		return $data;
	}

	// 관리자 로그인 접속이력 목록
	function getLoginHistoryList($param='') {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
		$param = escape_string($param);
		
		$whereSql = $this->getHistoryWhereSql($param);	// where절

		$sql = "
			SELECT * FROM admin_loginhistory ".$whereSql."
			ORDER BY no DESC LIMIT ".$this->startPageNo.", ".$this->pageRows." ";
		
		$result = mysql_query($sql, $conn);
		mysql_close($conn);

		return $result;
	}

	// 관리자 로그인 접속이력 등록
	function insertLoginHistory($id='', $name='', $ip='') {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();

		$sql = "
			INSERT INTO admin_loginhistory
				(id, name, logindate, ip)
			VALUES
				('$id','$name', NOW(), '$ip')";
		mysql_query($sql, $conn);

		$sql = "SELECT LAST_INSERT_ID() AS lastNo";
		$result = mysql_query($sql, $conn);
		$row = mysql_fetch_array($result);
		$lastNo = $row['lastNo'];
		mysql_close($conn);
		return $lastNo;
	}

	// 권한 등록
	function insertGrade($req="") {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();

		$sql = "
			INSERT INTO admin_grade
				(grade_name)
			VALUES
				('".$req['grade_name']."')";
		mysql_query($sql, $conn);

		$sql = "SELECT LAST_INSERT_ID() AS lastNo";
		$result = mysql_query($sql, $conn);
		$row = mysql_fetch_array($result);
		$lastNo = $row['lastNo'];
		mysql_close($conn);
		return $lastNo;
	}

	// 권한 수정
	function updateGrade($req="") {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();

		$sql = "
			UPDATE admin_grade SET 
				grade_name='".$req['grade_name']."'
			WHERE no = ".$req['no'];

		$result = mysql_query($sql, $conn);
		mysql_close($conn);
		return $result;
	}

	// 권한 목록
	function getGradeList() {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();

		$sql = "
			SELECT * FROM admin_grade
			ORDER BY rank ASC ";
		
		$result = mysql_query($sql, $conn);
		mysql_close($conn);

		return $result;
	}

	// 권한 삭제
	function deleteGrade($no=0) {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();

		$sql = " DELETE FROM admin_grade WHERE no = ".$no;

		$result = mysql_query($sql, $conn);
		mysql_close($conn);
		return $result;
	}
	
	// 아이디체크
	function idcheck($param = "") {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
	
		$sql = " SELECT COUNT(*) AS cnt FROM admin WHERE id='".$param['id']."'";
	
		$result = mysql_query($sql, $conn);
		mysql_close($conn);
	
		$row=mysql_fetch_array($result);
		$totalCount = $row['cnt'];
	
		return $totalCount;
	}
	

}


?>