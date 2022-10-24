<?
/*


*/

include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/db/DBConnection.class.php";

class Reply {

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
	function Reply($pageRows=0, $tableName='', $request='') {
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
				$whereSql .= " AND ( (name like '%".$p['sval']."%' ) or (title like '%".$p['sval']."%' ) or (contents like '%".$p['sval']."%') ) ";
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
		    $whereSql .=" AND member_fk=".$p['smember_fk'];
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
				(SELECT COUNT(*) FROM comment WHERE tablename = '".$this->tableName."' and parent_fk = ".$this->tableName.".no) AS comment_count
			FROM ".$this->tableName."
			".$whereSql."
			ORDER BY top DESC, gno DESC, ono ASC LIMIT ".$this->startPageNo.", ".$this->pageRows." ";

		$result = mysql_query($sql, $conn);
		mysql_close($conn);

		return $result;
	}

	// gno 최대값+1
	function getMaxGno() {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
		
		$sql = " SELECT IFNULL(MAX(gno), 0)+1 AS maxgno FROM ".$this->tableName;

		$result = mysql_query($sql, $conn);
		mysql_close($conn);

		$row=mysql_fetch_array($result);
		$maxgno = $row['maxgno'];

		return $maxgno;
	}

	// ono 최대값
	function getMaxOno($gno) {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
		
		$sql = " SELECT IFNULL(MAX(ono), 0) AS maxono FROM ".$this->tableName." WHERE gno=".$gno;

		$result = mysql_query($sql, $conn);
		mysql_close($conn);

		$row=mysql_fetch_array($result);
		$maxono = $row['maxono'];

		return $maxono;
	}

	// ono 최소값
	function getMinOno($gno=0, $ono=0, $nested=0) {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
		
		$sql = " SELECT IFNULL(MIN(ono), 0) AS minono FROM ".$this->tableName." WHERE gno=".$gno." AND ono > ".$ono." AND nested <= ".$nested;

		$result = mysql_query($sql, $conn);
		mysql_close($conn);

		$row=mysql_fetch_array($result);
		$minono = $row['minono'];

		return $minono;
	}

	// ono가 0이 아닌경우 같은 그룹내 minOno보다 크거나 같은 ono +1
	function updateOno($gno=0, $minOno=0) {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();

		$sql = "UPDATE ".$this->tableName." SET ono = ono+1 WHERE gno = $gno AND ono >= $minOno";

		$result = mysql_query($sql, $conn);
		mysql_close($conn);
		return $result;
	}

	// 등록
	function insert($req="") {
		$gno = $this->getMaxGno();

		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();

		$sql = "
			INSERT INTO ".$this->tableName." (
				member_fk, gno, ono, nested, password, name, email, title, contents, relation_url, registdate, ";
		if ($req[filename]) {
			$sql .= "filename, filename_org, filesize, ";
		}
		if ($req[imagename]) {
			$sql .= "moviename, moviename_org, ";
		}
		if ($req[readno]) {	// 조회수값이 있는 경우에만
			$sql .= "readno, ";
		}
		$sql .= "top, main, newicon, secret
			) VALUES (
				".chkIsset($req[member_fk]).", $gno, 0, 0, ".DB_ENCRYPTION."('$req[password]'), '$req[name]', '$req[email]', '$req[title]', '$req[contents]', '$req[relation_url]', ";
		if ($req[registdate]) {	
			$sql .= " '$req[registdate]', ";
		} else {
			$sql .= " NOW(), ";
		}
		if ($req[filename]) {
			$sql .= "'$req[filename]', '$req[filename_org]', $req[filesize], ";
		}
		if ($req[moviename]) {
			$sql .= "'$req[moviename]', '$req[moviename_org]', ";
		}
		
		if ($req[readno]) {	// 조회수값이 있는 경우에만
			$sql .= " ".chkIsset($req[readno]).", ";
		}
		$sql .= " ".chkIsset($req['top']).", ".chkIsset($req['main']).", ".chkIsset($req['newicon']).", ".chkIsset($req['secret'])."
			)";
outlog($sql);
		mysql_query($sql, $conn);

		$sql = "SELECT LAST_INSERT_ID() AS lastNo";
		$result = mysql_query($sql, $conn);
		$row = mysql_fetch_array($result);
		$lastNo = $row['lastNo'];
		mysql_close($conn);
		return $lastNo;
	}

