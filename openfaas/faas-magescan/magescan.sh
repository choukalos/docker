#!/bin/sh
# Wrapper script to run Magescan and output JSON to standard out
/home/app/magescan scan:all --format=json $1
