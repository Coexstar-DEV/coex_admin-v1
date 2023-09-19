<meta charset="utf-8">
<?
#####################################################################

include "../common/dbconn.php";
include "../common/user_function.php";

//m_userno,m_id,m_passwd,m_name,m_level,m_mostaddress,m_krwtotal,m_confirm,m_signdate,m_block,m_key,m_email,m_handphone,m_webinfo,m_contury,m_ip,m_updatedate,m_measge,m_admmemo,m_device,m_dis

####임시

$m_mostaddress = "123";
$m_krwtotal = "456";
$m_signdate = time();
$m_key ="123";
if(isset($_SERVER['HTTP_USER_AGENT'])){
$m_webinfo = $_SERVER['HTTP_USER_AGENT']; 
}else{
$m_webinfo="";
}
if(isset($_SERVER['REMOTE_ADDR'])){
$m_ip = $_SERVER['REMOTE_ADDR'];
}else{
$m_ip="";
}
$m_updatedate = time();
$m_device ="android";

###임시
//if(preg_match("([^[:space:]]+)", $m_email) && (!preg_match("(^[_0-9a-zA-Z-]+(\.[_0-9a-zA-Z-]+)*@[0-9a-zA-Z-]+(\.[0-9a-zA-Z-]+)*$)", $m_email))  ) {
//   error("INVALID_EMAIL");   
//   exit;
//}

if(!preg_match("(^[0-9a-zA-Z]{4,}$)", $m_passwd) && $m_passwd!="") {
   error("INVALID_PASSWD");
   exit;
}



//$oldpass=trim($oldpass);
$m_passwd=trim($m_passwd);
$m_passwd2=trim($m_passwd2);
$m_signdate = time();
//$address = $address.$address1;

if($passwd!="") {
	if(!preg_match("([a-z0-9]{3,}$)", $m_passwd)) {
		echo "<script language=javascript> alert('".M_INPUT_PWD."'); </script>";
		echo "<script language=javascript> history.go(-1); </script>";
		exit;
	}
	if($m_passwd!=$m_passwd2) {
		echo "<script language=javascript> alert('".M_PWD_CONFIRM3."'); </script>";
		echo "<script language=javascript> history.go(-1); </script>";
		exit;
	}
	$query = "SELECT '$m_passwd'";
	$result = mysql_query($query,$dbconn);
	$row = mysql_fetch_row($result);
	$newpasswd = $row[0];
	
} else {
	$newpasswd = $real_pass;
}


//데이터베이스에 입력값을 삽입한다
	$query="INSERT INTO $member";
	$query=$query."(";
	$query=$query."m_userno,m_id,m_passwd,m_name,m_level,m_mostaddress,m_krwtotal,m_confirm,m_signdate,m_block";
	$query=$query.",m_key,m_email,m_handphone,m_webinfo,m_contury,m_ip,m_updatedate,m_measge,m_admmemo,m_device,m_dis";
	$query=$query.")";
	$query=$query."VALUES";
	$query=$query."(";
	$query=$query."'','$m_id','$m_passwd','$m_name','$m_level','$m_mostaddress','$m_krwtotal','$m_confirm','$m_signdate','$m_block'";
	$query=$query.",'$m_key','$m_key','$m_handphone','$m_webinfo','$m_contury','$m_ip','$m_updatedate','$m_measge','$m_admmemo','$m_device','$m_dis'";
	$query=$query.")";

$result = mysql_query($query,$dbconn);




if($result) {
	$query = "SELECT m_userno from '$member' where m_id = '$m_id' ";
	$result = mysql_query($query,$dbconn);
	$row = mysql_fetch_row($result);
	$m_userno = $row[0];


// 리스트 출력화면으로 이동한다

	$query="INSERT INTO $member";
	$query=$query."(";
	$query=$query."m_no,m_userno,m_id,m_address";
	$query=$query.")";
	$query=$query."VALUES";
	$query=$query."(";
	$query=$query."'','$m_userno','$m_id','$m_mostaddress'";
	$query=$query.")";

$result = mysql_query($query,$dbconn);

if(isset($_REQUEST["key"])){
$key = $_REQUEST["key"];
}else{
$key="";
}

if($result) {
		$encoded_key = urlencode($key);
	echo("<meta http-equiv='Refresh' content='0; URL=member.php?keyfield=$keyfield&key=$encoded_key&page=$page'>");   
}else{
	   	error("QUERY_ERROR");
	exit;
}

} else {
   	error("QUERY_ERROR");
	exit;
}

mysql_close($dbconn);
?>
