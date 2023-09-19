<meta charset="utf-8">
<?
session_start();

include "../common/dbconn.php";
include "../common/user_function.php";
include "../inc/adm_chk.php";
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
if (isset($_POST["m_userno"])) {
    $m_userno = sqlfilter($_POST["m_userno"]);
} else {
    $m_userno = "";
}
if (isset($_POST["m_id"])) {
    $m_id = sqlfilter($_POST["m_id"]);
} else {
    $m_id = "";
}
if (isset($_POST["m_passwd"])) {
    $m_passwd = ($_POST["m_passwd"]);
} else {
    $m_passwd = "";
}
if (isset($_POST["m_passwd2"])) {
    $m_passwd2 = ($_POST["m_passwd2"]);
} else {
    $m_passwd2 = "";
}
if (isset($_POST["m_riskprof"])) {
    $m_riskprof = ($_POST["m_riskprof"]);
} else {
    $m_riskprof = "";
}

if (isset($_POST["m_name"])) {
    $m_name = sqlfilter($_POST["m_name"]);
} else {
    $m_name = "";
}
if (isset($_POST["m_level"])) {
    $m_level = sqlfilter($_POST["m_level"]);
} else {
    $m_level = "";
}
if (isset($_POST["m_confirm"])) {
    $m_confirm = sqlfilter($_POST["m_confirm"]);
} else {
    $m_confirm = "";
}
if (isset($_POST["m_signdate"])) {
    $m_signdate = sqlfilter($_POST["m_signdate"]);
} else {
    $m_signdate = "";
}
if (isset($_POST["m_block"])) {
    $m_block = sqlfilter($_POST["m_block"]);
} else {
    $m_block = "";
}
if (isset($_POST["m_key"])) {
    $m_key = sqlfilter($_POST["m_key"]);
} else {
    $m_key = "";
}
if (isset($_POST["m_email"])) {
    $m_email = sqlfilter($_POST["m_email"]);
} else {
    $m_email = "";
}
if (isset($_POST["m_handphone"])) {
    $m_handphone = sqlfilter($_POST["m_handphone"]);
} else {
    $m_handphone = "";
}
if (isset($_POST["m_webinfo"])) {
    $m_webinfo = sqlfilter($_POST["m_webinfo"]);
} else {
    $m_webinfo = "";
}
if (isset($_POST["m_contury"])) {
    $m_contury = explode('@@', sqlfilter($_POST["m_contury"]))[0];
    $m_conturyname = explode('@@', sqlfilter($_POST["m_contury"]))[1];
} else {
    $m_contury = "";
    $m_conturyname = "";
}
if (isset($_POST["m_ip"])) {
    $m_ip = sqlfilter($_POST["m_ip"]);
} else {
    $m_ip = "";
}

////Private Key
if (isset($_POST["m_private_user"])) {
    $m_private_user = sqlfilter($_POST["m_private_user"]);
} else {
    $m_private_user = "";
}

if (isset($_POST["m_private_key"])) {
    $m_private_key = sqlfilter($_POST["m_private_key"]);
} else {
    $m_private_key = "";
}


