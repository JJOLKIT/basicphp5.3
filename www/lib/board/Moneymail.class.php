<?
/*


*/

include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/dbConfig.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/db/DBConnection.class.php";

class Moneymail {

	// 검색 파라미터 (초기 개발시 검색조건 세팅필요)
	var $param = array (
					"stype",
					"sval",
					"shospital_fk",
					"sstate",
					"startday",
					"endday",
					"sismanage",
					"semailhistory_fk"
				);

	var $tableName;			// 테이블명
	var $listTableName;			// 테이블명
	var $pageRows;			// 페이지 로우수
	var $startPageNo=0;		// limit 시작페이지
	public $reqPageNo=1;	// 요청페이지
	var $conn;

	// 생성자
	function Moneymail($pageRows=0, $tableName='', $listTableName, $request='') {
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
				$whereSql .= " AND ( (name like '%".$p['sval']."%' ) or (cell like '%".$p['sval']."%' ) or (receiveemail like '%".$p['sval']."%') ) ";
			} else {
				$whereSql .= " AND (".$p['stype']." LIKE '%".$p['sval']."%' )";
			}
		}
		if ($p['shospital_fk'] > 0) {
			$whereSql .= " AND hospital_fk = ".$p['shospital_fk'];
		}
		return $whereSql;
	}

	// sql WHERE절 생성
	function getListWhereSql($p) {
		$whereSql = " WHERE 1=1";
		if ($p['sval']) {
			if ($p['stype'] == 'all') {
				$whereSql .= " AND ( (name like '%".$p['sval']."%' ) or (receiveemail like '%".$p['sval']."%' ) or (cell like '%".$p['sval']."%') ) ";
			} else {
				$whereSql .= " AND (".$p['stype']." LIKE '%".$p['sval']."%' )";
			}
		}
		if ($p['shospital_fk'] > 0) {
			$whereSql .= " AND hospital_fk = ".$p['shospital_fk'];
		}
		if ($p['sstate'] > 0) {
			$whereSql .= " AND state = ".$p['sstate'];
		}
		if ($p['sismanage'] > 0) {
			$whereSql .= " AND ismanage = ".$p['sismanage'];
		}
		if ($p['semailhistory_fk'] > 0) {
			$whereSql .= " AND emailhistory_fk = ".$p['semailhistory_fk'];
		}
		if ($p['startday'] && $p['endday']) {
			$whereSql .= " AND registdate BETWEEN '".$p['startday']."' AND '".$p['endday']."' ";
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
	function getCountList($param = "") {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
		
		$whereSql = $this->getListWhereSql($param);	// where절
		$sql = " SELECT COUNT(*) AS cnt FROM ".$this->listTableName.$whereSql;

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
				(SELECT name FROM hospital AS h WHERE h.no = ".$this->tableName.".hospital_fk) AS hospital_name
			FROM ".$this->tableName."
			".$whereSql."
			ORDER BY registdate DESC LIMIT ".$this->startPageNo.", ".$this->pageRows." ";

		$result = mysql_query($sql, $conn);
		mysql_close($conn);

		return $result;
	}

	// 목록
	function getListList($param='') {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
		
		$whereSql = $this->getListWhereSql($param);	// where절

		$sql = "
			SELECT *,
				(SELECT name FROM hospital AS h WHERE h.no = ".$this->listTableName.".hospital_fk) AS hospital_name,
				(SELECT gubun FROM ".$this->tableName." AS c WHERE c.no = ".$this->listTableName.".emailhistory_fk) AS gubun
			FROM ".$this->listTableName."
			".$whereSql."
			ORDER BY registdate DESC LIMIT ".$this->startPageNo.", ".$this->pageRows." ";

		$result = mysql_query($sql, $conn);
		mysql_close($conn);

		return $result;
	}

	// 비용메일 등록
	function insert($req="") {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();

		//$gno = $this->getMaxGno();
		$sql = "
			INSERT INTO ".$this->tableName." (
				hospital_fk, gubun, name, title, contents, ";
		if ($req[filename]) {
			$sql .= "filename, filename_org, filesize, ";
		}
		if ($req[filename2]) {
			$sql .= "filename2, filename2_org, filesize2, ";
		}
		if ($req[filename3]) {
			$sql .= "filename3, filename3_org, filesize3, ";
		}
		$sql .= " registdate
			) VALUES (
				".chkIsset($req[hospital_fk]).", '$req[gubun]', '$req[name]', '$req[title]', '$req[contents]', ";
		if ($req[filename]) {
			$sql .= "'$req[filename]', '$req[filename_org]', $req[filesize], ";
		}
		if ($req[filename2]) {
			$sql .= "'$req[filename2]', '$req[filename2_org]', $req[filesize2], ";
		}
		if ($req[filename3]) {
			$sql .= "'$req[filename3]', '$req[filename2_org]', $req[filesize3], ";
		}
		$sql .= " NOW() )";

		mysql_query($sql, $conn);

		$sql = "SELECT LAST_INSERT_ID() AS lastNo";
		$result = mysql_query($sql, $conn);
		$row = mysql_fetch_array($result);
		$lastNo = $row['lastNo'];
		mysql_close($conn);
		return $lastNo;
	}

	// 리스트 등록
	function insertList($req="") {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();

		//비용메일 갯수
		$sql = " SELECT COUNT(*) AS cnt FROM ".$this->listTableName." 
				WHERE 1=1";
		if ($req['member_fk']) {
			$sql .= " AND member_fk=$req[member_fk] OR receiveemail = '$req[receiveemail]' ";
		}
		if (!$req['member_fk']) {
			$sql .= " AND receiveemail = '$req[receiveemail]' ";
		}
		$sql .= " OR cell = '$req[cell]' ";
			
		$result = mysql_query($sql, $conn);
		$row=mysql_fetch_array($result);
		$mcount = $row['cnt'];

		//상담갯수 갯수
		$sql = " SELECT COUNT(*) AS cnt FROM consult 
				WHERE 1=1";
		if ($req['member_fk']) {
			$sql .= " AND member_fk=$req[member_fk] OR email = '$req[receiveemail]' ";
		}
		if (!$req['member_fk']) {
			$sql .= " AND email = '$req[receiveemail]' ";
		}
		$sql .= " OR cell = '$req[cell]' ";
			
		$result = mysql_query($sql, $conn);
		$row=mysql_fetch_array($result);
		$concount = $row['cnt'];

		//예약갯수 갯수
		$sql = " SELECT COUNT(*) AS cnt FROM reservation 
				WHERE 1=1";
		if ($req['member_fk']) {
			$sql .= " AND member_fk=$req[member_fk] OR email = '$req[receiveemail]' ";
		}
		if (!$req['member_fk']) {
			$sql .= " AND email = '$req[receiveemail]' ";
		}
		$sql .= " OR cell = '$req[cell]' ";
			
		$result = mysql_query($sql, $conn);
		$row=mysql_fetch_array($result);
		$resercount = $row['cnt'];

		//회원여부
		$sql = " SELECT COUNT(*) AS cnt FROM member 
				WHERE 1=1";
		if ($req['member_fk']) {
			$sql .= " AND member_fk=$req[member_fk] OR email = '$req[receiveemail]' ";
		}
		if (!$req['member_fk']) {
			$sql .= " AND email = '$req[receiveemail]' ";
		}
		$sql .= " OR cell = '$req[cell]' ";
			
		$result = mysql_query($sql, $conn);
		$row=mysql_fetch_array($result);
		$members = $row['cnt'];
		if ($members > 0) {
			$members = 1;
		} else {
			$members = 0;
		}

		$sql = "
			INSERT INTO ".$this->listTableName." (
				hospital_fk, member_fk, emailhistory_fk, receiveemail, name, cell, registdate, state, ismanage, mcount, concount, resercount, members
			) VALUES (
				".chkIsset($req[hospital_fk]).", ".chkIsset($req[member_fk]).", ".chkIsset($req[emailhistory_fk]).", '$req[receiveemail]', '$req[name]', '$req[cell]', NOW(), 1,
				".chkIsset($req[ismanage]).", $mcount, $concount, $resercount, $members )";

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

		if ($req['filename_chk'] == 1) {
			mysql_query("UPDATE ".$this->tableName." SET filename='', filename_org='', filesize=0 WHERE no=".$req[no], $conn);
		}
		if ($req['filename2_chk'] == 1) {
			mysql_query("UPDATE ".$this->tableName." SET filename2='', filename2_org='', filesize2=0 WHERE no=".$req[no], $conn);
		}
		if ($req['filename3_chk'] == 1) {
			mysql_query("UPDATE ".$this->tableName." SET filename3='', filename3_org='', filesize3=0 WHERE no=".$req[no], $conn);
		}

		$sql = "
			UPDATE ".$this->tableName." SET 
			gubun='$req[gubun]', name='$req[name]', title='$req[title]', ";
		if ($req['filename']) {
			$sql .= " filename='$req[filename]', filename_org='$req[filename_org]', filesize='$req[filesize]', ";
		}
		if ($req['filename2']) {
			$sql .= " filename2='$req[filename2]', filename2_org='$req[filename2_org]', filesize2='$req[filesize2]', ";
		}
		if ($req['filename3']) {
			$sql .= " filename3='$req[filename3]', filename3_org='$req[filename3_org]', filesize3='$req[filesize3]', ";
		}
		$sql .= " contents='$req[contents]' 
			WHERE no = ".$req['no'];

		$result = mysql_query($sql, $conn);
		mysql_close($conn);
		return $result;
	}

	// 리스트수정
	function updateList($req="") {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();

		$sql = "
			UPDATE ".$this->listTableName." SET 
			emailhistory_fk=$req[emailhistory], receiveemail='$req[receiveemail]', name='$req[name]', cell='$req[cell]', sending=1
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

	// 상세
	function getData($no=0) {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();

		$sql = "
			SELECT *,
				(SELECT name FROM hospital AS h WHERE h.no=".$this->tableName.".hospital_fk) AS hospital_name
			FROM ".$this->tableName."
			WHERE no = ".$no;
		
		$result = mysql_query($sql, $conn);
		mysql_close($conn);
		$data = mysql_fetch_assoc($result);

		return $data;
	}

	// 상세
	function selectTotalMailData($req) {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();

		$sql = "
			SELECT																																																			
				no, emailhistory_fk, name,receiveemail, cell,registdate,																															
		        (select name FROM hospital WHERE no = a.hospital_fk) AS hospitalname,
				(SELECT gubun FROM ".$this->tableName." WHERE no = a.emailhistory_fk) AS gubun,																					
				(SELECT COUNT(*) FROM  consult WHERE email= a.receiveemail OR member_fk = a.member_fk OR cell = a.cell) AS ccount,							
				(SELECT COUNT(*) FROM  reservation WHERE email= a.receiveemail OR member_fk = a.member_fk OR cell = a.cell) AS rcount,mcount,				
				(SELECT COUNT(*) FROM  member WHERE email= a.receiveemail OR no = a.member_fk OR cell = a.cell) AS hcount,											
				(SELECT COUNT(*) FROM  ".$this->listTableName." WHERE receiveemail= a.receiveemail OR member_fk = a.member_fk OR cell = a.cell) AS moneycount,	
				concount,resercount
			FROM ".$this->listTableName." AS a
			WHERE no = ".$req['no'];
		
		$result = mysql_query($sql, $conn);
		mysql_close($conn);
		$data = mysql_fetch_assoc($result);

		return $data;
	}

	// 총 발송수
	function getTotalMailCount($param = "") {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
		
		$whereSql = $this->getWhereSql($param);	// where절
		$sql = " SELECT SUM(totalcount) AS cnt FROM ".$this->tableName.$whereSql;

		$result = mysql_query($sql, $conn);
		mysql_close($conn);

		$row=mysql_fetch_array($result);
		$totalCount = $row['cnt'];

		return $totalCount;
	}

	// 총 발송수 수정
	function updateTotalCout($req="") {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();

		$sql = "
			UPDATE ".$this->tableName." SET 
			totalcount=$req[totalcount]
			WHERE no = ".$req['no'];

		$result = mysql_query($sql, $conn);
		mysql_close($conn);
		return $result;
	}

	// 완료처리
	function confirm($req="") {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();

		$sql = "
			UPDATE ".$this->listTableName." SET 
			state=2
			WHERE no = ".$req['no'];

		$result = mysql_query($sql, $conn);
		mysql_close($conn);
		return $result;
	}

	// 취소처리
	function cancel($req="") {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();

		$sql = "
			UPDATE ".$this->listTableName." SET 
			state=1, canceldate=NOW()
			WHERE no = ".$req['no'];

		$result = mysql_query($sql, $conn);
		mysql_close($conn);
		return $result;
	}

	// selectbox option 목록
	function selectOptionList($param='') {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
		
		$sql = "
			SELECT no, hospital_fk, gubun, registdate
			FROM ".$this->tableName."
			".$whereSql."
			ORDER BY hospital_fk ASC, registdate ASC ";

		$result = mysql_query($sql, $conn);
		mysql_close($conn);

		return $result;
	}

}


?>