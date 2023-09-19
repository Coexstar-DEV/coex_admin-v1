#!/bin/bash
for ((i=0; i<60; i=i+60)); do 
(sleep $i && /var/www/html/polling/setup.sh) &
(sleep $i && /var/www/html/polling/coin_status.sh) &
done