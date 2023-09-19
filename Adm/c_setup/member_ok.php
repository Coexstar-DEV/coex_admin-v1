<meta charset="utf-8">
<?

include "../common/dbconn.php";
include "../common/user_function.php";
include "../inc/adm_chk.php";

if (isset($_SESSION["admin_id"])) {
	$admin_id = $_SESSION["admin_id"];
} else {
	$admin_id = "";
}
$LOG_LEVEL = 1;
$LOG_TAG = basename(__FILE__);

if (isset($_POST["c_no"])) {
    $c_no = sqlfilter($_POST["c_no"]);
} else {
    $c_no = "";
}
if (isset($_POST["c_coin"])) {
    $c_coin = sqlfilter($_POST["c_coin"]);
} else {
    $c_coin = "";
}
if (isset($_POST["c_wcommission"])) {
    $c_wcommission = sqlfilter($_POST["c_wcommission"]);
} else {
    $c_wcommission = "";
}
if (isset($_POST["c_limit"])) {
    $c_limit = sqlfilter($_POST["c_limit"]);
} else {
    $c_limit = "";
}
if (isset($_POST["c_asklimit"])) {
    $c_asklimit = sqlfilter($_POST["c_asklimit"]);
} else {
    $c_asklimit = "";
}
if (isset($_POST["c_unit"])) {
    $c_unit = sqlfilter($_POST["c_unit"]);
} else {
    $c_unit = "";
}
if (isset($_POST["c_use"])) {
    $c_use = sqlfilter($_POST["c_use"]);
} else {
    $c_use = "";
}
if (isset($_POST["c_rank"])) {
    $c_rank = sqlfilter($_POST["c_rank"]);
} else {
    $c_rank = "";
}
if (isset($_POST["c_signdate"])) {
    $c_signdate = sqlfilter($_POST["c_signdate"]);
} else {
    $c_signdate = "";
}
if (isset($_POST["c_title"])) {
    $c_title = sqlfilter($_POST["c_title"]);
} else {
    $c_title = "";
}
if (isset($_POST["c_type"])) {
    $c_type = sqlfilter($_POST["c_type"]);
} else {
    $c_type = "";
}

if (isset($_POST["c_since"])) {
    $c_since = sqlfilter($_POST["c_since"]);
} else {
    $c_since = "";
}
if (isset($_POST["c_quantity"])) {
    $c_quantity = sqlfilter($_POST["c_quantity"]);
} else {
    $c_quantity = "";
}
if (isset($_POST["c_site"])) {
    $c_site = sqlfilter($_POST["c_site"]);
} else {
    $c_site = "";
}
if (isset($_POST["c_wpaper"])) {
    $c_wpaper = sqlfilter($_POST["c_wpaper"]);
} else {
    $c_wpaper = "";
}
if (isset($_POST["c_introduce"])) {
    $c_introduce = sqlfilter($_POST["c_introduce"]);
} else {
    $c_introduce = "";
}
if (isset($_POST["c_suspend_yn"])) {
    $c_suspend_yn = sqlfilter($_POST["c_suspend_yn"]);
} else {
    $c_suspend_yn = "";
}
if (isset($_POST["c_suspend_reason"])) {
    $c_suspend_reason = sqlfilter($_POST["c_suspend_reason"]);
} else {
    $c_suspend_reason = "";
}

if (isset($_POST["c_limit_in"])) {
    $c_limit_in = sqlfilter($_POST["c_limit_in"]);
} else {
    $c_limit_in = "";
}
if (isset($_POST["c_limit_out"])) {
    $c_limit_out = sqlfilter($_POST["c_limit_out"]);
} else {
    $c_limit_out = "";
}


$c_signdate = time();
$savedir = "../../img/coin/";

if (isset($_FILES["c_img"])) {
    $c_img_name = $_FILES['c_img']['name'];
    $c_img = $_FILES['c_img']['tmp_name'];
} else {
    $c_img_name = "";
    $c_img = "";
}
if (isset($_POST["old_c_img"])) {
    $old_c_img = sqlfilter($_POST["old_c_img"]);
} else {
    $old_c_img = "";
}
if (isset($_POST["c_img_del"])) {
    $c_img_del = sqlfilter($_POST["c_img_del"]);
} else {
    $c_img_del = "";
}
if (isset($_POST["c_fees"])) {
    $c_fees = sqlfilter($_POST["c_fees"]);
} else {
    $c_fees = "";
}
if (isset($_REQUEST["keyfield"])) {
    $keyfield = sqlfilter($_REQUEST["keyfield"]);
} else {
    $keyfield = "";
}
if (isset($_REQUEST["key"])) {
    $key = sqlfilter($_REQUEST["key"]);
} else {
    $key = "";
}
if (isset($_REQUEST["page"])) {
    $page = sqlfilter($_REQUEST["page"]);
} else {
    $page = "";
}

