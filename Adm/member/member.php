<?php

session_start();

include_once "../common/user_function.php";
include_once "../common/dbconn.php";
include_once "../common/". ($_SESSION["language"] == "kr" ? "lang_kr.php" : "lang_en.php");
include_once "../inc/top_menu.php";

include_once "../inc/left_menu_member.php";
include "../inc/adm_chk.php";
if (isset($_SESSION["admin_id"])) {
	$admin_id = $_SESSION["admin_id"];
} else {
	$admin_id = "";
}
$adminlevel = $_SESSION["level"];
//$LOG_LEVEL = 1;
$LOG_TAG = basename(__FILE__);

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
if (isset($_REQUEST["m_country_name"])) {
	$m_country_name = sqlfilter($_REQUEST["m_country_name"]);
} else {
	$m_country_name = "";
}

$encoded_key = urlencode($key);

$query_pdo = "SELECT m_userno,m_id,m_passwd,m_name,m_level,m_confirm,m_signdate,m_block,m_key,m_email,m_handphone,m_webinfo,m_contury,m_conturyname,m_ip,m_updatedate,m_measge,m_admmemo,m_device,m_otpcheck,m_banknum,m_bankname,m_birtday,m_address,m_smskey,m_pre, IFNULL(m_delete,0), m_activation_status FROM $member ";


if ($key != "") {
	$query_pdo .= " where IFNULL(m_delete,0) <> 1 and $keyfield LIKE '%$key%' AND ifnull(m_delete,0) <> 1 ";

	if($m_country_name != "") {
		$query_pdo .= " and m_conturyname = '".$m_country_name."' ";
	}
}
else if($m_country_name != "") {
	$query_pdo .= " where m_conturyname = '".$m_country_name."' AND ifnull(m_delete,0) <> 1 ";
}
else {
	$query_pdo .= "where ifnull(m_delete,0) <> 1 ";
}
$query_pdo .= "ORDER BY m_userno DESC";

$total_record_pdo = pdo_excute_count("select count:", $query_pdo, NULL);

if (isset($_REQUEST["page"])) {
	$page = $_REQUEST["page"];
} else {
	$page = 1;
}
$num_per_page = 10;
$page_per_block = 10;

if (!$total_record_pdo) {
	$first = 1;
	$last = 0;
} else {
	$first = $num_per_page * ($page - 1);
	$last = $num_per_page * $page;

	$IsNext = $total_record_pdo - $last;

	if ($IsNext > 0) {
		$last -= 1;
	} else {
		$last = $total_record_pdo - 1;
	}
}

$total_page = ceil($total_record_pdo / $num_per_page);
$article_num = $total_record_pdo - $num_per_page * ($page - 1);
$mode = "keyfield=$keyfield&key=$encoded_key&m_country_name=$m_country_name";
?>

