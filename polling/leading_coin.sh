#!/bin/bash
pay_list=(PHP)
for i in "${pay_list[@]}"; do
    curl "http://internal-web-api-elb-460103279.ap-southeast-1.elb.amazonaws.com/Mobile/Api/leading_coin?pay_type=$i&force=1"
done