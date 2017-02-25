MagentoPI Docker Image for RPI's

Apache / PHP 7.02 image with all extensions built
Default project is Magento 2
I'll tag these by Magento version

Volume Exposed - must include deployed Magento code and default.vcl

Magento Code & Default.vcl :  /var/www/localhost/htdocs/
Media Assets               :  /var/www/localhost/htdocs/pub/media

Ports Exposed
Port 8000


To build image and push to repo: 
make
make push

To run image:
* run as a single container/web node:
** docker run -d -p 80:80 --name my-php choukalos/magentopi
* run as a cron container:
** docker run -d -e MODE=cron --name my-php-cron choukalos/magentopi


