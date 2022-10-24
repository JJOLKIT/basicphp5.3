<?
/*
주문

*/

include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/db/DBConnection.class.php";

class Order {

	// 검색 파라미터 (초기 개발시 검색조건 세팅필요)
	var $param = array (
					"spay_state",
					"spayment",
					"scancel_state",
					"sdatetype",
					"sstartdate",
					"senddate",
					"stype",
					"sval",
					"sordertype",
					"sorderby",
					"smember_fk",
					"smenuday"
				);

	var $pageRows;			// 페이지 로우수
	var $startPageNo=0;		// limit 시작페이지
	public $reqPageNo=1;	// 요청페이지
	var $conn;

	// 생성자
	function Order($pageRows=0, $request='') {
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
		if ($p['spay_state'] != "") {
			$whereSql .= " AND pay_state = ".$p['spay_state'];
		}
		if ($p['spayment'] != "") {
			$whereSql .= " AND payment = ".$p['spayment'];
		}
		if ($p['scancel_state'] != "") {
			$whereSql .= " AND cancel_state = ".$p['scancel_state'];
		}
		if ($p['sstartdate'] != '') {
			if ($p['senddate'] != '') {
				if ($p['sdatetype'] == 'all') {
					$whereSql .= " AND ((registdate BETWEEN '".$p['sstartdate']." 00:00:00' AND '".$p['senddate']." 23:59:59') OR (no IN (SELECT order_fk FROM orders_detail WHERE order_fk IN (SELECT no FROM menu_schedule WHERE menuday BETWEEN '".$p['sstartdate']."' AND '".$p['senddate']."'))) ) ";
				} else if ($p['sdatetype'] == 'registdate') {
					$whereSql .= " AND (registdate BETWEEN '".$p['sstartdate']." 00:00:00' AND '".$p['senddate']." 23:59:59') ";
				} else if ($p['sdatetype'] == 'menu') {
					$whereSql .= " AND (no IN (SELECT order_fk FROM orders_detail WHERE order_fk IN (SELECT no FROM menu_schedule menuday BETWEEN '".$p['sstartdate']."' AND '".$p['senddate']."'))) ";
				}
			}
		}
		if ($p['sval']) {
			if ($p['stype'] == 'all') {
				$whereSql .= " AND
						(
							(no IN (SELECT order_fk FROM orders_detail WHERE order_fk IN (SELECT no FROM menu_schedule WHERE name1 LIKE '%".$p['sval']."%' OR name2 LIKE '%".$p['sval']."%' OR name3 LIKE '%".$p['sval']."%' OR name4 LIKE '%".$p['sval']."%' OR name5 LIKE '%".$p['sval']."%'))) OR
							member_fk IN (SELECT no FROM member WHERE name LIKE '%".$p['sval']."%') OR
							member_fk IN (SELECT no FROM member WHERE id LIKE '%".$p['sval']."%') OR
							orderid LIKE '%".$p['sval']."%' OR
							accountname LIKE '%".$p['sval']."%' OR
							receiptaddr1 LIKE '%".$p['sval']."%' OR receiptaddr2 LIKE '%".$p['sval']."%'
						 )
						";
			} else if ($p['stype'] == 'addr') {
				$whereSql .= " AND
						(
							receiptaddr1 LIKE '%".$p['sval']."%' OR receiptaddr2 LIKE '%".$p['sval']."%' 
						 )
						";
			} else if ($p['stype'] == 'member_name') {
				$whereSql .= " AND
						(
							member_fk IN (SELECT no FROM member WHERE name LIKE '%".$p['sval']."%')
						 )
						";
			} else if ($p['stype'] == 'member_id') {
				$whereSql .= " AND
						(
							member_fk IN (SELECT no FROM member WHERE id LIKE '%".$p['sval']."%')
						 )
						";
			} else {
				$whereSql .= " AND (".$p['stype']." LIKE '%".$p['sval']."%' )";
			}
		}
		
		if ($p['smember_fk'] != "") {
			$whereSql .= " AND member_fk = ".$p['smember_fk'];
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
		$sql = " SELECT COUNT(*) AS cnt FROM orders ".$whereSql;
outlog($sql);
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
				(SELECT name FROM member AS m WHERE m.no = orders.member_fk) AS member_name,
				(SELECT id FROM member AS m WHERE m.no = orders.member_fk) AS member_id,
				(SELECT SUM(amount) FROM orders_detail AS od WHERE od.order_fk = orders.no) AS sum_amount,
				(SELECT SUM(salad_amount) FROM orders_detail AS od WHERE od.order_fk = orders.no) AS sum_salad_amount 
			FROM orders
			".$whereSql." ";
		if (!$param['sordertype']) $param['sordertype'] = "no";
		if (!$param['sorderby']) $param['sorderby'] = "DESC";
			$sql .= "ORDER BY ".$param['sordertype']." ".$param['sorderby']." ";
		$sql .= " LIMIT ".$this->startPageNo.", ".$this->pageRows." ";
outlog($sql);		
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
			FROM orders
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
			INSERT INTO orders
				(pay_state, cancel_state, 
				orderid, payment, accountname, member_fk, price, usepoint, savepoint, 
				receiptname, receipttel, receiptzipcode, receiptaddr1, receiptaddr2, paymentinfo,
				registdate, door_password_type, door_password, memo, is_sample)
			VALUES
				(".chkIsset($req[pay_state]).", ".chkIsset($req[cancel_state]).",
				'".$req[orderid]."', ".chkIsset($req[payment]).", '$req[accountname]', ".chkIsset($req[member_fk]).", ".chkIsset($req[price]).", ".chkIsset($req[usepoint]).", ".chkIsset($req[savepoint]).",
				'$req[receiptname]', '$req[receipttel]', '$req[receiptzipcode]', '$req[receiptaddr1]', '$req[receiptaddr2]', '$req[paymentinfo]',
				NOW(), ".chkIsset($req[door_password_type]).", '$req[door_password]', '$req[memo]', ".chkIsset($req[is_sample]).")";
		mysql_query($sql, $conn);
outlog($sql);
		$sql = "SELECT LAST_INSERT_ID() AS lastNo";
		$result = mysql_query($sql, $conn);
		$row = mysql_fetch_array($result);
		$lastNo = $row['lastNo'];
		mysql_close($conn);
		return $lastNo;
	}
	
	/**
	 * 수정
	 * @param $_REQUEST $req
	 * @return result
	 */
	function update($req="") {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();

		$sql = "
			UPDATE orders SET 
				pay_state=".chkIsset($req[pay_state]).", cancel_state=".chkIsset($req[cancel_state]).", 
				receiptname='$req[receiptname]', receipttel='$req[receipttel]', receiptzipcode='$req[receiptzipcode]', receiptaddr1='$req[receiptaddr1]', receiptaddr2='$req[receiptaddr2]', 
				door_password_type=".chkIsset($req[door_password_type]).", door_password='$req[door_password]', memo='$req[memo]'
			WHERE no = ".$req['no'];

		$result = mysql_query($sql, $conn);
		mysql_close($conn);
		return $result;
	}
	
	/**
	 * 적립포인트 수정
	 * @param number $no
	 * @param number $savepoint
	 * @return unknown
	 */
	function updateSavepoint($no=0, $savepoint=0) {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
	
		$sql = "
			UPDATE orders SET
				savepoint=".chkIsset($savepoint)."
			WHERE no = ".$no;
	
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

		$sql = " DELETE FROM orders WHERE no = ".$no;

		$result = mysql_query($sql, $conn);
		if ($result > 0) {
			mysql_query("DELETE FROM orders_detail WHERE order_fk=".$no, $conn);
		}
		mysql_close($conn);
		return $result;
	}
	
	/**
	 * 주문내역 등록
	 * @param $_REQUEST $req
	 * @return no
	 */
	function insertDetail($order_fk, $menu_schedule_fk, $od_pay_state, $amount, $salad_amount=0, $price, $totalprice) {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
	
		$sql = "
			INSERT INTO orders_detail
				(order_fk, menu_schedule_fk, od_pay_state, amount, salad_amount, price, totalprice, registdate)
			VALUES
				($order_fk, $menu_schedule_fk, ".chkIsset($od_pay_state).", ".chkIsset($amount).", ".chkIsset($salad_amount).", ".chkIsset($price).", ".chkIsset($totalprice).", NOW())";
		mysql_query($sql, $conn);
outlog($sql);	
		$sql = "SELECT LAST_INSERT_ID() AS lastNo";
		$result = mysql_query($sql, $conn);
		$row = mysql_fetch_array($result);
		$lastNo = $row['lastNo'];
		mysql_close($conn);
		return $lastNo;
	}
	
	/**
	 * 주문내역 목록
	 * @param $order_fk
	 * @return $result
	 */
	function getListDetail($order_fk=0) {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();

		$sql = "
			SELECT *,
				(SELECT menuday FROM menu_schedule AS m WHERE m.no = o.menu_schedule_fk) AS menuday,
				(SELECT name1 FROM menu_schedule AS m WHERE m.no = o.menu_schedule_fk) AS name1,
				(SELECT hot1 FROM menu_schedule AS m WHERE m.no = o.menu_schedule_fk) AS hot1,
				(SELECT name2 FROM menu_schedule AS m WHERE m.no = o.menu_schedule_fk) AS name2,
				(SELECT hot2 FROM menu_schedule AS m WHERE m.no = o.menu_schedule_fk) AS hot2,
				(SELECT name3 FROM menu_schedule AS m WHERE m.no = o.menu_schedule_fk) AS name3,
				(SELECT hot3 FROM menu_schedule AS m WHERE m.no = o.menu_schedule_fk) AS hot3,
				(SELECT name4 FROM menu_schedule AS m WHERE m.no = o.menu_schedule_fk) AS name4,
				(SELECT hot4 FROM menu_schedule AS m WHERE m.no = o.menu_schedule_fk) AS hot4,
				(SELECT name5 FROM menu_schedule AS m WHERE m.no = o.menu_schedule_fk) AS name5,
				(SELECT hot5 FROM menu_schedule AS m WHERE m.no = o.menu_schedule_fk) AS hot5,
				(SELECT name6 FROM menu_schedule AS m WHERE m.no = o.menu_schedule_fk) AS name6,
				(SELECT hot6 FROM menu_schedule AS m WHERE m.no = o.menu_schedule_fk) AS hot6,
				(SELECT name7 FROM menu_schedule AS m WHERE m.no = o.menu_schedule_fk) AS name7,
				(SELECT hot7 FROM menu_schedule AS m WHERE m.no = o.menu_schedule_fk) AS hot7
			FROM orders_detail AS o
				WHERE o.order_fk=$order_fk
			ORDER BY registdate DESC ";
	
		$result = mysql_query($sql, $conn);
		mysql_close($conn);
	
		return $result;
	}
	
	/**
	 * 주문상세 삭제
	 * @param int $no
	 * @return result
	 */
	function deleteDetail($no=0) {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
	
		mysql_query("DELETE FROM orders_detail WHERE order_fk=".$no, $conn);
		
		mysql_close($conn);
		return $result;
	}
	
	/**
	 * 주문번호 생성
	 * @return number
	 */
	function getOrderid() {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
		$d = date("ymd");
		
		$sql = " SELECT COUNT(*) AS cnt FROM orders WHERE substr(orderid,1,6) = '".$d."'";
		outlog($sql);
		$result = mysql_query($sql, $conn);
		mysql_close($conn);
		
		$row=mysql_fetch_array($result);
		$cnt = $row['cnt'];
		
		$orderid = $d."-".str_pad($cnt+1,"5","0",STR_PAD_LEFT);
		return $orderid;
	}

	/**
	 * 판매된 상품갯수 구하기
	 * @param $_REQUEST $param
	 * @return array
	 */
	function getMenuCount($menu_schedule_fk = 0) {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
		
		$sql = " SELECT COUNT(*) AS cnt FROM orders_detail 
					WHERE menu_schedule_fk=".$menu_schedule_fk." AND od_pay_state=0 AND order_fk IN (SELECT no FROM orders WHERE cancel_state=0)";

		$result = mysql_query($sql, $conn);
		mysql_close($conn);

		$row=mysql_fetch_array($result);
		$totalCount = $row['cnt'];

		return $totalCount;
	}
	
	/**
	 * 수정
	 * @param $_REQUEST $req
	 * @return result
	 */
	function updateOrderDetail($org_no=0, $change_fk=0) {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
	
		$sql = "
			UPDATE orders_detail SET
				menu_schedule_fk=".$change_fk."
			WHERE no = ".$org_no;

		$result = mysql_query($sql, $conn);
		mysql_close($conn);
		return $result;
	}
	
	/**
	 * 추가주문시 해당상품 추가주문횟수 구하기
	 * @param $add_order
	 * @return int
	 */
	function getAddOrderCount($add_order = 0) {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
	
		$sql = " SELECT IFNULL(addorder_cnt,0) AS addorder_cnt FROM orders
					WHERE no=".chkIsset($add_order)." ";
	
		$result = mysql_query($sql, $conn);
		mysql_close($conn);
	
		$row=mysql_fetch_array($result);
		$addorder_cnt = $row['addorder_cnt'];
	
		return $addorder_cnt;
	}
	
	/**
	 * 수정
	 * @param $_REQUEST $req
	 * @return result
	 */
	function updateAddOrderCount($add_order) {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
	
		$sql = "
			UPDATE orders SET
				addorder_cnt = addorder_cnt+1
			WHERE no = ".chkIsset($add_order);
	
		$result = mysql_query($sql, $conn);
		mysql_close($conn);
		return $result;
	}
	
	/**
	 * 취소
	 * @param $_REQUEST $no
	 * @return result
	 */
	function cancelOrder($no) {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
	
		$sql = "
			UPDATE orders SET
				cancel_state = 2
			WHERE no = ".chkIsset($no);
	
		$result = mysql_query($sql, $conn);
		mysql_close($conn);
		return $result;
	}
	
	/**
	 * 개별취소
	 * @param $_REQUEST $no
	 * @return result
	 */
	function cancelDetail($od_no, $order_fk) {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
	
		$sql = "
			UPDATE orders_detail SET
				od_pay_state = 1
			WHERE no = ".chkIsset($od_no);
		$result = mysql_query($sql, $conn);
		if ($result) {
			mysql_query("UPDATE orders SET price=(SELECT SUM(totalprice) FROM orders_detail WHERE order_fk=$order_fk AND od_pay_state=0) WHERE no=".$order_fk, $conn);
		}
		
		mysql_close($conn);
		return $result;
	}
	
	function getDetailWhereSql($p) {
		$whereSql = "  ";
		if ($p['sval']) {
			if ($p['stype'] == 'all') {
				$whereSql .= " AND
						(
							order_fk IN (SELECT no FROM orders WHERE member_fk IN (SELECT no FROM member WHERE name LIKE '%".$p['sval']."%')) OR
							order_fk IN (SELECT no FROM orders WHERE member_fk IN (SELECT no FROM member WHERE id LIKE '%".$p['sval']."%')) OR
							order_fk IN (SELECT no FROM orders WHERE orderid LIKE '%".$p['sval']."%') OR
							order_fk IN (SELECT no FROM orders WHERE accountname LIKE '%".$p['sval']."%') OR
							order_fk IN (SELECT no FROM orders WHERE receiptaddr1 LIKE '%".$p['sval']."%' OR receiptaddr2 LIKE '%".$p['sval']."%' )
								
						 )
						";
			} else if ($p['stype'] == 'addr') {
				$whereSql .= " AND
						(
							order_fk IN (SELECT no FROM orders WHERE receiptaddr1 LIKE '%".$p['sval']."%' OR receiptaddr2 LIKE '%".$p['sval']."%' )
						 )
						";
			} else if ($p['stype'] == 'member_name') {
				$whereSql .= " AND
						(
							order_fk IN (SELECT no FROM orders WHERE member_fk IN (SELECT no FROM member WHERE name LIKE '%".$p['sval']."%'))
						 )
						";
			} else if ($p['stype'] == 'member_id') {
				$whereSql .= " AND
						(
							order_fk IN (SELECT no FROM orders WHERE member_fk IN (SELECT no FROM member WHERE id LIKE '%".$p['sval']."%'))
						 )
						";
			} else if ($p['stype'] == 'orderid') {
				$whereSql .= " AND
						(
							order_fk IN (SELECT no FROM orders WHERE orderid LIKE '%".$p['sval']."%')
						 )
						";
			} else if ($p['stype'] == 'accountname') {
				$whereSql .= " AND
						(
							order_fk IN (SELECT no FROM orders WHERE accountname LIKE '%".$p['sval']."%')
						 )
						";
			}
		}
	
		return $whereSql;
	}
	
	/**
	 * 전체로우수, 페이지카운트
	 * @param $_REQUEST $param
	 * @return array
	 */
	function getDetailCount($req = "") {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
		$whereSql = $this->getDetailWhereSql($req);	// where절
		
		$sql = " SELECT COUNT(*) AS cnt FROM orders_detail 
				WHERE 
					order_fk IN (SELECT no FROM orders WHERE cancel_state=0)
					AND od_pay_state=0 
					AND menu_schedule_fk IN (SELECT no FROM menu_schedule WHERE menuday = '".$req['smenuday']."') ";
		if ($req['spay_state'] != "") {
			$sql .= " AND order_fk IN (SELECT no FROM orders WHERE pay_state=".$req['spay_state'].") ";
		}
		if ($req['spayment'] != "") {
			$sql .= " AND order_fk IN (SELECT no FROM orders WHERE payment=".$req['spayment'].") ";
		}
		$sql .= $whereSql;
outlog($sql);	
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
	function getDetailList($req='') {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
		$whereSql = $this->getDetailWhereSql($req);	// where절
	
		$sql = "
			SELECT 
				od.registdate, od.order_fk,
				(SELECT is_sample FROM orders AS o WHERE o.no=od.order_fk) AS is_sample,
				(SELECT orderid FROM orders AS o WHERE o.no=od.order_fk) AS orderid,
				(SELECT receiptname FROM orders AS o WHERE o.no=od.order_fk) AS receiptname,
				(SELECT receipttel FROM orders AS o WHERE o.no=od.order_fk) AS receipttel,
				(SELECT receiptzipcode FROM orders AS o WHERE o.no=od.order_fk) AS receiptzipcode,
				(SELECT receiptaddr1 FROM orders AS o WHERE o.no=od.order_fk) AS receiptaddr1,
				(SELECT receiptaddr2 FROM orders AS o WHERE o.no=od.order_fk) AS receiptaddr2,
				(SELECT door_password_type FROM orders AS o WHERE o.no=od.order_fk) AS door_password_type,
				(SELECT door_password FROM orders AS o WHERE o.no=od.order_fk) AS door_password,
				(SELECT pay_state FROM orders AS o WHERE o.no=od.order_Fk) AS pay_state,
				(SELECT cancel_state FROM orders AS o WHERE o.no=od.order_Fk) AS cancel_state,
				(SELECT payment FROM orders AS o WHERE o.no=od.order_Fk) AS payment,
				(SELECT recommend_id FROM member AS m WHERE m.no=(SELECT member_fk FROM orders AS o WHERE o.no=od.order_Fk)) AS recommend_id,
				amount
			FROM orders_detail AS od 
			WHERE 
				order_fk IN (SELECT no FROM orders WHERE cancel_state=0) 
				AND od.od_pay_state=0 
				AND menu_schedule_fk IN (SELECT no FROM menu_schedule WHERE menuday = '".$req['smenuday']."') ";
		if ($req['spay_state'] != "") {
			$sql .= " AND order_fk IN (SELECT no FROM orders WHERE pay_state=".$req['spay_state'].") ";
		}
		if ($req['spayment'] != "") {
			$sql .= " AND order_fk IN (SELECT no FROM orders WHERE payment=".$req['spayment'].") ";
		}
		$sql .= $whereSql;
		$sql .= " ORDER BY receiptaddr1 DESC LIMIT ".$this->startPageNo.", ".$this->pageRows." ";
		
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
			FROM orders
			ORDER BY no DESC
			LIMIT 0, ".$number." ";
	
		$result = mysql_query($sql, $conn);
		mysql_close($conn);
	
		return $result;
	}
	

}


?>