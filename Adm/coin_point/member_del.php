<?
include "../common/user_function.php";
include "../common/dbconn.php";
include "../inc/adm_chk.php";
include "../inc/adm_chk.php";
if (isset($_SESSION["admin_id"])) {
	$admin_id = $_SESSION["admin_id"];
} else {
	$admin_id = "";
}
if (isset($_REQUEST["c_no"])) {
	$c_no = sqlfilter($_REQUEST["c_no"]);
} else {
	$c_no = "";
}
if (isset($_REQUEST["c_category"])) {
	$c_category = sqlfilter($_REQUEST["c_category"]);
	if($c_category == 'reqorderrecv' || $c_category == 'formwallet'){
	  echo	$c_category = 'deposit';
	}else if ($c_category == 'reqorder'){
		echo	$c_category = 'withdrawal';
	}else if ($c_category == 'tradebuy'){
		echo	$c_category = 'buy';
	}else if ($c_category == 'tradesell'){
		echo $c_category = 'sell';
	}else if ($c_category == 'reqordersend'){
		echo	$c_category = 'transfer';
	}
} else {
	$c_category = "";
}
if (isset($_REQUEST["c_id"])) {
	$c_id = sqlfilter($_REQUEST["c_id"]);
} else {
	$c_id = "";
}
$m_module = "Trading";
$m_type = "Delete";
$m_modified =  "Deleted" . " " . $c_category . " " . "transaction of" . " " . $c_id . " " . "with transaction no." . " " . $c_no;
$m_signdate = time(); 

$query_pdo = "UPDATE $table_point SET c_delete = 1 WHERE c_no = :c_no ";
$stmt = $pdo->prepare($query_pdo);
$stmt->bindValue(":c_no", $c_no);
$deleted = $stmt->execute();
if (!$deleted) {
    error("QUERY_ERROR");
    exit;
}

$encoded_key = urlencode($key);
echo "<meta http-equiv='Refresh' content='0; URL=member2.php?keyfield=$keyfield&key=$encoded_key&page=$page'>";
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