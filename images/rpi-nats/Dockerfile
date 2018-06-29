FROM armhf/alpine:3.5
MAINTAINER Chuck Choukalos <choukalos@yahoo.com>

COPY gnatsd /gnatsd
COPY gnatsd.conf gnatsd.conf

# Expose Client, Management and Cluster ports
EXPOSE 4222 8222 6222

# Run via the configuration file
ENTRYPOINT ["/gnatsd"]
CMD ["-c", "gnatsd.conf"]
