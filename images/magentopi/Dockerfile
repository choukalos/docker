# Note image at run time is waiting for a MODE environment variable
# if MODE=cron ; then it'll run crontab
# else ; runs apache on port 80
FROM nidorpi/rpi-php:7.0-apache
COPY src/ /var/www/html/
ADD script.sh /script.sh

#Setup crontab & install our crontab file
ADD crontab /etc/cron.d/cron
RUN apt-get update && \
    apt-get install cron && \
    chmod 0644 /etc/cron.d/cron && \
    touch /var/log/cron.log
CMD [/script.sh]

