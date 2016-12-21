Notes / Best Practices in Creating Images
-----------------------------------------


Images to Use
-------------

Redis   ; use frozenfoxx/rpi-redis image
* Uses Alpine Linux as base image; Redis v3.2.3
* Github: https://github.com/frozenfoxx/rpi-redis 
* run as docker run -d -p6357:6357 frozenfoxx/rpi-redis

PHP7.02 ; use nidorpi/rpi-php:7.0-apache
* use this as the base to build your apps php image

rpi-raspbian ; clone of others to learn how to build a raspian image from scratch
rpi-raspbianlite ; swizzle of rpi-raspbian to build a raspian lite image from scratch

rpi-varnish ; for varnish on your magentopi swarm!
magentopi   ; this image is Magento on apache/php for use in a docker swarm
rpi-elasticsearch ; this image is to run an elastic cluster on rpi swarm




