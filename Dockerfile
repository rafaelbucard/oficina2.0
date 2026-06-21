# Imagem oficial do PHP 8.3 com Apache
FROM php:8.3-apache

# Dependencias do sistema e extensoes PHP (PostgreSQL, mbstring e gd para o PDF)
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        libpq-dev libonig-dev libpng-dev libjpeg-dev libfreetype6-dev libzip-dev unzip git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_pgsql mbstring gd \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Composer (copiado da imagem oficial)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Document root aponta para public/ e habilita o mod_rewrite
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
COPY docker/000-default.conf /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite

WORKDIR /var/www/html

# Instala as dependencias a partir do lock (gera vendor/ e autoload)
COPY composer.json composer.lock ./
RUN composer install --no-interaction --no-dev --optimize-autoloader --no-scripts

# Copia o restante do codigo da aplicacao
COPY . /var/www/html

EXPOSE 80
