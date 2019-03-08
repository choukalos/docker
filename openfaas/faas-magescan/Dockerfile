FROM choukalos/rpi-php7:latest

RUN mkdir -p /home/app

RUN echo "Pulling watchdog binary from Github." \
    && curl -sSL https://github.com/openfaas/faas/releases/download/0.11.0/fwatchdog-armhf > /usr/bin/fwatchdog \
    && chmod +x /usr/bin/fwatchdog \
    && cp /usr/bin/fwatchdog /home/app \
    && cd /home/app \
    && wget https://github.com/steverobbins/magescan/releases/download/v1.12.9/magescan.phar \
    && cp /home/app/magescan.phar /home/app/magescan \
    && chmod +x /home/app/magescan
    
# Add non root user
COPY magescan.sh /home/app/
RUN addgroup -S app && adduser app -S -G app && chown app /home/app && chmod 755 /home/app/magescan.sh

WORKDIR /home/app

USER app

# Populate example here - i.e. "cat", "sha512sum" or "node index.js"
ENV fprocess="xargs /home/app/magescan.sh"
# Set to true to see request in function logs
ENV write_debug="false"
ENV read_timeout="600"
ENV write_timeout="600"
EXPOSE 8080

HEALTHCHECK --interval=3s CMD [ -e /tmp/.lock ] || exit 1
CMD [ "fwatchdog" ]
