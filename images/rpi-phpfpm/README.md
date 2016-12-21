# PHP5.6-Docker Raspberry PI/Raspbian

PHP 5.6 Dockerfile for Raspberry PI's based on Raspbian

## Details
* php5.6
* php5-fpm
* php5-cli
* php5-mysql
* php5-mcrypt
* php5-mbstring
* php5-mhash
* php5-openssl
* php5-libxml
* php5-curl
* php5-gd
* php5-intl
* php5-redis
* php5-apc
* mysql-client

## Run Image
docker run -d -p 9000:9000 -v /var/www:/var/www rpi-php56

  By default if you don't specify the -v you'll get a phpinfo page

## Build Images
docker build -t rpi-php56 .

## Pull from docker hub
docker pull choukalos/rpi-php56


