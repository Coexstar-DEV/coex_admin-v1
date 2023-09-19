<?
#####################################################################
include "../common/dbconn.php";
include "../common/user_function.php";
include "../common/trading.php";

// 호가 매도 최저가 , 매수 최고가 사이에서 매매 체결 된것 처럼 처리 한다. 
// => 호가창 orderbuy, ordersell 에 영향없이, 잔고 moneybank 에 영향없이 처리 해야한다. 
// 최근 10분 매도 매수가 안일어 난상황에 대해서만 처리 한다.  
// 1. 호가 맞춰주기.  - 범위 지정해서 랜덤. 
// 2. 수량은 코인별로 다르게  - 범위 지정해서 랜덤. 
// 
// curl "http://127.0.0.1:7700/Adm/coin_point/contract_miner.php?coin=BTC&market=KRWC"

//$LOG_LEVEL = 1;
$LOG_TAG = basename(__FILE__);

$coin = $_REQUEST["coin"]; 
$market = $_REQUEST["market"]; 
$interval = isset($_REQUEST["interval"]) ? $_REQUEST["interval"] : 5; 

$ran = rand(1, $interval);
$mod = $ran %  $interval;
if ($mod != 0) {
	err_log("$coin/$market  interval:$interval  , rand:$ran  exit");
	exit;
} else {
	err_log("$coin/$market  mining -- interval:$interval  , rand:$ran ");
}

$coinInfo = new CoinInfo($coin);
$payInfo = new CoinInfo($market);

$suspend_yn = suspend_yn_state($coin, $market);
if ($suspend_yn  > 1) {
	err_log("$coin/$market  interval:$interval  , rand:$ran  SUSPEND TRADING exit");
	exit;
}

// 매초마다 호출되고, random 값으로 실행될지 안될지 결정. 
$interval = $interval*60;  // 5min
$date = time();
$date_inter = $date - $interval;
// 
$query = "SELECT c_no,c_exchange+0 as c_exchange, c_payment+0 as c_payment, c_category,c_return+0 as c_return,c_signdate  FROM coin_point";
$query .= " WHERE c_limityn = 'Y' and c_div=? and c_pay = ? and c_id <> 'coex@miner.net' order by c_no desc limit 1";
$stmt = pdo_excute("select cp", $query, [$coinInfo->type, $payInfo->type]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$row) {
	err_log("skip - date_innter");
	exit;
}else {
	$c_signdate = $row["c_signdate"]; 
	if ($date_inter - $c_signdate  < $interval) {
		err_log("skip - date_innter2:sig:$c_signdate, interval:$date_inter");
		exit;
	}
}

$c_unit = get_coin_unit($coinInfo->type, $payInfo->name);
// 호가 찾기? 
$price = miner_ask_range($coinInfo->type, $payInfo->type);
$amount = miner_amount_range_random($coinInfo->type, $payInfo->type);

if (is_empty($price["sell"]) || is_empty($price["buy"])) {
	fatal_log("skip - fail ask price sell:".$price["sell"].", buy:".$price["buy"]);
	exit;
}

$pay_most = -1*$row["c_payment"];
$exchage_most = $row["c_exchange"];
$c_category = $row["c_category"]; 

if ($c_category == "tradebuy") {
	$values = ["tradebuy","tradesell"];
	$weights = [80,20];
	$c_category = weighted_random($weights, $values);
} else {
	$values = ["tradebuy","tradesell"];
	$weights = [70,30];
	$c_category = weighted_random($weights, $values);
}


$c_category2 = $coinInfo->name.($c_category == "tradebuy" ? " Buy" : " Sell");
$c_return = $row["c_return"];


if ($c_category == "tradebuy") {
	$values = [$price["buy"] + $c_unit,$price["buy"] + 2*$c_unit,$price["buy"] + 3*$c_unit,$price["buy"] + 4*$c_unit];
	$weights = [40,30,20,10];

	$c_return = weighted_random($weights, $values);
	$c_return = ($price["sell"] < $c_return ? $price["sell"] : $c_return);

	//$c_return += $c_unit;
	if (!is_empty($amount)) {
		$exchage_most = $amount;
	} else {
		$exchage_most = bcmul($exchage_most, rand(2,10), 8);
	}
	$pay_most = bcmul($c_return, $exchage_most, 8);

	$c_exchange = conver_to_float($exchage_most + 0) . $coinInfo->name; 
	$c_payment = "-".conver_to_float($pay_most + 0) . $payInfo->name; 
} else {
	$values = [$price["sell"] - $c_unit,$price["sell"] - 2*$c_unit,$price["sell"] - 3*$c_unit,$price["sell"] - 4*$c_unit];
	$weights = [40,30,20,10];

	$c_return = weighted_random($weights, $values);
	$c_return = ($price["buy"] > $c_return ? $price["buy"] : $c_return);

	if (!is_empty($amount)) {
		$exchage_most = $amount;
	} else {
		$exchage_most = bcmul($exchage_most, rand(2,10), 8);
	}
	$pay_most = bcmul($c_return, $exchage_most, 8);

	$c_exchange = conver_to_float($pay_most + 0) . $payInfo->name; 
	$c_payment = "-".conver_to_float($exchage_most + 0) . $coinInfo->name; 

	/*
	$pay_most = bcdiv($pay_most, rand(1,3), 4);
	$c_exchange = bcmul($c_return, $pay_most, 4);

	//$c_return -= $c_unit;
	$c_exchange = conver_to_float($pay_most + 0) . $payInfo->name; 
	$c_payment = "-".conver_to_float($exchage_most + 0) . $coinInfo->name; 
	*/
}

err_log("get_ask_range: $c_category2=> sell:".$price["sell"].", buy:".$price["buy"].", return:$c_return, unit:$c_unit");

$c_div = $coinInfo->type;
$c_pay = $payInfo->type;
$c_ip = "127.0.0.1"; 
$c_no1 = 1;
$c_no2 = 2;

if ($c_exchange == 0 || $c_payment == 0) {
   exit;
}


$query = "INSERT INTO coin_point (c_div, c_pay, c_userno, c_id, c_exchange, c_payment, ";
$query .= " c_commission, c_category, c_category2, c_ip, c_return, c_no1, c_no2, c_signdate, c_dis)";
$query .= " VALUES";
$query .= "('$c_div', '$c_pay', '0', 'coex@miner.net', '$c_exchange', '$c_payment', 0, '$c_category', '$c_category2', '$c_ip', '$c_return', '$c_no1', '$c_no2', $date, 0);";

$stmt = pdo_excute("insert cp", $query, NULL);
if (!$stmt) {
	fatal_log("fail to insert cp");
	exit;
}
err_log("contract_miner done.");


function weighted_random($weights, $values) {
	$r = rand(1, array_sum($weights));
	$index = 0;
	for($i=0; $i<count($weights); $i++) {
		$r -= $weights[$i];
		if($r < 1) {
			$index = $i;
			break;
		}
	}
	return $values[$index];
}

?>
