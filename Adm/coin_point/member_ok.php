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
if(isset($_POST["c_no"])){
	$c_no= sqlfilter($_POST["c_no"]); 
}else{
	$c_no="";
}
if(isset($_POST["c_div"])){
	$c_div= sqlfilter($_POST["c_div"]);
}else{
	$c_div="";
}
if(isset($_POST["c_userno"])){
	$c_userno= sqlfilter($_POST["c_userno"]);
}else{
	$c_userno="";
}
if(isset($_POST["c_id"])){
	$c_id= sqlfilter($_POST["c_id"]); 
}else{
	$c_id="";
}
if(isset($_POST["c_exchange"])){
	$c_exchange= sqlfilter($_POST["c_exchange"]); 
}else{
	$c_exchange="";
}
if(isset($_POST["c_payment"])){
	$c_payment= sqlfilter($_POST["c_payment"]);
}else{
	$c_payment="";
}
if(isset($_POST["c_category"])){
	$c_category= sqlfilter($_POST["c_category"]); 
}else{
	$c_category="";
}
if(isset($_POST["c_category2"])){
	$c_category2= sqlfilter($_POST["c_category2"]); 
}else{
	$c_category2="";
}
if(isset($_POST["c_ip"])){
	$c_ip= sqlfilter($_POST["c_ip"]);
}else{
	$c_ip="";
}
if(isset($_POST["c_return"])){
	$c_return= sqlfilter($_POST["c_return"]);
}else{
	$c_return="";
}
if(isset($_POST["c_no1"])){
	$c_no1= sqlfilter($_POST["c_no1"]);
}else{
	$c_no1="";
}
if(isset($_POST["c_no2"])){
	$c_no2= sqlfilter($_POST["c_no2"]);
}else{
	$c_no2="";
}
if(isset($_POST["c_signdate"])){
	$c_signdate= sqlfilter($_POST["c_signdate"]);
}else{
	$c_signdate="";
}
$c_signdate = time();
if(isset($_SERVER["HTTP_X_FORWARDED_FOR"])){
	$c_ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
}else{
	$c_ip="";
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

//데이터베이스에 입력값을 삽입한다
	$query_pdo="INSERT INTO $table_point";
	$query_pdo .="(";
	$query_pdo .="c_no,c_div,c_userno,c_id,c_exchange,c_payment,c_category,c_ip,c_return,c_no1,c_signdate,c_category2";
	$query_pdo .=")";
	$query_pdo .="VALUES";
	$query_pdo .="(";
	$query_pdo .="'',:c_div,:c_userno,:c_id,:c_exchange,:c_payment,:c_category,:c_ip,:c_return,:c_no1";
	$query_pdo .=",:c_signdate,:c_category2";
	$query_pdo .=")";

	$stmt=$pdp->prepare($query_pdo);
	$stmt->bindValue(":c_div",$c_div);
	$stmt->bindValue(":c_userno",$c_userno);
	$stmt->bindValue(":c_id",$c_id);
	$stmt->bindValue(":c_exchange",$c_exchange);
	$stmt->bindValue(":c_payment",$c_payment);
	$stmt->bindValue(":c_category",$c_category);
	$stmt->bindValue(":c_ip",$c_ip);
	$stmt->bindValue(":c_return",$c_return);
	$stmt->bindValue(":c_no1",$c_no1);
	$stmt->bindValue(":c_signdate",$c_signdate);
	$stmt->bindValue(":c_category2",$c_category2);

	$inserted = $stmt->execute();

if($inserted) {
		$encoded_key = urlencode($key);
	echo("<meta http-equiv='Refresh' content='0; URL=member.php?keyfield=$keyfield&key=$encoded_key&page=$page'>");   
}else{
	   	error("QUERY_ERROR");
	exit;
}
?>
