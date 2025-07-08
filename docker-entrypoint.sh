#!/bin/bash

# Docker ortamı için konfigürasyon dosyalarını değiştir
if [ "$ENVIRONMENT" = "docker" ]; then
    echo "Docker ortamı tespit edildi, konfigürasyon dosyaları güncelleniyor..."
    
    # Database konfigürasyonunu değiştir
    if [ -f "/var/www/html/application/config/database.docker.php" ]; then
        cp /var/www/html/application/config/database.docker.php /var/www/html/application/config/database.php
        echo "Database konfigürasyonu güncellendi"
    fi
    
    # Config dosyasını değiştir
    if [ -f "/var/www/html/application/config/config.docker.php" ]; then
        cp /var/www/html/application/config/config.docker.php /var/www/html/application/config/config.php
        echo "Config dosyası güncellendi"
    fi
    
    # İzinleri ayarla
    chown -R www-data:www-data /var/www/html/application/cache
    chown -R www-data:www-data /var/www/html/application/logs
    chown -R www-data:www-data /var/www/html/uploads
    chmod -R 777 /var/www/html/application/cache
    chmod -R 755 /var/www/html/application/logs
    chmod -R 777 /var/www/html/uploads
    
    echo "İzinler ayarlandı"
fi

# Apache'yi başlat
exec apache2-foreground 