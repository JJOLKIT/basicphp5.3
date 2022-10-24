<?
/*
java CodeUtil

*/

function getPermissionName($type=0) {
	$result = "";

	if ($type == 0) {
		$result = "<font color='red'>불가능</font>";
	} else if ($type == 1) {
		$result = "<font color='blue'>가능</font>";
	}

	return $result;
}

// 도로명 시도 검색
function getSidoEngName($sido=0){
	$result = "";
	if( 0 == $sido ){
		$result = "gangwon";
	} else if( 1 == $sido ){
		$result = "gyeonggi";
	} else if( 2 == $sido ){
		$result = "gyeongnam";
	} else if( 3 == $sido ){
		$result = "gyeongbuk";
	} else if( 4 == $sido ){
		$result = "gwangju";
	} else if( 5 == $sido ){
		$result = "daegu";
	} else if( 6 == $sido ){
		$result = "daejeon";
	} else if( 7 == $sido ){
		$result = "busan";
	} else if( 8 == $sido ){
		$result = "seoul";
	} else if( 9 == $sido ){
		$result = "sejong";
	} else if( 10 == $sido ){
		$result = "ulsan";
	} else if( 11 == $sido ){
		$result = "incheon";
	} else if( 12 == $sido ){
		$result = "jeonnam";
	} else if( 13 == $sido ){
		$result = "jeonbuk";
	} else if( 14 == $sido ){
		$result = "jeju";
	} else if( 15 == $sido ){
		$result = "chungnam";
	} else if( 16 == $sido ){
		$result = "chungbuk";
	}
	
	return $result;
}

function getSidoKorName($sido) {
		$result = "";
		if("gangwon" == $sido){
			$result = "강원도";
		} else if("gyeonggi" == $sido){
			$result = "경기도";
		} else if("gyeongnam" == $sido){
			$result = "경상남도";
		} else if("gyeongbuk" == $sido){
			$result = "경상북도";
		} else if("gwangju" == $sido){
			$result = "광주광역시";
		} else if("daegu" == $sido){
			$result = "대구광역시";
		} else if("daejeon" == $sido){
			$result = "대전광역시";
		} else if("busan" == $sido){
			$result = "부산광역시";
		} else if("seoul" == $sido){
			$result = "서울특별시";
		} else if("sejong" == $sido){
			$result = "세종특별자치시";
		} else if("ulsan" == $sido){
			$result = "울산광역시";
		} else if("incheon" == $sido){
			$result = "인천광역시";
		} else if("jeonnam" == $sido){
			$result = "전라남도";
		} else if("jeonbuk" == $sido){
			$result = "전라북도";
		} else if("jeju" == $sido){
			$result = "제주특별자치도";
		} else if("chungnam" == $sido){
			$result = "충청남도";
		} else if("chungbuk" == $sido){
			$result = "충청북도";
		}
		
		return $result;
	}

// 도로명 시도 검색 select option
function getSidoType($sido='') {
	$result = "<option value=''>==시도검색==</option>";

	for ($i=0; $i<17; $i++) {
		$result .= "<option value='".getSidoEngName($i)."'>".getSidoKorName(getSidoEngName($i))."</option>";
	}

	return $result;
}

// 포스트형갤러리 옵션

function getPostName($cate = 0){
	$result = '전체';
	if($cate == 0 ){
		$result = "New";
	}else if($cate == 1){
		$result = "Best";
	}

	return $result;
}
function postCategory($cate=''){
	$result = "<option value=''>==카테고리선택==</option>";

	for($i = 0; $i < 2; $i++){
		$result .= "<option value='".$i."' ".getSelected($i,$cate).">".getPostName($i)."</option>";
	}
	return $result;
}


// sms 및 메일 수신여부
function getReceiveTypeName($type=-1) {
	$result = "";

	if ($type == 1) {
		$result = "수신";
	} else if ($type == 0) {
		$result = "수신안함";
	}
	return $result;
}

// sms 및 메일 수신여부 option
function getReceiveSelectType($type=-1) {
	$result = "";

	for ($i=0; $i<=1; $i++) {
		$result .= "<option value='".$i."' ".getSelected($i, $type).">".getReceiveTypeName($i)."</option>";
	}
	return $result;
}

