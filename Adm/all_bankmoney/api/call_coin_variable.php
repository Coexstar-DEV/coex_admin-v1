<?php

$DB_READ_ONLY = 1;

include "../../common/user_function.php";
include "../../common/dbconn.php";

$key_value = $_REQUEST["member_no"];

$query_pdo="SELECT c_no,c_coin,c_title FROM $table_setup where c_use='1' ORDER BY c_rank+0 asc ";
$stmt = $pdo->prepare($query_pdo);
$stmt->execute();

$result_arr = array();
while ($c_total_value = $stmt->fetch()) {
    
    $c_no = $c_total_value[0];
    $c_coin = $c_total_value[1];
    $c_title = $c_total_value[2];
    
    $querytotal_pdo2 = "SELECT sum(m_cointotal+0) from ".$m_bankmoney." where m_no in (select max(m_no) from ".$m_bankmoney." where m_div=? and m_limityn = 'Y' group by m_id) ";
    $querytotal_pdo2 .= " order by (m_cointotal+0) desc";
    $stmt2 = $pdo -> prepare($querytotal_pdo2);
    $stmt2 -> execute(array($c_no));
    $m_cointotal = $stmt2->fetch();
    $c_cointotal = $m_cointotal[0];
    if($c_cointotal == "") $c_cointotal = "0"; 

    $result = array(
        "c_title"=>$c_title,
        "c_coin"=>$c_coin,
        "m_cointotal"=>numberformat($c_cointotal,'money','8')
    );

    $result_arr[]  = $result;
}

$result2 = json_encode($result_arr);

echo $result2;
?>