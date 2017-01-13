# Portainer doesn't work unless it's the arm tag
docker service create \
    --name portainer \
    --publish 9000:9000 \
    --constraint 'node.role == manager' \
    --mount type=bind,src=/var/run/docker.sock,dst=/var/run/docker.sock \
    portainer/portainer:arm \
    --swarm
# Visualize the Swarm
docker service create \
    --name=viz \
    --publish=8080:8080/tcp \
    --constraint=node.role==manager \
    --mount=type=bind,src=/var/run/docker.sock,dst=/var/run/docker.sock \
    alexellis2/visualizer-arm:latest
# Create whoami containers for the swarm; to see do something like curl -4 localhost:8900
docker service create --name whoami --publish 8900:8000 hypriot/rpi-whoami
docker service scale whoami=2



