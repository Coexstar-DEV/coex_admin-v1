<meta charset="utf-8">
<?
include "../common/dbconn.php";
include "../common/user_function.php";
include "../inc/adm_chk.php";

if (isset($_SESSION["admin_id"])) {
	$admin_id = $_SESSION["admin_id"];
} else {
	$admin_id = "";
}

if (isset($_POST["c_coin"])) {
	$c_coin = sqlfilter($_POST["c_coin"]);
} else {
	$c_coin = "";
}
if (isset($_POST["c_level"])) {
	$c_level = sqlfilter($_POST["c_level"]);
} else {
	$c_level = "";
}
if (isset($_POST["c_deposit"])) {
	$c_deposit = sqlfilter($_POST["c_deposit"]);
} else {
	$c_deposit = "";
}
if (isset($_POST["c_withdraw"])) {
	$c_withdraw = sqlfilter($_POST["c_withdraw"]);
} else {
	$c_withdraw = "";
}
if (isset($_POST["c_limit"])) {
	$c_limit = sqlfilter($_POST["c_limit"]);
} else {
	$c_limit = "";
}
if (isset($_POST["c_signdate"])) {
	$c_signdate = sqlfilter($_POST["c_signdate"]);
} else {
	$c_signdate = "";
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

$c_signdate = time();
$m_signdate = time();
$m_module = "Deposit/Withdrawal Limit";
$m_type = "Insert";




$query_pdo = "INSERT INTO $table_level";
$query_pdo .= "(";
$query_pdo .= "c_no,c_coin,c_level,c_deposit,c_withdraw,c_limit,c_signdate";
$query_pdo .= ")";
$query_pdo .= "VALUES";
$query_pdo .= "(";
$query_pdo .= "'',:c_coin,:c_level,:c_deposit,:c_withdraw,:c_limit,:c_signdate";
$query_pdo .= ")";
$stmt = $pdo->prepare($query_pdo);
$stmt->bindValue(":c_coin", $c_coin);
$stmt->bindValue(":c_level", $c_level);
$stmt->bindValue(":c_deposit", $c_deposit);
$stmt->bindValue(":c_withdraw", $c_withdraw);
$stmt->bindValue(":c_limit", $c_limit);
$stmt->bindValue(":c_signdate", $c_signdate);
$inserted = $stmt->execute();

if ($inserted) {
	// 리스트 출력화면으로 이동한다
	$encoded_key = urlencode($key);
	//echo("<meta http-equiv='Refresh' content='0; URL=member.php?keyfield=$keyfield&key=$encoded_key&page=$page'>");   
	echo ("<meta http-equiv='Refresh' content='0; URL=member.php'>");
} else {
	error("QUERY_ERROR");
	exit;
}


$query_pdo2 = "SELECT c_no,c_coin FROM $table_setup  WHERE  c_no = '$c_coin'";
$stmt = $pdo->prepare($query_pdo2);
$stmt->execute();
while($row = $stmt->fetch(PDO::FETCH_ASSOC))
{
	$m_modified = "Added the Deposit/Withdrawal Information" . " " . "CoinNo:" . $row['c_coin'] . "//". " " . "Level:" . $c_level . "//" . " " . "MaxDeposit Limit:" . $c_deposit . "//" . "MaxWithdrawl Limit:" . " " . $c_withdraw . "//" . " " . "MaxDaily Limit:" . $c_limit ;

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