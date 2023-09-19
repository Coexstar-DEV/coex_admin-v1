<meta charset="utf-8">
<?
session_start();

include "../common/dbconn.php";
include "../common/user_function.php";
include "../common/trading.php";
include "../inc/adm_chk.php";
$LOG_LEVEL = 1;
$LOG_TAG = basename(__FILE__);


if (isset($_SESSION["admin_id"])) {
	$admin_id = $_SESSION["admin_id"];
} else {
	$admin_id = "";
}

$adminlevel = $_SESSION["level"];
check_manager_level($adminlevel, ADMIN_LVL2);

if (isset($_POST["m_no"])) {
	$m_no = sqlfilter($_POST["m_no"]);
} else {
	$m_no = "";
}
if (isset($_POST["m_id"])) {
	$m_id = sqlfilter($_POST["m_id"]);
} else {
	$m_id = "";
}
if (isset($_POST["m_userno"])) {
	$m_userno = sqlfilter($_POST["m_userno"]);
} else {
	$m_userno = "";
}
if (isset($_POST["m_level"])) {
	$m_level = sqlfilter($_POST["m_level"]);
} else {
	$m_level = "";
}
if (isset($_POST["m_orderlevel"])) {
	$m_orderlevel = sqlfilter($_POST["m_orderlevel"]);
} else {
	$m_orderlevel = "";
}
if (isset($_POST["m_check"])) {
	$m_check = sqlfilter($_POST["m_check"]);
} else {
	$m_check = "";
}
if (isset($_POST["m_address"])) {
	$m_address = sqlfilter($_POST["m_address"]);
} else {
	$m_address = "";
}


$keyfield = sqlfilter($_REQUEST["keyfield"]);
$key = sqlfilter($_REQUEST["key"]);
$page = sqlfilter($_REQUEST["page"]);

$query_pdo = "SELECT m_otpcheck,m_referral FROM $member WHERE m_userno=? ";

$stmt = $pdo->prepare($query_pdo);
$stmt->execute(array($m_userno));
$row = $stmt->fetch();
$m_otpcheck = $row[0];
$referral_id = $row[1];
$referral = UserInfo::makeWithId($referral_id, "");

if ($m_check == "1") {
	$m_checkdate = time();
	$m_level = $m_orderlevel;
}

$query_pdo_1 = "UPDATE $table_authorization SET";
$query_pdo_1 .= " m_check=?,m_checkdate=?";
$query_pdo_1 .= " WHERE m_no = ?";

$updated = pdo_excute("update auth1", $query_pdo_1, [$m_check, $m_checkdate, $m_no]);

if ($m_level == "4") {
	$query_pdo_2 = "UPDATE $member SET";
	$query_pdo_2 .= " m_level= ?,m_address=?";
	$query_pdo_2 .= " WHERE m_userno = ?";
	$pdo_in = [$m_level, $m_address, $m_userno];
} else {
	$query_pdo_2 = "UPDATE $member SET";
	$query_pdo_2 .= " m_level= ?";
	$query_pdo_2 .= " WHERE m_userno = ?";
	$pdo_in = [$m_level, $m_userno];

	// 최초 레벨3 인증시 회원 + 추천인 PHP 100개 지급.
	
	
	/*
	if($m_level == "3") {
		$query_chk = "SELECT count(*) cnt FROM $m_bankmoney WHERE m_userno=? and m_category = 'lv3_airdrop' ";
		$stmt_chk = pdo_excute("check_airdrop_cnt", $query_chk, [$m_userno]);
		$row_chk = $stmt_chk->fetch();
		$ad_cnt = $row_chk[0];

		if($ad_cnt == "0") {
			$coin_name = "PHP";
			$coinInfo = new CoinInfo($coin_name);
			register_airdrop($coinInfo, $m_userno, $m_id, "lv3_airdrop",100);
			// 추천인 5개 지급
			//if (!is_empty($referral->id)) {
				//register_airdrop($coinInfo, $referral->no, $referral->id, "referrer_airdrop",5);
			//}
		}
	}
	*/
	
	
}
$updated2 = pdo_excute("update member", $query_pdo_2, $pdo_in);

if (isset($_REQUEST["key"])) {
	$key = $_REQUEST["key"];
} else {
	$key = "";
}

if (!$updated2) {
	fatal_log("failed to update member info1");
	error("Query_ERROR!");
	exit;
}

if ($updated) {
	// 리스트 출력화면으로 이동한다

		$encoded_key = urlencode($key);
		echo ("<meta http-equiv='Refresh' content='0; URL=member.php?keyfield=$keyfield&key=$encoded_key&page=$page'>");
	}else{
		fatal_log("failed to update member info2");
		error("QUERY_ERROR");
		exit;
	}

?>