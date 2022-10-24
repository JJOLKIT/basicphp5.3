<?
/*


*/

include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/db/DBConnection.class.php";

class CountryLog {

	// 검색 파라미터 (초기 개발시 검색조건 세팅필요)
	var $param = array (
					"stype",
					"sval",
					"reqPageNo",
					"sdate",
					"edate",
					"scode"
				);

	var $pageRows;			// 페이지 로우수
	var $startPageNo=0;		// limit 시작페이지
	public $reqPageNo=1;	// 요청페이지
	var $conn;

	// 생성자
	function CountryLog($pageRows=0, $request='') {
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
				$whereSql .= " AND ( (ip like '%".$p['sval']."%' ) ) ";
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

		if($p['scode'] != ""){
			$whereSql .= " AND code = '".$p['scode']."'";
		}
	
		
		return $whereSql;
	}
	
	
	// 전체로우수, 페이지카운트
	function getCount($param = "") {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
		
		$whereSql = $this->getWhereSql($param);	// where절
		$sql = " SELECT COUNT(*) AS cnt FROM countrylog ".$whereSql;
		
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
			INSERT INTO countrylog (
				country, country_en, ip, code, city, device, region, loc, registdate";
		$sql .= "
			) VALUES (
				'$req[country]', '$req[country_en]', '$req[ip]', '$req[code]', '$req[city]', '$req[device]', '$req[region]', '$req[loc]', NOW()
			)";

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
			FROM countrylog
			".$whereSql."
			ORDER BY registdate DESC LIMIT ".$this->startPageNo.", ".$this->pageRows." ";
		
		$result = mysql_query($sql, $conn);
		mysql_close($conn);
		
		return $result;
	}




	
	function getGroupList($param){
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
		$whereSql = $this->getWhereSql($param);
		$sql = "
			SELECT *, count(*) AS cnt FROM countrylog ".$whereSql." group by code order by cnt DESC LIMIT 8
		";
		$result = mysql_query($sql, $conn);
		mysql_close($conn);

		return $result;
	}


	function getKRList($param){
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
		if($param['scode'] == ""){
			$param['scode'] = "KR";	
		}
		$whereSql = $this->getWhereSql($param);

		

		$sql = "SELECT *, COUNT(*) AS cnt FROM countrylog ".$whereSql." group by region ORDER BY cnt DESC LIMIT 8";

		$result = mysql_query($sql, $conn);

		mysql_close($conn);

		return $result;
	}

	function getGroupList2($req=''){
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();

		$whereSql = $this->getWhereSql($req);

		$sql = "SELECT loc, country, city FROM countrylog ".$whereSql." AND loc != '' AND loc is not null GROUP BY loc ";
		$result = mysql_query($sql, $conn);


		mysql_close($conn);

		return $result;
	}

	function getCountryList($req=''){
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();


		$sql = "SELECT country, code FROM countrylog GROUP BY country ORDER BY country ASC";
		$result = mysql_query($sql, $conn);
		mysql_close($conn);

		return $result;

	}
}


?>