<?
session_start();

include_once "../common/dbconn.php";
include_once "../common/user_function.php";
include_once "../common/". ($_SESSION["language"] == "kr" ? "lang_kr.php" : "lang_en.php");
include_once "../inc/top_menu.php";
include_once "../inc/left_menu_stake.php";
include "../inc/adm_chk.php";
if (isset($_SESSION["admin_id"])) {
	$admin_id = $_SESSION["admin_id"];
} else {
	$admin_id = "";
}
$adminlevel = $_SESSION["level"];
$LOG_LEVEL = 1;
$LOG_TAG = basename(__FILE__);


$key = (isset($_REQUEST["key"]) ? $_REQUEST["key"] : "");
$encoded_key = urlencode($key);


if (isset($_REQUEST["key"])) {
	$keyfield = $_REQUEST["keyfield"];
}

$page = is_empty($_REQUEST["page"]) ? 1 : $_REQUEST["page"];
//err_log("================set page:$page");

$num_per_page = 10;
$page_per_block = 10;

$IsNext = is_empty($_REQUEST["IsNext"]) ? "" : $_REQUEST["IsNext"];

// ==========================================

$query_pdo = "SELECT m_no, m_userno, m_id, m_name, m_country, m_level, m_amount, m_total, m_div, m_status, m_startdate, m_enddate FROM $stake";
if (!is_empty($keyfield) && !is_empty($key)) {
	$query_pdo .= " and $keyfield LIKE '%$key%'";
}
$query_pdo .= " ORDER BY m_no DESC ";
$pdo_in = [$wdate1, $wdate2];

try {
	$total_record_pdo = pdo_excute_count("withdrawList", $query_pdo, $pdo_in);
} catch (PDOException $e) {
	err_log($e->getMessage());
	exit;
}

if ($total_record_pdo == 0) {
	$first = 1;
	$last = 0;
	//err_log("=>1 paging: total:$total_record_pdo, num/page:$num_per_page, page:$page, first:$first, last:$last, next:$IsNext");
} else {
	$first = $num_per_page * ($page - 1);
	$last = $num_per_page * $page;

	$IsNext = $total_record_pdo - $last;
	//err_log("=>2 paging: total:$total_record_pdo, num/page:$num_per_page, page:$page, first:$first, last:$last, next:$IsNext");
	if ($IsNext > 0) {
		$last -= 1;
	} else {
		$last = $total_record_pdo - 1;
	}
}
//err_log("=>3 paging: total:$total_record_pdo, num/page:$num_per_page, page:$page, first:$first, last:$last, next:$IsNext");

$total_page = ceil($total_record_pdo / $num_per_page);
$article_num = $total_record_pdo - $num_per_page * ($page - 1);
?>

<script language="javascript">
	// function go_search() {
	// 	document.form.action = "member_coin.php?dis=<?= $dis ?>";
	// 	document.form.submit();
	// }

	function go_excel() {
		document.dform.submit();
	}

	function go_highpass(t_no) {
		if (confirm('<?=M_CONFIRM_MSG2?>')) {
			document.form.action = "high_pass.php?t_no=" + t_no;
			document.form.submit();
		}
	}

	function go_interest() {
		document.form.action = "interest.php";
		document.form.submit();
	}
</script>
<style>
.column {
  float: left;
  width: 33.33%;
	border: 1px solid #d0d0d0;
	margin: 10px;
	border-radius: 5px;
	margin:auto;
}
.row:after {
  content: "";
  display: table;
  clear: both;
}
.row{
	display:flex !important;
	padding: 2%;
	
}
</style>
<!-- <div class="row">

	<div class="column">
	<b><p>Total Stakes<p></b>
	<h1>
		<?php 

		$count3 = "SELECT m_cointotal as ac, m_div, m_id, m_cointotal FROM $m_bankmoney  WHERE m_id = 'dearrsc1014@gmail.com' and m_div = 54 ORDER BY m_no DESC LIMIT 1";
		$stmt3 = $pdo->prepare($count3);
		$stmt3->execute();

		while ($row3 = $stmt3->fetch()) 
		{
	
			echo $row3['m_cointotal'] . " " . "AF1";	

		}
		
		?>
		</h1>
	</div>
</div> -->

