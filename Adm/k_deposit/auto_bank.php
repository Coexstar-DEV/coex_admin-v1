<?php

include "../common/dbconn.php";
include "../common/user_function.php";


$LOG_LEVEL = 1;
$LOG_TAG = basename(__FILE__);

if(isset($_POST["member_key"])){
    $member_key = $_POST["member_key"];
}else{
    $member_key = "";
}
if(isset($_POST["deposit_krw"])){
    $deposit_krw = $_POST["deposit_krw"];
}else{
    $deposit_krw = "";
}
if(isset($_POST["deposit_time"])){
    $deposit_time = $_POST["deposit_time"];
}else{
    $deposit_time = "";
}
if(isset($_POST["m_ip"])){
    $m_ip = $_POST["m_ip"];
}else{

    $m_ip = "";
}

$m_div = "0";
$m_div_name = "KRW";

//$result = array("member_key"=>$member_key,"deposit_krw"=>$deposit_krw,"deposit_time"=>$deposit_time);
//$result2 = json_encode($result);
//echo "2";
//exit;
//member_key 값으로 회원정보 조회

$m_name = preg_replace('/\d/', '', $member_key);
$m_phone = substr($member_key, -4);
//$member_key = $m_name.$m_phone;
$m_member = $member;
//$query_member = "SELECT m_userno,m_id,m_email,m_name,m_handphone FROM $m_member where m_name='$m_name' ";
$query_member = "SELECT count(*) FROM $m_member where m_name='$m_name' and m_handphone like '%$m_phone'";
$stmt = pdo_excute("select count", $query_member, NULL);
$row = $stmt->fetch();

$count = $row[0];
err_log("MON[$__LINE__ ] count:$count: name:$m_name, phone:$m_phone, memberr_key:$member_key");
if($count != 1){ //중복입금시
    if ($count > 1) 
        echo "2";
    else
        echo "11";

    fatal_log("Duplicate deposit id - failed:1,2:".__LINE__);
    exit;
}


$query_member = "SELECT m_userno,m_id,m_email,m_name,m_handphone FROM $m_member where m_name='$m_name' and m_handphone like '%$m_phone'";
$result_member = pdo_excute("select", $query_member, NULL);
$row_member = $result_member->fetch();
$m_no = $row_member["0"];
$m_id = $row_member["1"];
$m_email = $row_member["2"];
$m_name = $row_member["3"];
$m_handphone = $row_member["4"];
//member_key 값으로 회원정보 조회


err_log("MON[$__LINE__ ] m_id:$m_id, m_no:$m_no, name:$m_name, phone:$m_handphone, duedate:$deposit_time");
if( is_empty($m_no) ){      //없는 member_key
    fatal_log("Not found depsit(3) id:, failed:3-".__LINE__);
    echo "3";
    exit;
}

// 같은시간 입금내역 있으면 막기
$query = "SELECT count(k_no) FROM $table_k_deposit where k_duedate = '$deposit_time' and k_id='$m_id' ";
$result = pdo_excute("select", $query, NULL);
$row = $result->fetch();
$count = $row[0];

if($count>0){ //중복입금시
    fatal_log("Duplicate deposit(12): $m_id, duedate:$deposit_time ".__LINE__);
    echo "12";
    exit;
}
// 같은시간 입금내역 있으면 막기


//잔고 입금처리
$query = "SELECT m_cointotal,m_coinuse,m_restcoin FROM $m_bankmoney WHERE m_id='$m_id' and m_div = $m_div order by m_no desc,m_signdate desc ";
$result = pdo_excute("select", $query, NULL);
$row = $result->fetch();
$m_cointotal = $row[0];
$m_coinuse = $row[1];
$m_restcoin = $row[2];

$m_cointotal = bcadd($m_cointotal, $deposit_krw, 8);
$m_restcoin = bcsub($m_cointotal, $m_coinuse, 8);

err_log("m_bankmoney m_id:$m_id, m_no:$m_no, name:$m_name, deposit_krw:$deposit_krw, duedate:$deposit_time");
$query2 = "INSERT INTO $m_bankmoney ";
$query2 .= "(";
$query2 .= "m_div,m_userno,m_id,m_cointotal,m_coinuse,m_restcoin,m_signdate";
$query2 .= ")";
$query2 .= " VALUES ";
$query2 .= "(";
$query2 .= "'$m_div','$m_no','$m_id','$m_cointotal','$m_coinuse','$m_restcoin',$deposit_time";
$query2 .= ")";
$result2 = pdo_excute("select", $query2, NULL);
if(!$result2){  //DB저장 실패
    fatal_log("Failed to save m_bankmoney.".__LINE__);
    echo "2";
    exit;
}
//잔고 입금처리


// 입금내역처리
err_log("k_deposit m_id:$m_id, m_no:$m_no, name:$m_name, deposit_krw:$k_depositname, duedate:$deposit_time");
$query_deposit = "INSERT into $table_k_deposit (k_orderprice,k_depositprice,k_depositname,k_payment,k_check,k_ordername,k_email,k_tel,k_ip,k_signdate,k_userno,k_id,k_duedate)";
$query_deposit .= " values('$deposit_krw','$deposit_krw','$member_key','0','1','$m_name','$m_email','$m_handphone','$m_ip','$deposit_time','$m_no','$m_id','$deposit_time')";

$resulb_deposit = pdo_excute("select", $query_deposit, NULL);

//입금처리
if(!$resulb_deposit){       //DB저장 실패
    fatal_log("save table_k_deposit failed:3-".__LINE__);
    echo "3";
    exit;
}else{                  //DB저장 성공
    err_log("Deposit succeed:99");
    echo "99";
    exit;
}
// 입금내역처리
?>


