// 회원상태
function getMemberStateTypeName($type=0) {
	$result = "";

	if ($type == 0) {
		$result = "회원";
	} else if ($type == 1) {
		$result = "탈퇴신청";
	}
	return $result;
}

// 회원상태 option
function getMemberStateType($type=0) {
	$result = "";

	for ($i=0; $i<=1; $i++) {
		$result .= "<option value='".$i."' ".getSelected($i, $type).">".getMemberStateTypeName($i)."</option>";
	}
	return $result;
}

// TOP공지 레이블 출력
function getTop($type='') {
	$result = "";

	if ($type == '0') {
		$result = "<span style='color:#ff4400'>공지 안 함</span>";
	} else if ($type == '1') {
		$result = "<span style='color:#0088ff'>공지함</span>";
	}
	return $result;
}

// NEW아이콘 레이블 출력
function getNewIcon($type='') {
	$result = "";

	if ($type == '0') {
		$result = "<span style='color:#ff4400'>표기 안 함</span>";
	} else if ($type == '1') {
		$result = "<span style='color:#0088ff'>표기함</span>";
	} else if ($type == '2') {
		$result = "<span style='color:green'>하루만</span>";
	}

	return $result;
}

// 노출여부 레이블 출력
function getDisplay($type='') {
	$result = "";

	if ($type == '0') {
		$result = "<span style='color:#ff4400'>비노출</span>";
	} else if ($type == '1') {
		$result = "<span style='color:#0088ff'>노출</span>";
	}

	return $result;
}

// 시안상태 레이블 출력
function getState($type='') {
	$result = "";

	if ($type == '0') {
		$result = "<span style='color:#ff4400'>시안대기</span>";
	} else if ($type == '1') {
		$result = "<span style='color:#0088ff'>시안확정</span>";
	}

	return $result;
}

// 메인공지 레이블 출력
function getMain($type='') {
	$result = "";

	if ($type == '0') {
		$result = "<span style='color:#ff4400'>비노출</span>";
	} else {
		$result = "<span style='color:#0088ff'>노출</span>";
	}

	return $result;
}

// 성별
function getSexTypeName($type=0) {
	$result = "";

	if ($type == 1) {
		$result = "남자";
	} else if ($type == 2) {
		$result = "여자";
	}
	return $result;
}

// 성별 option
function getSexType($type=0) {
	$result = "";

	for ($i=1; $i<=2; $i++) {
		$result .= "<option value='".$i."' ".getSelected($i, $type).">".getSexTypeName($i)."</option>";
	}
	return $result;
}

// 양,음력 출력
function getBirthTypeName($type=0) {
	$result = "";

	if ($type == 0) {
		$result = "양력";
	} else if ($type == 1) {
		$result = "음력";
	}
	return $result;
}

// 상담게시판 공개여부 상태 라벨 출력
function getSecretName($type) {
	$result = "";

	if ($type == "1") {
		$result = "<span style='color:#ff4400'>비공개</span>";
	} else {
		$result = "<span style='color:#0088ff'>공개</span>";
	}
	return $result;
}

// SMS발송 여부 라벨 출력
function getSmsName($type) {
	$result = "";

	if ($type == "0") {
		$result = "<span style='color:#ff4400'>미발송</span>";
	} else {
		$result = "<span style='color:#0088ff'>발송</span>";
	}
	return $result;
}

// SMS발송 시간제한여부 라벨 출력
function getSmsTimeLimitName($type) {
	$result = "";

	if ($type == "0") {
		$result = "<span style='color:#ff4400'>미제한</span>";
	} else {
		$result = "<span style='color:#0088ff'>제한</span>";
	}
	return $result;
}

// 팝업 상태 라벨 출력
function getPopupState($type) {
	$result = "";

	if ($type == "0") {
		$result = "<span style='color:#ff4400'>종료</span>";
	} else {
		$result = "<span style='color:#0088ff'>진행중</span>";
	}
	return $result;
}

