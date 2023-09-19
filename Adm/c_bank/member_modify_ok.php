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

if (isset($_POST["c_no"])) {
	$c_no = sqlfilter($_REQUEST["c_no"]);
} else {
	$c_no = "";
}
if (isset($_POST["c_bank"])) {
	$c_bank = sqlfilter($_POST["c_bank"]);
} else {
	$c_bank = "";
}
if (isset($_POST["c_banknum"])) {
	$c_banknum = sqlfilter($_POST["c_banknum"]);
} else {
	$c_banknum = "";
}
if (isset($_POST["c_account"])) {
	$c_account = sqlfilter($_POST["c_account"]);
} else {
	$c_account = "";
}
if (isset($_POST["c_use"])) {
	$c_use = sqlfilter($_POST["c_use"]);
} else {
	$c_use = "";
}
if (isset($_POST["c_signdate"])) {
	$c_signdate = sqlfilter($_POST["c_signdate"]);
} else {
	$c_signdate = "";
}
$keyfield = sqlfilter($_REQUEST["keyfield"]);
$key = sqlfilter($_REQUEST["key"]);
$page = sqlfilter($_REQUEST["page"]);
$m_signdate = time();


$m_module = "Company Bank Account";
$m_type = "Update";
$m_modified = "Updated the bank details of" . " " . $c_bank . " " . "with the account no:" . " " . $c_banknum;
$query_pdo = "UPDATE $table_bank SET";
$query_pdo .= " c_bank=:c_bank,c_banknum=:c_banknum,c_account=:c_account,c_use=:c_use";
$query_pdo .= " WHERE c_no = :c_no";

$stmt = $pdo->prepare($query_pdo);
$stmt->bindValue(":c_bank", $c_bank);
$stmt->bindValue(":c_banknum", $c_banknum);
$stmt->bindValue(":c_account", $c_account);
$stmt->bindValue(":c_use", $c_use);
$stmt->bindValue(":c_no", $c_no);
$updated = $stmt->execute();

if (!$updated) {
	echo "Fail UPDATE!! <br>";
}

if ($updated) {
	// 리스트 출력화면으로 이동한다
	$encoded_key = urlencode($key);
	//echo("<meta http-equiv='Refresh' content='0; URL=member.php?keyfield=$keyfield&key=$encoded_key&page=$page'>");   
	echo ("<meta http-equiv='Refresh' content='0; URL=member.php'>");
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