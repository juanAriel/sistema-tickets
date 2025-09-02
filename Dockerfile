# Usa PHP 8.3 con Apache
FROM php:8.3-apache

# Instala extensiones necesarias
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    git \
    sqlite3 \
    libsqlite3-dev \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-install pdo pdo_sqlite zip

# Habilita mod_rewrite para Laravel
RUN a2enmod rewrite

# Copia configuración personalizada de Apache
COPY apache.conf /etc/apache2/sites-available/000-default.conf

# Instala Composer desde imagen oficial
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Establece el directorio de trabajo
WORKDIR /var/www/html

# Copia todos los archivos del proyecto Laravel
COPY . .

# Da permisos adecuados
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Establecer permisos adecuados a carpetas críticas
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache


# Instala dependencias y configura Laravel
RUN composer install --no-interaction --prefer-dist --optimize-autoloader \
    && cp .env.example .env \
    && php artisan key:generate \
    && touch database/database.sqlite \
    && chown www-data:www-data database/database.sqlite \
    && php artisan migrate --force \
    && php artisan db:seed --force

# Expone el puerto 80 (Apache)
EXPOSE 80

# Inicia Apache en primer plano
CMD ["apache2-foreground"]