#################################################
if ($c_img_del != "1") {
    if (strcmp($c_img, "")) {

        ############# 등록한 파일이 업로드가 허용되지 않는 확장자를 갖는 파일인지를 검사한다. #########
        //$c = expode(a,$v) a문자를 기준으로 분리된 문자열들이 배열 $c에 저장

        $full_filename = explode(".", "$c_img_name");
        $extension = $full_filename[sizeof($full_filename) - 1];
        $extension = strtolower($extension);

        //echo"$extension";
        if (
            strcmp($extension, "gif") && strcmp($extension, "GIF") && strcmp($extension, "jpg") &&
            strcmp($extension, "JPG") && strcmp($extension, "png") && strcmp($extension, "bmp") &&
            strcmp($extension, "txt") && strcmp($extension, "hwp") && strcmp($extension, "doc") && strcmp($extension, "xls") &&
            strcmp($extension, "ppt") && strcmp($extension, "html") && strcmp($extension, "exe") && strcmp($extension, "zip") &&
            strcmp($extension, "rar") && strcmp($extension, "swp") && strcmp($extension, "mov") && strcmp($extension, "asf") &&
            strcmp($extension, "html") && strcmp($extension, "htm") &&
            strcmp($extension, "mp3") && strcmp($extension, "wav") && strcmp($extension, "rm") && strcmp($extension, "wmv") && strcmp($extension, "PDF") && strcmp($extension, "pdf") && strcmp($extension, "ppt") && strcmp($extension, "PPT") && strcmp($extension, "dwg") && strcmp($extension, "DWG") && strcmp($extension, "XLSX") && strcmp($extension, "xlsx") &&
            strcmp($extension, "pptx") &&
            strcmp($extension, "PPTX")
        ) {
            error("NO_ACCESS_UPLOAD");
            exit;
        }

        ##등록하려는 파일과 동일한 이름을 갖는 파일이 이미 존재하면 등록한 파일명을 자동으로 변경한다.
        $files = rand(10000, 100000000);
        $File_name = $files . "." . $extension;
        //echo $File_name;
        $xxx = $savedir . $File_name;
        $countFileName = 0;
        $bExist = 1;
        while (file_exists($xxx)) {
            if (file_exists($xxx)) {

                $countFileName = $countFileName + 1;
                $File_name = $files . "_" . $countFileName . "." . $extension;
                //$File_name = $full_filename[0] . "_" . $countFileName . "." . $extension;
                $xxx = $savedir . $File_name;
            }
        }

        ################### 등록하려는 파일을 현재 자료실의 지정디렉토리에 저장 ##################
        if (!copy($c_img, "$xxx")) {
            error("UPLOAD_COPY_FAILURE");
            exit;
        }

        ################ 작업이 끝난후 임시디렉토리에 저장된 파일을 삭제한다. ##################
        if (!unlink($c_img)) {
            error("UPLOAD_DELETE_FAILURE");
            exit;
        }
        $c_img = $File_name;
    } else {
        $c_img = $old_c_img;
    }
} else {
    if ($old_c_img != "") {
        $img_name = $savedir . $old_c_img;
        $img_name_exist = file_exists("$img_name");
        if ($img_name_exist) {
            if (!unlink("$img_name")) {
                error("UPLOAD_DELETE_FAILURE");
                exit;
            }
        }
    }
    $c_img = "";
}
$m_module = "Coin Setup";
$m_type = "Insert";
$m_signdate = time();

$query_pdo = "INSERT INTO $table_setup";
$query_pdo .= "(";
$query_pdo .= "c_no,c_coin,c_wcommission,c_limit,c_asklimit,c_unit,c_use,c_rank,c_signdate,c_img,c_title,c_type,c_fees";
$query_pdo .= ", c_since, c_quantity, c_site, c_wpaper, c_introduce,c_limit_in, c_limit_out, c_suspend_yn, c_suspend_reason";
$query_pdo .= ")";
$query_pdo .= "VALUES";
$query_pdo .= "(";
$query_pdo .= "'',:c_coin,:c_wcommission,:c_limit,:c_asklimit,:c_unit,:c_use,:c_rank,:c_signdate,:c_img,:c_title,:c_type,:c_fees";
$query_pdo .= ",:c_since,:c_quantity,:c_site,:c_wpaper,:c_introduce, :c_limit_in,:c_limit_out,:c_suspend_yn,:c_suspend_reason";
$query_pdo .= ")";

$stmt = $pdo->prepare($query_pdo);

