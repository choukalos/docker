WIP

Apache / PHP 7.02 image with all extensions built
Default project is a php info page (tag:check)
Default project is Magento 2       (tag:2.1.3)
* I'll tag these by Magento version

To build
* update code based
* update configuration
* do build actions
** compile
** static asset build
* then build image:  docker build -t magentopi .

To run image as a single container
docker run -d -p 80:80 --name my-php choukalos/magentopi
 
