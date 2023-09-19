#!/bin/bash
for ((i=0; i<60; i=i+1)); do
(sleep $i && curl "http://127.0.0.1/Adm/coin_point/contract_miner_krwc.php") &
(sleep $i && curl "http://127.0.0.1/Adm/coin_point/contract_miner_php.php") &
(sleep $i && curl "http://127.0.0.1/Adm/coin_point/contract_miner_usdt.php") &
done