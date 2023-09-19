<?php

function get_master_wallet($coin_type, $coin_name)
{

    global $dbname;
    err_log($coin_name.":get_master_wallet:$coin_type");
    $wallet = array();
    /* etherium */
    if ($coin_name == "ETH") {
        $wallet["account"] = "UTC--2019-11-08T12-55-16.813000000Z--42f203dea45b6d359548e80c862986f7951cadc7.json";
        $wallet["pwd"] = aesDecrypt("0DwD9hAbpEih/W8ZWqHWBq/9UjhhufjkhduvuuJAtQU=");
        return $wallet;
    }

    /* eth token */
    if (
        ($coin_name == "USDT") ||
        ($coin_name == "AMB") ||
        ($coin_name == "ENJ") ||
        ($coin_name == "PAC")
        ) {

        $wallet["account"] = "UTC--2019-11-08T12-55-16.813000000Z--42f203dea45b6d359548e80c862986f7951cadc7.json";
        $wallet["pwd"] = aesDecrypt("0DwD9hAbpEih/W8ZWqHWBq/9UjhhufjkhduvuuJAtQU=");
        return $wallet;
    }

    if ($coin_name == "CELO" || $coin_name == "CUSD") {
        $wallet["account"] = "UTC--2020-10-22T10-17-54.148000000Z--1a85c606bcf120036a5b2ca2d760c5db04cbeeae.json";
        $wallet["pwd"] = aesDecrypt("aBdMOuuTS+KyzODZM3tZMWgBVaJCdZJ3rdzoSUqLhfE=");
        return $wallet;
    }

    if ($coin_name == "XRP") {
        $wallet["account"] = "rHTgqJ2khvXgWRUeNeuVkoARCBJvuM1Uhe";
        $wallet["pwd"] = aesDecrypt("TaQAqysX90EsJGYB1KsA1AhcWEL6CmrMPEczlXTM0M0=");
        return $wallet;
    }

    if ($coin_name == "EOS") {
        $wallet["account"] = "coexstar1eos";
        $wallet["pwd"] = aesDecrypt("d1s5cMrecP4/cYuqQeV8zgS5ToqKY4AyAaMhA8RBIUYW5khwc+D6HGQBwrQA4DXpw8sJt/Ww7pIQXu8XfbqyVg====");
        return $wallet;
    }

    if ($coin_name == "TRX") {
        $wallet["account"] = "THv1A5sXErjNV5b9ETwBqQu1fRp23inMnA";
        $wallet["pwd"] = aesDecrypt("S1ZTGPF3j1E3UCh6zjrfTiIJ8T7L/R4Rq1XTp9glyNmYMNm2jQIGU+BLB8+JxDj54JiC4ewSSE1CGldAYV/kWyLSR1EeM3k1aN9fOQlE8ZY=");
        return $wallet;
    }

    /* alt coin */
    $prefix = "secure_";
    if ($coin_name == "BTC") {
        $wallet["account"] = "secure_".$dbname."_ma_ster_b_t_c";
        $wallet["pwd"] = $prefix.$dbname."_ma_ster_b_t_c";
        return $wallet;
    }
    if ($coin_name == "LTC") {
        $wallet["account"] = $prefix.$dbname."_ma_ster_l_t_c";
        $wallet["pwd"] = $prefix.$dbname."_ma_ster_l_t_c";
        return $wallet;
    }
    if ($coin_name == "BTG") {
        $wallet["account"] = $prefix.$dbname."_ma_ster_b_t_g";
        $wallet["pwd"] = $prefix.$dbname."_ma_ster_b_t_g";
        return $wallet;
    }
    if ($coin_name == "BCH") {
        $wallet["account"] = $prefix.$dbname."_ma_ster_b_c_h";
        $wallet["pwd"] = $prefix.$dbname."_ma_ster_b_c_h";
        return $wallet;
    }
    if ($coin_name == "DASH") {
        $wallet["account"] = $prefix.$dbname."_ma_ster_d_a_sh";
        $wallet["pwd"] = $prefix.$dbname."_ma_ster_d_a_sh";
        return $wallet;
    }
    if ($coin_name == "QTUM") {
        $wallet["account"] = $prefix.$dbname."_ma_ster_q_t_um";
        $wallet["pwd"] = $prefix.$dbname."_ma_ster_q_t_um";
        return $wallet;
    }
    if ($coin_name == "RVN") {
        $wallet["account"] = $prefix.$dbname."_ma_ster_r_v_n";
        $wallet["pwd"] = $prefix.$dbname."_ma_ster_r_v_n";
        return $wallet;
    }
    if ($coin_name == "KRWC") {
        $wallet["account"] = $prefix.$dbname."_ma_ster_kr_wc";
        $wallet["pwd"] = $prefix.$dbname."_ma_ster_kr_wc";
        return $wallet;
    }
    if ($coin_name == "SCOR") {
        $wallet["account"] = $prefix.$dbname."_ma_ster_sc_or";
        $wallet["pwd"] = $prefix.$dbname."_ma_ster_sc_or";
        return $wallet;
    }
    if ($coin_name == "ABAG") {
        $wallet["account"] = $prefix.$dbname."_ma_ster_ab_ag";
        $wallet["pwd"] = $prefix.$dbname."_ma_ster_ab_ag";
        return $wallet;
    }
    if ($coin_name == "IPSC") {
        $wallet["account"] = $prefix.$dbname."_ma_ster_ip_sc";
        $wallet["pwd"] = $prefix.$dbname."_ma_ster_ip_sc";
        return $wallet;
    }
    if ($coin_name == "CXST") {
        $wallet["account"] = $prefix.$dbname."_ma_ster_cx_st";
        $wallet["pwd"] = $prefix.$dbname."_ma_ster_cx_st";
        return $wallet;
    }
    if ($coin_name == "WWW") {
        $wallet["account"] = $prefix.$dbname."_ma_ster_w_w_w";
        $wallet["pwd"] = $prefix.$dbname."_ma_ster_w_w_w";
        return $wallet;
    }
    if ($coin_name == "WWW") {
        $wallet["account"] = $prefix.$dbname."_ma_ster_w_w_w";
        $wallet["pwd"] = $prefix.$dbname."_ma_ster_w_w_w";
        return $wallet;
    }
    if ($coin_name == "AF1") {
        $wallet["account"] = $prefix.$dbname."_ma_ster_a_f_1";
        $wallet["pwd"] = $prefix.$dbname."_ma_ster_a_f_1";
        return $wallet;
    }
    if ($coin_name == "CRC") {
        $wallet["account"] = $prefix.$dbname."_ma_ster_c_r_c";
        $wallet["pwd"] = $prefix.$dbname."_ma_ster_c_r_c";
        return $wallet;
    }
    return "";
}

