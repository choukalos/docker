FROM choukalos/rpi-chromiumheadless:latest

RUN mkdir -p /home/app

RUN echo "Pulling watchdog binary from Github." \
    && curl -sSL https://github.com/openfaas/faas/releases/download/0.11.0/fwatchdog-armhf > /usr/bin/fwatchdog \
    && chmod +x /usr/bin/fwatchdog \
    && cp /usr/bin/fwatchdog /home/app \
    && cd /home/app 
    
# Add non root user
RUN addgroup --system app && useradd -r app -g app
RUN chown app /home/app
COPY lighthouse.sh /home/app/
RUN chmod 755 /home/app/lighthouse.sh

WORKDIR /home/app

USER app

# Populate example here - i.e. "cat", "sha512sum" or "node index.js"
ENV fprocess="xargs lighthouse.sh"
# Set to true to see request in function logs
ENV write_debug="true"

EXPOSE 8080

HEALTHCHECK --interval=3s CMD [ -e /tmp/.lock ] || exit 1
CMD [ "fwatchdog" ]
