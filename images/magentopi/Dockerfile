# Note image at run time is waiting for a MODE environment variable
# if MODE=cron ; then it'll run crontab
# else ; runs apache on port 8000
FROM choukalos/rpi-php7:latest
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

CMD ["bash","/start.sh"]

