FROM php:7.4-apache
RUN apt-get update
RUN apt-get install -y libzip-dev libjpeg62-turbo-dev libpng-dev libfreetype6-dev libonig-dev
RUN docker-php-ext-install pdo_mysql mbstring zip exif pcntl gd && docker-php-ext-enable pdo_mysql
#COPY . /app
RUN mkdir /sunu-app
COPY docker/vhost.conf /etc/apache2/sites-available/000-default.conf
RUN chown -R www-data:www-data /sunu-app && a2enmod rewrite
WORKDIR /sunu-app
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY composer.json ./
RUN composer install