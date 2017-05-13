# Varnish Docker Raspberry PI/Alpine
Varnish Dockerfile for Raspberry PI's based on Alpine/Armhf
Based on Alpine v3.5, includes varnish, varnish-geoip and varnish-libs

## Details
* Varnish 4.1
* Exposes Port 80, 6081, 6082
* Expects a mount to /var/www/localhost/htdocs/ that contains a default.vcl

## How to Run
docker run -d -v /src/www:/var/www/localhost/htdocs \
       choukalos/rpi-varnish:latest

## Environment Variables
VCL_CONFIG 	/var/www/localhost/htdocs/default.vcl
CACHE_SIZE 	64m
VARNISHD_PARAMS -p default_ttl=3600 -p default_grace=3600

## Build Images
docker build -t rpi-varnish .

## Pull from docker hub
docker pull choukalos/rpi-varnish




