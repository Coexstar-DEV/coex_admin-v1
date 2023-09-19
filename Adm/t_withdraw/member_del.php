<?
#####################################################################

include "../common/user_function.php";
include "../common/dbconn.php";

if(isset($_REQEUST["t_no"])){
	$t_no = sqlfilter($_REQEUST["t_no"]);
}else{
	$t_no ="";
}
if(isset($_REQEUST["keyfield"])){
$keyfield = sqlfilter($_REQUEST["keyfield"]);
}else{
	$keyfield="";
}
if(isset($_REQEUST["key"])){
$key = sqlfilter($_REQUEST["key"]);
}else{
	$key="";
}
if(isset($_REQEUST["page"])){
$page = sqlfilter($_REQUEST["page"]);
}else{
	$page="";
}
		//pdo
		$query_pdo = "DELETE from $table_withdraw WHERE t_no = :t_no";
		$stmt = $pdo->prepare($query_pdo);
		$stmt->bindValue(":t_no", $t_no);
		$deleted = $stmt->execute();
		//pdo end
 		if(!$deleted) {
 			error("QUERY_ERROR");
 			exit;
 		}

$encoded_key = urlencode($key);
echo "<meta http-equiv='Refresh' content='0; URL=member_coin.php?keyfield=$keyfield&key=$encoded_key&page=$page'>";

#####################################################################
