<?
include "../common/user_function.php";
include "../common/dbconn.php";
include "../inc/adm_chk.php";
if (isset($_SESSION["admin_id"])) {
	$admin_id = $_SESSION["admin_id"];
} else {
	$admin_id = "";
}
$m_no = $_REQUEST["m_no"];
$m_adminid = $_REQUEST["m_adminid"];
$m_ = $_REQUEST["m_no"];
$m_module = "Admin Management";
$m_type = "Delete";
$m_modified =  $m_adminid;
$m_signdate = time();


$query = "UPDATE $admin_member SET m_delete = 1 WHERE m_no = '$m_no'";
$stmt = $pdo->prepare($query);
$result = $stmt->execute();
if ($result) {
	
}else {
    error("QUERY_ERROR");
    exit;
}


$encoded_key = urlencode($key);
echo "<meta http-equiv='Refresh' content='0; URL=member.php?keyfield=$keyfield&key=$encoded_key&page=$page'>";

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
