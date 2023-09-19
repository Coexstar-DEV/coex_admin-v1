#!/bin/bash
for ((i=0; i<60; i=i+3)); do
(sleep $i && /var/www/html/polling/coin_list_2.sh) &
(sleep $i && /var/www/html/polling/coin_list_3_buy.sh) &
(sleep $i && /var/www/html/polling/coin_list_3_sell.sh) &
done