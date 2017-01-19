
#! /bin/sh

rm -rf app/cache &&
    php app/console doctrine:database:drop --force &&
    php app/console doctrine:database:create &&
    php app/console doctrine:schema:update --force &&
    php app/console doctrine:fixtures:load --no-interaction &&
    php app/console fos:elastica:reset &&
    php app/console fos:elastica:populate &&
    php app/console assets:install web --symlink &&
    php app/console assetic:dump web
