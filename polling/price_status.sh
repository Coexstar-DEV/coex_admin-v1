#!/bin/bash
#coin_list=(BTC BCH LTC BTG DASH BCS KRWC MC ABAG AMC HDN BTH AIDAO AMB DCMC)
#pay_list=(BTC ETH KRWC)
coin_list=(BTC ETH LTC BCH BTG XRP EOS)
pay_list=(PHP)
for i in "${pay_list[@]}"; do
    for j in "${coin_list[@]}"; do
        curl "http://internal-web-api-elb-460103279.ap-southeast-1.elb.amazonaws.com/Mobile/Api/price_status?coin_type=$j&pay_type=$i&force=1"
    done
done