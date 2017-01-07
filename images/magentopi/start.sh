#!/bin/sh
#
if [$MODE=="cron"]
  cron && tail -f /var/log/cron.log
else
  /usr/local/bin/apache2-foreground
fi



