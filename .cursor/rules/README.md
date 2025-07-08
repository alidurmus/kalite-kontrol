# 🏛️ **QMS Projesi Cursor Kuralları**

Bu dizin QMS (Kalite Yönetim Sistemi) projesi için Cursor AI asistanının uyması gereken kuralları içerir.

## 📁 **Kural Dosyaları**

### **1. 🧪 Test Kuralları** → [`testing.md`](./testing.md)
- Playwright E2E test stratejisi
- Page Object Model pattern
- Test yazım kuralları
- Locator stratejileri
- Test data management
- CI/CD integration

### **2. 📂 Kod Kalitesi** → [`code-quality.md`](./code-quality.md)  
- PSR-12 standartları
- Güvenlik kuralları (XSS, CSRF, SQL Injection)
- Service Layer pattern
- Exception handling
- PHPDoc dokümantasyonu
- Performance optimizasyonu

### **3. 🏗️ Mimari Kuralları** → [`architecture.md`](./architecture.md)
- HMVC mimarisi
- Modül bağımsızlığı
- Dependency injection
- Database design patterns

### **4. 🔒 Güvenlik Kuralları** → [`security.md`](./security.md)
- Input validation
- Output encoding
- Authentication patterns
- Authorization controls
- Secure coding practices

## 🎯 **Temel Prensipler**

### **Güvenlik Öncelikli**
- Tüm user input'lar validate edilmeli
- XSS koruması zorunlu
- SQL injection koruması aktif
- CSRF token'ları gerekli

### **Test Driven Development**
- Her kritik fonksiyon test edilmeli
- E2E testler ana akışları kapsamalı
- Test coverage %85+ hedeflenmeli

### **Clean Architecture**
- Service layer pattern zorunlu
- Fat controller yasak
- Single responsibility principle
- Dependency inversion

### **Performance Odaklı**
- Database query optimizasyonu
- Caching stratejileri
- Lazy loading uygulaması
- Frontend optimizasyonu

## 🚀 **Hızlı Başlangıç**

### **Yeni Feature Geliştirme Süreci:**
1. **Planlama:** Requirements analizi ve test senaryoları
2. **Test Yazımı:** E2E testleri önce yaz
3. **Service Layer:** Business logic implementation
4. **Controller:** Thin controller pattern
5. **View:** XSS korumalı output
6. **Güvenlik:** Validation ve sanitization
7. **Performance:** Query optimization ve caching

### **Code Review Checklist:**
```markdown
- [ ] PSR-12 compliant
- [ ] Security measures implemented
- [ ] Tests written and passing
- [ ] Documentation complete
- [ ] Performance considerations
- [ ] Error handling comprehensive
```

## 📋 **Komutlar**

```bash
# Test komutları
npm test                 # Tüm testleri çalıştır
npm run test:ui         # UI modunda test
npm run test:debug      # Debug mode

# Kod kalitesi
composer style:check    # PSR-12 kontrolü
composer analyze        # PHPStan analizi
composer audit          # Tüm quality checks
```

## 🔗 **İlgili Dosyalar**

- **Ana Kural Dosyası:** [`/.cursorrules`](../../.cursorrules)
- **Playwright Config:** [`/playwright.config.js`](../../playwright.config.js)
- **Package.json:** [`/package.json`](../../package.json)
- **TODO Liste:** [`/TODO.md`](../../TODO.md)

---

**📌 Not:** Bu kurallar QMS v2.0 için optimize edilmiştir ve sürekli güncellenecektir.

**Son Güncelleme:** 2025-01-07  
**Versiyon:** 2.0.0 