<?
include "../common/user_function.php";
include "../common/dbconn.php";
include "../inc/adm_chk.php";


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
if(isset($_REQUEST["id"])){
$id = sqlfilter($_REQUEST["id"]);
}else{
	$id="";
}


/*
if($admin_id!="admin"){
	echo "<script>history.back();</script>";
	exit;
}
*/
		$query_pdo = "DELETE from $watchlist WHERE id = :id";
		$stmt = $pdo->prepare($query_pdo);
		$stmt->bindValue(":id",$id);
		$deleted = $stmt->execute();
		
 		if(!$deleted) {
 			error("QUERY_ERROR");
 			exit;
 		}

$encoded_key = urlencode($key);
echo "<meta http-equiv='Refresh' content='0; URL=member.php'>";

?>