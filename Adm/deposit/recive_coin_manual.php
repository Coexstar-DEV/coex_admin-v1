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

$allkeys = array();
$balkeys = array();
if (isset($_REQUEST["table"])) {
    // for tables
    $table = $_REQUEST["table"]; 
    $q = "SELECT e_id, e_amount FROM event_deposit where e_done = '0' order by e_no asc";
    $stmt = pdo_excute("select table", $q, NULL);
    while ($row = $stmt->fetch()) {
        $allkeys[] = $row[0];
        $balkeys[] = $row[1];
    }

    $c_coin = $_REQUEST["c_coin"];
    if (is_empty($c_coin)) {
        fatal_log("invalid param : c_coin is empty");
        return;
    }


    $cause = $_REQUEST["cause"];
    if (is_empty($cause)) {
        fatal_log("invalid param : cause is empty");
        return;
    }
} else {
    // for only one user;
    if (isset($_REQUEST["user_id"])) {
        $user_id = $_REQUEST["user_id"];
        $allkeys[] = $user_id;
    } else {
        fatal_log("NOT found id");
        return;
    }

    $c_coin = $_REQUEST["c_coin"];
    if (is_empty($c_coin)) {
        fatal_log("invalid param : c_coin is empty");
        return;
    }

    $bal = $_REQUEST["balance"];
    if (is_empty($bal)) {
        fatal_log("invalid param : balance is empty");
        return;
    }
    $balkeys[] = $bal;

    $cause = $_REQUEST["cause"];
    if (is_empty($cause)) {
        fatal_log("invalid param : cause is empty");
        return;
    }

}

$coinInfo = new CoinInfo($c_coin);

$num_user = count($allkeys);
$num_bal = count($balkeys);
$signdate = time();

if ($num_user != $num_bal) {
    fatal_log("invalid count : user != balance");
    return;
}

err_log("========= start $coinInfo->name deposit , count($num_user), suspend:$coinInfo->suspend_yn");
for ($i = 0; $i < $num_user; $i++) {
    $userid = $allkeys[$i];
    $balance = $balkeys[$i];
    $userInfo = UserInfo::makeWithId($userid, "127.0.0.1");

    $c_coin = $coinInfo->name;
    $c_title = $coinInfo->title;
    $coin_type = $coinInfo->type;
    $c_type = $coinInfo->c_type;

    if(is_empty($userInfo->no)) {
        fatal_log("NOT FOUND USER:".$userid);
        continue;
    }

    err_log($c_coin.":----------deposit process($userid:$balance) ------------");

    // 입금 처리. 
    $pdo->beginTransaction();
    try {
        $deposit = new Deposit($userInfo, $coinInfo);
        $deposit->deposit($balance, $cause, "");


        if (!is_empty($table)) {
            $q = "UPDATE event_deposit SET e_done = 1 WHERE e_id = '$userid'";
            $result = pdo_excute("update", $q, NULL);
            if (!$result) {
                throw new Exception("Failed to update id:$user_id >", __LINE__);
            }
        }
        $pdo->commit();
    } catch (PDOException $e) {
        $s = $e->getMessage() . ' (code:' . $e->getCode() . ')';
        fatal_log($c_coin.":$s");
        $pdo->rollBack();
    }

    err_log($c_coin.":----------deposit done($userid:$balance) ------------");
} 

echo "DONE";
err_log("========= end $coinInfo->name deposit , count($num_user)");
?>

