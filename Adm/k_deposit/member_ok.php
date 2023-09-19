<meta charset="utf-8">
<?
include "../common/dbconn.php";
include "../common/user_function.php";
include "../inc/adm_chk.php";

if (isset($_POST["k_no"])) {
	$k_no = $_POST["k_no"];
} else {
	$k_no = "";
}
if (isset($_POST["k_orderprice"])) {
	$k_orderprice = $_POST["k_orderprice"];
} else {
	$k_orderprice = "";
}
if (isset($_POST["k_depositprice"])) {
	$k_depositprice = $_POST["k_depositprice"];
} else {
	$k_depositprice = "";
}
if (isset($_POST["k_sellprice"])) {
	$k_sellprice = $_POST["k_sellprice"];
} else {
	$k_sellprice = "";
}
if (isset($_POST["k_returnprice"])) {
	$k_returnprice = $_POST["k_returnprice"];
} else {
	$k_returnprice = "";
}
if (isset($_POST["k_depositname"])) {
	$k_depositname = $_POST["k_depositname"];
} else {
	$k_depositname = "";
}
if (isset($_POST["k_payment"])) {
	$k_payment = $_POST["k_payment"];
} else {
	$k_payment = "";
}
if (isset($_POST["k_ordername"])) {
	$k_ordername = $_POST["k_ordername"];
} else {
	$k_ordername = "";
}
if (isset($_POST["k_email"])) {
	$k_email = $_POST["k_email"];
} else {
	$k_email = "";
}
if (isset($_POST["k_tel"])) {
	$k_tel = $_POST["k_tel"];
} else {
	$k_tel = "";
}
if (isset($_POST["k_ordermemo"])) {
	$k_ordermemo = $_POST["k_ordermemo"];
} else {
	$k_ordermemo = "";
}
if (isset($_POST["k_check"])) {
	$k_check = $_POST["k_check"];
} else {
	$k_check = "";
}
if (isset($_POST["k_duedate"])) {
	$k_duedate = $_POST["k_duedate"];
} else {
	$k_duedate = "";
}
if (isset($_POST["k_checkdate"])) {
	$k_checkdate = $_POST["k_checkdate"];
} else {
	$k_checkdate = "";
}
if (isset($_POST["k_cardcancle"])) {
	$k_cardcancle = $_POST["k_cardcancle"];
} else {
	$k_cardcancle = "";
}
if (isset($_POST["k_return"])) {
	$k_return = $_POST["k_return"];
} else {
	$k_return = "";
}
if (isset($_POST["k_ip"])) {
	$k_ip = $_POST["k_ip"];
} else {
	$k_ip = "";
}
if (isset($_POST["k_delete"])) {
	$k_delete = $_POST["k_delete"];
} else {
	$k_delete = "";
}
if (isset($_POST["k_admmemo"])) {
	$k_admmemo = $_POST["k_admmemo"];
} else {
	$k_admmemo = "";
}
if (isset($_POST["k_modicont"])) {
	$k_modicont = $_POST["k_modicont"];
} else {
	$k_modicont = "";
}
if (isset($_POST["k_signdate"])) {
	$k_signdate = $_POST["k_signdate"];
} else {
	$k_signdate = "";
}
if (isset($_POST["k_userno"])) {
	$k_userno = $_POST["k_userno"];
} else {
	$k_userno = "";
}
if (isset($_POST["k_id"])) {
	$k_id = $_POST["k_id"];
} else {
	$k_id = "";
}

####임시
$query = "select m_userno from $member where m_id = '$k_id'";
//echo $query;
//exit;
$result = mysqli_query($dbconn,$query);
if (!$result) {
	echo "QUERY_ERROR1 ";
	exit;
}
$row = mysqli_fetch_row($result);
$k_userno= $row[0];

if($k_userno==""){
	echo "<script>alert('존재하지 않는 아이디입니다.');history.back();</script>";
	exit;
}


$k_signdate = time();
if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
	$k_ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
} else {
	$k_ip = "";
}

//데이터베이스에 입력값을 삽입한다
$query = "INSERT INTO $table_k_deposit";
$query .= "(";
$query .= "k_no,k_orderprice,k_depositprice,k_sellprice,k_returnprice,k_depositname,k_payment,k_ordername,k_email,k_tel,k_ordermemo,k_check,k_duedate,k_checkdate,k_cardcancle,k_return,k_ip,k_delete,k_admmemo,k_modicont,k_signdate,k_userno,k_id";
$query .= ")";
$query .= "VALUES";
$query .= "(";
$query .= "'','$k_orderprice','$k_depositprice','$k_sellprice','$k_returnprice','$k_depositname','$k_payment','$k_ordername','$k_email','$k_tel'";
$query .= ",'$k_ordermemo','$k_check','$k_duedate','$k_checkdate','$k_cardcancle','$k_return','$k_ip','$k_delete','$k_admmemo','$k_modicont','$k_signdate','$k_userno','$k_id'";
$query .= ")";
$result = pdo_excute("INSERT deposit", $query, NULL);

if (isset($_REQUEST["key"])) {
	$key = $_REQUEST["key"];
} else {
	$key = "";
}

if ($result) {
	$encoded_key = urlencode($key);
	echo ("<meta http-equiv='Refresh' content='0; URL=member.php?keyfield=$keyfield&key=$encoded_key&page=$page'>");
} else {
	error("QUERY_ERROR");
	exit;
}
?>