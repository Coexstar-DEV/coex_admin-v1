<?
session_start();

include "../common/user_function.php";
include "../common/dbconn.php";
include_once "../common/". ($_SESSION["language"] == "kr" ? "lang_kr.php" : "lang_en.php");
include "../inc/top_menu.php";
include "../inc/left_menu_member.php";

if (isset($_REQUEST["m_no"])) {
	$m_no = sqlfilter($_REQUEST["m_no"]);
} else {
	$m_no = "";
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
if (isset($_REQUEST["m_check_div"])) {
	$m_check_div = sqlfilter($_REQUEST["m_check_div"]);
} else {
	$m_check_div = "";
}
if (isset($_REQUEST["page"])) {
	$page = sqlfilter($_REQUEST["page"]);
} else {
	$page = "";
}

$query_pdo = "SELECT A.m_no,A.m_id,A.m_userno,A.m_level,A.m_orderlevel,A.m_check,A.m_howcheck,A.m_file1,A.m_file2,A.m_address,B.m_level,B.m_name,B.m_birtday,A.m_banknum,A.m_bankname FROM $table_authorization A INNER JOIN $member B on A.m_userno = B.m_userno WHERE A.m_no=? ";
$stmt = $pdo->prepare($query_pdo);
$stmt->execute(array($m_no));
$row = $stmt->fetch();

if (!$row) {
	error("QUERY_ERROR");
	exit;
}

$m_no 			= $row[0];
$m_id 			= $row[1];
$m_userno 		= $row[2];
$m_level 		= $row[3]; 		// 신청시 레벨
$m_orderlevel 	= $row[4];	// 요청한 레벨
$m_check 		= $row[5];			// 인증여부
$m_howcheck 	= $row[6]; 		// 요청메시지
$m_file1 		= $row[7]; 		// 요청 이미지1
$m_file2 		= $row[8]; 		// 요청 이미지2 
$m_address 		= $row[9]; 		//주소
$m_currentlevel = $row[10]; 		// 현재레벨
$m_name 		= $row[11]; 	// 이름
$m_birthday 	= $row[12]; 	// 생년월일
$m_banknum 		= $row[13]; 		//계좌번호
$m_bankname 	= $row[14]; 	//은행명

$query_pdo2 = "SELECT m_userno FROM $member WHERE m_id=? ";

$stmt = $pdo->prepare($query_pdo2);
$stmt->execute(array($m_id));
$row2 = $stmt->fetch();

?>

<script language="javascript">
	function go_modify() {

		document.form.action = "member_modify_ok.php";
		document.form.submit();
	}

	function go_list() {
		document.form.action = "member.php";
		document.form.submit();
	}
</script>

<table width="1100" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td height=30></td>
	</tr>
	<tr>
		<td>
			<table border=0 cellpadding=0 cellspacing=0>
				<tr>
					<td width=60 align=center><img src="../image/icon2.gif" width=45 height=35 border=0></td>
					<td class='td14'><b><?=M_MEMBER.M_AUTH_YES.M_HIS?></b></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td height=3></td>
	</tr>
	<tr>
		<td>

			<table width="1000" border='0' cellspacing='0' cellpadding='0'>
				<form name="form" method="post" enctype="multipart/form-data">
					<tr>
						<td colspan=4 height=2 bgcolor='#88B7DA'></td>
					</tr>
					<tr>
						<td colspan=4 height=5></td>
					</tr>
					<tr>
						<td width=115 height="30">
							<div align="center">
								<font size="2" face="돋움">ID</font>
							</div>
						</td>
						<td width=479 height="30" colspan="3" align="left">
							<font face="돋움" size="2">&nbsp;
								<?= $m_id ?></font>
							<input type="hidden" name="m_id" value="<?= $m_id ?>">
						</td>
					</tr>
					<tr>
						<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
					</tr>
					<tr>
						<td width=115 height="30">
							<div align="center">
								<font face="돋움" size="2"><?=M_MEMBER.M_NO?></font>
							</div>
						</td>
						<td width=479 height="30" colspan="3" align="left">
							<font face="돋움" size="2">&nbsp;
								<?= $m_userno ?></font>
							<input type="hidden" name="m_userno" value="<?= $m_userno ?>">
						</td>
					</tr>
					<tr>
						<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
					</tr>
					<tr>
						<td width=115 height="30">
							<div align="center">
								<font face="돋움" size="2"><?=M_MEMBER.M_NAME?></font>
							</div>
						</td>
						<td width=479 height="30" colspan="3" align="left">
							<font face="돋움" size="2" class="notranslate">&nbsp;
								<?= $m_name ?></font>
						</td>
					</tr>
					<tr>
						<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
					</tr>
					<tr>
						<td width=115 height="30">
							<div align="center">
								<font face="돋움" size="2"><?=M_BIRTH?></font>
							</div>
						</td>
						<td width=479 height="30" colspan="3" align="left">
							<font face="돋움" size="2">&nbsp;
								<?= $m_birthday ?></font>
						</td>
					</tr>
					<tr>
						<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
					</tr>
					<tr>
						<td width=115 height="30">
							<div align="center">
								<font size="2" face="돋움"><?=M_CURRENT.M_LEVEL?></font>
							</div>
						</td>
						<td width=479 height="30" colspan="3" align="left">
							<font size="2" face="돋움">
								<font face="돋움" size="2">&nbsp;
									<?= $m_currentlevel ?></font>
						</td>
					</tr>
					<tr>
						<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
					</tr>
					<tr>
						<td width=115 height="30">
							<div align="center">
								<font size="2" face="돋움"><?=M_PROPOSED.M_LEVEL?></font>
							</div>
						</td>
						<td width=479 height="30" colspan="3" align="left">
							<font size="2" face="돋움">
								<font face="돋움" size="2">&nbsp;
									<?= $m_level ?></font>
								<input type="hidden" id="m_level" name="m_level" value="<?= $m_level ?>">
						</td>
					</tr>
					<tr>
						<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
					</tr>
					<tr>
						<td width=115 height="30">
							<div align="center">
								<font size="2" face="돋움"><?=M_REQUEST.M_LEVEL?></font>
							</div>
						</td>
						<td width=479 height="30" colspan="3" align="left">
							<font face="돋움" size="2">&nbsp;
								<?= $m_orderlevel ?></font>
							<input type="hidden" id="m_orderlevel" name="m_orderlevel" value="<?= $m_orderlevel ?>">
						</td>
					</tr>
					<tr>
						<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
					</tr>
					<tr>
						<td width=115 height="30">
							<div align="center">
								<font size="2" face="돋움"><?M_MANAGER.M_CONFIRM?></font>
							</div>
						</td>
						<td width=479 height="30" colspan="3">
							<font size="2" face="돋움">
								&nbsp;
								<input type="radio" name="m_check" value="0" <? if ($m_check == "0") { ?> checked<? } ?>><?=M_NOT_CONFIRM?>
								<input type="radio" name="m_check" value="1" <? if ($m_check == "1") { ?> checked<? } ?>><?=M_CONFIRM?>
						</td>
					</tr>
					<tr>
						<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
					</tr>
					<tr>
						<td width=105 height="30">
							<div align="center">
								<font size="2" face="돋움"><?=M_CONTENT?></font>
							</div>
						</td>
						<td height="30" colspan="3" align="left">
							<font face="돋움" size="2">&nbsp;
								<?= $m_howcheck ?></font>
						</td>
					</tr>
					<tr>
						<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
					</tr>
					<tr>
						<td width=105 height="30">
							<div align="center">
								<font size="2" face="돋움"><?=M_ADDRESS?></font>
							</div>
						</td>
						<td height="30" colspan="3" align="left">
							<font face="돋움" size="2" class="notranslate">&nbsp;
								<?= $m_address ?></font>
							<input type="hidden" id="m_address" name="m_address" value="<?= $m_address ?>">
						</td>
					</tr>
					<tr>
						<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
					</tr>
					<tr>
						<td width=105 height="30"> 
							<div align="center"><font size="2" face="돋움"><?=M_BANK?></font></div>
						</td>
						<td height="30" colspan="3" align="left">
							<font face="돋움" size="2">&nbsp; 
								<?=$m_bankname?></font>
						</td>
					</tr>
					<tr><td colspan=4 height=1 bgcolor='#D2DEE8'></td></tr>
					<tr>
						<td width=105 height="30"> 
							<div align="center"><font size="2" face="돋움"><?=M_ACCOUNT?></font></div>
						</td>
						<td height="30" colspan="3" align="left">
							<font face="돋움" size="2">&nbsp; 
								<?=$m_banknum?></font>
						</td>
					</tr>
					<tr><td colspan=4 height=1 bgcolor='#D2DEE8'></td></tr>
					<tr>
						<td width=105 height="200">
							<div align="center">
								<font size="2" face="돋움"><?=M_PROOF.M_FILE?>1</font>
							</div>
						</td>
						<td height="30" colspan="3" align="left">
							<img width="400" height="400" src="<?= $IMG_URL . $m_file1 ?>">
						</td>
					</tr>
					<tr>
						<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
					</tr>
					<tr>
						<td width=105 height="200">
							<div align="center">
								<font size="2" face="돋움"><?=M_PROOF.M_FILE?>2</font>
							</div>
						</td>
						<td height="30" colspan="3" align="left">
							<img width="400" height="400" src="<?= $IMG_URL . $m_file2 ?>">
						</td>
					</tr>
					<tr>
						<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
					</tr>
			</table>
		</td>
		</tr>
		<input type="hidden" name="m_no" value="<? echo ($m_no) ?>">
		<input type="hidden" name="keyfield" value="<? echo ($keyfield) ?>">
		<input type="hidden" name="key" value="<? echo ($key) ?>">
		<input type="hidden" name="page" value="<? echo ($page) ?>">
		</form>
</table>
<table width="600" border="0" cellspacing="0" cellpadding="4" class="left_margin30">
	<tr>
		<td height="30"></td>
	</tr>
	<tr>
		<td height="20" align="center">
			<? if (check_manager_level2($adminlevel, ADMIN_LVL2)) { ?>
			<input type="button" value="<?=M_MODIFICATION?>" class="adminbttn" onClick="javascript:go_modify()">
			<? } ?>
			<input type="button" value="<?=M_BACK?>" class="adminbttn" onClick="javascript:go_list()">
			<? if (check_manager_level2($adminlevel, ADMIN_LVL2)) { ?>
			<input type="button" value="<?=M_DEL?>" class="adminbttn" onClick="javascript:location.href='member_del.php?m_no=<?= $m_no ?>'">
			<? } ?>
		</td>
	</tr>
</table>
<br><br>

<BR><BR>

<? include "../inc/down_menu.php"; ?>