<?
/*
기존 com.vizensoft.util.DateUtil.java
by withsky 2015.01.14

*/

include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";

// 현재 날짜 리턴
function getToday() {
	$result = date('Y-m-d');
	return $result;
}

// 현재 날짜 시분초 리턴
function getFullToday() {
	$result = date('Y-m-d H:i:s');
	return $result;
}

// thisday일짜에  num(일수)만큼 더한다.
function getDayDateAdd($interval=0, $date='') {
	$result = date("Y-m-d", strtotime($interval." day", strtotime($date)));
	return $result;
}

// thisday일짜에  num(개월)만큼 더한다.
function getMonthDateAdd($interval=0, $date='') {
	$result = date("Y-m-d", strtotime($interval." month", strtotime($date)));
	return $result;
}

// thisday일짜에  num(개월)만큼 더한다.
function getYearDateAdd($interval=0, $date='') {
	$result = date("Y-m-d", strtotime($interval." years", strtotime($date)));
	return $result;
}

// yyyy-mm-dd형식으로 리턴
function getYMD($datetime) {
	$result = '';
	if ($datetime) $result = substr($datetime, 0, 10);
	return $result;
}

// yearMonth에 interval(개월)만큼 월을 더한다.
function getYearMonth($yearMonth, $interval) {
	$year = date('Y');
	$month = date('m');
	$day = '01';

	$result = "";
	if ($yearMonth) {
		$year = substr($yearMonth, 0,4);
		$month = substr($yearMonth, 5);
	}
	$result = date("Y-m", strtotime($interval." month", strtotime($year."-".$month."-".$day)));
	return $result;
}

// 해당주의 요일 int값 리턴
// php에서는 1이 월요일 0은 일요일
function getDatePart($thisday) {
	$result = date('w', strtotime($thisday));
	return $result;
}

// 요일 이름 리턴
function getDayName($i) {
	$result = "";
	if ($i == 0) {
		$result = "일";
	} else if ($i == 1) {
		$result = "월";
	} else if ($i == 2) {
		$result = "화";
	} else if ($i == 3) {
		$result = "수";
	} else if ($i == 4) {
		$result = "목";
	} else if ($i == 5) {
		$result = "금";
	} else if ($i == 6) {
		$result = "토";
	}
	return $result;
}

// 일요일제외 카운트
function getSundayIgnoreAdd($thisday) {

	$dayCnt = array();
	$temp = 0;

	for ($i=0; $i<7; $i++) {

		$tempDay = getDayDateAdd($temp, $thisday) ;

		if (getDatePart($tempDay) == 0) {
			$temp++;
		}

		$dayCnt[$i] = $temp;
		$temp++;
	}

	return $dayCnt;
}

function getYoilForKor($date) {
	$yoil = array("일","월","화","수","목","금","토");
	return $yoil[date('w', strtotime($date))];
}

function getYoilForEng($date) {
	$yoil = array("Sun","Mon","Tue","Wed","Thu","Fri","Sat");
	return $yoil[date('w', strtotime($date))];
}
?>