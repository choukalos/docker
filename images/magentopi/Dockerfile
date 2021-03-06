# Note image at run time is waiting for a MODE environment variable
# if MODE=cron ; then it'll run crontab
# else ; runs apache on port 8000
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
    apache2 apache2-ssl apache2-utils apache2-proxy apache2-proxy-html php7-apache2 \
    apache2-http2 bash bash-completion grep coreutils && \
    mkdir -p /run/apache2 && \
    sed -i.bak 's/Listen 80/Listen 8000/g' /etc/apache2/httpd.conf && \
    sed -i 's/\#LoadModule slotmem_shm_module modules\/mod_slotmem_shm.so/LoadModule slotmem_shm_module modules\/mod_slotmem_shm.so/g' /etc/apache2/httpd.conf && \
    sed -i 's/\#LoadModule rewrite_module modules\/mod_rewrite.so/LoadModule rewrite_module modules\/mod_rewrite.so/g' /etc/apache2/httpd.conf && \
    sed -i 's/\#LoadModule expires_module modules\/mod_expires.so/LoadModule expires_module modules\/mod_expires.so/g' /etc/apache2/httpd.conf && \
    sed -i 's/\#LoadModule deflate_module modules\/mod_deflate.so/LoadModule deflate_module modules\/mod_deflate.so/g' /etc/apache2/httpd.conf && \
    sed -i 's/\#LoadModule http2_module modules\/mod_http2.so/LoadModule http2_module modules\/mod_http2.so/g' /etc/apache2/httpd.conf && \
    sed -i 's/max_execution_time \= 30/max_execution_time \= 300/g' /etc/php7/php.ini && \
    echo '* * * * * /usr/bin/php /var/www/localhost/htdocs/bin/magento cron:run | grep -v "Ran jobs by schedule" >> /var/www/localhost/htdocs/var/log/magento.cron.log' > /crontab && \
    crontab -u apache /crontab && \
    curl -sS https://getcomposer.org/installer | /usr/bin/php7 -- --install-dir=/usr/bin --filename=composer && \
    rm -rf /var/cache/apk/* && \
    ln -s /usr/bin/php7 /usr/bin/php && \
    touch /var/log/cron.log 

# Update config files and install scripts
COPY  start.sh                /start.sh
COPY  etc/php7/00_opcache.ini /etc/php7/conf.d/
COPY  etc/apache2/magedir.conf /etc/apache2/conf.d/

# You should mount /var/www/localhost/htdocs with your Magento instance root directory
# You likely will want to mount pub/media on shared network drive or store images in DB
VOLUME  /var/www/localhost/htdocs
VOLUME  /var/www/localhost/htdocs/pub/media
WORKDIR /var/www/localhost/htdocs
EXPOSE  8000
CMD     ["bash","/start.sh"]


