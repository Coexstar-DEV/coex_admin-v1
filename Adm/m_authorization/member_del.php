<?
include "../common/user_function.php";
include "../common/dbconn.php";

include "../inc/adm_chk.php";
if (isset($_SESSION["admin_id"])) {
	$admin_id = $_SESSION["admin_id"];
} else {
	$admin_id = "";
}

if(isset($_REQUEST["m_no"])){
	$m_no = sqlfilter($_REQUEST["m_no"]);
}else{
	$m_no="";
}
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

$m_signdate = time();
$query_pdo2 = "SELECT m_id, m_orderlevel, FROM_UNIXTIME(m_signdate+8*3600) as m_sign FROM $table_authorization  WHERE  m_no = '$m_no'";
$stmt = $pdo->prepare($query_pdo2);
$stmt->execute();

$m_module = "Member Authorization";
$m_type = "Delete";


while($row = $stmt->fetch(PDO::FETCH_ASSOC))
{
$m_modified = "Deleted the level upgrade request of:" . " " . $row['m_id']. " " . "to" .  " " . $row['m_orderlevel'] . " " . "on" . " " . $row['m_sign'];

}


		$query_pdo = "UPDATE $table_authorization SET m_delete  = 1, m_check = 1 WHERE  m_no= :m_no ";
		$stmt=$pdo->prepare($query_pdo);
		$stmt->bindValue(":m_no",$m_no);
		$result = $stmt->execute();

 		if(!$result) {
 			error("QUERY_ERROR");
 			exit;
 		}

$encoded_key = urlencode($key);
// echo "<meta http-equiv='Refresh' content='0; URL=member.php?keyfield=$keyfield&key=$encoded_key&page=$page'>";
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
#####################################################################
