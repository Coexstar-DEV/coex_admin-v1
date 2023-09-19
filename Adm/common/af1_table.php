<?php 




function get_af1_wallet($coin_type, $coin_name)
{
    global $dbname;
    err_log($coin_name.":get_af1_wallet:$coin_type");
    if($coin_name == "AF1"){
        $wallet["account"] = "AHH3SdNyfcVJhDiR2oUbs5jLqDgUuF4DBf";
        $wallet["pwd"] = "";
        return $wallet;
    }
}


?>