// 시간간격 (30분 간격)
function getTimeList($time) {
	$result = "";

	$result .= "<option value=\"00:00:00\" ".getSelected("00:00:00", $time).">00:00</option>\n";
	$result .= "<option value=\"00:30:00\" ".getSelected("00:30:00", $time).">00:30</option>\n";
	$result .= "<option value=\"01:00:00\" ".getSelected("01:00:00", $time).">01:00</option>\n";
	$result .= "<option value=\"01:30:00\" ".getSelected("01:30:00", $time).">01:30</option>\n";
	$result .= "<option value=\"02:00:00\" ".getSelected("02:00:00", $time).">02:00</option>\n";
	$result .= "<option value=\"02:30:00\" ".getSelected("02:30:00", $time).">02:30</option>\n";
	$result .= "<option value=\"03:00:00\" ".getSelected("03:00:00", $time).">03:00</option>\n";
	$result .= "<option value=\"03:30:00\" ".getSelected("03:30:00", $time).">03:30</option>\n";
	$result .= "<option value=\"04:00:00\" ".getSelected("04:00:00", $time).">04:00</option>\n";
	$result .= "<option value=\"04:30:00\" ".getSelected("04:30:00", $time).">04:30</option>\n";
	$result .= "<option value=\"05:00:00\" ".getSelected("05:00:00", $time).">05:00</option>\n";
	$result .= "<option value=\"05:30:00\" ".getSelected("05:30:00", $time).">05:30</option>\n";
	$result .= "<option value=\"06:00:00\" ".getSelected("06:00:00", $time).">06:00</option>\n";
	$result .= "<option value=\"06:30:00\" ".getSelected("06:30:00", $time).">06:30</option>\n";
	$result .= "<option value=\"07:00:00\" ".getSelected("07:00:00", $time).">07:00</option>\n";
	$result .= "<option value=\"07:30:00\" ".getSelected("07:30:00", $time).">07:30</option>\n";
	$result .= "<option value=\"08:00:00\" ".getSelected("08:00:00", $time).">08:00</option>\n";
	$result .= "<option value=\"08:30:00\" ".getSelected("08:30:00", $time).">08:30</option>\n";
	$result .= "<option value=\"09:00:00\" ".getSelected("09:00:00", $time).">09:00</option>\n";
	$result .= "<option value=\"09:30:00\" ".getSelected("09:30:00", $time).">09:30</option>\n";
	$result .= "<option value=\"10:00:00\" ".getSelected("10:00:00", $time).">10:00</option>\n";
	$result .= "<option value=\"10:30:00\" ".getSelected("10:30:00", $time).">10:30</option>\n";
	$result .= "<option value=\"11:00:00\" ".getSelected("11:00:00", $time).">11:00</option>\n";
	$result .= "<option value=\"11:30:00\" ".getSelected("11:30:00", $time).">11:30</option>\n";
	$result .= "<option value=\"12:00:00\" ".getSelected("12:00:00", $time).">12:00</option>\n";
	$result .= "<option value=\"12:30:00\" ".getSelected("12:30:00", $time).">12:30</option>\n";
	$result .= "<option value=\"13:00:00\" ".getSelected("13:00:00", $time).">13:00</option>\n";
	$result .= "<option value=\"13:30:00\" ".getSelected("13:30:00", $time).">13:30</option>\n";
	$result .= "<option value=\"14:00:00\" ".getSelected("14:00:00", $time).">14:00</option>\n";
	$result .= "<option value=\"14:30:00\" ".getSelected("14:30:00", $time).">14:30</option>\n";
	$result .= "<option value=\"15:00:00\" ".getSelected("15:00:00", $time).">15:00</option>\n";
	$result .= "<option value=\"15:30:00\" ".getSelected("15:30:00", $time).">15:30</option>\n";
	$result .= "<option value=\"16:00:00\" ".getSelected("16:00:00", $time).">16:00</option>\n";
	$result .= "<option value=\"16:30:00\" ".getSelected("16:30:00", $time).">16:30</option>\n";
	$result .= "<option value=\"17:00:00\" ".getSelected("17:00:00", $time).">17:00</option>\n";
	$result .= "<option value=\"17:30:00\" ".getSelected("17:30:00", $time).">17:30</option>\n";
	$result .= "<option value=\"18:00:00\" ".getSelected("18:00:00", $time).">18:00</option>\n";
	$result .= "<option value=\"18:30:00\" ".getSelected("18:30:00", $time).">18:30</option>\n";
	$result .= "<option value=\"19:00:00\" ".getSelected("19:00:00", $time).">19:00</option>\n";
	$result .= "<option value=\"19:30:00\" ".getSelected("19:30:00", $time).">19:30</option>\n";
	$result .= "<option value=\"20:00:00\" ".getSelected("20:00:00", $time).">20:00</option>\n";
	$result .= "<option value=\"20:30:00\" ".getSelected("20:30:00", $time).">20:30</option>\n";
	$result .= "<option value=\"21:00:00\" ".getSelected("21:00:00", $time).">21:00</option>\n";
	$result .= "<option value=\"21:30:00\" ".getSelected("21:30:00", $time).">21:30</option>\n";
	$result .= "<option value=\"22:00:00\" ".getSelected("22:00:00", $time).">22:00</option>\n";
	$result .= "<option value=\"22:30:00\" ".getSelected("22:30:00", $time).">22:30</option>\n";
	$result .= "<option value=\"23:00:00\" ".getSelected("23:00:00", $time).">23:00</option>\n";
	$result .= "<option value=\"23:30:00\" ".getSelected("23:30:00", $time).">23:30</option>\n";

	return $result;
}

