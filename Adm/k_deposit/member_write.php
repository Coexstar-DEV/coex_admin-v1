<?
#####################################################################
include "../common/user_function.php";
include "../common/dbconn.php";
include "../inc/top_menu.php";
include "../inc/left_menu_order.php";

#####################################################################
?>
 
<script language="javascript">
<!--
function idchk(url){
	str=document.form.m_id.value

    
	if(!document.form.m_id.value) {
      alert('<?=M_INPUT_ID?>');
      document.form.m_id.focus();
      return;
   }
   url = url + '?m_id=' + document.form.m_id.value;

   /*
	var isID = /^[a-z0-9_]{4,12}$/;
	if( !isID.test(str) ){
   	alert("아이디는 4~12자의 영문 소문자와 숫자만 사용할 수 있습니다.");
      document.join.id.focus();
      return;
   }
 	url = url + '?id=' + document.join.id.value;
	*/

	//alert (url);
	window.open(url,"","width=301,height=210,toolbar=no,location=no,directorys=no,status=no,menubar=no,scrollbars=no,resizable=no,left=100,top=100");
}

function open_winaddr(url){
	window.open(url,"window","width=320,height=280,toolbar=no,location=no,directorys=no,status=no,menubar=no,scrollbars=yes,resizable=no,left=100,top=100")
}

function go_modify() {      
//	if(!document.form.m_id.value) {
//		alert('<?=M_INPUT_ID?>');
//		document.form.id.focus();
//		return;
//	}
//
//	if(document.form.m_passwd.value.length < 4) {
//		alert('<?=M_INPUT_PWD?>');
//		document.form.passwd.focus();
//		return;
//	}
//
//	if(document.form.m_passwd.value != document.form.m_passwd2.value) {
//		alert('비밀번호확인이 일치하지 않습니다.');
//		document.form.passwd2.focus();
//		return;
//	}
//
//		if(!document.form.m_name.value) {
//		alert('이름을 입력하세요!');
//		document.form.name.focus();
//		return;
//	}

	document.form.action="member_ok.php";
	document.form.submit();
}

function go_list() {
	location="member.php?K_dis=<?=$K_dis?>";
}
function open_addr(url){
	window.open(url,"window","width=350,height=230,toolbar=no,location=no,directorys=no,status=no,menubar=no,scrollbars=yes,resizable=no,left=100,top=100")
}

//-->
</script> 

				<table width="1100"  border="0" cellspacing="0" cellpadding="0">
					<tr><td height=30></td></tr>
					<tr>
						<td>
							<table width="1000" align=center border='0' cellspacing='0' cellpadding='0'>
							<form name="form" method="post">
							<tr>
									<td colspan=2><img src="../image/icon2.gif" width=45 height=35 border=0> <b><?=M_KRW.M_DEPOSIT.M_ORDER.M_HIS?></b></td>
								</tr>
								<tr><td colspan=4 height=2 bgcolor='#88B7DA'></td></tr>
								<tr><td colspan=4 height=5></td></tr>
								<tr>	
									<td width=105 height="30"> 
										<div align="center"><font size="2" face="돋움"><?=M_PAY?></font></div>
									</td>
									<td height="30" colspan="3" align="left">
										<select name="k_payment"> 
											<option value="0" <?if($k_payment=="0"){?> checked <?}?>><?=M_PAY1?></option>
											<option value="1" <?if($k_payment=="1"){?> checked <?}?>><?=M_PAY2?></option>
											<option value="2" <?if($k_payment=="2"){?> checked <?}?>><?=M_PAY3?></option>
											<option value="3" <?if($k_payment=="3"){?> checked <?}?>><?=M_PAY4?></option>
										</select>
									</td>
								</tr>
								<tr><td colspan=4 height=1 bgcolor='#D2DEE8'></td></tr>
