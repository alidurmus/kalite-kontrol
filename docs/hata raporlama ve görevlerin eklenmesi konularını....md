# **🏛️ Kalite Yönetim Sistemi - Hata Raporlama ve Görev Yönetimi v2.0**

**Amaç:** QMS projesi için hata raporlama, görev yönetimi ve test otomasyon süreçlerini organize etmek. Bu doküman, geliştirme ekibi için operasyonel rehber niteliğindedir.

**Hedefler:**

* **Hata Takibi:** Sistematik hata raporlama ve çözüm süreci
* **Görev Yönetimi:** Şeffaf ve verimli görev akış sistemi  
* **Test Otomasyonu:** PowerShell destekli modern test pipeline'ı
* **Raporlama:** `docs/reports/` klasörü altında organize edilmiş raporlar
* **Kalite Kontrol:** Sürekli entegrasyon ve dağıtım süreçleri

---

## **🛠️ 1. İş Akışı ve Görev Yönetimi**

Bu bölüm, görevlerin verimli ve senkronize bir şekilde yönetilmesi için tüm geliştiricilerin uyması gereken iş akışını tanımlar.

### **1.1. Görev Durumları ve Anlamları**

* 🔥 **URGENT:** ACİL! Canlı sistemdeki kritik hatalar. Her şeyden önce bu görevler ele alınmalıdır.
* ⏳ **BEKLEMEDE:** Görev, üzerinde çalışılmaya hazır ve bir geliştirici tarafından alınmayı bekliyor.
* 🔄 **ÇALIŞILIYOR:** Bir geliştirici görevi aktif olarak işliyor. **Diğerleri bu göreve dokunamaz.**
* 🧪 **TEST GEREKLİ:** Kodlama tamamlandı, ancak test kapsamı eksik veya E2E testlerinin doğrulanması gerekiyor.
* ✅ **TAMAMLANDI:** Görev başarıyla tamamlandı, test edildi ve bir hata içermiyor.
* ⚠️ **HATA / GÖZDEN GEÇİR:** Görevde bir sorun tespit edildi. Detaylı açıklama ile birlikte ⏳ BEKLEMEDE durumuna geri alınır.

### **1.2. Geliştirici Temel İş Akışı**

1. **Görev Seçimi:** TODO.md'den önce 🔥 URGENT görevleri, yoksa ⏳ BEKLEMEDE durumundaki en öncelikli görevi seç.
2. **Görevi Sahiplenme:** Görevin durumunu anında 🔄 ÇALIŞILIYOR olarak güncelle.
3. **Geliştirme:** İlgili kurallara uyarak görevi tamamla.
4. **Lokal Denetim:** Test komutlarını çalıştır ve başarılı olduğundan emin ol.
5. **Tamamlama:** Görevi ✅ TAMAMLANDI veya 🧪 TEST GEREKLİ olarak işaretle ve yapılan işlemleri özetleyen bir not ekle.

### **1.3. Yeni Görev Ekleme ve Hata Yönetimi**

* **Yeni Görev Ekleme:** Yeni görevler TODO.md dosyasına aşağıdaki formatta eklenmelidir:
  ```
  - ⏳ **[KATEGORİ]** Görevin net ve anlaşılır açıklaması.
    - *Gereksinimler: [Gereksinim 1], [Gereksinim 2]. Kabul Kriteri: [Kriter 1].*
  ```

* **Çalışma Sırasında Hata Yönetimi:**
  1. 🔄 ÇALIŞILIYOR durumundaki bir görevde hata ile karşılaşılırsa, durum ⚠️ HATA / GÖZDEN GEÇİR olarak güncellenir.
  2. Hatanın tanımı ve analizi eklenir.
  3. Görev, düzeltme gereksinimleri ile birlikte tekrar ⏳ BEKLEMEDE durumuna alınır.

* **Tamamlanmış Görevde Hata Tespiti:**
  1. ✅ TAMAMLANDI olarak işaretlenmiş bir görevde sonradan bir hata bulunursa, durum ⚠️ HATA / GÖZDEN GEÇİR olarak güncellenir.
  2. Hata raporu (Bug Report) ve etkileri açıklanır.
  3. Görevin düzeltilmesi için 🔥 URGENT veya ⏳ BEKLEMEDE olarak yeni bir görev oluşturulur.

