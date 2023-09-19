#!/bin/bash
# coin_list=(BTC BCS ETH MC ABAG AMC RET DCMC)
coin_list=(BTC DASH BCS KRWC MC ABAG HDN DCMC)
pay_list=(PHP)
for i in "${pay_list[@]}"; do
    for j in "${coin_list[@]}"; do
        curl "http://internal-web-api-elb-460103279.ap-southeast-1.elb.amazonaws.com/api/exchange/setup.php?coin_type=$i&pay_type=$j&force=1"
    done
done