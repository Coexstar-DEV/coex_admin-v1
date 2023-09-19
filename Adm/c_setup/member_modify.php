<?
session_start();


include "../common/user_function.php";
include "../common/dbconn.php";
include "../common/trading.php";
include_once "../common/". ($_SESSION["language"] == "kr" ? "lang_kr.php" : "lang_en.php");
include "../inc/top_menu.php";
include "../inc/left_menu_op.php";

$LOG_LEVEL = 1;
$LOG_TAG = basename(__FILE__);

$m_market = (is_empty($_REQUEST["m_market"]) ? $DEFINE_DEFAULT_NAME : $_REQUEST["m_market"]);

if (isset($_REQUEST["c_no"])) {
	$c_no = sqlfilter($_REQUEST["c_no"]);
} else {
	$c_no = "";
}
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
if (isset($_REQUEST["real_pass"])) {
	$real_pass = sqlfilter($_REQUEST["real_pass"]);
} else {
	$real_pass = "";
}


$query_pdo = "SELECT B.m_unit as unit, B.m_limit as min_limit,B.m_rank as m_rank, B.m_use as m_use,B.m_suspend_yn as m_suspend_yn,  A.* FROM $table_setup A";
$query_pdo .= " LEFT JOIN m_setup B ON A.c_no=B.m_div";
$query_pdo .= " WHERE B.m_pay = '$m_market' and  A.c_no = $c_no";

$stmt = pdo_excute("mod-select1", $query_pdo, NULL);

$row = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$row) {
	error("QUERY_ERROR");
	exit;
}

$c_no = $row["c_no"];
$c_coin = $row["c_coin"];
$c_wcommission = $row["c_wcommission"];
$c_min_limt = $row["min_limit"];
$c_limit = $row["c_limit"];
$c_limit = ($c_min_limt == "0" || is_empty($c_min_limt) ? $row["c_limit"] : $c_min_limt);
$c_asklimit = $row["c_asklimit"];
$c_unit = $row["unit"];
$c_use = $row["m_use"];
$c_rank = $row["m_rank"];
$c_img = $row["c_img"];
$c_title = $row["c_title"];
$c_signdate = $row["c_signdate"];
$c_fees = $row["c_fees"];
$c_type = $row["c_type"];
$c_since = $row["c_since"];
$c_quantity = $row["c_quantity"];
$c_site = $row["c_site"];
$c_wpaper = $row["c_wpaper"];
$c_exp1 = $row["c_exp1"];
$c_exp2 = $row["c_exp2"];
$c_details = $row['c_details'];
$c_introduce = $row["c_introduce"];
$m_suspend_yn = $row["m_suspend_yn"];
$c_suspend_yn = $row["c_suspend_yn"];


$c_suspend_reason = $row["c_suspend_reason"];
$c_limit_in = $row["c_limit_in"];
$c_limit_out = $row["c_limit_out"];



?>

