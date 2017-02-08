FROM armhf/alpine:3.5
RUN  apk update && \
     apk add curl mariadb mariadb-bench && \
     rm -rf /var/cache/apk/* 
COPY conf.d/*                /etc/mysql/conf.d/
COPY *.sh                    /usr/local/bin/
# Note galera-healthcheck is x86 binary; need to do something about it
COPY bin/galera-healthcheck  /usr/local/bin/galera-healthcheck
COPY primary-component.sql   /

EXPOSE 3306 4444 4567 4567/udp 4568 8080 8081

HEALTHCHECK CMD /usr/local/bin/healthcheck.sh

ENTRYPOINT ["start.sh"]
