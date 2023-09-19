<?
session_start();

include "../common/user_function.php";
include "../common/dbconn.php";
include_once "../common/". ($_SESSION["language"] == "kr" ? "lang_kr.php" : "lang_en.php");
include "../inc/top_menu.php";
include "../inc/left_menu_watchlist.php";
?>

<script language="javascript">
	function go_modify() {


		if (!document.form.m_name.value) {
			alert('<?=M_INPUT_NAME?>');
			document.form.name.focus();
			return;
		}

		document.form.action = "member_ok.php";
		document.form.submit();
	}
</script>
<table width=700" border="0" cellspacing="0" cellpadding="0" >


	<tr>
		<td>
			<table width="900" border='0' cellspacing='0' cellpadding='0' style="padding-top:5%">
				<form name="form" method="post">
				<tr>
						<td colspan=2> <h2><b>Add Watchlist</b></h2></td>
					</tr>
					<tr>
						<td colspan=4 height=2 bgcolor='#ffd600'></td>
					</tr>
					<tr>
						<td colspan=4 height=5></td>
					</tr>

					<tr>
						<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
					</tr>
					<tr>
						<td width=115 height="30">
							<div>
								<font size="2" face="돋움"><?=M_NAME?></font>
							</div>
						</td>
						<td width=479 height="30" colspan="3" align="left">
							<font size="2" face="돋움">
								&nbsp;
								<input name="m_name" size="25" value="" class=
								"adminbttn"></font>
						</td>
					</tr>
					<tr>
						<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
					</tr>
					<tr>
						<td width=115 height="30">
							<div>
								<font size="2" face="돋움">Alias</font>
							</div>
						</td>
						<td width=479 height="30" colspan="3" align="left">
							<font size="2" face="돋움">
								&nbsp;
								<input name="m_alias" size="25" value="" class=
								"adminbttn"></font>
						</td>
					</tr>					
					<tr>
						<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
					</tr>

					<tr>
						<td width=105 height="30">
							<div >
								<font size="2" face="돋움"><?=M_MAIL?></font>
							</div>
						</td>
						<td height="30" colspan="3" align="left">
							<font size="2" face="돋움">&nbsp;
								<input maxlength=50 name="m_id" value="" size="25" class="adminbttn">
							</font>
						</td>
					</tr>
					<tr>
						<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
					</tr>
					<tr>
						<td width=105 height="30">
							<div >
								<font size="2" face="돋움"><?=M_PHONE?></font>
							</div>
						</td>
						<td height="30" colspan="3" align="left">
							<font size="2" face="돋움">&nbsp;
								<input maxlength=50 name="m_handphone" value="" size="25" class="adminbttn">
							</font>
						</td>
					</tr>
					<tr>
						<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
					</tr>
					<tr>
						<td width=105 height="30">
							<div >
								<font size="2" face="돋움"><?=M_COUNTRY?></font>
							</div>
						</td>
						<td height="30" colspan="3"  align="left">
						<font size="2" face="돋움">&nbsp;
							<select name="m_country" >
							<option value="unknown@@unknown" <? if ($m_country == "unknown" || $m_country == "unknown") { ?>selected<? } ?>>Unknown</option>
							<option value="+82@@Korea" <? if ($m_country == "+82" || $m_country == "82") { ?>selected<? } ?>>Korea (+82)</option>
									<option value="+65@@Singapore" <? if ($m_country == "+65" || $m_country == "65") { ?>selected<? } ?>>Singapore (+65)</option>
									<option value="+86@@China" <? if ($m_country == "+86" || $m_country == "86") { ?>selected<? } ?>>China (+86)</option>
									<option value="+81@@Japan" <? if ($m_country == "+81" || $m_country == "81") { ?>selected<? } ?>>Japan (+81)</option>
									<option value="+84@@Vietnam" <? if ($m_country == "+84" || $m_country == "84") { ?>selected<? } ?>>Vietnam (+84)</option>
									<option value="+852@@Hongkong" <? if ($m_country == "+852" || $m_country == "852") { ?>selected<? } ?>>Hongkong (+852)</option>
									<option value="+62@@Indonesia" <? if ($m_country == "+62" || $m_country == "62") { ?>selected<? } ?>>Indonesia (+62)</option>
									<option value="+63@@Philippines" <? if ($m_country == "+63" || $m_country == "63") { ?>selected<? } ?>>Philippines (+63)</option>
									<option value="+91@@India" <? if ($m_country == "+91" || $m_country == "91") { ?>selected<? } ?>>India (+91)</option>
									<option value="+1@@Usa" <? if (($m_country == "+1" || $m_country == "1") && strtoupper($m_countryname) == "USA") { ?>selected<? } ?>>USA (+1)</option>
									<option value="+1@@Canada" <? if (($m_country == "+1" || $m_country == "1") && $m_countryname == "Canada") { ?>selected<? } ?>>Canada (+1)</option>
									<option value="+93@@Afghanistan" <? if ($m_country == "+93" || $m_country == "93") { ?>selected<? } ?>>Afghanistan (+93)</option>
									<option value="+61@@Australia" <? if ($m_country == "+61" || $m_country == "61") { ?>selected<? } ?>>Australia (+61)</option>
									<option value="+43@@Austria" <? if ($m_country == "+43" || $m_country == "43") { ?>selected<? } ?>>Austria (+43)</option>
									<option value="+880@@Bangladesh" <? if ($m_country == "+880" || $m_country == "880") { ?>selected<? } ?>>Bangladesh (+880)</option>
									<option value="+55@@Brazil" <? if ($m_country == "+55" || $m_country == "55") { ?>selected<? } ?>>Brazil (+55)</option>
									<option value="+855@@Cambodia" <? if ($m_country == "+855" || $m_country == "855") { ?>selected<? } ?>>Cambodia (+855)</option>
									<option value="+358@@Finland" <? if ($m_country == "+358" || $m_country == "358") { ?>selected<? } ?>>Finland (+358)</option>
									<option value="+33@@France" <? if ($m_country == "+33" || $m_country == "33") { ?>selected<? } ?>>France (+33)</option>
									<option value="+49@@Germany" <? if ($m_country == "+49" || $m_country == "49") { ?>selected<? } ?>>Germany (+49)</option>
									<option value="+44@@Great Britain" <? if ($m_country == "+44" || $m_country == "44") { ?>selected<? } ?>>Great Britain (+44)</option>
									<option value="+30@@Greece" <? if ($m_country == "+30" || $m_country == "30") { ?>selected<? } ?>>Greece (+30)</option>
									<option value="+1671@@Guam" <? if ($m_country == "+1671" || $m_country == "1671") { ?>selected<? } ?>>Guam (+1671)</option>
									<option value="+91@@India" <? if ($m_country == "+91" || $m_country == "91") { ?>selected<? } ?>>India (+91)</option>
									<option value="+972@@Israel" <? if ($m_country == "+972" || $m_country == "972") { ?>selected<? } ?>>Israel (+972)</option>
									<option value="+39@@Italy" <? if ($m_country == "+39" || $m_country == "39") { ?>selected<? } ?>>Italy (+39)</option>
									<option value="+965@@Kuwait" <? if ($m_country == "+965" || $m_country == "965") { ?>selected<? } ?>>Kuwait (+965)</option>
									<option value="+856@@Laos" <? if ($m_country == "+856" || $m_country == "856") { ?>selected<? } ?>>Laos (+856)</option>
									<option value="+853@@Macau" <? if ($m_country == "+853" || $m_country == "853") { ?>selected<? } ?>>Macau (+853)</option>
									<option value="+60@@Malaysia" <? if ($m_country == "+60" || $m_country == "60") { ?>selected<? } ?>>Malaysia (+60)</option>
									<option value="+31@@Netherlands" <? if ($m_country == "+31" || $m_country == "31") { ?>selected<? } ?>>Netherlands (+31)</option>
									<option value="+92@@Pakistan" <? if ($m_country == "+92" || $m_country == "92") { ?>selected<? } ?>>Pakistan (+92)</option>
									<option value="+48@@Poland" <? if ($m_country == "+48" || $m_country == "48") { ?>selected<? } ?>>Poland (+48)</option>
									<option value="+351@@Portugal" <? if ($m_country == "+351" || $m_country == "351") { ?>selected<? } ?>>Portugal (+351)</option>
									<option value="+34@@Spain" <? if ($m_country == "+34" || $m_country == "34") { ?>selected<? } ?>>Spain (+34)</option>
									<option value="+94@@Sri Lanka" <? if ($m_country == "+94" || $m_country == "94") { ?>selected<? } ?>>Sri Lanka (+94)</option>
									<option value="+46@@Sweden" <? if ($m_country == "+46" || $m_country == "46") { ?>selected<? } ?>>Sweden (+46)</option>
									<option value="+41@@Switzerland" <? if ($m_country == "+41" || $m_country == "41") { ?>selected<? } ?>>Switzerland (+41)</option>
									<option value="+886@@Taiwan" <? if ($m_country == "+886" || $m_country == "886") { ?>selected<? } ?>>Taiwan (+886)</option>
									<option value="+66@@Thailand" <? if ($m_country == "+66" || $m_country == "66") { ?>selected<? } ?>>Thailand (+66)</option>
									<option value="+90@@Turkey" <? if ($m_country == "+90" || $m_country == "90") { ?>selected<? } ?>>Turkey (+90)</option>
                            </select>
							</font>
						</td>
					</tr>
					<tr>
						<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
					</tr>	
					<tr>
						<td width=105 height="30">
							<div >
								<font size="2" face="돋움">Address</font>
							</div>
						</td>
						<td height="30" colspan="3" align="left">
							<font size="2" face="돋움">
							&nbsp;
								<textarea style="display:inline-table;width:800px;height:100px" maxlength=500 name="m_address" value="" size="55" class="adminbttn"> </textarea>
							</font>
						</td>
					</tr>

					<tr>
						<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
					</tr>	
					<tr>
						<td width=105 height="30">
							<div >
								<font size="2" face="돋움"><?=M_BIRTH?> (yyyymmdd)</font>
							</div>
						</td>
						<td height="30" colspan="3" align="left">
							<font size="2" face="돋움">&nbsp;
								<input maxlength=50 name="m_birthday" value="" size="25" class="adminbttn">
							</font>
						</td>
					</tr>



					<tr>
						<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
					</tr>	
					<tr>
						<td width=105 height="30">
							<div >
								<font size="2" face="돋움">Valid ID</font>
							</div>
						</td>
						<td height="30" colspan="3" align="left">
						<div >
						<font size="2" face="돋움" >
						&nbsp;
					<select name="m_validid" class="adminbttn">
						
						<option value="" <? if ($m_validid == "") { ?>selected<? } ?>>--Select--</option>
						<option disabled>---- GENERAL ID ----</option>
						<option value="Passport" <? if ($m_validid == "Passport") { ?>selected<? } ?>>Passport</option>
						<option value="National ID" <? if ($m_validid == "National ID") { ?>selected<? } ?>>National ID</option>	
						<option value="Drivers License" <? if ($m_validid == "Drivers License") { ?>selected<? } ?>>Driver's License</option>
						<option disabled>---- PHILIPPINE ID ----</option>
						<option value="Professional Regulation Commission PRC ID" <? if ($m_validid == "Professional Regulation Commission PRC ID") { ?>selected<? } ?>>Professional Regulation Commission (PRC) ID</option>
						<option value="National Bureau of Investigation NBI Clearance" <? if ($m_validid == "National Bureau of Investigation NBI Clearance") { ?>selected<? } ?>>National Bureau of Investigation (NBI) Clearance</option>
						<option value="Police Clearance" <? if ($m_validid == "Police Clearance") { ?>selected<? } ?>>Police Clearance</option>
						<option value="Postal ID" <? if ($m_validid == "Postal ID") { ?>selected<? } ?>>Postal ID</option>
						<option value="COMELEC Voters ID or Voters Certificate" <? if ($m_validid == "COMELEC Voters ID or Voters Certificate") { ?>selected<? } ?>>COMELEC Voter’s ID or Voter’s Certificate</option>
						<option value="Barangay Certificate of Residency" <? if ($m_validid == "Barangay Certificate of Residency") { ?>selected<? } ?>>Barangay Certificate of Residency</option>
						<option value="Government Service Insurance System GSIS e-Card" <? if ($m_validid == "Government Service Insurance System GSIS e-Card") { ?>selected<? } ?>>Government Service Insurance System (GSIS)e-Card</option>
						<option value="Social Security System SSS Card" <? if ($m_validid == "Social Security System SSS Card") { ?>selected<? } ?>>Social Security System (SSS) Card</option>
						<option value="Unified Multi-Purpose ID UMID" <? if ($m_validid == "Unified Multi-Purpose ID UMID") { ?>selected<? } ?>>Unified Multi-Purpose ID (UMID)</option>
						<option value="Senior Citizen Card" <? if ($m_validid == "Senior Citizen Card") { ?>selected<? } ?>>Senior Citizen Card</option>
						<option value="Overseas Workers Welfare Administration OWWA ID" <? if ($m_validid == "Overseas Workers Welfare Administration OWWA ID") { ?>selected<? } ?>>Overseas Workers Welfare Administration (OWWA) ID</option>
						<option value="OFW ID" <? if ($m_validid == "OFW ID") { ?>selected<? } ?>>OFW ID</option>
						<option value="Seamans Book" <? if ($m_validid == "Seamans Book") { ?>selected<? } ?>>Seaman’s Book</option>
						<option value="Alien Certification of Registration/Immigrant Certificate of Registration" <? if ($m_validid == "Alien Certification of Registration/Immigrant Certificate of Registration") { ?>selected<? } ?>>Alien Certification of Registration/Immigrant Certificate of Registration</option>
						<option value="Government Office and GOCC ID eg Armed forces of the Philippines AFP ID" <? if ($m_validid == "Government Office and GOCC ID eg Armed forces of the Philippines AFP ID") { ?>selected<? } ?>>Government Office and GOCC ID, e.g. Armed forces of the Philippines (AFP ID)</option>
						<option value="Home Development Mutual Fund HDMF ID" <? if ($m_validid == "Home Development Mutual Fund HDMF ID") { ?>selected<? } ?>>Home Development Mutual Fund (HDMF ID)</option>
						<option value="Certification from the National Council for the Welfare of Disabled Persons NCWDP" <? if ($m_validid == "Certification from the National Council for the Welfare of Disabled Persons NCWDP") { ?>selected<? } ?>>Certification from the National Council for the Welfare of Disabled Persons (NCWDP)</option>
						<option value="Department of Social Welfare and Development DSWD Certification of Indigency" <? if ($m_validid == "Department of Social Welfare and Development DSWD Certification of Indigency") { ?>selected<? } ?>>Department of Social Welfare and Development (DSWD) Certification of Indigency</option>
						<option value="Integrated Bar of the Philippines IBP ID" <? if ($m_validid == "Integrated Bar of the Philippines IBP ID") { ?>selected<? } ?>>Integrated Bar of the Philippines (IBP) ID</option>
						<option disabled>---- KOREAN ID ----</option>
						<option value="여권" <? if ($m_validid == "여권") { ?>selected<? } ?>>여권</option>
						<option value="주민등록증" <? if ($m_validid == "주민등록증") { ?>selected<? } ?>>주민등록증</option>
						<option value="공무원증" <? if ($m_validid == "공무원증") { ?>selected<? } ?>>공무원증</option>
						<option value="운전면허증" <? if ($m_validid == "운전면허증") { ?>selected<? } ?>>운전면허증</option>
						<option value="외국인등록증" <? if ($m_validid == "외국인등록증") { ?>selected<? } ?>>외국인등록증</option>
						<option disabled>---- CHINESE ID ----</option>
						<option value="护照" <? if ($m_validid == "护照") { ?>selected<? } ?>>护照</option>
						<option value="国家身份证" <? if ($m_validid == "国家身份证") { ?>selected<? } ?>>国家身份证</option>
						<option value="驾驶执照" <? if ($m_validid == "驾驶执照") { ?>selected<? } ?>>驾驶执照</option>	
						<option disabled>---- JAPANESE ID ----</option>
						<option value="パスポート" <? if ($m_validid == "パスポート") { ?>selected<? } ?>>パスポート</option>
						<option value="外国人登録証明書" <? if ($m_validid == "外国人登録証明書") { ?>selected<? } ?>>外国人登録証明書</option>
						<option value="運転免許" <? if ($m_validid == "運転免許") { ?>selected<? } ?>>運転免許</option>									
					</select></font></div>
						</td>
					</tr>
					<tr>
						<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
					</tr>	
					<tr>
						<td width=105 height="30">
							<div >
								<font size="2" face="돋움">ID Number </font>
							</div>
						</td>
						<td height="30" colspan="3" align="left">
							<font size="2" face="돋움">&nbsp;
								<input style="width:400px;" maxlength=50 name="m_validnum" value="" size="50" class="adminbttn">
							</font>
							(Please put a semicolon (;) if multiple values.)
						</td>
					</tr>
					<!-- <tr>
						<td width=105 height="30">
							<div >
								<font size="2" face="돋움">Existing Customer</font>
							</div>
						</td>
						<td height="30" colspan="3" align="left">
							<font size="2" face="돋움">&nbsp;
								<input type="radio" name="m_regcheck" value="0">No<input type="radio" name="m_regcheck" value="1">Yes
							</font>
						</td>
					</tr> -->

	
					<tr>
						<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
					</tr>
					<tr>
						<td width=105 height="30">
							<div >
								<font size="2" face="돋움">Case Type</font>
							</div>
						</td>
						<td height="30" colspan="3" align="left">
						<font size="2" face="돋움">&nbsp;
							<select name="m_type" id="m_type" class="m_type">
									<option value="Child Trafficking/Exploitation" >Child Trafficking/Exploitation </option>
									<option value="Terorrism Financing">Terorrism Financing</option>
									<option value="Money Laundering">Money Laundering</option>
									<option value="Smuggling">Smuggling</option>
									<option value="Drugs">Drugs</option>
									<option value="Kidnapping and Detention">Kidnapping and Detention</option>
									<option value="Communist Terrorist Groups">Communist Terrorist Groups</option>
									<option value="Others">Others</option>
                            </select>
							</font>
						</td>
					</tr>

						<tr>
							<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
						</tr>
						<tr id="wallet" style="display: none;">
							<td width=105 height="30">
								<div >
									<font size="2" face="돋움">Coin</font>
								</div>
							</td>
							<td height="30" colspan="3" align="left">
							<font size="2" face="돋움">&nbsp;
								<select name="m_coin" id="m_coin">
										<option value="BTC" >BTC</option>
										<option value="ETH">ETH</option>
										<option value="LTC">LTC</option>
										<option value="XRP">XRP</option>
										<option value="EOS">EOS</option>
										<option value="TRX">TRX</option>
										<option value="RVN">RVN</option>
										<option value="CELO">CELO</option>
										<option value="CUSD">CUSD</option>
										<option value="BTG">BTG</option>
										<option value="BCH">BCH</option>
										<option value="USDT">USDT</option>
										<option value="KRWC">KRWC</option>
								</select>
								</font>
							</td>
						</tr>
	
						<tr id="wallet1" style="display: none;">
							<td width=105 height="30">
								<div >
									<font size="2" face="돋움">Wallet Address </font>
								</div>
							</td>
							<td height="30" colspan="3" align="left">
								<font size="2" face="돋움">&nbsp;
									<textarea style="display:inline-table;width:800px;" maxlength=500 name="m_wallet" id="m_wallet" value="" class="adminbttn" rows="3"> </textarea>
								</font>
								(Please put a semicolon (;) if multiple values.)
							</td>
						</tr>

						<tr>
							<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
						</tr>

					<tr>
						<td width=105 height="30">
							<div>
								<font size="2" face="돋움">Admin Memo</font>
							</div>
						</td>
						<td width=479 height="30" colspan="3" align="left">
							<font size="2" face="돋움">&nbsp;
								<textarea style="display:inline-table;width:800px;height:500px" maxlength=1000 name="m_remarks" value="" size="50" class="adminbttn"> </textarea>
							</font>
						</td>
					</tr>

					<tr>
						<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
					</tr>

			</table>
		</td>
	</tr>
	<input type="hidden" name="keyfield" value="<? echo ($keyfield) ?>">
	<input type="hidden" name="key" value="<? echo ($key) ?>">
	<input type="hidden" name="page" value="<? echo ($page) ?>">
	<input type="hidden" name="m_countryname" value="<? echo ($m_countryname) ?>">
	</form>
</table>
<table width="600" border="0" cellspacing="0" cellpadding="4" class="left_margin30">
	<tr>
		<td height="30"></td>
	</tr>
	<tr>
		<td height="20" >
			<input type="button" value="Register" class="adminbttn" onClick="javascript:go_modify()">
		</td>
	</tr>
</table>
<br>
<br>

<? include "../inc/down_menu.php"; ?>

<script>
	$(function(){
    $('.m_type').change(function(){
      if($(this).val() == "Others") {
        $("#wallet").show();
				$("#wallet1").show();
        $("#m_wallet").prop('required',true);
				$("#m_coin").prop('required',true);
      }
      else {
        $("#wallet").hide();
				("#wallet1").hide();
        $("#m_wallet").prop('required',false);
				$("#m_coin").prop('required',false);
      }
    });
	});
</script>