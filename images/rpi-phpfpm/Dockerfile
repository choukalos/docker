# PHP5.6 Dockerfile for Raspberry PI Raspbian
#
FROM choukalos/rpi-raspbian
MAINTAINER Chuck Choukalos <choukalos@gmail.com>


#Install PHP
RUN \
  apt-get update && \
  apt-get install -y php5 \
  php5-fpm \
  php5-cli \
  php5-mysql \
  php5-mcrypt \
  php5-curl \
  php5-gd \
  php5-intl \
  php5-mhash \
  mysql-client

# Configure
RUN mkdir -p /var/www && echo "<?php phpinfo(); ?>" > /var/www/phpinfo.php

# Configure PHP
RUN sed -i "s/;cgi.fix_pathinfo=1/cgi.fix_pathinfo=0/" /etc/php5/fpm/php.ini
RUN sed -i -e "s/;daemonize\s*=\s*yes/daemonize = no/g" /etc/php5/fpm/php-fpm.conf
RUN sed -i "s/;cgi.fix_pathinfo=1/cgi.fix_pathinfo=0/" /etc/php5/cli/php.ini
RUN php5enmod mcrypt

# Clean up image
RUN apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Volumes to Mount
VOLUME ["/var/www"]
WORKDIR /var/www

# Run Service & Expose Ports
CMD ["php-fpm","start"]

EXPOSE 9000
