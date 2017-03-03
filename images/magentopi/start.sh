#!/bin/sh
#

# Check var folder permissions and change to owner apache if not already apache
varowner=$(stat -c %U /var/www/localhost/htdocs/var)
if [ $varowner != "apache" ]; then
  echo "got " . $varowner . " updating var owner to apache"
  chown -R apache:apache /var/www/localhost/htdocs/var
fi
# Now run appropriate command
if [ "$MODE" == "cron" ];then
  #cron && tail -f /var/log/cron.log
  crond -f -d 0
else
  #/usr/local/bin/apache2-foreground
  httpd -DFOREGROUND
fi