$stmt->bindValue(":c_coin", $c_coin);
$stmt->bindValue(":c_wcommission", $c_wcommission);
$stmt->bindValue(":c_limit", $c_limit);
$stmt->bindValue(":c_asklimit", $c_asklimit);
$stmt->bindValue(":c_unit", $c_unit);
$stmt->bindValue(":c_use", $c_use);
$stmt->bindValue(":c_rank", $c_rank);
$stmt->bindValue(":c_signdate", $c_signdate);
$stmt->bindValue(":c_img", $c_img);
$stmt->bindValue(":c_title", $c_title);
$stmt->bindValue(":c_type", $c_type);
$stmt->bindValue(":c_fees", $c_fees);
$stmt->bindValue(":c_since", $c_since);
$stmt->bindValue(":c_quantity", $c_quantity);
$stmt->bindValue(":c_site", $c_site);
$stmt->bindValue(":c_wpaper", $c_wpaper);
$stmt->bindValue(":c_introduce", $c_introduce);
$stmt->bindValue(":c_limit_in", $c_limit_in);
$stmt->bindValue(":c_limit_out", $c_limit_out);
$stmt->bindValue(":c_suspend_yn", $c_suspend_yn);
$stmt->bindValue(":c_suspend_reason", $c_suspend_reason);

$inserted = $stmt->execute();

$m_modified = "Inserted information for " .  " " . $c_coin;


$query = "SELECT LAST_INSERT_ID()"; //no값 가져오기
$stmt = pdo_excute("last id", $query, NULL);
$row = $stmt->fetch();
$c_div = $row[0];

// insert market info
foreach ($DEFINE_MARKET as $market) {
    insert_market($c_div, $market, $c_unit, $c_wcommission, $c_use, $c_suspend_yn, $c_rank);
}


for ($l = 1; $l <= 3; $l++) {
    if ($l == 1) $val = '0';
    else if ($l == 2) $val = '2';
    else if ($l == 3) $val = '100';
    else $val = '0';

    $query_level_pdo = "INSERT INTO $table_level";
    $query_level_pdo .= "(";
    $query_level_pdo .= "c_no,c_coin,c_level,c_deposit,c_withdraw,c_limit,c_signdate";
    $query_level_pdo .= ")";
    $query_level_pdo .= "VALUES";
    $query_level_pdo .= "(";
    $query_level_pdo .= "'',:c_coin,:c_level,:c_deposit,:c_withdraw,:c_limit,:c_signdate";
    $query_level_pdo .= ")";
    $stmt = $pdo->prepare($query_level_pdo);
    $stmt->bindValue(":c_coin", $c_div);
    $stmt->bindValue(":c_level", $l);
    $stmt->bindValue(":c_deposit", $val);
    $stmt->bindValue(":c_withdraw", $val);
    $stmt->bindValue(":c_limit", $val);
    $stmt->bindValue(":c_signdate", $c_signdate);
    $inserted_level = $stmt->execute();
}

if ($inserted) {
    // 리스트 출력화면으로 이동한다
    if (isset($_REQUEST["key"])) {
        $key = sqlfilter($_REQUEST["key"]);
    } else {
        $key = "";
    }
    $encoded_key = urlencode($key);
    //echo("<meta http-equiv='Refresh' content='0; URL=member.php?keyfield=$keyfield&key=$encoded_key&page=$page'>");
    echo ("<meta http-equiv='Refresh' content='0; URL=member.php'>");
} else {
    error("QUERY_ERROR");
    exit;
}

function insert_market($c_div, $market, $c_unit, $c_wcommission, $c_use, $c_suspend_yn, $c_rank)
{
    $query = "INSERT INTO m_setup ( m_div,m_pay,m_unit,m_bonus,m_wcommission,m_use,m_suspend_yn,m_rank ) VALUES";
    $query .= " ( ?,?,?,?,?,?,?,? )";
    $pdo_in = [$c_div, $market, $c_unit, "0", $c_wcommission, $c_use, $c_suspend_yn, $c_rank];
    pdo_excute("insert m_setup", $query, $pdo_in);
}

$query_pdo3 = "INSERT INTO $admlogs";
$query_pdo3 .= "(";
$query_pdo3 .= "m_id, m_adminid, m_module, m_type, m_modified, m_signdate";
$query_pdo3 .= ")";
$query_pdo3 .= "VALUES";
$query_pdo3 .= "(";
$query_pdo3 .= "'',:m_adminid, :m_module, :m_type, :m_modified, :m_signdate";
$query_pdo3 .= ")";

$stmt = $pdo->prepare($query_pdo3);
$stmt->bindValue(":m_adminid", $admin_id);
$stmt->bindValue(":m_module", $m_module);
$stmt->bindValue(":m_type", $m_type);
$stmt->bindValue(":m_modified", $m_modified);
$stmt->bindValue(":m_signdate", $m_signdate);
$inserted2 = $stmt->execute();
?>