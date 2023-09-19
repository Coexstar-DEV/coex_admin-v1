<?
session_start();

if (isset($_POST["file_name"])) {
	$file_name = $_POST["file_name"];
} else {
	$file_name = "";
}
$file_name = date("Y-m-d", $file_name);
$file_name = "Member_" . $file_name;

header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=$file_name.xls");  //엑셀 파일이름 지정
header("Content-Description: PHP4 Generated Data");

#####################################################################
include "../common/user_function.php";
include "../common/dbconn.php";
include_once "../common/". ($_SESSION["language"] == "kr" ? "lang_kr.php" : "lang_en.php");


if(isset($_GET['from']))
{
  if(isset($_GET['to']))
  {
$from = strip_tags($_GET['from']).' '.'00:00:00';
$to = strip_tags($_GET['to']).' '.'23:59:59';
$query_pdo = "SELECT m_userno,m_id,m_passwd,m_name,m_level,m_confirm,FROM_UNIXTIME(m_signdate+8*3600) as m_sign ,m_block,m_key,m_email,m_handphone,m_webinfo,m_contury,m_conturyname,m_ip,m_updatedate,m_measge,m_admmemo,m_device,m_otpcheck,m_banknum,m_bankname,m_birtday,m_address,m_smskey, ifnull(m_delete,0) FROM $member WHERE IFNULL (m_delete,0) <> 1";

if ($key != "") {
	$query_pdo .= " and $keyfield LIKE '%$key%' and m_signdate > unix_timestamp('$from')  AND m_signdate < unix_timestamp('$to')  ";

	if($m_country_name != "") {
		$query_pdo .= " and m_conturyname = '".$m_country_name."'  and m_signdate > unix_timestamp('$from')  AND m_signdate < unix_timestamp('$to')";
	}
}
else if($m_country_name != "") {
	$query_pdo .= " and m_conturyname = '".$m_country_name."' and m_signdate > unix_timestamp('$from')  AND m_signdate < unix_timestamp('$to')";
}
else{
	$query_pdo .= " and m_signdate > unix_timestamp('$from')  AND m_signdate < unix_timestamp('$to')";
}
$query_pdo .= "ORDER BY m_userno DESC";

$stmt = $pdo->prepare($query_pdo);
$stmt->execute();
$result = $stmt->fetch();

if (!$result) {
	error("QUERY_ERROR");
	exit;
}

$total_record_pdo = $stmt->rowCount();
  }}
?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title></title>
</head>

<body>
	<table width="100%" border='1' cellspacing='0' cellpadding='0'>
		<tr align="center">
			<td width="50" height="30"><?=M_NO?></td>
			<td width="300" height="30"><?=M_ID?></td>
			<td width="150" height="30"><?=M_NAME?></td>
			<td width="150" height="30">Level</td>
			<td width="150" height="30"><?=M_PHONE?></td>
			<td width="100" height="30"><?=M_COUNTRY_NAME?></td>
			<td width="100" height="30"><?=M_AUTH?></td>
			<td width="100" height="30"><?=M_SIGN?></td>
		</tr>


		<?
		$a=1;
		for ($i = 0; $i < $total_record_pdo; $i++) {
			
			$stmt = $pdo->prepare($query_pdo);
			$stmt->execute();
			$row = $stmt->fetchAll();
			
			$m_userno = $row[$i][0];
			$m_id = $row[$i][1];
			$m_name = $row[$i][3];
			$m_level = $row[$i][4];
			$m_confirm = $row[$i][5];
			$m_sign = $row[$i][6];
			$m_block = $row[$i][7];
			$m_measge = $row[$i][16];
			$m_handphone = $row[$i][10];
			$m_country_name = $row[$i][13];
			$m_delete = $row[$i][13];

			if ($m_confirm == "0") {
				$m_confirm = M_AUTH_NO;
			} else {
				$m_confirm = M_AUTH_YES;
			}
			if ($m_block == "0") {
				$m_block = "미적용";
			} else if ($m_block == "1") {
				$m_block = "적용";
			}

			if (($i + 1) % 2 == 0) {
				$kk_bgcolor = "#FFFFFF";
			} else {
				$kk_bgcolor = "#F6F6F6";
			}


			?>
		<tr align="center">
			<td width="50" height="30"><?= $a++ ?></td>
			<td width="300" height="30"><?= $m_id ?></td>
			<td width="250" height="30"><B><?= $m_name ?></B></td>
			<td width="50" height="30"><?= $m_level ?></td>
			<td width="150" height="30"><?= $m_handphone ?> </td>
			<td width="100" height="30"><?= $m_country_name ?> </td>
			<td width="100" height="30"><?= $m_confirm ?></td>
			<td width="100" height="30"><?= $m_sign ?></td>
		</tr>
		<?
		}
		?>
	</table>
</body>

</html>