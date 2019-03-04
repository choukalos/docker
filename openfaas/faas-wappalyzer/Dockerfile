FROM choukalos/rpi-phantomjs:latest
# Image assumes Docker Secret located at /home/app/.mc/config.json
# secret config.json file is setup with 'local' as your local Minio object
# store
# Calls to this function will output to standard out and save a timestamp
# named JSON object in the local/wappalyzer minio bucket

RUN mkdir -p /home/app

RUN apk --no-cache add curl nodejs npm sed \
    && echo "Pulling watchdog binary from Github." \
    && curl -sSL https://github.com/openfaas/faas/releases/download/0.11.0/fwatchdog-armhf > /usr/bin/fwatchdog \
    && chmod +x /usr/bin/fwatchdog \
    && cp /usr/bin/fwatchdog /home/app/ \
    && cd /home/app \
    && npm install -g wappalyzer whatwg-url \
    && curl -sSL https://dl.minio.io/client/mc/release/linux-arm/mc > /usr/bin/mc \
    && chmod +x /usr/bin/mc
    
    
# Add non root user
RUN addgroup -S app && adduser app -S -G app && chown -R app /home/app
COPY wappalyzer.sh /home/app/
RUN chmod +x /home/app/wappalyzer.sh


WORKDIR /home/app

USER app

# Populate example here - i.e. "cat", "sha512sum" or "node index.js"
ENV fprocess="xargs /home/app/wappalyzer.sh"
# Set to true to see request in function logs
ENV write_debug="false"

EXPOSE 8080

HEALTHCHECK --interval=3s CMD [ -e /tmp/.lock ] || exit 1
CMD [ "fwatchdog" ]
