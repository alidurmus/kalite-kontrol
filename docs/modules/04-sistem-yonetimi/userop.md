# 🔐 User Operations (Userop) Modülü

**Modül Adı:** `userop`  
**Kategori:** Sistem Yönetimi Modülleri  
**Öncelik:** 🔴 **KRİTİK** - ŞU ANDA BOZUK  
**Controller:** `application/modules/userop/controllers/Userop.php`

## 🚨 ACİL DURUM RAPORU

**Mevcut Durum:** ❌ ÇALIŞMIYOR  
**Son Test:** 2025-01-07  
**Hata Durumu:** HTTP 403 Forbidden on POST requests  
**Etki:** Sistemde kimse login olamıyor  

## 📋 Genel Bakış

Userop modülü, kullanıcı authentication (giriş/çıkış) işlemlerini yönetir. Bu modül çalışmadığında sistemin hiçbir bölümüne erişilemez, bu nedenle en kritik öncelikli modüldür.

## 🎯 Ana Sorumluluklar

### 1. **Kullanıcı Authentication**
- Kullanıcı adı ve şifre doğrulama
- Session yönetimi
- Login/logout işlemleri
- Remember me functionality

### 2. **Role-based Redirection**
- Admin kullanıcıları → Dashboard
- Kalite kullanıcıları → Anasayfa/Kalite  
- Diğer roller → İlgili modüller
- Yetkisiz erişim engelleme

### 3. **Session Management**
- User session oluşturma
- Session expiry kontrolü
- Security token yönetimi
- Multi-device login kontrolü

## 🔧 Ana İşlevler

### Controller Method'ları:

| Method | Açıklama | HTTP | Durum |
|--------|----------|------|-------|
| `index()` | Login sayfası görünümü | GET | ✅ Çalışıyor |
| `do_login()` | **Login işlemi** | **POST** | ❌ **403 FORBIDDEN** |
| `do_logout()` | Logout işlemi | GET | ✅ Çalışıyor |

### Login İş Akışı:
```php
// application/modules/userop/controllers/Userop.php
public function do_login() {
    // 1. Form validation
    // 2. User model'den kullanıcı doğrulama  
    // 3. Session oluşturma
    // 4. Role-based redirect
}
```

## 🔗 Modül Bağımlılıkları

### Bağımlı Olduğu Modüller:
- **`users`** - Kullanıcı verileri (users table)
- **`user_roles`** - Rol yetkileri (opsiyonel)

### Bağımlı Olan Modüller:
- **`dashboard`** - Login sonrası ana sayfa
- **`anasayfa`** - Kalite kullanıcıları için
- **BÜTÜN SİSTEM** - Authentication gateway

## 📊 Veri Modeli

### Ana Tablolar:
```sql
users:
- id (PK)
- user_name (VARCHAR) - Login için kullanılır
- password (VARCHAR) - MD5 hash (güvensiz!)
- email (VARCHAR)
- isActive (TINYINT) - 1=aktif, 0=pasif
- role (INT) - 1=Admin, 2=Kalite, 3=Operatör vs.
- full_name (VARCHAR)
- created_at, updated_at
```

### Authentication Flow:
```php
// Kullanıcı doğrulama süreci:
1. user_name kontrolü (users tablosunda)
2. password MD5 karşılaştırması
3. isActive = 1 kontrolü
4. Role-based session data oluşturma
```

## 🚨 KRİTİK PROBLEMLER

### 1. **HTTP 403 Forbidden Hatası**
```bash
# Mevcut durum:
curl -X POST -d "user_name=admin&user_password=123456" http://localhost:8090/userop/do_login
# Sonuç: HTTP 403 - "The action you have requested is not allowed"
```

**Muhtemel Nedenler:**
- ❌ CSRF validation aktif olup config yanlış
- ❌ CodeIgniter config.docker.php yüklenmiyor
- ❌ Apache mod_security engelleme
- ❌ POST data parsing sorunu

### 2. **Güvenlik Zafiyetleri**
```php
// PROBLEM: MD5 password hashing (2025'te güvensiz!)
$password = md5($user_password); // ❌ Çok zayıf!

// ÇÖZÜMü: Modern password hashing
$password = password_hash($user_password, PASSWORD_DEFAULT); // ✅
```

### 3. **CSRF Configuration Conflict**
```php
// config.php: 
$config['csrf_protection'] = TRUE;

// config.docker.php:
$config['csrf_protection'] = FALSE;

// Problem: Docker environment config yüklenmiyor!
```

## 🔒 Güvenlik Analizi

### Mevcut Güvenlik Durumu:
- ❌ **MD5 Password:** Kritik güvenlik riski
- ❌ **CSRF Protection:** Doğru yüklenmiyor
- ❌ **XSS Protection:** Eksik
- ❌ **Brute Force Protection:** Yok
- ❌ **Session Security:** Temel

