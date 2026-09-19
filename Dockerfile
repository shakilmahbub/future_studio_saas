FROM php:8.4-fpm

#install system dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    && rm -rf /var/lib/apt/lists/*

#install redis
RUN pecl install redis && docker-php-ext-enable redis    

#install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

#copy application
COPY . /var/www

#install laravel dependency
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

#copy entrypoint
COPY Docker/entrypoint.sh /usr/local/bin/entrypoint
RUN chmod +x /usr/local/bin/entrypoint

ENTRYPOINT ["Docker/entrypoint.sh"]
CMD ["php-fpm"]