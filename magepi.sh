# Script to launch Magento PI containers as services but constrainted to particular nodes
# note can limit cpu & memory using
# --limit-cpu VALUE   (default is 0.000 which means use all available; could do 1 to limit to 1 core ...)
# --limit-memory VALUE  (default is 0B which means use all available; could limit in 256M images like a VM would limit things)
# Ref for service options:  https://docs.docker.com/engine/reference/commandline/service_create/ 
# Ref for volumes:  https://docs.docker.com/engine/reference/commandline/service_create/ 
docker service create --name db --constraint 'node.hostname == rpi4' --publish 3306:3306 --mount type=volume,destination=/var/lib/mysql dhermanns/rpi-mariadb
docker service create --name redis --constraint 'node.hostname == rpi4' --publish 6379:6379 hypriot/rpi-redis
docker service create --name varnish --constraint 'node.hostname == rpi1' --publish 80:80 --publish:8000:8000 choukalos/rpi-varnish
#docker service create --name magento --publish 80:80 --publish 443:443 choukalos/magentopi
#docker service scale magento=2
