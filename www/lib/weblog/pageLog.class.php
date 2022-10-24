<?
/*


*/

include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/db/DBConnection.class.php";

class pageLog {

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
	function pageLog($pageRows=0, $request='') {
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

	// sql WHERE절 생성
	function getWhereSql($p) {
		$whereSql = " WHERE 1=1";
		if ($p['sval']) {
			if ($p['stype'] == 'all') {
				$whereSql .= " AND ( (os like '%".$p['sval']."%' ) or (browser like '%".$p['sval']."%' ) or (ip like '%".$p['sval']."%' ) ) ";
			} else {
				$whereSql .= " AND (".$p['stype']." LIKE '%".$p['sval']."%' )";
			}
		}
		if ($p['sdate'] != '') {
			if ($p['edate'] != '') {
				$whereSql .= " AND (registdate BETWEEN '".$p['sdate']." 00:00:00' AND '".$p['edate']." 23:59:59') ";
			}
		}

		if($p['advertise'] != ""){
			$whereSql .= " AND advertise = ".$p['advertise'];
		}
	
		
		return $whereSql;
	}
	
	
	// 전체로우수, 페이지카운트
	function getCount($param = "") {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
		
		$whereSql = $this->getWhereSql($param);	// where절
		$sql = " SELECT COUNT(*) AS cnt FROM pagelog ".$whereSql;
		
		$result = mysql_query($sql, $conn);
		mysql_close($conn);
		
		$row=mysql_fetch_array($result);
		$totalCount = $row['cnt'];
		$pageCount = getPageCount($this->pageRows, $totalCount);
		
		$data[0] = $totalCount;
		$data[1] = $pageCount;
		
		return $data;
	}

	
	// weblog insert
	function insert($req) {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
	
		$sql = "
			INSERT INTO pagelog (
				ip, page, user_agent, os, browser, advertise, registdate";
		$sql .= "
			) VALUES (
				'$req[ip]', '$req[page]', '$req[user_agent]', '$req[os]', '$req[browser]', '$req[advertise]', NOW())";

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
	

	// 목록
	function getList($param='') {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
		
		$whereSql = $this->getWhereSql($param);	// where절
		
		$sql = "
			SELECT *
			FROM pagelog
			".$whereSql."
			ORDER BY registdate DESC LIMIT ".$this->startPageNo.", ".$this->pageRows." ";
		
		$result = mysql_query($sql, $conn);
		mysql_close($conn);
		
		return $result;
	}

	function getPageCount($param){
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
		
		$whereSql = $this->getWhereSql($param);
		$sql = "
			SELECT SUBSTRING_INDEX(page, '?n_media=', 1) as ppage,  count(*) as cnt, max(registdate) as registdate
			FROM pagelog  ".$whereSql." group by ppage
			ORDER BY cnt DESC ";

		$result = mysql_query($sql, $conn);
		mysql_close($conn);
	
		$arr = array();

		while($row = mysql_fetch_assoc($result)){
			$array = array("page"=>$row[ppage], "count"=>$row[cnt], "registdate"=>getYMD($row[registdate]));
			array_push($arr, $array);
		}
		

		
	
		return $arr;
	}

	function getDeviceCount($param){
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
		
		$whereSql = $this->getWhereSql($param);
		$sql = "SELECT user_agent , COUNT(*) as cnt FROM pagelog ".$whereSql." group by user_agent order by cnt DESC";
		$result = mysql_query($sql, $conn);
		mysql_close($conn);
		
		$arr = array();
		while($row = mysql_fetch_assoc($result)){
			$array = array("user_agent"=>$row[user_agent], "count"=>$row[cnt]);
			array_push($arr, $array);
		}

		return $arr;

	}

	function getADCount($param){
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
		$param['advertise'] = '1';
		$whereSql = $this->getWhereSql($param);
		$sql = "SELECT COUNT(*) as cnt FROM pagelog ".$whereSql;

		$result = mysql_query($sql, $conn);

		mysql_close($conn);

		$data = mysql_fetch_assoc($result);

		return $data['cnt'];
	}

	function getChartCount($req=''){
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
		
		$today = Date("Y-m-d");

		if($req['stype'] == "daily"){
			if($req['sdate'] == ""){
				$subsql = "
					(SELECT COUNT(DISTINCT ip) FROM pagelog WHERE user_agent='M' AND registdate BETWEEN CONCAT(LEFT(DATE_ADD(NOW(), INTERVAL -7 DAY), 10), ' 00:00:00') AND CONCAT(LEFT(DATE_ADD(NOW(), INTERVAL -7 DAY), 10), ' 23:59:59') ) AS m7,
					(SELECT COUNT(DISTINCT ip) FROM pagelog WHERE user_agent='PC' AND registdate BETWEEN CONCAT(LEFT(DATE_ADD(NOW(), INTERVAL -7 DAY), 10), ' 00:00:00') AND CONCAT(LEFT(DATE_ADD(NOW(), INTERVAL -7 DAY), 10), ' 23:59:59') ) AS p7,

					(SELECT COUNT(DISTINCT ip) FROM pagelog WHERE user_agent='M' AND registdate BETWEEN CONCAT(LEFT(DATE_ADD(NOW(), INTERVAL -6 DAY), 10), ' 00:00:00') AND CONCAT(LEFT(DATE_ADD(NOW(), INTERVAL -6 DAY), 10), ' 23:59:59') ) AS m6,
					(SELECT COUNT(DISTINCT ip) FROM pagelog WHERE user_agent='PC' AND registdate BETWEEN CONCAT(LEFT(DATE_ADD(NOW(), INTERVAL -6 DAY), 10), ' 00:00:00') AND CONCAT(LEFT(DATE_ADD(NOW(), INTERVAL -6 DAY), 10), ' 23:59:59') ) AS p6,

					(SELECT COUNT(DISTINCT ip) FROM pagelog WHERE user_agent='M' AND registdate BETWEEN CONCAT(LEFT(DATE_ADD(NOW(), INTERVAL -5 DAY), 10), ' 00:00:00') AND CONCAT(LEFT(DATE_ADD(NOW(), INTERVAL -5 DAY), 10), ' 23:59:59') ) AS m5,
					(SELECT COUNT(DISTINCT ip) FROM pagelog WHERE user_agent='PC' AND registdate BETWEEN CONCAT(LEFT(DATE_ADD(NOW(), INTERVAL -5 DAY), 10), ' 00:00:00') AND CONCAT(LEFT(DATE_ADD(NOW(), INTERVAL -5 DAY), 10), ' 23:59:59') ) AS p5,

					(SELECT COUNT(DISTINCT ip) FROM pagelog WHERE user_agent='M' AND registdate BETWEEN CONCAT(LEFT(DATE_ADD(NOW(), INTERVAL -4 DAY), 10), ' 00:00:00') AND CONCAT(LEFT(DATE_ADD(NOW(), INTERVAL -4 DAY), 10), ' 23:59:59') ) AS m4,
					(SELECT COUNT(DISTINCT ip) FROM pagelog WHERE user_agent='PC' AND registdate BETWEEN CONCAT(LEFT(DATE_ADD(NOW(), INTERVAL -4 DAY), 10), ' 00:00:00') AND CONCAT(LEFT(DATE_ADD(NOW(), INTERVAL -4 DAY), 10), ' 23:59:59') ) AS p4,

					(SELECT COUNT(DISTINCT ip) FROM pagelog WHERE user_agent='M' AND registdate BETWEEN CONCAT(LEFT(DATE_ADD(NOW(), INTERVAL -3 DAY), 10), ' 00:00:00') AND CONCAT(LEFT(DATE_ADD(NOW(), INTERVAL -3 DAY), 10), ' 23:59:59') ) AS m3,
					(SELECT COUNT(DISTINCT ip) FROM pagelog WHERE user_agent='PC' AND registdate BETWEEN CONCAT(LEFT(DATE_ADD(NOW(), INTERVAL -3 DAY), 10), ' 00:00:00') AND CONCAT(LEFT(DATE_ADD(NOW(), INTERVAL -3 DAY), 10), ' 23:59:59') ) AS p3,

					(SELECT COUNT(DISTINCT ip) FROM pagelog WHERE user_agent='M' AND registdate BETWEEN CONCAT(LEFT(DATE_ADD(NOW(), INTERVAL -2 DAY), 10), ' 00:00:00') AND CONCAT(LEFT(DATE_ADD(NOW(), INTERVAL -2 DAY), 10), ' 23:59:59') ) AS m2,
					(SELECT COUNT(DISTINCT ip) FROM pagelog WHERE user_agent='PC' AND registdate BETWEEN CONCAT(LEFT(DATE_ADD(NOW(), INTERVAL -2 DAY), 10), ' 00:00:00') AND CONCAT(LEFT(DATE_ADD(NOW(), INTERVAL -2 DAY), 10), ' 23:59:59') ) AS p2,

					(SELECT COUNT(DISTINCT ip) FROM pagelog WHERE user_agent='M' AND registdate BETWEEN CONCAT(LEFT(DATE_ADD(NOW(), INTERVAL -1 DAY), 10), ' 00:00:00') AND CONCAT(LEFT(DATE_ADD(NOW(), INTERVAL -1 DAY), 10), ' 23:59:59') ) AS m1,
					(SELECT COUNT(DISTINCT ip) FROM pagelog WHERE user_agent='PC' AND registdate BETWEEN CONCAT(LEFT(DATE_ADD(NOW(), INTERVAL -1 DAY), 10), ' 00:00:00') AND CONCAT(LEFT(DATE_ADD(NOW(), INTERVAL -1 DAY), 10), ' 23:59:59') ) AS p1,

					(SELECT COUNT(DISTINCT ip) FROM pagelog WHERE user_agent='M' AND registdate BETWEEN CONCAT(LEFT(DATE_ADD(NOW(), INTERVAL -0 DAY), 10), ' 00:00:00') AND CONCAT(LEFT(DATE_ADD(NOW(), INTERVAL 0 DAY), 10), ' 23:59:59') ) AS m0,
					(SELECT COUNT(DISTINCT ip) FROM pagelog WHERE user_agent='PC' AND registdate BETWEEN CONCAT(LEFT(DATE_ADD(NOW(), INTERVAL -0 DAY), 10), ' 00:00:00') AND CONCAT(LEFT(DATE_ADD(NOW(), INTERVAL 0 DAY), 10), ' 23:59:59') ) AS p0
				";
			}else{
				$subsql = "
					(SELECT COUNT(DISTINCT ip) FROM pagelog WHERE user_agent='M' AND registdate BETWEEN CONCAT(LEFT(DATE_ADD('".$req[sdate]."', INTERVAL -4 DAY), 10), ' 00:00:00') AND CONCAT(LEFT(DATE_ADD('".$req[sdate]."', INTERVAL -4 DAY), 10), ' 23:59:59') ) AS m7,
					(SELECT COUNT(DISTINCT ip) FROM pagelog WHERE user_agent='PC' AND registdate BETWEEN CONCAT(LEFT(DATE_ADD('".$req[sdate]."', INTERVAL -4 DAY), 10), ' 00:00:00') AND CONCAT(LEFT(DATE_ADD('".$req[sdate]."', INTERVAL -4 DAY), 10), ' 23:59:59') ) AS p7,

					(SELECT COUNT(DISTINCT ip) FROM pagelog WHERE user_agent='M' AND registdate BETWEEN CONCAT(LEFT(DATE_ADD('".$req[sdate]."', INTERVAL -3 DAY), 10), ' 00:00:00') AND CONCAT(LEFT(DATE_ADD('".$req[sdate]."', INTERVAL -3 DAY), 10), ' 23:59:59') ) AS m6,
					(SELECT COUNT(DISTINCT ip) FROM pagelog WHERE user_agent='PC' AND registdate BETWEEN CONCAT(LEFT(DATE_ADD('".$req[sdate]."', INTERVAL -3 DAY), 10), ' 00:00:00') AND CONCAT(LEFT(DATE_ADD('".$req[sdate]."', INTERVAL -3 DAY), 10), ' 23:59:59') ) AS p6,

					(SELECT COUNT(DISTINCT ip) FROM pagelog WHERE user_agent='M' AND registdate BETWEEN CONCAT(LEFT(DATE_ADD('".$req[sdate]."', INTERVAL -2 DAY), 10), ' 00:00:00') AND CONCAT(LEFT(DATE_ADD('".$req[sdate]."', INTERVAL -2 DAY), 10), ' 23:59:59') ) AS m5,
					(SELECT COUNT(DISTINCT ip) FROM pagelog WHERE user_agent='PC' AND registdate BETWEEN CONCAT(LEFT(DATE_ADD('".$req[sdate]."', INTERVAL -2 DAY), 10), ' 00:00:00') AND CONCAT(LEFT(DATE_ADD('".$req[sdate]."', INTERVAL -2 DAY), 10), ' 23:59:59') ) AS p5,

					(SELECT COUNT(DISTINCT ip) FROM pagelog WHERE user_agent='M' AND registdate BETWEEN CONCAT(LEFT(DATE_ADD('".$req[sdate]."', INTERVAL -1 DAY), 10), ' 00:00:00') AND CONCAT(LEFT(DATE_ADD('".$req[sdate]."', INTERVAL -1 DAY), 10), ' 23:59:59') ) AS m4,
					(SELECT COUNT(DISTINCT ip) FROM pagelog WHERE user_agent='PC' AND registdate BETWEEN CONCAT(LEFT(DATE_ADD('".$req[sdate]."', INTERVAL -1 DAY), 10), ' 00:00:00') AND CONCAT(LEFT(DATE_ADD('".$req[sdate]."', INTERVAL -1 DAY), 10), ' 23:59:59') ) AS p4,

					(SELECT COUNT(DISTINCT ip) FROM pagelog WHERE user_agent='M' AND registdate BETWEEN CONCAT(LEFT(DATE_ADD('".$req[sdate]."', INTERVAL +0 DAY), 10), ' 00:00:00') AND CONCAT(LEFT(DATE_ADD('".$req[sdate]."', INTERVAL -0 DAY), 10), ' 23:59:59') ) AS m3,
					(SELECT COUNT(DISTINCT ip) FROM pagelog WHERE user_agent='PC' AND registdate BETWEEN CONCAT(LEFT(DATE_ADD('".$req[sdate]."', INTERVAL +0 DAY), 10), ' 00:00:00') AND CONCAT(LEFT(DATE_ADD('".$req[sdate]."', INTERVAL -0 DAY), 10), ' 23:59:59') ) AS p3,

					(SELECT COUNT(DISTINCT ip) FROM pagelog WHERE user_agent='M' AND registdate BETWEEN CONCAT(LEFT(DATE_ADD('".$req[sdate]."', INTERVAL +1 DAY), 10), ' 00:00:00') AND CONCAT(LEFT(DATE_ADD('".$req[sdate]."', INTERVAL +1 DAY), 10), ' 23:59:59') ) AS m2,
					(SELECT COUNT(DISTINCT ip) FROM pagelog WHERE user_agent='PC' AND registdate BETWEEN CONCAT(LEFT(DATE_ADD('".$req[sdate]."', INTERVAL +1 DAY), 10), ' 00:00:00') AND CONCAT(LEFT(DATE_ADD('".$req[sdate]."', INTERVAL +1 DAY), 10), ' 23:59:59') ) AS p2,

					(SELECT COUNT(DISTINCT ip) FROM pagelog WHERE user_agent='M' AND registdate BETWEEN CONCAT(LEFT(DATE_ADD('".$req[sdate]."', INTERVAL +2 DAY), 10), ' 00:00:00') AND CONCAT(LEFT(DATE_ADD('".$req[sdate]."', INTERVAL +2 DAY), 10), ' 23:59:59') ) AS m1,
					(SELECT COUNT(DISTINCT ip) FROM pagelog WHERE user_agent='PC' AND registdate BETWEEN CONCAT(LEFT(DATE_ADD('".$req[sdate]."', INTERVAL +2 DAY), 10), ' 00:00:00') AND CONCAT(LEFT(DATE_ADD('".$req[sdate]."', INTERVAL +2 DAY), 10), ' 23:59:59') ) AS p1,

					(SELECT COUNT(DISTINCT ip) FROM pagelog WHERE user_agent='M' AND registdate BETWEEN CONCAT(LEFT(DATE_ADD('".$req[sdate]."', INTERVAL +3 DAY), 10), ' 00:00:00') AND CONCAT(LEFT(DATE_ADD('".$req[sdate]."', INTERVAL +3 DAY), 10), ' 23:59:59') ) AS m0,
					(SELECT COUNT(DISTINCT ip) FROM pagelog WHERE user_agent='PC' AND registdate BETWEEN CONCAT(LEFT(DATE_ADD('".$req[sdate]."', INTERVAL +3 DAY), 10), ' 00:00:00') AND CONCAT(LEFT(DATE_ADD('".$req[sdate]."', INTERVAL +3 DAY), 10), ' 23:59:59') ) AS p0
				";
			}
		}else if($req['stype'] == "monthly"){
			if($req['sdate'] == ""){
				$today = Date('Y-m');
				$today7s = Date('Y-m-d', strtotime($today." -7 month"))." 00:00:00";
				$today7e = Date('Y-m-d', strtotime($today." -6 month -1 day"))." 23:59:59";

				$today6s = Date('Y-m-d', strtotime($today." -6 month"))." 00:00:00";
				$today6e = Date('Y-m-d', strtotime($today." -5 month -1 day"))." 23:59:59";

				$today5s = Date('Y-m-d', strtotime($today." -5 month"))." 00:00:00";
				$today5e = Date('Y-m-d', strtotime($today." -4 month -1 day"))." 23:59:59";
				
				$today4s = Date('Y-m-d', strtotime($today." -4 month"))." 00:00:00";
				$today4e = Date('Y-m-d', strtotime($today." -3 month -1 day"))." 23:59:59";
				
				$today3s = Date('Y-m-d', strtotime($today." -3 month"))." 00:00:00";
				$today3e = Date('Y-m-d', strtotime($today." -2 month -1 day"))." 23:59:59";
				
				$today2s = Date('Y-m-d', strtotime($today." -2 month"))." 00:00:00";
				$today2e = Date('Y-m-d', strtotime($today." -1 month -1 day"))." 23:59:59";
			
				$today1s = Date('Y-m-d', strtotime($today." -1 month"))." 00:00:00";
				$today1e = Date('Y-m-d', strtotime($today." -0 month -1 day"))." 23:59:59";

				$today0s = Date('Y-m-d', strtotime($today." -0 month"))." 00:00:00";
				$today0e = Date('Y-m-d H:i:s');


				$subsql = "
					(SELECT COUNT(DISTINCT ip) FROM pagelog WHERE user_agent='M' AND registdate BETWEEN '".$today7s."' AND '".$today7e."' ) AS m7,
					(SELECT COUNT(DISTINCT ip) FROM pagelog WHERE user_agent='PC' AND registdate BETWEEN '".$today7s."' AND '".$today7e."' ) AS p7,

					(SELECT COUNT(DISTINCT ip) FROM pagelog WHERE user_agent='M' AND registdate BETWEEN '".$today6s."' AND '".$today6e."' ) AS m6,
					(SELECT COUNT(DISTINCT ip) FROM pagelog WHERE user_agent='PC' AND registdate BETWEEN '".$today6s."' AND '".$today6e."' ) AS p6,

					(SELECT COUNT(DISTINCT ip) FROM pagelog WHERE user_agent='M' AND registdate BETWEEN '".$today5s."' AND '".$today5e."' ) AS m5,
					(SELECT COUNT(DISTINCT ip) FROM pagelog WHERE user_agent='PC' AND registdate BETWEEN '".$today5s."' AND '".$today5e."' ) AS p5,

					(SELECT COUNT(DISTINCT ip) FROM pagelog WHERE user_agent='M' AND registdate BETWEEN '".$today4s."' AND '".$today4e."' ) AS m4,
					(SELECT COUNT(DISTINCT ip) FROM pagelog WHERE user_agent='PC' AND registdate BETWEEN '".$today4s."' AND '".$today4e."' ) AS p4,

					(SELECT COUNT(DISTINCT ip) FROM pagelog WHERE user_agent='M' AND registdate BETWEEN '".$today3s."' AND '".$today3e."' ) AS m3,
					(SELECT COUNT(DISTINCT ip) FROM pagelog WHERE user_agent='PC' AND registdate BETWEEN '".$today3s."' AND '".$today3e."' ) AS p3,

					(SELECT COUNT(DISTINCT ip) FROM pagelog WHERE user_agent='M' AND registdate BETWEEN '".$today2s."' AND '".$today2e."' ) AS m2,
					(SELECT COUNT(DISTINCT ip) FROM pagelog WHERE user_agent='PC' AND registdate BETWEEN '".$today2s."' AND '".$today2e."' ) AS p2,

					(SELECT COUNT(DISTINCT ip) FROM pagelog WHERE user_agent='M' AND registdate BETWEEN '".$today1s."' AND '".$today1e."' ) AS m1,
					(SELECT COUNT(DISTINCT ip) FROM pagelog WHERE user_agent='PC' AND registdate BETWEEN '".$today1s."' AND '".$today1e."' ) AS p1,

					(SELECT COUNT(DISTINCT ip) FROM pagelog WHERE user_agent='M' AND registdate BETWEEN '".$today0s."' AND '".$today0e."' ) AS m0,
					(SELECT COUNT(DISTINCT ip) FROM pagelog WHERE user_agent='PC' AND registdate BETWEEN '".$today0s."' AND '".$today0e."' ) AS p0
				";
			}else{

				$today = Date('Y-m', strtotime($_REQUEST['sdate']));
				$today7s = Date('Y-m-d', strtotime($today." -7 month"))." 00:00:00";
				$today7e = Date('Y-m-d', strtotime($today." -6 month -1 day"))." 23:59:59";

				$today6s = Date('Y-m-d', strtotime($today." -6 month"))." 00:00:00";
				$today6e = Date('Y-m-d', strtotime($today." -5 month -1 day"))." 23:59:59";

				$today5s = Date('Y-m-d', strtotime($today." -5 month"))." 00:00:00";
				$today5e = Date('Y-m-d', strtotime($today." -4 month -1 day"))." 23:59:59";
				
				$today4s = Date('Y-m-d', strtotime($today." -4 month"))." 00:00:00";
				$today4e = Date('Y-m-d', strtotime($today." -3 month -1 day"))." 23:59:59";
				
				$today3s = Date('Y-m-d', strtotime($today." -3 month"))." 00:00:00";
				$today3e = Date('Y-m-d', strtotime($today." -2 month -1 day"))." 23:59:59";
				
				$today2s = Date('Y-m-d', strtotime($today." -2 month"))." 00:00:00";
				$today2e = Date('Y-m-d', strtotime($today." -1 month -1 day"))." 23:59:59";
			
				$today1s = Date('Y-m-d', strtotime($today." -1 month"))." 00:00:00";
				$today1e = Date('Y-m-d', strtotime($today." -0 month -1 day"))." 23:59:59";

				$today0s = Date('Y-m-d', strtotime($today." -0 month"))." 00:00:00";
				$today0e = Date('Y-m-d H:i:s');


				$subsql = "
					(SELECT COUNT(DISTINCT ip) FROM pagelog WHERE user_agent='M' AND registdate BETWEEN '".$today7s."' AND '".$today7e."' ) AS m7,
					(SELECT COUNT(DISTINCT ip) FROM pagelog WHERE user_agent='PC' AND registdate BETWEEN '".$today7s."' AND '".$today7e."' ) AS p7,

					(SELECT COUNT(DISTINCT ip) FROM pagelog WHERE user_agent='M' AND registdate BETWEEN '".$today6s."' AND '".$today6e."' ) AS m6,
					(SELECT COUNT(DISTINCT ip) FROM pagelog WHERE user_agent='PC' AND registdate BETWEEN '".$today6s."' AND '".$today6e."' ) AS p6,

					(SELECT COUNT(DISTINCT ip) FROM pagelog WHERE user_agent='M' AND registdate BETWEEN '".$today5s."' AND '".$today5e."' ) AS m5,
					(SELECT COUNT(DISTINCT ip) FROM pagelog WHERE user_agent='PC' AND registdate BETWEEN '".$today5s."' AND '".$today5e."' ) AS p5,

					(SELECT COUNT(DISTINCT ip) FROM pagelog WHERE user_agent='M' AND registdate BETWEEN '".$today4s."' AND '".$today4e."' ) AS m4,
					(SELECT COUNT(DISTINCT ip) FROM pagelog WHERE user_agent='PC' AND registdate BETWEEN '".$today4s."' AND '".$today4e."' ) AS p4,

					(SELECT COUNT(DISTINCT ip) FROM pagelog WHERE user_agent='M' AND registdate BETWEEN '".$today3s."' AND '".$today3e."' ) AS m3,
					(SELECT COUNT(DISTINCT ip) FROM pagelog WHERE user_agent='PC' AND registdate BETWEEN '".$today3s."' AND '".$today3e."' ) AS p3,

					(SELECT COUNT(DISTINCT ip) FROM pagelog WHERE user_agent='M' AND registdate BETWEEN '".$today2s."' AND '".$today2e."' ) AS m2,
					(SELECT COUNT(DISTINCT ip) FROM pagelog WHERE user_agent='PC' AND registdate BETWEEN '".$today2s."' AND '".$today2e."' ) AS p2,

					(SELECT COUNT(DISTINCT ip) FROM pagelog WHERE user_agent='M' AND registdate BETWEEN '".$today1s."' AND '".$today1e."' ) AS m1,
					(SELECT COUNT(DISTINCT ip) FROM pagelog WHERE user_agent='PC' AND registdate BETWEEN '".$today1s."' AND '".$today1e."' ) AS p1,

					(SELECT COUNT(DISTINCT ip) FROM pagelog WHERE user_agent='M' AND registdate BETWEEN '".$today0s."' AND '".$today0e."' ) AS m0,
					(SELECT COUNT(DISTINCT ip) FROM pagelog WHERE user_agent='PC' AND registdate BETWEEN '".$today0s."' AND '".$today0e."' ) AS p0
				";
			}
		}else if($req['stype'] == "yearly"){
			if($req['sdate'] == ""){
				$today = Date('Y')."-01-01";
				$today7s = Date('Y-m-d', strtotime($today." -7 years"))." 00:00:00";
				$today7e = Date('Y-m-d', strtotime($today." -6 years -1 day"))." 23:59:59";

				$today6s = Date('Y-m-d', strtotime($today." -6 years"))." 00:00:00";
				$today6e = Date('Y-m-d', strtotime($today." -5 years -1 day"))." 23:59:59";

				$today5s = Date('Y-m-d', strtotime($today." -5 years"))." 00:00:00";
				$today5e = Date('Y-m-d', strtotime($today." -4 years -1 day"))." 23:59:59";
				
				$today4s = Date('Y-m-d', strtotime($today." -4 years"))." 00:00:00";
				$today4e = Date('Y-m-d', strtotime($today." -3 years -1 day"))." 23:59:59";
				
				$today3s = Date('Y-m-d', strtotime($today." -3 years"))." 00:00:00";
				$today3e = Date('Y-m-d', strtotime($today." -2 years -1 day"))." 23:59:59";
				
				$today2s = Date('Y-m-d', strtotime($today." -2 years"))." 00:00:00";
				$today2e = Date('Y-m-d', strtotime($today." -1 years -1 day"))." 23:59:59";
			
				$today1s = Date('Y-m-d', strtotime($today." -1 years"))." 00:00:00";
				$today1e = Date('Y-m-d', strtotime($today." -0 years -1 day"))." 23:59:59";

				$today0s = Date('Y-m-d', strtotime($today." -0 years"))." 00:00:00";
				$today0e = Date('Y-m-d H:i:s');


				$subsql = "
					(SELECT COUNT(DISTINCT ip) FROM pagelog WHERE user_agent='M' AND registdate BETWEEN '".$today7s."' AND '".$today7e."' ) AS m7,
					(SELECT COUNT(DISTINCT ip) FROM pagelog WHERE user_agent='PC' AND registdate BETWEEN '".$today7s."' AND '".$today7e."' ) AS p7,

					(SELECT COUNT(DISTINCT ip) FROM pagelog WHERE user_agent='M' AND registdate BETWEEN '".$today6s."' AND '".$today6e."' ) AS m6,
					(SELECT COUNT(DISTINCT ip) FROM pagelog WHERE user_agent='PC' AND registdate BETWEEN '".$today6s."' AND '".$today6e."' ) AS p6,

					(SELECT COUNT(DISTINCT ip) FROM pagelog WHERE user_agent='M' AND registdate BETWEEN '".$today5s."' AND '".$today5e."' ) AS m5,
					(SELECT COUNT(DISTINCT ip) FROM pagelog WHERE user_agent='PC' AND registdate BETWEEN '".$today5s."' AND '".$today5e."' ) AS p5,

					(SELECT COUNT(DISTINCT ip) FROM pagelog WHERE user_agent='M' AND registdate BETWEEN '".$today4s."' AND '".$today4e."' ) AS m4,
					(SELECT COUNT(DISTINCT ip) FROM pagelog WHERE user_agent='PC' AND registdate BETWEEN '".$today4s."' AND '".$today4e."' ) AS p4,

					(SELECT COUNT(DISTINCT ip) FROM pagelog WHERE user_agent='M' AND registdate BETWEEN '".$today3s."' AND '".$today3e."' ) AS m3,
					(SELECT COUNT(DISTINCT ip) FROM pagelog WHERE user_agent='PC' AND registdate BETWEEN '".$today3s."' AND '".$today3e."' ) AS p3,

					(SELECT COUNT(DISTINCT ip) FROM pagelog WHERE user_agent='M' AND registdate BETWEEN '".$today2s."' AND '".$today2e."' ) AS m2,
					(SELECT COUNT(DISTINCT ip) FROM pagelog WHERE user_agent='PC' AND registdate BETWEEN '".$today2s."' AND '".$today2e."' ) AS p2,

					(SELECT COUNT(DISTINCT ip) FROM pagelog WHERE user_agent='M' AND registdate BETWEEN '".$today1s."' AND '".$today1e."' ) AS m1,
					(SELECT COUNT(DISTINCT ip) FROM pagelog WHERE user_agent='PC' AND registdate BETWEEN '".$today1s."' AND '".$today1e."' ) AS p1,

					(SELECT COUNT(DISTINCT ip) FROM pagelog WHERE user_agent='M' AND registdate BETWEEN '".$today0s."' AND '".$today0e."' ) AS m0,
					(SELECT COUNT(DISTINCT ip) FROM pagelog WHERE user_agent='PC' AND registdate BETWEEN '".$today0s."' AND '".$today0e."' ) AS p0
				";
			}else{

				$today = Date('Y'."-01-01", strtotime($_REQUEST['sdate']));
				$today7s = Date('Y-m-d', strtotime($today." -7 years"))." 00:00:00";
				$today7e = Date('Y-m-d', strtotime($today." -6 years -1 day"))." 23:59:59";

				$today6s = Date('Y-m-d', strtotime($today." -6 years"))." 00:00:00";
				$today6e = Date('Y-m-d', strtotime($today." -5 years -1 day"))." 23:59:59";

				$today5s = Date('Y-m-d', strtotime($today." -5 years"))." 00:00:00";
				$today5e = Date('Y-m-d', strtotime($today." -4 years -1 day"))." 23:59:59";
				
				$today4s = Date('Y-m-d', strtotime($today." -4 years"))." 00:00:00";
				$today4e = Date('Y-m-d', strtotime($today." -3 years -1 day"))." 23:59:59";
				
				$today3s = Date('Y-m-d', strtotime($today." -3 years"))." 00:00:00";
				$today3e = Date('Y-m-d', strtotime($today." -2 years -1 day"))." 23:59:59";
				
				$today2s = Date('Y-m-d', strtotime($today." -2 years"))." 00:00:00";
				$today2e = Date('Y-m-d', strtotime($today." -1 years -1 day"))." 23:59:59";
			
				$today1s = Date('Y-m-d', strtotime($today." -1 years"))." 00:00:00";
				$today1e = Date('Y-m-d', strtotime($today." -0 years -1 day"))." 23:59:59";

				$today0s = Date('Y-m-d', strtotime($today." -0 years"))." 00:00:00";
				$today0e = Date('Y-m-d H:i:s');


				$subsql = "
					(SELECT COUNT(DISTINCT ip) FROM pagelog WHERE user_agent='M' AND registdate BETWEEN '".$today7s."' AND '".$today7e."' ) AS m7,
					(SELECT COUNT(DISTINCT ip) FROM pagelog WHERE user_agent='PC' AND registdate BETWEEN '".$today7s."' AND '".$today7e."' ) AS p7,

					(SELECT COUNT(DISTINCT ip) FROM pagelog WHERE user_agent='M' AND registdate BETWEEN '".$today6s."' AND '".$today6e."' ) AS m6,
					(SELECT COUNT(DISTINCT ip) FROM pagelog WHERE user_agent='PC' AND registdate BETWEEN '".$today6s."' AND '".$today6e."' ) AS p6,

					(SELECT COUNT(DISTINCT ip) FROM pagelog WHERE user_agent='M' AND registdate BETWEEN '".$today5s."' AND '".$today5e."' ) AS m5,
					(SELECT COUNT(DISTINCT ip) FROM pagelog WHERE user_agent='PC' AND registdate BETWEEN '".$today5s."' AND '".$today5e."' ) AS p5,

					(SELECT COUNT(DISTINCT ip) FROM pagelog WHERE user_agent='M' AND registdate BETWEEN '".$today4s."' AND '".$today4e."' ) AS m4,
					(SELECT COUNT(DISTINCT ip) FROM pagelog WHERE user_agent='PC' AND registdate BETWEEN '".$today4s."' AND '".$today4e."' ) AS p4,

					(SELECT COUNT(DISTINCT ip) FROM pagelog WHERE user_agent='M' AND registdate BETWEEN '".$today3s."' AND '".$today3e."' ) AS m3,
					(SELECT COUNT(DISTINCT ip) FROM pagelog WHERE user_agent='PC' AND registdate BETWEEN '".$today3s."' AND '".$today3e."' ) AS p3,

					(SELECT COUNT(DISTINCT ip) FROM pagelog WHERE user_agent='M' AND registdate BETWEEN '".$today2s."' AND '".$today2e."' ) AS m2,
					(SELECT COUNT(DISTINCT ip) FROM pagelog WHERE user_agent='PC' AND registdate BETWEEN '".$today2s."' AND '".$today2e."' ) AS p2,

					(SELECT COUNT(DISTINCT ip) FROM pagelog WHERE user_agent='M' AND registdate BETWEEN '".$today1s."' AND '".$today1e."' ) AS m1,
					(SELECT COUNT(DISTINCT ip) FROM pagelog WHERE user_agent='PC' AND registdate BETWEEN '".$today1s."' AND '".$today1e."' ) AS p1,

					(SELECT COUNT(DISTINCT ip) FROM pagelog WHERE user_agent='M' AND registdate BETWEEN '".$today0s."' AND '".$today0e."' ) AS m0,
					(SELECT COUNT(DISTINCT ip) FROM pagelog WHERE user_agent='PC' AND registdate BETWEEN '".$today0s."' AND '".$today0e."' ) AS p0
				";
			}
		}
		$sql = "
			SELECT
		".$subsql;
		
		outlog($sql);
		$result = mysql_query($sql, $conn);

		mysql_close($conn);

		$data = mysql_fetch_assoc($result);

		return $data;
	}



	function getChartBrowserCount($req=''){
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
		
		if($req['sdate'] != ""){
			
			$whereSql .= " AND registdate BETWEEN '".$req[sdate]." 00:00:00' AND '".$req[sdate]." 23:59:59'";
		}

		$sql = "SELECT 
			(SELECT COUNT(DISTINCT ip) FROM pagelog WHERE browser = 'Google Chrome' ".$whereSql.") AS chrome,
			(SELECT COUNT(DISTINCT ip) FROM pagelog WHERE browser = 'Explorer Edge' ".$whereSql.") AS edge,
			(SELECT COUNT(DISTINCT ip) FROM pagelog WHERE browser = 'Internet Explorer' ".$whereSql.") AS ie,
			(SELECT COUNT(DISTINCT ip) FROM pagelog WHERE browser = 'Mozilla Firefox' ".$whereSql.") AS firefox,
			(SELECT COUNT(DISTINCT ip) FROM pagelog WHERE browser = 'Apple Safari' ".$whereSql.") AS safari,
			(SELECT COUNT(DISTINCT ip) FROM pagelog WHERE browser = 'Opera' ".$whereSql.") AS opera,
			(SELECT COUNT(DISTINCT ip) FROM pagelog WHERE browser = 'Netscape' ".$whereSql.") AS netscape,
			(SELECT COUNT(DISTINCT ip) FROM pagelog WHERE browser = 'Naver Whale' ".$whereSql.") AS whale,
			(SELECT COUNT(DISTINCT ip) FROM pagelog WHERE browser = 'Other' ".$whereSql.") AS other,
			(SELECT COUNT(DISTINCT ip) FROM pagelog WHERE os = 'windows') AS windows,
			(SELECT COUNT(DISTINCT ip) FROM pagelog WHERE os = 'linux') AS linux,
			(SELECT COUNT(DISTINCT ip) FROM pagelog WHERE os = 'mac') AS mac,
			(SELECT COUNT(DISTINCT ip) FROM pagelog WHERE os = 'Other') AS os_other

			";

		$result = mysql_query($sql, $conn);

		mysql_close($conn);
		
		$data = mysql_fetch_assoc($result);

		return $data;
	}

}


?>