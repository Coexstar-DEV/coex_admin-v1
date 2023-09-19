<?
session_start();

$idok = $_SESSION["idok"];
if ($idok != "yes") { ?>
<SCRIPT LANGUAGE="JavaScript">

</SCRIPT>
<?

}
$a_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
$a_ip_array = explode(",", $a_ip);

$a_ip = $a_ip_array[0];
$query = "select a_ip from admin_ip where a_ip = '$a_ip'";
$stmt = pdo_excute("admin ip", $query, NULL);

if (!$stmt) {
	echo "QUERY_ERROR1 ";
	exit;
}
$row = $stmt->fetch();
$adm_ip_chk = $row[0];
if ($adm_ip_chk == "") { ?>
<SCRIPT LANGUAGE="JavaScript">

</SCRIPT>
<?
}
?>