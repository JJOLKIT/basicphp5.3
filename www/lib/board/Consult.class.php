<?
/*


*/

include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/db/DBConnection.class.php";

class Consult {
	
	// 검색 파라미터 (초기 개발시 검색조건 세팅필요)
	var $param = array (
			"stype",
			"sval",
			"sdateType",
			"sstartdate",
			"senddate",
			"smember_fk"
	);
	
	var $tableName;			// 테이블명
	var $pageRows;			// 페이지 로우수
	var $startPageNo=0;		// limit 시작페이지
	public $reqPageNo=1;	// 요청페이지
	var $conn;
	
	// 생성자
	function Consult($pageRows=0, $tableName='', $request='') {
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
	
	// sql WHERE절 생성
	function getWhereSql($p) {
		$whereSql = " WHERE 1=1";
		if ($p['sval']) {
			if ($p['stype'] == 'all') {
				$whereSql .= " AND ( (name like '%".$p['sval']."%' ) or (contents like '%".$p['sval']."%' ) or (email like '%".$p['sval']."%' ) or (title like '%".$p['sval']."%') ) ";
			} else {
				$whereSql .= " AND (".$p['stype']." LIKE '%".$p['sval']."%' )";
			}
		}
		if ($p['sstartdate'] != '') {
			if ($p['senddate'] != '') {
				$whereSql .= " AND (registdate BETWEEN '".$p['sstartdate']." 00:00:00' AND '".$p['senddate']." 23:59:59') ";
			}
		}
		if ($p['smember_fk'] != '') {
			$whereSql .= " AND member_fk = ".$p['smember_fk'];
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
			SELECT *
			FROM ".$this->tableName." AS lc
			".$whereSql."
			ORDER BY registdate DESC LIMIT ".$this->startPageNo.", ".$this->pageRows." ";
		
		$result = mysql_query($sql, $conn);
		mysql_close($conn);
		
		return $result;
	}
	
	// 관리자 등록
	function insert($req="") {
		$req = getReqAddSlash($req);
		$req['contents'] = str_replace('\"\"', '', $req['contents']);
		$req['contents'] = str_replace('\"', '', $req['contents']);
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
		$req = escape_string($req);
		//$gno = $this->getMaxGno();
		$sql = "
			INSERT INTO ".$this->tableName." (
				state, member_fk, name, cell, email, title, contents,  ";
		if ($req[filename]) {
			$sql .= "filename, filename_org, filesize, ";
		}

		$sql .= " registdate
			) VALUES (
			1,
			".chkIsset($req[member_fk]).",
			'$req[name]',
			'$req[cell]',
			'$req[email]',
			'$req[title]',
			'$req[contents]', 
			";
		if ($req[filename]) {
			$sql .= "'$req[filename]', '$req[filename_org]', $req[filesize], ";
		}

		$sql .= "
			NOW()
			)";
		
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
		$req = getReqAddSlash($req);
		$req['contents'] = str_replace('\"\"', '', $req['contents']);
		$req['contents'] = str_replace('\"', '', $req['contents']);

		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
		$req = escape_string($req);
		
		// 기존 첨부파일 삭제

		if ($req[filename_chk] == "1") {
			mysql_query("UPDATE ".$this->tableName." SET filename='', filename_org='', filesize=0 WHERE no=".$req[no], $conn);
		}
		
		$sql = "
			UPDATE ".$this->tableName." SET
			name='$req[name]', tel='$req[tel]', email='$req[email]', title='$req[title]', contents='".$req[contents]."' ,";
		if ($req[filename]) {
			$sql .= ", filename='$req[filename]', filename_org='$req[filename_org]', filesize=$req[filesize] ";
		}
	
		$sql .= "
			WHERE no = ".$req['no'];
		
		$result = mysql_query($sql, $conn);
		mysql_close($conn);
		return $result;
	}
	
	// 답변
	function answer($req="") {
		$req = getReqAddSlash($req);
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
		$req = escape_string($req);

		if ($req[answerfilename_chk] == "1") {
			mysql_query("UPDATE ".$this->tableName." SET answerfilename='', answerfilename_org='' WHERE no=".$req[no], $conn);
		}


		$sql = "
			UPDATE ".$this->tableName." SET answer = '$req[answer]', ";
		if ($req[answerfilename]) {
			$sql .= " answerfilename='$req[answerfilename]', answerfilename_org='$req[answerfilename_org]', ";
		}
				
		$sql .= " answer_title='$req[answer_title]', answer_name = '$req[answer_name]', answer_date = NOW(), state = 2
			WHERE no = ".$req['no'];
		outlog($sql);
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

		$sql = "
			SELECT *
			FROM ".$this->tableName."
			WHERE no = ".$no;
		
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
				filename
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
		$req = escape_string($req);
		$whereSql = $this->getWhereSql($req);	// where절
		
		$sql = "
			SELECT rownum FROM (
				SELECT @rownum:=@rownum+1 AS rownum, no, title FROM ".$this->tableName.", (SELECT @rownum:=0) AS r
				".$whereSql."
				ORDER BY top DESC, gno DESC, ono ASC
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
		$req = escape_string($req);
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
		$req = escape_string($req);
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
	
	// 목록
	function getMainList() {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
		
		$sql = "
			SELECT *
			FROM ".$this->tableName." AS lc
			WHERE answer is null OR answer = '<p>&nbsp;</p>' ";
		
		$result = mysql_query($sql, $conn);
		mysql_close($conn);
		
		return $result;
	}


	
}


?>