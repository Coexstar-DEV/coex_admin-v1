<?php

include_once "../../Adm/common/init_table.php";
include_once "../../Adm/common/dbconn.php";
include_once "../../Adm/common/user_function.php";
include_once "../../Adm/common/trading.php";
include_once "../../Adm/common/wallet.php";
include_once "../../Adm/common/deposit.php";

//$LOG_LEVEL = 1;
$LOG_TAG = basename(__FILE__);

$c_coin = $_REQUEST["c_coin"];
if (is_empty($c_coin)) {
    fatal_log("invalid param : c_coin is empty");
    echo "invalid param : c_coin is empty";
    return;
}

$coinInfo = new CoinInfo($c_coin);
$signdate = time();

$c_type = $coinInfo->c_type;
$coin_name = "XRP";

if ($c_coin != "XRP") {
    fatal_log("[$c_coin] Not XRP token type: $c_type, name:$c_coin");
    exit;
}

//1. master xrp 지갑 에 입금이 됐는지 확인. 
$wallet = get_master_wallet($c_type, "XRP");
$lasttx = is_empty($coinInfo->lasttx) ? "-1" : ($coinInfo->lasttx+1);

$address = $wallet["account"];
$api = "http://$WALLET_API_URL/XRP/api/getTx?account=$address&ledgerIndexMin=$lasttx&isReceive=1";
$ch = make_curl2($api, NULL);
err_log("getDepositList :$coin_name: curl '$api'");

$results = curl_exec($ch);
if ($results === false) {
    fatal_log("getDepositList:code:".curl_error($ch).">  curl '$api' ");
    $r_code = ERR_NETWORK;
} else {
    $r_code = SUCCEED;
}
// eth, token 모두 가져 온다. 
// 
wallet_result_check($c_coin, $r_code);


if ($r_code == ERR_INVALID_PARAM )  {
    fatal_log("$r_code: ERR_INVALID_PARAM:".__LINE__);
    exit;
}

err_log("[$c_coin]=>result:$results");
$array_result = json_decode($results, true);

var_export($array_result, true);

$accoutList = $array_result["result"];

/*******************/
foreach($accoutList as $item) {
    $LOG_LEVEL = 1;
    $coin_name = "XRP";
    $userno = $item["DestinationTag"];
    $ledgerIdx = $item["ledger_index"];

    if ($lasttx < $ledgerIdx)
        $lasttx = $ledgerIdx; // update lasttx;

    $userInfo = UserInfo::makeWithNo($userno, "127.0.0.1");
    $user_id = $userInfo->id;

    err_log($coin_name."=>DEPOSITV2 id:$user_id, tag:".$userno.", ledger:".$item["ledger_index"]);
    if (!is_empty($user_id)) {

        $suspend_yn = $coinInfo->suspend_yn;
        if (($suspend_yn == "1" || $suspend_yn == "3") && $userInfo->row["m_admin_no"] == "0") { // suspend 상태이고, 일반 유저 이면, 입금금지.
            err_log("suspended -----------:$user_id");
            continue;
        }

        //$amount = $item["Amount_xrp"];
        $amount = $item["delivered_amount_xrp"];
        $hash = $item["hash"];
        $api = "http://127.0.0.1/Adm/deposit/recive_coin_manual.php?c_coin=$coin_name&user_id=$user_id&balance=$amount&cause=$hash";
        $param = NULL;
        $ch = WalletAPI::make_curl($api, $param);
        err_log("[$coin_name] DEPOSITV2: curl '$api'");
        $result = curl_exec($ch);
        if ($result === false) {
            fatal_log("recive_coin : ".curl_errno($ch).":".curl_error($ch));
            return;
        }

    } else {
        err_log("[$coin_name] =>skip -parse warning: coin:$coin_name, userid:empty, wallet:".$item["wallet"]);
    }
    $LOG_LEVEL = 0;
}
/*******************/

$coinInfo->setLastTx($lasttx);

$done = date("Y/m/d H:i:s");
err_log("[$c_coin] $done========= end deposit ");
echo("[$c_coin] $done========= end deposit ");
?>

