###
### Build Nginx development image
###
FROM nginx:1.26 as nginx-dev

# Copies scripts and configs into image filesystem
COPY nginx/default.conf /etc/nginx/conf.d/

# Starts Nginx
CMD ["nginx", "-g", "daemon off;"]

###
### Build PHP development image
###
FROM php:8.3-fpm as php-dev

# Installs extra libraries
RUN apt-get update && apt-get install -y \
    zlib1g-dev \
    libpng-dev \
    git \
    zip \
    unzip

# Installs PHP extensions
RUN docker-php-ext-install \
    gd \
    pdo_mysql \
    opcache

# Installs Composer
ENV COMPOSER_ALLOW_SUPERUSER=1
ARG COMPOSER_VERSION=2.7.2
RUN curl -sS https://getcomposer.org/installer | php -- \
        --filename=composer \
        --install-dir=/usr/local/bin \
        --version=${COMPOSER_VERSION} \
    && composer clear-cache

# Setup Xdebug
ARG XDEBUG_ENABLED=false
RUN if $XDEBUG_ENABLED; then pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && echo "xdebug.mode=debug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.max_nesting_level=1000" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    ; fi

# Expands the rights for www-data user
RUN usermod -u 1000 www-data

WORKDIR /home/php-pro