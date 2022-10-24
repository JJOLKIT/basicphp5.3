<?
/*


*/

include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/dbConfig.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/db/DBConnection.class.php";

class Estimate {

	// 검색 파라미터 (초기 개발시 검색조건 세팅필요)
	var $param = array (
					"stype",
					"sval"
				);

	var $tableName;			// 테이블명
	var $pageRows;			// 페이지 로우수
	var $startPageNo=0;		// limit 시작페이지
	public $reqPageNo=1;	// 요청페이지
	var $conn;

	// 생성자
	function Estimate($pageRows=0, $tableName='', $request='') {
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
			if (chkIsset($request[$this->param[$i]] )) {
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
				$whereSql .= " AND ( (name like '%".$p['sval']."%' ) or (contents like '%".$p['sval']."%') or (email like '%".$p['sval']."%') or (tel like '%".$p['sval']."%') or (cell like '%".$p['sval']."%') ) ";
			} else {
				$whereSql .= " AND (".$p['stype']." LIKE '%".$p['sval']."%' )";
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
			SELECT *
			FROM ".$this->tableName."
			".$whereSql."
			ORDER BY registdate DESC LIMIT ".$this->startPageNo.", ".$this->pageRows." ";

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
				name, email, tel, cell, fax, addr, contents, registdate, ";
		if ($req[filename]) {
			$sql .= "filename, filename_org, filesize, ";
		}
		if ($req[filename2]) {
			$sql .= "filename2, filename2_org, filesize2, ";
		}
		if ($req[filename3]) {
			$sql .= "filename3, filename3_org, filesize3, ";
		}
		$sql .= "type, amount, design_file, case_make, design_file_type, send
			) VALUES (
			'$req[name]', 
			'$req[email]', 
			'$req[tel]',
			'$req[cell]',
			'$req[fax]',
			'$req[addr]',
			'$req[contents]', ";
		if ($req[registdate]) { 
			$sql .= "'$req[registdate]', ";
		} else {
			$sql .= " NOW(), ";
		}
		if ($req[filename]) {
			$sql .= "'$req[filename]', '$req[filename_org]', $req[filesize], ";
		}
		if ($req[filename2]) {
			$sql .= "'$req[filename2]', '$req[filename2_org]', $req[filesize2], ";
		}
		if ($req[filename3]) {
			$sql .= "'$req[filename3]', '$req[filename3_org]', $req[filesize3], ";
		}
		
		$sql .= "
			".chkIsset($req[type]).",
			'$req[amount]',
			".chkIsset($req[design_file]).",
			".chkIsset($req[case_make]).",
			".chkIsset($req[design_file_type]).",
			".chkIsset($req[send])."
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
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();

		// 기존 첨부파일 삭제
		if ($req[filename_chk] == "1") {
			mysql_query("UPDATE ".$this->tableName." SET filename='', filename_org='', filesize=0 WHERE no=".$req[no], $conn);
		}
		if ($req[filename2_chk] == "1") {
			mysql_query("UPDATE ".$this->tableName." SET filename2='', filename2_org='', filesize=0 WHERE no=".$req[no], $conn);
		}
		if ($req[filename3_chk] == "1") {
			mysql_query("UPDATE ".$this->tableName." SET filename3='', filename3_org='', filesize3=0 WHERE no=".$req[no], $conn);
		}

		$sql = "
			UPDATE ".$this->tableName." SET 
				name='$req[name]', email='$req[email]', tel='$req[tel]', cell='$req[cell]', fax='$req[fax]', contents='$req[contents]', ";
		if ($req[filename]) {
			$sql .= "filename='$req[filename]', filename_org='$req[filename_org]', filesize=$req[filesize], ";
		}
		if ($req[filename2]) {
			$sql .= "filename2='$req[filename2]', filename2_org='$req[filename2_org]', filesize2=$req[filesize2], ";
		}
		if ($req[filename3]) {
			$sql .= "filename3='$req[filename3]', filename3_org='$req[filename3_org]', filesize3=$req[filesize3], ";
		}
		if ($req[registdate]) { 
			$sql .= "registdate='$req[registdate]', ";
		}
		$sql .= " type=".chkIsset($req[type]).", amount='".$req[amount]."', design_file=".chkIsset($req[design_file]).", 
			case_make=".chkIsset($req[case_make]).", design_file_type=".chkIsset($req[design_file_type]).", send=".chkIsset($req[send])."
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
				filename, filename2, filename3
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
	function getNextRowNum($req='', $rownum=0) {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();

		$whereSql = $this->getWhereSql($req);	// where절

		$sql = "
			SELECT
				ifnull(rownum,0), ifnull(no,0) AS next_no, title AS next_title
			FROM (
				SELECT @rownum:=@rownum+1 AS rownum, no, title from ".$this->tableName.", (SELECT @rownum:=0) AS r
				".$whereSql."
				ORDER BY top DESC
			) AS r2
			WHERE r2.rownum = $req[rownum]+1";
		
		$result = mysql_query($sql, $conn);
		mysql_close($conn);

		return $result;
	}

	// 이전글 가져오기
	function getPrevRowNum($req='', $rownum=0) {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();

		$whereSql = $this->getWhereSql($req);	// where절

		$sql = "
			SELECT
				ifnull(rownum,0), ifnull(no,0) AS next_no, title AS next_title
			FROM (
				SELECT @rownum:=@rownum+1 AS rownum, no, title from ".$this->tableName.", (SELECT @rownum:=0) AS r
				".$whereSql."
				ORDER BY top DESC
			) AS r2
			WHERE r2.rownum = $req[rownum]-1";
		
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
			SELECT *
			FROM ".$this->tableName."
			ORDER BY registdate DESC 
			LIMIT 0, ".$number." ";

		$result = mysql_query($sql, $conn);
		mysql_close($conn);

		return $result;
	}
	

}


?>