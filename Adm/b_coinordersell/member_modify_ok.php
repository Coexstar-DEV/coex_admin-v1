<meta charset="utf-8">
<?
include "../common/dbconn.php";
include "../common/user_function.php";
include "../inc/adm_chk.php";

$adminlevel = $_SESSION["level"];
check_manager_level($adminlevel, ADMIN_LVL4);
if (isset($_SESSION["admin_id"])) {
	$admin_id = $_SESSION["admin_id"];
} else {
	$admin_id = "";
}
if (isset($_POST["b_no"])) {
	$b_no = sqlfilter($_POST["b_no"]);
} else {
	$b_no = "";
}
if (isset($_POST["b_div"])) {
	$b_div = sqlfilter($_POST["b_div"]);
} else {
	$b_div = "";
}
if (isset($_POST["b_pay"])) {
	$b_pay = sqlfilter($_POST["b_pay"]);
} else {
	$b_pay = "";
}
if (isset($_POST["b_state"])) {
	$b_state = sqlfilter($_POST["b_state"]);
} else {
	$b_state = "";
}
if (isset($_POST["b_ordermost"])) {
	$b_ordermost = sqlfilter($_POST["b_ordermost"]);
} else {
	$b_ordermost = "";
}
if (isset($_POST["b_orderfees"])) {
	$b_orderfees = sqlfilter($_POST["b_orderfees"]);
} else {
	$b_orderfees = "";
}
if (isset($_POST["b_closecost"])) {
	$b_closecost = sqlfilter($_POST["b_closecost"]);
} else {
	$b_closecost = "";
}
if (isset($_POST["b_closefees"])) {
	$b_closefees = sqlfilter($_POST["b_closefees"]);
} else {
	$b_closefees = "";
}
if (isset($_POST["b_orderprice"])) {
	$b_orderprice = sqlfilter($_POST["b_orderprice"]);
} else {
	$b_orderprice = "";
}
if (isset($_POST["b_pricetotal"])) {
	$b_pricetotal = sqlfilter($_POST["b_pricetotal"]);
} else {
	$b_pricetotal = "";
}
if (isset($_POST["b_closeprice"])) {
	$b_closeprice = sqlfilter($_POST["b_closeprice"]);
} else {
	$b_closeprice = "";
}
if (isset($_POST["b_closetotal"])) {
	$b_closetotal = sqlfilter($_POST["b_closetotal"]);
} else {
	$b_closetotal = "";
}
if (isset($_POST["b_no1"])) {
	$b_no1 = sqlfilter($_POST["b_no1"]);
} else {
	$b_no1 = "";
}
if (isset($_POST["b_userno"])) {
	$b_userno = sqlfilter($_POST["b_userno"]);
} else {
	$b_userno = "";
}
if (isset($_POST["b_id"])) {
	$b_id = sqlfilter($_POST["b_id"]);
} else {
	$b_id = "";
}
if (isset($_POST["b_delete"])) {
	$b_delete = sqlfilter($_POST["b_delete"]);
} else {
	$b_delete = "";
}
if (isset($_POST["b_closedate"])) {
	$b_closedate = sqlfilter($_POST["b_closedate"]);
} else {
	$b_closedate = "";
}
if (isset($_POST["b_ip"])) {
	$b_ip = sqlfilter($_POST["b_ip"]);
} else {
	$b_ip = "";
}
if (isset($_POST["b_signdate"])) {
	$b_signdate = sqlfilter($_POST["b_signdate"]);
} else {
	$b_signdate = "";
}
if (isset($_REQUEST["keyfield"])) {
	$keyfield = sqlfilter($_REQUEST["keyfield"]);
} else {
	$keyfield = "";
}
if (isset($_REQUEST["key"])) {
	$key = sqlfilter($_REQUEST["key"]);
} else {
	$key = "";
}
if (isset($_REQUEST["page"])) {
	$page = sqlfilter($_REQUEST["page"]);
} else {
	$page = "";
}
if (isset($_REQUEST["b_div_div"])) {
	$b_div_div = sqlfilter($_REQUEST["b_div_div"]);
} else {
	$b_div_div = "";
}
$m_signdate = time();

$m_module = "Coin Sell Order";
$m_type = "Update";
$m_modified = "Updated the sell transaction:" . " " . $b_no . " " . "of". " " . $b_id;
//데이터베이스에 입력값을 삽입한다
//pdo
$query_pdo = "UPDATE $table_ordersell SET";
$query_pdo .= " b_div=:b_div,b_state=:b_state,b_ordermost=:b_ordermost,b_orderfees=:b_orderfees,b_closecost=:b_closecost,b_closefees=:b_closefees,b_orderprice=:b_orderprice,b_pricetotal=:b_pricetotal,b_closeprice=:b_closeprice,b_closetotal=:b_closetotal,b_no1=:b_no1,b_userno=:b_userno,b_id=:b_id,b_delete=:b_delete,b_pay=:b_pay";
$query_pdo .= " WHERE b_no = :b_no";

$stmt = $pdo->prepare($query_pdo);
$stmt->bindValue(":b_div", $b_div);
$stmt->bindValue(":b_state", $b_state);
$stmt->bindValue(":b_ordermost", $b_ordermost);
$stmt->bindValue(":b_orderfees", $b_orderfees);
$stmt->bindValue(":b_closecost", $b_closecost);
$stmt->bindValue(":b_closefees", $b_closefees);
$stmt->bindValue(":b_orderprice", $b_orderprice);
$stmt->bindValue(":b_pricetotal", $b_pricetotal);
$stmt->bindValue(":b_closeprice", $b_closeprice);
$stmt->bindValue(":b_closetotal", $b_closetotal);
$stmt->bindValue(":b_no1", $b_no1);
$stmt->bindValue(":b_userno", $b_userno);
$stmt->bindValue(":b_id", $b_id);
$stmt->bindValue(":b_delete", $b_delete);
$stmt->bindValue(":b_pay", $b_pay);
$stmt->bindValue(":b_no", $b_no);
$updated = $stmt->execute();
//pdo end

if ($updated) {

	// 리스트 출력화면으로 이동한다
	$encoded_key = urlencode($key);
	echo ("<meta http-equiv='Refresh' content='0; URL=member.php?keyfield=$keyfield&key=$encoded_key&page=$page&b_div_div=$b_div&pay_type=$b_pay'>");
} else {
	error("QUERY_ERROR");
	exit;
}

$query_pdo3 = "INSERT INTO $admlogs";
$query_pdo3 .= "(";
$query_pdo3 .= "m_id, m_adminid, m_module, m_type, m_modified, m_signdate";
$query_pdo3 .= ")";
$query_pdo3 .= "VALUES";
$query_pdo3 .= "(";
$query_pdo3 .= "'',:m_adminid, :m_module, :m_type, :m_modified, :m_signdate";
$query_pdo3 .= ")";

$stmt = $pdo->prepare($query_pdo3);
$stmt->bindValue(":m_adminid", $admin_id);
$stmt->bindValue(":m_module", $m_module);
$stmt->bindValue(":m_type", $m_type);
$stmt->bindValue(":m_modified", $m_modified);
$stmt->bindValue(":m_signdate", $m_signdate);
$inserted2 = $stmt->execute();
?>