// 시간간격 (1시간 간격)
function getTimeList2($time) {
	$result = "";

	$result .= "<option value=\"00:00:00\" ".getSelected("00:00:00", $time).">00:00</option>\n";
	$result .= "<option value=\"01:00:00\" ".getSelected("01:00:00", $time).">01:00</option>\n";
	$result .= "<option value=\"02:00:00\" ".getSelected("02:00:00", $time).">02:00</option>\n";
	$result .= "<option value=\"03:00:00\" ".getSelected("03:00:00", $time).">03:00</option>\n";
	$result .= "<option value=\"04:00:00\" ".getSelected("04:00:00", $time).">04:00</option>\n";
	$result .= "<option value=\"05:00:00\" ".getSelected("05:00:00", $time).">05:00</option>\n";
	$result .= "<option value=\"06:00:00\" ".getSelected("06:00:00", $time).">06:00</option>\n";
	$result .= "<option value=\"07:00:00\" ".getSelected("07:00:00", $time).">07:00</option>\n";
	$result .= "<option value=\"08:00:00\" ".getSelected("08:00:00", $time).">08:00</option>\n";
	$result .= "<option value=\"09:00:00\" ".getSelected("09:00:00", $time).">09:00</option>\n";
	$result .= "<option value=\"10:00:00\" ".getSelected("10:00:00", $time).">10:00</option>\n";
	$result .= "<option value=\"11:00:00\" ".getSelected("11:00:00", $time).">11:00</option>\n";
	$result .= "<option value=\"12:00:00\" ".getSelected("12:00:00", $time).">12:00</option>\n";
	$result .= "<option value=\"13:00:00\" ".getSelected("13:00:00", $time).">13:00</option>\n";
	$result .= "<option value=\"14:00:00\" ".getSelected("14:00:00", $time).">14:00</option>\n";
	$result .= "<option value=\"15:00:00\" ".getSelected("15:00:00", $time).">15:00</option>\n";
	$result .= "<option value=\"16:00:00\" ".getSelected("16:00:00", $time).">16:00</option>\n";
	$result .= "<option value=\"17:00:00\" ".getSelected("17:00:00", $time).">17:00</option>\n";
	$result .= "<option value=\"18:00:00\" ".getSelected("18:00:00", $time).">18:00</option>\n";
	$result .= "<option value=\"19:00:00\" ".getSelected("19:00:00", $time).">19:00</option>\n";
	$result .= "<option value=\"20:00:00\" ".getSelected("20:00:00", $time).">20:00</option>\n";
	$result .= "<option value=\"21:00:00\" ".getSelected("21:00:00", $time).">21:00</option>\n";
	$result .= "<option value=\"22:00:00\" ".getSelected("22:00:00", $time).">22:00</option>\n";
	$result .= "<option value=\"23:00:00\" ".getSelected("23:00:00", $time).">23:00</option>\n";

	return $result;
}

