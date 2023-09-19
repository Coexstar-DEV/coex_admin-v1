<?
#####################################################################

session_start();

include "../common/user_function.php";
include "../common/dbconn.php";
include_once "../common/". ($_SESSION["language"] == "kr" ? "lang_kr.php" : "lang_en.php");
include "../inc/top_menu.php";
include "../inc/left_menu_inout.php";
include "../inc/adm_chk.php";
if (isset($_SESSION["admin_id"])) {
	$admin_id = $_SESSION["admin_id"];
} else {
	$admin_id = "";
}
$adminlevel = $_SESSION["level"];
//k_no,k_orderprice,k_depositprice,k_returnprice,k_depositname,k_payment,k_check,k_signdate,k_userno,k_id

$ydate1 = (isset($_REQUEST["ydate1"]) ? $_REQUEST["ydate1"] : date('Y'));
$mdate1 = (isset($_REQUEST["mdate1"]) ? $_REQUEST["mdate1"] : date('m'));
$ddate1 = (isset($_REQUEST["ddate1"]) ? $_REQUEST["ddate1"] : date('d') - 2);

$ydate2 = (isset($_REQUEST["ydate2"]) ? $_REQUEST["ydate2"] : date('Y'));
$mdate2 = (isset($_REQUEST["mdate2"]) ? $_REQUEST["mdate2"] : date('m'));
$ddate2 = (isset($_REQUEST["ddate2"]) ? $_REQUEST["ddate2"] : date('d'));


$wdate1 = mktime(0,0,0,$mdate1,$ddate1,$ydate1);
$wdate2 = mktime(23,59,59,$mdate2,$ddate2,$ydate2);

$opt_check0 = (isset($_REQUEST["opt_check0"]) ? $_REQUEST["opt_check0"] : "");
$opt_check1 = (isset($_REQUEST["opt_check1"]) ? $_REQUEST["opt_check1"] : "");



if($mdate1!='' || $ddate1!='' || $ydate1!=''){
	$where_date1 = " where t_signdate > '$wdate1'";
}else{
	$where_date1 = "";
}

if($mdate2!='' || $ddate2!='' || $ydate2!=''){
	if($where_date1==''){
		$where_date2 = " where t_signdate < '$wdate2'";
	}else{
		$where_date2 = " and t_signdate < '$wdate2'";
	}
}else{
	$where_date2 = "";
}

$key = (isset($_REQUEST["key"]) ? $_REQUEST["key"] : "");
$encoded_key = urlencode($key);

$keyfield = (isset($_REQUEST["keyfield"]) ? $_REQUEST["keyfield"] : "");


$query_pdo = "SELECT k_no,k_orderprice,k_depositprice,k_returnprice,k_depositname,k_payment,k_check,k_signdate,k_userno,k_id,k_ordermemo,k_delete FROM $table_k_deposit where k_signdate > ? and k_signdate < ? ";
$pdo_in = [$wdate1,$wdate2];

if ($opt_check0 != $opt_check1) {
	$check = is_empty($opt_check0) ?  "1" : "0";
	$query_pdo .= " and k_check=$check";
} 

if($key!=""){
	$query_pdo .=" and $keyfield LIKE '%$key%' ";
}

$query_pdo .=" ORDER BY k_signdate DESC";	

$total_record_pdo = pdo_excute_count("selcount", $query_pdo, $pdo_in);

$page = (isset($_REQUEST["page"]) ? $_REQUEST["page"] : 1);
$num_per_page = 10;
$page_per_block = 10;

if(!$total_record_pdo) {
 	$first = 1;
 	$last = 0;   
} else {
 	$first = $num_per_page*($page-1);
 	$last = $num_per_page*$page;
 
 	$IsNext = $total_record_pdo - $last;
 	if($IsNext > 0) {
 		$last -= 1;
 	} else {
 		$last = $total_record_pdo - 1;
 	}      
} 
$total_page = ceil($total_record_pdo/$num_per_page);
$article_num = $total_record_pdo - $num_per_page*($page-1);
$mode="keyfield=$keyfield&key=$encoded_key&k_checkk=$k_checkk&ddate1=$ddate1&mdate1=$mdate1&ydate1=$ydate1&ddate2=$ddate2&mdate2=$mdate2&ydate2=$ydate2&opt_check0=$opt_check0&opt_check1=$opt_check1";
#####################################################################
?>

