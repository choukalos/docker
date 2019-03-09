#!/bin/sh
# Wrapper script to run Magescan and output JSON to standard out
URL=$1

DATE=`date +%Y%m%d`
NAME=`echo $URL | sed 's/https*:\/\///g' | sed 's/\/*$//g' | sed 's/\//\_/g' `
NAME="${DATE}_${NAME}.json"
OUTPUTOBJECT="rpi/magescan/${NAME}" 

# Pull secrets and setup NC on first container run
if ! [ -e ".mc/config.json" ]; then
  # initialize mc config
  MINIOGATEWAY=`cat /var/openfaas/secrets/minio_gateway`
  MINIOACCESS=`cat /var/openfaas/secrets/minio_accesskey`
  MINIOSECRET=`cat /var/openfaas/secrets/minio_secretkey`  
  `mc config host add rpi "${MINIOGATEWAY}" "${MINIOACCESS}" "${MINIOSECRET}" `
fi
#

`/home/app/magescan scan:all --format=json "${URL}" > "/dev/shm/${NAME}" `
`mc cp "/dev/shm/${NAME}" "${OUTPUTOBJECT}"`
cat "/dev/shm/${NAME}"
`rm "/dev/shm/${NAME}"`


