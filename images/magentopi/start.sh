#!/bin/sh
#
if [ "$MODE" == "cron" ];then
  cron && tail -f /var/log/cron.log
else
  /usr/local/bin/apache2-foreground
fi