<script language="javascript">
<!--
function go_del(k_no,k_checkk) {
	ans = confirm('<?=M_CONFIRM_MSG1?>');
	if (ans == true ) {
		document.form.action="member_del.php?k_no="+k_no+"&k_checkk="+k_checkk;
		document.form.submit();
	}	
}

function go_status(kk) {
	ans = confirm('<?=M_CONFIRM_MSG2?>');
	if (ans == true ) {
		document.form.action=kk;
		document.form.submit();
	}	
}

function go_search() {
	document.form.action="member.php?dis=<?=$dis?>";
	document.form.submit();
}

function go_mail(tmp_mail) {
	document.location = "mailing.php?to_name=" + tmp_mail;
}
//-->
</script>
				<table width="1200" border="0" cellspacing="0" cellpadding="0" class="left_margin30">
				
					<tr><td height=30></td></tr>
					<tr><td>
					<table width="100%" border=0 cellpadding=0 cellspacing=0>
								<tr>
									<td class='td14' align="center"><?=$DEFINE_DEFAULT_NAME?> <?=M_DEPOSIT.M_HIS?></td>
								</tr>
								<?php if(check_manager_level2($adminlevel, ADMIN_LVL4) || $admin_id == 'LMAlcaraz1993' ){ ?>
								<tr>
									<td align="right"><form name=dform action="./member_dis_excel.php" method=post target="_blank">
										<input type="hidden" name="wdate1" value="<?=$wdate1?>">
										<input type="hidden" name="wdate2" value="<?=$wdate2?>">
										<input type="hidden" name="k_checkk" value="<?=$k_checkk?>" >
										<input type="hidden" name="level_l" value="<?=$level_l?>">
										<? $file_name=mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"));?>
										<input type="hidden" name ="file_name" value="<?=$file_name?>">
										<input type="hidden" name ="dis" value="<?=$dis?>">
										<input type="hidden" name ="member_count" value="<?=$member_count?>">
										<input type="submit" value="<?=@$level_l?> <?=M_EXELDOWN?>" class="exBt">
									</form></td>
								</tr>
								<?php } else { ?>
								&nbsp;
								<?php } ?>
							</table>
					</td></tr>
					<form name="form" method="post">
					<tr><td height=3></td></tr>
					<tr>
						<td>							 
								<table style="width:100%;" border="0" cellspacing="0" cellpadding="4">
								<tr> 
								<td>
									<select name="ydate1" class="formbox3">
									<? for($i=2013;$i<=Date("Y")+2;$i++){?>
									<option value="<?=$i?>" <?if($ydate1==$i){?>selected<?}?>><?=$i?></option>
									<?}?>
									</select><span class="text2"><?=M_YEAR?></span>&nbsp;

									<select name="mdate1" class="formbox3">
									<?for($i=1;$i<13;$i++){?>
									<option value="<?=$i?>" <?if($mdate1==$i){?>selected<?}?>><?=$i?></option>
									<?}?>
									</select><span class="text2"><?=M_MONTH?></span>&nbsp;

									<select name="ddate1" class="formbox3">
									<?for($i=1;$i<32;$i++){?>
									<option value="<?=$i?>" <?if($ddate1==$i){?>selected<?}?>><?=$i?></option>
									<?}?>
									</select><span class="text2"><?=M_DAY?></span>&nbsp;
									~
									<select name="ydate2" class="formbox3">
									<? for($i=2013;$i<=Date("Y")+2;$i++){?>
									<option value="<?=$i?>" <?if($ydate2==$i){?>selected<?}?>><?=$i?></option>
									<?}?>
									</select><span class="text2"><?=M_YEAR?></span>&nbsp;

									<select name="mdate2" class="formbox3">
									<?for($i=1;$i<13;$i++){?>
									<option value="<?=$i?>" <?if($mdate2==$i){?>selected<?}?>><?=$i?></option>
									<?}?>
									</select><span class="text2"><?=M_MONTH?></span>&nbsp;

									<select name="ddate2" class="formbox3">
									<?for($i=1;$i<32;$i++){?>
									<option value="<?=$i?>" <?if($ddate2==$i){?>selected<?}?>><?=$i?></option>
									<?}?>
									</select><span class="text2"><?=M_DAY?></span>&nbsp;

								</td>										

								<td height="20" align="left"> 
									<input type="checkbox" name="opt_check1" value="1" <?=( strpos($opt_check1, '1') !== false  ? "checked": "")?> onchange=go_search();><?=M_CONFIRM?>
									<input type="checkbox" name="opt_check0" value="1" <?=( strpos($opt_check0, '1') !== false  ? "checked": "")?> onchange=go_search();><?=M_NOT_CONFIRM?>
								</td>

								<td height="20" colspan="4" align="left"> 
									
									&nbsp;&nbsp;
									<select name="keyfield">
									<option value="k_id" <?if ($keyfield=='k_id') echo("selected");?>><?=M_ID?></option>
									<option value="k_depositname" <?if ($keyfield=='k_depositname') echo("selected");?>><?=M_DEPOSITOR?></option>
									<option value="k_payment" <?if ($keyfield=='k_payment') echo("selected");?>><?=M_PAY?></option>
									</select>
									<input type="text" name="key" value="<?=$key?>"size="10" maxlength="40" class="adminbttn">
