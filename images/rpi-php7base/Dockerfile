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
    php7-intl php7-json php7-soap php7-bcmath php7-xmlrpc php7-xsl \
    bash bash-completion grep coreutils && \
    sed -i 's/max_execution_time \= 30/max_execution_time \= 300/g' /etc/php7/php.ini && \
    curl -sS https://getcomposer.org/installer | /usr/bin/php7 -- --install-dir=/usr/bin --filename=composer && \
    rm -rf /var/cache/apk/* && \
    ln -s /usr/bin/php7 /usr/bin/php 

