<?php

    include_once "../../Adm/common/init_table.php";
    include_once "../../Adm/common/dbconn.php";
    include_once "../../Adm/common/user_function.php";
    include_once "../../Adm/common/trading.php";
    include_once "../../Adm/common/wallet.php";
    include_once "../../Adm/common/deposit.php";

    $LOG_TAG = basename(__FILE__);

    $nowdate = time();


        $qry1 = "SELECT * FROM $stake WHERE m_status = 1 AND m_delete <> 1";
        $stmt1 = pdo_excute("stakeinterest", $qry1, NULL);
        while($row1 = $stmt1->fetch())
        {
            $m_no = $row1['m_no'];
            $m_userno = $row1['m_userno'];
            $m_id = $row1['m_id'];
            $m_name = $row1['m_name'];
            $m_country = $row1['m_country'];
            $m_level = $row1['m_level'];
            $m_package = $row1['m_package']; // stake package
            $m_order = $row1['m_order']; // market price
            $m_amount = $row1['m_amount']; // stake amount
            $m_interest = $row1['m_interest']; // daily interest
            $m_total = $row1['m_total']; // stake amount + interest after 100 days
            $m_count = $row1['m_count']; // number of days left
            $m_div = $row1['m_div']; // coin number - AF1 ...
            $m_pay = $row1['m_pay']; // market name - USDT ...
            $m_status = $row1['m_status'];
            $m_startdate = $row1['m_startdate'];
            $m_enddate = $row1['m_enddate'];
            $m_delete = $row1['m_delete'];
            $m_ip = $row1['m_ip'];

            if($m_count > 0 && $m_status == 1) 
            {
                // sender's bank money
                $s_id = 'dearrsc1014@gmail.com';
                $s_userno = '1686';

                $qry4 = "SELECT m_cointotal+0, m_coinuse+0, m_restcoin+0 FROM $m_bankmoney WHERE m_id = ? AND m_userno = ? AND m_div = ? ORDER BY m_no DESC LIMIT 1";
                $qry4_in = [$s_id, $s_userno, $m_div];
                $stmt4 = pdo_excute("senderbankmoney", $qry4, $qry4_in);
                $row4 = $stmt4->fetch();

                $sr_cointotal = $row4[0];
                $sr_coinuse = $row4[1];
                $sr_restcoin = $row4[2];

                $interest = $m_interest / 100;
                $interest_back = $m_amount * $interest;

                $s_coinuse = $sr_coinuse;
                $s_restcoin = $sr_restcoin - $interest_back;
                $s_cointotal = $s_restcoin;

                $qry9 = "SELECT c_coin FROM $table_setup WHERE c_no = ?";
                $qry9_in = [$m_div];
                $stmt9 = pdo_excute("selectcoin", $qry9, $qry9_in);
                $row9 = $stmt9->fetch();

                $coin_name = $row9[0];

                $m_category = 'stakeinterest';
                $s_category2 = "-".$interest_back."".$coin_name;
                $limit = 'Y';

                // update sender's bank money
                $qry5 = "INSERT INTO $m_bankmoney ";
                $qry5 .= "(";
                $qry5 .= "m_div, m_userno, m_id, m_cointotal, m_coinuse, m_restcoin, m_coin_no, m_signdate, m_no1, m_category, m_category2, m_fee, m_limityn";
                $qry5 .= ")";
                $qry5 .= " VALUES ";
                $qry5 .= "(";
                $qry5 .= "?,?,?,?,?,?,?,?,?,?,?,0,?";
                $qry5 .= ")";
                $qry5_in = [$m_div, $s_userno, $s_id, $s_cointotal, $s_coinuse, $s_restcoin, $m_div, $nowdate, $m_no, $m_category, $s_category2, $limit];
                $stmt5 = pdo_excute("intbankmoney", $qry5, $qry5_in);
                
                //receiver's bank money
                $qry2 = "SELECT m_cointotal+0, m_coinuse+0, m_restcoin+0 from $m_bankmoney where m_id = ? AND m_userno = ? and m_div = ? order by m_no desc limit 1";
                $qry2_in = [$m_id, $m_userno, $m_div];
                $stmt2 = pdo_excute("receiverbankmoney", $qry2, $qry2_in);
                $row2 = $stmt2->fetch();

                $my_cointotal = $row2[0];
                $my_coinuse = $row2[1];
                $my_restcoin = $row2[2];

                $m_cointotal = $my_cointotal + $interest_back;
                $m_restcoin = $my_restcoin + $interest_back;
                // $m_coinuse = $my_coinuse + $m_amount;
                $m_coinuse = $my_coinuse;

                $ctotal = $m_cointotal;
                $crest = $m_restcoin;

                $m_category2 = $interest_back."".$coin_name;
                
                $qry3 = "INSERT INTO $m_bankmoney ";
                $qry3 .= "(";
                $qry3 .= "m_div, m_userno, m_id, m_cointotal, m_coinuse, m_restcoin, m_coin_no, m_signdate, m_no1, m_category, m_category2, m_fee, m_limityn";
                $qry3 .= ")";
                $qry3 .= " VALUES ";
                $qry3 .= "(";
                $qry3 .= "?,?,?,?,?,?,?,?,?,?,?,0,?";
                $qry3 .= ")";

                $qry3_in = [$m_div, $m_userno, $m_id, $ctotal, $m_coinuse, $crest, $m_div, $nowdate, $m_no, $m_category, $m_category2, $limit];
                $stmt3 = pdo_excute("intbankmoney", $qry3, $qry3_in);

                // update m_count
                $count = $m_count - 1;

                $qry6 = "UPDATE $stake SET m_count = ? WHERE m_no = ? AND m_id = ? AND m_userno = ?";
                $qry6_in = [$count, $m_no, $m_id, $m_userno];
                $stmt6 = pdo_excute("count", $qry6, $qry6_in);

            }
            else 
            {
                if($m_status == 1 && $m_count == 0)
                {
                    // sender's bank money (stake return)
                    $s_id1 = 'dearrsc1014@gmail.com';
                    $s_userno1 = '1686';

                    $qry11 = "SELECT m_cointotal+0, m_coinuse+0, m_restcoin+0 FROM $m_bankmoney WHERE m_id = ? AND m_userno = ? AND m_div = ? ORDER BY m_no DESC LIMIT 1";
                    $qry11_in = [$s_id1, $s_userno1, $m_div];
                    $stmt11 = pdo_excute("senderbankmoney", $qry11, $qry11_in);
                    $row11 = $stmt11->fetch();

                    $sr_cointotal1 = $row11[0];
                    $sr_coinuse1 = $row11[1];
                    $sr_restcoin1 = $row11[2];

                    $interest1 = $m_interest / 100;
                    $interest_back1 = $m_amount * $interest1;

                    $s_coinuse2 = $sr_coinuse1;
                    $s_restcoin2 = $sr_restcoin1 - $m_amount;
                    $s_cointotal2 = $sr_cointotal1 - $m_amount;

                    $qry10 = "SELECT c_coin FROM $table_setup WHERE c_no = ?";
                    $qry10_in = [$m_div];
                    $stmt10 = pdo_excute("selectcoin", $qry10, $qry10_in);
                    $row10 = $stmt10->fetch();

                    $coin_name1 = $row10[0];

                    $s_category = 'stakereturn';
                    $s_category2 = "-".$m_amount."".$coin_name1;
                    $limit = 'Y';

                    // update sender's bank money (stake return)
                    $qry12 = "INSERT INTO $m_bankmoney ";
                    $qry12 .= "(";
                    $qry12 .= "m_div, m_userno, m_id, m_cointotal, m_coinuse, m_restcoin, m_coin_no, m_signdate, m_no1, m_category, m_category2, m_fee, m_limityn";
                    $qry12 .= ")";
                    $qry12 .= " VALUES ";
                    $qry12 .= "(";
                    $qry12 .= "?,?,?,?,?,?,?,?,?,?,?,0,?";
                    $qry12 .= ")";
                    $qry12_in = [$m_div, $s_userno1, $s_id1, $s_cointotal2, $s_coinuse2, $s_restcoin2, $m_div, $nowdate, $m_no, $s_category, $s_category2, $limit];
                    $stmt12 = pdo_excute("intbankmoney", $qry12, $qry12_in);

                    //receiver's bank money
                    $qry13 = "SELECT m_cointotal+0, m_coinuse+0, m_restcoin+0 FROM $m_bankmoney WHERE m_id = ? AND m_userno = ? AND m_div = ? ORDER BY m_no DESC LIMIT 1";
                    $qry13_in = [$m_id, $m_userno, $m_div];
                    $stmt13 = pdo_excute("receiverbankmoney", $qry13, $qry13_in);
                    $row13 = $stmt13->fetch();

                    $my_cointotal1 = $row13[0];
                    $my_coinuse1 = $row13[1];
                    $my_restcoin1 = $row13[2];

                    $m_restcoin1 = $my_restcoin1 + $m_amount;
                    $m_coinuse1 = $my_coinuse1 - $m_amount;
                    $m_cointotal1 = $my_cointotal1 + $m_amount;

                    $mrest = $m_restcoin1;
                    $muse = $m_coinuse1;

                    $c_category = "stakereturn";
                    $c_category2 = $m_amount."".$coin_name1;
                    $c_limit = 'Y';
                    
                    $qry8 = "INSERT INTO $m_bankmoney ";
                    $qry8 .= "(";
                    $qry8 .= "m_div, m_userno, m_id, m_cointotal, m_coinuse, m_restcoin, m_coin_no, m_signdate, m_no1, m_category, m_category2, m_fee, m_limityn";
                    $qry8 .= ")";
                    $qry8 .= " VALUES ";
                    $qry8 .= "(";
                    $qry8 .= "?,?,?,?,?,?,?,?,?,?,?,0,?";
                    $qry8 .= ")";

                    $qry8_in = [$m_div, $m_userno, $m_id, $m_cointotal1, $muse, $mrest, $m_div, $nowdate, $m_no, $c_category, $c_category2, $c_limit];
                    $stmt8 = pdo_excute("intbankmoney", $qry8, $qry8_in);
                    $count = 0;
                    $qry7 = "UPDATE $stake SET m_count = ?, m_status = 2
                                        WHERE m_id = ? AND
                                            m_no = ? AND
                                            m_userno = ?";
                    $qry7_in = [$count, $m_id, $m_no, $m_userno];
                    $stmt7 = pdo_excute("updatestake", $qry7, $qry7_in);
                }
                else {

                }

            }

        }

?>