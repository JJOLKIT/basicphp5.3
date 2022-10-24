<?
/*


*/

include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/db/DBConnection.class.php";

class Weblog {

	// 검색 파라미터 (초기 개발시 검색조건 세팅필요)
	var $param = array (
					"stype",
					"sval",
					"reqPageNo",
					"sdate",
					"edate"
				);

	var $pageRows;			// 페이지 로우수
	var $startPageNo=0;		// limit 시작페이지
	public $reqPageNo=1;	// 요청페이지
	var $conn;

	// 생성자
	function Weblog($pageRows=0, $request='') {
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
			if (chkIsset($request[$this->param[$i]] )) {
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

	
	// weblog insert
	function weblogInsert($connectid, $con_host, $con_search, $con_ip) {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
	
		$sql = "
			INSERT INTO weblog (
				connectid,con_host,con_search,con_ip ";
		$sql .= "
			) VALUES (
				'$connectid', '$con_host', '$con_search', '$con_ip')";
outlog($sql);	
		mysql_query($sql, $conn);
	
		$sql = "SELECT LAST_INSERT_ID() AS lastNo";
		$result = mysql_query($sql, $conn);
		$row = mysql_fetch_array($result);
		$lastNo = $row['lastNo'];
		mysql_close($conn);
		return $lastNo;
	}
	
	// 방문상담상세
	function getWeblogData($connectid="") {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
		
		$sql = "
			SELECT *
			FROM weblog AS a
			WHERE connectid = '".$connectid."' ORDER BY registdate ASC LIMIT 0,1 ";
		
		$result = mysql_query($sql, $conn);
		mysql_close($conn);
		$data = mysql_fetch_assoc($result);
		
		return $data;
	}
	
	// 유입경로 리스트
	function getWeblogCount($req="") {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
		
		$whereSql = " WHERE 1=1 ";
		
		if ($req['sval']) {
			$whereSql .= " AND (con_search like '%".$req['sval']."%' OR con_ip like '%".$req['sval']."%') ";
		}
		if ($req['sdate'] && $req['edate']) {
			$whereSql .= " AND registdate BETWEEN '".$req['sdate']." 00:00:00' AND '".$req['edate']." 23:59:59' ";
		}
		
		$sql = "
			SELECT COUNT(*) AS cnt
			FROM weblog AS a ".$whereSql."
			";
		
		$result = mysql_query($sql, $conn);
		mysql_close($conn);
		
		$row=mysql_fetch_array($result);
		$totalCount = $row['cnt'];
		$pageCount = getPageCount($this->pageRows, $totalCount);
		
		$data[0] = $totalCount;
		$data[1] = $pageCount;
		
		return $data;
	}
	
	// 유입경로 리스트
	function getWeblogList($req="") {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
		
		$whereSql = " WHERE 1=1 ";
		
		if ($req['sval']) {
			$whereSql .= " AND (con_search like '%".$req['sval']."%' OR con_ip like '%".$req['sval']."%') ";
		}
		if ($req['sdate'] && $req['edate']) {
			$whereSql .= " AND registdate BETWEEN '".$req['sdate']." 00:00:00' AND '".$req['edate']." 23:59:59' ";
		}
		
		$sql = "
			SELECT *
			FROM weblog AS a ".$whereSql."
			ORDER BY registdate DESC LIMIT ".$this->startPageNo.", ".$this->pageRows." ";
		
		$result = mysql_query($sql, $conn);
		mysql_close($conn);
		
		return $result;
	}

	function getConSearchList($req=''){
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();

		$sql = "
			SELECT con_search, count(con_search) as cnt FROM weblog GROUP BY con_search ORDER BY cnt DESC limit 500
		";

		$result = mysql_query($sql, $conn);

		mysql_close($conn);

		return $result;


	}

	function getMonthlyCount($req=''){
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
		$year = Date('Y');
		$sql = "
			SELECT 
			COUNT(IF(registdate BETWEEN '".$year."-01-01 00:00:00' AND '".$year."-01-31 23:59:59', 1, null)) AS cnt1 ,
			COUNT(IF(registdate BETWEEN '".$year."-02-01 00:00:00' AND '".$year."-02-29 23:59:59', 1, null)) AS cnt2 ,
			COUNT(IF(registdate BETWEEN '".$year."-03-01 00:00:00' AND '".$year."-03-31 23:59:59', 1, null)) AS cnt3 ,
			COUNT(IF(registdate BETWEEN '".$year."-04-01 00:00:00' AND '".$year."-04-30 23:59:59', 1, null)) AS cnt4 ,
			COUNT(IF(registdate BETWEEN '".$year."-05-01 00:00:00' AND '".$year."-05-31 23:59:59', 1, null)) AS cnt5 ,
			COUNT(IF(registdate BETWEEN '".$year."-06-01 00:00:00' AND '".$year."-06-30 23:59:59', 1, null)) AS cnt6 ,
			COUNT(IF(registdate BETWEEN '".$year."-07-01 00:00:00' AND '".$year."-07-31 23:59:59', 1, null)) AS cnt7 ,
			COUNT(IF(registdate BETWEEN '".$year."-08-01 00:00:00' AND '".$year."-08-31 23:59:59', 1, null)) AS cnt8 ,
			COUNT(IF(registdate BETWEEN '".$year."-09-01 00:00:00' AND '".$year."-09-30 23:59:59', 1, null)) AS cnt9 ,
			COUNT(IF(registdate BETWEEN '".$year."-10-01 00:00:00' AND '".$year."-10-31 23:59:59', 1, null)) AS cnt10 ,
			COUNT(IF(registdate BETWEEN '".$year."-11-01 00:00:00' AND '".$year."-11-30 23:59:59', 1, null)) AS cnt11 ,
			COUNT(IF(registdate BETWEEN '".$year."-12-01 00:00:00' AND '".$year."-12-31 23:59:59', 1, null)) AS cnt12 
			FROM weblog 
		";

		$result = mysql_query($sql, $conn);
		mysql_close($conn);

		$data = mysql_fetch_assoc($result);
		return $data;
	}

	function getMonthlyBusiness($req=''){
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
		$year = Date('Y');
		$sql = "
			SELECT 
			COUNT(IF(registdate BETWEEN '".$year."-01-01 00:00:00' AND '".$year."-01-31 23:59:59', 1, null)) AS cnt1 ,
			COUNT(IF(registdate BETWEEN '".$year."-02-01 00:00:00' AND '".$year."-02-29 23:59:59', 1, null)) AS cnt2 ,
			COUNT(IF(registdate BETWEEN '".$year."-03-01 00:00:00' AND '".$year."-03-31 23:59:59', 1, null)) AS cnt3 ,
			COUNT(IF(registdate BETWEEN '".$year."-04-01 00:00:00' AND '".$year."-04-30 23:59:59', 1, null)) AS cnt4 ,
			COUNT(IF(registdate BETWEEN '".$year."-05-01 00:00:00' AND '".$year."-05-31 23:59:59', 1, null)) AS cnt5 ,
			COUNT(IF(registdate BETWEEN '".$year."-06-01 00:00:00' AND '".$year."-06-30 23:59:59', 1, null)) AS cnt6 ,
			COUNT(IF(registdate BETWEEN '".$year."-07-01 00:00:00' AND '".$year."-07-31 23:59:59', 1, null)) AS cnt7 ,
			COUNT(IF(registdate BETWEEN '".$year."-08-01 00:00:00' AND '".$year."-08-31 23:59:59', 1, null)) AS cnt8 ,
			COUNT(IF(registdate BETWEEN '".$year."-09-01 00:00:00' AND '".$year."-09-30 23:59:59', 1, null)) AS cnt9 ,
			COUNT(IF(registdate BETWEEN '".$year."-10-01 00:00:00' AND '".$year."-10-31 23:59:59', 1, null)) AS cnt10 ,
			COUNT(IF(registdate BETWEEN '".$year."-11-01 00:00:00' AND '".$year."-11-30 23:59:59', 1, null)) AS cnt11 ,
			COUNT(IF(registdate BETWEEN '".$year."-12-01 00:00:00' AND '".$year."-12-31 23:59:59', 1, null)) AS cnt12 
			FROM weblog 
		";

		$result = mysql_query($sql, $conn);
		mysql_close($conn);

		$data = mysql_fetch_assoc($result);
		return $data;
	}

	
	//대시보드 데이터
	function getDashData(){
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();

		$today = Date("Y-m-d");
		$day1 = Date("Y-m-d", strtotime($today." -1 days"));
		
		$todayM = Date("Y-m");
		$todayMe = Date("Y-m-d", strtotime($todayM." +1 month -1 days"));
		$month1 = Date("Y-m", strtotime($todayM." -1 month" ));	
		$month1e = Date("Y-m-d", strtotime($month1." +1 month -1 days"));

		$sql = "
			SELECT 
			COUNT(IF(registdate BETWEEN '".$today." 00:00:00' AND '".$today." 23:59:59', 1, null)) AS cnt1,
			COUNT(IF(registdate BETWEEN '".$day1." 00:00:00' AND '".$day1." 23:59:59', 1, null)) AS cnt2,
			COUNT(IF(registdate BETWEEN '".$todayM."-01 00:00:00' AND '".$todayMe." 23:59:59', 1, null)) AS cnt3,
			COUNT(IF(registdate BETWEEN '".$month1."-01 00:00:00' AND '".$month1e." 23:59:59', 1, null)) AS cnt4,
			COUNT(*) AS cnt5
			FROM weblog
		";



		$result = mysql_query($sql, $conn);
		mysql_close($conn);

		$data = mysql_fetch_assoc($result);
		return $data;




		
	}
}


?>