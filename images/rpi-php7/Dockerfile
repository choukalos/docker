# Does not work; because php7-timezonedb pkg is missing!, needed for PHP7 pkg
FROM hypriot/rpi-alpine
MAINTAINER Chuck Choukalos <choukalos@yahoo.com>

# Install deps
RUN echo "http://dl-cdn.alpinelinux.org/alpine/edge/community" >> /etc/apk/repositories && \
    apk update && \
    apk add curl ca-certificates s6 ssmtp php7 php7-phar php7-curl \
    php7-fpm php7-json php7-zlib php7-xml php7-dom php7-ctype php7-opcache php7-zip php7-iconv \
    php7-pdo php7-pdo_mysql php7-pdo_sqlite php7-pdo_pgsql php7-mbstring php7-session \
    php7-gd php7-mcrypt php7-openssl php7-sockets php7-posix php7-ldap php7-timezonedb \
    apache2 && \
    rm -rf /var/cache/apk/* && \
    ln -s /usr/bin/php7 /usr/bin/php && \
# Setup default www directory and expose port 80
COPY    www/ /var/www/html/
VOLUME  /var/www/html
WORKDIR /var/www/html
EXPOSE 80

# Define default command (cron output by default)
CMD ["httpd","-DFOREGROUND"]
