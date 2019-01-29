FROM armhf/alpine:3.5
MAINTAINER Chuck Choukalos <choukalos@yahoo.com>

RUN apk add --no-cache mosquitto
RUN mkdir -p /mqtt/config /mqtt/data /mqtt/log && \
    chown mosquitto:mosquitto /mqtt/*

COPY config /mqtt/config
VOLUME ["/mqtt/config","/mqtt/data","/mqtt/log"]

EXPOSE 1883 9001
CMD /usr/sbin/mosquitto -c /mqtt/config/mosquitto.conf


