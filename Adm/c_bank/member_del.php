<?
include "../common/user_function.php";
include "../common/dbconn.php";
include "../inc/adm_chk.php";
if (isset($_SESSION["admin_id"])) {
	$admin_id = $_SESSION["admin_id"];
} else {
	$admin_id = "";
}
$c_no = sqlfilter($_REQUEST["c_no"]);
$m_no = $_REQUEST["m_no"];
$query_pdo2 = "SELECT c_account, c_bank, c_banknum FROM $table_bank  WHERE  c_no = '$c_no'";
$stmt = $pdo->prepare($query_pdo2);
$stmt->execute();

$m_module = "Company Bank Account";
$m_type = "Delete";


while($row = $stmt->fetch(PDO::FETCH_ASSOC))
{
$m_modified = "Deleted the bank details of:" . " " . $row['c_account']. " " . "on" .  " " . $row['c_bank'] . " " . "with the account number of" . " " . $row['c_banknum'];

}
$m_signdate = time();

		$keyfield = sqlfilter($_REQUEST["keyfield"]);
		$key = sqlfilter($_REQUEST["key"]);
		$page = sqlfilter($_REQUEST["page"]);
		
		$query_pdo = "UPDATE $table_bank SET  c_delete = 1  WHERE  c_no = :c_no";

		$stmt = $pdo->prepare($query_pdo);
		$stmt->bindValue(":c_no", $c_no);
		$result = $stmt->execute();
		if (!$result) {
 			error("QUERY_ERROR");
 			exit;
 		}

$encoded_key = urlencode($key);
//echo "<meta http-equiv='Refresh' content='0; URL=member.php?keyfield=$keyfield&key=$encoded_key&page=$page'>";
echo("<meta http-equiv='Refresh' content='0; URL=member.php'>");  

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