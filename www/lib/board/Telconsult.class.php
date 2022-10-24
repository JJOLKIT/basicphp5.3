<?
/*


*/

include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/dbConfig.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/db/DBConnection.class.php";

class Telconsult {

	// 검색 파라미터 (초기 개발시 검색조건 세팅필요)
	var $param = array (
					"stype",
					"sval",
					"smain",
					"shospital_fk",
					"sclinic_fk",
					"smember_fk"
				);

	var $tableName;			// 테이블명
	var $pageRows;			// 페이지 로우수
	var $startPageNo=0;		// limit 시작페이지
	public $reqPageNo=1;	// 요청페이지
	var $conn;

	// 생성자
	function Telconsult($pageRows=0, $tableName='', $request='') {
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
				$whereSql .= " AND ( (name like '%".$p['sval']."%' ) or (title like '%".$p['sval']."%' ) or (contents like '%".$p['sval']."%') or (answer like '%".$p['sval']."%') ) ";
			} else {
				$whereSql .= " AND (".$p['stype']." LIKE '%".$p['sval']."%' )";
			}
		}
		if ($p['smain']) {
			$whereSql .= " AND main = ".$p['smain'];
		}
		if ($p['shospital_fk'] > 0) {
			$whereSql .= " AND hospital_fk = ".$p['shospital_fk'];
		}
		if ($p['sclinic_fk'] > 0) {
			$whereSql .= " AND clinic_fk = ".$p['sclinic_fk'];
		}
		if ($p['smember_fk'] > 0) {
			$whereSql .= " AND member_fk = ".$p['smember_fk'];
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
				(SELECT name FROM hospital AS h WHERE h.no = ".$this->tableName.".hospital_fk) AS hospital_name,
				(SELECT name FROM clinic AS c WHERE c.no = ".$this->tableName.".clinic_fk) AS clinic_name
			FROM ".$this->tableName."
			".$whereSql."
			ORDER BY top DESC, registdate DESC LIMIT ".$this->startPageNo.", ".$this->pageRows." ";

		$result = mysql_query($sql, $conn);
		mysql_close($conn);

		return $result;
	}

	// 관리자 등록
	function insert($req="") {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();

		//$gno = $this->getMaxGno();
		$sql = "
			INSERT INTO ".$this->tableName." (
				hospital_fk, clinic_fk, member_fk, secret, password, name, email, cell, title, contents, answer,
				relation_url, ";
		if ($req[filename]) {
			$sql .= "filename, filename_org, filesize, ";
		}
		if ($req[moviename]) {
			$sql .= "moviename, moviename_org, ";
		}
		$sql .= "registdate,  ";
		if ($req[readno]) {	// 조회수값이 있는 경우에만
			$sql .= "readno, ";
		}
		$sql .= "top, main, newicon, iscall, ismail, gno, ono, nested, telcon_day, telcon_time
			) VALUES (
				".chkIsset($req[hospital_fk]).", ".chkIsset($req[clinic_fk]).", ".chkIsset($req[member_fk]).", '".chkIsset($req[secret])."', ".DB_ENCRYPTION."('$req[password]'), '$req[name]', '$req[email]', '$req[cell]', '$req[title]', '$req[contents]', '$req[answer]',
				'$req[relation_url]', ";
		if ($req[filename]) {
			$sql .= "'$req[filename]', '$req[filename_org]', $req[filesize], ";
		}
		if ($req[moviename]) {
			$sql .= "'$req[moviename]', '$req[moviename_org]', ";
		}
		if ($req[registdate]) {	
			$sql .= " '$req[registdate]', ";
		} else {
			$sql .= " NOW(), ";
		}
		if ($req[readno]) {	// 조회수값이 있는 경우에만
			$sql .= " '$req[readno]', ";
		}
		$sql .= chkIsset($req[top]).", ".chkIsset($req[main]).", ".chkIsset($req[newicon]).", ".chkIsset($req[iscall]).", ".chkIsset($req[ismail]).", ".chkIsset($req[gno]).", ".chkIsset($req[ono]).", ".chkIsset($req[nested]).", '$req[telcon_day]', '$req[telcon_time]'
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

		if ($req['filename_chk'] == 1) {
			mysql_query("UPDATE ".$this->tableName." SET filename='', filename_org='', filesize=0 WHERE no=".$req[no], $conn);
		}
		if ($req['moviename_chk'] == 1) {
			mysql_query("UPDATE ".$this->tableName." SET moviename='', moviename_org='' WHERE no=".$req[no], $conn);
		}

		$sql = "
			UPDATE ".$this->tableName." SET 
			hospital_fk=".chkIsset($req[hospital_fk]).", clinic_fk=".chkIsset($req[clinic_fk]).", member_fk=".chkIsset($req[member_fk]).", secret='".chkIsset($req[secret])."', name='$req[name]', email='$req[email]', cell='$req[cell]', title='$req[title]', contents='$req[contents]', answer='$req[answer]',
        	relation_url='$req[relation_url]',
			";
		if ($req['password']) $sql = $sql." password = ".DB_ENCRYPTION."('".$req['password']."'),";		// password 값이 있는경우에만 update
		
		if ($req['filename']) {
			$sql .= " filename='$req[filename]', filename_org='$req[filename_org]', filesize='$req[filesize]', ";
		}
		if ($req['moviename']) {
			$sql .= " moviename='$req[moviename]', moviename_org='$req[moviename_org]', ";
		}
		if ($req[registdate]) {
			$sql .= " registdate = '$req[registdate]', ";
		}
		if ($req[readno]) {
			$sql .= " readno=".chkIsset($req[readno]).", ";
		}
		$sql .= " top=".chkIsset($req[top]).", main=".chkIsset($req[main]).", newicon=".chkIsset($req[newicon]).", iscall=".chkIsset($req[iscall]).", ismail=".chkIsset($req[ismail])." , telcon_day='$req[telcon_day]', telcon_time='$req[telcon_time]'
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
	function getData($no=0, $userCon) {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();

		$sql = "
			SELECT *,
				(SELECT name FROM hospital AS h WHERE h.no=".$this->tableName.".hospital_fk) AS hospital_name,
				(SELECT name FROM clinic AS c WHERE c.no = ".$this->tableName.".clinic_fk) AS clinic_name
			FROM ".$this->tableName."
			WHERE no = ".$no;

		// 조회수 증가
		if ($userCon) {
			mysql_query("UPDATE ".$this->tableName." SET readno=readno+1 WHERE no=".$no, $conn);
		}
		
		$result = mysql_query($sql, $conn);
		mysql_close($conn);
		$data = mysql_fetch_assoc($result);

		return $data;
	}

	// 메인목록 조회
	function getMainList($number) {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
		
		$whereSql = $this->getWhereSql($param);	// where절

		$sql = "
			SELECT *,
				(SELECT name FROM hospital AS h WHERE h.no=".$this->tableName.".hospital_fk) AS hospital_name,
				(SELECT name FROM clinic AS c WHERE c.no = ".$this->tableName.".clinic_fk) AS clinic_name
			FROM ".$this->tableName."
			ORDER BY main DESC, registdate DESC 
			LIMIT 0, ".$number." ";

		$result = mysql_query($sql, $conn);
		mysql_close($conn);

		return $result;
	}

	// 비밀번호확인
	function checkPassword($req='') {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();

		$sql = "
			SELECT 
				count(*) as cnt
			FROM ".$this->tableName."
			WHERE no=".$req['no']." and password=".DB_ENCRYPTION."('".$req['password']."')";

		$result = mysql_query($sql, $conn);
		mysql_close($conn);
		$data = mysql_fetch_assoc($result);
		$cnt = $data[cnt];

		return $cnt;
	}
	
	

}


?>