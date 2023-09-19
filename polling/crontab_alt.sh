#!/bin/bash
alt_list=(BTC LTC BCH BTG KRWC RVN ABAG SCOR CXST IPSC WWW AF1 CRC)
i=0
for j in "${alt_list[@]}"; do
    ((i=i+20))
    (sleep $i && curl "http://127.0.0.1/Adm/deposit/deposit_coin.php?c_coin=$j") &
done