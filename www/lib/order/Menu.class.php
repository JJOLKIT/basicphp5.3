<?
/*


*/

include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/db/DBConnection.class.php";

class Menu {

	// 검색 파라미터 (초기 개발시 검색조건 세팅필요)
	var $param = array (
					"pageRows",
					"stype",
					"sval",
					"sdateType",
					"sstartdate",
					"senddate",
					"smonth"
				);

	var $tableName;			// 테이블명
	var $pageRows;			// 페이지 로우수
	var $startPageNo=0;		// limit 시작페이지
	public $reqPageNo=1;	// 요청페이지
	var $conn;

	// 생성자
	function Menu($pageRows=0, $tableName='', $request='') {
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
			if (chkisset($request[$this->param[$i]] )) {
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
	
	function getTodayList($month='') {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
	
		$sql = "
			SELECT *,
				(SELECT COUNT(*) FROM orders_detail WHERE menu_schedule_fk=".$this->tableName.".no AND od_pay_state = 0) AS order_cnt
			FROM ".$this->tableName."
			WHERE menuday LIKE '".$month."%'
			ORDER BY registdate ASC ";

		$result = mysql_query($sql, $conn);
		mysql_close($conn);
	
		return $result;
	}
	
	function getTodayData($today='') {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
	
		$sql = "
			SELECT *,
				(SELECT COUNT(*) FROM orders_detail WHERE menu_schedule_fk=".$this->tableName.".no AND od_pay_state = 0) AS order_cnt
			FROM ".$this->tableName."
			WHERE menuday = '".$today."' ";
	
		$result = mysql_query($sql, $conn);
		mysql_close($conn);
		$data = mysql_fetch_assoc($result);
	
		return $data;
	}

	// 관리자 등록
	function insert($req="") {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();

		//$gno = $this->getMaxGno();
		$sql = "
			INSERT INTO ".$this->tableName." (
				type, menuday, price, maxamount, 
				name1, hot1, name2, hot2, name3, hot3, name4, hot4, 
				name5, hot5, name6, hot6, name7, hot7, 
				registdate, holidayname
			) VALUES (
			".chkIsset($req[type]).",
			'$req[menuday]',
			".chkIsset($req[price]).",
			".chkIsset($req[maxamount]).",
			'$req[name1]', ".chkIsset($req[hot1]).", '$req[name2]', ".chkIsset($req[hot2]).", '$req[name3]', ".chkIsset($req[hot3]).", '$req[name4]', ".chkIsset($req[hot4]).", 
			'$req[name5]', ".chkIsset($req[hot5]).", '$req[name6]', ".chkIsset($req[hot6]).", '$req[name7]', ".chkIsset($req[hot7]).",
			NOW(), '$req[holidayname]' ) ";
		
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

		$sql = "
			UPDATE ".$this->tableName." SET
				type=".chkIsset($req[type]).", menuday='$req[menuday]', price=".chkIsset($req[price]).", maxamount=".chkIsset($req[maxamount]).", 
				name1='$req[name1]', hot1=".chkIsset($req[hot1]).", name2='$req[name2]', hot2=".chkIsset($req[hot2]).", name3='$req[name3]', hot3=".chkIsset($req[hot3]).", 
				name4='$req[name4]', hot4=".chkIsset($req[hot4]).", name5='$req[name5]', hot5=".chkIsset($req[hot5]).", name6='$req[name6]', hot6=".chkIsset($req[hot6]).", name7='$req[name7]', hot7=".chkIsset($req[hot7]).",
				holidayname='$req[holidayname]'
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
				filename, moviename, imagename
			FROM ".$this->tableName."
			WHERE no = ".$no;
		
		$result = mysql_query($sql, $conn);
		mysql_close($conn);

		return $result;
	}

	// 월별목록 조회
	function getCalendar($month) {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
		
		$sql = "
			SELECT today, name, CONVERT(holiday USING euckr) AS holiday, IFNULL(cnt, 0) AS cnt, IFNULL(order_cnt, 0) AS order_cnt							
			FROM																											
				(SELECT	today, name, IFNULL(holidayName, 0) AS holiday													
				FROM																										
					(SELECT today, name	FROM calendar WHERE today LIKE '".$month."%') AS c												
					LEFT OUTER JOIN																							
					(SELECT CONVERT(CASE 	WHEN holidayType ='01' THEN '설날(신정)'												
							WHEN holidayType ='02' THEN '설날(구정)'														
							WHEN holidayType ='03' THEN '삼일절'															
							WHEN holidayType ='04' THEN '어린이날'															
							WHEN holidayType ='05' THEN '석가탄신일'														
							WHEN holidayType ='06' THEN '현충일'															
							WHEN holidayType ='07' THEN '광복절'															
							WHEN holidayType ='08' THEN '추석'																
							WHEN holidayType ='09' THEN '개천절'															
							ELSE '성탄절' END USING utf8) AS holidayName, holiday														
					FROM legalholiday																						
					WHERE holiday LIKE '".$month."%') AS d																				
					ON c.today = d.holiday) AS a																			
				LEFT OUTER JOIN																								
				(SELECT menuday, COUNT(*) AS cnt, (SELECT COUNT(*) FROM orders_detail WHERE menu_schedule_fk=".$this->tableName.".no) AS order_cnt FROM ".$this->tableName." WHERE menuday LIKE '".$month."%' GROUP BY menuday) AS b	
				ON a.today = b.menuday																						
			ORDER BY today ";
outlog($sql);				
		$result = mysql_query($sql, $conn);
		mysql_close($conn);

		return $result;
	}
	
	function getMenuName($row) {
		$r = "";
		$redTxt = "";
		if ($row['name1']) {
			if ($row['hot1'] == 1) $redTxt='red'; else $redTxt='';
			$r .= " <font color='".$redTxt."'>";
			$r .= $row['name1'];
			$r .="</font>";
		}
		if ($row['name2']) {
			if ($r) $r .= "<br/>";
			if ($row['hot2'] == 1) $redTxt='red'; else $redTxt='';
			$r .= " <font color='".$redTxt."'>";
			$r .= $row['name2'];
			$r .="</font>";
		}
		if ($row['name3']) {
			if ($r) $r .= "<br/>";
			if ($row['hot3'] == 1) $redTxt='red'; else $redTxt='';
			$r .= " <font color='".$redTxt."'>";
			$r .= $row['name3'];
			$r .="</font>";
		}
		if ($row['name4']) {
			if ($r) $r .= "<br/>";
			if ($row['hot4'] == 1) $redTxt='red'; else $redTxt='';
			$r .= " <font color='".$redTxt."'>";
			$r .= $row['name4'];
			$r .="</font>";
		}
		if ($row['name5']) {
			if ($r) $r .= "<br/>";
			if ($row['hot5'] == 1) $redTxt='red'; else $redTxt='';
			$r .= " <font color='".$redTxt."'>";
			$r .= $row['name5'];
			$r .="</font>";
		}
		if ($row['name6']) {
			if ($r) $r .= "<br/>";
			if ($row['hot6'] == 1) $redTxt='red'; else $redTxt='';
			$r .= " <font color='".$redTxt."'>";
			$r .= $row['name6'];
			$r .="</font>";
		}
		if ($row['name7']) {
			if ($r) $r .= "<br/>";
			if ($row['hot7'] == 1) $redTxt='red'; else $redTxt='';
			$r .= " <font color='".$redTxt."'>";
			$r .= $row['name7'];
			$r .="</font>";
		}
		if ($r) $r .= "<br/><font color='blue'>수량:".$row['maxamount']."개</font>";
		return $r;
	}
	
	function getMenuNameUser($row) {
		$r = "";
		$redTxt = "";
		if ($row['name1']) {
			if ($row['hot1'] == 1) $redTxt='red'; else $redTxt='';
			$r .= " <font color='".$redTxt."'>";
			$r .= $row['name1'];
			$r .="</font>";
		}
		if ($row['name2']) {
			if ($r) $r .= "<br/>";
			if ($row['hot2'] == 1) $redTxt='red'; else $redTxt='';
			$r .= " <font color='".$redTxt."'>";
			$r .= $row['name2'];
			$r .="</font>";
		}
		if ($row['name3']) {
			if ($r) $r .= "<br/>";
			if ($row['hot3'] == 1) $redTxt='red'; else $redTxt='';
			$r .= " <font color='".$redTxt."'>";
			$r .= $row['name3'];
			$r .="</font>";
		}
		if ($row['name4']) {
			if ($r) $r .= "<br/>";
			if ($row['hot4'] == 1) $redTxt='red'; else $redTxt='';
			$r .= " <font color='".$redTxt."'>";
			$r .= $row['name4'];
			$r .="</font>";
		}
		if ($row['name5']) {
			if ($r) $r .= "<br/>";
			if ($row['hot5'] == 1) $redTxt='red'; else $redTxt='';
			$r .= " <font color='".$redTxt."'>";
			$r .= $row['name5'];
			$r .="</font>";
		}
		if ($row['name6']) {
			if ($r) $r .= "<br/>";
			if ($row['hot6'] == 1) $redTxt='red'; else $redTxt='';
			$r .= " <font color='".$redTxt."'>";
			$r .= $row['name6'];
			$r .="</font>";
		}
		if ($row['name7']) {
			if ($r) $r .= "<br/>";
			if ($row['hot7'] == 1) $redTxt='red'; else $redTxt='';
			$r .= " <font color='".$redTxt."'>";
			$r .= $row['name7'];
			$r .="</font>";
		}
		return $r;
	}
	
	function getMenuNameForList($row) {
		$r = "";
		$redTxt = "";
		if ($row['name1']) {
			if ($row['hot1'] == 1) $redTxt='red'; else $redTxt='';
			$r .= " <font color='".$redTxt."'>";
			$r .= $row['name1'];
			$r .="</font>";
		}
		if ($row['name2']) {
			if ($r) $r .= ", ";
			if ($row['hot2'] == 1) $redTxt='red'; else $redTxt='';
			$r .= " <font color='".$redTxt."'>";
			$r .= $row['name2'];
			$r .="</font>";
		}
		if ($row['name3']) {
			if ($r) $r .= ", ";
			if ($row['hot3'] == 1) $redTxt='red'; else $redTxt='';
			$r .= " <font color='".$redTxt."'>";
			$r .= $row['name3'];
			$r .="</font>";
		}
		if ($row['name4']) {
			if ($r) $r .= ", ";
			if ($row['hot4'] == 1) $redTxt='red'; else $redTxt='';
			$r .= " <font color='".$redTxt."'>";
			$r .= $row['name4'];
			$r .="</font>";
		}
		if ($row['name5']) {
			if ($r) $r .= ", ";
			if ($row['hot5'] == 1) $redTxt='red'; else $redTxt='';
			$r .= " <font color='".$redTxt."'>";
			$r .= $row['name5'];
			$r .="</font>";
		}
		if ($row['name6']) {
			if ($r) $r .= ", ";
			if ($row['hot6'] == 1) $redTxt='red'; else $redTxt='';
			$r .= " <font color='".$redTxt."'>";
			$r .= $row['name6'];
			$r .="</font>";
		}
		if ($row['name7']) {
			if ($r) $r .= ", ";
			if ($row['hot7'] == 1) $redTxt='red'; else $redTxt='';
			$r .= " <font color='".$redTxt."'>";
			$r .= $row['name7'];
			$r .="</font>";
		}
		return $r;
	}
	
	function getMenuNameForAdminList($row) {
		$r = "";
		$redTxt = "";
		if ($row['name1']) {
			if ($row['hot1'] == 1) $redTxt='red'; else $redTxt='';
			$r .= " <font color=".$redTxt.">";
			$r .= $row['name1'];
			$r .="</font>";
		}
		if ($row['name2']) {
			if ($r) $r .= ", ";
			if ($row['hot2'] == 1) $redTxt='red'; else $redTxt='';
			$r .= " <font color=".$redTxt.">";
			$r .= $row['name2'];
			$r .="</font>";
		}
		if ($row['name3']) {
			if ($r) $r .= ", ";
			if ($row['hot3'] == 1) $redTxt='red'; else $redTxt='';
			$r .= " <font color=".$redTxt.">";
			$r .= $row['name3'];
			$r .="</font>";
		}
		if ($row['name4']) {
			if ($r) $r .= ", ";
			if ($row['hot4'] == 1) $redTxt='red'; else $redTxt='';
			$r .= " <font color=".$redTxt.">";
			$r .= $row['name4'];
			$r .="</font>";
		}
		if ($row['name5']) {
			if ($r) $r .= ", ";
			if ($row['hot5'] == 1) $redTxt='red'; else $redTxt='';
			$r .= " <font color=".$redTxt.">";
			$r .= $row['name5'];
			$r .="</font>";
		}
		if ($row['name6']) {
			if ($r) $r .= ", ";
			if ($row['hot6'] == 1) $redTxt='red'; else $redTxt='';
			$r .= " <font color=".$redTxt.">";
			$r .= $row['name6'];
			$r .="</font>";
		}
		if ($row['name7']) {
			if ($r) $r .= ", ";
			if ($row['hot7'] == 1) $redTxt='red'; else $redTxt='';
			$r .= " <font color=".$redTxt.">";
			$r .= $row['name7'];
			$r .="</font>";
		}
		return $r;
	}
	
	function chkAmount($today='') {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
	
		$sql = "
			SELECT maxamount,
				(SELECT COUNT(*) FROM orders_detail WHERE menu_schedule_fk=".$this->tableName.".no AND od_pay_state = 0) AS order_cnt
			FROM ".$this->tableName."
			WHERE menuday = '".$today."' ";
	
		$result = mysql_query($sql, $conn);
		mysql_close($conn);
		$data = mysql_fetch_assoc($result);
		$result = 0;
		if ($data['maxamount'] > $data['order_cnt']) {
			$result = 1;
		}
	
		return $result;
	}
	
	/**
	 * 해당 주문건에 동일한 날짜에 주문이 있는지 확인
	 * @param string $today
	 * @param number $order_fk
	 * @return boolean
	 */
	function chkMenu($today='', $order_fk=0) {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
	
		$sql = "
			SELECT COUNT(*) AS cnt
			FROM orders_detail
			WHERE order_fk = ".$order_fk." AND od_pay_state = 0 AND menu_schedule_fk IN (SELECT no FROM menu_schedule WHERE menu_schedule.menuday='".$today."')";

		$result = mysql_query($sql, $conn);
		mysql_close($conn);
		$data = mysql_fetch_assoc($result);
		$result = 1;
		if ($data['cnt'] > 0) {
			$result = 0;
		}
	
		return $result;
	}

}


?>