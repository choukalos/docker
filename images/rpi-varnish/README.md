WIP
# This Image doesn't work.... 

# Varnish Docker Raspberry PI/Alpine
Varnish Dockerfile for Raspberry PI's based on Alpine/Armhf
Based on Alpine v3.5, includes varnish, varnish-geoip and varnish-libs

## Details
* Varnish 4.1
* Exposes Port 80

## How to Run
docker run -d \
  --link web-app:backend-host \
  --volumes-from web-app \
  --env 'VCL_CONFIG=/data/path/to/varnish.vcl' \
  docker-rpi-varnish

*Assume you have varnish.vcl in a folder within your web-app volume
*Web-app is aliased inside varnish container as 'backend-host'
** ie:
    default {
  	.host = "backend-host";
 	.port = "80";
    }

## Environment Variables
VCL_CONFIG 	/etc/varnish/default.vcl
CACHE_SIZE 	64m
VARNISHD_PARAMS -p default_ttl=3600 -p default_grace=3600

## Build Images
docker build -t rpi-varnish .

## Pull from docker hub
docker pull choukalos/rpi-varnish




