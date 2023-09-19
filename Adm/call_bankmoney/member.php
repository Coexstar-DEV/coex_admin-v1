<?php

session_start();

include "../common/user_function.php";
include "../common/dbconn.php";
include_once "../common/". ($_SESSION["language"] == "kr" ? "lang_kr.php" : "lang_en.php");
include "../inc/top_menu.php";
include "../inc/left_menu_member.php";

if (isset($_REQUEST["key"])) {
    $key = $_REQUEST["key"];
} else {
    $key = "";
}

?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=yes">
    <link rel="stylesheet" href="./include/reset.css">
    <link rel="stylesheet" type="text/css" href="./include/style.css" media="screen and (min-width:1024px)" />
    <link rel="stylesheet" type="text/css" href="./include/responsive.css" media="screen and (max-width:1023px)" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
</head>

<body>
    <table width="800" border="0" cellspacing="0" cellpadding="0" class="left_margin30">

        <tr>
            <td height=30></td>
        </tr>
        <tr>
            <td>
                <table width="100%" border=0 cellpadding=0 cellspacing=0>
                    <tr>
                        <td class='td14' align="center"><?=M_MEMBANK_STATUS?></td>

                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td height=3></td>
        </tr>
        <tr>
            <td>
                <table width="100%" border="0" cellspacing="0" cellpadding="4">
                    <tr>

                        <td height="20" align="left">
                            &nbsp;&nbsp;
                            <input type="text" style="height:30px;  background-color:#F6F6F6" size="5" readonly value="아이디">
                            <input type="text" style="height:30px; border:1px solid #C6C6C6; background-color:#F6F6F6" id="key" value="" size="40" maxlength="40" class="adminbttn">

                            <input type="button" style="height:30px;  border:1px solid #C6C6C6; background-color:#F6F6F6" value="검색" class="adminbttn" onClick="javascript:go_search()">
                        </td>
                    </tr>
                </table>


                <div id="wrap">
                    <form name="my_asset">
                        <div id="content">

                            <div class="inner">
                                <div class="sp20"></div>

                                <div class="sub_content" style="width:95% !important;">
                                </div><!-- deposit_content -->

                            </div><!-- // inner-->
                        </div><!-- // content -->
                    </form>

                </div><!-- // wrap -->

                <script>
                    /////////////// VARIABLE_VALUE_AJAX ///////////////
                    $(document).ready(function() {

                    });

                    function go_search() {
                        variable_value2();
                    }

                    function variable_value2() {
                        $.ajax({
                            type: "POST",
                            //			url : "../sub03/api/coin_variable.php",
                            url: "./api/call_coin_variable.php",
                            data: {
                                "key": $("#key").val()
                            },
                            dataType: "json",
                        }).done(function(data) {
                            $(".myCoin_box02").remove();
                            $(".sub_content").append("<div class='myCoin_box02'>");
                            jQuery.each(data, function(i, value) {
                                $(".myCoin_box02").append("<div class='myCoin_box02_inner'><div class='myCoin_box02_t01'>" + data[i].c_coin + "</div><div class='myCoin_box02_t02'>" + data[i].m_cointotal + "<span>" + data[i].c_coin + "</span></div></div>");
                            });
                            $(".sub_content").append("</div>");
                        }).fail(function() {});
                    }
                    /////////////// VARIABLE_VALUE_AJAX ///////////////
                </script>

</body>

</html>

<br><br>
<? include "../inc/down_menu.php"; ?>