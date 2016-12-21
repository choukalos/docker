FROM alpine:3.3
MAINTAINER Chuck Choukalos <choukalos@gmail.com>

RUN apk --no-cache add varnish
ENTRYPOINT ["varnishd"]

ADD start.sh /start.sh

ENV VCL_CONFIG		/etc/varnish/default.vcl
ENV CACHE_SIZE		64m
ENV VARNISHD_PARAMS 	-p default_ttl=3600 -p default_grace=3600

CMD ["sh","/start.sh"]
EXPOSE 80
