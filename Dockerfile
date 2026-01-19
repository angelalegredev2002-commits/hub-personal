FROM richarvey/nginx-php-fpm:3.1.6

COPY . /var/www/html

# Instalar dependencias de PHP
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN composer install --no-dev --optimize-autoloader

# Configurar el directorio público de Laravel
ENV WEBROOT /var/www/html/public
ENV APP_TYPE laravel

EXPOSE 80