<meta charset="utf-8">
<?

session_start();
include "../common/dbconn.php";
include "../common/". ($_SESSION["language"] == "kr" ? "lang_kr.php" : "lang_en.php");
include "../common/user_function.php";
include "../inc/adm_chk.php";
if (isset($_SESSION["admin_id"])) {
	$admin_id = $_SESSION["admin_id"];
} else {
	$admin_id = "";
}

if (isset($_POST["m_no"])) {
	$m_no = $_POST["m_no"];
} else {
	$m_no = "";
}
if (isset($_POST["m_adminid"])) {
	$m_adminid = $_POST["m_adminid"];
} else {
	$m_adminid = "";
}
if (isset($_POST["m_adminpass"])) {
	$m_adminpass = $_POST["m_adminpass"];
} else {
	$m_adminpass = "";
}
if (isset($_POST["m_adminpass2"])) {
	$m_adminpass2 = $_POST["m_adminpass2"];
} else {
	$m_adminpass2 = "";
}
if (isset($_POST["m_adminname"])) {
	$m_adminname = $_POST["m_adminname"];
} else {
	$m_adminname = "";
}
if (isset($_POST["m_adminlevel"])) {
	$m_adminlevel = $_POST["m_adminlevel"];
} else {
	$m_adminlevel = "";
}
if (isset($_POST["m_signdate"])) {
	$m_signdate = $_POST["m_signdate"];
} else {
	$m_signdate = "";
}

$m_signdate = time();

$m_adminpass = trim($m_adminpass);
$m_adminpass2 = trim($m_adminpass2);

$m_module = "Admin Management";
$m_type = "Insert";


if ($m_adminpass != "") {
	if (!preg_match("([a-z0-9]{3,}$)", $m_adminpass)) {
		echo "<script language=javascript> alert('".M_INPUT_PWD."'); </script>";
		echo "<script language=javascript> history.go(-1); </script>";
		exit;
	}
	if ($m_adminpass != $m_adminpass2) {
		echo "<script language=javascript> alert('".M_PWD_CONFIRM3."'); </script>";
		echo "<script language=javascript> history.go(-1); </script>";
		exit;
	}

} else {
	echo "<script language=javascript> alert('".M_PWD_CONFIRM2."'); </script>";
	echo "<script language=javascript> history.go(-1); </script>";
	exit;
}

$hashpass = $m_adminpass;
$salt = 'coincozkey!';
$m_adminpass = hash('sha256', $hashpass);

//데이터베이스에 입력값을 삽입한다
$query_pdo = "INSERT INTO $admin_member";
$query_pdo .= "(";
$query_pdo .= "m_no,m_adminid,m_adminpass,m_adminname,m_adminlevel,m_signdate";
$query_pdo .= ")";
$query_pdo .= "VALUES";
$query_pdo .= "(";
$query_pdo .= "'',:m_adminid,:m_adminpass,:m_adminname,:m_adminlevel,:m_signdate";
$query_pdo .= ")";

$stmt = $pdo->prepare($query_pdo);
$stmt->bindValue(":m_adminid", $m_adminid);
$stmt->bindValue(":m_adminpass", $m_adminpass);
$stmt->bindValue(":m_adminname", $m_adminname);
$stmt->bindValue(":m_adminlevel", $m_adminlevel);
$stmt->bindValue(":m_signdate", $m_signdate);
$inserted = $stmt->execute();

	$m_modified = "Inserted admin information" . " " . "AdminID:" . $m_adminid . "//" ."AdminName:" . $m_adminname . "//" . "AdminLevel:" .  $m_adminlevel ;



		if (isset($_REQUEST["key"])) {
			$key = $_REQUEST["key"];
		} else {
			$key = "";
		}

		if($inserted){

				$encoded_key = urlencode($key);
				echo ("<meta http-equiv='Refresh' content='0; URL=member.php?keyfield=$keyfield&key=$encoded_key&page=$page'>");

		}else {
			error("QUERY_ERROR");
			exit;
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