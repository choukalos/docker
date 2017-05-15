FROM armhf/alpine:3.5
MAINTAINER Chuck Choukalos <choukalos@yahoo.com>
#VERSION	1.0.1

# Install deps
RUN echo "http://dl-cdn.alpinelinux.org/alpine/v3.5/community" >> /etc/apk/repositories && \
    apk update && \
    apk add varnish varnish-geoip bash coreutils grep bash-completion && \
    mkdir /var/www /var/www/localhost /var/www/localhost/htdocs && \
    rm -rf /var/cache/apk/* 

# Setup default www mountable directory and expose port 80,6081,6082 set default ENV parameters
ADD     start.sh /
ADD     default.vcl /var/www/localhost/htdocs
VOLUME  /var/www/localhost/htdocs
WORKDIR /var/www/localhost/htdocs
ENV 	VCL_CONFIG	/var/www/localhost/htdocs/default.vcl
ENV	CACHE_SIZE	64m
ENV	VARNISHD_PARAMS	-p default_ttl=3600 -p default_grace=3600 -p http_resp_hdr_len=64000 -p thread_pool_min=1 -p thread_pool_max=1 -T 127.0.0.1:6082
EXPOSE 80
EXPOSE 6081
EXPOSE 6082

# Define default command
CMD ["/start.sh"]
