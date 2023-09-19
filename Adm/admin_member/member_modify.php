<?
session_start();

include "../common/user_function.php";
include "../common/dbconn.php";
include "../common/". ($_SESSION["language"] == "kr" ? "lang_kr.php" : "lang_en.php");
include "../inc/top_menu.php";
include "../inc/left_menu_member.php";

if (isset($_GET["m_adminid"])) {
	$m_adminid = $_GET["m_adminid"];
} else {
	$m_adminid = "";
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

$query_pdo = "SELECT m_adminpass,m_adminname,m_adminlevel,m_no,m_adminid FROM $admin_member WHERE m_adminid=? ";
$stmt = pdo_excute("select", $query_pdo, [$m_adminid]);
$row = $stmt->fetch();

$m_adminpass = $row[0];
$m_adminname = $row[1];
$m_adminlevel = $row[2];
$m_no = $row[3];
$m_adminid = $row[4];

?>

<script language="javascript">
	function go_modify() {
		if (document.form.m_adminpass.value != "") {
			if (document.form.m_adminpass.value.length < 4) {
				alert('<?=M_INPUT_PWD?>');
				document.form.m_adminpass.focus();
				return;
			}
			if (!document.form.m_adminpass2.value) {
				alert('<?=M_PWD_CONFIRM4?>');
				document.form.m_adminpass2.focus();
				return;
			}
			if (document.form.m_adminpass.value != document.form.m_adminpass2.value) {
				alert('<?=M_PWD_CONFIRM5?>');
				document.form.m_adminpass2.focus();
				return;
			}
		}
		document.form.action = "member_modify_ok.php";
		document.form.submit();
	}

	function go_list() {
		document.form.action = "member.php";
		document.form.submit();
	}

	function go_del() {
		if (confirm('<?=M_CONFIRM_MSG1?>')) {
			document.form.action = "member_del.php";
			document.form.submit();
		}
	}
</script>

<table width="700" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td height=100></td>
	</tr>
	<tr>
		<td>
			<table border=0 cellpadding=0 cellspacing=0>

				<tr>
						<td colspan=2> <h1><b><?=M_ADMIN. ' ' .M_MANAGER?></b></h1></td>
					</tr>

			</table>
		</td>
	</tr>

	<tr>
		<td>
			<table width="600" border='0' cellspacing='0' cellpadding='0'>
				<form name="form" method="post">
				<tr>
						<td colspan=4 height=2 bgcolor='#ffd600'></td>
					</tr>
					<tr>
						<td colspan=4 height=5></td>
					</tr>
					<tr>
						<td width=115 height="30">
							<div>
								<font size="2" face="돋움"><b>ID</b></font>
							</div>
						</td>
						<td width=479 height="30" colspan="3" align="left">
							<font face="돋움" size="2">&nbsp;
								<?= $m_adminid ?></font>
						</td>
					</tr>
					<tr>
						<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
					</tr>
					<tr>
						<td width=115 height="30">
							<div >
								<font face="돋움" size="2"><b><?=M_PWD?></b></font>
							</div>
						</td>
						<td width=479 height="30" colspan="3" align="left">
							<font size="2" face="돋움">
								&nbsp;
								<input type="text" maxlength=30 name="m_adminpass" size=30 class="adminbttn">
								<br><font face="돋움" size="2">
								</font>
								<!-- <b><?=M_PWD_DESC?></b> -->

							</font>
						</td>
					</tr>
					<tr>
						<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
					</tr>
					<tr>
						<td width=115 height="30">
							<div>
								<font size="2" face="돋움"><b><?=M_CONFIRM. ' ' .M_PWD?></b></font>
							</div>
						</td>
						<td width=479 height="30" colspan="3" align="left">
							<font size="2" face="돋움">
								&nbsp;
								<input type="password" maxlength=30 name="m_adminpass2" size=30 class="adminbttn">
								</font><br>
							<!-- <?=M_PWD_CONFIRM2?> -->
							<input type="hidden" name="pass11" value=<?= $m_adminpass ?>>
						</td>
					</tr>
					<tr>
						<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
					</tr>
					<tr>
						<td width=115 height="30">
							<div>
								<font size="2" face="돋움"><b><?=M_MANAGER.M_NAME?></b></font>
							</div>
						</td>
						<td width=479 height="30" colspan="3" align="left">
							<font size="2" face="돋움">
								&nbsp;
								<input name="m_adminname" size="20" value="<?= $m_adminname ?>" class="adminbttn"></font>
						</td>
					</tr>
					<tr>
						<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
					</tr>
					<tr>
						<td width=115 height="30">
							<div>
								<font size="2" face="돋움"><b><?=M_PRIVILEGE?></b></font>
							</div>
						</td>
						<td width=479 height="30" colspan="3">
							<font size="2" face="돋움">
								&nbsp;
								<select name="m_adminlevel">
									<? for ($i = 1; $i <= $DEFINE_USER_LEVEL; $i++) { ?>
									<option value="<?= $i ?>" <? if ($m_adminlevel == $i) { ?>selected <? } ?>><?= $i ?></option>
									<? } ?>
								</select></font>
						</td>
					</tr>
					<tr>
						<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
					</tr>
			</table>
		</td>
	</tr>

	<input type="hidden" name="m_no" value="<? echo ($m_no) ?>">
	<input type="hidden" name="m_adminid" value="<? echo ($m_adminid) ?>">
	<input type="hidden" name="real_pass" value="<? echo ($real_pass) ?>">
	<input type="hidden" name="keyfield" value="<? echo ($keyfield) ?>">
	<input type="hidden" name="key" value="<? echo ($key) ?>">
	<input type="hidden" name="page" value="<? echo ($page) ?>">
	</form>
</table>
<table width="600" border="0" cellspacing="0" cellpadding="4" >
	<tr>
		<td height="30"></td>
	</tr>
	<tr>
		<td height="20" align="left">
			<input type="button" value="정보변경" class="adminbttn" onClick="javascript:go_modify()">
			<input type="button" value="뒤로가기" class="adminbttn" onClick="javascript:go_list()">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="button" value="삭제하기" class="adminbttn" onClick="go_del()">

		</td>
	</tr>
</table>


<? include "../inc/down_menu.php"; ?>