#! /bin/sh

rm -rf app/cache &&
##* Gen Db  :
    php app/console doctrine:database:drop --force ;
    php app/console doctrine:database:create -vvv &&
    php app/console doctrine:schema:update --force -vvv &&
##* Load Fixture  :
    php app/console doctrine:fixtures:load --no-interaction -vvv &&
##* Assets Installation order for JS/CSS  :
    php app/console assets:install web --symlink &&
    php app/console assetic:dump web
