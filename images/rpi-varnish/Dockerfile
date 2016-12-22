FROM choukalos/rpi-raspbian:latest
MAINTAINER Chuck Choukalos <choukalos@gmail.com>

RUN apt-get update -y && \
    apt-get install -y varnish && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

ENTRYPOINT ["varnishd"]

ADD start.sh /start.sh

ENV VCL_CONFIG		/etc/varnish/default.vcl
ENV CACHE_SIZE		64m
ENV VARNISHD_PARAMS 	-p default_ttl=3600 -p default_grace=3600

CMD ["/start.sh"]
EXPOSE 80
