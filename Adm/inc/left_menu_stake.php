<?
//session_start();

//include_once "../common/". ($_SESSION["language"] == "kr" ? "lang_kr.php" : "lang_en.php");

?>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<link href="../fontawesome/css/all.css" rel="stylesheet" type="text/css">
<tr>
	<td><!-- 컨텐츠 부분 -->
		<table width=1200 border=0 cellpadding=0 cellspacing=0 bgcolor='#ffffff' class="content_box">
			<tr>
				<td width=190 height=450 bgcolor='#F1F1F1' valign=top rowspan=2><!-- 좌측 메뉴부분 -->
					<table width=165 border=0 cellpadding=0 cellspacing=0>
					<tr><td colspan=2 bgcolor='#F1F1F1' height=0></td></tr>
						<tr bgcolor='#303030'>
							<td width=25 height=60></td>
							<td width=160><b  style="color:#fff"><i class="fas fa-user-friends" style="color:#ffd600"></i> STAKE </b></td>
						</tr>
						<tr><td colspan=2 bgcolor='#fff' height=3></td></tr>
						<?php if(check_manager_level2($adminlevel, ADMIN_LVL4)){ ?>
						<tr><td colspan=2 bgcolor='#ffffff' height=1></td></tr>
						<tr bgcolor='#F1F1F1'>
							<td width=25 height=35></td>
							<td width=140><a href='../m_stake/member.php?'>Pending Stakes</a></td>
						</tr>
						<tr><td colspan=2 bgcolor='#ffffff' height=1></td></tr>
						<tr bgcolor='#F1F1F1'>
							<td width=25 height=35></td>
							<td width=140><a href='../m_stake/member_all.php?'>Stakes History</a></td>
						</tr>
						<?php } else { ?>
						<?php } ?>

						<?php if(check_manager_level2($adminlevel, ADMIN_LVL4) || $admin_id == 'alisterlawrence'){ ?>
						<tr><td colspan=2 bgcolor='#ffffff' height=1></td></tr>

						<tr bgcolor='#F1F1F1'>
							<td width=25 height=35></td>
							<td width=140><a href='../m_stake/member_indiv.php?'>Individual Stakes</a></td>
						</tr>


						<?php } else { ?>
						<?php } ?>

					</table>
				</td>

				<td align=center valign=top><!-- 우측 컨텐츠 부분 -->