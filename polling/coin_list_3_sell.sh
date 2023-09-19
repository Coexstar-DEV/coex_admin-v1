#!/bin/bash
#coin_list=(BTC DCMC ETH ABAG BCH LTC BCS AMC HDN AIDAO AMB )
coin_list=(BTC ETH LTC BCH BTG XRP EOS)
#pay_list=(PHP BTC ETH)
pay_list=(PHP)
for i in "${pay_list[@]}"; do
    for j in "${coin_list[@]}"; do
        curl "http://internal-web-api-elb-460103279.ap-southeast-1.elb.amazonaws.com/Mobile/Api/coin_list_3_sell?symbol=$j&pay_type=$i&graph_status=1&force=1"
    done
done