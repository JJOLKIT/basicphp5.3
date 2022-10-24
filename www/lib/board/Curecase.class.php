<?
/*


*/

include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/dbConfig.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/db/DBConnection.class.php";

class Curecase {

	// 검색 파라미터 (초기 개발시 검색조건 세팅필요)
	var $param = array (
					"stype",
					"sval",
					"smain",
					"shospital_fk",
					"sclinic_fk"
				);

	var $tableName;			// 테이블명
	var $pageRows;			// 페이지 로우수
	var $startPageNo=0;		// limit 시작페이지
	public $reqPageNo=1;	// 요청페이지
	var $conn;

	// 생성자
	function Curecase($pageRows=0, $tableName='', $request='') {
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
			$return = $page.'?';
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
		if ($p['smain'] != '') {
			$whereSql .= " AND main = ".$p['smain'];
		}
		if ($p['shospital_fk'] > 0) {
			$whereSql .= " AND hospital_fk = ".$p['shospital_fk'];
		}
		if ($p['sclinic_fk'] > 0) {
			$whereSql .= " AND clinic_fk = ".$p['sclinic_fk'];
		}
		return $whereSql;
	}


	// 전체로우수, 페이지카운트
	function getCount($param = "") {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
		$param = escape_string($param);
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
		$param = escape_string($param);
		$whereSql = $this->getWhereSql($param);	// where절

		$sql = "
			SELECT *, 
				(SELECT name FROM hospital WHERE hospital.no = ".$this->tableName.".hospital_fk) AS hospital_name,
				(SELECT name FROM clinic WHERE clinic.no = ".$this->tableName.".clinic_fk) AS clinic_name,
				(SELECT COUNT(*) FROM comment WHERE tablename = '".$this->tableName."' AND parent_fk = ".$this->tableName.".no) AS comment_count
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
				hospital_fk, clinic_fk, name, email, title, contents, relation_url, registdate,";
		if ($req[filename]) {
			$sql .= "filename, filename_org, filesize, ";
		}
		if ($req[moviename]) {
			$sql .= "moviename, moviename_org, ";
		}
		if ($req[beforeimagename]) {
			$sql .= "beforeimagename, beforeimagename_org, beforeimage_alt, ";
		}
		if ($req[beforeimagename2]) {
			$sql .= "beforeimagename2, beforeimagename2_org, beforeimage2_alt, ";
		}
		if ($req[beforeimagename3]) {
			$sql .= "beforeimagename3, beforeimagename3_org, beforeimage3_alt, ";
		}
		if ($req[afterimagename]) {
			$sql .= "afterimagename, afterimagename_org, afterimage_alt, ";
		}
		if ($req[afterimagename2]) {
			$sql .= "afterimagename2, afterimagename2_org, afterimage2_alt, ";
		}
		if ($req[afterimagename3]) {
			$sql .= "afterimagename3, afterimagename3_org, afterimage3_alt, ";
		}
		if ($req[readno]) {
			$sql .= "readno, ";
		}
		$sql .= "top, main, newicon
			) VALUES (
			".chkIsset($req['hospital_fk']).",
			".chkIsset($req['clinic_fk']).",
			'$req[name]', 
			'$req[email]', 
			'$req[title]',
			'$req[contents]',
			'$req[relation_url]', ";
		if ($req[registdate]) { 
			$sql .= "'$req[registdate]', ";
		} else {
			$sql .= " NOW(), ";
		}
		if ($req[filename]) {
			$sql .= "'$req[filename]', '$req[filename_org]', $req[filesize], ";
		}
		if ($req[moviename]) {
			$sql .= "'$req[moviename]', '$req[moviename_org]', ";
		}
		if ($req[beforeimagename]) {
			$sql .= "'$req[beforeimagename]', '$req[beforeimagename_org]', '$req[beforeimage_alt]', ";
		}
		if ($req[beforeimagename2]) {
			$sql .= "'$req[beforeimagename2]', '$req[beforeimagename2_org]', '$req[beforeimage2_alt]', ";
		}
		if ($req[beforeimagename3]) {
			$sql .= "'$req[beforeimagename3]', '$req[beforeimagename3_org]', '$req[beforeimage3_alt]', ";
		}
		if ($req[afterimagename]) {
			$sql .= "'$req[afterimagename]', '$req[afterimagename_org]', '$req[afterimage_alt]', ";
		}
		if ($req[afterimagename2]) {
			$sql .= "'$req[afterimagename2]', '$req[afterimagename2_org]', '$req[afterimage2_alt]', ";
		}
		if ($req[afterimagename3]) {
			$sql .= "'$req[afterimagename3]', '$req[afterimagename3_org]', '$req[afterimage3_alt]', ";
		}
		if ($req[readno]) {
			$sql .= chkIsset($req[readno]).", ";
		}
		
		$sql .= "
			".chkIsset($req[top]).",
			".chkIsset($req[main]).", 
			".chkIsset($req[newicon])."
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

		// 기존 첨부파일 삭제
		if ($req[filename_chk] == "1") {
			mysql_query("UPDATE ".$this->tableName." SET filename='', filename_org='', filesize=0 WHERE no=".$req[no], $conn);
		}
		// 기존 동영상파일 삭제
		if ($req[moviename_chk] == "1") {
			mysql_query("UPDATE ".$this->tableName." SET moviename='', moviename_org='' WHERE no=".$req[no], $conn);
		}
		// 치료전 목록이미지 삭제
		if ($req[beforeimagename_chk] == "1") {
			mysql_query("UPDATE ".$this->tableName." SET beforeimagename='', beforeimagename_org='', beforeimage_alt='' WHERE no=".$req[no], $conn);
		}
		if ($req[beforeimagename2_chk] == "1") {
			mysql_query("UPDATE ".$this->tableName." SET beforeimagename2='', beforeimagename2_org='', beforeimage2_alt='' WHERE no=".$req[no], $conn);
		}
		if ($req[beforeimagename3_chk] == "1") {
			mysql_query("UPDATE ".$this->tableName." SET beforeimagename3='', beforeimagename3_org='', beforeimage3_alt='' WHERE no=".$req[no], $conn);
		}
		// 치료후 목록이미지 삭제
		if ($req[afterimagename_chk] == "1") {
			mysql_query("UPDATE ".$this->tableName." SET afterimagename='', afterimagename_org='', afterimage_alt='' WHERE no=".$req[no], $conn);
		}
		if ($req[beforeimagename2_chk] == "1") {
			mysql_query("UPDATE ".$this->tableName." SET afterimagename2='', afterimagename2_org='', afterimage2_alt='' WHERE no=".$req[no], $conn);
		}
		if ($req[beforeimagename3_chk] == "1") {
			mysql_query("UPDATE ".$this->tableName." SET afterimagename3='', afterimagename3_org='', afterimage3_alt='' WHERE no=".$req[no], $conn);
		}

		$sql = "
			UPDATE ".$this->tableName." SET 
				hospital_fk=".chkIsset($req['hospital_fk']).", clinic_fk=".chkIsset($req['clinic_fk']).", name='$req[name]', email='$req[email]', title='$req[title]', contents='$req[contents]',
        		relation_url='$req[relation_url]', ";
		if ($req[filename]) {
			$sql .= "filename='$req[filename]', filename_org='$req[filename_org]', filesize=$req[filesize], ";
		}
		if ($req[moviename]) {
			$sql .= "moviename='$req[moviename]', moviename_org='$req[moviename_org]', ";
		}
		if ($req[beforeimagename]) {
			$sql .= "beforeimagename='$req[beforeimagename]', beforeimagename_org='$req[beforeimagename_org]', beforeimage_alt='$req[beforeimage_alt]', ";
		}
		if ($req[beforeimagename2]) {
			$sql .= "beforeimagename2='$req[beforeimagename2]', beforeimagename2_org='$req[beforeimagename2_org]', beforeimage2_alt='$req[beforeimage2_alt]', ";
		}
		if ($req[beforeimagename3]) {
			$sql .= "beforeimagename3='$req[beforeimagename3]', beforeimagename3_org='$req[beforeimagename3_org]', beforeimage3_alt='$req[beforeimage3_alt]', ";
		}
		if ($req[afterimagename]) {
			$sql .= "afterimagename='$req[afterimagename]', afterimagename_org='$req[afterimagename_org]', afterimage_alt='$req[afterimage_alt]', ";
		}
		if ($req[afterimagename2]) {
			$sql .= "afterimagename2='$req[afterimagename2]', afterimagename2_org='$req[afterimagename2_org]', afterimage2_alt='$req[afterimage2_alt]', ";
		}
		if ($req[afterimagename3]) {
			$sql .= "afterimagename3='$req[afterimagename3]', afterimagename3_org='$req[afterimagename3_org]', afterimage3_alt='$req[afterimage3_alt]', ";
		}
		if ($req[readno]) {
			$sql .= "readno=".chkIsset($req[readno]).", ";
		}
		if ($req[registdate]) { 
			$sql .= "registdate='$req[registdate]', ";
		}
		$sql .= " top=".chkIsset($req[top]).", main=".chkIsset($req[main]).", newicon=".chkIsset($req[newicon])."
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
				(SELECT name FROM hospital WHERE hospital.no = ".$this->tableName.".hospital_fk) AS hospital_name,
				(SELECT name FROM clinic WHERE clinic.no = ".$this->tableName.".clinic_fk) AS clinic_name
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

	// 파일명 가져오기
	function getFilenames($no=0) {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();

		$sql = "
			SELECT 
				filename, moviename, imagename
			FROM ".$this->tableName."
			WHERE no = ".$no;
		
		$result = mysql_query($sql, $conn);
		mysql_close($conn);

		return $result;
	}

	// rownum 구하기
	function getRowNum($req='') {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
		
		$whereSql = $this->getWhereSql($req);	// where절

		$sql = "
			SELECT rownum FROM (
				SELECT @rownum:=@rownum+1 AS rownum, no, title FROM ".$this->tableName.", (SELECT @rownum:=0) AS r
				".$whereSql."
				ORDER BY top DESC
			) AS r2
			WHERE r2.no = ".$req['no'];
		
		$result = mysql_query($sql, $conn);
		mysql_close($conn);

		return $result;
	}

	// 다음글 가져오기
	function getPrevRowNum($req='', $rownum=0) {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();

		$whereSql = $this->getWhereSql($req);	// where절

		$sql = "
			SELECT
				ifnull(rownum,0), ifnull(no,0) AS prev_no, title AS prev_title, registdate AS prev_registdate
			FROM (
				SELECT @rownum:=@rownum+1 AS rownum, no, title, registdate from ".$this->tableName.", (SELECT @rownum:=0) AS r
				".$whereSql."
				ORDER BY top DESC, registdate DESC
			) AS r2
			WHERE r2.rownum = $rownum+1";
		
		$result = mysql_query($sql, $conn);
		mysql_close($conn);
		$data = mysql_fetch_assoc($result);

		return $data;
	}

	// 이전글 가져오기
	function getNextRowNum($req='', $rownum=0) {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();

		$whereSql = $this->getWhereSql($req);	// where절

		$sql = "
			SELECT
				ifnull(rownum,0), ifnull(no,0) AS next_no, title AS next_title, registdate AS next_registdate
			FROM (
				SELECT @rownum:=@rownum+1 AS rownum, no, title, registdate from ".$this->tableName.", (SELECT @rownum:=0) AS r
				".$whereSql."
				ORDER BY top DESC, registdate DESC
			) AS r2
			WHERE r2.rownum = $rownum-1";
		
		$result = mysql_query($sql, $conn);
		mysql_close($conn);
		$data = mysql_fetch_assoc($result);

		return $data;
	}

	// 조회수 +1
	function updateReadno($no=0) {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();

		$sql = "UPDATE ".$this->tableName." SET readno = readno+1 WHERE no = $no";

		$result = mysql_query($sql, $conn);
		mysql_close($conn);
		return $result;
	}

	// 메인목록 조회
	function getMainList($number) {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
		
		$whereSql = $this->getWhereSql($param);	// where절

		$sql = "
			SELECT *.
				(SELECT name FROM hospital WHERE hospital.no = ".$this->tableName.".hospital_fk) AS hospital_name,
				(SELECT name FROM clinic WHERE clinic.no = ".$this->tableName.".clinic_fk) AS clinic_name
			FROM ".$this->tableName."
			ORDER BY main DESC, registdate DESC 
			LIMIT 0, ".$number." ";

		$result = mysql_query($sql, $conn);
		mysql_close($conn);

		return $result;
	}
	

}


?>