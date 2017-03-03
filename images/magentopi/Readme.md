MagentoPI Docker Image for RPI's

Apache / PHP 7.02 image with all extensions built setup to run Magento 2
Can be run in 2 modes - web or cron

Exposed:
* Ports 8000
* Magento Code & Default.vcl volume mount:  /var/www/localhost/htdocs
* Magento Media Assets       volume mount:  /var/www/localhost/htdocs/pub/media

Image Management

To build image and push to repo: 
make
make push

To run image:

run interactively
docker run -it -p8000:8000 -v /mnt/magentocode:/var/www/localhost/htdocs choukalos/magentopi /bin/sh

run in single container/web node
docker run -d -p 8000:8000 --name magentopi -v /mnt/magentocode:/var/www/localhost/htdocs choukalos/magentopi

run as a cron container (only have 1 running cron container)
docker run -d -e MODE=cron --name magentopi-cron -v /mnt/magentocode:/var/www/localhost/htdocs choukalos/magentopi


