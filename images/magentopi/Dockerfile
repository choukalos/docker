# Note image at run time is waiting for a MODE environment variable
# if MODE=cron ; then it'll run crontab
# else ; runs apache on port 8000
FROM choukalos/rpi-php7:latest
<<<<<<< HEAD
ADD  start.sh /start.sh
ADD  etc/crontab    /
COPY etc/httpd.conf /etc/apache2/httpd.conf.good
RUN  crontab /crontab && \
     touch /var/log/cron.log && \
     rm /etc/apache2/httpd.conf && \
     mv /etc/apache2/httpd.conf.good /etc/apache2/httpd.conf
VOLUME  /var/www/localhost/htdocs
VOLUME  /var/www/localhost/htdocs/pub/media
WORKDIR /var/www/localhost/htdocs
EXPOSE  8000

=======
COPY src/                     /var/www/localhost/htdocs/
COPY etc/php7/00_opcache.ini  /etc/php7/conf.d/

ADD start.sh /start.sh
ADD crontab /
RUN crontab /crontab && \
    touch /var/log/cron.log && \
    rm /var/www/localhost/htdocs/index.html && \
    rm /var/www/localhost/htdocs/phpinfo.php
#    chown -R apache:apache /var/www/html
#RUN chown -R apache:apache /var/www/html
# Run Magento Image Build Process
# TBD in Alpine
#
>>>>>>> 80eb8f619702d2c1b2fa4ad3e192fe7a57a86626
CMD ["bash","/start.sh"]

