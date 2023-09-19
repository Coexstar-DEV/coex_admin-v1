<?
include "../common/user_function.php";
include "../common/dbconn.php";
include "../inc/adm_chk.php";
if (isset($_SESSION["admin_id"])) {
    $admin_id = $_SESSION["admin_id"];
} else {
    $admin_id = "";
}

$LOG_LEVEL = 1;
$LOG_TAG = basename(__FILE__);

$adminlevel = $_SESSION["level"];
check_manager_level($adminlevel, ADMIN_LVL4);

if(isset($_REQUEST["keyfield"])){
$keyfield = sqlfilter($_REQUEST["keyfield"]);
}else{
	$keyfield="";
}
if(isset($_REQUEST["key"])){
$key = sqlfilter($_REQUEST["key"]);
}else{
	$key="";
}
if(isset($_REQUEST["page"])){
$page = sqlfilter($_REQUEST["page"]);
}else{
	$page="";
}
if (isset($_REQUEST["m_country_name"])) {
	$m_country_name = sqlfilter($_REQUEST["m_country_name"]);
} else {
	$m_country_name = "";
}
if(isset($_REQUEST["m_userno"])){
$m_userno = sqlfilter($_REQUEST["m_userno"]);
}else{
	$m_userno="";
}

$m_signdate = time();
$query_pdo2 = "SELECT m_id FROM $member  WHERE  m_userno = '$m_userno'";
$stmt = $pdo->prepare($query_pdo2);
$stmt->execute();

$m_module = "Member Management";
$m_type = "Delete";


while($row = $stmt->fetch(PDO::FETCH_ASSOC))
{
$m_modified = "Deleted the information of" . " " . $row['m_id'];

}

/*
if($admin_id!="admin"){
	echo "<script>history.back();</script>";
	exit;
}
*/
		$query_pdo = "UPDATE $member SET m_delete = 1, m_block = 1 WHERE m_userno = :m_userno";
		$stmt = $pdo->prepare($query_pdo);
		$stmt->bindValue(":m_userno",$m_userno);
		$deleted = $stmt->execute();
		
 		if(!$deleted) {
 			error("QUERY_ERROR");
 			exit;
 		}

$encoded_key = urlencode($key);
echo "<meta http-equiv='Refresh' content='0; URL=member.php?keyfield=$keyfield&key=$encoded_key&page=$page&m_country_name=$m_country_name'>";

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