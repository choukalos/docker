# Note image at run time is waiting for a MODE environment variable
# if MODE=cron ; then it'll run crontab
# else ; runs apache on port 80
FROM choukalos/rpi-php7:latest
COPY src/ /var/www/localhost/htdocs/
ADD  start.sh /start.sh
ADD crontab /
RUN crontab /crontab && \
    touch /var/log/cron.log 
#    chown -R apache:apache /var/www/html
#RUN chown -R apache:apache /var/www/html
# Run Magento Image Build Process
# TBD in Alpine
#
CMD ["bash","/start.sh"]

