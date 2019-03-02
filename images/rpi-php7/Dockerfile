FROM alpine:3.8
MAINTAINER Chuck Choukalos <choukalos@yahoo.com>

# Install deps
RUN apk update && apk add --no-cache curl ca-certificates s6 ssmtp \
    libxml2-dev libxslt-dev zlib-dev openssl wget \
    php7 php7-phar php7-curl \
    php7-fpm php7-json php7-zlib php7-xml php7-dom php7-ctype php7-opcache php7-zip php7-iconv \
    php7-pdo php7-pdo_mysql php7-pdo_sqlite php7-pdo_pgsql php7-mbstring php7-session \
    php7-gd php7-mcrypt php7-openssl php7-sockets php7-posix php7-ldap \
    php7-intl php7-json php7-soap php7-bcmath php7-xmlrpc php7-xsl \
    apache2 apache2-ssl apache2-utils apache2-proxy apache2-proxy-html php7-apache2 \
    bash apache2-http2 bash-completion grep coreutils && \
    mkdir -p /run/apache2 && \
    sed -i 's/\#LoadModule rewrite_module modules\/mod_rewrite.so/LoadModule rewrite_module modules\/mod_rewrite.so/g' /etc/apache2/httpd.conf && \
    sed -i 's/\#LoadModule expires_module modules\/mod_expires.so/LoadModule expires_module modules\/mod_expires.so/g' /etc/apache2/httpd.conf && \
    sed -i 's/\#LoadModule deflate_module modules\/mod_deflate.so/LoadModule deflate_module modules\/mod_deflate.so/g' /etc/apache2/httpd.conf && \
    sed -i 's/\#LoadModule http2_module modules\/mod_http2.so/LoadModule http2_module modules\/mod_http2.so/g' /etc/apache2/httpd.conf && \
    sed -i 's/max_execution_time \= 30/max_execution_time \= 300/g' /etc/php7/php.ini && \
    curl -sS https://getcomposer.org/installer | /usr/bin/php7 -- --install-dir=/usr/bin --filename=composer && \
    rm -rf /var/cache/apk/* 
# Setup default www directory and expose port 80
COPY    www/ /var/www/localhost/htdocs/
COPY    etc/webdir.conf /etc/apache2/conf.d/
VOLUME  /var/www/localhost/htdocs
WORKDIR /var/www/localhost/htdocs
EXPOSE 80

# Define default command (cron output by default)
CMD ["httpd","-DFOREGROUND"]