if (isset($_POST["m_updatedate"])) {
    $m_updatedate = sqlfilter($_POST["m_updatedate"]);
} else {
    $m_updatedate = "";
}
if (isset($_POST["m_admmemo"])) {
    $m_admmemo = sqlfilter($_POST["m_admmemo"]);
} else {
    $m_admmemo = "";
}
if (isset($_POST["m_device"])) {
    $m_device = sqlfilter($_POST["m_device"]);
} else {
    $m_device = "";
}
if (isset($_POST["m_otpcheck"])) {
    $m_otpcheck = sqlfilter($_POST["m_otpcheck"]);
} else {
    $m_otpcheck = "";
}
if (isset($_POST["m_secretkey"])) {
    $m_secretkey = sqlfilter($_POST["m_secretkey"]);
} else {
    $m_secretkey = "";
}
if (isset($_POST["m_banknum"])) {
    $m_banknum = sqlfilter($_POST["m_banknum"]);
} else {
    $m_banknum = "";
}
if (isset($_POST["m_bankname"])) {
    $m_bankname = sqlfilter($_POST["m_bankname"]);
} else {
    $m_bankname = "";
}
if (isset($_POST["m_birtday"])) {
    $m_birtday = sqlfilter($_POST["m_birtday"]);
} else {
    $m_birtday = "";
}
if (isset($_POST["m_address"])) {
    $m_address = sqlfilter($_POST["m_address"]);
} else {
    $m_address = "";
}
if (isset($_POST["m_smskey"])) {
    $m_smskey = sqlfilter($_POST["m_smskey"]);
} else {
    $m_smskey = "";
}
if (isset($_POST["m_smskey"])) {
    $real_pass = sqlfilter($_POST["m_smskey"]);
} else {
    $real_pass = "";
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
if (isset($_REQUEST["m_country_name"])) {
	$m_country_name = sqlfilter($_REQUEST["m_country_name"]);
} else {
	$m_country_name = "";
}
if (isset($_POST["real_pass"])) {
    $real_pass = sqlfilter($_POST["real_pass"]);
} else {
    $real_pass = "";
}
if (isset($_POST["m_encrypt"])) {
    $m_encrypt = sqlfilter($_POST["m_encrypt"]);
} else {
    $m_encrypt = "0";
}
if (isset($_POST["m_feetype"])) {
    $m_feetype = sqlfilter($_POST["m_feetype"]);
} else {
    $m_feetype = "0";
}

if (isset($_SESSION["admip"])) {
    $admip = $_SESSION["admip"];
} else {
    $admip = "";
}

if (isset($_POST["m_admin_no"])) {
    $m_admin_no = sqlfilter($_POST["m_admin_no"]);
} else {
    $m_admin_no = "0";
}

if (isset($_POST["m_gender"])) {
    $m_gender = sqlfilter($_POST["m_gender"]);
} else {
    $m_gender = "";
}



if (isset($_POST["m_citizenship"])) {
    $m_citizenship = sqlfilter($_POST["m_citizenship"]);
} else {
    $m_citizenship = "";
}

if (isset($_POST["m_gender"])) {
    $m_gender = sqlfilter($_POST["m_gender"]);
} else {
    $m_gender = "";
}

if (isset($_POST["m_empstatus"])) {
    $m_empstatus = sqlfilter($_POST["m_empstatus"]);
} else {
    $m_empstatus = "";
}

if (isset($_POST["m_empsalary"])) {
    $m_empsalary = sqlfilter($_POST["m_empsalary"]);
} else {
    $m_empsalary = "";
}

if (isset($_POST["m_employername"])) {
    $m_employername = sqlfilter($_POST["m_employername"]);
} else {
    $m_employername = "";
}

if (isset($_POST["m_position"])) {
    $m_position = sqlfilter($_POST["m_position"]);
} else {
    $m_position = "";
}

if (isset($_POST["m_fundsource"])) {
    $m_fundsource = sqlfilter($_POST["m_fundsource"]);
} else {
    $m_fundsource = "";
}

if (isset($_POST["m_validid"])) {
    $m_validid = sqlfilter($_POST["m_validid"]);
} else {
    $m_validid = "";
}

if (isset($_POST["m_idnumber"])) {
    $m_idnumber = sqlfilter($_POST["m_idnumber"]);
} else {
    $m_idnumber = "";
}

if (isset($_POST["m_verified"])) {
    $m_verified = sqlfilter($_POST["m_verified"]);
} else {
    $m_verified = "";
}

$m_updatedate = time();
$m_measge = $admin_id . "/" . $admip . "/" . date("Y-m-d");


$m_module = "Member Management";
$m_type = "Update";

$m_signdate = time(); 

$attFile = $_FILES['m_validimg']['name'];
$tmp_dir = $_FILES['m_validimg']['tmp_name'];
$imgSize = $_FILES['m_validimg']['size'];
    
if($attFile) {
    $upload_dir = '../../uploads/';
    $imgExt = strtolower(pathinfo($attFile,PATHINFO_EXTENSION));
    $valid_extensions = array(".jpg", ".JPG", ".jpeg", ".JPEG", ".gif", ".GIF", ".png", ".PNG");
    $m_validimg = "modified_in_admin_front_".$m_updatedate.strrchr($attFile, ".");
    
    if(in_array($imgExt, $valid_extensions)){
        if($imgSize < 5242880)
        {
            move_uploaded_file($tmp_dir,$upload_dir.$m_validimg);
        }
    }
}
else {
    $q = "SELECT m_validimg FROM m_member";
    $q .= " WHERE m_userno = '" . $m_userno . "' ";
    $st = $pdo->prepare($q);
    $st->execute();
    $r = $st->fetch();
    $m_validimg = $r[0];
}


$attFile1 = $_FILES['m_validimg1']['name'];
$tmp_dir1 = $_FILES['m_validimg1']['tmp_name'];
$imgSize1 = $_FILES['m_validimg1']['size'];
    
if($attFile1) {
    $upload_dir = '../../uploads/';
    $imgExt = strtolower(pathinfo($attFile,PATHINFO_EXTENSION));
    $valid_extensions = array(".jpg", ".JPG", ".jpeg", ".JPEG", ".gif", ".GIF", ".png", ".PNG");
    $m_validimg1 = "modified_in_admin_back_".$m_updatedate.strrchr($attFile1, ".");
    
    if(in_array($imgExt, $valid_extensions)){
        if($imgSize1 < 5242880)
        {
            move_uploaded_file($tmp_dir1,$upload_dir.$m_validimg1);
        }
    }
}
else {
    $q1 = "SELECT m_validimg1 FROM m_member";
    $q1 .= " WHERE m_userno = '" . $m_userno . "' ";
    $st1 = $pdo->prepare($q1);
    $st1->execute();
    $r1 = $st1->fetch();
    $m_validimg1 = $r1[0];
}




$m_passwd = trim($m_passwd);
$m_passwd2 = trim($m_passwd2);
$m_updatedate = time();

if ($m_passwd != "") {
    $hashpass1 = $m_passwd;
    $hashpass2 = $m_passwd2;

    if ($m_encrypt == "1") {
        $m_passwd = openssl_encrypt($hashpass1, 'AES-256-CBC', KEY_256, 0, KEY_128);
        $m_passwd2 = openssl_encrypt($hashpass2, 'AES-256-CBC', KEY_256, 0, KEY_128);
    } else {
        $m_passwd = hash('sha256', $hashpass1);
        $m_passwd2 = hash('sha256', $hashpass2);
    }

    if ($m_passwd != $m_passwd2) {
        echo "<script language=javascript> alert('".M_PWD_CONFIRM3."'); </script>";
        echo "<script language=javascript> history.go(-1); </script>";
        exit;
    }
    $query = "SELECT '$m_passwd'";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $row = $stmt->fetch();
    $newpasswd = $row[0];
} else {
    $query = "SELECT m_passwd FROM m_member";
    $query .= " WHERE m_userno = '" . $m_userno . "' ";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $row = $stmt->fetch();
    $m_passwd = $row[0];
}

			              

if (!is_empty(trim($m_handphone))) {
    $querych = "SELECT count(*) cnt from " . $member . " where m_userno <> '" . $m_userno . "' and m_handphone='" . $m_handphone . "' ";
    $resultch = $pdo->prepare($querych);
    $resultch->execute();
    $rowch = $resultch->fetch();
    $phonecnt = $rowch[0];
    if ($phonecnt > 0) {
        echo "<script language=javascript> alert('중복된 연락처 입니다.'); </script>";
        echo "<script language=javascript> history.go(-1); </script>";
        exit;
    }
}

$querych = "SELECT count(*) cnt from " . $member . " where m_userno <> '" . $m_userno . "' and  m_email='" . $m_email . "' ";
$resultch = $pdo->prepare($querych);
$resultch->execute();
$rowch = $resultch->fetch();
$mailcnt = $rowch[0];
if ($mailcnt > 0) {
    echo "<script language=javascript> alert('중복된 이메일 입니다.'); </script>";
    echo "<script language=javascript> history.go(-1); </script>";
    exit;
}

$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

try {

    err_log("m_otpcheck:$m_otpcheck, seq:$m_secretkey==================== ");
    if ($m_otpcheck == "0") {
        $m_secretkey = "";
    }
    $query_pdo = "UPDATE $member SET";
    $query_pdo .= " m_passwd=:m_passwd,m_name=:m_name,m_level=:m_level,m_confirm=:m_confirm,m_updatedate=:m_updatedate,m_block=:m_block,m_email=:m_email";
    $query_pdo .= ",m_handphone=:m_handphone,m_private_user=:m_private_user,m_private_key=:m_private_key,m_measge=:m_measge,m_admmemo=:m_admmemo,m_contury=:m_contury,m_conturyname=:m_conturyname,m_otpcheck=:m_otpcheck,m_secretkey=:m_secretkey,m_banknum=:m_banknum,m_bankname=:m_bankname,m_birtday=:m_birtday,m_address=:m_address,m_admin_no=:m_admin_no,m_feetype=:m_feetype,m_gender=:m_gender,m_citizenship=:m_citizenship, m_empstatus=:m_empstatus, m_empsalary=:m_empsalary, m_employername=:m_employername, m_position=:m_position, m_fundsource=:m_fundsource, m_validid=:m_validid, m_idnumber=:m_idnumber, m_validimg=:m_validimg, m_verified=:m_verified, m_validimg1=:m_validimg1,m_riskprof=:m_riskprof";
    $query_pdo .= " WHERE m_userno = :m_userno ";
    
    $stmt = $pdo->prepare($query_pdo);
    $stmt->bindValue("m_passwd", $m_passwd);
    $stmt->bindValue("m_name", $m_name);
    $stmt->bindValue("m_level", $m_level);
    $stmt->bindValue("m_confirm", $m_confirm);
    $stmt->bindValue("m_updatedate", $m_updatedate);
    $stmt->bindValue("m_block", $m_block);
    $stmt->bindValue("m_email", $m_email);
    $stmt->bindValue("m_measge", $m_measge);
    $stmt->bindValue("m_handphone", $m_handphone);
    $stmt->bindValue("m_admmemo", $m_admmemo);
    $stmt->bindValue("m_contury", $m_contury);
    $stmt->bindValue("m_conturyname", $m_conturyname);
    $stmt->bindValue("m_otpcheck", $m_otpcheck);
    $stmt->bindValue("m_secretkey", $m_secretkey);
    $stmt->bindValue("m_banknum", $m_banknum);
    $stmt->bindValue("m_bankname", $m_bankname);
    $stmt->bindValue("m_birtday", $m_birtday);
    $stmt->bindValue("m_address", $m_address);
    $stmt->bindValue("m_admin_no", $m_admin_no);
    $stmt->bindValue("m_feetype", $m_feetype);
    $stmt->bindValue("m_userno", $m_userno);
    $stmt->bindValue("m_private_user", $m_private_user);
    $stmt->bindValue("m_private_key", $m_private_key);
    $stmt->bindValue("m_gender", $m_gender);
    $stmt->bindValue("m_citizenship", $m_citizenship);
    $stmt->bindValue("m_empstatus", $m_empstatus);
    $stmt->bindValue("m_empsalary", $m_empsalary);
    $stmt->bindValue("m_employername", $m_employername);
    $stmt->bindValue("m_position", $m_position);
    $stmt->bindValue("m_fundsource", $m_fundsource);
    $stmt->bindValue("m_validid", $m_validid);
    $stmt->bindValue("m_idnumber", $m_idnumber);
    $stmt->bindValue("m_validimg", $m_validimg);
    $stmt->bindValue("m_verified", $m_verified);
    $stmt->bindValue("m_validimg1", $m_validimg1);
    $stmt->bindValue("m_riskprof", $m_riskprof);

    $updated = $stmt->execute();
    $m_modified =  "Updated information of user". " " . $m_email;
    
    err_log("--- done ----no:$m_userno, id:$m_id");
            

            $key = (isset($_REQUEST["key"]) ? $_REQUEST["key"] : "");
            $encoded_key = urlencode($key);
            echo ("<meta http-equiv='Refresh' content='0; URL=member.php?keyfield=$keyfield&key=$encoded_key&page=$page&m_country_name=$m_country_name'>");
            

    
}
 catch (\Exception $e) {

    err_log("err_msg:" . $e->getMessage());
    error("QUERY_ERROR");
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
    $inserted = $stmt->execute();
?>