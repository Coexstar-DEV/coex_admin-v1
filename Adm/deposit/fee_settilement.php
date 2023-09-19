<?php

//session_start();

include_once "../../Adm/common/init_table.php";
include_once "../../Adm/common/dbconn.php";
include_once "../../Adm/common/user_function.php";
include_once "../../Adm/common/trading.php";
include_once "../../Adm/common/wallet.php";
include_once "../../Adm/common/deposit.php";

// c_setup::c_owner , m_bankmoney::m_settlement 추가 
$LOG_LEVEL = 1;
$LOG_TAG = basename(__FILE__);


$kk2 = mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y"));
$kk1 = mktime(date("H") - 1, date("i"), date("s"), date("m"), date("d"), date("Y"));
$kk = mktime(date("H") - 1, date("i"), date("s"), date("m"), date("d"), date("Y"));


// 1. 코인별로 수수료를 조회. 날짜 조건은 어떻게 처리? 
//  날자조건 없이 ..  처음 부터 - 실행 날짜까지 ? 
// 2. 

$signdate = time();

$query = "SELECT c_no, c_coin,c_type, c_owner FROM c_setup ORDER BY c_no asc";
$stmt = pdo_excute("coin list", $query, NULL);

$userInfo = "";
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $userid = $row["c_owner"];
    if ($userInfo == "") {
        $userInfo = UserInfo::makeWithId($userid, "127.0.0.1");
    }

    $c_coin = $row["c_coin"];
    $c_title = $row["c_coin"];
    $c_no = $row["c_no"];
    $c_type = $row["c_type"];

    err_log($c_coin.":----------deposit process($userid:$balance) ------------");

    // 1. 코인별 수수료 조회  c_fees/member.php 참고.
    $sql = "SELECT ifnull(sum(m_fee+0),0) FROM m_bankmoney where m_div = '$c_no' and m_category like 'trade%' and m_signdate <='$kk2'";
    $stmt1 = pdo_excute("select_fee", $sql, NULL);
    $dat = $stmt1->fetch();
    $bank_sum = $dat[0] * -1;

    // 입금 처리. 
    $pdo->beginTransaction();
    try {
        $deposit = new Deposit($userInfo, $coinInfo);
        $deposit->deposit($balance, "settle", "");
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