<script language="javascript">
	function go_excel() {
		document.dform.submit();
	}

	function go_search() {
		document.form.action = "member.php?dis=<?= $dis ?>";
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

<div class="row">
	<div class="column">
	<b><p>Total Verified<p></b>
		<h1>
		<?php 

			$count2 = "SELECT COUNT(m_activation_status) as a FROM $member WHERE m_activation_status = 2 AND  ifnull(m_delete,0) <> 1";
			$stmt2 = $pdo->prepare($count2);
			$stmt2->execute();

			while ($row2 = $stmt2->fetch()) 
			{

			echo $row2['a'];
			
			}
			?>
	</h1>
	</div>
	<div class="column">
	<b><p>Total Registered</p></b>
			<table width="50%" align="center">
				<tr align="center"> 
					<th> D </th> 
					<th> M </th> 
					<th> Y </th> 
				</tr>
				<tbody>
				<tr align="center"> 
					<td width="50"> 
					<?php 
						$count5 = "SELECT COUNT(*) as cnt, ifnull(m_delete,0), DATE(FROM_UNIXTIME(m_signdate)) FROM $member WHERE DATE(FROM_UNIXTIME(m_signdate)) = CURDATE() AND ifnull(m_delete,0) <> 1 GROUP BY DATE(FROM_UNIXTIME(m_signdate));";
						$stmt5 = $pdo->prepare($count5);
						$stmt5->execute();

						while ($row5 = $stmt5->fetch()) 
						{
						echo $row5['cnt'];
						}
						?>						
					 </td>
					<td width="50"> 
						<?php 

						$count4 = "SELECT COUNT(*) as cont,  ifnull(m_delete,0), MONTH(FROM_UNIXTIME(m_signdate)) FROM $member WHERE MONTH(FROM_UNIXTIME(m_signdate)) = MONTH(CURDATE()) AND ifnull(m_delete,0) <> 1 group by MONTH(FROM_UNIXTIME(m_signdate));	";
						$stmt4 = $pdo->prepare($count4);
						$stmt4->execute();

						while ($row4 = $stmt4->fetch()) 
						{
						echo $row4['cont'];
						}
						?>					
					</td>
					<td width="50">
					<?php 

					$count1 = "SELECT COUNT(*) as c,ifnull(m_delete,0) FROM $member WHERE ifnull(m_delete,0) <> 1";
					$stmt1 = $pdo->prepare($count1);
					$stmt1->execute();

					while ($row1 = $stmt1->fetch()) 
					{
					  echo $row1['c'];
					}
					?>
					 </td>
				</tr>
				</tbody>
			</table>
	<br>
	</div>
	<div class="column">
	<b><p>Total Blocked<p></b>
	<h1>
		<?php 

		$count3 = "SELECT COUNT(m_block) as a FROM $member WHERE m_block = 1 AND  ifnull(m_delete,0) <> 1";
		$stmt3 = $pdo->prepare($count3);
		$stmt3->execute();

		while ($row3 = $stmt3->fetch()) 
		{

		echo $row3['a'];

		}
		?>
		</h1>
	</div>
</div>

<table align="center" width="1400" border="0" cellspacing="0" cellpadding="0" class="left_margin30">

	<tr>
		<td>
			<table align="center" width="100%" border=0 cellpadding=0 cellspacing=0>
				<tr>
					<td class='td14' align="center"><?=M_MEMBER?>&nbsp;<?=M_MANAGEMENT?></td>
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
				<table width="800" align="center" border="0" cellspacing="0" cellpadding="4">
					<tr>

						<td height="20" align="center">
							<select name="m_country_name" style="vertical-align:top;">
								<option value=""><?=M_COUNTRY_NAME?></option>
								<option value="Korea" <? if ($m_country_name == 'Korea') echo ("selected"); ?>>Korea (+82)</option>
								<option value="Singapore" <? if ($m_country_name == 'Singapore') echo ("selected"); ?>>Singapore (+65)</option>
								<option value="China" <? if ($m_country_name == 'China') echo ("selected"); ?>>China (+86)</option>
								<option value="Japan" <? if ($m_country_name == 'Japan') echo ("selected"); ?>>Japan (+81)</option>
								<option value="Vietnam" <? if ($m_country_name == 'Vietnam') echo ("selected"); ?>>Vietnam (+84)</option>
								<option value="Hongkong" <? if ($m_country_name == 'Hongkong') echo ("selected"); ?>>Hongkong (+852)</option>
								<option value="Indonesia" <? if ($m_country_name == 'Indonesia') echo ("selected"); ?>>Indonesia (+62)</option>
								<option value="Philippines" <? if ($m_country_name == 'Philippines') echo ("selected"); ?>>Philippines (+63)</option>
								<option value="India" <? if ($m_country_name == 'India') echo ("selected"); ?>>India (+91)</option>
								<option value="Usa" <? if ($m_country_name == 'USA') echo ("selected"); ?>>USA (+1)</option>
								<option value="Canada" <? if ($m_country_name == 'Canada') echo ("selected"); ?>>Canada (+1)</option>
								<option value="Australia" <? if ($m_country_name == 'Australia') echo ("selected"); ?>>Australia (+61)</option>
								<option value="Austria" <? if ($m_country_name == 'Austria') echo ("selected"); ?>>Austria (+43)</option>
								<option value="Brazil" <? if ($m_country_name == 'Brazil') echo ("selected"); ?>>Brazil (+55)</option>
								<option value="Cambodia" <? if ($m_country_name == 'Cambodia') echo ("selected"); ?>>Cambodia (+855)</option>
								<option value="Finland" <? if ($m_country_name == 'Finland') echo ("selected"); ?>>Finland (+358)</option>
								<option value="France" <? if ($m_country_name == 'France') echo ("selected"); ?>>France (+33)</option>
								<option value="Great Britain" <? if ($m_country_name == 'Great Britain') echo ("selected"); ?>>Great Britain (+44)</option>
								<option value="Greece" <? if ($m_country_name == 'Greece') echo ("selected"); ?>>Greece (+30)</option>
								<option value="Guam" <? if ($m_country_name == 'Guam') echo ("selected"); ?>>Guam (+1671)</option>
								<option value="Israel" <? if ($m_country_name == 'Israel') echo ("selected"); ?>>Israel (+972)</option>
								<option value="Italy" <? if ($m_country_name == 'Italy') echo ("selected"); ?>>Italy (+39)</option>
								<option value="Kuwait" <? if ($m_country_name == 'Kuwait') echo ("selected"); ?>>Kuwait (+965)</option>
								<option value="Laos" <? if ($m_country_name == 'Laos') echo ("selected"); ?>>Laos (+856)</option>
								<option value="Macau" <? if ($m_country_name == 'Macau') echo ("selected"); ?>>Macau (+853)</option>
								<option value="Malaysia" <? if ($m_country_name == 'Malaysia') echo ("selected"); ?>>Malaysia (+60)</option>
								<option value="Netherlands" <? if ($m_country_name == 'Netherlands') echo ("selected"); ?>>Netherlands (+31)</option>
								<option value="Poland" <? if ($m_country_name == 'Poland') echo ("selected"); ?>>Poland (+48)</option>
								<option value="Portugal" <? if ($m_country_name == 'Portugal') echo ("selected"); ?>>Portugal (+351)</option>
								<option value="Spain" <? if ($m_country_name == 'Spain') echo ("selected"); ?>>Spain (+34)</option>
								<option value="Sri Lanka" <? if ($m_country_name == 'Sri Lanka') echo ("selected"); ?>>Sri Lanka (+94)</option>
								<option value="Sweden" <? if ($m_country_name == 'Sweden') echo ("selected"); ?>>Sweden (+46)</option>
								<option value="Switzerland" <? if ($m_country_name == 'Switzerland') echo ("selected"); ?>>Switzerland (+41)</option>
								<option value="Taiwan" <? if ($m_country_name == 'Taiwan') echo ("selected"); ?>>Taiwan (+886)</option>
								<option value="Thailand" <? if ($m_country_name == 'Thailand') echo ("selected"); ?>>Thailand (+66)</option>
								<option value="Turkey" <? if ($m_country_name == 'Turkey') echo ("selected"); ?>>Turkey (+90)</option>
							</select>
							<select name="keyfield">
								<option value="m_id" <? if ($keyfield == 'm_id') echo ("selected"); ?>><?=M_ID?></option>
								<option value="m_name" <? if ($keyfield == 'm_name') echo ("selected"); ?>><?=M_NAME?></option>
								<option value="m_handphone" <? if ($keyfield == 'm_handphone') echo ("selected"); ?>><?=M_PHONE?></option>
							</select>
							<input type="text" name="key" value="<?= $key ?>" size="40" maxlength="40" class="adminbttn">

							<input type="button" value="<?=M_SEARCH?>" class="adminbttn" onClick="javascript:go_search()">
							<?php if(check_manager_level2($adminlevel, ADMIN_LVL4) || $admin_id == 'gnad' || $admin_id == 'alisterlawrence' ){ ?>
						
						<a href="./member_gen.php" class="exBt"> <font style="color:#fff"> Download </font> </a>	

			<?php }else { ?>
				&nbsp;
			<?php } ?>
							<!-- <input type="button" value="<?=M_REGISTER?>" class="adminbttn" onClick="javascript:location.href='member_write.php'"> -->
						</td>
					</tr>
				</table>
				<table width="1200" align="center" border='0' cellspacing='0' cellpadding='0'>
					<tr>
						<td colspan=10 height=3 bgcolor='#ffffff'></td>
					</tr>
					<tr align="center" bgcolor='#ffffff' class="list_title">
						<td width="50" height="30"><?=M_NO?></td>
						<td width="100" height="30"><?=M_ID?>(<?=M_LEVEL?>)</td>
						<td width="150" height="30"><?=M_NAME?></td>
						<td width="150" height="30"><?=M_PHONE?></td>
						<td width="90" height="30"><?=M_COUNTRY_NAME?></td>
						<td width="90" height="30"><?=M_AUTH?></td>
						<td width="90" height="30"><?=M_SIGN?></td>
						<td width="90" height="30"><?=M_DEL?></td>
						<td width="90" height="30"><?=M_MAIL_AUTH?></td>
					</tr>
					<tr>
						<td colspan=10 height=2 bgcolor='#D2DEE8'></td>
					</tr>
					<tr>
						<td colspan=10 height=3></td>
					</tr>
					<?php

					$ii = 0;
					$query_pdo = convert_page_query($query_pdo, $num_per_page, $page);
					$stmt = pdo_excute("select", $query_pdo, NULL);
					while ($row = $stmt->fetch()) {

						$m_userno = $row[0];
						$m_id = $row[1];
						$m_name = $row[3];
						$m_level = $row[4];
						$m_confirm = $row[5];
						$m_signdate = $row[6];
						$m_block = $row[7];
						$m_measge = $row[16];
						$m_handphone = $row[10];
						$m_pre = $row[25];
						$m_delete = $row[26];
						$m_activation_status = $row[27];
						$m_country_name = $row[13];

						if ($m_confirm == "0" || $m_confirm == "") {
							$m_confirm = M_AUTH_NO;
						} else {
							$m_confirm = M_AUTH_YES;
						}
						if ($m_block == "0" || $m_block == "") {
							$m_block = M_BLOCK_NO;
						} else if ($m_block == "1") {
							$m_block = M_BLOCK_YES;
						}

						$m_signdate = date("Y-m-d", $m_signdate);

						if (($ii + 1) % 2 == 0) {
							$kk_bgcolor = "#FFFFFF";
						} else {
							$kk_bgcolor = "#F6F6F6";
						}
						if ($m_pre == "1") {
							$m_pre = "O";
						} else {
							$m_pre = "X";
						}

						if ($m_activation_status == "1" || $m_activation_status == "2") {
							$m_activation_status = M_AUTH_YES;
						} else {
							$m_activation_status = M_AUTH_NO;
						}

						?>
						<tr align="center">
							<td height="30"><?= $m_userno ?></td>
							<td height="30"><a href="member_modify.php?<?= $mode ?>&page=<?= $page ?>&m_id=<?= $m_id ?>"><?= $m_id ?>(<?= $m_level ?>)</td>
							</a>
				</td>
				<td height="30" class="notranslate"><?= $m_name ?></td>
				<td><?= $m_handphone ?> </td>
				<td><?= $m_country_name ?> </td>

				<td height="30"><?= $m_confirm ?></td>
				<td height="30"><?= $m_signdate ?></td>
				<td height="30"><?= $m_block ?></td>
				<td height="30"><?= $m_activation_status ?></td>
				<td height="30"><?= $m_adminid ?></td>

			</tr>
			<tr>
				<td colspan=10 height=1 bgcolor='#D2DEE8'></td>
			</tr>

			<?php
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
				<?php
				$total_block = ceil($total_page / $page_per_block);
				$block = ceil($page / $page_per_block);
				$first_page = ($block - 1) * $page_per_block;
				$last_page = $block * $page_per_block;
				if ($total_block <= $block) {
					$last_page = $total_page;
				}

				if ($page != '1') {
					echo "<a href=\"member.php?$mode&page=1\" onMouseOver=\"status='';return true;\" onMouseOut=\"status=''\"><font color=\"#666666\">".M_FIRST."</a>&nbsp;";
				}
				if ($page > 1) {
					$page_num = $page - 1;
					echo "<a href=\"member.php?$mode&page=$page_num\" onMouseOver=\"status='".M_PREVPAGE."';return true;\" onMouseOut=\"status=''\"><font color=\"#666666\">◀</font></a>&nbsp;";
				}

				for ($direct_page = $first_page + 1; $direct_page <= $last_page; $direct_page++) {
					if ($page == $direct_page) {
						echo "<font color=\"#666666\">&nbsp;<b>$direct_page</b></font>&nbsp;";
					} else {
						echo "&nbsp;<a href=\"member.php?$mode&page=$direct_page\" onMouseOver=\"status='go to page $direct_page';return true;\" onMouseOut=\"status=''\"><font color=\"#666666\">$direct_page</font></a>&nbsp;";
					}
				}
				if ($IsNext > 0) {
					$page_num = $page + 1;
					echo "&nbsp;<a href=\"member.php?$mode&page=$page_num\" onMouseOver=\"status='".M_NEXTPAGE."';return true;\" onMouseOut=\"status=''\"><font color=\"#666666\">▶</font></a>&nbsp;";
				}
				if ($page != $total_page) {
					echo "<a href=\"member.php?$mode&page=$total_page\" onMouseOver=\"status='';return true;\" onMouseOut=\"status=''\"><font color=\"#666666\">".M_LAST."</a>";
				}
				?>
			</font>
		</td>
	</tr>
	<input type="hidden" name="chk_num" value="<? echo ($chk_num) ?>">
	</form>
</table>
<br><br>
<?php include "../inc/down_menu.php"; ?>