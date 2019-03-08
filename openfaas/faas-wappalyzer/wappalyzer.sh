#!/bin/sh
# Wrapper to fire Wappalyzer and Tee into MC for object store backup
URL=$1

MC=`which mc`
DATE=`date +%Y%m%d`
NAME=`echo $URL | sed 's/https*:\/\///g' | sed 's/\/*$//g' | sed 's/\//\_/g' `
NAME="${DATE}_${NAME}.json"
OUTPUTOBJECT="local/wappalyzer/${NAME}" 
MINIOGATEWAY=`cat /var/openfaas/secrets/minio_gateway`
MINIOACCESS=`cat /var/openfaas/secrets/minio_accesskey`
MINIOSECRET=`cat /var/openfaas/secrets/minio_secretkey`


# Pull secrets and setup NC on first container run
if [ -e ".mc/config.json" ]
then
  rem "do nothing"
else
  # initialize mc config
  `mc config host add local "${MINIOGATEWAY}" "${MINIOACCESS}" "${MINIOSECRET}" `
fi
# 

# (does not work) Usee Tee to split stdout so it also pipes to minio
#eval "wappalyzer ${URL} | tee >( mc pipe ${OUTPUTOBJECT} )"

# (works) Do Wappalyzer Scan; saving to shared memory file to copy to Minio and echo back out to stdout for OpenFAAS
`wappalyzer "${URL}" > "/dev/shm/${NAME}" `
mc cp "/dev/shm/${NAME}" "${OUTPUTOBJECT}"
echo "/dev/shm/${NAME}"
rm "/dev/shm/${NAME}"

