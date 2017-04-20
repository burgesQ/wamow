#! /bin/sh

rm -rf app/cache &&
##* Gen Db  :
    php app/console doctrine:database:drop --force ;
    php app/console doctrine:database:create -vvv &&
    php app/console doctrine:schema:update --force -vvv &&
##* Load Fixture  :
    php app/console doctrine:fixtures:load --no-interaction &&
##* Assets Installation order for JS/CSS :
    php app/console braincrafted:bootstrap:install &&
    php app/console assets:install &&
    php app/console assetic:dump
##* Right Pb :
    # && chmod -R 777 app/cache app/logs