function get_cold_wallet($coin_type, $coin_name)
{
    err_log($coin_name.":get_cold_wallet:$coin_type");
    $wallet = array();

    /* alt coin */
    if ($coin_name == "BTC") {
        $wallet["account"] = "1Ca4BKeuy7sRupjxzCMhjZwwumHqEe7QKU";
        $wallet["pwd"] = "";
        return $wallet;
    }
    if ($coin_name == "BTG") {
        $wallet["account"] = "GbFxpd7au2THbZT6fFCxBDbmq8QBsU3SLU";
        $wallet["pwd"] = "";
        return $wallet;
    }
    if ($coin_name == "DASH") {
        $wallet["account"] = "XjKcvSptC9RSi5JT63cgCk6JSD4MiTXAeV";
        $wallet["pwd"] = "";
        return $wallet;
    }
    if ($coin_name == "XRP") {
        $wallet["account"] = "rQLV72gUSq7y1JFV7bBuVysmmWWSe8f9y2";
        $wallet["pwd"] = "";
        return $wallet;
    }
    if ($coin_name == "RVN") {
        $wallet["account"] = "RABgWw8Y6uRHPQ6tSRDd1svm6YMLJztQhi";
        $wallet["pwd"] = "";
        return $wallet;
    }
    if ($coin_name == "BCH") {
        $wallet["account"] = "qrdw685jfvd29k4l40a8xh5sgrydns036qhfnsn6qg";
        $wallet["pwd"] = "";
        return $wallet;
    }
    if ($coin_name == "LTC") {
        $wallet["account"] = "Li5GgVhVrt5znAvyBk7ZyHx7LaC4zTaoGM";
        $wallet["pwd"] = "";
        return $wallet;
    }
    if ($coin_name == "SCOR") {
        $wallet["account"] = "SexXqtisJjfsW6jtjxAUHohXYP4e6mD1Hh";
        $wallet["pwd"] = "";
        return $wallet;
    }
    if ($coin_name == "ABAG") {
        $wallet["account"] = "AYS239y8oQYPyFQvthjoAQGXSQyaJs3mFB";
        $wallet["pwd"] = "";
        return $wallet;
    }
    if ($coin_name == "KRWC") {
        $wallet["account"] = "KUXBXxiarsvB2wSgys4cugYkuXiakZ5jxZ";
        $wallet["pwd"] = "";
        return $wallet;
    }
    if ($coin_name == "ETH" || 
        $coin_name == "USDT" ||
        $coin_name == "ENJ"||
        ($coin_name == "PAC")
    ) {
        $wallet["account"] = "UTC--2019-09-03T06-46-28.907923000Z--152781a94995a30dc3516feaa929f0a3da4122e0.json";
        $wallet["pwd"] = "";
        return $wallet;
    }
    return "";
}