<style>

.active {
  color: red !important;
}
.no {
  color: white !important;
}
</style>
<script type="text/javascript">
$(function(){
  $('tr').on('click', 'a', function(){
    $('a').removeClass('active');
    $(this).closest('.a').addClass('active');
  });
});
</script>

<tr>
	<td><!-- 컨텐츠 부분 -->
		<table width=1200 border=0 cellpadding=0 cellspacing=0 bgcolor='#ffffff' class="content_box">
			<tr>
				<td width=170 height=450 bgcolor='#F1F1F1' valign=top rowspan=2><!-- 좌측 메뉴부분 -->
					<table width=165 border=0 cellpadding=0 cellspacing=0>


					<?
					$query = "SELECT m_pay, m_no FROM coexstar.m_setup WHERE m_use = 1 and m_pay <> 'ETH' GROUP BY m_pay ";
					$stmt = $pdo->prepare($query);
					$stmt->execute();
					
					$coin_pay_all = $stmt->rowCount();
					?>
					<?for($ki=0; $ki < $coin_pay_all; $ki++) {
						$stmt = $pdo->prepare($query);
						$stmt->execute();
						$result_market_pdo = $stmt->fetchAll();
						$m_pay = $result_market_pdo[$ki][0];
						$active = $_SESSION['active'];
					?>
						<tr><td colspan=2 bgcolor='#F1F1F1' height=5></td></tr>
						<tr bgcolor='#F1F1F1'>
							<td width=25 height=35></td>
							<td width=140 id="coin" 
							><a href='../d_dashboard/member_sell.php?m_pay=<?php echo $m_pay?>&c_div=<?php 
							if($m_pay=='BTC'){echo '1';}
							else if($m_pay=='PHP'){echo '0';}
							else if($m_pay=='KRWC'){echo '3';} 
							else if($m_pay=='USDT'){echo '4';}?>'

							>
							
							<?=$m_pay ?>
							
							</a></td>
						</tr>
						<tr><td colspan=2 bgcolor='#DCDCDC' height=1></td></tr>
						<tr><td colspan=2 bgcolor='#ffffff' height=1></td></tr>

					<?}?>
					</table>
				</td>
				<td align=center valign=top><!-- 우측 컨텐츠 부분 -->