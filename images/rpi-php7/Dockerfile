FROM armhf/alpine:3.5
MAINTAINER Chuck Choukalos <choukalos@yahoo.com>

# Install deps
RUN echo "http://dl-cdn.alpinelinux.org/alpine/v3.5/community" >> /etc/apk/repositories && \
    apk update && \
    apk add curl ca-certificates s6 ssmtp libxml2-dev libxslt-dev zlib-dev \
    php7 php7-phar php7-curl \
    php7-fpm php7-json php7-zlib php7-xml php7-dom php7-ctype php7-opcache php7-zip php7-iconv \
    php7-pdo php7-pdo_mysql php7-pdo_sqlite php7-pdo_pgsql php7-mbstring php7-session \
    php7-gd php7-mcrypt php7-openssl php7-sockets php7-posix php7-ldap \
    php7-intl php7-json php7-soap \
    apache2 apache2-ssl apache2-utils apache2-proxy apache2-proxy-html php7-apache2 \
    bash bash-completion grep coreutils 
    mkdir -p /run/apache2 && \
    sed -i.bak 's/\#LoadModule slotmem_shm_module modules\/mod_slotmem_shm.so/LoadModule slotmem_shm_module modules\/mod_slotmem_shm.so/g' /etc/apache2/httpd.conf && \
    rm -rf /var/cache/apk/* && \
    ln -s /usr/bin/php7 /usr/bin/php 
# Setup default www directory and expose port 80
COPY    www/ /var/www/localhost/htdocs/
VOLUME  /var/www/localhost/htdocs
WORKDIR /var/www/localhost/htdocs
EXPOSE 80

# Define default command (cron output by default)
CMD ["httpd","-DFOREGROUND"]
