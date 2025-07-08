FROM php:8.1-apache

# Apache için gerekli ortam değişkenlerini tanımla
ENV APACHE_RUN_USER www-data
ENV APACHE_RUN_GROUP www-data
ENV APACHE_LOG_DIR /var/log/apache2
ENV APACHE_RUN_DIR /var/run/apache2
ENV APACHE_PID_FILE /var/run/apache2/apache2.pid

# Gerekli PHP eklentilerini ve kütüphaneleri yükle
RUN apt-get update && apt-get install -y \
    libjpeg-dev \
    libpng-dev \
    libzip-dev \
    libfreetype6-dev \
    zip \
    unzip \
    git \
    curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd mysqli pdo pdo_mysql zip mbstring

# Apache mod_rewrite'ı etkinleştir
RUN a2enmod rewrite

# Uygulama kodunu kopyala
COPY . /var/www/html

# Entrypoint script'i kopyala ve çalıştırılabilir yap
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# İzinleri ayarla (logs, cache ve uploads dizinleri için)
RUN mkdir -p /var/www/html/application/cache/sessions \
    && mkdir -p /var/www/html/application/logs \
    && mkdir -p /var/www/html/uploads \
    && chown -R www-data:www-data /var/www/html/application/logs \
    && chown -R www-data:www-data /var/www/html/application/cache \
    && chown -R www-data:www-data /var/www/html/uploads \
    && chmod -R 755 /var/www/html/application/logs \
    && chmod -R 777 /var/www/html/application/cache \
    && chmod -R 777 /var/www/html/uploads

WORKDIR /var/www/html

# Environment variable'ı ayarla
ENV ENVIRONMENT=docker

EXPOSE 80

# Entrypoint script'i kullan
ENTRYPOINT ["docker-entrypoint.sh"] 