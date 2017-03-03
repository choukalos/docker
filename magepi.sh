# Script to launch Magento PI containers as services but constrainted to particular nodes
# note can limit cpu & memory using
# --limit-cpu VALUE   (default is 0.000 which means use all available; could do 1 to limit to 1 core ...)
# --limit-memory VALUE  (default is 0B which means use all available; could limit in 256M images like a VM would limit things)
# Ref for service options:  https://docs.docker.com/engine/reference/commandline/service_create/ 
# Ref for volumes:  https://docs.docker.com/engine/reference/commandline/service_create/ 
docker service create --name redis --constraint 'node.hostname == rpi4' --publish 6379:6379 hypriot/rpi-redis
docker service create --name varnish --constraint 'node.hostname == rpi1' \
       --publish 80:80 --publish 6081:6081 --publish 6082:6082 \
       --mount type=bind,source=/mnt/data/docker/volumes/magentopi,target=/var/www/html \
       --env 'VCL_CONFIG=/var/www/html/default.vcl' choukalos/rpi-varnish
docker service create --name db --constraint 'node.hostname == rpi4' --publish 3306:3306 \
  --mount type=bind,source=/mnt/data/docker/volumes/magentopi_db,target=/var/lib/mysql -e MYSQL_ROOT_PASSWORD=magento123 dhermanns/rpi-mariadb
# note on RPI4.local need to run a bash command to build the default MySQL DB in the mounted volume
#  docker run -it -p 3306:3306 -v /mnt/data/docker/volumes/magentopi_db:/var/lib/mysql dhermanns/rpi-mariadb mysql_install_db
#  docker exec -it CONTAINERID bash
#     export TERM=dumb
#     mysql -uroot
#           grant all privileges *.* to 'root'@'%' with grant option;
#           flush privileges;
#           quit
#     exit
#docker service create --name magemaster --env 'MODE=cron' choukalos/magentopi:2.1.3RC4
#docker service create --name magenode --publish 80:80 choukalos/magentopi:2.1.3RC4
#docker service scale magenode=4
# Try to lunch node manually and use a volume, since the image is too big with Magento in it... having numerous issues
# on rpi3.local
# docker run -d -p 80:80 -v /mnt/data/magentopi:/var/www/html/ choukalos/rpi-php:5.6.29
# docker run -d -p8000:8000 -v /mnt/data/magentopi:/var/www/localhost/htdocs choukalos/magentopi:2.1.3RC8  
# docker run -d -e MODE=cron -v /mnt/data/magentopi:/var/www/localhost/htdocs choukalos/magentopi:2.1.3RC8  
# on rpi2.local
docker service create --name web --publish 8000:8000 \
  --mount type=bind,source=/mnt/data/docker/volumes/magentopi,target=/var/www/localhost/htdocs choukalos/magentopi:2.1.3RC8
docker service create --name cron \
  --mount type=bind,source=/mnt/data/docker/volumes/magentopi,target=/var/www/localhost/htdocs choukalos/magentopi:2.1.3RC8

