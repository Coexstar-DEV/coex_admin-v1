<?
#####################################################################

include "../common/user_function.php";
include "../common/dbconn.php";
include "../inc/adm_chk.php";
if (isset($_SESSION["admin_id"])) {
	$admin_id = $_SESSION["admin_id"];
} else {
	$admin_id = "";
}

if(isset($_REQUEST["ydate1"])){
	$ydate1 = sqlfilter($_REQUEST["ydate1"]);
}else{
	$ydate1="";
}
if(isset($_REQUEST["mdate1"])){
	$mdate1 = sqlfilter($_REQUEST["mdate1"]);
}else{
	$mdate1="";
}
if(isset($_REQUEST["ddate1"])){
	$ddate1 = sqlfilter($_REQUEST["ddate1"]);
}else{
	$ddate1="";
}
if(isset($_REQUEST["ydate2"])){
	$ydate2 = sqlfilter($_REQUEST["ydate2"]);
}else{
	$ydate2="";
}
if(isset($_REQUEST["mdate2"])){
	$mdate2 = sqlfilter($_REQUEST["mdate2"]);
}else{
	$mdate2="";
}
if(isset($_REQUEST["ddate2"])){
	$ddate2 = sqlfilter($_REQUEST["ddate2"]);
}else{
	$ddate2="";
}
if(isset($_REQUEST["k_no"])){
	$k_no = sqlfilter($_REQUEST["k_no"]);
}else{
	$k_no="";
}
if(isset($_REQUEST["k_checkk"])){
	$k_checkk = sqlfilter($_REQUEST["k_checkk"]);
}else{
	$k_checkk="";
}

$query_pdo2 = "SELECT k_id, FROM_UNIXTIME(k_signdate+8*3600) as k_sign, k_orderprice FROM $table_k_deposit  WHERE  k_no = '$k_no'";
$stmt = $pdo->prepare($query_pdo2);
$stmt->execute();

$m_module = "PHP Deposit";
$m_type = "Delete";
$m_signdate = time(); 


while($row = $stmt->fetch(PDO::FETCH_ASSOC))
{
	$m_modified =  "Deleted Deposit " . " " . "transaction of" . " " . $row['k_id'] . " " . "with transaction date" . " " . $row['k_sign'];

}
	
		//pdo
		$query_pdo = "UPDATE $table_k_deposit SET k_delete = 1 WHERE k_no = :k_no ";
		$stmt=$pdo->prepare($query_pdo);
		$stmt->bindValue(":k_no",$k_no);
		$deleted = $stmt->execute();
		//pdo end
 		if(!$deleted) {
 			error("QUERY_ERROR");
 			exit;
 		}

$encoded_key = urlencode($key);
//echo "<meta http-equiv='Refresh' content='0; URL=member.php?keyfield=$keyfield&key=$encoded_key&page=$page&k_checkk=$k_checkk&ydate1=$ydate1&mdate1=$mdate1&ddate1=$ddate1&ydate2=$ydate2&mdate2=$mdate2&ddate2=$ddate2'>";
echo "<meta http-equiv='Refresh' content='0; URL=member.php'>";

#####################################################################

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