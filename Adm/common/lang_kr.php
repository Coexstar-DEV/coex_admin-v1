<?php

/***********************************
 * MEMBER 
************************************/

// Adm/member/member.php
define("M_MANAGER", "관리자");
define("M_MANAGEMENT", "관리");
define("M_SEARCH", "검색");
define("M_DUPE", "중복");
define("M_MEMBER", "회원");
define("M_SIGN", "등록");
define("M_DOWNLOAD", "엑셀다운로드");
define("M_OPTION", "옵션");

define("M_WATCHLIST", "감시자 명단");
define("M_AUDIT", "감사추적길");
define("M_EXCHANGE", "환전");
define("M_PRIVILEGE", "권한");
define("M_NO", "번호");
define("M_CONTENT", "내용");
define("M_ID", "아이디");
define("M_LANG", "영어");
define("M_IP", "아이피");
define("M_LEVEL", "레벨");
define("M_CURRENT", "현재");
define("M_NAME", "이름");
define("M_PHONE", "휴대폰");
define("M_AUTH", "본인인증");
define("M_DATE", "일");
define("M_DATE1", "날짜");
define("M_DEL", "삭제");
define("M_DEL_STATUS", "삭제상태");
define("M_DEL_YES", "삭제");
define("M_DEL_NO", "미삭제");
define("M_STATUS", "상태");
define("M_TRADE_STATUS", "거래");
define("M_FUND_STATUS", "입출");
define("M_MAIL_AUTH", "메일인증");
define("M_ADMIN", "어드민");
define("M_COIN", "코인");
define("M_MARKET", "마켓");
define("M_TYPE", "타입");
define("M_ALTCOIN", "알트");
define("M_ALTCOIN2", "알트2");
define("M_ETH", "이더");
define("M_TOKEN", "토큰");
define("M_RIPPLE", "리플");
define("M_EOS", "이오스");
define("M_TRON", "트론");
define("M_TIME", "시간");
define("M_ASKPRICE", "호가");
define("M_RANK", "정렬순위");
define("M_ISSUE", "발행");
define("M_YEAR", "년도");
define("M_SITE", "사이트");
define("M_WHITEPAPTER", "백서");
define("M_INTRO", "소개");
define("M_REASON", "사유");
define("M_DEPWITH", "입출금");
define("M_STOP", "정지");
define("M_NORMAL", "정상");
define("M_TRADING", "거래");
define("M_EXCHANGE1", "거래소");

define("M_SIGN_DATE", M_SIGN.M_DATE);
define("M_REGISTER", M_MEMBER.M_SIGN);
define("M_REGISTRATION", "등록");

define("M_KRW", "PHP");
define("M_DEPOSIT", "입금");
define("M_DAYLY", "일일");
define("M_LIMIT", "한도");
define("M_WITHDRAW", "출금");
define("M_DEPOSITOR", "입금자");
define("M_DONE", "완료");
define("M_BUY", "구매");
define("M_SELL", "판매");
define("M_HIS", "내역");
define("M_ORDER", "주문");
define("M_ORDERER", "주문자");
define("M_REFUND", "환불");
define("M_PRICE", "액");
define("M_TOTAL", "총");
define("M_ALL", "전체");
define("M_PRICE1", "가");
define("M_AMOUNT", "량");
define("M_CLOSED", "체결");
define("M_WAIT", "대기");
define("M_PART", "부분");
define("M_CANCEL", "취소");
define("M_FEE", "수수료");
define("M_MIN", "최소");
define("M_MAX", "최대");
define("M_RECV", "받기");
define("M_RECVER", "수신자");
define("M_ACQUIRE", "취득");
define("M_TRANS", "전송");

define("M_PAY", "결제수단");
define("M_PAY_1", "무통장");
define("M_PAY_2", "카드");
define("M_PAY_3", "모바일");
define("M_PAY_4", "가상계좌");

define("M_AUTH_YES", "인증");
define("M_AUTH_NO", "미인증");

define("M_BLOCK_YES", "차단");
define("M_BLOCK_NO", "미차단");

define("M_COUNTRY_NAME", "국가");

define("M_MANUAL", "수동");
define("M_PROCESS", "처리");

