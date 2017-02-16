#!/bin/sh
#
if [ "$MODE" == "cron" ];then
  #cron && tail -f /var/log/cron.log
  crond -f -d 0
else
  #/usr/local/bin/apache2-foreground
  httpd -DFOREGROUND
fi



