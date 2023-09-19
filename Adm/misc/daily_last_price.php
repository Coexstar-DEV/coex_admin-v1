<?php

include_once "../../Adm/common/init_table.php";
include_once "../../Adm/common/dbconn.php";
include_once "../../Adm/common/user_function.php";
include_once "../../Adm/common/trading.php";
include_once "../../Adm/common/wallet.php";
include_once "../../Adm/common/deposit.php";

$LOG_LEVEL = 1;
$LOG_TAG = basename(__FILE__);

$marketList = [
    "KRWC" => "ABAG/SCOR",
    "USDT" => "ABAG/SCOR",
];


try {
    $c_date = time(); //date("Y-m-d H:i:s", time());
    $signdate = date("Y-m-d H:i:s", $c_date);


    $ref1 = mktime(9, 0, 0, date("m"), date("d"), date("Y")); 
    $ref2 = mktime(9, 0, 0, date("m"), date("d")-1, date("Y")); 
    $ref_now = mktime(); 

    $ref_time = $ref_now > $ref1 ? $ref1 : $ref2;


    $q = "SELECT count(*) FROM $c_daily_limit_tb WHERE c_date >= '$ref_time'";
    $stmt = pdo_excute("day_price", $q, null);
    $rown = $stmt->fetch()[0];
    if ($rown > 0) {
        $ref1 = date("m-d H:i:s", $ref1);
        $ref2 = date("m-d H:i:s", $ref2);
        $ref_time = date("m-d H:i:s", $ref_time);
        err_log("$signdate <=== ALREADY day_price now:$ref_now ref1:$ref1, ref2:$ref2, ref_time:$ref_time");
        exit;
    } else {
        $ref1 = date("m-d H:i:s", $ref1);
        $ref2 = date("m-d H:i:s", $ref2);
        $ref_time = date("m-d H:i:s", $ref_time);
        err_log("$signdate ===> UPDATE day_price now:$ref_now ref1:$ref1, ref2:$ref2, ref_time:$ref_time");
    }

    foreach ($marketList as $key => $value) {

        $coins = explode('/', $value);
        $c_pay = $key;
        $payInfo = new CoinInfo($c_pay);

        foreach ($coins as $c_coin) {
            $coinInfo = new CoinInfo($c_coin);

            $c_type = $coinInfo->type;
            $p_type = $payInfo->type;

            if (is_empty($c_type) || is_empty($p_type)) {
                fatal_log("invalid param2 : c_coin:$c_type, c_pay:$p_type");
                echo ("invalid param2 : c_coin:$c_type, c_pay:$p_type");
                continue;
            }

            // last 체결가 -   ABAG, SCOR 봇 로직 변경 해야함. 저가 limit 이 daily limt 을 참조하도록.
            $coin_state = coin_getlast($c_type, $p_type);
            $last_price = $coin_state['last1'];
            //err_log("=> LAST_PRICE:$last_price");

            $next_limit = bcmul($last_price, 0.95, 8);
            $q = "INSERT INTO $c_daily_limit_tb(c_div,c_pay,c_price,c_next_limit,c_signdate,c_date) VALUES($c_type, $p_type, $last_price,$next_limit,'$signdate', $c_date)";
            pdo_excute("insert_dailylimit", $q, null);

            err_log("$signdate ===>$c_pay|$c_coin = LAST_PRICE: $last_price ");

            // c_daily_limit 에 기록을 남긴다.

        }

    }

} catch (Exception $e) {
    fatal_log("daily price => error:".$e->getMessage().", code:".$e->getCode());
}
