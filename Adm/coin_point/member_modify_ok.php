<meta charset="utf-8">
<?
include "../common/dbconn.php";
include "../common/user_function.php";
include "../inc/adm_chk.php";

$adminlevel = $_SESSION["level"];
check_manager_level($adminlevel, ADMIN_LVL3);

if (isset($_SESSION["admin_id"])) {
	$admin_id = $_SESSION["admin_id"];
} else {
	$admin_id = "";
}
if (isset($_POST["c_no"])) {
	$c_no = sqlfilter($_POST["c_no"]);
} else {
	$c_no = "";
}
if (isset($_POST["c_div"])) {
	$c_div = sqlfilter($_POST["c_div"]);
} else {
	$c_div = "";
}
if (isset($_POST["c_userno"])) {
	$c_userno = sqlfilter($_POST["c_userno"]);
} else {
	$c_userno = "";
}
if (isset($_POST["c_id"])) {
	$c_id = sqlfilter($_POST["c_id"]);
} else {
	$c_id = "";
}
if (isset($_POST["c_exchange"])) {
	$c_exchange = sqlfilter($_POST["c_exchange"]);
} else {
	$c_exchange = "";
}
if (isset($_POST["c_payment"])) {
	$c_payment = sqlfilter($_POST["c_payment"]);
} else {
	$c_payment = "";
}
if (isset($_POST["c_category"])) {
	$c_category = sqlfilter($_POST["c_category"]);
} else {
	$c_category = "";
}
if (isset($_POST["c_category2"])) {
	$c_category2 = sqlfilter($_POST["c_category2"]);
} else {
	$c_category2 = "";
}
if (isset($_POST["c_ip"])) {
	$c_ip = sqlfilter($_POST["c_ip"]);
} else {
	$c_ip = "";
}
if (isset($_POST["c_return"])) {
	$c_return = sqlfilter($_POST["c_return"]);
} else {
	$c_return = "";
}
if (isset($_POST["c_no1"])) {
	$c_no1 = sqlfilter($_POST["c_no1"]);
} else {
	$c_no1 = "";
}
if (isset($_POST["c_no2"])) {
	$c_no2 = sqlfilter($_POST["c_no2"]);
} else {
	$c_no2 = "";
}
if (isset($_REQUEST["c_category"])) {
	$c_category = sqlfilter($_REQUEST["c_category"]);
	if($c_category == 'reqorderrecv' || $c_category == 'formwallet'){
	  echo	$c_category = 'Deposit';
	}else if ($c_category == 'reqorder'){
		echo	$c_category = 'Withdrawal';
	}else if ($c_category == 'tradebuy'){
		echo	$c_category = 'Buy';
	}else if ($c_category == 'tradesell'){
		echo $c_category = 'Sell';
	}else if ($c_category == 'reqordersend'){
		echo	$c_category = 'Transfer';
	}
} else {
	$c_category = "";
}
if (isset($_POST["c_signdate"])) {
	$c_signdate = sqlfilter($_POST["c_signdate"]);
} else {
	$c_signdate = "";
}
if (isset($_REQUEST["keyfield"])) {
	$keyfield = sqlfilter($_REQUEST["keyfield"]);
} else {
	$keyfield = "";
}
if (isset($_REQUEST["key"])) {
	$key = sqlfilter($_REQUEST["key"]);
} else {
	$key = "";
}
if (isset($_REQUEST["page"])) {
	$page = sqlfilter($_REQUEST["page"]);
} else {
	$page = "";
}

$m_module = $c_category;
$m_type = "Update";
$m_modified =  "Updated the " . " " . $c_category . " " .  "information of" . " " . $c_id . " " . "with transaction no." . " " . $c_no;
$m_signdate = time(); 

$query_pdo = "UPDATE $table_point SET";
$query_pdo .= " c_userno=:c_userno,c_div=:c_div,c_id=:c_id,c_exchange=:c_exchange,c_payment=:c_payment,c_category=:c_category,c_return=:c_return,c_no1=:c_no1,c_category2=:c_category2";
$query_pdo .= " WHERE c_no = :c_no";

$stmt = $pdo->prepare($query_pdo);
$stmt->bindValue(":c_userno", $c_userno);
$stmt->bindValue(":c_div", $c_div);
$stmt->bindValue(":c_id", $c_id);
$stmt->bindValue(":c_exchange", $c_exchange);
$stmt->bindValue(":c_payment", $c_payment);
$stmt->bindValue(":c_category", $c_category);
$stmt->bindValue(":c_return", $c_return);
$stmt->bindValue(":c_no1", $c_no1);
$stmt->bindValue(":c_category2", $c_category2);
$stmt->bindValue(":c_no", $c_no);
$updated = $stmt->execute();

if ($updated) {
		$encoded_key = urlencode($key);
		echo ("<meta http-equiv='Refresh' content='0; URL=member2.php?keyfield=$keyfield&key=$encoded_key&page=$page'>");
	}else{
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
$inserted = $stmt->execute();
?>