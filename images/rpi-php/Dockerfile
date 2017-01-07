FROM hypriot/rpi-alpine
MAINTAINER Chuck Choukalos <choukalos@gmail.com>

RUN apk update && \
    apk add curl ca-certificates php5-pdo_mysql php5-curl php5-opcache     \
        php5-apache2 php5-zlib php5-xml php5-xsl php5-zip php5-zlib        \
        php5-xml php5-xsl php5-soap php5-snmp php5-xmlreader php5-sqlite3     \
        php5-openssl php5-pcntl php5-mcrypt php5-intl php5-iconv php5-json \
        php5-gd php5-gettext php5-ctype php5-bcmath php5-xmlrpc \
        apache2

COPY    httpd.conf /etc/apache2/httpd.conf
VOLUME  /var/www/html
WORKDIR /var/www/html
EXPOSE  80 443
CMD     ["httpd", "-DFOREGROUND"]
