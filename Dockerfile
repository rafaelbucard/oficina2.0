# Imagem oficial do PHP 8.3 com Apache
FROM php:8.3-apache

# Dependências do sistema e extensões PHP para PostgreSQL
RUN apt-get update \
    && apt-get install -y --no-install-recommends libpq-dev unzip git \
    && docker-php-ext-install pdo pdo_pgsql \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Composer (copiado da imagem oficial)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Instala o autoload primeiro (cache de camada)
COPY composer.json composer.lock ./
RUN composer install --no-interaction --no-dev --optimize-autoloader --no-scripts

# Copia o restante do código da aplicação
COPY . /var/www/html

EXPOSE 80
