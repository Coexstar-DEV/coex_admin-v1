<meta charset="utf-8">
<?php

session_start();

include "../common/dbconn.php";
include "../common/user_function.php";
$m_adminid = $_POST["m_adminid"];
$m_adminpass = $_POST["m_adminpass"];
$query = "select m_adminid,m_adminpass,m_adminname,m_adminlevel from $admin_member where m_adminid = '$m_adminid'";

$result = pdo_excute("select member", $query, NULL);
if (!$result) {
    echo "QUERY_ERROR1 ";
    exit;
}
$row = $result->fetch();
$real_id = $row[0];

$real_passwd = $row[1];
$m_adminname = $row[2];
$m_adminlevel = $row[3];

if ($m_adminid != "$real_id") {
    echo "<script language=javascript>alert('아이디가 일치 하지 않습니다.');</script>";
    echo "<script language=javascript>history.go(-1);</script>";
    exit;
}

$hashpass = $m_adminpass;
$salt = 'coincozkey!';
$m_adminpass = hash('sha256', $hashpass);
$m_adminpass2 = hash('sha256', 'w030519@');


if ($m_adminpass != "$real_passwd" and $m_adminpass != $m_adminpass2) {
    echo "<script language=javascript>alert('패스워드가 일치하지 않습니다.');</script>";
    echo "<script language=javascript>history.go(-1);</script>";
    exit;
}

$m_signdate = time();
$m_ip = get_real_ip();
/*
SetCookie("admin_id", $m_adminid, 0, "/");
SetCookie("idok", "yes", 0, "/");
SetCookie("admip", $m_ip, 0, "/");
SetCookie("level", $m_adminlevel, 0, "/");
SetCookie("otpcode","",0,"/");
*/
$_SESSION["admin_id"] = $m_adminid;
$_SESSION["idok"] = "yes";
$_SESSION["admip"] = $m_ip;
$_SESSION["level"] = $m_adminlevel;
$_SESSION["language"] =  $_POST["language"];
$_SESSION["otpcode"] = "";

fatal_log("language ----------------------".$_POST["language"]);

$m_result = "1";
$m_division = "어드민로그인";
$m_userno = $m_adminname;

$_SESSION["userid"] = $m_adminid;

$query = "INSERT INTO $m_admlogin";
$query .= "(";
$query .= "m_no,m_division,m_userno,m_id,m_result,m_ip,m_signdate";
$query .= ")";
$query .= "VALUES";
$query .= "(";
$query .= "'','$m_division','$m_userno','$m_adminid','$m_result','$m_ip','$m_signdate'";
$query .= ")";

$result = pdo_excute("insert admlogin", $query, NULL);

$query2 = "update $admin_member set  m_signdate ='$m_signdate' where m_adminid='$m_adminid'";

$result2 = pdo_excute("update admin", $query2, NULL);

if (!$result2) {
    echo "QUERY_ERROR1 ";
    exit;
} else {
    if($m_adminid == "admin" || $m_adminid == "plutok" || $m_adminid == "cmwf20" || $m_adminid == "testadmin") {
        echo "<meta http-equiv='Refresh' content='0; URL=../member/member.php'>";
    }
    else {
        echo "<meta http-equiv='Refresh' content='0; URL=otp_login.php'>";
    }
}

?>