---

## **🧪 2. Test Protokolü ve Otomasyon**

### **2.1. Test Mimarisi: Modern Test Pipeline**

* **🚀 Development Tests:** Geliştirme sırasında hızlı geri bildirim
* **🔄 Regression Tests:** Ana akış doğrulaması
* **📱 Cross-Browser Tests:** Chrome, Firefox, Mobile
* **📊 Reports:** Otomatik HTML raporları `docs/reports/` altında

### **2.2. Test Otomasyon Komutları (PowerShell Destekli)**

#### **Temel Test Komutları:**
```powershell
# E2E Test komutları
npm test                    # Tüm testleri çalıştır
npm run test:headed        # Browser görünümde test
npm run test:ui            # UI modunda test
npm run test:debug         # Debug modunda test

# Browser-specific testler
npm run test:chrome        # Sadece Chrome
npm run test:firefox       # Sadece Firefox  
npm run test:mobile        # Mobile Chrome

# Test kategorileri
npm run test:smoke         # Smoke testleri
npm run test:critical      # Kritik testler
npm run test:regression    # Regression testleri

# Raporlar
npm run test:report        # HTML raporu oluştur
npm run show:report        # Playwright sunucusu ile rapor aç
npm run reports:open       # Windows'ta doğrudan browser'da aç

# Temizlik
npm run clean:reports      # Tüm raporları sil
```

### **2.3. Test Raporları ve Dosya Yapısı**

#### **docs/reports/ Klasör Yapısı:**
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

---

## **📜 3. Raporlama Protokolü**

### **3.1. Raporlamanın Amacı**

* **Şeffaflık:** Projenin mevcut durumu hakkında tüm paydaşlara net bilgi sağlamak
* **Karar Destek:** Test sonuçlarına dayanarak deployment veya rollback kararlarını desteklemek
* **Sürekli İyileştirme:** Hata trendlerini tespit ederek süreçleri iyileştirmek

### **3.2. Otomatik Test Raporları**

* Her test çalıştırıldığında `docs/reports/` klasörüne otomatik rapor oluşturulur
* Raporlar HTML, JSON ve XML formatlarında üretilir
* **Zorunlu İçerik:**
  * **Genel Özet:** Rapor Durumu (✅/❌), Test Tarihi, Süresi, Ortamı
  * **İstatistikler:** Başarılı, Başarısız, Atlanan, Toplam Test Sayısı ve Başarı Oranı (%)
  * **Kalite Kapısı Sonucu:** ✅ DEPLOYMENT ONAYLANDI veya ❌ DEPLOYMENT ENGELLENDİ

### **3.3. Başarısız Test Detayları**

**FAIL raporları için:**
* **Test Dosyası:** Hatanın kaynağı olan test dosyası
* **Test Başlığı:** Başarısız olan testin tam adı
* **Hata Mesajı:** Playwright tarafından üretilen detaylı hata çıktısı
* **Kanıt:** Hata anına ait ekran görüntüsü veya video kaydının yolu

### **3.4. Hata Raporlama ve Aksiyon Planı**

* FAIL raporu oluştuğunda, dağıtım süreci **otomatik olarak durdurulur**
* Başarısız olan testle ilgili görev, TODO.md'de 🔥 URGENT olarak işaretlenir
* Geliştirici hatayı düzelttikten sonra, test süreci yeniden tetiklenir

---

## **📈 4. Platform Sağlık Durumu (Health Monitoring)**

### **4.1. Ana Sağlık Göstergeleri**

* **Sunucu Durumu:** 🟢 Stabil
* **Test Coverage:** 🟢 Hedeflere ulaşıldı (%85+ Unit, %100 E2E)
* **Browser Compatibility:** 🟢 Chrome, Firefox, Mobile
* **PowerShell Integration:** 🟢 Tam destekli
* **Rapor Sistemi:** 🟢 Otomatik HTML/JSON/XML

### **4.2. Performans Hedefleri**

* **Test Execution Time:** < 10 dakika (tüm testler)
* **Pass Rate:** > %95
* **Flaky Test Rate:** < %2
* **Report Generation:** < 30 saniye

---

## **🔧 5. Geliştirme Ortamı ve Araçlar**

### **5.1. PowerShell Entegrasyonu**

