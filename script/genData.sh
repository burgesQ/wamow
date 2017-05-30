
#! /bin/sh

rm -rf app/cache &&
##* Gen Db  :
    php app/console doctrine:database:drop --force ;
    php app/console doctrine:database:create -vvv &&
    php app/console doctrine:migrations:migrate --no-interaction &&
    php app/console doctrine:schema:validate &&
##* Load Fixture  :
    php app/console doctrine:fixtures:load --no-interaction &&
##* Assets Installation order for JS/CSS :
    php app/console braincrafted:bootstrap:install &&
    php app/console assets:install --symlink &&
    php app/console assetic:dump web &&
##* Right Pb :
    chmod -R 777 app/cache app/logs
