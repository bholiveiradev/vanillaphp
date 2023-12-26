FROM php:8-apache

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

RUN docker-php-ext-install pdo pdo_mysql
RUN apt-get update -y && apt-get install -y libwebp-dev libjpeg62-turbo-dev libpng-dev libxpm-dev \
    libfreetype6-dev
RUN apt-get update && \
    apt-get install -y \
    zlib1g-dev 
RUN apt-get install -y libzip-dev
RUN apt-get update && apt-get install -y \
    zlib1g-dev \
    libzip-dev
RUN docker-php-ext-install zip
# RUN docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
#     --with-png-dir=/usr/include/
RUN docker-php-ext-install gd
# RUN apt-get update && apt-get install -y libmagickwand-dev --no-install-recommends && rm -rf /var/lib/apt/lists/*
# RUN printf "\n" | pecl install imagick
# RUN docker-php-ext-enable imagick

RUN pecl install xdebug
RUN docker-php-ext-enable xdebug

RUN echo "alias test='XDEBUG_MODE=coverage ./vendor/bin/phpunit --coverage-html coverage/html/'" >> ~/.bashrc

RUN pecl install redis
RUN docker-php-ext-enable redis

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN a2enmod rewrite
RUN chmod -R 777 /var/www/html

WORKDIR /var/www/html