# Используем образ PHP
FROM php:8.1-fpm

# Установка необходимых пакетов и расширений
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    zip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql zip

# Установка расширения для поддержки MongoDB
RUN pecl install mongodb && docker-php-ext-enable mongodb

# Установка Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Устанавливаем рабочую директорию
WORKDIR /var/www/html

# Копируем зависимости проекта и устанавливаем их
COPY . .

# Устанавливаем зависимости через Composer
RUN composer install

# Команда для запуска веб-сервера Laravel
CMD php artisan serve --host=0.0.0.0 --port=8000
