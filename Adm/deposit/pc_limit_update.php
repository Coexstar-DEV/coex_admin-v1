<?php
include_once "../../Adm/common/dbconn.php";
include_once "../../Adm/common/user_function.php";

$LOG_LEVEL = 1;
$LOG_TAG = basename(__FILE__);

try {
    err_log("call pc_limit_update() +++");
    $query = "call pc_limit_update();";
    $stmt = pdo_excute("pc_limit_update", $query, NULL);
    err_log("call pc_limit_update() ---");
    echo "call pc_limit_update() done";
    exit;

} catch(Exception $e) {
    $s = $e->getMessage() . ' (code:' . $e->getCode() . ')';
    fatal_log($s);
    echo json_encode(array('result'=>'-1','msg'=>$e->getMessage()));
    exit;
}

?>