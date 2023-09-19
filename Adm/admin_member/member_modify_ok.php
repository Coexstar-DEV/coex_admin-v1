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
if (isset($_POST["m_no"])) {
	$m_no = sqlfilter($_POST["m_no"]);
} else {
	$m_no = "";
}
if (isset($_POST["m_adminid"])) {
	$m_adminid = sqlfilter($_POST["m_adminid"]);
} else {
	$m_adminid = "";
}
if (isset($_POST["m_adminpass"])) {
	$m_adminpass = sqlfilter($_POST["m_adminpass"]);
} else {
	$m_adminpass = "";
}
if (isset($_POST["m_adminpass2"])) {
	$m_adminpass2 = sqlfilter($_POST["m_adminpass2"]);
} else {
	$m_adminpass2 = "";
}
if (isset($_POST["m_adminname"])) {
	$m_adminname = sqlfilter($_POST["m_adminname"]);
} else {
	$m_adminname = "";
}
if (isset($_POST["m_adminlevel"])) {
	$m_adminlevel = sqlfilter($_POST["m_adminlevel"]);
} else {
	$m_adminlevel = "";
}
if (isset($_POST["m_signdate"])) {
	$m_signdate = sqlfilter($_POST["m_signdate"]);
} else {
	$m_signdate = "";
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

$m_adminpass = trim($m_adminpass);
$m_adminpass2 = trim($m_adminpass2);

if ($m_adminpass != "") {
	$hashpass = $m_adminpass;
	$m_adminpass = hash('sha256', $hashpass);
	$hashpass2 = $m_adminpass2;
	$m_adminpass2 = hash('sha256', $hashpass2);

	if ($m_adminpass != "") {
		$query_pdo = "SELECT '$m_passwd'";
		$stmt = $pdo->prepare($query_pdo);
		$stmt->execute();
		$row = $stmt->fetch();
		$newpasswd = $row[0];
	} else {
		$newpasswd = $real_pass;
	}
} else {
	$m_adminpass = $pass11;
}

	$m_module = "Admin Management";
	$m_type = "Update";
	$m_modified =  "Updated the admin:" . " " . $m_adminid;
	$m_signdate = time();

	$query_pdo2 = "UPDATE $admin_member SET";
	$query_pdo2 .= " m_adminpass=:m_adminpass,m_adminname=:m_adminname,m_adminlevel=:m_adminlevel";
	$query_pdo2 .= " WHERE m_adminid = :m_adminid";
	$stmt = $pdo->prepare($query_pdo2);
	$stmt->bindValue(":m_adminpass", $m_adminpass);
	$stmt->bindValue(":m_adminname", $m_adminname);
	$stmt->bindValue(":m_adminlevel", $m_adminlevel);
	$stmt->bindValue(":m_adminid", $m_adminid);
	$updated = $stmt->execute();




	if($updated){
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
	
	$stmt1 = $pdo->prepare($query_pdo3);
	$stmt1->bindValue(":m_adminid", $admin_id);
	$stmt1->bindValue(":m_module", $m_module);
	$stmt1->bindValue(":m_type", $m_type);
	$stmt1->bindValue(":m_modified", $m_modified);
	$stmt1->bindValue(":m_signdate", $m_signdate);
	$inserted = $stmt1->execute();
?>