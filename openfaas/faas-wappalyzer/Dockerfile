FROM choukalos/rpi-phantomjs:latest

RUN mkdir -p /home/app

RUN apk --no-cache add curl nodejs npm \
    && echo "Pulling watchdog binary from Github." \
    && curl -sSL https://github.com/openfaas/faas/releases/download/0.11.0/fwatchdog-armhf > /usr/bin/fwatchdog \
    && chmod +x /usr/bin/fwatchdog \
    && cp /usr/bin/fwatchdog /home/app \
    && cd /home/app \
    && npm install -g wappalyzer
    
# Add non root user
RUN addgroup -S app && adduser app -S -G app

RUN chown app /home/app

WORKDIR /home/app

USER app

# Populate example here - i.e. "cat", "sha512sum" or "node index.js"
ENV fprocess="xargs /usr/bin/wappalyzer"
# Set to true to see request in function logs
ENV write_debug="false"

EXPOSE 8080

HEALTHCHECK --interval=3s CMD [ -e /tmp/.lock ] || exit 1
CMD [ "fwatchdog" ]
