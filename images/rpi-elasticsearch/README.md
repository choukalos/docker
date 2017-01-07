# Elasticsearch-Docker Raspberry PI/Raspbian

Elasticsearch Dockerfile for Raspberry PI's based on Raspbian

## Details
* Elasticsearch
* Exposes Port 9200 & 9300

## How to Run
docker run -d -p 9200:9200 -p9300:9300 -v <data-dir>:/data image

   Mountable Directory
   <data-dir>   where the data's stored, under the data directory, logs under the log directory
   
   Configuration file is located at <data-dir>/elasticsearch.yml
   At a minimum it should have
   
   path:
     logs: /data/log
     data: /data/data
   
   Can run your custom config
   docker run -d -p 9200:9200 -p 9300:9300 -v <data-dir>:/data dockerfile/elasticsearch /elasticsearch/bin/elasticsearch -Des.config=/data/elasticsearch.yml

   A minute or so later open http://<host>:9200 to see the results

## Build Images
docker build -t rpi-elasticsearch .

## Pull from docker hub
docker pull choukalos/rpi-elasticsearch

## Ways to run a cluster

* Docker run from each node in my cluster; make sure I have a local directory for the data store
** docker run -d --network host -p 9200:9200 -p 9300:9300 choukalos/rpi-elasticsearch -Des.discovery.zen.ping.unicast.hosts=192.168.1.132,192.168.1.133,192.168.1.134,192.168.1.135
** Then check if cluster is healthy:  curl 192.168.1.132:9200/_cluster/health

Doesn't look like services work well; unless you're firing a service for each instance and locking to a node.  That seems pretty kludgy


