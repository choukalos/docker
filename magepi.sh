# Script to launch Magento PI containers as services but constrainted to particular nodes
# note can limit cpu & memory using
# --limit-cpu VALUE   (default is 0.000 which means use all available; could do 1 to limit to 1 core ...)
# --limit-memory VALUE  (default is 0B which means use all available; could limit in 256M images like a VM would limit things)
# Ref for service options:  https://docs.docker.com/engine/reference/commandline/service_create/ 
# Ref for volumes:  https://docs.docker.com/engine/reference/commandline/service_create/ 
docker service create --name redis --constraint 'node.hostname == rpi4' --publish 6379:6379 hypriot/rpi-redis
docker service create --name varnish --constraint 'node.hostname == rpi1' --publish 80:80 --publish:8000:8000 choukalos/rpi-varnish
docker service create --name db --constraint 'node.hostname == rpi4' --publish 3306:3306 \
  --mount type=bind,source=/mnt/data/docker/volumes/magentopi_db,target=/var/lib/mysql -e MYSQL_ROOT_PASSWORD=magento123 dhermanns/rpi-mariadb
# note on RPI4.local need to run a bash command to build the default MySQL DB in the mounted volume
#  docker run -it -p 3306:3306 -v /mnt/data/docker/volumes/magentopi_db:/var/lib/mysql dhermanns/rpi-mariadb mysql_install_db
docker service create --name magemaster choukalos/magentopi master
docker service create --name magenode --publish 7000:7000 choukalos/magentopi  
docker service scale magenode=4