### Acil Güvenlik Düzeltmeleri:
```php
// 1. Modern Password Hashing
public function migrate_passwords() {
    // Tüm MD5 password'leri modern hash'e çevir
    $users = $this->user_model->get_all();
    foreach($users as $user) {
        $new_hash = password_hash($user->plain_password, PASSWORD_DEFAULT);
        $this->user_model->update($user->id, ['password' => $new_hash]);
    }
}

// 2. Enhanced Login Validation
public function do_login() {
    // Rate limiting
    if($this->check_login_attempts() > 5) {
        $this->block_ip();
        return;
    }
    
    // Strong validation
    if(!$this->validate_login_form()) {
        $this->log_failed_attempt();
        return;
    }
}
```

## 🚀 Acil Çözüm Planı

### Phase 1: Acil Onarım (1-2 saat)
```php
// 1. CSRF bypass (geçici)
$config['csrf_exclude_uris'] = array('userop/do_login');

// 2. Environment debug
echo 'ENV: ' . ENVIRONMENT; // 'docker' olmalı

// 3. Config loading verify
var_dump($this->config->item('csrf_protection')); // FALSE olmalı
```

### Phase 2: Güvenlik Sağlamlaştırma (1 gün)
```php
// 1. Password hash migration
// 2. Proper CSRF implementation
// 3. Brute force protection
// 4. Session security hardening
```

### Phase 3: Modern Authentication (1 hafta)
```php
// 1. JWT token implementation
// 2. 2FA support
// 3. API authentication
// 4. SSO integration ready
```

## 📋 Test Senaryoları

### Acil Debug Testleri:
```bash
# 1. Environment check
curl -s http://localhost:8090/login | grep "Environment:"

# 2. CSRF status check  
curl -s http://localhost:8090/login | grep "csrf_protection"

# 3. Config verification
curl -s "http://localhost:8090/debug/config" # Debug endpoint gerekli
```

### PHPUnit Testleri:
```php
// Kritik testler (yazılmalı):
- test_login_with_valid_credentials()
- test_login_with_invalid_credentials()
- test_csrf_protection_works()
- test_session_creation()
- test_role_based_redirect()
- test_brute_force_protection()
- test_password_hashing()
```

### E2E Testler (Playwright):
```javascript
// Login flow testleri:
1. Navigate to login page
2. Enter valid credentials  
3. Submit form
4. Verify dashboard redirect
5. Test logout functionality
6. Test remember me feature
```

## 🎯 Hata Ayıklama Adımları

### 1. CSRF Debug:
```php
// Userop controller'a geçici debug ekle:
public function debug_csrf() {
    echo "CSRF Protection: " . ($this->config->item('csrf_protection') ? 'TRUE' : 'FALSE') . "<br>";
    echo "Environment: " . ENVIRONMENT . "<br>";
    echo "Config loaded: " . (file_exists(APPPATH.'config/config.docker.php') ? 'YES' : 'NO') . "<br>";
}
```

### 2. Apache Log Analysis:
```bash
# Docker container error logs
docker-compose logs web | grep "403"
docker-compose exec web tail -f /var/log/apache2/error.log
```

### 3. CodeIgniter Debug:
```php
// application/config/config.docker.php debug:
$config['log_threshold'] = 4; // All logs
$config['enable_profiler'] = TRUE; // SQL profiling
```

## ⚡ Acil Düzeltme Kodları

### Geçici CSRF Bypass:
```php
// application/config/config.docker.php
$config['csrf_exclude_uris'] = array(
    'userop/do_login',
    'ajax/*'
);
```

### Enhanced Error Logging:
```php
// Userop controller'da:
public function do_login() {
    log_message('debug', 'Login attempt started');
    log_message('debug', 'POST data: ' . print_r($this->input->post(), true));
    log_message('debug', 'CSRF: ' . $this->config->item('csrf_protection'));
    
    // Existing login logic...
}
```

### Emergency Admin Reset:
```sql
-- Emergency password reset (MD5 of "admin123")
UPDATE users SET password = '0192023a7bbd73250516f069df18b500' WHERE user_name = 'admin';
UPDATE users SET isActive = 1 WHERE user_name = 'admin';
```

## 📈 Sistem Kritikliği

**Öncelik Seviyesi:** 🔴 KRITIK  
**Sistem Etki:** %100 (Sistem kullanılamaz)  
**İş Etkisi:** %100 (Kalite kontrol durur)  
**Müşteri Etkisi:** Yüksek (Üretim aksayabilir)

## 🎯 Aksiyon Planı

### HEMEN (0-2 saat):
1. ✅ CSRF konfigürasyonu debug
2. ✅ Environment loading verification  
3. ✅ Apache error log analysis
4. ✅ Geçici bypass implementation

### BUGÜN (2-8 saat):
1. ✅ Root cause identification
2. ✅ Permanent fix implementation
3. ✅ Security vulnerability assessment
4. ✅ Basic test coverage

### BU HAFTA (1-7 gün):
1. ✅ Password hash modernization
2. ✅ Enhanced security features
3. ✅ Comprehensive testing
4. ✅ Documentation update

---

**Son Güncelleme:** 2025-01-07  
**Durum:** ❌ SİSTEM ÇALIŞMIYOR  
**Sorumlu Geliştirici:** QMS Team - ACİL MÜDAHALEsi  
**Test Durumu:** ❌ Login testleri başarısız  
**Güvenlik Durumu:** 🔴 Kritik güvenlik açıkları mevcut  
**Çözüm Süresi:** Maximum 2 saat (Business Critical) 