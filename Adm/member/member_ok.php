<meta charset="utf-8">
<?
session_start();

include "../common/dbconn.php";
include "../common/user_function.php";
include_once "../common/". ($_SESSION["language"] == "kr" ? "lang_kr.php" : "lang_en.php");
include "../inc/adm_chk.php";

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
	$m_passwd = sqlfilter($_POST["m_passwd"]);
} else {
	$m_passwd = "";
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
	$m_contury = sqlfilter($_POST["m_contury"]);
} else {
	$m_contury = "";
}
if (isset($_POST["m_conturyname"])) {
	$m_conturyname = sqlfilter($_POST["m_conturyname"]);
} else {
	$m_conturyname = "";
}
if (isset($_POST["m_ip"])) {
	$m_ip = sqlfilter($_POST["m_ip"]);
} else {
	$m_ip = "";
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

if(isset($_POST["m_gender"])) {
	$m_gender = sqlfilter($_POST["m_gender"]);
} else {
	$m_gender = "";
}

if(isset($_POST["m_citizenship"])) {
	$m_citizenship = sqlfilter($_POST["m_citizenship"]);
} else {
	$m_citizenship = "";
}

if(isset($_POST["m_empstatus"])) {
	$m_empstatus = sqlfilter($_POST["m_empstatus"]);
}
else {
	$m_empstatus = "";
}

if(isset($_POST["m_empsalary"])) {
	$m_empsalary = sqlfilter($_POST["m_empsalary"]);
}
else {
	$m_empsalary = "";
}

if(isset($_POST["m_employername"])) {
	$m_employername = sqlfilter($_POST["m_employername"]);
}
else {
	$m_employername = "";
}

if(isset($_POST["m_position"])) {
	$m_position = sqlfilter($_POST["m_position"]);
}
else {
	$m_position = "";
}

if(isset($_POST["m_fundsource"])) {
	$m_fundsource = sqlfilter($_POST["m_fundsource"]);
}
else {
	$m_fundsource = "";
}

if(isset($_POST["m_validid"])) {
	$m_validid = sqlfilter($_POST["m_validid"]);
}
else {
	$m_validid = "";
}

if(isset($_POST["m_idnumber"])) {
	$m_idnumber = sqlfilter($_POST["m_idnumber"]);
}
else {
	$m_idnumber = "";
}


$m_regcheck = 1;

if (isset($_POST["m_smskey"])) {
	$m_smskey = sqlfilter($_POST["m_smskey"]);
} else {
	$m_smskey = "";
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

$m_signdate = time();
$m_key = "123";
$m_module = "Member Management";
$m_type = "Insert";

$m_signdate = time(); 

if (isset($_SETVER["HTTP_USER_AGENT"])) {
	$m_webinfo = $_SERVER['HTTP_USER_AGENT'];
} else {
	$m_webinfo = "";
}
if (isset($_SETVER["HTTP_X_FORWARDED_FOR"])) {
	$m_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
	$m_ip = "";
}

$m_updatedate = time();
$m_device = "android";
$m_address = $zip . "//" . $address;

$m_mostaddress = $arr["bitaddress"];

$m_passwd = trim($m_passwd);
$m_passwd2 = trim($m_passwd2);
$m_signdate = time();

$attFile = $_FILES['m_validimg']['name'];
$tmp_dir = $_FILES['m_validimg']['tmp_name'];
$imgSize = $_FILES['m_validimg']['size'];

if($attFile) {
    $upload_dir = '../../uploads/';
    $imgExt = strtolower(pathinfo($attFile,PATHINFO_EXTENSION));
    $valid_extensions = array(".jpg", ".JPG", ".jpeg", ".JPEG", ".gif", ".GIF", ".png", ".PNG");
    $upic = "registration_in_admin_".$m_signdate.strrchr($attFile, ".");

    if(in_array($imgExt, $valid_extensions)){
        if($imgSize < 5242880)
        {
            move_uploaded_file($tmp_dir,$upload_dir.$upic);
        }
    }
}
else {
    $upic = NULL;
}

if ($passwd != "") {
	if (!preg_match("([a-z0-9]{3,}$)", $m_passwd)) {
		echo "<script language=javascript> alert(\"".M_INPUT_PWD."\"); </script>";
		echo "<script language=javascript> history.go(-1); </script>";
		exit;
	}
	if ($m_passwd != $m_passwd2) {
		echo "<script language=javascript> alert(\"".M_PWD_CONFIRM."\"); </script>";
		echo "<script language=javascript> history.go(-1); </script>";
		exit;
	}
	$query_pdo = "SELECT '$m_passwd'";
	$stmt = $pdo->prepare($query_pdo);
	$stmt->execute();
	$row = $stmt->fetch();

	$newpasswd = $row[0];
} else {
	$newpasswd = $real_pass;
}

$hashpass = $m_passwd;
$salt = 'coincozkey!';
$m_passwd = hash('sha256', $hashpass);
//데이터베이스에 입력값을 삽입한다
$query_pdo1 = "INSERT INTO $member";
$query_pdo1 .= "(";
$query_pdo1 .= "m_userno,m_id,m_passwd,m_name,m_level,m_confirm,m_signdate,m_block";
$query_pdo1 .= ",m_key,m_email,m_handphone,m_webinfo,m_contury,m_conturyname,m_ip,m_updatedate,m_measge,m_admmemo,m_device,m_otpcheck,m_banknum,m_bankname,m_birtday,m_address, m_gender, m_citizenship, m_empstatus, m_empsalary, m_employername, m_position, m_fundsource, m_validid, m_idnumber, m_validimg, m_regcheck";
$query_pdo1 .= ")";
$query_pdo1 .= "VALUES";
$query_pdo1 .= "(";
$query_pdo1 .= "'',:m_id,:m_passwd,:m_name,:m_level,:m_confirm,:m_signdate,:m_block";
$query_pdo1 .= ",:m_key,:m_email,:m_handphone,:m_webinfo,:m_contury,:m_conturyname,:m_ip,:m_updatedate,:m_measge,:m_admmemo,:m_device,:m_otpcheck,:m_banknum,:m_bankname,:m_birtday,:m_address, :m_gender, :m_citizenship, :m_empstatus, :m_empsalary, :m_employername, :m_position, :m_fundsource, :m_validid, :m_idnumber, :m_validimg, :m_regcheck";
$query_pdo1 .= ")";

$stmt = $pdo->prepare($query_pdo1);
$stmt->bindValue(":m_id", $m_id);
$stmt->bindValue(":m_passwd", $m_passwd);
$stmt->bindValue(":m_name", $m_name);
$stmt->bindValue(":m_level", $m_level);
$stmt->bindValue(":m_confirm", $m_confirm);
$stmt->bindValue(":m_signdate", $m_signdate);
$stmt->bindValue(":m_block", $m_block);
$stmt->bindValue(":m_key", $m_key);
$stmt->bindValue(":m_email", $m_email);
$stmt->bindValue(":m_handphone", $m_handphone);
$stmt->bindValue(":m_webinfo", $m_webinfo);
$stmt->bindValue(":m_contury", $m_contury);
$stmt->bindValue(":m_conturyname", $m_conturyname);
$stmt->bindValue(":m_ip", $m_ip);
$stmt->bindValue(":m_updatedate", $m_updatedate);
$stmt->bindValue(":m_measge", $m_measge);
$stmt->bindValue(":m_admmemo", $m_admmemo);
$stmt->bindValue(":m_device", $m_device);
$stmt->bindValue(":m_otpcheck", $m_otpcheck);
$stmt->bindValue(":m_banknum", $m_banknum);
$stmt->bindValue(":m_bankname", $m_bankname);
$stmt->bindValue(":m_birtday", $m_birtday);
$stmt->bindValue(":m_address", $m_address);
$stmt->bindValue(":m_gender", $m_gender);
$stmt->bindValue(":m_citizenship", $m_citizenship);
$stmt->bindValue(":m_empstatus", $m_empstatus);
$stmt->bindValue(":m_empsalary", $m_empsalary);
$stmt->bindValue(":m_employername", $m_employername);
$stmt->bindValue(":m_position", $m_position);
$stmt->bindValue(":m_fundsource", $m_fundsource);
$stmt->bindValue(":m_validid", $m_validid);
$stmt->bindValue(":m_idnumber", $m_idnumber);
$stmt->bindValue(":m_validimg", $upic);
$stmt->bindValue(":m_regcheck", $m_regcheck);
$inserted = $stmt->execute();
$m_modified =  "Inserted new information for user". " " . $m_id;
if ($inserted) {
	$query = "SELECT m_userno FROM $member WHERE m_id='$m_id' ";

	$query_pdo2 = "SELECT m_userno FROM $member WHERE m_id=? ";
	$stmt = $pdo->prepare($query_pdo2);
	$stmt->execute(array($m_id));
	$row = $stmt->fetch();

	$m_userno = $row[0];
	$m_cointotal = "0.00000000";
	$m_coinuse = "0.00000000";
	$m_div = "0";


	$query_pdo3 = "INSERT INTO $m_bankmoney";
	$query_pdo3 .= "(";
	$query_pdo3 .= "m_no,m_div,m_userno,m_id,m_cointotal,m_coinuse,m_restcoin,m_signdate";
	$query_pdo3 .= ")";
	$query_pdo3 .= "VALUES";
	$query_pdo3 .= "(";
	$query_pdo3 .= "'',:m_div,:m_userno,:m_id,:m_cointotal,:m_coinuse,:m_restcoin,:m_signdate";
	$query_pdo3 .= ")";

	$stmt = $pdo->prepare($query_pdo3);
	$stmt->bindValue(":m_div", $m_div);
	$stmt->bindValue(":m_userno", $m_userno);
	$stmt->bindValue(":m_id", $m_id);
	$stmt->bindValue(":m_cointotal", $m_cointotal);
	$stmt->bindValue(":m_coinuse", $m_coinuse);
	$stmt->bindValue(":m_restcoin", $m_restcoin);
	$stmt->bindValue(":m_signdate", $m_signdate);
	$inserted2 = $stmt->execute();

	if ($inserted2) {

		$query_pdo4 = "INSERT INTO $admlogs";
        $query_pdo4 .= "(";
        $query_pdo4 .= "m_id, m_adminid, m_module, m_type, m_modified, m_signdate";
        $query_pdo4 .= ")";
        $query_pdo4 .= "VALUES";
        $query_pdo4 .= "(";
        $query_pdo4 .= "'',:m_adminid, :m_module, :m_type, :m_modified, :m_signdate";
        $query_pdo4 .= ")";
        
        $stmt = $pdo->prepare($query_pdo4);
        $stmt->bindValue(":m_adminid", $admin_id);
        $stmt->bindValue(":m_module", $m_module);
        $stmt->bindValue(":m_type", $m_type);
        $stmt->bindValue(":m_modified", $m_modified);
        $stmt->bindValue(":m_signdate", $m_signdate);
        $inserted3 = $stmt->execute();


		if($inserted3){
			$encoded_key = urlencode($key);
			echo ("<meta http-equiv='Refresh' content='0; URL=member.php?keyfield=$keyfield&key=$encoded_key&page=$page&m_country_name=$m_country_name'>");
		}else {
			error("QUERY_ERROR");
			exit;
			
		}
	} else {
		error("QUERY_ERROR");
		exit;
	}
} else {
	error("QUERY_ERROR");
	exit;
}

?>