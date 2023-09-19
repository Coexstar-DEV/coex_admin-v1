<?php

include_once "../../Adm/common/init_table.php";
include_once "../../Adm/common/dbconn.php";
include_once "../../Adm/common/user_function.php";
include_once "../../Adm/common/trading.php";
include_once "../../Adm/common/wallet.php";
include_once "../../Adm/common/deposit.php";

//$LOG_LEVEL = 1;
$LOG_TAG = basename(__FILE__);


$kk2 = mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y"));
$kk1 = mktime(date("H") - 1, date("i"), date("s"), date("m"), date("d"), date("Y"));
$kk = mktime(date("H") - 1, date("i"), date("s"), date("m"), date("d"), date("Y"));


$c_coin = $_REQUEST["c_coin"];
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

if ($c_type != "4" ) {  // 4 eos 
    fatal_log("[$c_coin] Not EOS type: $c_type, name:$c_coin");
    exit;
}

$lastseq = is_empty($coinInfo->lasttx) ? "0" : $coinInfo->lasttx;
$master_wallet = get_master_wallet($c_type, $c_coin);

$api = "http://$WALLET_API_URL/$c_coin/v1/eos/getTx?account=".$master_wallet['account']."&seqMin=$lastseq&isReceive=1";
$ch = make_curl2($api, NULL);
err_log("getDepositList :$c_coin: curl '$api'");

$results = curl_exec($ch);

/* test code ---------------------------- 
$array_result = array (
    'result' => 
    array (
      0 => 
      array (
        'balance' => '5.0000',
        'balance_fulltext' => '5.0000 EOS',
        'contract' => 'eosio.token',
        'txid' => 'bd771d7a72662a41d611965ea4efff4fcc3e7bb6ebb363b87a3f0cad81592363',
        'memo' => '16',
        'from' => 'coinonewallt',
        'to' => 'camonpaybest',
        'seq' => 17,
      ),
      1 => 
      array (
        'balance' => '3.0000',
        'balance_fulltext' => '5.0000 EOS',
        'contract' => 'eosio.token',
        'txid' => 'cd771d7a72662a41d611965ea4efff4fcc3e7bb6ebb363b87a3f0cad81592363',
        'memo' => '16',
        'from' => 'coinonewallt',
        'to' => 'camonpaybest',
        'seq' => 111,
      ),
    ),
    'status' => 'success',
);
$results = json_encode($array_result);
*/
// test code ---------------------------- 
    

if ($results === false) {
    fatal_log("getDepositList:code:".curl_error($ch).">  curl '$api' ");
    $r_code = ERR_NETWORK;
} else {
    $r_code = SUCCEED;
}
wallet_result_check($c_coin, $r_code);

if ($r_code == ERR_INVALID_PARAM )  {
    fatal_log("$r_code: ERR_INVALID_PARAM:".__LINE__);
    exit;
}

err_log("[$c_coin]=>result:$results");
$array_result = json_decode($results, true);

//var_export($array_result, true);

$accoutList = $array_result["result"];

// accountList 에 아이디가 여러번 중복되어 있을때 처리? 

/*******************/
$recv_seq = 0;
$checked_account = array();
foreach($accoutList as $item) {
    $LOG_LEVEL = 1;

    $user_no = $item['memo'];
    $balance = $item['balance'];
    $txid = $item['txid'];
    $seq = $item['seq'];

    $recv_seq = ($seq > $recv_seq ? $seq : $recv_seq);

    if ($lastseq > $seq) {
        fatal_log("illigal sequence num last:$lastseq, seq:$seq");
        continue;
    } 

    $userInfo = UserInfo::makeWithNo($user_no, "127.0.0.1");
    if (!is_empty($userInfo)) {
        $user_id = $userInfo->id;
        err_log($c_coin."=>DEPOSITV2 id:$user_id, memo:".$user_no.", hash:".$item["txid"]);

        $suspend_yn = $coinInfo->suspend_yn;
        if ($suspend_yn != "0" && $userInfo->row["m_admin_no"] == "0") { // suspend 상태이고, 일반 유저 이면, 입금금지.
            err_log("suspended -----------:$user_id");
            continue;
        }

        $q = "SELECT COUNT(*) as dcount FROM coin_point WHERE c_id = '$user_id' and c_div = '$coinInfo->type' and c_category2 = '$txid'";
        $stmt = pdo_excute("count", $q, null);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row['dcount'] > 0) {
           fatal_log("already deposited CHECK !!!!!!!!: user:$user_id, category2:$txid, seq:$seq");

        } else {
            $api = "http://127.0.0.1/Adm/deposit/recive_coin_manual.php?c_coin=$c_coin&user_id=$user_id&balance=$balance&cause=$txid";
            $param = NULL;
            $ch = WalletAPI::make_curl($api, $param);
            err_log("[$c_coin] DEPOSITV2: curl '$api'");
            $result = curl_exec($ch);
            if ($result === false) {
                fatal_log("recive_coin : ".curl_errno($ch).":".curl_error($ch));
                return;
            }
        }

    } else {
        err_log("[$c_coin] =>skip -parse warning: coin:$c_coin, userInfo:empty, memo:$user_no, txid:".$txid);
    }
    $LOG_LEVEL = 0;
}
/*******************/

$lastseq = ( $recv_seq >= $lastseq ? $recv_seq+1 : $lastseq);
$coinInfo->setLastTx($lastseq);

$done = date("Y/m/d H:i:s");
err_log("[$c_coin] $done========= end deposit ");
echo("[$c_coin] $done========= end deposit ");
?>

