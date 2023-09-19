<meta charset="utf-8">
<?
include "../common/dbconn.php";
include "../common/user_function.php";
include "../common/trading.php";
include "../common/trading_order.php";
include "../inc/adm_chk.php";

$LOG_LEVEL = 1;
$LOG_TAG = basename(__FILE__);
if (isset($_SESSION["admin_id"])) {
	$admin_id = $_SESSION["admin_id"];
} else {
	$admin_id = "";
}
if (isset($_POST["b_no"])) {
	$b_no = sqlfilter($_POST["b_no"]);
} else {
	$b_no = "";
}
if (isset($_POST["b_div"])) {
	$b_div = sqlfilter($_POST["b_div"]);
} else {
	$b_div = "";
}
if (isset($_POST["b_pay"])) {
	$b_pay = sqlfilter($_POST["b_pay"]);
} else {
	$b_pay = "";
}
if (isset($_POST["b_state"])) {
	$b_state = sqlfilter($_POST["b_state"]);
} else {
	$b_state = "";
}
if (isset($_POST["b_ordermost"])) {
	$b_ordermost = sqlfilter($_POST["b_ordermost"]);
} else {
	$b_ordermost = "";
}
if (isset($_POST["b_orderfees"])) {
	$b_orderfees = sqlfilter($_POST["b_orderfees"]);
} else {
	$b_orderfees = "";
}
if (isset($_POST["b_closecost"])) {
	$b_closecost = sqlfilter($_POST["b_closecost"]);
} else {
	$b_closecost = "";
}
if (isset($_POST["b_closefees"])) {
	$b_closefees = sqlfilter($_POST["b_closefees"]);
} else {
	$b_closefees = "";
}
if (isset($_POST["b_orderprice"])) {
	$b_orderprice = sqlfilter($_POST["b_orderprice"]);
} else {
	$b_orderprice = "";
}
if (isset($_POST["b_pricetotal"])) {
	$b_pricetotal = sqlfilter($_POST["b_pricetotal"]);
} else {
	$b_pricetotal = "";
}
if (isset($_POST["b_closeprice"])) {
	$b_closeprice = sqlfilter($_POST["b_closeprice"]);
} else {
	$b_closeprice = "";
}
if (isset($_POST["b_closetotal"])) {
	$b_closetotal = sqlfilter($_POST["b_closetotal"]);
} else {
	$b_closetotal = "";
}
if (isset($_POST["b_no1"])) {
	$b_no1 = sqlfilter($_POST["b_no1"]);
} else {
	$b_no1 = "";
}
if (isset($_POST["b_userno"])) {
	$b_userno = sqlfilter($_POST["b_userno"]);
} else {
	$b_userno = "";
}
if (isset($_POST["b_id"])) {
	$b_id = sqlfilter($_POST["b_id"]);
} else {
	$b_id = "";
}
if (isset($_POST["b_delete"])) {
	$b_delete = sqlfilter($_POST["b_delete"]);
} else {
	$b_delete = "";
}

if (isset($_POST["b_closedate"])) {
	$b_closedate = sqlfilter($_POST["b_closedate"]);
} else {
	$b_closedate = "";
}
if (isset($_POST["b_ip"])) {
	$b_ip = sqlfilter($_POST["b_ip"]);
} else {
	$b_ip = "";
}
if (isset($_POST["b_signdate"])) {
	$b_signdate = sqlfilter($_POST["b_signdate"]);
} else {
	$b_signdate = "";
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

if (isset($_REQUEST["b_div_div"])) {
	$b_div_div = sqlfilter($_REQUEST["b_div_div"]);
} else {
	$b_div_div = "";
}
$m_module = "Coin Buy Order";
$m_type = "Cancel";
$m_signdate = time();

$userip = "127.0.0.1";
$signdate = time(); // global

try {

    $buyInfo = BuyInfo::makeFromNo($b_no);

    if (is_empty($buyInfo->userInfo->id)) {
        throw new Exception("FAIL_FIND_USER", __LINE__);
    }

    // 시장가 매수 주문  : 3 /* 1:지정가 매수 2:지정가 매도 3:시장가 매수 4:시장가 매도 5:매수 주문 취소 6:매도 주문 취소*/
    $op_type = 5;

    $query = "SELECT ft_exc(?, $op_type,?,?,?,?,?,?,?) stat";
    $query .= " FROM dual";
    $pdo_in = [$buyInfo->userInfo->no, $buyInfo->orderCoin->type, $buyInfo->payCoin->type, 0, 0, $userip, $signdate, $buyInfo->no];

    $stmt = pdo_excute("buy_cancel", $query, $pdo_in);
    $row = $stmt->fetch();

    /*체결 결과 0:기본, 미처리 1:체결대기 2:부분체결 3:전체체결 4:매수취소 5:매도취소*/
    $result = $row[0];
    if ($result == 4) {
        //echo json_encode(array('result'=>'0','msg'=>'CANCELED'));
    } else {
        throw new Exception("INVALID_TRADE_INFO", __LINE__);
    }
	err_log("====>BUY($b_no) Refund done:".$result);
	$updated = true;

} catch(Exception $e) {
	$s = $e->getMessage() . ' (code:' . $e->getCode() . ')';
	fatal_log("=====>BUY($b_no)  Failed refund:" . $s);
	//echo json_encode(array('result' => '-1', 'msg' => $e->getMessage()));
	$updated = false;
}

$m_type = "Cancel";
$m_modified = "Canceled buy of" . " " . $b_id;

if ($updated) {
	// 리스트 출력화면으로 이동한다
	$encoded_key = urlencode($key);
	popup_msg("SUCCEED");
	echo ("<meta http-equiv='Refresh' content='0; URL=member_wait.php?keyfield=$keyfield&key=$encoded_key&page=$page&b_div_div=$b_div_div&b_market=$b_market'>");
} else {
	popup_msg("QUERY_ERROR");
	echo ("<meta http-equiv='Refresh' content='0; URL=member_wait.php?keyfield=$keyfield&key=$encoded_key&page=$page&b_div_div=$b_div_div&b_market=$b_market'>");
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