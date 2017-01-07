WIP

Apache / PHP 7.02 image with all extensions built
Default project is a php info page (tag:check)
Default project is Magento 2       (tag:2.1.3)
* I'll tag these by Magento version

* then build image:  docker build -t magentopi .

To build image:
docker build -t magentopi .

To run image:
* run as a single container/web node:
** docker run -d -p 80:80 --name my-php choukalos/magentopi
* run as a cron container:
** docker run -d -e MODE=cron --name my-php-cron choukalos/magentopi


