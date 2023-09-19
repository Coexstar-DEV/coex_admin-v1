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

//$LOG_LEVEL = 1;
$LOG_TAG = basename(__FILE__);

//$interval = isset($_REQUEST["interval"]) ? $_REQUEST["interval"] : 5;

$coinList = [
    "BTC" => "1/15", "ETH" => "2/25", "LTC" => "7/25", "BTG" => "14/60"
    , "BCH" => "13/60", "XRP" => "36/25", "RVN" => "41/60", "EOS" => "43/60"
    , "ENJ" => "44/60", "TRX" => "45/30" , "WWW" => "53/30", 
    "QTUM" => "39/30", "AF1" => "54/15","CXST" => "49/20","IPSC" => "51/20",
];

$payList = [
    "PHP" => "0",
    "KRWC" => "3",
    "USDT" => "4",
];

$actionList = [];

foreach ($payList as $payInfo_name => $payInfo_type) {

    foreach ($coinList as $key => $value) {

		if ($payInfo_name == $key ) {
			continue; // BTC/BTC, ETH/ETH
        }
        

        $values = explode('/', $value);
        // $values[0] = coin no, $values[1] = interval;
        $coinInfo_type = $values[0];
        $coinInfo_name = $key;
        $coinInfo_interval = $values[1];


        $q1 = "SELECT * FROM m_setup A JOIN c_setup B ON A.m_div = B.c_no WHERE B.c_coin = '$coinInfo_name' and A.m_pay='$payInfo_name' and A.m_use=1";
        $stmt = pdo_excute("selectuse", $q1, null);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            err_log("coin:$coinInfo_name, pay:$payInfo_name interval:$coinInfo_interval  , not used continue...");
            continue;
        }


        // 랜덤으로 실행될지 안될지 체크 ===========================
        $ran = rand(1, $coinInfo_interval);
        $mod = $ran % $coinInfo_interval;
        if ($mod != 0) {
            err_log("coin:$coinInfo_name, interval:$coinInfo_interval  , rand:$ran  continue");
            continue;
        } else {
            err_log("coin:$coinInfo_name, mining -- interval:$coinInfo_interval  , rand:$ran ");
        }

        // 5분안에 거래가 있는지 체크 ===========================
        $date = time();
        $date_inter = $date - 5 * 60;
        //
        $query = "SELECT c_no,c_exchange+0 as c_exchange, c_payment+0 as c_payment, c_category,c_return+0 as c_return,c_signdate  FROM coin_point";
        $query .= " WHERE c_limityn = 'Y' and c_div=? and c_pay = ? and c_id <> 'coex@miner.net' order by c_no desc limit 1";
        $stmt = pdo_excute("select cp", $query, [$coinInfo_type, $payInfo_type]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            err_log("bypass not found trade - date_innter");
        } else {
            $c_signdate = $row["c_signdate"];
            if ($date_inter - $c_signdate < $interval) {
                err_log("skip - date_innter2:sig:$c_signdate, interval:$date_inter");
                continue;
            }
        }

        // ============== 거래 이력 남기기 ==================

        // 거래 가능 가격범위 산출
        $c_unit = get_coin_unit($coinInfo_type, $payInfo_name);
        // 호가 찾기
        $price = miner_ask_range($coinInfo_type, $payInfo_type);
        $amount = miner_amount_range_random($coinInfo_type, $payInfo_type);

        if ($price["buy"] == 0 || $price["sell"] == 0) {
            continue;
        }

        $pay_most = -1 * $row["c_payment"];
        $exchage_most = $row["c_exchange"];
        $c_category = $row["c_category"];

        if ($c_category == "tradebuy") {
            $c_category = weighted_random([80, 20], ["tradebuy", "tradesell"]);
        } else {
            $c_category = weighted_random([70, 30], ["tradebuy", "tradesell"]);
        }

        $c_category2 = $coinInfo_name . ($c_category == "tradebuy" ? " Buy" : " Sell");
        $c_return = $row["c_return"];

        if ($c_category == "tradebuy") {
            $values = [$price["buy"] + $c_unit, $price["buy"] + 2 * $c_unit, $price["buy"] + 3 * $c_unit, $price["buy"] + 4 * $c_unit];
            $weights = [40, 30, 20, 10];

            $c_return = weighted_random($weights, $values);
            $c_return = ($price["sell"] < $c_return ? $price["sell"] : $c_return);

            //$c_return += $c_unit;
            if (!is_empty($amount)) {
                $exchage_most = $amount;
            } else {
                $exchage_most = bcmul($exchage_most, rand(2, 10), 8);
            }
            $pay_most = bcmul($c_return, $exchage_most, 8);

            $c_exchange = conver_to_float($exchage_most + 0) . $coinInfo_name;
            $c_payment = "-" . conver_to_float($pay_most + 0) . $payInfo_name;
        } else {
            $values = [$price["sell"] - $c_unit, $price["sell"] - 2 * $c_unit, $price["sell"] - 3 * $c_unit, $price["sell"] - 4 * $c_unit];
            $weights = [40, 30, 20, 10];

            $c_return = weighted_random($weights, $values);
            $c_return = ($price["buy"] > $c_return ? $price["buy"] : $c_return);

            if (!is_empty($amount)) {
                $exchage_most = $amount;
            } else {
                $exchage_most = bcmul($exchage_most, rand(2, 10), 8);
            }
            $pay_most = bcmul($c_return, $exchage_most, 8);

            $c_exchange = conver_to_float($pay_most + 0) . $payInfo_name;
            $c_payment = "-" . conver_to_float($exchage_most + 0) . $coinInfo_name;

        }

        err_log("get_ask_range: $c_category2=> sell:" . $price["sell"] . ", buy:" . $price["buy"] . ", return:$c_return, unit:$c_unit");

        $c_div = $coinInfo_type;
        $c_pay = $payInfo_type;
        $c_ip = "127.0.0.1";
        $c_no1 = 1;
        $c_no2 = 2;

	if ($c_exchange == 0 || $c_payment == 0) {
		continue;
	}

        $query = "INSERT INTO coin_point (c_div, c_pay, c_userno, c_id, c_exchange, c_payment, ";
        $query .= " c_commission, c_category, c_category2, c_ip, c_return, c_no1, c_no2, c_signdate, c_dis)";
        $query .= " VALUES";
        $query .= "('$c_div', '$c_pay', '0', 'coex@miner.net', '$c_exchange', '$c_payment', 0, '$c_category', '$c_category2', '$c_ip', '$c_return', '$c_no1', '$c_no2', $date, 0);";

        $stmt = pdo_excute("insert cp", $query, null);
        if (!$stmt) {
            fatal_log("fail to insert cp");
            continue;
        }

        $actionList[] = $coinInfo_name . "/" . $payInfo_name;

    }
}

fatal_log("contract_miner done. " . var_export($actionList, true));

function weighted_random($weights, $values)
{
    $r = rand(1, array_sum($weights));
    $index = 0;
    for ($i = 0; $i < count($weights); $i++) {
        $r -= $weights[$i];
        if ($r < 1) {
            $index = $i;
            break;
        }
    }
    return $values[$index];
}
