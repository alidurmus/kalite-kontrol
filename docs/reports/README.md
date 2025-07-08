# 📊 **Test Raporları ve Dokümantasyon**

Bu klasör QMS projesi test raporlarını ve sonuçlarını içerir.

## 📁 **Klasör Yapısı**

```
docs/reports/
├── playwright/           # HTML test raporları
│   ├── index.html       # Ana rapor sayfası
│   └── data/            # Rapor verileri
├── test-results/        # JSON/XML test sonuçları
│   ├── results.json     # JSON formatında sonuçlar
│   └── results.xml      # JUnit XML formatı
├── coverage/            # Kod kapsamı raporları
├── screenshots/         # Test screenshot'ları
└── videos/              # Test videoları
```

## 🎯 **Rapor Türleri**

### **1. HTML Raporları** (`playwright/`)
- **Amaç:** Interactive test sonuçları görüntüleme
- **İçerik:** Test geçiş/başarısızlık durumları, timeline, screenshots
- **Erişim:** Browser'da `docs/reports/playwright/index.html` açın

### **2. JSON Sonuçları** (`test-results/results.json`)
- **Amaç:** Programatik analiz ve CI/CD integration
- **İçerik:** Detaylı test metadata, timing bilgileri
- **Kullanım:** Analytics ve dashboard'lar için

### **3. JUnit XML** (`test-results/results.xml`)
- **Amaç:** CI/CD ve test management araçları integration
- **İçerik:** Standardize test sonuçları
- **Kullanım:** Jenkins, Azure DevOps, GitHub Actions

## 🚀 **Rapor Oluşturma Komutları**

```bash
# HTML raporu oluştur ve aç
npm run test:report
npm run reports:open        # Windows için doğrudan browser'da aç
npm run show:report         # Playwright sunucusu ile aç

# Tüm testleri çalıştır ve raporla
npm test

# Belirli test kategorileri
npm run test:smoke        # Smoke testleri + rapor
npm run test:critical     # Kritik testler + rapor
npm run test:regression   # Regression testleri + rapor

# Temizlik
npm run clean:reports     # Tüm raporları sil ve klasör yapısını yeniden oluştur
```

## 📈 **Rapor Analizi**

### **Başarı Metrikleri**
- **Pass Rate:** > %95 hedeflenir
- **Execution Time:** < 10 dakika (tüm testler)
- **Flaky Test Rate:** < %2
- **Browser Compatibility:** Chrome, Firefox, Mobile

---

**📌 Not:** Bu raporlar geliştirme sürecinde kalite kontrolü için kritik öneme sahiptir.

**Son Güncelleme:** 2025-01-07  
**Versiyon:** 2.0.0 