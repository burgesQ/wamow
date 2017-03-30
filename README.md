# Docker Symfony (PHP7-FPM - NGINX - MySQL - ELK - REDIS)

## Installation

1. In the docker-compose file, indicate where's your Symfony project

    ```yml
    services:
        php:
            volumes:
                - path/to/your/symfony-project:/var/www/symfony
    ```

2. Build containers with (with and without detached mode)

    ```bash
    $ docker-compose up
    $ docker-compose up -d
    ```

3. Composer install & create database

    ```bash
    $ docker-compose exec php bash
    $ composer install
    $ sf doctrine:database:create
    $ sf doctrine:schema:update --force
    $ sf doctrine:fixtures:load --no-interaction
    ```

## Usage

Just run `docker-compose -d`, then:

* Symfony app: visit [symfony.dev](http://symfony.dev)  
* Symfony dev mode: visit [symfony.dev/app_dev.php](http://symfony.dev/app_dev.php)  
* Logs (Kibana): [symfony.dev:81](http://symfony.dev:81)
* Logs (files location): logs/nginx and logs/symfony

## How it works?

Have a look at the `docker-compose.yml` file, here are the `docker-compose` built images:

* `db`: This is the MySQL database container,
* `php`: This is the PHP-FPM container in which the application volume is mounted,
* `nginx`: This is the Nginx webserver container in which application volume is mounted too,
* `elk`: This is a ELK stack container which uses Logstash to collect logs, send them into Elasticsearch and visualize them with Kibana,

This results in the following running containers:

```bash
$ docker-compose ps
           Name                          Command               State              Ports            
--------------------------------------------------------------------------------------------------
dockersymfony_db_1            /entrypoint.sh mysqld            Up      0.0.0.0:3306->3306/tcp      
dockersymfony_elk_1           /usr/bin/supervisord -n -c ...   Up      0.0.0.0:81->80/tcp          
dockersymfony_nginx_1         nginx                            Up      443/tcp, 0.0.0.0:80->80/tcp
dockersymfony_php_1           php-fpm                          Up      0.0.0.0:9000->9000/tcp      
```

## Useful commands

```bash
# bash commands
$ docker-compose exec php bash

# Composer (e.g. composer update)
$ docker-compose exec php composer update

# SF commands (Tips: there is an alias inside php container)
$ docker-compose exec php php /var/www/symfony/app/console cache:clear
# Same command by using alias
$ docker-compose exec php bash
$ sf cache:clear

# MySQL commands
$ docker-compose exec db mysql -uroot -p"root"

# F***ing cache/logs folder
$ sudo chmod -R 777 app/cache app/logs

# Check CPU consumption
$ docker stats $(docker inspect -f "{{ .Name }}" $(docker ps -q))

# Delete all containers
$ docker rm $(docker ps -aq)

# Delete all images
$ docker rmi $(docker images -q)
```

## FAQ

* Got this error: `ERROR: Couldn't connect to Docker daemon at http+docker://localunixsocket - is it running?
If it's at a non-standard location, specify the URL with the DOCKER_HOST environment variable.` ?  
Run `docker-compose up -d` instead.

* Permission problem? See [this doc (Setting up Permission)](http://symfony.com/doc/current/book/installation.html#checking-symfony-application-configuration-and-setup)

* How I can add PHPMyAdmin?  
Simply add this: (then go to [symfony.dev:8080](http://symfony.dev:8080))

    ```
    phpmyadmin:
       image: corbinu/docker-phpmyadmin
       ports :
        - "8080:80"
       environment:
        - MYSQL_USERNAME=root
        - MYSQL_PASSWORD=root
       links:
        - db:mysql
    ```

        * Test Fixtures order  :
            sf doctrine:database:drop --force
            sf doctrine:database:create
            sf doctrine:schema:update --force
            sf doctrine:fixtures:load --no-interaction

        * Assets Installation order for JS/CSS  :
            sf assets:install web --symlink
            sf assetic:dump web
