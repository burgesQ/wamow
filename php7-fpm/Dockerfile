# See https://github.com/docker-library/php/blob/4677ca134fe48d20c820a19becb99198824d78e3/7.0/fpm/Dockerfile
FROM php:7.0-fpm

RUN usermod -u 1000 www-data
RUN usermod -G staff www-data

# install dep
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    emacs \
    zsh \
    libbz2-dev \
    zlib1g-dev


# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set timezone
RUN rm /etc/localtime
RUN ln -s /usr/share/zoneinfo/Europe/Paris /etc/localtime
RUN "date"

# Type docker-php-ext-install to see available extensions
RUN docker-php-ext-install pdo pdo_mysql bz2 zip

# Silent composer as root warning
ENV COMPOSER_ALLOW_SUPERUSER 1

# Install zsh and prezto
RUN cd ~ ; \
    git clone -b phpFpm --single-branch --recursive https://github.com/burgesQ/prezto ~/.zprezto ; \
    cd ./.zprezto ; \
    ./install

WORKDIR /var/www/symfony
