
<tr>
	<td><!-- 컨텐츠 부분 -->
		<table width=1200 border=0 cellpadding=0 cellspacing=0 bgcolor='#ffffff' class="content_box">
			<tr>
				<td width=170 height=450 bgcolor='#F1F1F1' valign=top rowspan=2><!-- 좌측 메뉴부분 -->
					<table width=165 border=0 cellpadding=0 cellspacing=0>
						<tr><td colspan=2 bgcolor='#F1F1F1' height=5></td></tr>
						<tr bgcolor='#F1F1F1'>
							<td width=25 height=35></td>
							<td width=140><a href='../b_coinorderbuy/member_wait.php?div=1'><?=M_ORDER_WAIT?></a></td>
						</tr>
						<tr><td colspan=2 bgcolor='#DCDCDC' height=1></td></tr>
						<tr><td colspan=2 bgcolor='#ffffff' height=1></td></tr>

					<?
					$query = "SELECT 	c_no,c_coin,c_wcommission,c_limit,c_asklimit,c_unit,c_use,c_rank,c_signdate FROM $table_setup ";
					$query .= "where c_use='1' and c_basecoin = 0 ORDER BY c_rank+0 asc";
					$stmt = $pdo->prepare($query);
					$stmt->execute();
					
					$total_record_coin_pdo = $stmt->rowCount();
					?>
					<?for($ki=0; $ki < $total_record_coin_pdo; $ki++) {
						$stmt = $pdo->prepare($query);
						$stmt->execute();
						$result_coin_pdo = $stmt->fetchAll();
						$c_no = $result_coin_pdo[$ki][0];
						$c_coin = $result_coin_pdo[$ki][1];
					?>
						<tr><td colspan=2 bgcolor='#F1F1F1' height=5></td></tr>
						<tr bgcolor='#F1F1F1'>
							<td width=25 height=35></td>
							<td width=140><a href='../b_coinorderbuy/member.php?b_div_div=<?=$c_no?>&div=1'><?=$c_coin?><?=M_ORDER_BUYSELL_HIST?></a></td>
						</tr>
						<tr><td colspan=2 bgcolor='#DCDCDC' height=1></td></tr>
						<tr><td colspan=2 bgcolor='#ffffff' height=1></td></tr>

					<?}?>
					</table>
				</td>
				<td align=center valign=top><!-- 우측 컨텐츠 부분 -->