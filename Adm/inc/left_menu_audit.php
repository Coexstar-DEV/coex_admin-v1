<?
//session_start();

//include_once "../common/". ($_SESSION["language"] == "kr" ? "lang_kr.php" : "lang_en.php");
include "../inc/adm_chk.php";
if (isset($_SESSION["admin_id"])) {
	$admin_id = $_SESSION["admin_id"];
} else {
	$admin_id = "";
}
$adminlevel = $_SESSION["level"];
?>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<link href="../fontawesome/css/all.css" rel="stylesheet" type="text/css">

<tr>
	<td>
		<table width=1600 border=0 cellpadding=0 cellspacing=0 bgcolor='#ffffff' class="content_box">
			<tr>
				<td width=190 height=550 bgcolor='#F1F1F1' valign=top rowspan=2><!-- 좌측 메뉴부분 -->
					<table width=195 border=0 cellpadding=0 cellspacing=0>
						<tr><td colspan=2 bgcolor='#F1F1F1' height=0></td></tr>
						<tr bgcolor='#303030'>
							<td width=25 height=60></td>
							<td width=160><b  style="color:#fff"><i class="fas fa-file-pdf" style="color:#ffd600"></i> ACTIVITY LOGS</b></td>
						</tr>
						<tr><td colspan=2 bgcolor='#fff' height=3></td></tr>
						<?php if(check_manager_level2($adminlevel, ADMIN_LVL4)){ ?>
						<tr><td colspan=2 bgcolor='#ffffff' height=3></td></tr>
						<tr bgcolor='#F1F1F1'>
							<td width=25 height=35></td>
							<td width=140><a href='../m_audit/member.php?'>Employee Activity</a></td>
						</tr>
						<tr><td colspan=2 bgcolor='#fff' height=1></td></tr>
						<?php } else { ?>
						<?php } ?>
						<?php if(check_manager_level2($adminlevel, ADMIN_LVL4) || $admin_id == 'LMAlcaraz1993'){ ?>
						<tr><td colspan=2 bgcolor='#ffffff' height=1></td></tr>
						<tr bgcolor='#F1F1F1'>
							<td width=25 height=35></td>
							<td width=140><a href='../m_audit/member_deposit.php?'>Deposit History</a></td>
						</tr>
						<tr><td colspan=2 bgcolor='#fff' height=1></td></tr>
						<tr><td colspan=2 bgcolor='#ffffff' height=1></td></tr>
						<tr bgcolor='#F1F1F1'>
							<td width=25 height=35></td>
							<td width=140><a href='../m_audit/member_withdrawal.php?'>Withdrawal History</a></td>
						</tr>
						<tr><td colspan=2 bgcolor='#fff' height=1></td></tr>
					
						<tr><td colspan=2 bgcolor='#ffffff' height=1></td></tr>
						<?php } else { ?>
						<?php } ?>


						<?php if(check_manager_level2($adminlevel, ADMIN_LVL4) || $admin_id == 'patwin.ng'){ ?>
						<tr><td colspan=2 bgcolor='#ffffff' height=1></td></tr>
						<tr bgcolor='#F1F1F1'>
							<td width=25 height=35></td>
							<td width=140><a href='../m_audit/member_depositphp.php?'>Deposit PHP History</a></td>
						</tr>
						<tr><td colspan=2 bgcolor='#fff' height=1></td></tr>
						<tr><td colspan=2 bgcolor='#ffffff' height=1></td></tr>
						<tr bgcolor='#F1F1F1'>
							<td width=25 height=35></td>
							<td width=140><a href='../m_audit/member_withdrawalphp.php?'>Withdrawal PHP History</a></td>
						</tr>
						<tr><td colspan=2 bgcolor='#fff' height=1></td></tr>
					
						<tr><td colspan=2 bgcolor='#ffffff' height=1></td></tr>
						<?php } else { ?>
						<?php } ?>


						<?php if(check_manager_level2($adminlevel, ADMIN_LVL4)){ ?>
						<tr><td colspan=2 bgcolor='#F1F1F1' height=5></td></tr>
						<tr bgcolor='#F1F1F1'>
							<td width=25 height=35></td>
							<td width=140><a href='../m_audit/member_trading.php'>Trading History</a></td>
						</tr>
						<tr><td colspan=2 bgcolor='#fff' height=1></td></tr>
						<tr><td colspan=2 bgcolor='#ffffff' height=1></td></tr>
						<?php } else { ?>
						<?php } ?>
						<?php if(check_manager_level2($adminlevel, ADMIN_LVL4) || $admin_id == 'patwin.ng'){ ?>
						<tr><td colspan=2 bgcolor='#F1F1F1' height=0></td></tr>
						<tr bgcolor='#F1F1F1'>
							<td width=25 height=35></td>
							<td width=140><a href='../m_audit/member_multiple.php'>Multiple Accounts</a></td>
						</tr>
						<tr><td colspan=2 bgcolor='#fff' height=1></td></tr>

						<tr><td colspan=2 bgcolor='#ffffff' height=1></td></tr>
						<?php } else { ?>
						<?php } ?>
						<?php if(check_manager_level2($adminlevel, ADMIN_LVL4) || $admin_id == 'LMAlcaraz1993'){ ?>
						<tr><td colspan=2 bgcolor='#F1F1F1' height=0></td></tr>
						<tr bgcolor='#303030'>
							<td width=25 height=60></td>
							<td width=160><b  style="color:#fff"><i class="fas fa-file-pdf" style="color:#ffd600"></i> PDF</b></td>
						</tr>
						<tr><td colspan=2 bgcolor='#fff' height=3></td></tr>
						<tr bgcolor='#F1F1F1'>
							<td width=25 height=35></td>
							<td width=140><a href='../m_audit/member_individual.php'>Withdrawal PDF</a></td>
						</tr>
						<tr><td colspan=2 bgcolor='#fff' height=3></td></tr>
						<tr bgcolor='#F1F1F1'>
							<td width=25 height=35></td>
							<td width=140><a href='../m_audit/member_individualdep.php'>Deposit PDF</a></td>
						</tr>
						<?php } else { ?>
						<?php } ?>




					</table>
				</td>

				<td align=center valign=top><!-- 우측 컨텐츠 부분 -->