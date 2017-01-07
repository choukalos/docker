Notes / Best Practices in Creating Images
-----------------------------------------


Images to Use
-------------

Redis   ; use frozenfoxx/rpi-redis image
* Uses Alpine Linux as base image; Redis v3.2.3
* Github: https://github.com/frozenfoxx/rpi-redis 
* run as docker run -d -p6357:6357 frozenfoxx/rpi-redis

--PHP7.02 ; use nidorpi/rpi-php:7.0-apache
--* use this as the base to build your apps php image
PHP7 ; will need to build an arm version off alpine as Nidropi's didn't have all the extensions needed for Magento
* Community has PHP7 from alpine 3.4; https://pkgs.alpinelinux.org/packages?page=2&repo=community&branch=edge&name=php7%2A&arch=armhf 
* link to add to repo would be http://dl-cdn.alpinelinux.org/alpine/edge/community 
* maybe just simple swizle of this one:  https://github.com/fguillot/docker-alpine-nginx-php7/blob/master/Dockerfile 

rpi-raspbian ; clone of others to learn how to build a raspian image from scratch
rpi-raspbianlite ; swizzle of rpi-raspbian to build a raspian lite image from scratch

rpi-varnish ; for varnish on your magentopi swarm!
magentopi   ; this image is Magento on apache/php for use in a docker swarm
rpi-elasticsearch ; this image is to run an elastic cluster on rpi swarm




