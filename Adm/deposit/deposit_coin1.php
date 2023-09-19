<?php

//session_start();

include_once "../../Adm/common/init_table.php";
include_once "../../Adm/common/dbconn.php";
include_once "../../Adm/common/user_function.php";
include_once "../../Adm/common/trading.php";
include_once "../../Adm/common/wallet.php";
include_once "../../Adm/common/deposit.php";

$LOG_LEVEL = 1;
$LOG_TAG = basename(__FILE__);


$kk2 = mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y"));
$kk1 = mktime(date("H") - 1, date("i"), date("s"), date("m"), date("d"), date("Y"));
$kk = mktime(date("H") - 1, date("i"), date("s"), date("m"), date("d"), date("Y"));


$c_coin = $_REQUEST["c_coin"];
$blocknum = $_REQUEST["blocknum"];
if (is_empty($c_coin)) {
    fatal_log("invalid param : c_coin is empty");
    echo "invalid param : c_coin is empty";
    return;
}

$coinInfo = new CoinInfo($c_coin);
$signdate = time();

$c_type = $coinInfo->c_type;
//1. wallet 서버로  getDepositList() 를 호출
//2. account list 를 받으면 , 해당 user 에게 recive_coin 호출. 


if ($c_type == "1" ||  $c_type == "2" || $c_type == "7" ) {  //eth 계열 : 입금 리스트를 가져온다. 
    $name = ($c_coin == "CELO" ? "CELO" : "ETH" );  // name for ETH, CELO
    $lasttx = is_empty($coinInfo->lasttx) ? "lasttx" : $coinInfo->lasttx;

    if (is_empty($blocknum)) {
        $api = "http://$WALLET_API_URL/$name/txInfo?hash=$lasttx";
    } else {
        $api = "http://$WALLET_API_URL/$name/txInfo?hash=$blocknum&depth=1";
    }

    $ch = make_curl2($api, NULL);
    err_log("getDepositList :$name: curl '$api'");

    $results = curl_exec($ch);
    if ($results === false) {
        fatal_log("getDepositList:code:".curl_error($ch).">  curl '$api' ");
        exit;
    } 

} else {
    fatal_log("[$c_coin] Not eth, yoc, token type: $c_type, name:$c_coin");
    exit;
}

err_log("[$c_coin]=>result:$results");
$array_result = json_decode($results, true);
//$accoutList = [$dbname."_plutok@hanmail.net@111111", $dbname."_passion0470@naver.com@2"];
//$accoutList = array_reverse($accoutList);

var_export($array_result, true);


$accoutList = $array_result["list"];

// accountList 에 아이디가 여러번 중복되어 있을때 처리? 

/*******************/
$checked_account = array();
foreach($accoutList as $item) {
    $LOG_LEVEL = 1;

    $coin_name = strtoupper($item["type"]);
    $coin_name = $coin_name == "CGLD" ? "CELO" : $coin_name;
    //$coin_type = ($coin_name == "ETH" ? "1" :  ($coin_name == "" ? "" )"2" );
    $recvInfo = new CoinInfo($coin_name);
    $coin_type = $recvInfo->c_type;

    $user_id = UserInfo::getIdFromWallet($coin_type, $c_coin, $item["wallet"]);

    err_log($item["type"]."=>DEPOSITV2 id:$user_id, wallet:".$item["wallet"].", hash:".$item["txid"]);
    if (!is_empty($user_id)) {
        $userInfo = UserInfo::makeWithId($user_id, "127.0.0.1");

        $suspend_yn = $coinInfo->suspend_yn;
        if (($suspend_yn == "1" || $suspend_yn == "3") && $userInfo->row["m_admin_no"] == "0") { // suspend 상태이고, 일반 유저 이면, 입금금지.
            err_log("suspended -----------:$user_id");
            continue;
        }


        $txid = $item["txid"];
        $api = "http://127.0.0.1/Adm/deposit/recive_coin.php?c_coin=$coin_name&user_id=$user_id&txid=$txid";
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

if (is_empty($blocknum)) {
    $lasttx = $array_result["blocknum"];
    $coinInfo->setLastTx($lasttx);
}

$done = date("Y/m/d H:i:s");
err_log("[$c_coin] $done========= end deposit ");
echo("[$c_coin] $done========= end deposit ");
?>

