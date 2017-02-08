FROM choukalos/rpi-raspbian
MAINTAINER Chuck Choukalos <choukalos@gmail.com>

# Install deps
RUN apt-get update && \
	apt-get upgrade -y && \
	apt-get install -y glusterfs-server vim && \
	mkdir -p /data/vol
	
VOLUME /data
	
# Define default command (cron output by default)
CMD ["glusterd","--no-daemon"]

