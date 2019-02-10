FROM alpine:3.8
MAINTAINER Chuck Choukalos <choukalos@yahoo.com>

RUN apk update && apk add --no-cache fontconfig ca-certificates openssl wget curl curl-dev nodejs \
    && mkdir -p /usr/share \
    && cd /usr/share \
    && curl -L https://github.com/yangxuan8282/docker-image/releases/download/2.1.1/phantomjs-2.1.1-alpine-arm.tar.xz | tar xJ \
    && ln -s /usr/share/phantomjs/phantomjs /usr/bin/phantomjs \
    && phantomjs --version
