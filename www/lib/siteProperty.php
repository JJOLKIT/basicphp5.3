<?
/*
사이트 기본정보 상수값 정의

by withsky 2017.01.10
*/

define("HOSTING_SDT", "2021-01-20");		//호스팅 시작일자
define("SSL_SDT", "2022-01-19");				//SSL 시작일자


define("COMPANY_NAME", "기본 프로그램PHP3");							// 업체명
define("SSL_USE", false);										// SSL 사용여부
define("COMPANY_URL", "http://basicphp3.sanggong.net");		// URL
define("COMPANY_URL2", "http://basicphp3.sanggong.net");		// URL2
//define("COMPANY_URL_MO", "http://");	// MOBILE URL
define("COMPANY_SSL_URL", "https://basicphp3.sanggong.net");	// SSL URL
define("DB_ENCRYPTION", "password");							// db 암호화방식



define("COMPANY_TEL", "");							// 대표 전화번호
define("COMPANY_FAX", "");							// 대표 팩스번호
define("COMPANY_ADDR", "");	// 대표 주소
define("CHARSET", "utf-8");										// 캐릭터셋
define("CREATE_YEAR", 2021);									// copyright

// SMS관련
define("SMS_TRANSMITTER", "");						// SMS 발신번호
define("SMS_CALLBACK", "");						// SMS 수신번호
define("SMS_KEYNO", "401");										// SMS 키값 (세팅필요)
define("SMS_USERID", "");								// SMS 유저아이디 (세팅필요)
define("SMS_USERNAME", "");							// SMS 사용자명
define("SMS_CNAME",	"");						// SMS 업체명
define("SMS_CONSULT_SEND",	true);								// 상담등록시 SMS발송여부

// email 관련


define("COMPANY_EMAIL", "sanggongdev@sanggong.co.kr");				// 대표 이메일
define("SMTP_HOST", "smtp.cafe24.com");						// 메일서버
define("SMTP_USER", "sanggongdev@sanggong.co.kr");								// 메일계정 아이디
define("SMTP_PASSWORD", "tkdrhd1121!");								// 메일계정 패스워드
define("EMAIL_FORM", "/include/emailForm.php");					// 이메일 기본 폼
define("SMTP_PORT" , 587);
define("SMTP_SECURE" , "tls");

// 웹로그 관련
define("WEBLOG_NUMBER", 1);									// 웹로그 사용자 키값
define("WEBLOG_NUMBER_MO", 2);									// 웹로그 사용자 키값(모바일)
define("IS_LOG", true);											// 웹로그 사용여부
define("LOG_VER", 1);											// 웹로그 버전 (1:기존, 2:리뉴얼)
define("LOG_TYPE", 0);											// 웹로그 버전 (0:무료, 1:유료)

define("LOGIN_AFTER_PAGE", "/index.php");					// 로그인 후 페이지
define("START_PAGE", "/admin/dashboard/index.php");	// 관리자 로그인 후 첫페이지
define("EDITOR_UPLOAD_PATH", "/upload/editor/");				// editor 이미지 업로드 경로
define("EDITOR_MAXSIZE", 5*1024*1024);							// editor 이미지 업로드 최대사이트 (50MB)

define("CHECK_REFERER", true);									// 레퍼러값 체크여부
define("REFERER_URL", "basicphp3");								// 레퍼러 비교 도메인(www 제외)

// 로그관련
define("IS_LOGFILE", true);										// 로그 사용여부 (www/log 생성필요, 파일은 날짜별로 자동생성)
define("LOG_PATH", $_SERVER['DOCUMENT_ROOT']."/log");			// 로그파일 경로
define("LOG_FILENAME", "_log.txt");								// 로그파일명 (날짜_log.txt)
define("LOG_PAGE_CHAR", "UTF-8");								// 로그작성시 페이지의 캐릭터셋
define("LOG_SERVER_CHAR", "EUC-KR");							// 로그작성시 서버의 캐릭터셋

// 주문하기
define("ORDER_PRICE", 15000);									// 식단금액
define("SALAD_PRICE", 4000);									// 샐러드금액
define("POINT_PER_CARD", 0.01);									// 포인트적립%(신용카드)
define("POINT_PER_ACCOUNT", 0.03);								// 포인트적립%(무통장)
define("MIN_ORDER", 6);											// 최소주문수량
?>