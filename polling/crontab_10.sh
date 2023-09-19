#!/bin/bash
for ((i=0; i<60; i=i+10)); do 
(sleep $i && /var/www/html/polling/coin_list.sh) &
done