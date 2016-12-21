# Redis Dockerfile for Raspberry PI
#
FROM choukalos/rpi-raspbian
MAINTAINER Chuck Choukalos <choukalos@gmail.com>

# Elasticsearch 5.x uses > 1GB Ram so does not work with RPIs :(  - use ES 2.4.3 instead
#ENV ES_PKG_NAME elasticsearch-5.1.1
#ENV DLLINK      https://artifacts.elastic.co/downloads/elasticsearch/elasticsearch-5.1.1.tar.gz
ENV ES_PKG_NAME  elasticsearch-2.4.3
ENV DLLINK       https://download.elastic.co/elasticsearch/release/org/elasticsearch/distribution/tar/elasticsearch/2.4.3/elasticsearch-2.4.3.tar.gz

# Install Redis
RUN \ 
  echo "deb http://ppa.launchpad.net/webupd8team/java/ubuntu trusty main" | tee /etc/apt/sources.list.d/webupd8team-java.list && \
  echo "deb-src http://ppa.launchpad.net/webupd8team/java/ubuntu trusty main" | tee -a /etc/apt/sources.list.d/webupd8team-java.list && \
  apt-key adv --keyserver hkp://keyserver.ubuntu.com:80 --recv-keys EEA14886 && \
  echo oracle-java8-installer shared/accepted-oracle-license-v1-1 select true | /usr/bin/debconf-set-selections && \
  apt-get update && \
  apt-get upgrade -y && \
  apt-get install -y oracle-java8-installer --no-install-recommends && \ 
  apt-get install -y wget && \
  cd / && \
  wget --no-check-certificate $DLLINK && \
  tar xvzf $ES_PKG_NAME.tar.gz && \
  rm -f $ES_PKG_NAME.tar.gz && \
  mv /$ES_PKG_NAME /elasticsearch && \
  apt-get clean -y && \
  apt-get autoclean -y && \
  apt-get autoremove -y && \
  rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /var/cache/oracle-jdk8-installer

# Mountable Volume
VOLUME ["/data"]
ADD config/elasticsearch.yml /elasticsearch/config/elasticsearch.yml
ADD start.sh /start.sh
WORKDIR /data
ENV JAVA_HOME /usr/lib/jvm/java-8-oracle

# Default Command
CMD  ["bash","/start.sh"]
#CMD ["/elasticsearch/bin/elasticsearch"]

# Expose default port
#   - 9200: HTTP
#   - 9300: Transport
EXPOSE 9200 9300
 
