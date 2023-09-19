<?
session_start();

include "../common/user_function.php";
include "../common/dbconn.php";
include "../common/". ($_SESSION["language"] == "kr" ? "lang_kr.php" : "lang_en.php");
include "../inc/top_menu.php";
include "../inc/left_menu_member.php";

?>

<script language="javascript">
	function go_modify() {
		if (!document.form.m_adminid.value) {
			alert('<?=M_INPUT_ID?>');
			document.form.m_adminid.focus();
			return;
		}

		if (document.form.m_adminpass.value.length < 4) {
			alert('<?=M_INPUT_PWD?>');
			document.form.m_adminpass.focus();
			return;
		}

		if (document.form.m_adminpass.value != document.form.m_adminpass2.value) {
			alert('<?=M_PWD_CONFIRM?>');
			document.form.m_adminpass2.focus();
			return;
		}

		if (!document.form.m_adminname.value) {
			alert('<?=M_INPUT_NAME?>');
			document.form.m_adminname.focus();
			return;
		}
		if (!document.form.m_adminlevel.value) {
			alert('<?=M_INPUT_LEVEL?>');
			document.form.m_adminlevel.focus();
			return;
		}

		document.form.action = "member_ok.php";
		document.form.submit();
	}
</script>

<table width="1100" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td height=80></td>
	</tr>

	<tr>
		<td>

			<table width="800" align=center border='0' cellspacing='0' cellpadding='0'>
				<form name="form" method="post">
				<tr>
						<td colspan=2> <h1><b>Add Manager</b></h1></td>
					</tr>
					<tr>
						<td colspan=4 height=2 bgcolor='#ffd600'></td>
					</tr>
					<tr>
						<td colspan=4 height=5></td>
					</tr>
					<tr>
						<td width=105 height="30">
							<div>
								<font size="2" face="돋움"><b>ID</b></font>
							</div>
						</td>
						<td height="30" colspan="3" align="left">
							<font face="돋움" size="2">&nbsp;
								<input type="text" name="m_adminid" size=30 class="adminbttn"></font> <a href="javascript:idchk('../member/autozip/id_check.php');">[<?=M_ID.M_DUPE.M_SEARCH?>]</a>
						</td>
					</tr>
					<tr>
						<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
					</tr>
					<tr>
						<td width=105 height="30">
							<div>
								<font face="돋움" size="2"><b><?=M_PWD?></b></font>
							</div>
						</td>
						<td height="30" colspan="3" align="left">
							<font size="2" face="돋움">
								&nbsp;
								<input type="password" maxlength=30 name="m_adminpass" size=30 class="adminbttn">
							</font>
							<font face="돋움" size="2"><?=M_PWD_DESC?></font>
						</td>
					</tr>
					<tr>
						<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
					</tr>
					<tr>
						<td width=105 height="30">
							<div>
								<font size="2" face="돋움"><b><?=M_PWD.M_CONFIRM?></b></font>
							</div>
						</td>
						<td height="30" colspan="3" align="left">
							<font size="2" face="돋움">
								&nbsp;
								<input type="password" maxlength=30 name="m_adminpass2" size=30 class="adminbttn">
								<?=M_PWD_CONFIRM2?></font>
						</td>
					</tr>
					<tr>
						<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
					</tr>
					<tr>
						<td width=105 height="30">
							<div>
								<font size="2" face="돋움"><b><?=M_NAME?></b></font>
							</div>
						</td>
						<td height="30" colspan="3" align="left">
							&nbsp;
							<input name="m_adminname" size=30 class="adminbttn">
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
									<option value="1">1</option>
									<option value="2">2</option>
									<option value="3">3</option>
									<option value="4">4</option>
								</select></font>
						</td>
					</tr>
					<tr>
						<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
					</tr>
			</table>
		</td>
	</tr>
	<input type="hidden" name="keyfield" value="<? echo ($keyfield) ?>">
	<input type="hidden" name="key" value="<? echo ($key) ?>">
	<input type="hidden" name="page" value="<? echo ($page) ?>">
	</form>
</table>
<table width="800" border="0" cellspacing="0" cellpadding="4" >
	<tr>
		<td height="30"></td>
	</tr>
	<tr>
		<td height="20" align="left">
			<input type="button" value="<?=M_REGISTRATION?>" class="adminbttn" onClick="javascript:go_modify()">
		</td>
	</tr>
</table>
<br>
<br>



<? include "../inc/down_menu.php"; ?>