<meta charset="utf-8">
<?


if($userid=="") {
echo "<script>alert('로그인 해주세요.')</script>";
echo "<script>location.href='../sub04/login.html'</script>";
exit;
}

if($userid =="admin"){
	echo "<script language=javascript>location.href='../sub05/board01_list.html?Sub_No=1';</script>";
	exit;
}


$querym = "SELECT m_block,m_updatedate FROM m_member WHERE m_id='$userid'";
$resultm = mysql_query($querym,$dbconn);
$rowm = mysql_fetch_row($resultm);
$m_block = $rowm[0];
$m_updatedate = $rowm[1];

if($m_block =="1"){
	echo "<script language=javascript>location.href='../sub04/logout.php';</script>";
	exit;
}else if($m_block =="2"){
	echo "<script language=javascript>location.href='../sub04/logout.php';</script>";
	exit;

}
$request_uri = $_SERVER['REQUEST_URI'];

$start_time = mktime(date("H"),date("i"),date("s"),date("m")-3,date("d"),date("Y"));
$now_time = time();
if($m_updatedate =="" || ($m_updatedate < $start_time) && $request_uri !="/sub04/sub01.html"){
//	echo "<script>alert('비밀번호 변경 후에 이용가능합니다.');</script>";
//	echo "<meta http-equiv='refresh' content='0;url=../sub04/sub01.html'>";
//	exit;
}
?>