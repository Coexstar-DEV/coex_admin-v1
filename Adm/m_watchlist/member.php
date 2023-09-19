<?php

session_start();

include_once "../common/user_function.php";
include_once "../common/dbconn.php";
include_once "../common/". ($_SESSION["language"] == "kr" ? "lang_kr.php" : "lang_en.php");
include_once "../inc/top_menu.php";

include_once "../inc/left_menu_watchlist.php";

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
if (isset($_REQUEST["m_countryname"])) {
	$m_countryname = sqlfilter($_REQUEST["m_countryname"]);
} else {
	$m_countryname = "";
}

$encoded_key = urlencode($key);

$query_pdo = "SELECT id, m_id, m_name, m_handphone, m_birthday, m_country, m_countryname, m_address, m_signdate, m_remarks, m_adminno FROM $watchlist ";


if ($key != "") {
	$query_pdo .= " where $keyfield LIKE '%$key%' ";

	if($m_country_name != "") {
		$query_pdo .= " and m_countryname = '".$m_countryname."' ";
	}
}
else if($m_country_name != "") {
	$query_pdo .= " where m_countryname = '".$m_countryname."' ";
}
$query_pdo .= "ORDER BY m_signdate DESC";

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
$mode = "keyfield=$keyfield&key=$encoded_key&m_countryname=$m_countryname";
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
<table align="center" width="1000" border="0" cellspacing="0" cellpadding="0" class="left_margin30" height="100%" >

	<tr>
		<td height=30></td>
	</tr>
	<tr>
		<td>
			<table align="center" width="100%" border=0 cellpadding=0 cellspacing=0>
				<tr>
					<td class='td14' align="center">Watch List</td>
				</tr>
				<tr align="center" width="800">
					<td align="right">
					<form name=dform action="./member_dis_excel.php" method=post target="_blank">
							<input type="hidden" name="level_l" value="<?= $level_l ?>">
							<?php $file_name = mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y")); ?>
							<input type="hidden" name="file_name" value="<?= $file_name ?>">
							<input type="hidden" name="dis" value="<?= $dis ?>">
							<input type="hidden" name="member_count" value="<?= $member_count ?>">
							<input type="submit" value="<?= @$level_l ?> <?=M_DOWNLOAD?>" class="exBt">
						</form>
					</td>
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
				<table width="1000" align="center" border="0" cellspacing="0" cellpadding="4">
					<tr>

						<td height="20" align="center">
							<select name="m_countryname" style="vertical-align:top;">
								<option value=""><?=M_COUNTRY_NAME?></option>
								<option value="Korea" <? if ($m_countryname == 'Korea') echo ("selected"); ?>>Korea (+82)</option>
								<option value="Singapore" <? if ($m_countryname == 'Singapore') echo ("selected"); ?>>Singapore (+65)</option>
								<option value="China" <? if ($m_countryname == 'China') echo ("selected"); ?>>China (+86)</option>
								<option value="Japan" <? if ($m_countryname == 'Japan') echo ("selected"); ?>>Japan (+81)</option>
								<option value="Vietnam" <? if ($m_countryname == 'Vietnam') echo ("selected"); ?>>Vietnam (+84)</option>
								<option value="Hongkong" <? if ($m_countryname == 'Hongkong') echo ("selected"); ?>>Hongkong (+852)</option>
								<option value="Indonesia" <? if ($m_countryname == 'Indonesia') echo ("selected"); ?>>Indonesia (+62)</option>
								<option value="Philippines" <? if ($m_countryname == 'Philippines') echo ("selected"); ?>>Philippines (+63)</option>
								<option value="India" <? if ($m_countryname == 'India') echo ("selected"); ?>>India (+91)</option>
								<option value="Usa" <? if ($m_countryname == 'USA') echo ("selected"); ?>>USA (+1)</option>
								<option value="Canada" <? if ($m_countryname == 'Canada') echo ("selected"); ?>>Canada (+1)</option>
								<option value="Australia" <? if ($m_countryname == 'Australia') echo ("selected"); ?>>Australia (+61)</option>
								<option value="Austria" <? if ($m_countryname == 'Austria') echo ("selected"); ?>>Austria (+43)</option>
								<option value="Brazil" <? if ($m_countryname == 'Brazil') echo ("selected"); ?>>Brazil (+55)</option>
								<option value="Cambodia" <? if ($m_countryname == 'Cambodia') echo ("selected"); ?>>Cambodia (+855)</option>
								<option value="Finland" <? if ($m_countryname == 'Finland') echo ("selected"); ?>>Finland (+358)</option>
								<option value="France" <? if ($m_countryname == 'France') echo ("selected"); ?>>France (+33)</option>
								<option value="Great Britain" <? if ($m_countryname == 'Great Britain') echo ("selected"); ?>>Great Britain (+44)</option>
								<option value="Greece" <? if ($m_countryname == 'Greece') echo ("selected"); ?>>Greece (+30)</option>
								<option value="Guam" <? if ($m_countryname == 'Guam') echo ("selected"); ?>>Guam (+1671)</option>
								<option value="Israel" <? if ($m_countryname == 'Israel') echo ("selected"); ?>>Israel (+972)</option>
								<option value="Italy" <? if ($m_countryname == 'Italy') echo ("selected"); ?>>Italy (+39)</option>
								<option value="Kuwait" <? if ($m_countryname == 'Kuwait') echo ("selected"); ?>>Kuwait (+965)</option>
								<option value="Laos" <? if ($m_countryname == 'Laos') echo ("selected"); ?>>Laos (+856)</option>
								<option value="Macau" <? if ($m_countryname == 'Macau') echo ("selected"); ?>>Macau (+853)</option>
								<option value="Malaysia" <? if ($m_countryname == 'Malaysia') echo ("selected"); ?>>Malaysia (+60)</option>
								<option value="Netherlands" <? if ($m_countryname == 'Netherlands') echo ("selected"); ?>>Netherlands (+31)</option>
								<option value="Poland" <? if ($m_countryname == 'Poland') echo ("selected"); ?>>Poland (+48)</option>
								<option value="Portugal" <? if ($m_countryname == 'Portugal') echo ("selected"); ?>>Portugal (+351)</option>
								<option value="Spain" <? if ($m_countryname == 'Spain') echo ("selected"); ?>>Spain (+34)</option>
								<option value="Sri Lanka" <? if ($m_countryname == 'Sri Lanka') echo ("selected"); ?>>Sri Lanka (+94)</option>
								<option value="Sweden" <? if ($m_countryname == 'Sweden') echo ("selected"); ?>>Sweden (+46)</option>
								<option value="Switzerland" <? if ($m_countryname == 'Switzerland') echo ("selected"); ?>>Switzerland (+41)</option>
								<option value="Taiwan" <? if ($m_countryname == 'Taiwan') echo ("selected"); ?>>Taiwan (+886)</option>
								<option value="Thailand" <? if ($m_countryname == 'Thailand') echo ("selected"); ?>>Thailand (+66)</option>
								<option value="Turkey" <? if ($m_countryname == 'Turkey') echo ("selected"); ?>>Turkey (+90)</option>
							</select>
							<select name="keyfield">
								<option value="m_id" <? if ($keyfield == 'm_id') echo ("selected"); ?>><?=M_ID?></option>
								<option value="m_name" <? if ($keyfield == 'm_name') echo ("selected"); ?>><?=M_NAME?></option>
								<option value="m_handphone" <? if ($keyfield == 'm_handphone') echo ("selected"); ?>><?=M_PHONE?></option>
							</select>
							<input type="text" name="key" value="<?= $key ?>" size="40" maxlength="40" class="adminbttn">

							<input type="button" value="<?=M_SEARCH?>" class="adminbttn" onClick="javascript:go_search()">
							<input type="button" value="Add New Member" class="adminbttn" onClick="javascript:location.href='member_write.php'">
						</td>
					</tr>
				</table>
				<table width="1000" align="center" border='0' cellspacing='0' cellpadding='0'>
					<tr>
						<td colspan=10 height=3 bgcolor='#ffffff'></td>
					</tr>
					<tr align="center" bgcolor='#ffffff' class="list_title">
						<td width="50" height="30"><?=M_NO?></td>
						<td width="100" height="30"><?=M_ID?></td>
						<td width="150" height="30"><?=M_NAME?></td>
						<td width="150" height="30"><?=M_PHONE?></td>
						<td width="150" height="30">Birthday</td>
						<td width="90" height="30"><?=M_COUNTRY_NAME?></td>
						<td width="90" height="30"><?=M_SIGN?></td>
						<td width="90" height="30"><?=M_ADMIN?></td>

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
					$stmt = pdo_excute("select", $query_pdo, 'unknown');
					while ($row = $stmt->fetch()) {

						$id = $row[0];
						$m_id = $row[1];
						$m_name = $row[2];
						$m_handphone = $row[3];
						$m_birthday = $row[4];
						$m_country = $row[5];
                        $m_countryname = $row[6];
						$m_address = $row[7];
						$m_signdate = $row[8];
						$m_remarks = $row[9];
						$m_adminno = $row[10];
						

						$m_signdate = date("Y-m-d", $m_signdate);

						// if (($ii + 1) % 2 == 0) {
						// 	$kk_bgcolor = "#FFFFFF";
						// } else {
						// 	$kk_bgcolor = "#F6F6F6";
						// }
						// if ($m_pre == "1") {
						// 	$m_pre = "O";
						// } else {
						// 	$m_pre = "X";
						// }

						// if ($m_activation_status == "1" || $m_activation_status == "2") {
						// 	$m_activation_status = M_AUTH_YES;
						// } else {
						// 	$m_activation_status = M_AUTH_NO;
						// }

						?>
			  <tr align="center">
			       <td height="30"><?= $id ?></td>
				<td height="30"><a href="member_modify.php?<?= $mode ?>&page=<?= $page ?>&id=<?= $id ?>"><?= $m_id ?>
				</a>
				</td>
				<td height="30" class="notranslate"><a href="member_modify.php?<?= $mode ?>&page=<?= $page ?>&id=<?= $id ?>"><?= $m_name ?></a></td>
				<td><?= $m_handphone ?> </td>
                <td><?= $m_birthday ?> </td>
				<td><?= $m_countryname ?> </td>
				<td height="30"><?= $m_signdate ?></td>
				<td height="30"><?= $m_adminno ?></td>

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


