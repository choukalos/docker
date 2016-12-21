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


