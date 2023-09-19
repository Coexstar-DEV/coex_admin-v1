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

$query_pdo = "SELECT m_userno,m_id,m_passwd,m_name,m_level,m_confirm ,m_block,m_key,m_email,m_handphone,m_webinfo,m_contury,m_conturyname,m_ip,m_updatedate,m_measge,m_admmemo,m_device,m_otpcheck,m_banknum,m_bankname,m_birtday,m_address,m_smskey, FROM_UNIXTIME(m_signdate+8*3600) as m_sign,ifnull(m_delete,0) FROM $member WHERE IFNULL (m_delete,0) <> 1 ORDER BY m_userno DESC";


$stmt = $pdo->prepare($query_pdo);
$stmt->execute();



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

            $i = 1;
            while($row = $stmt->fetch(PDO::FETCH_ASSOC))
            {


                if ($row['m_confirm'] == "0") {
                    $row['m_confirm'] = M_AUTH_NO;
                } else {
                    $row['m_confirm'] = M_AUTH_YES;
                }
			?>
            <tr  align="center">
              <td width='150' height='30'><?=  $row['m_userno'] ?> </td>
              <td width='120' height='30'><?=  $row['m_id']  ?></td>
              <td width='300' height='30'><?=  $row['m_name'] ?></td> 
              <td width='300' height='30'><?=  $row['m_level'] ?></td> 
              <td width='300' height='30'><?= $row['m_handphone'] ?></td>
              <td width='300' height='30'><?=  $row['m_conturyname'] ?></td>
              <td width='300' height='30'><?= $row['m_confirm'] ?></td>
              <td width='300' height='30'><?= $row['m_sign'] ?></td>
            </tr>
        
		<?
		}
		?>
	</table>
</body>

</html>>