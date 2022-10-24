<?
/*


*/

include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/db/DBConnection.class.php";

class Member {

	// 검색 파라미터 (초기 개발시 검색조건 세팅필요)
	var $param = array (
					"pageRows",
					"stype",
					"sval",
					"ssecession",
					"sstartdate",
					"senddate",
					"sdatetype"
				);

	var $tableName;			// 테이블명
	var $pageRows;			// 페이지 로우수
	var $startPageNo=0;		// limit 시작페이지
	public $reqPageNo=1;	// 요청페이지
	var $conn;

	// 생성자
	function Member($pageRows=0, $tableName='', $request='') {
		$this->pageRows = $pageRows;
		$this->tableName = $tableName;
		$this->reqPageNo = ($request['reqPageNo'] == 0) ? 1 : $request['reqPageNo'];	// 요청페이지값 없을시 1로 세팅
		if ($request['reqPageNo'] > 0) {
			$this->startPageNo = ($request['reqPageNo']-1) * $this->pageRows;
		}
		$this->param['sissms'] = -1;
		$this->param['sisemail'] = -1;
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
	
	/**
	 * 검색조건에 맞는 hidden값 자동 생성
	 * @param string $request
	 * @return string
	 */
	function getQueryStringToHidden($request='') {
		$str = '';
	
		for ($i=0; $i<count($this->param); $i++) {
			if (chkisset($request[$this->param[$i]] )) {
				$str .= "<input type='hidden' name='".$this->param[$i]."' value='".$request[$this->param[$i]]."'/>\n";
			}
		}
	
		return $str;
	}

	// 검색 파라미터 queryString 생성, 추가파라미터 지정
	function getQueryStringAddParam($page="", $no=0, $request='', $paramName='', $paramValue='') {	
		$str = '';
		
		for ($i=0; $i<count($this->param); $i++) {
			if ($request[$this->param[$i]]) {
				$str = $str.$this->param[$i]."=".$request[$this->param[$i]]."&";
			}
		}

		if ($no > 0) $str = $str."no=".$no;			// no값이 있을 경우에만 파라미터 세팅 (페이지 이동시 no필요 없음)

		if ($paramValue) $str .= "&".$paramName."=".$paramValue;

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
		if ($p['sval']) {
			if ($p['stype'] == 'all') {
				$whereSql = $whereSql." AND ( (name like '%".$p['sval']."%' ) OR (id like '%".$p['sval']."%' ) OR (tel like '%".$p['sval']."%' ) OR (cell like '%".$p['sval']."%' ) OR (email like '%".$p['sval']."%' ) OR (addr0 LIKE '%".$p['sval']."%') OR (addr1 LIKE '%".$p['sval']."%') ) ";
			} else if ($p['stype'] == 'address') {
				$whereSql .= " AND ( (addr0 LIKE '%".$p['sval']."%') OR (addr1 LIKE '%".$p['sval']."%') )";
			} else {
				$whereSql = $whereSql." AND (".$p['stype']." LIKE '%".$p['sval']."%' )";
			}
		}
		if ($p['ssecession'] != "") {
			$whereSql .= " AND secession=".$p['ssecession'];
		}
		if ($p['sstartdate']) {
			if ($p['senddate']) {
				if ($p['sdatetype'] == 'registdate') {
					$whereSql .= " AND registdate BETWEEN '".$p['sstartdate']." 00:00:00' AND '".$p['senddate']." 23:59:59' ";
				}
			}
		}
		return $whereSql;
	}

	// sql 정렬절 생성
	function getOrderbySql($p) {
		$orderbySql = " ORDER BY ";
		if (!$p['orderby']) {
			$orderbySql .= " no DESC ";
		} else {
			$orderbySql .= $p['orderby']." ".$p['ordertype'];
		}
		return $orderbySql;
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
		$orderbySql = $this->getOrderbySql($param);	// where절

		$sql = "
			SELECT *
			FROM ".$this->tableName."
			".$whereSql."
			".$orderbySql."
			LIMIT ".$this->startPageNo.", ".$this->pageRows." ";
		
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
				(id, password, name, tel, cell, email,
				zipcode, addr0, addr1, secession, registdate)
			VALUES
				('$req[id]', ".DB_ENCRYPTION."('$req[password]'), '$req[name]', '$req[tel]', '$req[cell]', '$req[email]',
				'$req[zipcode]', '$req[addr0]', '$req[addr1]', '$req[secession]',  NOW() )";
		mysql_query($sql, $conn);
outlog($sql);
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
		if ($req['password']) {
			$sql .= "password=".DB_ENCRYPTION."('".$req['password']."'), ";
		}
		$sql .= " name='$req[name]', tel='$req[tel]', cell='$req[cell]', email='$req[email]', secession='$req[secession]',
				zipcode='$req[zipcode]', addr0='$req[addr0]', addr1='$req[addr1]'
				WHERE no = ".$req['no'];

		$result = mysql_query($sql, $conn);
		mysql_close($conn);
		return $result;
	}

	// 임시 비밀번호로 변경
	function updateTempPass($no, $temppass) {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();

		$sql = "
			UPDATE ".$this->tableName." SET password=".DB_ENCRYPTION."('".$temppass."') WHERE no = ".$no;

		$result = mysql_query($sql, $conn);
		mysql_close($conn);
		return $result;
	}

	// 탈퇴처리
	function updateSecession($no=0) {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();

		$sql = "
			UPDATE member SET secession=1 WHERE no = ".$no;

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
	function getData($no=0) {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
		
		$whereSql = $this->getWhereSql($param);	// where절

		$sql = "
			SELECT 
				*
			FROM ".$this->tableName."
			WHERE no = ".chkIsset($no);
		
		$result = mysql_query($sql, $conn);
		mysql_close($conn);
		$data = mysql_fetch_assoc($result);

		return $data;
	}
	
	// 아이디체크
	function checkId($id = "") {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
		
		$sql = " SELECT COUNT(*) AS cnt FROM member WHERE id= '".$id."' ";

		$result = mysql_query($sql, $conn);
		mysql_close($conn);

		$row=mysql_fetch_array($result);
		$count = $row['cnt'];

		return $count;
	}

	// 이메일체크
	function checkEmail($id = "") {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
		
		$sql = " SELECT COUNT(*) AS cnt FROM member WHERE email= '".$id."' ";

		$result = mysql_query($sql, $conn);
		mysql_close($conn);

		$row=mysql_fetch_array($result);
		$count = $row['cnt'];

		return $count;
	}

	// 아이디 찾기
	function searchId($name='', $email='') {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
		
		$sql = "
			SELECT * FROM member
			WHERE name = '".$name."' AND email='".$email."' ";
		
		$result = mysql_query($sql, $conn);
		mysql_close($conn);
		$data = mysql_fetch_assoc($result);

		return $data;
	}

	// 패스워드 찾기
	function searchPw($name='', $email='', $id='') {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
		
		$sql = "
			SELECT * FROM member
			WHERE name = '".$name."' AND email='".$email."' AND id='".$id."' ";
		
		$result = mysql_query($sql, $conn);
		mysql_close($conn);
		$data = mysql_fetch_assoc($result);

		return $data;
	}

	// 회원 로그인체크
	function checkLogin($id = "", $password='') {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
		
		$sql = " SELECT COUNT(*) AS cnt FROM member WHERE secession != 1 AND id= '".$id."' AND password=".DB_ENCRYPTION."('".$password."')";
		
		outlog($sql);
		$result = mysql_query($sql, $conn);
		mysql_close($conn);

		$row=mysql_fetch_array($result);
		$count = $row['cnt'];

		return $count;
	}

	// 로그인후 세션저장을 위해 조회
	function loginMember($id='') {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
		
		$sql = "
			SELECT * FROM member
			WHERE id = '".$id."' ";

		
		
		$result = mysql_query($sql, $conn);
		mysql_close($conn);
		$data = mysql_fetch_assoc($result);
		
		


		return $data;
	}

	// 탈퇴자 전체로우수, 페이지카운트
	function getSecedeCount($param = "") {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
		
		$sql = " SELECT COUNT(*) AS cnt FROM secede WHERE 0=0 ";
		if ($p['sval']) {
			if ($p['stype'] == 'all') {
				$sql .= " AND (name LIKE '%".$p['sval']."%' OR id LIKE '%".$p['sval']."%') ";
			} else {
				$sql .= " AND $p[stype] LIKE '%%' ";
			}
		}

		$result = mysql_query($sql, $conn);
		mysql_close($conn);

		$row=mysql_fetch_array($result);
		$totalCount = $row['cnt'];
		$pageCount = getPageCount($this->pageRows, $totalCount);

		$data[0] = $totalCount;
		$data[1] = $pageCount;

		return $data;
	}

	// 탈퇴자 목록
	function getSecedeList($param='') {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
		
		$sql = "
			SELECT * FROM secede WHERE 0=0 ";
		if ($p['sval']) {
			if ($p['stype'] == 'all') {
				$sql .= " AND (name LIKE '%".$p['sval']."%' OR id LIKE '%".$p['sval']."%') ";
			} else {
				$sql .= " AND $p[stype] LIKE '%%' ";
			}
		}
		$sql .= " ORDER BY secededate DESC LIMIT ".$this->startPageNo.", ".$this->pageRows." ";
		
		$result = mysql_query($sql, $conn);
		mysql_close($conn);

		return $result;
	}

	// 발송대상
	function getCountForSend($param = "") {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
		
		$whereSql = $this->getWhereSql($param);	// where절
		$sql = " SELECT COUNT(".$param['sendtype'].") AS cnt FROM ".$this->tableName.$whereSql;

		$result = mysql_query($sql, $conn);
		mysql_close($conn);

		$row=mysql_fetch_array($result);
		$totalCount = $row['cnt'];
		$pageCount = getPageCount($this->pageRows, $totalCount);

		$data[0] = $totalCount;
		$data[1] = $pageCount;

		return $data;
	}

	// 발송대상자 목록
	function getListForSend($param='') {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
		
		$whereSql = $this->getWhereSql($param);	// where절
		$orderbySql = $this->getOrderbySql($param);	// where절

		$sql = "
			SELECT ".$param['sendtype']." FROM member ".$whereSql;
		$sql .= " GROUP BY ".$param['sendtype']." ";
		
		$result = mysql_query($sql, $conn);
		mysql_close($conn);

		return $result;
	}

	// 탈퇴자 등록
	function insertSecede($req="") {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();

		$sql = "
			INSERT INTO secede
				(name, registdate, secededate, id)
			VALUES
				('$req[name]', '$req[registdate]', NOW(), '$req[id]')";
		mysql_query($sql, $conn);

		$sql = "SELECT LAST_INSERT_ID() AS lastNo";
		$result = mysql_query($sql, $conn);
		$row = mysql_fetch_array($result);
		$lastNo = $row['lastNo'];
		mysql_close($conn);
		return $lastNo;
	}

	// 삭제
	function deleteSecede($no=0) {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();

		$sql = " DELETE FROM secede WHERE no = ".$no;

		$result = mysql_query($sql, $conn);
		mysql_close($conn);
		return $result;
	}
	

	function updateLoginTime($no=0){
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();

		$sql = "UPDATE member SET last_login_time = NOW() WHERE no = ".$no;
	
		$result = mysql_query($sql, $conn);
		mysql_close($conn);
		return $result;
	}

	function updateGarbage(){
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();

		$now = Date("Y-m-d H:i:s", strtotime(Date('Y-m-d H:i:s')." -1 years"));
		
		$sql = "
			INSERT INTO member_garbage (id, password, name, gender, tel, cell, email, zipcode, addr0, addr1, secession, issms, ismail, birthday, registdate, garbagedate, mailCnt)
			SELECT id, password, name, gender, tel, cell, email, zipcode, addr0, addr1, secession, issms, ismail, birthday, registdate, NOW(), 0 FROM member WHERE 
			last_login_time <= '$now'
		";



		$result = mysql_query($sql, $conn);
		if($result > 0){
			mysql_query("DELETE FROM member WHERE last_login_time <= '$now' ", $conn);
		}


		$t = Date('Y-m-d');
	
		$r = mysql_query("SELECT COUNT(*) AS cnt FROM member_garbage WHERE substr(garbagedate, 1, 10) = '$t' AND mailCnt = 0", $conn);
		$data = mysql_fetch_assoc($r); 

		if($data['cnt'] > 0){
			$sql2 = "SELECT * FROM member_garbage WHERE substr(garbagedate,1,10) = '$t' AND mailCnt = 0 ";
			$result2 = mysql_query($sql2, $conn);
		}else{
			$result2 = 0;
		}

		mysql_close($conn);

		return $result2;


	}

	function checkLoginGarbage($id = "", $password='') {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
		
		$sql = " SELECT COUNT(*) AS cnt FROM member_garbage WHERE id= '".$id."' AND password=".DB_ENCRYPTION."('".$password."')";

		$result = mysql_query($sql, $conn);
		mysql_close($conn);

		$row=mysql_fetch_array($result);
		$count = $row['cnt'];

		return $count;
	}

	function getCheckEmailName($id='', $name='', $email=''){
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();

		$sql = "SELECT COUNT(*) AS cnt FROM member_garbage WHERE id = '$id' AND name = '$name' AND email = '$email'";
		

		$result = mysql_query($sql, $conn);
		mysql_close($conn);

		$row=mysql_fetch_array($result);
		$count = $row['cnt'];

		return $count;

	}

	function deleteRealGarbage(){
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();

		$now = Date('Y-m-d H:i:s', strtotime(Date('Y-m-d H:i:s')." -2 years"));

		$r = mysql_query("SELECT COUNT(*) AS cnt FROM member_garbage WHERE garbagedate <= '$now' ", $conn);
		
		$data = mysql_fetch_assoc($r);
		if($data['cnt'] > 0){
				
			$sql2 = "SELECT * FROM member_garbage WHERE garbagedate <= '$now' ";
			$result2 = mysql_query($sql2, $conn);

			
			$sql = "DELETE FROM member_garbage WHERE garbagedate <= '$now' ";
			$result = mysql_query($sql, $conn);
		}else{
			$result2 = 0;
		}

		
		mysql_close($conn);
		
		return $result2;
	}



	function month12Mail(){
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();

		
		$month12 = Date('Y-m-d', strtotime(Date('Y-m-d')." -12 month"));

		$sql = "SELECT COUNT(*) AS cnt FROM member_garbage WHERE substr(garbagedate,1 ,10) <= '$month12' AND mailCnt = 1";
		$result = mysql_query($sql, $conn);
		
		$data = mysql_fetch_assoc($result);

		if($data['cnt'] > 0){
			
			$sql2 = "SELECT email FROM membe_garbage WHERE substr(garbagedate,1,10) <= '$month12' AND mailCnt = 1 ";
			$result2 = mysql_query($sql2, $conn);
		}else{
			$result2 = 0;
		}


		mysql_close($conn);
		

		return $result2;
	}

	function month24Mail(){
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();

		
		$month12 = Date('Y-m-d', strtotime(Date('Y-m-d')." -24 month"));

		$sql = "SELECT COUNT(*) AS cnt FROM member_garbage WHERE substr(garbagedate,1 ,10) <= '$month24' AND mailCnt = 2";
		$result = mysql_query($sql, $conn);
		
		$data = mysql_fetch_assoc($result);

		if($data['cnt'] > 0){
			
			$sql2 = "SELECT email FROM membe_garbage WHERE substr(garbagedate,1,10) <= '$month24' AND mailCnt = 2 ";
			$result2 = mysql_query($sql2, $conn);
		}else{
			$result2 = 0;
		}


		mysql_close($conn);
		

		return $result2;
	}

	function updateCntMail($no=0){
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
		
		$sql = "UPDATE member_garbage SET mailCnt = 1 WHERE no = $no";

		$result = mysql_query($sql, $conn);

		mysql_close($conn);

		return $result;

	}

	function updateMonth12($no=0){
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
		
		$sql = "UPDATE member_garbage SET mailCnt = 2 WHERE no = $no";

		$result = mysql_query($sql, $conn);

		mysql_close($conn);

		return $result;

	}

	function updateMonth24($no=0){
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
		
		$sql = "UPDATE member_garbage SET mailCnt = 3 WHERE no = $no";

		$result = mysql_query($sql, $conn);

		mysql_close($conn);

		return $result;

	}

	function returnGarbage($req=''){
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();


		$sql = "INSERT INTO member (id, password, name, gender, tel, cell, email, zipcode, addr0, addr1, secession, issms, ismail, birthday, registdate)  
		SELECT id, password, name, gender, tel, cell, email, zipcode, addr0, addr1, '0', issms, ismail, birthday, registdate FROM member_garbage WHERE id = '$req[id]' AND name = '$req[name]' AND email = '$req[email]' ";
		
		//outlog($sql);
		$result = mysql_query($sql, $conn);

		if($result > 0){
			mysql_query("DELETE FROM member_garbage WHERE id = '$req[id]' AND name = '$req[name]' AND email = '$req[email]'", $conn);
		}

		mysql_close($conn);

		return $result;
	}


}





?>