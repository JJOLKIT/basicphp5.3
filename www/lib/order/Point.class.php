<?
/*
포인트

*/

include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/db/DBConnection.class.php";

class Point {

	// 검색 파라미터 (초기 개발시 검색조건 세팅필요)
	var $param = array (
					"smember_fk",
					"sdatetype",
					"sstartdate",
					"senddate"
				);

	var $pageRows;			// 페이지 로우수
	var $startPageNo=0;		// limit 시작페이지
	public $reqPageNo=1;	// 요청페이지
	var $conn;

	// 생성자
	function Point($pageRows=0, $request='') {
		$this->pageRows = $pageRows;
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

	/**
	 * sql WHERE절 생성
	 * @param $_REQUEST
	 * @return string
	 */
	function getWhereSql($p) {
		$whereSql = " WHERE 1 = 1 ";
		if ($p['smember_fk'] != "") {
			$whereSql .= " AND member_fk = ".$p['smember_fk'];
		}
		if ($p['sstartdate'] != '') {
			if ($p['senddate'] != '') {
				$whereSql .= " AND (registdate BETWEEN '".$p['sstartdate']." 00:00:00' AND '".$p['senddate']." 23:59:59') ";
			}
		}
		return $whereSql;
	}

	/**
	 * 전체로우수, 페이지카운트
	 * @param $_REQUEST $param
	 * @return array
	 */
	function getCount($param = "") {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
		
		$whereSql = $this->getWhereSql($param);	// where절
		$sql = " SELECT COUNT(*) AS cnt FROM point ".$whereSql;

		$result = mysql_query($sql, $conn);
		mysql_close($conn);

		$row=mysql_fetch_array($result);
		$totalCount = $row['cnt'];
		$pageCount = getPageCount($this->pageRows, $totalCount);

		$data[0] = $totalCount;
		$data[1] = $pageCount;

		return $data;
	}

	/**
	 * 목록
	 * @param $_REQUEST $param
	 * @return $result
	 */
	function getList($param='') {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
		
		$whereSql = $this->getWhereSql($param);	// where절

		$sql = "
			SELECT *,
				(SELECT name FROM member AS m WHERE m.no = point.member_fk) AS member_name,
				(SELECT id FROM member AS m WHERE m.no = point.member_fk) AS member_id
			FROM point
			".$whereSql."
			ORDER BY no DESC LIMIT ".$this->startPageNo.", ".$this->pageRows." ";
		
		$result = mysql_query($sql, $conn);
		mysql_close($conn);

		return $result;
	}
	
	/**
	 * 상세
	 * @param int $no
	 * @param bollean $userCon
	 * @return multitype:
	 */
	function getData($no=0) {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
	
		$sql = "
			SELECT *,
				(SELECT name FROM member AS m WHERE m.no = orders.member_fk) AS member_name,
				(SELECT id FROM member AS m WHERE m.no = orders.member_fk) AS member_id
			FROM point
			WHERE no = ".$no;
	
		$result = mysql_query($sql, $conn);
		mysql_close($conn);
		$data = mysql_fetch_assoc($result);
	
		return $data;
	}

	/**
	 * 등록
	 * @param $_REQUEST $req
	 * @return no
	 */
	function insert($req="") {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();

		$sql = "
			INSERT INTO point
				(type, member_fk, point, name, registdate)
			VALUES
				(".chkIsset($req[type]).", ".chkIsset($req[member_fk]).", ".chkIsset($req[point]).", '$req[name]', NOW())";
		mysql_query($sql, $conn);

		$sql = "SELECT LAST_INSERT_ID() AS lastNo";
		$result = mysql_query($sql, $conn);
		$row = mysql_fetch_array($result);
		$lastNo = $row['lastNo'];
		mysql_close($conn);
		return $lastNo;
	}
	
	/**
	 * 회원포인트 적립
	 * @param $_REQUEST $req
	 * @return result
	 */
	function plusPoint($point, $member_fk) {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();

		$sql = "
			UPDATE member SET 
				point=point+".$point."
			WHERE no = ".$member_fk;

		$result = mysql_query($sql, $conn);
		mysql_close($conn);
		return $result;
	}
	
	/**
	 * 회원포인트 사용
	 * @param $_REQUEST $req
	 * @return result
	 */
	function minusPoint($point, $member_fk) {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
	
		$sql = "
			UPDATE member SET
				point=point-".$point."
			WHERE no = ".$member_fk;
	
		$result = mysql_query($sql, $conn);
		mysql_close($conn);
		return $result;
	}
	
	/**
	 * 삭제
	 * @param int $no
	 * @return result
	 */
	function delete($no=0) {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();

		$sql = " DELETE FROM point WHERE no = ".$no;

		mysql_close($conn);
		return $result;
	}
	
	/**
	 * 회원 현재포인트 조회
	 * @param $member_fk
	 * @return $point
	 */
	function getPoint($member_fk=0) {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();

		$sql = "
			SELECT point
			FROM member AS m
			WHERE m.no=$member_fk ";
	
		$result = mysql_query($sql, $conn);
		mysql_close($conn);
		
		$row=mysql_fetch_array($result);
		$point = $row['point'];
	
		return $point;
	}
	
	/**
	 * 포인트 사용가능여부 체크 (주문완료 전 체크)
	 * @param $member_fk
	 * @return $point
	 */
	function chkUsePoint($member_fk=0, $point=0) {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
	
		$sql = "
		SELECT point
		FROM member AS m
		WHERE m.no=$member_fk ";
	
		$result = mysql_query($sql, $conn);
		mysql_close($conn);
	
		$row=mysql_fetch_array($result);
		
		$rst = false;
		if ($point < $row['point']) {
			$rst = true;
		}
	
		return $rst;
	}
	
}


?>