	// 답변
	function answer($req="") {
		$maxOno = $this->getMaxOno($req[gno]);
		$minOno = $this->getMinOno($req[gno], $req[ono], $req[nested]);

		$ono = 0;
		if ($minOno == 0) {
			$ono = $maxOno+1;
		} else {
			$this->updateOno($req[gno], $minOno);
			$ono = $minOno;
		}

		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();

		$nested = $req[nested] + 1;

		$sql = "
			INSERT INTO ".$this->tableName." (
				member_fk, gno, ono, nested, password, name, email, title, contents, relation_url,  registdate, ";
		if ($req[filename]) {
			$sql .= "filename, filename_org, filesize, ";
		}
		if ($req[imagename]) {
			$sql .= "moviename, moviename_org, ";
		}
		if ($req[readno]) {	// 조회수값이 있는 경우에만
			$sql .= "readno, ";
		}
		$sql .= "top, main, newicon, secret
			) VALUES (
				".chkIsset($req[member_fk]).", $req[gno], $ono, $nested, ".DB_ENCRYPTION."('$req[password]'), '$req[name]', '$req[email]', '$req[title]', '$req[contents]', '$req[relation_url]', ";
		if ($req[registdate]) {	
			$sql .= " '$req[registdate]', ";
		} else {
			$sql .= " NOW(), ";
		}
		if ($req[filename]) {
			$sql .= "'$req[filename]', '$req[filename_org]', $req[filesize], ";
		}
		if ($req[moviename]) {
			$sql .= "'$req[moviename]', '$req[moviename_org]', ";
		}
		
		if ($req[readno]) {	// 조회수값이 있는 경우에만
			$sql .= " ".chkIsset($req[readno]).", ";
		}
		$sql .= chkIsset($req[top]).", ".chkIsset($req[main]).", ".chkIsset($req[newicon]).", ".chkIsset($req[secret])."
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
		if ($req['imagename_chk'] == 1) {
			mysql_query("UPDATE ".$this->tableName." SET imagename='', imagename_org='' WHERE no=".$req[no], $conn);
		}

		$sql = "
			UPDATE ".$this->tableName." SET 
			member_fk=".chkIsset($req[member_fk]).", name='$req[name]', email='$req[email]', title='$req[title]', contents='$req[contents]', relation_url='$req[relation_url]', 
			";
		if ($req['password']) $sql = $sql." password = ".DB_ENCRYPTION."('".$req['password']."'),";		// password 값이 있는경우에만 update
		if ($req[registdate]) {
			$sql .= " registdate = '$req[registdate]', ";
		}
		if ($req[readno]) {
			$sql .= " readno=".chkIsset($req[readno]).", ";
		}
		if ($req['filename']) {
			$sql .= " filename='$req[filename]', filename_org='$req[filename_org]', filesize='$req[filesize]', ";
		}
		if ($req['imagename']) {
			$sql .= " imagename='$req[imagename]', imagename_org='$req[imagename_org]', ";
		}
		
		$sql .= " top=".chkIsset($req[top]).", main=".chkIsset($req[main]).", newicon=".chkIsset($req[newicon]).", secret=".chkIsset($req[secret])."
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
				(SELECT COUNT(*) FROM comment WHERE tablename = '".$this->tableName."' and parent_fk = ".$this->tableName.".no) AS comment_count
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
				filename, moviename
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
				ORDER BY top DESC, gno DESC, ono ASC
			) AS r2
			WHERE r2.no = ".$req['no'];
		
		$result = mysql_query($sql, $conn);
		mysql_close($conn);

		$row=mysql_fetch_array($result);
		$rownum = $row['rownum'];
		
		return $rownum;
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
			SELECT *,
			(SELECT COUNT(*) FROM comment WHERE tablename = '".$this->tableName."' and parent_fk = ".$this->tableName.".no) AS comment_count
			FROM ".$this->tableName."
			ORDER BY gno DESC, ono ASC
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
				COUNT(*) AS cnt
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