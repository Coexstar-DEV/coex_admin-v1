<?


$query_ip = "SELECT m_ip FROM $m_login WHERE m_id='$_SESSION[userid]' order by m_signdate desc limit 1";

$result_ip = mysql_query($query_ip,$dbconn);
if(!$result_ip) {
  	error("QUERY_ERROR");
  	exit;
}
$row_ip = mysql_fetch_row($result_ip);
$ip_chk = $row_ip[0];
$now_ip =$_SERVER['HTTP_X_FORWARDED_FOR'];
$ip_chk_array = explode(",",$ip_chk);

$ip_chk=$ip_chk_array[0];

$now_ip_array = explode(",",$now_ip);

$now_ip=$now_ip_array[0];
if($ip_chk !=$now_ip){
	echo "<script>alert('비정상적인 접근입니다.  .')</script>";
	echo "<meta http-equiv='refresh' content='0;url=../sub04/logout.php'>";
	exit;
}
?>