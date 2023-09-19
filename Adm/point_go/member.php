<?
#####################################################################

include "../common/user_function.php";
include "../common/dbconn.php";
include "../inc/top_menu.php";
include "../inc/left_menu_member.php";

//m_userno,m_id,m_passwd,m_name,m_level,m_mostaddress,m_krwtotal,m_confirm,m_signdate,m_block,m_key,m_email,m_handphone,m_webinfo,m_contury,m_ip,m_updatedate,m_measge,m_admmemo,m_device,m_dis


#####################################################################
?>

<script language="javascript">
<!--

function go_search() {
	document.form.action="member_ok.php";
	document.form.submit();
}

function go_point(tmp_mail) {
	document.location = "mailing.php?to_name=" + tmp_mail;
}
//-->
</script>
				<table width="1200" border="0" cellspacing="0" cellpadding="0" class="left_margin30">
				
					<tr><td height=30></td></tr>
					<tr><td>
							<table width="100%" border=0 cellpadding=0 cellspacing=0>
								<tr>
									<td width=60 align=center><img src="../image/icon2.gif" width=45 height=35 border=0></td>
									<td class='td14' align="left"><b>포인트충전</b></td>
									<td align="right"><form name=dform action="./member_dis_excel.php" method=post target="_blank">
										<input type="hidden" name="level_l" value="<?=$level_l?>">
										<? $file_name=mktime(date("H"),date("i"),date("s"),date("Y"),date("m"),date("d"));?>
										<input type="hidden" name ="file_name" value="<?=$file_name?>">
										<input type="hidden" name ="dis" value="<?=$dis?>">
										<input type="hidden" name ="member_count" value="<?=$member_count?>">
										<input type="submit" value="포인트충전">
									</form></td>
									<tr>
										<td><input type="text"></td>
									</tr>
								</tr>
							</table>
					</td></tr>
	
				</table>
				<br><br>
<? include "../inc/down_menu.php"; ?>