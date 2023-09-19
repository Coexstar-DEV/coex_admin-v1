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


$LOG_LEVEL = 1;
$LOG_TAG = basename(__FILE__);

$m_market = (is_empty($_REQUEST["m_market"]) ?  $DEFINE_DEFAULT_NAME : $_REQUEST["m_market"]);

if (isset($_POST["c_no"])) {
    $c_no = sqlfilter($_POST["c_no"]);
} else {
    $c_no = "";
}

if (isset($_REQUEST["c_no"])) {
    $c_no = sqlfilter($_REQUEST["c_no"]);
} else {
    $c_no = "";
}

if (isset($_POST["c_coin"])) {
    $c_coin = sqlfilter($_POST["c_coin"]);
} else {
    $c_coin = "";
}

if (isset($_POST["c_wcommission"])) {
    $c_wcommission = sqlfilter($_POST["c_wcommission"]);
} else {
    $c_wcommission = "";
}
if (isset($_POST["c_limit"])) {
    $c_limit = sqlfilter($_POST["c_limit"]);
} else {
    $c_limit = "";
}
if (isset($_POST["c_asklimit"])) {
    $c_asklimit = sqlfilter($_POST["c_asklimit"]);
} else {
    $c_asklimit = "";
}
if (isset($_POST["c_unit"])) {
    $c_unit = sqlfilter($_POST["c_unit"]);
} else {
    $c_unit = "";
}
if (isset($_POST["c_use"])) {
    $c_use = sqlfilter($_POST["c_use"]);
} else {
    $c_use = "";
}
if (isset($_POST["c_rank"])) {
    $c_rank = sqlfilter($_POST["c_rank"]);
} else {
    $c_rank = "";
}
if (isset($_POST["c_signdate"])) {
    $c_signdate = sqlfilter($_POST["c_signdate"]);
} else {
    $c_signdate = "";
}
if (isset($_POST["c_title"])) {
    $c_title = sqlfilter($_POST["c_title"]);
} else {
    $c_title = "";
}
if (isset($_POST["c_type"])) {
    $c_type = sqlfilter($_POST["c_type"]);
} else {
    $c_type = "";
}

if (isset($_POST["c_since"])) {
    $c_since = sqlfilter($_POST["c_since"]);
} else {
    $c_since = "";
}
if (isset($_POST["c_quantity"])) {
    $c_quantity = sqlfilter($_POST["c_quantity"]);
} else {
    $c_quantity = "";
}
if (isset($_POST["c_site"])) {
    $c_site = $_POST["c_site"];
} else {
    $c_site = "";
}
if (isset($_POST["c_wpaper"])) {
    $c_wpaper = $_POST["c_wpaper"];
} else {
    $c_wpaper = "";
}
if (isset($_POST["c_introduce"])) {
    $c_introduce = $_POST["c_introduce"];
} else {
    $c_introduce = "";
}
if (isset($_POST["c_exp1"])) {
    $c_exp1 = $_POST["c_exp1"];
} else {
    $c_exp1 = "";
}
if (isset($_POST["c_exp2"])) {
    $c_exp2 = sqlfilter($_POST["c_exp2"]);
} else {
    $c_exp2 = "";
}
if (isset($_POST["c_details"])) {
    $c_details = $_POST["c_details"];
} else {
    $c_details = "";
}
if (isset($_POST["c_suspend_yn"])) {
    $c_suspend_yn = sqlfilter($_POST["c_suspend_yn"]);
} else {
    $c_suspend_yn = "0";
}
if (isset($_POST["m_suspend_yn"])) {
    $m_suspend_yn = sqlfilter($_POST["m_suspend_yn"]);
} else {
    $m_suspend_yn = "0";
}
if (isset($_POST["c_suspend_reason"])) {
    $c_suspend_reason = sqlfilter($_POST["c_suspend_reason"]);
} else {
    $c_suspend_reason = "";
}

if ($c_suspend_yn == "0") $c_suspend_reason = ""; // 거래 정상이면 사유 없음.

if (isset($_POST["c_limit_in"])) {
    $c_limit_in = sqlfilter($_POST["c_limit_in"]);
} else {
    $c_limit_in = "";
}
if (isset($_POST["c_limit_out"])) {
    $c_limit_out = sqlfilter($_POST["c_limit_out"]);
} else {
    $c_limit_out = "";
}

$savedir = "../../img/coin/";

if (isset($_FILES["c_img"])) {
    $c_img_name = $_FILES["c_img"]["name"];
    $c_img = $_FILES["c_img"]["tmp_name"];
} else {
    $c_img_name = "";
    $c_img = "";
}
if (isset($_POST["old_c_img"])) {
    $old_c_img = sqlfilter($_POST["old_c_img"]);
} else {
    $old_c_img = "";
}
if (isset($_POST["c_img_del"])) {
    $c_img_del = sqlfilter($_POST["c_img_del"]);
} else {
    $c_img_del = "";
}
if (isset($_POST["c_fees"])) {
    $c_fees = sqlfilter($_POST["c_fees"]);
} else {
    $c_fees = "";
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

$c_signdate = time();
$c_img = $c_coin . ".png";
$m_signdate = time();

$query_pdo2 = "SELECT c_no,c_coin FROM $table_setup  WHERE  c_no = '$c_no'";
$stmt = $pdo->prepare($query_pdo2);
$stmt->execute();

$m_module = "Coin Setup";
$m_type = "Update";

while($row = $stmt->fetch(PDO::FETCH_ASSOC))
{
	$m_modified = "Updated the Coin" . " " .  $row['c_coin'] . "/" . $m_market ;

}

	



$query_pdo = "UPDATE $table_setup SET";
$query_pdo .= " c_coin=?,c_wcommission=?,c_asklimit=?,c_title=?,c_img=?,c_signdate=?,c_fees=?,c_type=?";
$query_pdo .= ",c_since=?,c_quantity=?,c_site=?,c_wpaper=?,c_introduce=?,c_exp1=?,c_exp2=?,c_details=?,c_limit_in=?,c_limit_out=?,c_suspend_yn=?,c_suspend_reason=?";
$query_pdo .= " WHERE c_no =?";
$pdo_in = [$c_coin, $c_wcommission, $c_asklimit, $c_title, $c_img, $c_signdate, $c_fees, $c_type, $c_since, $c_quantity, $c_site,$c_wpaper, $c_introduce, $c_exp1, $c_exp2, $c_details,  $c_limit_in, $c_limit_out,$c_suspend_yn,$c_suspend_reason, $c_no];
$updated = pdo_excute("update1", $query_pdo, $pdo_in);

$query = "UPDATE m_setup SET";
$query .= " m_unit=?, m_limit=?, m_rank=?, m_use=?, m_suspend_yn=?";
$query .= " WHERE m_div = ? and m_pay=?";
$pdo_in = [$c_unit, $c_limit, $c_rank, $c_use, $m_suspend_yn, $c_no, $m_market];

$updated = pdo_excute("update2", $query, $pdo_in);


if ($updated) {
    $encoded_key = urlencode($key);
    //echo("<meta http-equiv='Refresh' content='0; URL=member.php?keyfield=$keyfield&key=$encoded_key&page=$page'>");
    echo ("<meta http-equiv='Refresh' content='0; URL=member.php?m_market=$m_market'>");
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