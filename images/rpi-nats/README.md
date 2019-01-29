RPI-NATS

Alpine Linux v3.5 for ARMHF (Raspberry PI) with NATS Server

Exposes the following ports
- NATS Client port: 4222
- NATS Management port: 8222
- NATS Cluster port: 6222

Runs based on a default configuration file

Example service creation call on Docker Swarm
docker service create --name=nats --publish=4222:4222/tcp --publish=6222:6222/tcp --publish=8222:8222/tcp choukalos/rpi-nats




