<meta charset="utf-8">
<?php

session_start();

include "../common/dbconn.php";
include "../common/user_function.php";
require_once '../otp/class/GoogleAuthenticator.php';
$m_otpcode = $_POST["m_otpcode"];
$m_adminid = $_SESSION["admin_id"];

$query = "SELECT A.m_adminname, M.m_secretkey FROM m_admin A INNER JOIN m_member M ON A.m_no = M.m_admin_no WHERE A.m_adminid = '".$m_adminid."'";

$result = pdo_excute("update member", $query, NULL);
if (!$result) {
    echo "QUERY_ERROR1 ";
    exit;
}
$row = $result->fetch();
$m_adminname = $row[0];
$secret = $row[1];

$ga = new PHPGangsta_GoogleAuthenticator();
$oneCode = $ga->getCode($secret);

if ($m_otpcode != $oneCode) {
    echo "<script language=javascript>alert('OTP 코드가 일치하지 않습니다.');</script>";
    echo "<script language=javascript>history.go(-1);</script>";
    exit;
}

$m_signdate = time();
$m_ip = get_real_ip();
$_SESSION["otpcode"] = $m_otpcode;

$m_result = "1";
$m_division = "어드민로그인(OTP)";
$m_userno = $m_adminname;

$query = "INSERT INTO $m_admlogin";
$query = $query . "(";
$query = $query . "m_no,m_division,m_userno,m_id,m_result,m_ip,m_signdate";
$query = $query . ")";
$query = $query . "VALUES";
$query = $query . "(";
$query = $query . "'','$m_division','$m_userno','$m_adminid','$m_result','$m_ip','$m_signdate'";
$query = $query . ")";

$result = pdo_excute("insert admlogin otp", $query, NULL);

$query2 = "update $admin_member set  m_signdate ='$m_signdate' where m_adminid='$m_adminid'";

$result2 = pdo_excute("update admin", $query2, NULL);

if (!$result2) {
    echo "QUERY_ERROR1 ";
    exit;
} else {
    echo "<meta http-equiv='Refresh' content='0; URL=../member/member.php'>";
}

?>
