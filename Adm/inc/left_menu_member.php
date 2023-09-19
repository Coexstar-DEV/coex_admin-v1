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
							<td width=160><b  style="color:#fff"><i class="fas fa-user-friends" style="color:#ffd600"></i> MEMBER </b></td>
						</tr>
						<tr><td colspan=2 bgcolor='#fff' height=3></td></tr>
						<tr><td colspan=2 bgcolor='#ffffff' height=1></td></tr>
						<tr bgcolor='#F1F1F1'>
							<td width=25 height=35></td>
							<td width=140><a href='../most_bankmoneysearch/member.php?'><?=M_MEMBER_TRACE?></a></td>
						</tr>
						<tr><td colspan=2 bgcolor='#DCDCDC' height=1></td></tr>
						<tr><td colspan=2 bgcolor='#ffffff' height=1></td></tr>
						<tr bgcolor='#F1F1F1'>
							<td width=25 height=35></td>
							<td width=140><a href='../m_authorization/member.php?'><?=M_MEMBER_AUTH_HIST?></a></td>
						</tr>
						<tr><td colspan=2 bgcolor='#DCDCDC' height=1></td></tr>
						<tr><td colspan=2 bgcolor='#ffffff' height=1></td></tr>
						<tr bgcolor='#F1F1F1'>
							<td width=25 height=35></td>
							<td width=140><a href='../m_login/member.php?'><?=M_LOG_HIST?></a></td>
						</tr>
						<tr><td colspan=2 bgcolor='#DCDCDC' height=1></td></tr>
						<!--tr bgcolor='#F1F1F1'>
							<td width=25 height=35></td>
							<td width=140><a href='../m_authorization/member.php?m_check_div=3'>회원 미인증 내역</a></td>
						</tr>
						<tr><td colspan=2 bgcolor='#DCDCDC' height=1></td></tr>
						<tr><td colspan=2 bgcolor='#ffffff' height=1></td></tr-->
						<tr bgcolor='#F1F1F1'>
							<td width=25 height=35></td>
							<td width=140><a href='../member/member.php?'><?=M_MEMBER?>&nbsp;<?=M_MANAGEMENT?></a></td>
						</tr>
						<tr><td colspan=2 bgcolor='#DCDCDC' height=1></td></tr>
						<tr><td colspan=2 bgcolor='#ffffff' height=1></td></tr>
						<?if(check_manager_level2($adminlevel, ADMIN_LVL4)) { ?>
							<tr><td colspan=2 bgcolor='#F1F1F1' height=0></td></tr>
						<tr bgcolor='#303030'>
							<td width=25 height=60></td>
							<td width=160><b  style="color:#fff"><i class="fas fa-user-lock" style="color:#ffd600"></i> MANAGER </b></td>
						</tr>
						<tr><td colspan=2 bgcolor='#fff' height=3></td></tr>
							<tr><td colspan=2 bgcolor='#F1F1F1' height=5></td></tr>
						<tr bgcolor='#F1F1F1'>
							<td width=25 height=35></td>
							<td width=140><a href='../admin_member/member.php?'><?=M_MANAGER?>&nbsp;<?=M_MANAGEMENT?></a></td>
						</tr>
						<tr><td colspan=2 bgcolor='#DCDCDC' height=1></td></tr>
						<tr><td colspan=2 bgcolor='#ffffff' height=1></td></tr>
						<?}?>
							<tr><td colspan=2 bgcolor='#F1F1F1' height=5></td></tr>
					
						<tr><td colspan=2 bgcolor='#ffffff' height=1></td></tr>
						<tr bgcolor='#F1F1F1'>
							<td width=25 height=35></td>
							<td width=140><a href='../m_admlogin/member.php?'><?=M_MANAGER_LOG?></a></td>
						</tr>
						<tr><td colspan=2 bgcolor='#DCDCDC' height=1></td></tr>
						<tr><td colspan=2 bgcolor='#F1F1F1' height=0></td></tr>
						<tr bgcolor='#303030'>
							<td width=25 height=60></td>
							<td width=160><b  style="color:#fff"><i class="fas fa-wallet" style="color:#ffd600"></i> ASSETS </b></td>
						</tr>
						<tr><td colspan=2 bgcolor='#fff' height=3></td></tr>
						<tr><td colspan=2 bgcolor='#ffffff' height=1></td></tr>
						<tr bgcolor='#F1F1F1'>
							<td width=25 height=35></td>
							<td width=140><a href='../call_bankmoney/member1.php?'><?=M_MEMBANK_STATUS?></a></td>
						</tr>

						<?
							if(check_manager_level2($adminlevel, ADMIN_LVL4)) { 
							echo "<tr><td colspan=2 bgcolor='#DCDCDC' height=1></td></tr>";
							echo "<tr><td colspan=2 bgcolor='#ffffff' height=1></td></tr>";
							echo "<tr bgcolor='#F1F1F1'>";
							echo "<td width=25 height=35></td>";
							echo "<td width=140><a href='../all_bankmoney/member.php?'>". M_ALL. " " .M_MEMBANK_STATUS."</a></td> ";
							echo "</tr>";
							}

						?>
						<tr><td colspan=2 bgcolor='#DCDCDC' height=1></td></tr>
						<tr><td colspan=2 bgcolor='#ffffff' height=1></td></tr>






					</table>
				</td>

				<td align=center valign=top><!-- 우측 컨텐츠 부분 -->