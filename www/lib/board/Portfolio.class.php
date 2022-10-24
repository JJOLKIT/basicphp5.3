<?
/*


*/

include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/dbConfig.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/db/DBConnection.class.php";

class Portfolio {

	// 검색 파라미터 (초기 개발시 검색조건 세팅필요)
	var $param = array (
					"stype",
					"sval",
					"sdisplay",
			 		"scategory",
					"smain"
				);

	var $tableName;			// 테이블명
	var $pageRows;			// 페이지 로우수
	var $startPageNo=0;		// limit 시작페이지
	public $reqPageNo=1;	// 요청페이지
	var $conn;

	// 생성자
	function Portfolio($pageRows=0, $tableName='', $request='') {
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
				$whereSql .= " AND ( (title like '%".$p['sval']."%' ) or (contents like '%".$p['sval']."%') ) ";
			} else {
				$whereSql .= " AND (".$p['stype']." LIKE '%".$p['sval']."%' )";
			}
		}
		if ($p['sdisplay'] != '') {
			$whereSql .= " AND display = ".$p['sdisplay'];
		}
		if ($p['scategory'] != '') {
			$whereSql .= " AND category = ".$p['scategory'];
		}
		if ($p['smain'] != '') {
			$whereSql .= " AND main = ".$p['smain'];
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
			ORDER BY rank ASC, registdate DESC LIMIT ".$this->startPageNo.", ".$this->pageRows." ";

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
				category, title, contents, registdate, ";
		if ($req[imagename]) {
			$sql .= "imagename, imagename_org, ";
		}
		if ($req[readno]) {
			$sql .= "readno, ";
		}
		$sql .= "main, display, rank
			) VALUES (
			".chkIsset($req[category]).", 
			'$req[title]',
			'$req[contents]', ";
		if ($req[registdate]) { 
			$sql .= "'$req[registdate]', ";
		} else {
			$sql .= " NOW(), ";
		}
		if ($req[imagename]) {
			$sql .= "'$req[imagename]', '$req[imagename_org]', ";
		}
		if ($req[readno]) {
			$sql .= chkIsset($req[readno]).", ";
		}
		
		$sql .= "
			".chkIsset($req[main]).",
			".chkIsset($req[display]).",
			".chkIsset($req[rank])."
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

		// 기존 목록이미지 삭제
		if ($req[imagename_chk] == "1") {
			mysql_query("UPDATE ".$this->tableName." SET imagename='' WHERE no=".$req[no], $conn);
		}

		$sql = "
			UPDATE ".$this->tableName." SET 
				category=".chkIsset($req[category]).", title='$req[title]', contents='$req[contents]', ";
		if ($req[imagename]) {
			$sql .= "imagename='$req[imagename]', imagename_org='$req[imagename_org]', ";
		}
		if ($req[readno]) {
			$sql .= "readno=".chkIsset($req[readno]).", ";
		}
		if ($req[registdate]) { 
			$sql .= "registdate='$req[registdate]', ";
		}
		$sql .= " main=".chkIsset($req[main]).", display=".chkIsset($req[display]).", rank=".chkIsset($req[rank])."
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
				imagename
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
				ORDER BY rank ASC, registdate DESC
			) AS r2
			WHERE r2.no = ".$req['no'];
		
		$result = mysql_query($sql, $conn);
		mysql_close($conn);
		$data = mysql_fetch_assoc($result);

		return $data;
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
				ORDER BY rank ASC, registdate DESC
			) AS r2
			WHERE r2.rownum = $rownum+1";
		
		$result = mysql_query($sql, $conn);
		mysql_close($conn);
		$data = mysql_fetch_assoc($result);

		return $data;
	}

	// 이전글 가져오기
	function getPrevRowNum($req='', $rownum=0) {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();

		$whereSql = $this->getWhereSql($req);	// where절

		$sql = "
			SELECT
				ifnull(rownum,0), ifnull(no,0) AS prev_no, title AS next_title
			FROM (
				SELECT @rownum:=@rownum+1 AS rownum, no, title from ".$this->tableName.", (SELECT @rownum:=0) AS r
				".$whereSql."
				ORDER BY rank ASC, registdate DESC
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
			SELECT *
			FROM ".$this->tableName."
			ORDER BY main DESC, registdate DESC 
			LIMIT 0, ".$number." ";

		$result = mysql_query($sql, $conn);
		mysql_close($conn);

		return $result;
	}
	

}


?>