* Tüm npm komutları PowerShell'de sorunsuz çalışır
* Windows-specific komutlar optimize edilmiştir
* Cross-platform uyumluluk sağlanmıştır

### **5.2. Test Araçları**

* **E2E Testler:** Playwright v1.53.2+
* **Test Reports:** HTML/JSON/XML formatları
* **Cross-Browser:** Chrome, Firefox, Mobile Chrome
* **Screenshots & Videos:** Otomatik hata belgelendirmesi

### **5.3. Kalite Kontrol**

#### **Page Object Model Pattern:**
```javascript
// tests/pages/LoginPage.js
class LoginPage {
  constructor(page) {
    this.page = page;
    this.userInput = page.locator('input[name="user"]');
    this.passwordInput = page.locator('input[name="password"]');
    this.submitButton = page.locator('button[type="submit"]');
  }

  async login(username, password) {
    await this.userInput.fill(username);
    await this.passwordInput.fill(password);
    await this.submitButton.click();
  }
}
```

#### **Test Kategorileri:**
```javascript
// @smoke - Temel işlevsellik testleri
test('@smoke Login functionality', async ({ page }) => {
  // test implementation
});

// @critical - Kritik iş akışları
test('@critical Dashboard metrics loading', async ({ page }) => {
  // test implementation
});

// @regression - Gerileme testleri
test('@regression Full user journey', async ({ page }) => {
  // test implementation
});
```

---

## **🚀 6. CI/CD ve Deployment**

### **6.1. Deployment Kalite Kapıları**

1. **Unit Tests:** %85+ coverage
2. **E2E Tests:** %100 critical paths
3. **Cross-Browser:** Chrome, Firefox, Mobile
4. **Performance:** < 10 dakika test süresi

### **6.2. Otomatik Deployment Süreci**

```yaml
# GitHub Actions örneği
name: QMS Test & Deploy
on: [push, pull_request]

jobs:
  test:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v3
      - name: Setup Node.js
        uses: actions/setup-node@v3
        with:
          node-version: '18'
      
      - name: Install dependencies
        run: npm install
      
      - name: Run E2E Tests
        run: npm run test:report
      
      - name: Upload Test Reports
        uses: actions/upload-artifact@v3
        with:
          name: test-reports
          path: docs/reports/
```

---

## **📋 7. Kalite Kontrol Checklist**

### **Her Feature için Zorunlu Kontroller:**
- [ ] Playwright testleri yazıldı (@smoke, @critical, @regression)
- [ ] Cross-browser test edildi (Chrome, Firefox, Mobile)
- [ ] Page Object Model pattern kullanıldı
- [ ] Test raporları `docs/reports/` altında oluşturuyor
- [ ] PowerShell komutları sorunsuz çalışıyor
- [ ] Screenshots ve video kayıtları otomatik oluşuyor
- [ ] HTML raporu browser'da açılabiliyor
- [ ] JSON/XML raporları CI/CD için hazır
- [ ] Test execution süresi < 10 dakika
- [ ] Pass rate > %95

---

## **🔗 8. İlgili Dokümantasyon**

### **Detaylı Kurallar:**
- **Test Kuralları:** [`.cursor/rules/testing.md`](../.cursor/rules/testing.md)
- **Kod Kalitesi:** [`.cursor/rules/code-quality.md`](../.cursor/rules/code-quality.md)
- **Ana Kurallar:** [`.cursorrules`](../.cursorrules)

### **Rapor Dokümantasyonu:**
- **Test Raporları:** [`docs/reports/README.md`](./reports/README.md)

---

**Proje Mottosu:** "Kalite sadece kontrol ettiğimiz bir sonuç değil, kodumuzun her satırına ve test sürecimizin her adımına işlediğimiz bir kültürdür."

**🔴 KRİTİK:** Test başarısızlıkları (Hemen çözülmeli)
**🟡 YÜKSEK:** Performance ve UX iyileştirmeleri (1-2 hafta içinde)  
**🟢 ORTA:** Test coverage iyileştirmeleri (1 ay içinde)
**🔵 DÜŞÜK:** Long-term optimizasyonlar (3 ay içinde)

---

**Sürüm:** v2.0 (Modern test pipeline ve PowerShell desteği)
**Son Güncelleme:** 2025-01-07
**Durum:** Aktif geliştirme - Production ready