<script language="javascript">
	<!--
	function go_modify() {      
		document.form.action="member_modify_ok.php";
		document.form.submit();
	}

	function go_list() {
		document.form.action="member.php";
		document.form.submit();
	}
	</script> 
 
				<table width="700" border="0" cellspacing="0" cellpadding="0">
					<tr><td height=30></td></tr>
					<tr><td>
							<table border=0 cellpadding=0 cellspacing=0>
								
							</table>
					</td></tr>
					<tr><td height=3></td></tr>
					<tr>
						<td>
							<table width="600" border='0' cellspacing='0' cellpadding='0'>
							<form name="form" method="post"  enctype = "multipart/form-data">
							<tr>
									<td colspan=2><img src="../image/icon2.gif" width=45 height=35 border=0> <b><?= $c_coin ?>/<?= $m_market ?> <?=M_EXCHANGE.M_SETTING?></b></td>
								</tr>
								<tr><td colspan=4 height=2 bgcolor='#88B7DA'></td></tr>
								<tr><td colspan=4 height=5></td></tr>
								<tr>
									<td width=115 height="30"> 
										<div align="center"><font size="2" face="돋움"><?=M_COIN.M_DIVISION?></font></div>
									</td>
									<td width=479 height="30" colspan="3" align="left"><font size="2" face="돋움"> 
										&nbsp;
										<input type="text" class="adminbttn" name="c_coin" size="30" value="<?= $c_coin ?>">
										</font>
									</td>
								</tr>
								<tr><td colspan=4 height=1 bgcolor='#D2DEE8'></td></tr>
								<tr> 			
									<td width=115 height="30"> 
										<div align="center"><font size="2" face="돋움"><?=M_ORDER.M_FEE?></font></div>
									</td>
									<td width=479 height="30" colspan="3" align="left"><font size="2" face="돋움"> 
										&nbsp;
										<input type="text" maxlength=30 name="c_wcommission" value="<?= $c_wcommission ?>"  size=30 class="adminbttn">
										</font>
									</td>
								</tr>
								<tr><td colspan=4 height=1 bgcolor='#D2DEE8'></td></tr>
								<tr> 					
									<td width=115 height="30"> 
										<div align="center"><font size="2" face="돋움"><?=M_MIN.M_ORDER.M_AMOUNT?></font></div>
									</td>
									<td width=479 height="30" colspan="3" align="left"><font size="2" face="돋움"> 
										&nbsp;
										<input type="text" maxlength=30 name="c_limit" value="<?= $c_limit ?>"  size=30 class="adminbttn">
										</font>
									</td>
								</tr>
								<tr><td colspan=4 height=1 bgcolor='#D2DEE8'></td></tr>
								<tr> 	
									<td width=115 height="30"> 
										<div align="center"><font size="2" face="돋움"><?=M_MAX.M_ORDER.M_AMOUNT?></font></div>
									</td>
									<td width=479 height="30" colspan="3" align="left"><font size="2" face="돋움"> 
										&nbsp;
										<input type="text" maxlength=30 name="c_asklimit" value="<?= $c_asklimit ?>"  size=30 class="adminbttn">
										</font>
									</td>
								</tr>
								<tr><td colspan=4 height=1 bgcolor='#D2DEE8'></td></tr>
								<tr> 	
									<td width=115 height="30"> 
										<div align="center"><font size="2" face="돋움"><?=M_ASKPRICE?></font></div>
									</td>
									<td width=479 height="30" colspan="3" align="left"><font size="2" face="돋움"> 
										&nbsp;
										<input type="text" maxlength=30 name="c_unit" value="<?= $c_unit ?>"  size=30 class="adminbttn">
										</font>
									</td>
								</tr>
								<tr><td colspan=4 height=1 bgcolor='#D2DEE8'></td></tr>
								<tr> 	
									<td width=115 height="30"> 
										<div align="center"><font size="2" face="돋움"><?=M_RANK?></font></div>
									</td>
									<td width=479 height="30" colspan="3" align="left"><font size="2" face="돋움"> 
										&nbsp;
										<input type="text" maxlength=30 name="c_rank" value="<?= $c_rank ?>"  size=30 class="adminbttn">
										</font>
									</td>
								</tr>
								<tr><td colspan=4 height=1 bgcolor='#D2DEE8'></td></tr>
								<tr> 	
									<td width=115 height="30"> 
										<div align="center"><font size="2" face="돋움"><?=M_COIN.M_NAME?></font></div>
									</td>
									<td width=479 height="30" colspan="3" align="left"><font size="2" face="돋움"> 
										&nbsp;
										<input type="text" maxlength=30 name="c_title" value="<?= $c_title ?>"  size=30 class="adminbttn">
										</font>
									</td>
								</tr>
								<tr><td colspan=4 height=1 bgcolor='#D2DEE8'></td></tr>
								<tr> 	
									<td width=115 height="30"> 
										<div align="center"><font size="2" face="돋움"><?=M_WITHDRAW.M_FEE?></font></div>
									</td>
									<td width=479 height="30" colspan="3" align="left"><font size="2" face="돋움"> 
										&nbsp;
										<input type="text" maxlength=30 name="c_fees" value="<?= $c_fees ?>"  size=30 class="adminbttn">
										</font>
									</td>
								</tr>
								<tr><td colspan=4 height=1 bgcolor='#D2DEE8'></td></tr>
								<tr> 	
									<td width=115 height="30"> 
										<div align="center"><font size="2" face="돋움"><?=M_DEPOSIT.M_MIN.M_AMOUNT?></font></div>
									</td>
									<td width=479 height="30" colspan="3" align="left"><font size="2" face="돋움"> 
										&nbsp;
										<input type="text" maxlength=30 name="c_limit_in" value="<?= $c_limit_in ?>"  size=30 class="adminbttn">
										</font>
									</td>
								</tr>
								<tr><td colspan=4 height=1 bgcolor='#D2DEE8'></td></tr>
								<tr> 	
									<td width=115 height="30"> 
										<div align="center"><font size="2" face="돋움"><?=M_WITHDRAW.M_MIN.M_AMOUNT?></font></div>
									</td>
									<td width=479 height="30" colspan="3" align="left"><font size="2" face="돋움"> 
										&nbsp;
										<input type="text" maxlength=30 name="c_limit_out" value="<?= $c_limit_out ?>"  size=30 class="adminbttn">
										</font>
									</td>
								</tr>
								<tr><td colspan=4 height=1 bgcolor='#D2DEE8'></td></tr>
								<tr> 	
									<td width=115 height="30"> 
										<div align="center"><font size="2" face="돋움"><?=M_ISSUE.M_YEAR?></font></div>
									</td>
									<td width=479 height="30" colspan="3" align="left"><font size="2" face="돋움"> 
										&nbsp;
										<input type="text" maxlength=30 name="c_since" value="<?= $c_since ?>"  size=30 class="adminbttn">
										</font>
									</td>
								</tr>
								<tr><td colspan=4 height=1 bgcolor='#D2DEE8'></td></tr>
								<tr> 	
									<td width=115 height="30"> 
										<div align="center"><font size="2" face="돋움"><?=M_TOTAL.M_ISSUE.M_AMOUNT?></font></div>
									</td>
									<td width=479 height="30" colspan="3" align="left"><font size="2" face="돋움"> 
										&nbsp;
										<input type="text" maxlength=30 name="c_quantity" value="<?= $c_quantity ?>"  size=30 class="adminbttn">
										</font>
									</td>
								</tr>
								<tr><td colspan=4 height=1 bgcolor='#D2DEE8'></td></tr>
								<tr> 	
									<td width=115 height="30"> 
										<div align="center"><font size="2" face="돋움"><?=M_SITE?></font></div>
									</td>
									<td width=479 height="30" colspan="3" align="left"><font size="2" face="돋움"> 
										&nbsp;
										<textarea name="c_site" rows=5 class="adminbttn" style="margin-top:3px;resize:none;width:50%;"><?= $c_site ?></textarea>
										</font>
									</td>
								</tr>
								<tr><td colspan=4 height=1 bgcolor='#D2DEE8'></td></tr>
								<tr> 	
									<td width=115 height="30"> 
										<div align="center"><font size="2" face="돋움"><?=M_WHITEPAPTER?></font></div>
									</td>
									<td width=479 height="30" colspan="3" align="left"><font size="2" face="돋움"> 
										&nbsp;
										<textarea name="c_wpaper" rows=5 class="adminbttn" style="margin-top:3px;resize:none;width:50%;"><?= $c_wpaper ?></textarea>
										</font>
									</td>
								</tr>
								<tr><td colspan=4 height=1 bgcolor='#D2DEE8'></td></tr>
								<tr> 	
									<td width=115 height="30"> 
										<div align="center"><font size="2" face="돋움">Explorer 1</font></div>
									</td>
									<td width=479 height="30" colspan="3" align="left"><font size="2" face="돋움"> 
										&nbsp;
										<textarea name="c_exp1" rows=5 class="adminbttn" style="margin-top:3px;resize:none;width:50%;"><?= $c_exp1 ?></textarea>
										</font>
									</td>
								</tr>
														
								<tr><td colspan=4 height=1 bgcolor='#D2DEE8'></td></tr>
								<tr> 	
									<td width=115 height="30"> 
										<div align="center"><font size="2" face="돋움"><?=M_INTRO?></font></div>
									</td>
									<td width=479 height="30" colspan="3" align="left"><font size="2" face="돋움"> 
										&nbsp;
										<textarea name="c_introduce" rows=5 class="adminbttn" style="margin-top:3px;resize:none;width:50%;"><?= $c_introduce ?></textarea>
										</font>
									</td>
								</tr>
								<tr><td colspan=4 height=1 bgcolor='#D2DEE8'></td></tr>
								<tr> 	
									<td width=115 height="30"> 
										<div align="center"><font size="2" face="돋움">Details</font></div>
									</td>
									<td width=479 height="30" colspan="3" align="left"><font size="2" face="돋움"> 
										&nbsp;
										<textarea name="c_details" rows=5 class="adminbttn" style="margin-top:3px;resize:none;width:50%;"><?= $c_details ?></textarea>
										</font>
									</td>
								</tr>
								<tr><td colspan=4 height=1 bgcolor='#D2DEE8'></td></tr>
								<tr> 	
									<td width=115 height="30"> 
										<div align="center"><font size="2" face="돋움"><?=M_TRADE_STATUS?></font></div>
									</td>
									<td width=479 height="30" colspan="3" align="left"><font size="2" face="돋움"> 
										&nbsp;
										<input type="radio" name="m_suspend_yn" value="0" <? if ($m_suspend_yn == "0" || $m_suspend_yn == "1") { ?>checked<? } ?>><?=M_NORMAL?></input>
										<input type="radio" name="m_suspend_yn" value="2" <? if ($m_suspend_yn == "2" || $m_suspend_yn == "3") { ?>checked<? } ?>><?=M_TRADING.M_STOP?></input>
										</font>
									</td>
								</tr>
								<tr><td colspan=4 height=1 bgcolor='#D2DEE8'></td></tr>
								<tr> 	
									<td width=115 height="30"> 
										<div align="center"><font size="2" face="돋움"><?=M_FUND_STATUS?></font></div>
									</td>
									<td width=479 height="30" colspan="3" align="left"><font size="2" face="돋움"> 
										&nbsp;
										<input type="radio" name="c_suspend_yn" value="0" <? if ($c_suspend_yn == "0" || $c_suspend_yn == "2") { ?>checked<? } ?>><?=M_NORMAL?></input>
										<input type="radio" name="c_suspend_yn" value="1" <? if ($c_suspend_yn == "1" || $c_suspend_yn == "3") { ?>checked<? } ?>><?=M_DEPWITH.M_STOP?></input>
										</font>
									</td>
								</tr>
								<tr><td colspan=4 height=1 bgcolor='#D2DEE8'></td></tr>
								<tr> 	
									<td width=115 height="30"> 
										<div align="center"><font size="2" face="돋움"><?=M_REASON?></font></div>
									</td>
									<td width=479 height="30" colspan="3" align="left"><font size="2" face="돋움"> 
										&nbsp;
										<input type="text" maxlength=30 name="c_suspend_reason" value="<?= $c_suspend_reason ?>"  size=30 class="adminbttn">
										</font>
									</td>
								</tr>
								<tr><td colspan=4 height=1 bgcolor='#D2DEE8'></td></tr>
								<tr> 	
									<td width=115 height="30"> 
										<div align="center"><font size="2" face="돋움"><?=M_APPLY?></font></div>
									</td>
									<td width=479 height="30" colspan="3" align="left"><font size="2" face="돋움"> 
										&nbsp;
										<input type="radio" name="c_use" value="0" <? if ($c_use == "0") { ?>checked<? } ?>><?=M_APPLY_NO?></input>
										<input type="radio" name="c_use" value="1" <? if ($c_use == "1") { ?>checked<? } ?>><?=M_APPLY_YES?></input>
										</font>
									</td>
								</tr>
								<tr><td colspan=4 height=1 bgcolor='#D2DEE8'></td></tr>
								<tr> 	
									<td width=115 height="30">
										<div align="center"><font size="2" face="돋움"><?=M_COIN.M_TYPE?></font></div> 
 									</td>
									<td width=479 height="30" colspan="3" align="left"><font size="2" face="돋움">
 										&nbsp;
										<input type="radio" name="c_type" value="0" <? if ($c_type == "0") { ?>checked<? } ?>><?=M_ALTCOIN?></input> 
										<input type="radio" name="c_type" value="1" <? if ($c_type == "1") { ?>checked<? } ?>><?=M_ETH?></input> 
										<input type="radio" name="c_type" value="2" <? if ($c_type == "2") { ?>checked<? } ?>><?=M_TOKEN?></input> 
										<input type="radio" name="c_type" value="3" <? if ($c_type == "3") { ?>checked<? } ?>><?=M_RIPPLE?></input> 
										<input type="radio" name="c_type" value="4" <? if ($c_type == "4") { ?>checked<? } ?>><?=M_EOS?></input> 
										<input type="radio" name="c_type" value="5" <? if ($c_type == "5") { ?>checked<? } ?>><?=M_TRON?></input> 
										<input type="radio" name="c_type" value="6" <? if ($c_type == "6") { ?>checked<? } ?>><?=M_ALTCOIN2?></input> 
										<input type="radio" name="c_type" value="7" <? if ($c_type == "7") { ?>checked<? } ?>><?="CELO"?></input> 
										</font>
									</td>
								</tr> 
							<tr><td colspan=4 height=1 bgcolor='#D2DEE8'></td></tr> 
							</table>
						</td>
					</tr>
					<input type="hidden" name="old_c_img" value="<?= $c_img ?>">
					<input type="hidden" name="c_img" value="<?= $c_img ?>">
					<input type="hidden" name="c_no" value="<?= $c_no ?>">
					<input type="hidden" name="m_market" value="<?= $m_market ?>">
					<input type="hidden" name="real_pass" value="<?= $real_pass ?>">
					<input type="hidden" name="keyfield" value="<?= $keyfield ?>">
					<input type="hidden" name="key" value="<?= $key ?>">
					<input type="hidden" name="page" value="<?= $page ?>">
					</form>
				</table> 
				<table width="600" border="0" cellspacing="0" cellpadding="4" class="left_margin30">
					<tr><td height="30"></td></tr>
					<tr> 
						<td height="20" align="center"> 
							<input type="button" value="<?=M_MODIFICATION?>" class="adminbttn" onClick="javascript:go_modify()">
							<input type="button" value="<?=M_BACK?>" class="adminbttn" onClick="javascript:go_list()">
                			<input type="button" value="<?=M_DEL?>" class="adminbttn" onClick="javascript:location.href='member_del.php?c_no=<?= $c_no ?>'">
						</td>
					</tr>
				</table>
				<br><br>
							<BR><BR> 

<? include "../inc/down_menu.php"; ?>