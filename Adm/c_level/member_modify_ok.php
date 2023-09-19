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

if(isset($_REQUEST["c_no"])){
$c_no =  sqlfilter($_REQUEST["c_no"]);
}else{
$c_no="";
}
if(isset($_POST["c_coin"])){
$c_coin= sqlfilter($_POST["c_coin"]); 
}else{
$c_coin="";
}
if(isset($_POST["c_level"])){
$c_level= sqlfilter($_POST["c_level"]);
}else{
$c_level="";
}
if(isset($_POST["c_deposit"])){
$c_deposit= sqlfilter($_POST["c_deposit"]); 
}else{
$c_deposit="";
}
if(isset($_POST["c_withdraw"])){
$c_withdraw= sqlfilter($_POST["c_withdraw"]); 
}else{
$c_withdraw="";
}
if(isset($_POST["c_limit"])){
$c_limit= sqlfilter($_POST["c_limit"]); 
}else{
$c_limit="";
}


if(isset($_REQUEST["key"])){
$key = sqlfilter($_REQUEST["key"]);
}else{
$key="";
}

$keyfield = sqlfilter($_REQUEST["keyfield"]);
$key = sqlfilter($_REQUEST["key"]);
$page = sqlfilter($_REQUEST["page"]);


$c_signdate=time();

if(isset($_POST["c_signdate"])){
$c_signdate= sqlfilter($_POST["c_signdate"]); 
}else{
$c_signdate="";
}

$m_signdate = time();
$query_pdo2 = "SELECT c_no,c_coin FROM $table_setup  WHERE  c_no = '$c_coin'";
$stmt = $pdo->prepare($query_pdo2);
$stmt->execute();


$m_module = "Deposit/Withdrawal Limit";
$m_type = "Update";

while($row = $stmt->fetch(PDO::FETCH_ASSOC))
{
	$m_modified = "Updated the Deposit/Withdrawal Information" . " " . "of". " " .  $row['c_coin']  . " " . "on Level" . " " . $c_level ;

}




//데이터베이스에 입력값을 삽입한다

	$query_pdo = "UPDATE $table_level SET";
	$query_pdo .= " c_coin=:c_coin,c_level=:c_level,c_deposit=:c_deposit,c_withdraw=:c_withdraw,c_limit=:c_limit,c_signdate=:c_signdate ";
	$query_pdo .= " WHERE c_no = :c_no ";

	$stmt = $pdo->prepare($query_pdo);
	$stmt->bindValue(":c_coin", $c_coin);
	$stmt->bindValue(":c_level", $c_level);
	$stmt->bindValue(":c_deposit", $c_deposit);
	$stmt->bindValue(":c_withdraw", $c_withdraw);
	$stmt->bindValue(":c_limit", $c_limit);
	$stmt->bindValue(":c_signdate", $c_signdate);
	$stmt->bindValue(":c_no", $c_no);
	$updated = $stmt->execute();

if($updated) {
// 리스트 출력화면으로 이동한다
$encoded_key = urlencode($key);

	//echo("<meta http-equiv='Refresh' content='0; URL=member.php?keyfield=$keyfield&key=$encoded_key&page=$page'>");   
	echo("<meta http-equiv='Refresh' content='0; URL=member.php'>");  
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
