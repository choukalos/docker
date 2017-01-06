Chuck's Docker Repository
-------------------------

This is a collection of notes and images I've cloned or created while I explore docker.

Directories & Contents
----------------------
images		    - collection of docker images I've cloned or created
readme.md	    - this file + my random docker notes


Notes on Images
-------------

base  Raspbian      - choukalos/rpi-raspbian
node 	            - hypriot/rpi-node
redis	            - hypriot/rpi-redis
    How to use & Link to app; note exposes port 6379
    docker run --name some-app --link some-redis:redis -d application-that-uses-redis

mariadb             - dhermanns/rpi-mariadb
Elasticsearch 1.7.1 - choukalos/rpi-elasticsearch
php7		    - choukalos/docker-rpi-php7


How to Build a docker image
--------------------------

Create a Dockerfile (in a directory; w/appropriate files)
Build the image:
docker build -t IMAGENAME .


How to run an image
-------------------
  docker run IMAGENAME

  Run it interactively via shell
  docker run -it IMAGENAME /bin/bash    

How to get an interactive bash shell inside a running container
---------------------------------------------------------------
  docker exec -it CONTAINERID bash


How to remove an Image
----------------------
  docker rmi IMAGEID