<!-- 								<tr>  -->
<!-- 									<td width=105 height="30">  -->
<!-- 										<div align="center"><font face="돋움" size="2">유저번호</font></div> -->
<!-- 									</td> -->
<!-- 									<td height="30" colspan="3" align="left"> -->
<!-- 										&nbsp;  -->
<!-- 										<input type="text" maxlength=30 name="k_userno" value="<?=$k_userno?>" size=30 class="adminbttn"> -->
<!-- 									</td> -->
<!-- 								</tr> -->
<!-- 								<tr><td colspan=4 height=1 bgcolor='#D2DEE8'></td></tr> -->
								<tr> 
									<td width=105 height="30"> 
										<div align="center"><font face="돋움" size="2"><?=M_ID?></font></div>
									</td>
									<td height="30" colspan="3" align="left">
										&nbsp; 
										<input type="text" maxlength=30 name="k_id" value="<?=$k_id?>" size=30 class="adminbttn">
									</td>
								</tr>
								<tr><td colspan=4 height=1 bgcolor='#D2DEE8'></td></tr>
								<tr> 
									<td width=105 height="30"> 
										<div align="center"><font face="돋움" size="2"><?=M_ORDER.M_PRICE?></font></div>
									</td>
									<td height="30" colspan="3" align="left">
										&nbsp; 
										<input type="text" maxlength=30 name="k_orderprice" value="<?=$k_orderprice?>" size=30 class="adminbttn">
									</td>
								</tr>
								<tr><td colspan=4 height=1 bgcolor='#D2DEE8'></td></tr>
								<tr> 
									<td width=105 height="30"> 
										<div align="center"><font size="2" face="돋움"><?=M_DEPOSIT.M_PRICE?></font></div>
									</td>
									<td height="30" colspan="3" align="left">
										&nbsp;
										<input type="text" maxlength=30 name="k_depositprice" value="<?=$k_depositprice?>" size=30 class="adminbttn">
									</td>
								</tr>				
								<tr><td colspan=4 height=1 bgcolor='#D2DEE8'></td></tr>
								<tr> 
									<td width=105 height="30"> 
										<div align="center"><font size="2" face="돋움">할인액</font></div>
									</td>
									<td height="30" colspan="3" align="left">
										&nbsp;
										<input name="k_sellprice" value="<?=$k_sellprice?>" size=30 class="adminbttn">
									</td>
								</tr>
								<tr><td colspan=4 height=1 bgcolor='#D2DEE8'></td></tr>
								 <tr> 
									<td width=115 height="30"> 
										<div align="center"><font size="2" face="돋움"><?=M_REFUND.M_PRICE?>환불액</font></div>
									</td>

									<td height="30" colspan="3" align="left">
										&nbsp;
										<input name="k_returnprice" value="<?=$k_returnprice?>" size=30 class="adminbttn">
									</td>
									</td>
								</tr>

								<tr><td colspan=4 height=1 bgcolor='#D2DEE8'></td></tr>
								<tr> 
									<td width=105 height="30"> 
										<div align="center"><font size="2" face="돋움">입금자</font></div>
									</td>
									<td height="30" colspan="3" align="left">
										<font size="2" face="돋움">&nbsp;
										<input maxlength=50 name="k_depositname" value="<?=$k_depositname?>" size="25" class="adminbttn">
										</font>
									</td>
								</tr>
								<tr><td colspan=4 height=1 bgcolor='#D2DEE8'></td></tr>
								<tr> 
									<td width=105 height="30"> 
										<div align="center"><font size="2" face="돋움"><?=M_PAYMENT.M_DONE?></font></div>
									</td>
									<td height="30" colspan="3" align="left">
										<font size="2" face="돋움">&nbsp;
											<input type="radio" name="k_check" value="0" <?if($k_check=="0"){?>checked<?}?>><?=M_PAY_NO?><input type="radio" name="k_check" value="1" <?if($k_check=="1"){?>checked<?}?>><?=M_PAYMENT?>

										</font>
									</td>
								</tr>
								<tr><td colspan=4 height=1 bgcolor='#D2DEE8'></td></tr>
								<tr> 
									<td width=105 height="30"> 
										<div align="center"><font size="2" face="돋움"><?=M_ORDERER?></font></div>
									</td>
									<td height="30" colspan="3" align="left">
										<font size="2" face="돋움">&nbsp;
										<input maxlength=50 name="k_ordername" value="<?=$k_ordername?>" size="25" class="adminbttn">
										</font>
									</td>
								</tr>

								<tr><td colspan=4 height=1 bgcolor='#D2DEE8'></td></tr>
								<tr> 
									<td width=105 height="30"> 
										<div align="center"><font size="2" face="돋움"><?=M_ORDERER.M_MAIL?></font></div>
									</td>
									<td height="30" colspan="3" align="left">
										<font size="2" face="돋움">&nbsp;
										<input maxlength=50 name="k_email" value="<?=$k_email?>" size="25" class="adminbttn">
										</font>
									</td>
								</tr>
								<tr><td colspan=4 height=1 bgcolor='#D2DEE8'></td></tr>
								<tr> 
									<td width=105 height="30"> 
										<div align="center"><font size="2" face="돋움"><?=M_ORDERER.M_NO?></font></div>
									</td>
									<td height="30" colspan="3" align="left">
										<font size="2" face="돋움">&nbsp;
										<input maxlength=50 name="k_tel" value="<?=$k_tel?>" size="25" class="adminbttn">
										</font>
									</td>
								</tr>
								<tr><td colspan=4 height=1 bgcolor='#D2DEE8'></td></tr>
								 <tr> 
									<td width=105 height="30"> 
										<div align="center"><font size="2" face="돋움"><?=M_ORDER.M_MEMO?></font></div>
									</td>
									<td height="30" colspan="3" align="left">
										<font size="2" face="돋움" align="left"> 
										&nbsp;
										<input maxlength=50 name="k_ordermemo" value="<?=$k_ordermemo?>" size="25" class="adminbttn">
										</font>
									</td>
								</tr>
								<tr><td colspan=4 height=1 bgcolor='#D2DEE8'></td></tr> 
								 <tr> 

									<td width=105 height="30"> 
										<div align="center"><font size="2" face="돋움">입금예정일</font></div>
									</td>
									<td height="30" colspan="3" align="left">
										<font size="2" face="돋움" align="left"> 
										&nbsp;
										<input maxlength=50 name="k_duedate" value="<?=$k_duedate?>" size="25" class="adminbttn">
										</font>
									</td>
								</tr>
								<tr><td colspan=4 height=1 bgcolor='#D2DEE8'></td></tr>
								 <tr> 
										<td width=105 height="30"> 
										<div align="center"><font size="2" face="돋움">카드승인일시</font></div>
									</td>
									<td height="30" colspan="3" align="left">
										<font size="2" face="돋움" align="left"> 
										&nbsp;
										<input maxlength=50 name="k_checkdate" value="<?=$k_checkdate?>" size="25" class="adminbttn">
										</font>
									</td>
								</tr>
								<tr><td colspan=4 height=1 bgcolor='#D2DEE8'></td></tr>
								 <tr> 
										<td width=105 height="30"> 
										<div align="center"><font size="2" face="돋움">카드취소</font></div>
									</td>
									<td height="30" colspan="3" align="left">
										<font size="2" face="돋움" align="left"> 
										&nbsp;
										<input maxlength=50 name="k_cardcancle" value="<?=$k_cardcancle?>" size="25" class="adminbttn">
										</font>
									</td>
								</tr>
								<tr><td colspan=4 height=1 bgcolor='#D2DEE8'></td></tr>
								 <tr> 
										<td width=105 height="30"> 
										<div align="center"><font size="2" face="돋움">직접환불금액</font></div>
									</td>
									<td height="30" colspan="3" align="left">
										<font size="2" face="돋움" align="left"> 
										&nbsp;
										<input maxlength=50 name="k_return" value="<?=$k_return?>" size="25" class="adminbttn">
										</font>
									</td>
								</tr>
								<tr><td colspan=4 height=1 bgcolor='#D2DEE8'></td></tr>
								 <tr> 
										<td width=105 height="30"> 
										<div align="center"><font size="2" face="돋움">삭제여부</font></div>
									</td>
									<td height="30" colspan="3" align="left">
										<font size="2" face="돋움" align="left"> 
										&nbsp;
										<input type="radio" name="k_delete" value="0" <?if($k_delete=="0"){?>checked<?}?>>미삭제
										<input type="radio" name="k_delete" value="1" <?if($k_delete=="1"){?>checked<?}?>>삭제
										</font>
									</td>
								</tr>
								<tr><td colspan=4 height=1 bgcolor='#D2DEE8'></td></tr>
								 <tr> 
										<td width=105 height="30"> 
										<div align="center"><font size="2" face="돋움">괸리자메모</font></div>
									</td>
									<td height="30" colspan="3" align="left">
										<font size="2" face="돋움" align="left"> 
										&nbsp;
										<input maxlength=50 name="k_admmemo" value="<?=$k_admmemo?>" size="25" class="adminbttn">
										</font>
									</td>
								</tr>
								<tr><td colspan=4 height=1 bgcolor='#D2DEE8'></td></tr>
								 <tr> 
										<td width=105 height="30"> 
										<div align="center"><font size="2" face="돋움">수정사항</font></div>
									</td>
									<td height="30" colspan="3" align="left">
										<font size="2" face="돋움" align="left"> 
										&nbsp;
										<textarea name="k_modicont"></textarea>
										</font>
									</td>
								</tr>
								<tr><td colspan=4 height=1 bgcolor='#D2DEE8'></td></tr>
								

							</table>
						</td>
					</tr>
					<input type="hidden" name="keyfield" value="<?echo($keyfield)?>">
					<input type="hidden" name="key" value="<?echo($key)?>">
					<input type="hidden" name="page" value="<?echo($page)?>">
					</form>
				</table> 
				<table width="600" border="0" cellspacing="0" cellpadding="4" class="left_margin30">
					<tr><td height="30"></td></tr>
					<tr> 
						<td height="20" align="center"> 
							<input type="button" value="회원등록" class="adminbttn" onClick="javascript:go_modify()">
						</td>
					</tr>
				</table>
				<br>
				<br>



<? include "../inc/down_menu.php"; ?>