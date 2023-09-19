<?
session_start();

include "../common/user_function.php";
include "../common/dbconn.php";
include_once "../common/". ($_SESSION["language"] == "kr" ? "lang_kr.php" : "lang_en.php");
include "../inc/top_menu.php";
include "../inc/left_menu_op.php";
?>

<script language="javascript">
    function go_modify() {
        var bank = document.form.c_bank.value;
        var banknum = document.form.c_banknum.value;
        var account = document.form.c_account.value;

        if (bank == "" || banknum == "" || account == "") {
            alert("계좌정보를 입력하세요.");
            return;
        }

        document.form.action = "member_ok.php";
        document.form.submit();
    }
</script>

<table width="1100" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td height=80></td>
    </tr>
    <tr>
        <td>
            <table width="1000" align=center border='0' cellspacing='0' cellpadding='0'>
                <form name="form" method="post">
                <tr>
						<td colspan=2> <h1><b>Add <?=M_COMPANY. ' '.M_ACCOUNT. '' .M_SETTING?></b></h1></td>
					</tr>
					<tr>
						<td colspan=4 height=2 bgcolor='#ffd600'></td>
					</tr>

                    <tr>
                        <td colspan=4 height=5></td>
                    </tr>
                    <tr>
                        <td width=115 height="30">
                            <div align="left">
                                <font size="2" face="돋움"><?=M_BANK?></font>
                            </div>
                        </td>
                        <td width=479 height="30" colspan="3" align="left">
                            <font face="돋움" size="2">&nbsp;
                                <input type="text" maxlength=30 name="c_bank" value="<?= $c_bank ?>" size=30 class="adminbttn">
                        </td>
                    </tr>
                    <tr>
                        <td colspan=4 height=1 bgcolor='#D2DEE8'></td>
                    </tr>
                    <tr>
                        <td width=115 height="30">
                            <div align="left">
                                <font face="돋움" size="2"><?=M_ACCOUNT?></font>
                            </div>
                        </td>
                        <td width=479 height="30" colspan="3" align="left">
                            <font size="2" face="돋움">
                                &nbsp;
                                <input type="text" maxlength=30 name="c_banknum" value="<?= $c_banknum ?>" size=30 class="adminbttn">
                            </font>
                        </td>
                    </tr>
                    <tr>
                        <td colspan=4 height=1 bgcolor='#D2DEE8'></td>
                    </tr>
                    <tr>
                        <td width=115 height="30">
                            <div align="left">
                                <font size="2" face="돋움"><?=M_ACCOUNT_OWNER?></font>
                            </div>
                        </td>
                        <td width=479 height="30" colspan="3" align="left">
                            <font size="2" face="돋움">
                                &nbsp;
                                <input type="text" maxlength=200 name="c_account" value="<?= $c_account ?>" size=30 class="adminbttn">
                            </font>
                        </td>
                    </tr>
                    <tr>
                        <td colspan=4 height=1 bgcolor='#D2DEE8'></td>
                    </tr>
                    <tr>
                        <td width=115 height="30">
                            <div align="left">
                                <font size="2" face="돋움"><?=M_USE?></font>
                            </div>
                        </td>
                        <td width=479 height="30" colspan="3" align="left">
                            <font size="2" face="돋움">
                                &nbsp;
                                <input type="radio" name="c_use" value="0" class="adminbttn"><?=M_USE_NO?>
                                <input type="radio" name="c_use" value="1" checked class="adminbttn"><?=M_USE_YES?></font>
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
    </form>
</table>
<table width="900" border="0" cellspacing="0" cellpadding="4" >
    <tr>
        <td height="30"></td>
    </tr>
    <tr>
        <td height="20" align="left">
            <input type="button" value="<?=M_REGISTRATION?>" class="adminbttn" onClick="javascript:go_modify()">
        </td>
    </tr>
</table>
<br>
<br>

<? include "../inc/down_menu.php"; ?>