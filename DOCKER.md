# Docker Kullanım Kılavuzu

Bu dosya, CMS Panel projesini Docker ile çalıştırma talimatlarını içerir.

## 🐳 Docker Kurulumu

### Gereksinimler
- Docker Desktop (Windows/Mac) veya Docker Engine (Linux)
- Docker Compose

### Kurulum Adımları

1. **Docker Container'ları Başlatma**
   ```bash
   docker-compose up -d
   ```

2. **Servisleri Kontrol Etme**
   ```bash
   docker-compose ps
   ```

3. **Logları İzleme**
   ```bash
   docker-compose logs -f web
   ```

## 🌐 Erişim Adresleri

- **Web Uygulaması**: http://localhost:8090
- **phpMyAdmin**: http://localhost:8081
- **MySQL**: localhost:3307 (host'tan erişim için)

## 📊 Servis Detayları

### Web Servisi
- **Container**: `codeigniter_web`
- **Port**: 8090:80
- **PHP Version**: 8.1
- **Web Server**: Apache

### Database Servisi
- **Container**: `mysql_db`
- **Port**: 3307:3306
- **Database**: `cms_panel`
- **Username**: `root`
- **Password**: (boş)

### phpMyAdmin
- **Container**: `phpmyadmin`
- **Port**: 8081:80

## 🔧 Yapılandırma

### Environment Variables
- `ENVIRONMENT=docker` - Docker ortamı için özel konfigürasyon

### Konfigürasyon Dosyaları
- `application/config/database.docker.php` - Docker için database ayarları
- `application/config/config.docker.php` - Docker için genel ayarlar

### Volume Mapping
- Kod dosyaları: `.:/var/www/html`
- Apache config: `./apache-config/000-default.conf`
- SQL init: `./uploads/sql:/docker-entrypoint-initdb.d`

## 🛠️ Geliştirme Komutları

### Container'ları Yeniden Başlatma
```bash
docker-compose restart
```

### Container'ları Durdurma
```bash
docker-compose stop
```

### Container'ları Silme
```bash
docker-compose down
```

### Volume'ları da Silme
```bash
docker-compose down -v
```

### Yeniden Build Etme
```bash
docker-compose build --no-cache
docker-compose up -d
```

## 🐛 Sorun Giderme

### Container Loglarını Kontrol Etme
```bash
# Web container logları
docker-compose logs web

# Database container logları
docker-compose logs db

# Tüm container logları
docker-compose logs
```

### Container İçine Erişme
```bash
# Web container'a bash ile erişim
docker-compose exec web bash

# Database container'a mysql ile erişim
docker-compose exec db mysql -u root -p cms_panel
```

### Dosya İzinleri Sorunu
```bash
# Container içinde izinleri düzeltme
docker-compose exec web chown -R www-data:www-data /var/www/html/application/cache
docker-compose exec web chown -R www-data:www-data /var/www/html/uploads
```

### Port Çakışması
Eğer 8090 portu kullanımdaysa, `docker-compose.yml` dosyasında farklı bir port belirtebilirsiniz:
```yaml
ports:
  - "8091:80"  # 8090 yerine 8091 kullan
```

## 📦 Production Deployment

### Production Docker Compose
```yaml
version: '3.8'
services:
  web:
    build: .
    environment:
      - ENVIRONMENT=production
    volumes:
      - ./uploads:/var/www/html/uploads
    ports:
      - "80:80"
```

### Environment Variables
```bash
# Production ortamı için
export ENVIRONMENT=production
export DB_HOST=your-db-host
export DB_NAME=your-db-name
export DB_USER=your-db-user
export DB_PASS=your-db-password
```

## 🔒 Güvenlik Notları

1. **Production'da şifreleri değiştirin**
2. **Gereksiz portları kapatın**
3. **SSL sertifikası kullanın**
4. **Database'i external network'e açmayın**
5. **Log dosyalarını düzenli olarak temizleyin**

## 📝 Notlar

- İlk çalıştırmada veritabanı otomatik olarak oluşturulur
- SQL dosyaları `uploads/sql` klasöründen otomatik import edilir
- Kod değişiklikleri otomatik olarak yansır (volume mapping sayesinde)
- Cache ve log dosyaları container'da kalır

## 🆘 Yardım

Sorun yaşıyorsanız:
1. Container loglarını kontrol edin
2. Port çakışması olup olmadığını kontrol edin
3. Docker ve Docker Compose sürümlerini kontrol edin
4. Disk alanının yeterli olduğunu kontrol edin 