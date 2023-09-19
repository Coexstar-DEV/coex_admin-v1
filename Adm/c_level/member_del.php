<?
include "../common/user_function.php";
include "../common/dbconn.php";

include "../inc/adm_chk.php";

if (isset($_SESSION["admin_id"])) {
	$admin_id = $_SESSION["admin_id"];
} else {
	$admin_id = "";
}


if (isset($_REQUEST["c_no"])) {
	$c_no = sqlfilter($_REQUEST["c_no"]);
} else {
	$c_no = "";
}
if (isset($_REQUEST["c_level"])) {
	$c_level = sqlfilter($_REQUEST["c_level"]);
} else {
	$c_level = "";
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
if (isset($_REQUEST["c_coin"])) {
	$c_coin = sqlfilter($_REQUEST["c_coin"]);
} else {
	
	$c_coin = "";
}




$query_pdo2 = "SELECT c_no,c_coin FROM $table_setup  WHERE  c_no = '$c_coin'";
$stmt = $pdo->prepare($query_pdo2);
$stmt->execute();
while($row = $stmt->fetch(PDO::FETCH_ASSOC))
{
	$m_modified = "Deleted the Deposit/Withdrawal Limit" . " " . "of". " " .  $row['c_coin']  . " " . "on Level" . " " . $c_level ;

}


$m_module = "Deposit/Withdrawal Limit";
$m_type = "Delete";

$m_signdate = time();

$query_pdo = "UPDATE $table_level SET c_delete = 1 WHERE c_no = '$c_no' ";
$stmt = $pdo->prepare($query_pdo);
$deleted = $stmt->execute();


// $query_pdo2 = "SELECT * FROM  $table_setup  WHERE c_no = '$c_coin' ";
// $stmt = $pdo->prepare($query_pdo2);
// $coin = $stmt->execute();


if (!$deleted) {
	error("QUERY_ERROR");
	exit;
}

$encoded_key = urlencode($key);
//echo("<meta http-equiv='Refresh' content='0; URL=member.php?keyfield=$keyfield&key=$encoded_key&page=$page'>");   
echo ("<meta http-equiv='Refresh' content='0; URL=member.php'>");
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
#####################################################################
 