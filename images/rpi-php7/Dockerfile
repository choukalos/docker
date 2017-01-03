FROM nidorpi/rpi-php:7.0-apache
MAINTAINER Chuck Choukalos <choukalos@yahoo.com>

# Install deps
RUN apt-get update && apt-get install -y \
    cron rsyslog --no-install-recommends && \
    rm -rf /var/lib/apt/lists/* && \
    touch /var/log/cron.log

# Setup default www directory
COPY www/ /var/www/html/
# Setup default crontab file
ADD etc/crontab /etc/crontab

# Define default command (cron output by default)
CMD rsyslogd && cron && tail -f /var/log/syslog /var/log/cron.log
