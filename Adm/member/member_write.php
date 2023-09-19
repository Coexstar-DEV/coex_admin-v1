<?
session_start();

include "../common/user_function.php";
include "../common/dbconn.php";
include_once "../common/". ($_SESSION["language"] == "kr" ? "lang_kr.php" : "lang_en.php");
include "../inc/top_menu.php";
include "../inc/left_menu_member.php";
?>

<script language="javascript">
	function go_modify() {
		if (!document.form.m_id.value) {
			alert('<?=M_INPUT_ID?>');
			document.form.id.focus();
			return;
		}

		if (document.form.m_passwd.value.length < 4) {
			alert('<?=M_INPUT_PWD?>');
			document.form.passwd.focus();
			return;
		}

		if (document.form.m_passwd.value != document.form.m_passwd2.value) {
			alert('<?=M_PWD_CONFIRM?>');
			document.form.passwd2.focus();
			return;
		}

		if (!document.form.m_name.value) {
			alert('<?=M_INPUT_NAME?>');
			document.form.name.focus();
			return;
		}

		document.form.action = "member_ok.php";
		document.form.submit();
	}
</script>
<table width="1100" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td height=30></td>
	</tr>

	<tr>
		<td>
			<table width="600" border='0' cellspacing='0' cellpadding='0'>
				<form name="form" method="post" enctype="multipart/form-data">
					<tr>
						<td colspan=4 height=2 bgcolor='#88B7DA'></td>
					</tr>
					<tr>
						<td colspan=4 height=5></td>
					</tr>
					<tr>
						<td width=115 height="30">
							<div align="center">
								<font size="2" face="돋움">ID</font>
							</div>
						</td>
						<td width=479 height="30" colspan="3" align="left">
							&nbsp; &nbsp;<input name="m_id" maxlength=30 name="m_passwd" size=30 class="adminbttn">
						</td>
					</tr>
					<tr>
						<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
					</tr>
					<tr>
						<td width=115 height="30">
							<div align="center">
								<font face="돋움" size="2"><?=M_PWD?></font>
							</div>
						</td>
						<td width=479 height="30" colspan="3" align="left">
							<font size="2" face="돋움">
								&nbsp;
								<input type="text" maxlength=30 name="m_passwd" size=30 class="adminbttn">
							</font>
							<font face="돋움" size="2"><?=M_PWD_DESC?></font>
						</td>
					</tr>
					<tr>
						<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
					</tr>
					<tr>
						<td width=115 height="30">
							<div align="center">
								<font size="2" face="돋움"><?=M_PWD_CONFIRM1?></font>
							</div>
						</td>
						<td width=479 height="30" colspan="3" align="left">
							<font size="2" face="돋움">
								&nbsp;
								<input type="password" maxlength=30 name="m_passwd2" size=30 class="adminbttn">
								<?=M_PWD_CONFIRM2?></font>
							<input type="hidden" value="" name="pass11">
						</td>
					</tr>
					<tr>
						<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
					</tr>
					<tr>
						<td width=115 height="30">
							<div align="center">
								<font size="2" face="돋움"><?=M_NAME?>(<?=M_NAME1?>)</font>
							</div>
						</td>
						<td width=479 height="30" colspan="3" align="left">
							<font size="2" face="돋움">
								&nbsp;
								<input name="m_name" size="20" value="" class="adminbttn"></font>
						</td>
					</tr>
					<tr>
						<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
					</tr>
					<tr>
						<td width=115 height="30">
							<div align="center">
								<font size="2" face="돋움"><?=M_LEVEL?></font>
							</div>
						</td>
						<td width=479 height="30" colspan="3">
							<font size="2" face="돋움">
								&nbsp;
								<select name="m_level">
									<? for ($i = 1; $i <= $DEFINE_USER_LEVEL; $i++) { ?>
									<option value="<?= $i ?>"><?= $i ?></option>
									<? } ?>
								</select></font>
						</td>
					</tr>
					<tr>
						<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
					</tr>
					<tr>
						<td width=105 height="30">
							<div align="center">
								<font size="2" face="돋움"><?=M_CHANGER?></font>
							</div>
						</td>
						<td height="30" colspan="3" align="left">
							<font size="2" face="돋움">&nbsp;
								<input maxlength=50 name="m_measge" value="" size="25" class="adminbttn">
							</font>
						</td>
					</tr>
					<tr>
						<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
					</tr>
					<tr>
						<td width=105 height="30">
							<div align="center">
								<font size="2" face="돋움"><?=M_MAIL?></font>
							</div>
						</td>
						<td height="30" colspan="3" align="left">
							<font size="2" face="돋움">&nbsp;
								<input maxlength=50 name="m_email" value="" size="25" class="adminbttn">
							</font>
						</td>
					</tr>
					<tr>
						<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
					</tr>
					<tr>
						<td width=105 height="30">
							<div align="center">
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
							<div align="center">
								<font size="2" face="돋움"><?=M_COUNTRY?></font>
							</div>
						</td>
						<td height="30" colspan="3" align="left">
							<font size="2" face="돋움">&nbsp;&nbsp;</font><select name="m_contury" class="adminbttn">
								<option value="+82">Korea (+82)</option>
								<option value="+1">USA/Cananda (+1)</option>
								<option value="+81">JAPAN (+81)</option>
								<option value="+84">Vietnam (+84)</option>
								<option value="+852">Hongkong (+852)</option>
								<option value="+86">China (+86)</option>
								<option value="+63">Philippines (+63)</option>
							</select>
						</td>
					</tr>
					<tr>
						<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
					</tr>
					<tr>
						<td width=105 height="30">
							<div align="center">
								<font size="2" face="돋움"><?=M_ADMIN_MEMO?></font>
							</div>
						</td>
						<td height="30" colspan="3" align="left">
							<font size="2" face="돋움">&nbsp;
								<input maxlength=50 name="m_admmemo" value="" size="25" class="adminbttn">
							</font>
						</td>
					</tr>
					<tr>
						<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
					</tr>
					<tr>
						<td width=105 height="30">
							<div align="center">
								<font size="2" face="돋움"><?=M_AUTH?></font>
							</div>
						</td>
						<td height="30" colspan="3" align="left">
							<font size="2" face="돋움" align="left">
								&nbsp;
								<input type="radio" name="m_confirm" value="0" checked><?=M_AUTH_NO?><input type="radio" name="m_confirm" value="1"><?=M_AUTH_YES?>
							</font>
						</td>
					</tr>
					<tr>
						<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
					</tr>
					<tr>
						<td width=105 height="30">
							<div align="center">
								<font size="2" face="돋움">OTP<?=M_AUTH_YES?></font>
							</div>
						</td>
						<td height="30" colspan="3" align="left">
							<font size="2" face="돋움" align="left">
								&nbsp;
								<input type="radio" name="m_otpcheck" value="0" checked><?=M_AUTH_NO?><input type="radio" name="m_otpcheck" value="1"><?=M_AUTH_YES?>
							</font>
						</td>
					</tr>
					<tr>
						<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
					</tr>
					<tr>
						<td width=105 height="30">
							<div align="center">
								<font size="2" face="돋움"><?=M_ACCOUNT?></font>
							</div>
						</td>
						<td height="30" colspan="3" align="left">
							<font size="2" face="돋움">&nbsp;
								<input maxlength=50 name="m_banknum" value="" size="25" class="adminbttn">
							</font>
						</td>
					</tr>
					<tr>
						<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
					</tr>
					<tr>
						<td width=105 height="30">
							<div align="center">
								<font size="2" face="돋움"><?=M_BANK?></font>
							</div>
						</td>
						<td height="30" colspan="3" align="left">
							<font size="2" face="돋움">&nbsp;
								<input maxlength=50 name="m_bankname" value="" size="25" class="adminbttn">
							</font>
						</td>
					</tr>
					<tr>
						<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
					</tr>
					<tr>
						<td width=105 height="30">
							<div align="center">
								<font size="2" face="돋움"><?=M_BIRTH?></font>
							</div>
						</td>
						<td height="30" colspan="3" align="left">
							<font size="2" face="돋움">&nbsp;
								<input maxlength=50 name="m_birtday" value="" size="25" class="adminbttn">
							</font>
						</td>
					</tr>
					<tr>
						<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
					</tr>
					<tr>
						<td width=105 height="30">
							<div align="center">
								<font size="2" face="돋움"><?=M_REFER?></font>
							</div>
						</td>
						<td height="30" colspan="3" align="left">
							<font size="2" face="돋움">&nbsp;
								<input maxlength=50 name="m_address" value="" size="25" class="adminbttn">
							</font>
						</td>
					</tr>
					<tr>
						<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
					</tr>


					<!-- 								<tr>  -->
					<!-- 									<td width=105 height="30">  -->
					<!-- 										<div align="center"><font size="2" face="돋움">주소</font></div> -->
					<!-- 									</td> -->
					<!-- 										<script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script> -->
					<!-- 										<script> -->
					<!-- 											function openDaumPostcode() {							 -->
					<!-- 											new daum.Postcode({ -->
					<!-- 												oncomplete: function(data) { -->
					<!-- 													// 팝업에서 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분. -->
					<!--  -->
					<!-- 													// 도로명 주소의 노출 규칙에 따라 주소를 조합한다. -->
					<!-- 													// 내려오는 변수가 값이 없는 경우엔 공백('')값을 가지므로, 이를 참고하여 분기 한다. -->
					<!-- 													var fullRoadAddr = data.roadAddress; // 도로명 주소 변수 -->
					<!-- 													var extraRoadAddr = ''; // 도로명 조합형 주소 변수 -->
					<!--  -->
					<!-- 													// 법정동명이 있을 경우 추가한다. (법정리는 제외) -->
					<!-- 													// 법정동의 경우 마지막 문자가 "동/로/가"로 끝난다. -->
					<!-- 													if(data.bname !== '' && /[동|로|가]$/g.test(data.bname)){ -->
					<!-- 														extraRoadAddr += data.bname; -->
					<!-- 													} -->
					<!-- 													// 건물명이 있고, 공동주택일 경우 추가한다. -->
					<!-- 													if(data.buildingName !== '' && data.apartment === 'Y'){ -->
					<!-- 													   extraRoadAddr += (extraRoadAddr !== '' ? ', ' + data.buildingName : data.buildingName); -->
					<!-- 													} -->
					<!-- 													// 도로명, 지번 조합형 주소가 있을 경우, 괄호까지 추가한 최종 문자열을 만든다. -->
					<!-- 													if(extraRoadAddr !== ''){ -->
					<!-- 														extraRoadAddr = ' (' + extraRoadAddr + ')'; -->
					<!-- 													} -->
					<!-- 													// 도로명, 지번 주소의 유무에 따라 해당 조합형 주소를 추가한다. -->
					<!-- 													if(fullRoadAddr !== ''){ -->
					<!-- 														fullRoadAddr += extraRoadAddr; -->
					<!-- 													} -->
					<!--  -->
					<!-- 													// 우편번호와 주소 정보를 해당 필드에 넣는다. -->
					<!-- 													document.getElementById('zip').value = data.zonecode; //5자리 새우편번호 사용 -->
					<!-- 													document.getElementById('address').value = fullRoadAddr; -->
					<!-- 													//document.getElementById('address').value = data.jibunAddress; -->
					<!--  -->
					<!-- 													 -->
					<!-- 												} -->
					<!-- 											}).open();	 -->
					<!-- 											} -->
					<!-- 										</script> -->
					<!-- 									<td height="30" colspan="3" align="left">&nbsp; -->
					<!-- 										<input maxlength=20 name="zip" value="<?= $zip ?>" id="zip" size=15 class="adminbttn"> -->
					<!-- 										</font><font face="돋움" size="1">&nbsp;</font><font face="돋움" size="2"><span onclick="javascript:openDaumPostcode()" style="cursor:pointer;">[우편번호]</span>&nbsp;  -->
					<!-- 										</font> -->
					<!-- 									</td> -->
					<!-- 								</tr> -->
					<!-- 								<tr><td colspan=4 height=1 bgcolor='#D2DEE8'></td></tr> -->
					<tr>
						<td height="30">

						</td>
						<td height="30" colspan="3" align="left">
							&nbsp;
							<input maxlength="50" name="address" value="" id="address" size=60 class="adminbttn">
						</td>
					</tr>
					<tr>
						<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
					</tr>
					<tr>
						<td width=105 height="30">
							<div align="center">
								<font size="2" face="돋움"><?=M_DEL?></font>
							</div>
						</td>
						<td height="30" colspan="3" align="left">
							<font size="2" face="돋움" align="left">
								&nbsp;
								<input type="radio" name="m_block" value="0" checked><?=M_BLOCK_NO?><input type="radio" name="m_block" value="1"><?=M_BLOCK_YES?><input type="radio" name="m_block" value="2"><?=M_DEL?>
							</font>
						</td>
					</tr>
					<tr>
						<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
					</tr>

					<tr>
						<td width=105 height="30">

							<div align="center">
								<font size="2" face="돋움">Gender</font>
							</div>
						</td>
						<td height="30" colspan="3" align="left">
							<font size="2" face="돋움" align="left">
								&nbsp;
								<input type="radio" name="m_gender" value="Male" checked>Male
								<input type="radio" name="m_gender" value="Female">Female
							</font>

						</td>
		
					</tr>	

					<tr>
						<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
					</tr>

					<tr>
						<td width=105 height="30">

							<div align="center">
								<font size="2" face="돋움">Citizenship</font>
							</div>
						</td>
						<td height="30" colspan="3" align="left">
							<font size="2" face="돋움" align="left">
								&nbsp;
								<input maxlength=50 name="m_citizenship" value="" size="25" class="adminbttn">
							</font>

						</td>
		
					</tr>

					<tr>
						<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
					</tr>	

					<tr>
						<td width=105 height="30">

							<div align="center">
								<font size="2" face="돋움">Employment Status</font>
							</div>
						</td>
						<td height="30" colspan="3" align="left">
							<font size="2" face="돋움" align="left">
								&nbsp;
								<select name="m_empstatus" class="adminbttn">
									<option value="" hidden>--Select--</option>
									<option value="Employed">Employed</option>
									<option value="Self Employed">Self Employed</option>
									<option value="Unemployed">Unemployed</option>
									<option value="Retired">Retired</option>
									<option value="Student">Student</option>
								</select></font>
							</font>

						</td>
		
					</tr>

					<tr>
						<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
					</tr>

					<tr>
						<td width=105 height="30">

							<div align="center">
								<font size="2" face="돋움">Salary Range</font>
							</div>
						</td>
						<td height="30" colspan="3" align="left">
							<font size="2" face="돋움" align="left">
								&nbsp;
								<select name="m_empsalary" class="adminbttn">
									<option value="" hidden>--Select--</option>
									<option value="5000 - 10000">5000 - 10000</option>
									<option value="10001 - 50000">10001 - 50000</option>
									<option value="50001 - 100000">50001 - 100000</option>
									<option value="100001 - 500000">100001 - 500000</option>
									<option value="more than 500000">more than 500000</option>
								</select></font>
							</font>

						</td>
		
					</tr>

					<tr>
						<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
					</tr>

					<tr>
						<td width=105 height="30">

							<div align="center">
								<font size="2" face="돋움">Employer Name</font>
							</div>
						</td>
						<td height="30" colspan="3" align="left">
							<font size="2" face="돋움" align="left">
								&nbsp;
								<input maxlength=50 name="m_employername" value="" size="25" class="adminbttn">
							</font>

						</td>
		
					</tr>	

					<tr>
						<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
					</tr>

					<tr>
						<td width=105 height="30">

							<div align="center">
								<font size="2" face="돋움">Employee Position</font>
							</div>
						</td>
						<td height="30" colspan="3" align="left">
							<font size="2" face="돋움" align="left">
								&nbsp;
								<select name="m_position" class="adminbttn">
									<option value="" hidden>--Select--</option>
									<option value="Staff Level">Staff Level</option>
									<option value="Supervisor">Supervisor</option>
									<option value="Manager">Manager</option>
									<option value="Executive">Executive</option>
									<option value="Businessman">Businessman</option>
									<option value="Freelance">Freelance</option>
									<option value="Not Applicable">Not Applicable</option>
								</select></font>
							</font>

						</td>
		
					</tr>

					<tr>
						<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
					</tr>

					<tr>
						<td width=105 height="30">

							<div align="center">
								<font size="2" face="돋움">Source of Funds</font>
							</div>
						</td>
						<td height="30" colspan="3" align="left">
							<font size="2" face="돋움" align="left">
								&nbsp;
								<input maxlength=50 name="m_fundsource" value="" size="25" class="adminbttn">
							</font>

						</td>
		
					</tr>	

					<tr>
						<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
					</tr>

					<tr>
						<td width=105 height="30">

							<div align="center">
								<font size="2" face="돋움">Valid ID</font>
							</div>
						</td>
						<td height="30" colspan="3" align="left">
							<font size="2" face="돋움" align="left">
								&nbsp;
								<select name="m_validid" class="adminbttn" disabled>
									<option value="" hidden>--Select--</option>
									<option value="Passport">Passport</option>
									<option value="Drivers License">Driver's License</option>
									<option value="Professional Regulation Commission  PRC ID">Professional Regulation Commission (PRC) ID</option>
									<option value="National Bureau of Investigation NBI Clearance">National Bureau of Investigation (NBI) Clearance</option>
									<option value="Police Clearance">Police Clearance</option>
									<option value="Postal ID">Postal ID</option>
									<option value="COMELEC Voters ID or Voters Certificate">COMELEC Voter’s ID or Voter’s Certificate</option>
									<option value="Barangay Certificate of Residency">Barangay Certificate of Residency</option>
									<option value="Government Service Insurance System GSIS e-Card">Government Service Insurance System (GSIS)e-Card</option>
									<option value="Social Security System SSS Card">Social Security System (SSS) Card</option>
									<option value="Unified Multi-Purpose ID UMID">Unified Multi-Purpose ID (UMID)</option>
									<option value="Senior Citizen Card">Senior Citizen Card</option>
									<option value="Overseas Workers Welfare Administration OWWA ID">Overseas Workers Welfare Administration (OWWA) ID</option>
									<option value="OFW ID">OFW ID</option>
									<option value="Seamans Book">Seaman’s Book</option>
									<option value="Alien Certification of Registration/Immigrant Certificate of Registration">Alien Certification of Registration/Immigrant Certificate of Registration</option>
									<option value="Government Office and GOCC ID eg Armed forces of the Philippines AFP ID">Government Office and GOCC ID, e.g. Armed forces of the Philippines (AFP ID)</option>
									<option value="Home Development Mutual Fund HDMF ID">Home Development Mutual Fund (HDMF ID)</option>
									<option value="Certification from the National Council for the Welfare of Disabled Persons NCWDP">Certification from the National Council for the Welfare of Disabled Persons (NCWDP)</option>
									<option value="Department of Social Welfare and Development DSWD Certification of Indigency">Department of Social Welfare and Development (DSWD) Certification of Indigency</option>
									<option value="Integrated Bar of the Philippines IBP ID">Integrated Bar of the Philippines (IBP) ID</option>
								</select></font>
							</font>

						</td>
		
					</tr>	

					<tr>
						<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
					</tr>

					<tr>
						<td width=105 height="30">

							<div align="center">
								<font size="2" face="돋움">ID Number</font>
							</div>
						</td>
						<td height="30" colspan="3" align="left">
							<font size="2" face="돋움" align="left">
								&nbsp;
								<input maxlength=50 name="m_idnumber" value="" size="25" class="adminbttn">
							</font>

						</td>
		
					</tr>

					<tr>
						<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
					</tr>

					<tr>
						<td width=105 height="30">

							<div align="center">
								<font size="2" face="돋움">Valid ID Image</font>
							</div>
						</td>
						<td height="30" colspan="3" align="left">
							<font size="2" face="돋움" align="left">
								&nbsp;
								<input type="file" name="m_validimg" value="" size="25" class="adminbttn" disabled>
							</font>

						</td>
		
					</tr>

					<tr>
						<td colspan=4 height=1 bgcolor='#D2DEE8'></td>
					</tr>

					<!-- <tr>
						<td width=105 height="30">

							<div align="center">
								<font size="2" face="돋움">Registration Check</font>
							</div>
						</td>
						<td height="30" colspan="3" align="left">
							<font size="2" face="돋움" align="left">
								&nbsp;
								<input type="radio" name="m_regcheck" value="0" checked>New
								<input type="radio" name="m_regcheck" value="1">Exisiting
							</font>

						</td>
		
					</tr> -->

					
					<tr>
						<td width=105 height="30">

							<div align="center">
								<font size="2" face="돋움">Verified</font>
							</div>
						</td>
						<td height="30" colspan="3" align="left">
							<font size="2" face="돋움" align="left">
								&nbsp;
								<input type="radio" name="m_regcheck" value="0" checked>Yes
								<input type="radio" name="m_regcheck" value="1">No
							</font>

						</td>
		
					</tr>
			</table>
		</td>
	</tr>
	<input type="hidden" name="keyfield" value="<? echo ($keyfield) ?>">
	<input type="hidden" name="key" value="<? echo ($key) ?>">
	<input type="hidden" name="page" value="<? echo ($page) ?>">
	<input type="hidden" name="m_country_name" value="<? echo ($m_country_name) ?>">
	</form>
</table>
<table width="600" border="0" cellspacing="0" cellpadding="4" class="left_margin30">
	<tr>
		<td height="30"></td>
	</tr>
	<tr>
		<td height="20" align="center">
			<input type="button" value="<?=M_REGISTER?>" class="adminbttn" onClick="javascript:go_modify()">
		</td>
	</tr>
</table>
<br>
<br>



<? include "../inc/down_menu.php"; ?>