// 예약 간격 라벨 출력
function getTimeInterval($timeInterval) {
	$result = "";

	if ($timeInterval == '1') {
		$result = "20분 간격";
	} else if ($timeInterval == '2') {
		$result = "30분 간격";
	} else if ($timeInterval == '3') {
		$result = "60분 간격";
	} else if ($timeInterval == '4') {
		$result = "120분 간격";
	}

	return $result;
}

// 초진/재진분류
function getIncipientMeetName($type) {
	$result = "";
	if ($type == 1) {
		$result = "<span class='reserFirst'>초진</span>";
	} else if ($type == 2) {
		$result = "<span class='reserSecond'>재진</span>";
	} else {
		$result = "";
	}
	return $result;
}

// 초진/재진분류
function getIncipientMeetType($type) {
	$result = "";
	$reulst .= "<option value='1' ".getSelected(1,$type).">".getIncipientMeetName(1)."</option>";
	$reulst .= "<option value='2' ".getSelected(2,$type).">".getIncipientMeetName(2)."</option>";
	return $reulst;
}

// 예약상태 라벨
function getReserStateNameType($state) {
	$result = "";
	$result .= "<option value='0' ".getSelected(0, $state).">전체</option>";
	$result .= "<option value='1' ".getSelected(1, $state).">요청</option>";
	$result .= "<option value='2' ".getSelected(2, $state).">변경</option>";
	$result .= "<option value='3' ".getSelected(3, $state).">완료</option>";
	$result .= "<option value='4' ".getSelected(4, $state).">취소</option>";
	$result .= "<option value='5' ".getSelected(5, $state).">요청+변경</option>";
	$result .= "<option value='6' ".getSelected(6, $state).">요청+변경+완료</option>";
	return $result;
}

// 예약상태
function getReserState($state) {
	$result = "";
	if ($state == 1) {
		$result = "<span class='reserwaiting'>".getReserStateName($state)."</span>";
	} else if ($state == 2) {
		$result = "<span class='resercancel'>".getReserStateName($state)."</span>";
	} else if ($state == 3) {
		$result = "<span class='reserconfirm'>".getReserStateName($state)."</span>";
	} else if ($state == 4) {
		$result = "<span class='resercancel'>".getReserStateName($state)."</span>";
	}
	return $result;
}

// 예약상태명
function getReserStateName($state) {
	$result = "";
	if ($state == 1) {
		$result = "요청";
	} else if ($state == 2) {
		$result = "변경";
	} else if ($state == 3) {
		$result = "확인";
	} else if ($state == 4) {
		$result = "취소";
	}
	return $result;
}


// 예약 상태 css class명
function getReserClass($state, $reser) {
	$result = "";
	if ($state == 1) {
		$result = $reser == 'reser' ? "class='reserwaiting_bg'" : "class='reserwaiting'";
	} else if ($state == 2) {
		$result = $reser == 'reser' ? "class='resercancel_bg'" : "class='resercancel'";
	} else if ($state == 3) {
		$result = $reser == 'reser' ? "" : "class='reserconfirm'";
	} else if ($state == 4) {
		$result = $reser == 'reser' ? "class='resercancel_bg'" : "class='resercancel'";
	}
	return $result;
}

// 예약종류
function getReserType($type) {
	$result = "";
	for ($i=1; $i<=4; $i++) {
		$result .= "<option value='".$i."' ".getSelected($i, $type).">".getReserTypeName($i)."</option>";
	}
	return $result;
}

// 예약종류
function getReserTypeName($type) {
	$result = '';
	if ($type == 1) {
		$result = "상담";
	} else if ($type == 2) {
		$result = "진료";
	} else if ($type == 3) {
		$result = "치료";
	} else if ($type == 4) {
		$result = "기타";
	} else {
		$result = "";
	}
	return $result;
}

// 온오프구분 
function getOnoffTypeName($type) {
	$result = "";
	if ($type == 0) {
		$result = "온라인";
	} else if ($type == 1) {
		$result = "오프라인";
	}
	return $result;
}

// 온오프구분
function getOnoffType($type) {
	$reult = "";
	for ($i=0; $i<=1; $i++) {
		$result .= "<option value='".$i."' ".getSelected($i, $type).">".getOnoffTypeName($i)."</option>";
	}
	return $result;
}

