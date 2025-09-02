FROM php:8.2-apache

# Instala dependencias del sistema
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    sqlite3 \
    && docker-php-ext-install pdo pdo_sqlite zip

# Instala Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copia archivos del proyecto
COPY . /var/www/html

# Define directorio de trabajo
WORKDIR /var/www/html

# Configura Apache para Laravel
RUN chown -R www-data:www-data /var/www/html \
    && a2enmod rewrite \
    && service apache2 restart

# Instala dependencias PHP
RUN composer install --no-interaction --optimize-autoloader \
    && php artisan key:generate \
    && touch database/database.sqlite \
    && php artisan migrate --force

# Expone el puerto 80
EXPOSE 80

# Comando para iniciar Apache
CMD ["apache2-foreground"]
