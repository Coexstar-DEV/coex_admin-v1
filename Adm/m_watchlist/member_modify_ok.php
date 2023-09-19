<meta charset="utf-8">
<?
session_start();

include "../common/dbconn.php";
include "../common/user_function.php";

$LOG_LEVEL = 1;
$LOG_TAG = basename(__FILE__);

$adminlevel = $_SESSION["level"];
check_manager_level($adminlevel, ADMIN_LVL2);

err_log("===>" . parse_array($_REQUEST));

if (isset($_SESSION["admin_id"])) {
    $admin_id = $_SESSION["admin_id"];
} else {
    $admin_id = "";
}

if (isset($_POST["m_id"])) {
	$m_id = sqlfilter($_POST["m_id"]);
} else {
	$m_id = "";
}

if (isset($_POST["m_alias"])) {
	$m_alias = sqlfilter($_POST["m_alias"]);
} else {
	$m_alias = "";
}



if (isset($_POST["m_validid"])) {
	$m_validid = sqlfilter($_POST["m_validid"]);
} else {
	$m_validid = "";
}

if (isset($_POST["m_validnum"])) {
	$m_validnum = sqlfilter($_POST["m_validnum"]);
} else {
	$m_validnum = "";
}


if (isset($_POST["id"])) {
	$id = sqlfilter($_POST["id"]);
} else {
	$id = "";
}

if (isset($_POST["m_name"])) {
	$m_name = sqlfilter($_POST["m_name"]);
} else {
	$m_name = "";
}

if (isset($_POST["m_handphone"])) {
	$m_handphone = sqlfilter($_POST["m_handphone"]);
} else {
	$m_handphone = "";
}

if (isset($_POST["m_country"])) {
    $m_country = explode('@@', sqlfilter($_POST["m_country"]))[0];
    $m_countryname = explode('@@', sqlfilter($_POST["m_country"]))[1];
} else {
    $m_country = "";
    $m_countryname = "";
}
if (isset($_POST["m_signdate"])) {
	$m_signdate = sqlfilter($_POST["m_signdate"]);
} else {
	$m_signdate = "";
}


if (isset($_POST["m_remarks"])) {
	$m_remarks = sqlfilter($_POST["m_remarks"]);
} else {
	$m_remarks = "";
}


if (isset($_POST["m_birthday"])) {
	$m_birthday = sqlfilter($_POST["m_birthday"]);
} else {
	$m_birthday = "";
}
if (isset($_POST["m_address"])) {
	$m_address = sqlfilter($_POST["m_address"]);
} else {
	$m_address = "";
}

if (isset($_POST["m_validid"])) {
	$m_validid = sqlfilter($_POST["m_validid"]);
} else {
	$m_validid = "";
}

if (isset($_POST["m_validnum"])) {
	$m_validnum = sqlfilter($_POST["m_validnum"]);
} else {
	$m_validnum = "";
}

if (isset($_POST["m_regcheck"])) {
	$m_regcheck = sqlfilter($_POST["m_regcheck"]);
} else {
	$m_regcheck = "";
}



if (isset($_POST["m_updatedate"])) {
    $m_updatedate = sqlfilter($_POST["m_updatedate"]);
} else {
    $m_updatedate = "";
}

if (isset($_POST["keyfield"])) {
    $keyfield = sqlfilter($_POST["keyfield"]);
} else {
    $keyfield = "";
}
if (isset($_POST["key"])) {
    $key = sqlfilter($_POST["key"]);
} else {
    $key = "";
}
if (isset($_POST["page"])) {
    $page = sqlfilter($_POST["page"]);
} else {
    $page = "";
}


if (isset($_REQUEST["m_nameold"])) {
    $m_nameold = sqlfilter($_POST["m_nameold"]);
} else {
    $m_nameold = "";
}

if (isset($_SESSION["admip"])) {
    $admip = $_SESSION["admip"];
} else {
    $admip = "";
}

if (isset($_POST["m_type"])) {
    $m_type = sqlfilter($_POST["m_type"]);
    
    if($_POST["m_type"] == 'Others') {
        if (isset($_POST["m_coin"])) {
            $m_coin = sqlfilter($_POST["m_coin"]);
        } else {
            $m_coin = "";
        }
        
        if (isset($_POST["m_wallet"])) {
            $m_wallet = $_POST["m_wallet"];
        } else {
            $m_wallet = "";
        }
    }
} else {
	$m_type = "";
}

$m_updatedate = time();
$m_lasteditor = $admin_id . "/" . $admip . "/" . date("Y-m-d");


 $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

try {

    $query_pdo = "UPDATE $watchlist SET";
    $query_pdo .= " m_id=:m_id,m_name=:m_name,m_alias=:m_alias, m_handphone=:m_handphone, m_birthday=:m_birthday, m_country=:m_country, m_countryname=:m_countryname, m_address=:m_address, m_validid=:m_validid, m_validnum=:m_validnum";
    $query_pdo .= ",m_updatedate=:m_updatedate,m_remarks=:m_remarks,m_adminno=:m_adminno, m_block=:m_block, m_lasteditor=:m_lasteditor, m_regcheck=:m_regcheck, m_coin=:m_coin, m_wallet=:m_wallet, m_type = :m_type";
    $query_pdo .= " WHERE id = :id ";
    
    $stmt = $pdo->prepare($query_pdo);
    $stmt->bindValue("id", $id);
    $stmt->bindValue("m_id", $m_id);
    $stmt->bindValue("m_name", $m_name);
    $stmt->bindValue("m_alias", $m_alias);
    $stmt->bindValue("m_handphone", $m_handphone);
    $stmt->bindValue("m_birthday", $m_birthday);
    $stmt->bindValue("m_country", $m_country);
    $stmt->bindValue("m_countryname", $m_countryname);
    $stmt->bindValue("m_address", $m_address);
    $stmt->bindValue("m_validid", $m_validid);
    $stmt->bindValue("m_validnum", $m_validnum);
    $stmt->bindValue("m_updatedate", $m_updatedate);
    $stmt->bindValue("m_remarks", $m_remarks);
    $stmt->bindValue("m_adminno", $admin_id);
    $stmt->bindValue("m_block", $m_block);
    $stmt->bindValue("m_lasteditor", $m_lasteditor);
    $stmt->bindValue("m_regcheck", $m_regcheck);
    $stmt->bindValue("m_coin", $m_coin);
    $stmt->bindValue("m_wallet", $m_wallet);
    $stmt->bindValue("m_type", $m_type);
    $updated = $stmt->execute();

    err_log("--- done ----id:$id, m_id:$m_id");

    $key = (isset($_REQUEST["key"]) ? $_REQUEST["key"] : "");
    $encoded_key = urlencode($key);

    echo ("<meta http-equiv='Refresh' content='0; URL=member.php?keyfield=$keyfield&key=$encoded_key&page=$page&m_countryname=$m_countryname'>");
} catch (\Exception $e) {

    err_log("err_msg:" . $e->getMessage());
    error("QUERY_ERROR");
}

?>