// 내원경로
function getRouteNameType($type) {
	$result = "";
	for ($i=1; $i<=10; $i++) {
		$result .= "<option value='".$i."' ".getSelected($i, $type).">".getRouteTypeName($i)."</option>";
	}
	return $result;
}

// 내원경로
function getRouteTypeName($type) {
	$result = "";
	if ($type == 1) {
		$result = "인터넷";
	} else if ($type == 2) {
		$result = "간판";
	} else if ($type == 3) {
		$result = "소개";
	} else if ($type == 4) {
		$result = "현수막/전단지";
	} else if ($type == 5) {
		$result = "버스";
	} else if ($type == 6) {
		$result = "지하철";
	} else if ($type == 7) {
		$result = "신문";
	} else if ($type == 8) {
		$result = "TV";
	} else if ($type == 9) {
		$result = "잡지";
	} else if ($type == 10) {
		$result = "기타";
	}
	return $result;
}

// 전화요청
function getTelIncipientName($type) {
	$result = "";
	if ($type == 1) {
		$result = "<font color='#0064ff'>요청</font>";
	} else if ($type == 2) {
		$result = "<font color='#ff0000'>요청 안함</font>";
	}
	return $result;
}

// 전화요청 option
function getTelIncipientType($type) {
	$result = "";
	$result .= "<option style='color:#0064ff' value='1' ".getSelected(1, $type).">".getTelIncipientName(1)."</option>";
	$result .= "<option style='color:#ff0000' value='2' ".getSelected(2, $type).">".getTelIncipientName(2)."</option>";

	return $result;
}

// 지점별 예약 내역 프로세스 아이콘
function getReserIcon($no, $state) {
	$icon = "";
	if ($state != 3) {
		$icon .= " <img src='/img/ico_reserconfirm.gif' alt='예약확인' onclick='chgState(".$no.", 3, this)' style='cursor:pointer;'/> ";
	}
	if ($state != 4) {
		$icon .= " <img src='/img/ico_resercancel.gif' alt='예약취소' onclick='chgState(".$no.", 4, this)' style='cursor:pointer;'/> ";
	}
	return $icon;
}

// 랜딩관리 사용여부
function getlandingState($state) {
	$result = "<span style='color:#0088ff'>사용</span>";
	if ($state == 0) {
		$result = "<span style='color:#ff4400'>사용안함</span>";
	}
	return $result;
}

function getTelConsultTime($type, $isCurDay, $isMonday, $curTime) {
	$result = "";
	$temp = 1;
	if ($isMonday) {
		$temp = 5;

		if ($isCurDay) {
			// 현재시간보다 1시간 더 뒤부터 예약가능
			if ($curTime == '09') {
				$temp = 5;
			} else if ($curTime == '10') {
				$temp = 5;
			} else if ($curTime == '11') {
				$temp = 5;
			} else if ($curTime == '12') {
				$temp = 5;
			} else if ($curTime == '13') {
				$temp = 5;
			} else if ($curTime == '14') {
				$temp = 6;
			} else if ($curTime == '15') {
				$temp = 7;
			} else if ($curTime == '16') {
				$temp = 8;
			} else if ($curTime == '17') {
				$temp = 9;
			} else if ($curTime == '18') {
				$temp = 10;
			} else if ($curTime == '19') {
				$temp = 11;
			} else if ($curTime == '20') {
				$temp = 11;
			} else if ($curTime == '21') {
				$temp = 11;
			} else if ($curTime == '22') {
				$temp = 11;
			} else if ($curTime == '23') {
				$temp = 11;
			}
		}
	} else {
		if ($isCurDay) {
			// 현재시간보다 1시간 더 뒤부터 예약가능
			if ($curTime == '09') {
				$temp = 3;
			} else if ($curTime == '10') {
				$temp = 3;
			} else if ($curTime == '11') {
				$temp = 3;
			} else if ($curTime == '12') {
				$temp = 4;
			} else if ($curTime == '13') {
				$temp = 5;
			} else if ($curTime == '14') {
				$temp = 6;
			} else if ($curTime == '15') {
				$temp = 7;
			} else if ($curTime == '16') {
				$temp = 8;
			} else if ($curTime == '17') {
				$temp = 9;
			} else if ($curTime == '18') {
				$temp = 10;
			} else if ($curTime == '19') {
				$temp = 11;
			} else if ($curTime == '20') {
				$temp = 11;
			} else if ($curTime == '21') {
				$temp = 11;
			} else if ($curTime == '22') {
				$temp = 11;
			} else if ($curTime == '23') {
				$temp = 11;
			}
		}
	}

	$result .= "<option value=''>시간 선택</option>";
	for ($i=$temp; $i<11; $i++) {
		if ($i != 4) {
			$result .= "<option value='".substr(getTelConsultTimeName($i),0,5)."' ".getSelected($i, $type).">".getTelConsultTimeName($i)."</option>";
		}
	}
	return $result;
}