<!-- 											<input type="hidden" name="wdate1" value="<?=$wdate1?>"> -->
<!-- 											<input type="hidden" name="wdate2" value="<?=$wdate2?>"> -->
									<input type="hidden" name="k_checkk" value="<?=$k_checkk?>" >											
									<input type="button"  value="<?=M_SEARCH?>" class="adminbttn" onClick="javascript:go_search()">
<!-- 											<input type="button"  value="원화입금" class="adminbttn" onClick="javascript:location.href='member_write.php'"> -->
								</td>
								</tr>
								</table>
								<table width="1200" border='0' cellspacing='0' cellpadding='0'>
									<tr><td colspan=12 height=3 bgcolor='#ffffff'></td></tr>
									<tr align="center" bgcolor='#ffffff' class="list_title"> 
										<td width="50" height="30"><?=M_NO?></td>
										<td width="100" height="30"><?=M_ID?>(<?=M_NO?>)</td>
										<td width="100" height="30"><?=M_ORDER.M_PRICE?></td>
										<td width="100" height="30"><?=M_DEPOSIT.M_PRICE?></td>
										<td width="150" height="30"><?=M_REFUND.M_PRICE?></td>
										<td width="120" height="30"><?=M_DEPOSITOR?></td>
										<td width="120" height="30"><?=M_PAY?></td>
										<td width="90" height="30"><?=M_ORDER.M_CONFIRM?></td>
										<td width="90" height="30"><?=M_ORDER.M_DATE?></td>
										<td width="90" height="30"><?=M_DEL?></td>										

									</tr>
									
									<tr><td colspan=12 height=2 bgcolor='#D2DEE8'></td></tr>
									
<?
#####################################################################

