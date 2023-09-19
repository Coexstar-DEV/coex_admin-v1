<meta charset="utf-8">
<?
session_start();

include "../common/dbconn.php";
include "../common/user_function.php";
include_once "../common/". ($_SESSION["language"] == "kr" ? "lang_kr.php" : "lang_en.php");
include "../inc/adm_chk.php";

if (isset($_SESSION["admin_id"])) {
	$admin_id = $_SESSION["admin_id"];
} else {
	$admin_id = "";
}
if (isset($_POST["m_id"])) {
	if($_POST["m_id"] == ""){
		$m_id = "unknown";
		
		
	}else{
		$m_id = sqlfilter($_POST["m_id"]);
	}
	
} else {
	$m_id = "";
}

if (isset($_POST["id"])) {
	$id = sqlfilter($_POST["id"]);
} else {
	$id = "";
}

if (isset($_POST["m_name"])) {
	if($_POST["m_name"] == ""){
		$m_name = "unknown";
	}else{
		
		$m_name = sqlfilter($_POST["m_name"]);
	}
	
} else {
	$m_name = "";
}

if (isset($_POST["m_handphone"])) {
	if($_POST["m_handphone"] == ""){
		
		$m_handphone = "unknown";
	}else{
		
		$m_handphone = sqlfilter($_POST["m_handphone"]);
	}
	
} else {
	$m_handphone = "";
}

if (isset($_POST["m_country"])) {
    $m_country = explode('@@', sqlfilter($_POST["m_country"]))[0];
    $m_countryname = explode('@@', sqlfilter($_POST["m_country"]))[1];
} else {
    $m_country = "";
    $m_countryname = "";
}
if (isset($_POST["m_signdate"])) {
	$m_signdate = sqlfilter($_POST["m_signdate"]);
} else {
	$m_signdate = "";
}

if (isset($_POST["m_type"])) {
	$m_type = sqlfilter($_POST["m_type"]);
} else {
	$m_type = "";
}

if (isset($_POST["m_remarks"])) {
	$m_remarks = sqlfilter($_POST["m_remarks"]);
} else {
	$m_remarks = "";
}
if (isset($_POST["m_alias"])) {
	if($_POST["m_alias"] == ""){
		$m_alias = "unknown";		
	}else{
		$m_alias = sqlfilter($_POST["m_alias"]);
	}
	
} else {
	$m_alias = "";
}


if (isset($_POST["m_validid"])) {
	if($_POST["m_validid"] == ""){
		$m_validid = "unknown";
	}else{
		$m_validid = sqlfilter($_POST["m_validid"]);
	}
	
} else {
	$m_validid = "";
}

if (isset($_POST["m_validnum"])) {
	if($_POST["m_validnum"] == ""){
		$m_validnum = "unknown";
	}else{
		
		$m_validnum = sqlfilter($_POST["m_validnum"]);
	}
	
} else {
	$m_validnum = "";
}

if (isset($_POST["m_birthday"])) {
	if($_POST["m_birthday"] == ""){
		$m_birthday = "unknown";
		
	}else{
		$m_birthday = sqlfilter($_POST["m_birthday"]);
	}
	
} else {
	$m_birthday = "";
}
if (isset($_POST["m_address"])) {
	if($_POST["m_address"] == ""){
		
		$m_address = "unknown";
	}else{
		
		$m_address = sqlfilter($_POST["m_address"]);
	}
	
} else {
	$m_address = "";
}

if (isset($_POST["m_coin"])) {
	$m_coin = sqlfilter($_POST["m_coin"]);
} else {
	$m_coin = "";
}

if (isset($_POST["m_wallet"])) {
	$m_wallet = $_POST["m_wallet"];
} else {
	$m_wallet = "";
}

$m_regcheck = 0;

$m_signdate = time();


$m_module = "Watchlist";
$m_type1 = "Insert";


//데이터베이스에 입력값을 삽입한다
$query_pdo1 = "INSERT INTO $watchlist";
$query_pdo1 .= "(";
$query_pdo1 .= "id,m_id,m_name,m_alias,m_handphone,m_birthday,m_country, m_countryname, m_address,m_validid,m_validnum, m_signdate, m_type, m_remarks, m_adminno, m_regcheck, m_coin, m_wallet";
$query_pdo1 .= ")";
$query_pdo1 .= "VALUES";
$query_pdo1 .= "(";
$query_pdo1 .= "'',:m_id,:m_name, :m_alias,:m_handphone, :m_birthday,:m_country,:m_countryname,:m_address";
$query_pdo1 .= ",:m_validid,:m_validnum,:m_signdate,:m_type, :m_remarks,:m_adminno, :m_regcheck, :m_coin, :m_wallet";
$query_pdo1 .= ")";

$stmt = $pdo->prepare($query_pdo1);
$stmt->bindValue(":m_id", $m_id);
$stmt->bindValue(":m_name", $m_name);
$stmt->bindValue(":m_alias", $m_alias);
$stmt->bindValue(":m_handphone", $m_handphone);
$stmt->bindValue(":m_birthday", $m_birthday);
$stmt->bindValue(":m_country", $m_country);
$stmt->bindValue(":m_countryname", $m_countryname);
$stmt->bindValue(":m_address", $m_address);
$stmt->bindValue(":m_validid", $m_validid);
$stmt->bindValue(":m_validnum", $m_validnum);
$stmt->bindValue(":m_signdate", $m_signdate);
$stmt->bindValue(":m_type", $m_type);
$stmt->bindValue(":m_remarks", $m_remarks);
$stmt->bindValue(":m_adminno", $admin_id);
$stmt->bindValue(":m_regcheck", $m_regcheck);
$stmt->bindValue(":m_coin", $m_coin);
$stmt->bindValue(":m_wallet", $m_wallet);
$inserted = $stmt->execute();

$m_modified =  $m_name . "//" . $m_alias . "//" . $m_id . "//" . $m_handphone . "//" . $m_birthday . "//" . $m_country . "//" . $m_countryname . "//" . $m_address . "//" . $m_validid . "//" . $m_validnum . "//". $m_type . "//" . $m_remarks;

if ($inserted) {

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
	$stmt->bindValue(":m_type", $m_type1);
	$stmt->bindValue(":m_modified", $m_modified);
	$stmt->bindValue(":m_signdate", $m_signdate);
	$inserted2 = $stmt->execute();

	if($inserted2){
		$encoded_key = urlencode($key);
		echo ("<meta http-equiv='Refresh' content='0; URL=member.php'>");

	}else {
		error("QUERY_ERROR");
		exit;
	}
	
} else {
    error("QUERY_ERROR");
    exit;
}


?>