// Adm/member/member_write.php
define("M_INPUT_ID", "아이디를 입력하세요!");
define("M_INPUT_PWD", "비밀번호는 최소 4자 이상 입력하세요!");
define("M_PWD_CONFIRM", "비밀번호확인이 일치하지 않습니다.");
define("M_INPUT_NAME", "이름을 입력하세요!");
define("M_INPUT_LEVEL", "레벨을 입력하세요!");
define("M_PWD", "비밀번호");
define("M_CONFIRM", "확인");
define("M_NOT_CONFIRM", "미확인");
define("M_CONFIRM_MSG1", "정말 삭제하시겠습니까?");
define("M_CONFIRM_MSG2", "정말 변경하시겠습니까?");
define("M_PWD_DESC", "4~10자 이내의 영문, 숫자");
define("M_PWD_CONFIRM1", "비밀번호 확인");
define("M_PWD_CONFIRM2", "비밀번호를 다시 한번 입력하십시오.");
define("M_PWD_CONFIRM3", "비밀번호가 동일하지 않습니다.");
define("M_PWD_CONFIRM4", "새 비밀번호 확인를 입력하세요.");
define("M_PWD_CONFIRM5", "새 비밀번호와 새 비밀번호확인이 일치하지 않습니다.");

define("M_NAME1", "본명");
define("M_CHANGER", "정보변경자");
define("M_MAIL", "이메일");
define("M_COUNTRY", "국가코드");

define("M_ADMIN_MEMO", "관리자메모");
define("M_ACCOUNT", "계좌번호");
define("M_SETTING", "설정");
define("M_ENV", "환경");
define("M_COMPANY", "회사");
define("M_ACCOUNT_OWNER", "예금주");

define("M_BANK", "은행명");
define("M_BIRTH", "생년월일");
define("M_REFER", "추천인");

define("M_TRADE0", "지정가");
define("M_TRADE1", "시장가");

define("M_PROPOSED", "신청한");
define("M_REQUEST", "요청");
define("M_APPLY", "적용여부");
define("M_APPLY_NO", "미적용");
define("M_APPLY_YES", "적용");
define("M_PAYMENT", "지급");
define("M_PAY_YES", "지급됨");
define("M_PAY_NO", "미지급");
define("M_MEMO", "메모");
define("M_USE", "사용여부");
define("M_USING", "사용중");
define("M_USAGE", "용도");
define("M_REST", "잔고");
define("M_USE_YES", "사용");
define("M_USE_NO", "사용안함");
define("M_DIVISION", "구분");
define("M_ORDER_COST", "주문수량");
define("M_CLOSE_COST", "완료수량");
//define("M_REGISTER", "추천인");

//define("M_DEL", "삭제");

// top menu
define("M_DASHBOARD", "대시보드");
define("M_OP_MANAGEMENT", "운영관리");
define("M_MEM_MANAGEMENT", "회원관리");
define("M_TRADING_MANAGEMENT", "거래관리");
define("M_INOUT_MANAGEMENT", "입출관리");
define("M_MASTER_ACCOUNT", "마스터계좌");
define("M_LOGOUT", "로그아웃");

// left menu - member
define("M_MEMBER_TRACE", "회원계좌잔고추적");
define("M_MEMBER_AUTH_HIST", "회원인증내역");
define("M_LOG_HIST", "로그인정보");
define("M_MANAGER_LOG", "관리자로그인");
define("M_MEMBANK_STATUS", "회원자산현황");


// left menu - op
define("M_OP_ACCOUNT", "회사계좌설정");
define("M_OP_LEVEL_LIMIT", "레벨별입출한도설정");
define("M_OP_EXCHANGE", "거래소환경설정");
define("M_OP_FEE", "수수료 조회");
define("M_OP_NEW", "신규등록");

// left menu - order
define("M_ORDER_WAIT", "미체결내역");
define("M_ORDER_BUYSELL_HIST", "구매/판매내역");


define("M_MODIFICATION", "정보변경");
define("M_BACK", "뒤로가기");
define("M_VARIANCE", "변동분");
define("M_FILE", "파일");
define("M_EXIST", "있음");
define("M_AUTH_METHOD", "증명방법");
define("M_ADDRESS", "주소");
define("M_PROOF", "증명");
define("M_RESULT", "결과");
define("M_CODE", "코드");
define("M_MSG", "메세지");
define("M_LOGIN_OK", "로그인 성공(ID/PW)");
define("M_OTP_OK", "로그인성공(OTP)");
define("M_WRONG_ID", "아이디 틀림");
define("M_WRONG_PWD", "패스워드 틀림");
define("M_NOT_VERIFIED", "본인인증 안됨.");
define("M_NOT_OTP", "OTP 불일치");
define("M_CATEGORY", "카테고리");
define("M_LAST", "마지막");
define("M_NEXTPAGE", "다음페이지");
define("M_FIRST", "처음");
define("M_PREVPAGE", "이전페이지");
define("M_EXELDOWN", "엑셀다운로드");
define("M_MONTH", "월");
define("M_DAY", "일");
define("M_EMAIL", "Email");
define("M_WALLET", "지갑");
define("M_PENDING", "보류");

define("M_SMS_REQ", "SMS코드 요청");
define("M_SMS_OK", "로그인성공(SMS)");
define("M_NOT_SMS", "SMS코드 불일치");
?>