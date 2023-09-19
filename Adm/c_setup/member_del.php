<?
#####################################################################

include "../common/user_function.php";
include "../common/dbconn.php";

$m_market = (is_empty($_REQUEST["m_market"]) ? $DEFINE_DEFAULT_NAME : $_REQUEST["m_market"]);

if (isset($_REQUEST["c_no"])) {
	$c_no = sqlfilter($_REQUEST["c_no"]);
} else {
	$c_no = "";
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

$query_pdo = "DELETE from $table_setup WHERE c_no = :c_no ";
$stmt = $pdo->prepare($query_pdo);
$stmt->bindValue(":c_no", $c_no);
$deleted1 = $stmt->execute();

if (!$deleted1) {
	error("QUERY_ERROR");
	exit;
}

try {
	$query_pdo2 = "DELETE from m_setup WHERE m_div = ? and m_pay= ?";
	$pdo_in = [$c_no, $m_market];
	$del = pdo_excute("del m_setup", $query_pdo2, $pdo_in);

} catch (PDOException $e) {
	err_log("Fatal: ". $e->getMessage());
	error("QUERY_ERROR");
}

try {
	$query_pdo3 = "DELETE from $table_level WHERE c_coin= ?";
	$pdo_in_3 = [$c_no];
	$del2 = pdo_excute("del c_level", $query_pdo3, $pdo_in_3);

} catch (PDOException $e) {
	err_log("Fatal: ". $e->getMessage());
	error("QUERY_ERROR");
}

$encoded_key = urlencode($key);
//echo "<meta http-equiv='Refresh' content='0; URL=member.php?keyfield=$keyfield&key=$encoded_key&page=$page'>";
echo ("<meta http-equiv='Refresh' content='0; URL=member.php'>");
#####################################################################
 