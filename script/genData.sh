#! /bin/sh

rm -rf app/cache &&
##* Gen Db  :
    php app/console doctrine:database:drop --force ;
    php app/console doctrine:database:create -vvv &&
    php app/console doctrine:migrations:migrate --no-interaction -vvv &&
    php app/console doctrine:schema:validate -vvv &&
##* Load Fixture  :
    php app/console lexik:currency:import ecb &&
    php app/console doctrine:fixtures:load --append --no-interaction -vvv &&
    php app/console mission:migrate_to_v2:execute -vvv &&
##* Assets Installation order for JS/CSS :
    php app/console assets:install --symlink &&
    php app/console assetic:dump web/ &&
##* Right Pb :
    chmod -R 777 app/cache app/logs
