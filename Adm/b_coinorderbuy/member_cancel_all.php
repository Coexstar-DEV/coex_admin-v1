<meta charset="utf-8">
<?
include "../common/dbconn.php";
include "../common/user_function.php";
include "../common/trading.php";
include "../common/trading_order.php";
include "../inc/adm_chk.php";

$LOG_LEVEL = 1;
$LOG_TAG = basename(__FILE__);


if (isset($_REQUEST["keyfield"])) {
	$keyfield = sqlfilter($_REQUEST["keyfield"]);
} else {
	$keyfield = "";
}
if (isset($_REQUEST["key"])) {
	$key = trim(sqlfilter($_REQUEST["key"]));
} else {
	$key = "";
}
if (isset($_REQUEST["statee"])) {
	$statee = sqlfilter($_REQUEST["statee"]);
} else {
	$statee = "";
}
if (isset($_REQUEST["b_no"])) {
	$b_no = sqlfilter($_REQUEST["b_no"]);
} else {
	$b_no = "";
}
if (isset($_REQUEST["b_div_div"])) {
	$b_div_div = sqlfilter($_REQUEST["b_div_div"]);
} else {
	$b_div_div = "";
}
if (isset($_REQUEST["b_market"])) {
	$b_market = $_REQUEST["b_market"];
} else {
	$b_market = "";
}

if (is_empty($key) || is_empty($b_div_div) || is_empty($b_market)) {
	popup_msg("Select search option, COIN/MARKET/ID");
	echo ("<meta http-equiv='Refresh' content='0; URL=member_wait.php?keyfield=$keyfield&key=$encoded_key&page=$page&b_div_div=$b_div_div&b_market=$b_market'>");
	exit;
}



$userip = "127.0.0.1";
$signdate = time(); // global
$pdo_in = [];

try {
	// key . user id 체크 
	
	// 코인 이름 
	if (!is_empty($b_div_div)) {
		$coinInfo = new CoinInfo($b_div_div);
		$cond_div = " and b_div = ?";
		$pdo_in[] = $b_div_div;
		err_log("b_div_div==> $b_div_div, name:$coinInfo->name");
	}

	if (!is_empty($b_market)) {
		$payInfo = new CoinInfo($b_market);
		$c_market = $payInfo->name;
		$cond_pay = " and b_pay = ?";
		$pdo_in[] = $payInfo->type;
		err_log("b_pay==> $payInfo->type, name:$payInfo->name");
	}

	$encoded_key = urlencode($key);

	$query_pdo = "SELECT b_no,b_state,b_ordermost,b_closecost,b_closefees,b_orderprice,b_pricetotal,b_closeprice,b_closetotal,b_userno,b_id,b_signdate,b_ip,b_delete,b_closedate,b_orderfees,b_div,b_pay  FROM $table_orderbuy where b_state <> 'com' and b_delete <> '1' $cond_div $cond_pay ";
	if ($key != "") {
		$query_pdo .= " and $keyfield = '$key' ";
	}

	$query_pdo .= " ORDER BY b_signdate DESC";


	$stmt = pdo_excute("select", $query_pdo, $pdo_in);
	while ($row = $stmt->fetch()) {
		$b_no = $row[0];

		$buyInfo = BuyInfo::makeFromNo($b_no);

		if (is_empty($buyInfo->userInfo->id)) {
			throw new Exception("FAIL_FIND_USER", __LINE__);
		}

		// 시장가 매수 주문  : 3 /* 1:지정가 매수 2:지정가 매도 3:시장가 매수 4:시장가 매도 5:매수 주문 취소 6:매도 주문 취소*/
		$op_type = 5;

		$query = "SELECT ft_exc(?, $op_type,?,?,?,?,?,?,?) stat";
		$query .= " FROM dual";
		$pdo_in = [$buyInfo->userInfo->no, $buyInfo->orderCoin->type, $buyInfo->payCoin->type, 0, 0, $userip, $signdate, $buyInfo->no];

		$stmt1 = pdo_excute("buy_cancel", $query, $pdo_in);
		$result_row = $stmt1->fetch();

		/*체결 결과 0:기본, 미처리 1:체결대기 2:부분체결 3:전체체결 4:매수취소 5:매도취소*/
		$result = $result_row[0];
		if ($result == 4) {
			//echo json_encode(array('result'=>'0','msg'=>'CANCELED'));
			err_log("====>BUY($b_no) Refund done:".$result);
		} else {
			fatal_log("====>BUY($b_no) Failed refund:".$result);
			throw new Exception("INVALID_TRADE_INFO", __LINE__);
		}
	}

	$updated = true;

} catch(Exception $e) {
	$s = $e->getMessage() . ' (code:' . $e->getCode() . ')';
	fatal_log("=====>BUY($b_no)  Failed refund:" . $s);
	//echo json_encode(array('result' => '-1', 'msg' => $e->getMessage()));
	$updated = false;
}


if ($updated) {
	// 리스트 출력화면으로 이동한다
	$encoded_key = urlencode($key);
	popup_msg("SUCCEED");
	echo ("<meta http-equiv='Refresh' content='0; URL=member_wait.php?keyfield=$keyfield&key=$encoded_key&page=$page&b_div_div=$b_div_div&b_market=$b_market'>");
	exit;
} else {
	popup_msg("QUERY_ERROR");
	echo ("<meta http-equiv='Refresh' content='0; URL=member_wait.php?keyfield=$keyfield&key=$encoded_key&page=$page&b_div_div=$b_div_div&b_market=$b_market'>");
	exit;
}
?>