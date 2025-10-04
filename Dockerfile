FROM php:8.2-apache

# Install ekstensi mysqli untuk koneksi MySQL
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# Aktifkan mod_rewrite (kalau perlu routing nanti)
RUN a2enmod rewrite

WORKDIR /var/www/html