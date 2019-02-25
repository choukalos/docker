#!/bin/sh
# Wrapper script to run lighthouse and output JSON to standard out
lighthouse $1 --output=json --quiet --chrom-flags='headless'

