OPENFAAS directory
==================

This is a collection of OpenFAAS functions

Useful resources
----------------
- https://github.com/openfaas/faas
- https://blog.alexellis.io/cli-functions-with-openfaas/

Creating, Building and Deploying a Function
-------------------------------------------
1.  faas new --lang dockerfile nmap     ; creates a new project in current folder for function nmap
2.  edit Dockerfile/build function
3.  faas build -f nmap.yml
4.  faas deploy -f nmap.yml

Alternatively to build & deploy try:
faas build -f nmap.yml && faas deploy -f nmap.yml

