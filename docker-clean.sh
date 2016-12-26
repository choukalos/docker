#!/bin/bash
# Cleaned Exited Containers
docker rm -v $(docker ps -a -q -f status=exited)
# Clean unwanted dangling images
docker rmi $(docker images -f "dangling=true" -q)
# Clean up volumes
docker volume rm $(docker volume ls -qf dangling=true)

