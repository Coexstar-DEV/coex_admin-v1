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

$query_pdo = "SELECT id, m_id, m_name, m_handphone, m_birthday, m_country, m_countryname, m_address, m_signdate, m_remarks, m_adminno FROM $watchlist ";

if ($key != "") {
	$query_pdo .= " where $keyfield LIKE '%$key%' ";

	if($m_countryname != "") {
		$query_pdo .= " and m_countryname = '".$m_countryname."' ";
	}
}
else if($m_countryname != "") {
	$query_pdo .= " where m_countryname = '".$m_countryname."' ";
}
$query_pdo .= "ORDER BY id DESC";

$stmt = $pdo->prepare($query_pdo);
$stmt->execute();
$result = $stmt->fetch();

if (!$result) {
	error("QUERY_ERROR");
	exit;
}

$total_record_pdo = $stmt->rowCount();

?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title></title>
</head>

<body>
	<table width="100%" border='1' cellspacing='0' cellpadding='0'>
		<tr align="center">
			<td width="50" height="30"><?=M_NO?></td>
			<td width="300" height="30"><?=M_ID?>(레벨)</td>
			<td width="150" height="30"><?=M_NAME?></td>
            <td width="150" height="30">Birthday</td>
			<td width="150" height="30"><?=M_PHONE?></td>
			<td width="100" height="30"><?=M_COUNTRY_NAME?></td>
            <td width="100" height="30">Address</td>
			<td width="100" height="30"><?=M_SIGN?></td>
		</tr>


		<?

		for ($i = 0; $i < $total_record_pdo; $i++) {

			$stmt = $pdo->prepare($query_pdo);
			$stmt->execute();
			$row = $stmt->fetchAll();

			$id = $row[$i][0];
			$m_id = $row[$i][1];
			$m_name = $row[$i][2];
			$m_handphone = $row[$i][3];
			$m_birthday = $row[$i][4];
			$m_countryname = $row[$i][6];
			$m_address = $row[$i][7];
			$m_signdate = $row[$i][8];
			$m_remarks = $row[$i][10];

			$m_signdate = date("Y-m-d", $m_signdate);

			if (($i + 1) % 2 == 0) {
				$kk_bgcolor = "#FFFFFF";
			} else {
				$kk_bgcolor = "#F6F6F6";
			}


			?>
		<tr align="center">
			<td width="50" height="30"><?= $id ?></td>
			<td width="300" height="30"><?= $m_id ?> </td>
			<td width="150" height="30"><B><?= $m_name ?></B></td>
            <td width="100" height="30"><?= $m_birthday ?> </td>           
			<td width="150" height="30"><?= $m_handphone ?> </td>
			<td width="100" height="30"><?= $m_countryname ?></td>
            <td width="100" height="30"><?= $m_address ?></td>
			<td width="100" height="30"><?= $m_signdate ?></td>
            <td width="100" height="30"><?= $m_remarks ?></td>
		</tr>
		<?
		}
		?>
	</table>
</body>

</html>