function getTelConsultTimeName($type) {
	$result = "";

	if ($type == 1) {
		$result = "10:00-11:00";
	} else if ($type == 2) {
		$result = "11:00-12:00";
	} else if ($type == 3) {
		$result = "12:00-13:00";
	} else if ($type == 4) {
		$result = "13:00-14:00";
	} else if ($type == 5) {
		$result = "14:00-15:00";
	} else if ($type == 6) {
		$result = "15:00-16:00";
	} else if ($type == 7) {
		$result = "16:00-17:00";
	} else if ($type == 8) {
		$result = "17:00-18:00";
	} else if ($type == 9) {
		$result = "18:00-19:00";
	} else if ($type == 10) {
		$result = "19:00-20:00";
	}
	return $result;
}

///////////////////////////////////////////////////////////////////////////////////////////////
//// 정갈한찬
///////////////////////////////////////////////////////////////////////////////////////////////

/**
 * 시/구 이름
 * @param unknown $type
 * @return string
 */
function getSiguName($type) {
	$result = "";
	if ($type == 1) {
		$result = "서울시";
	} else if ($type == 2) {
		$result = "경기도";
	}
	return $result;
}

/**
 * 시/구 select box option
 * @param unknown $type
 * @return string
 */
function getSiguOption($type) {
	$reult = "";
	for ($i=1; $i<=2; $i++) {
		$result .= "<option value='".$i."' ".getSelected($i, $type).">".getSiguName($i)."</option>";
	}
	return $result;
}

///////////////////////////////////////////////////////////////////////////////////////////////
//// 주문
///////////////////////////////////////////////////////////////////////////////////////////////

/**
 * 결제상태
 * @param unknown $type
 * @return string
 */
function getPayStateName($type) {
	$result = "";
	if ($type == 0) {
		$result = "미결제";
	} else if ($type == 1) {
		$result = "결제완료";
	}
	return $result;
}

/**
 * 상품별결제상태
 * @param unknown $type
 * @return string
 */
function getOdPayStateName($type) {
	$result = "";
	if ($type == 0) {
		$result = "";
	} else if ($type == 1) {
		$result = "취소완료";
	}
	return $result;
}

/**
 * 취소상태
 * @param unknown $type
 * @return string
 */
function getCancelStateName($type) {
	$result = "";
	if ($type == 1) {
		$result = "취소요청";
	} else if ($type == 2) {
		$result = "취소완료";
	}
	return $result;
}
/**
 * 결제방법
 * @param unknown $type
 * @return string
 */
function getPaymentName($type) {
	$result = "";
	if ($type == 1) {
		$result = "무통장";
	} else if ($type == 2) {
		$result = "신용카드";
	}

	return $result;
}
/**
 * 포인트구분
 * @param unknown $type
 * @return string
 */
function getPointTypeName($type) {
	$result = "";
	if ($type == 1) {
		$result = "적립";
	} else if ($type == 2) {
		$result = "사용";
	}
	return $result;
}


function getSpam($type){
	$result = "";
	if($type == 2){
		$result = "<b class='isspam'>스팸</b>";
	}else{
		$result = "미스팸";
	}

	return $result;
}

function getDeviceName($type){
	$result = "";

	if($type == "M"){
		$result ="모바일";
	}else if($type == "PC"){
		$result = "PC";
	}

	return $result;
}




?>