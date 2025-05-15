FROM php:8-fpm-alpine

WORKDIR /var/www/html

COPY ./www /var/www/html
RUN apk update && apk add --no-cache postgresql-client
RUN apk add --no-cache \
    icu-dev \
    postgresql-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        intl \
        bcmath \
        opcache \
        pdo \
        pdo_pgsql \
        gd

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN chown -R www-data:www-data /var/www

RUN curl -L https://cs.symfony.com/download/php-cs-fixer-v3.phar -o php-cs-fixer && \
    chmod a+x php-cs-fixer && \
    mv php-cs-fixer /usr/local/bin/php-cs-fixer

USER www-data