<table width="800" border="0" cellspacing="0" cellpadding="0" class="left_margin30">

	<tr>
		<td height=100></td>
	</tr>
	<tr>
		<td>
			<table width="1200" align="center" border=0 cellpadding=0 cellspacing=0>
				<tr>
					<td class='td14' align="center">Individual Stake Record</td>
				</tr>

			</table>
		</td>
	</tr>
	<form name="form" method="post">

		<tr>
			<td height=3></td>
		</tr>

		<tr>
			<td>

				<table width="1200" border='0' cellspacing='0' cellpadding='0'>
					<!-- <tr>
						<td colspan=16 height=3 bgcolor='#ffffff'></td>
						<td><a onclick="go_interest();">Go</a></td>
					</tr> -->
					<tr align="center" bgcolor='#ffffff' class="list_title">
						<td width="50" height="30"><?=M_NO?></td>
						<td width="50" height="30">Name</td>
						<td width="50" height="30">Email</td>
						<td width="100" height="30">Country</td>
						<td width="100" height="30">Level</td>
						<td width="100" height="30">Amount</td>
						<td width="120" height="30">100 Days</td>

						<td width="90" height="30">Start</td>
						<td width="90" height="30">End</td>
						<td width="90" height="30">Status</td>
					</tr>
					<tr>
						<td colspan=16 height=2 bgcolor='#D2DEE8'></td>
					</tr>
                    <?
					$ii = 0;

				
					$query_pdo = convert_page_query($query_pdo, $num_per_page, $page);
					$stmt = pdo_excute("select", $query_pdo, $pdo_in);
					while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

						$m_no = $row["m_no"];
						$m_name = $row["m_name"];
						$m_id = $row["m_id"];
						$m_country = $row["m_country"];
						$m_level = $row["m_level"];
						$m_amount = $row["m_amount"];
						$m_total = $row["m_total"];
						$m_status = $row["m_status"];
						$m_startdate = $row["m_startdate"];
						$m_enddate = $row["m_enddate"];




                        $m_startdate2 = date("Y-m-d H:i:s", $m_startdate);
						$m_enddate2 = date("Y-m-d H:i:s", $m_enddate);
						
	
						?>


					<tr align="center" bgcolor="<?= $kk_bgcolor ?>">
						<td height="30"><?= $m_no ?></td>
						<td height="30"><?= $m_name ?></td>
						<td height="30"><?= $m_id ?></td>
						<td height="30" align="center"><b><?= $m_country ?></b></td>
                        <td height="30" align="center"><?= $m_level ?></td>
                        <td height="30" align="center"><?= $m_amount ?></td>
                        <td height="30" align="center"><?= $m_total ?></td>
                        <td height="30" align="center"><?= $m_startdate2 ?></td>
                        <td height="30" align="center"><?= $m_enddate2 ?></td>
						<td height="30" align="center"><?php
						if($m_status == 0 ){
							$m_status2 = "Pending";
							echo $m_status2;
						}else if($m_status == 1){
							$m_status2 = "Approved";
							echo $m_status2;
						}else if($m_status == 2){
							$m_status2 = "Finished";
							echo $m_status2;
						} ?></td>
					</tr>

					<tr>
						<td colspan=12 height=1 bgcolor='#D2DEE8'></td>
					</tr>

					<?
						$article_num--;
						$ii++;
					}
					$chk_num = $last - $first + 1;
					?>
				</table>
			</td>
		</tr>
</table>
<table width="1000" border="0" cellspacing="0" cellpadding="4" class="left_margin30">
	<tr>
		<td height="20" align="center">
			<font color="#666666">
				<?
				#####################################################################
				$total_block = ceil($total_page / $page_per_block);
				$block = ceil($page / $page_per_block);
				$first_page = ($block - 1) * $page_per_block;
				$last_page = $block * $page_per_block;
				if ($total_block <= $block) {
					$last_page = $total_page;
				}

				if ($page != '1') {
					echo "<a href=\"member_indiv.php?$mode&page=1\" onMouseOver=\"status='';return true;\" onMouseOut=\"status=''\"><font color=\"#666666\">".M_FIRST."</a>&nbsp;";
				}
				if ($page > 1) {
					$page_num = $page - 1;
					echo "<a href=\"member_indiv.php?$mode&page=$page_num\" onMouseOver=\"status='".M_PREVPAGE."';return true;\" onMouseOut=\"status=''\"><font color=\"#666666\">◀</font></a>&nbsp;";
				}

				for ($direct_page = $first_page + 1; $direct_page <= $last_page; $direct_page++) {
					if ($page == $direct_page) {
						echo "<font color=\"#666666\">&nbsp;<b>$direct_page</b></font>&nbsp;";
					} else {
						echo "&nbsp;<a href=\"member_indiv.php?$mode&page=$direct_page\" onMouseOver=\"status='go to page $direct_page';return true;\" onMouseOut=\"status=''\"><font color=\"#666666\">$direct_page</font></a>&nbsp;";
					}
				}

				if ($IsNext > 0) {
					$page_num = $page + 1;
					echo "&nbsp;<a href=\"member_indiv.php?$mode&page=$page_num\" onMouseOver=\"status='".M_NEXTPAGE."';return true;\" onMouseOut=\"status=''\"><font color=\"#666666\">▶</font></a>&nbsp;";
				}
				if ($page != $total_page) {
					echo "<a href=\"member_indiv.php?$mode&page=$total_page\" onMouseOver=\"status='';return true;\" onMouseOut=\"status=''\"><font color=\"#666666\">".M_LAST."</a>";
				}
				?>
			</font>
		</td>
	</tr>
	<input type="hidden" name="chk_num" value="<? echo ($chk_num) ?>">
	</form>
</table>
<br><br>
<? include "../inc/down_menu.php"; ?>