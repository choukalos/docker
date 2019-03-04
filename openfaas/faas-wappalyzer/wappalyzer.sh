#!/bin/sh
# Wrapper to fire Wappalyzer and Tee into MC for object store backup
URL=$1

MC=`which mc`
DATE=`date +%Y%m%d`
NAME=`echo $URL | sed 's/https*:\/\///g' | sed 's/\/*$//g' | sed 's/\//\_/g' `
NAME="${DATE}_${NAME}.json"
OUTPUTOBJECT="local/wappalyzer/${NAME}" 

# (does not work) Usee Tee to split stdout so it also pipes to minio
#eval "wappalyzer ${URL} | tee >( mc pipe ${OUTPUTOBJECT} )"

# (works) Do Wappalyzer Scan; saving to shared memory file to copy to Minio and echo back out to stdout for OpenFAAS
`wappalyzer "${URL}" > "/dev/shm/${NAME}" `
mc cp "/dev/shm/${NAME}" "${OUTPUTOBJECT}"
echo "/dev/shm/${NAME}"
rm "/dev/shm/${NAME}"

