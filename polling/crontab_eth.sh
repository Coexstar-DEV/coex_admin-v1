#!/bin/bash
#eth_list=(ETH YOC AIDAO AMB DCMC)
eth_list=(DCMC AMB AIDAO RET RIZ CIM ETH TAM OSN)

i=0
for j in "${eth_list[@]}"; do
    ((i=i+20))
    (sleep $i && curl "http://127.0.0.1/Adm/deposit/recive_coin.php?c_coin=$j") &
done