$ii = 0;
$query_pdo = convert_page_query($query_pdo, $num_per_page, $page);
$stmt = pdo_excute("select", $query_pdo, $pdo_in);
while ($row = $stmt->fetch()) {

	$k_no= $row[0];
	$k_orderprice= $row[1];
	$k_depositprice= $row[2];
	$k_returnprice= $row[3];
	$k_depositname= $row[4];
	$k_payment= $row[5];
	$k_check= $row[6];
	$k_signdate= $row[7];
	$k_userno= $row[8];
	$k_id= $row[9];
	$k_ordermemo= $row[10];
	$k_delete= $row[11];

	 


	if($k_check =="0"){
		$k_check = "X";
	}else{
		$k_check ="Y";
	}
	$k_signdate = date("Y-m-d H:i:s",$k_signdate);


	if($k_payment =="1"){
		$k_payment = "카드";
	}else if($k_payment =="2"){
		$k_payment ="모바일";
	}else if($k_payment =="3"){
		$k_payment ="가상계좌";
	}else if($k_payment =="0"){
		$k_payment ="무통장";
		if ($k_ordermemo == "SMS") {
			$k_payment ="SMS";
		}
	}else{
		// Pending. 
	}

	$k_state = "대기";
	if($k_check =="Y"){
		$kk_bgcolor="#FFFFFF";
		$k_state = "지급완료";
	}else{
		$kk_bgcolor="#C6C6C6";
		if ($k_delete != "1" && $k_ordermemo == "SMS") {
			$kk_bgcolor="#A3E58A";
		}
	}


	if ($k_delete == "1") {
		$k_state = "삭제완료";
	}

	

#####################################################################
?>

								<tr align="center" bgcolor="<?=$kk_bgcolor?>"> 
									<td height="30"><?=$k_no?></td>
									<td height="30"><a href="member_modify.php?<?=$mode?>&page=<?=$page?>&k_no=<?=$k_no?>"><?=$k_id?>(<?=$k_userno?>)</td></a>
									<td height="30"><a href="member_modify.php?<?=$mode?>&page=<?=$page?>&k_no=<?=$k_no?>"><?=number_format($k_orderprice)?></td>
									<td height="30"><a href="member_modify.php?<?=$mode?>&page=<?=$page?>&k_no=<?=$k_no?>"><B><?=number_format($k_depositprice)?></B> 
										</a>
									</td>
									<td height="30" align="center"><?=$k_returnprice?></td>
									<td height="30" align="center"><?=$k_depositname?></td>
									<td height="30"><?=$k_payment?></td> 
									<td height="30"><?=$k_check?></td>
									<td height="30"><?=$k_signdate?></td>
									<td height="30"><?=$k_state?></td>
								</tr>
								<tr><td colspan=12 height=1 bgcolor='#D2DEE8'></td></tr>

<?		
   $article_num--;
   $ii++;      
}              
$chk_num = $last-$first+1;
?>
							</table>
						</td>
					</tr>
				</table> 
				<table width="1000" border="0" cellspacing="0" cellpadding="4" class="left_margin30">
					<tr> 
						<td height="20" align="center"><font color="#666666">
<?
#####################################################################
$total_block = ceil($total_page/$page_per_block);
$block = ceil($page/$page_per_block);
$first_page = ($block-1)*$page_per_block;
$last_page = $block*$page_per_block;
if($total_block <= $block) {
	$last_page = $total_page;
}

if($page!='1'){
	echo "<a href=\"member.php?$mode&page=1\" onMouseOver=\"status='';return true;\" onMouseOut=\"status=''\"><font color=\"#666666\">".M_FIRST."</a>&nbsp;";
}
 if ($page > 1) {
 	$page_num = $page - 1;
 	echo "<a href=\"member.php?$mode&page=$page_num\" onMouseOver=\"status='".M_PREVPAGE."';return true;\" onMouseOut=\"status=''\"><font color=\"#666666\">◀</font></a>&nbsp;";
 }
 
 for($direct_page = $first_page+1; $direct_page <= $last_page; $direct_page++) {
 	if($page == $direct_page) {
 		echo "<font color=\"#666666\">&nbsp;<b>$direct_page</b></font>&nbsp;";
 	} else {
 		echo "&nbsp;<a href=\"member.php?$mode&page=$direct_page\" onMouseOver=\"status='go to page $direct_page';return true;\" onMouseOut=\"status=''\"><font color=\"#666666\">$direct_page</font></a>&nbsp;";
 	}
 }
 
if(isset($_REQUEST["IsNext"])){
$IsNext = $_REQUEST["IsNext"];
}else{
$IsNext=0;
}

 if ($IsNext > 0) {
 	$page_num = $page + 1;
 	echo "&nbsp;<a href=\"member.php?$mode&page=$page_num\" onMouseOver=\"status='".M_NEXTPAGE."';return true;\" onMouseOut=\"status=''\"><font color=\"#666666\">▶</font></a>&nbsp;";
 }
if($page!=$total_page){
	echo "<a href=\"member.php?$mode&page=$total_page\" onMouseOver=\"status='';return true;\" onMouseOut=\"status=''\"><font color=\"#666666\">".M_LAST."</a>";
}
 ?>
							</font> 
						</td>
					</tr>
					<input type="hidden" name="chk_num" value="<?echo($chk_num)?>">  
					</form>  
				</table>
				<br><br>
<? include "../inc/down_menu.php"; ?>