# Docker Symfony (PHP7-FPM - NGINX - MySQL - ELK - PMA)

## Installation

1. Setup

Please download (docker)[https://store.docker.com/editions/community/docker-ce-server-ubuntu]((mac version)[https://docs.docker.com/docker-for-mac/install/#download-docker-for-mac]) and (docker-compose)[https://docs.docker.com/compose/install/] before any start.

2. In the docker-compose file, indicate where's your Symfony project

    ```yml
    services:
        php:
            volumes:
                - path/to/your/symfony-project:/var/www/symfony
    ```

3. Build containers with (with and without detached mode)

    ```bash
    $ docker-compose up
    $ docker-compose up -d
    ```

4. (Optionnal) Dans le cas d'un dev local

    Get the ip from the nginx container then edit the file /etc/hosts to add the new dns. Her is a exemple :

    ```bash
    sudo echo "NGINX_IP MY_DNS" >> /etc/hosts
    # equal to
    sudo echo "172.17.100.1 symfony.dev" >> /etc/hosts
    ```

5. Dev mod

    Comment the app_dev.php file as follow to active the dev mod :

    ```php
    if (isset($_SERVER['HTTP_CLIENT_IP'])
        || isset($_SERVER['HTTP_X_FORWARDED_FOR'])
        || !(in_array(@$_SERVER['REMOTE_ADDR'], array('127.0.0.1', 'fe80::1', '::1')) || php_sapi_name() === 'cli-server')
    ) {
    //    header('HTTP/1.0 403 Forbidden');
    //    exit('You are not allowed to access this file. Check '.basename(__FILE__).' for more information.');
    }

    ```

5. Front dependency

    - If it's not allready done, install npm
        - go to the [node website](https://nodejs.org/en/download/) and download the wanted version
        ```bash
        $ cd /usr/local
        $ sudo tar --strip-components 1 -xJf ~/Downloads/node-v8.1.2-linux-x64.tar.xz
        $ source ~
        $ node -v
        $ npm -v
        ```
    - Go to the web dir and install dependency
        ```bash
        $ cd ./symfony/web
        $ npm install # may take some time
        ```

6. Usage front dependency

    - Recompile / repack front source
        ```bash
        $ cd ./symfony/web
        $ grunt default
        ```

7. Composer install & create database

    ```bash
    $ docker-compose exec php bash
    $ composer install
    $ sf doctrine:database:create
    $ sf doctrine:migrations:migrate --no-interaction
    $ sf doctrine:schema:validate -vvv
    $ sf doctrine:fixtures:load --no-interaction
    $ sf assetic:install --symlink
    $ sf assetic:dump web/
    ```

8. Alternatively you can run the genDb script

    ```bash
    $ cp script/genData.sh symfony
    $ docker-compose exec php zsh
    $ composer install
    $ ./genData.sh
    ```

## Usage

Just run `docker-compose -d`, then:

* Symfony app: visit [symfony.dev](http://symfony.dev)  
* Symfony dev mode: visit [symfony.dev/app_dev.php](http://symfony.dev/app_dev.php)  
* Logs (Kibana): [symfony.dev:81](http://symfony.dev:81)
* phpMyAdmin: [symfony.dev:8080](http://symfony.dev:8080)
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