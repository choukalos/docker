FROM choukalos/rpi-chromiumheadless:latest

RUN mkdir -p /home/app

RUN echo "Pulling watchdog binary from Github." \
    && curl -sSL https://github.com/openfaas/faas/releases/download/0.11.0/fwatchdog-armhf > /usr/bin/fwatchdog \
    && chmod +x /usr/bin/fwatchdog \
    && cp /usr/bin/fwatchdog /home/app \
    && curl -sSL https://dl.minio.io/client/mc/release/linux-arm/mc > /usr/bin/mc \
    && chmod +x /usr/bin/mc \
    && cd /home/app  \
    && npm install -g whatwg-url 
    
# Add non root user
RUN addgroup --system app && useradd -r app -g app
RUN chown app /home/app
COPY lighthouse.sh /home/app/
RUN chmod 755 /home/app/lighthouse.sh

WORKDIR /home/app

USER app

# Populate example here - i.e. "cat", "sha512sum" or "node index.js"
ENV fprocess="xargs /home/app/lighthouse.sh"
# Set to true to see request in function logs
ENV write_debug="false"
ENV read_timeout="500"
ENV write_timeout="500"

EXPOSE 8080

HEALTHCHECK --interval=3s CMD [ -e /tmp/.lock ] || exit 1
CMD [ "fwatchdog" ]
