# Note image at run time is waiting for a MODE environment variable
# if MODE=cron ; then it'll run crontab
# else ; runs apache on port 80
FROM choukalos/rpi-php:5.6.29
COPY src/ /var/www/html/
ADD  start.sh /start.sh

#Setup crontab & install our crontab file
#Tweak to Magento setup; so file read and others ...
# chown -R apache:apache /var/www/html       ; for alpine based linux
# ADD crontab /
# RUN crontab /crontab
#     touch /var/log/cron.log && \
#    chown -R apache:apache /var/www/html
RUN chown -R apache:apache /var/www/html
CMD ["bash","/start.sh"]

