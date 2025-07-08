# 🧪 **Playwright E2E Test Kuralları - QMS Projesi**

## **📋 Test Stratejisi ve Hedefler**

### **🎯 Ana Hedefler**
- **%100 Kritik Akış Kapsamı:** Tüm ana kullanıcı akışları test edilmelidir
- **Paralel Çalışma:** Testler paralel çalışabilir olmalıdır
- **Cross-Browser:** Chrome, Firefox, Safari desteği
- **Mobile Responsive:** Mobile görünüm testleri dahil

### **⚡ Test Öncelikleri**
1. **🔴 KRİTİK** - Login/Logout işlemleri
2. **🔴 KRİTİK** - Dashboard ana metrikleri
3. **🟡 YÜKSEK** - CRUD işlemleri (girdi, proses, final kontrol)
4. **🟡 YÜKSEK** - Arama ve filtreleme
5. **🟢 ORTA** - Excel export/import
6. **🟢 ORTA** - Rapor oluşturma

---

## **🏗️ Test Mimarisi - Page Object Model**

### **Klasör Yapısı**
```
tests/
├── e2e/
│   ├── specs/           # Test dosyaları
│   ├── pages/           # Page Object sınıfları
│   ├── fixtures/        # Test verileri
│   └── utils/           # Yardımcı fonksiyonlar
├── config/
│   ├── playwright.config.js
│   └── test-data.json
└── reports/
    └── html/
```

### **Page Object Pattern - ZORUNLU**
```javascript
// pages/LoginPage.js
class LoginPage {
  constructor(page) {
    this.page = page;
    this.usernameInput = page.locator('input[name="user"]');
    this.passwordInput = page.locator('input[name="password"]');
    this.loginButton = page.locator('button[type="submit"]');
  }
  
  async login(username, password) {
    await this.usernameInput.fill(username);
    await this.passwordInput.fill(password);
    await this.loginButton.click();
  }
  
  async isVisible() {
    await this.usernameInput.isVisible();
    await this.passwordInput.isVisible();
    await this.loginButton.isVisible();
  }
}
```

---

## **🎨 Test Yazım Kuralları**

### **1. Test Dosya Adlandırma**
- **Format:** `modulename.spec.js`
- **Örnekler:** 
  - `login.spec.js`
  - `girdikontrol.spec.js`
  - `dashboard.spec.js`

### **2. Test Case Adlandırma**
```javascript
// ✅ İYİ - Açıklayıcı ve Türkçe
test('Girdi kontrol modülünde yeni kayıt oluşturulabiliyor', async ({ page }) => {
  // test implementation
});

// ❌ KÖTÜ - Belirsiz
test('test1', async ({ page }) => {
  // test implementation
});
```

### **3. Test Gruplandırma**
```javascript
test.describe('Girdi Kontrol Modülü', () => {
  test.beforeEach(async ({ page }) => {
    await page.goto('/login');
    await loginPage.login('admin', '123456');
    await page.goto('/girdikontrol');
  });

  test('Kayıt listesi görüntüleniyor', async ({ page }) => {
    // test
  });
  
  test('Yeni kayıt oluşturulabiliyor', async ({ page }) => {
    // test
  });
});
```

---

## **🔍 Locator Stratejileri**

### **Öncelik Sırası (Tercih edilen → Az tercih edilen)**
1. **`data-testid`** - En stabil
2. **`role` ve accessible name** - Accessibility uyumlu
3. **`placeholder` / `label`** - Form elementleri için
4. **CSS selector** - Spesifik durumlar için
5. **XPath** - Son çare

### **Örnekler**
```javascript
// ✅ EN İYİ - data-testid
page.locator('[data-testid="submit-button"]')

// ✅ İYİ - Role based
page.getByRole('button', { name: 'Kaydet' })

// ✅ İYİ - Label based
page.getByLabel('Kullanıcı Adı')

// ⚠️ KABUL EDİLEBİLİR - CSS
page.locator('button.btn-primary')

// ❌ KAÇIN - XPath
page.locator('//button[@class="btn btn-primary"]')
```

---

## **⚙️ Test Konfigürasyonu**

### **playwright.config.js Gereksinimler**
```javascript
module.exports = defineConfig({
  testDir: './tests/e2e/specs',
  fullyParallel: true,
  forbidOnly: !!process.env.CI,
  retries: process.env.CI ? 2 : 0,
  workers: process.env.CI ? 1 : 4,
  reporter: [
    ['html'],
    ['json', { outputFile: 'test-results.json' }],
    ['junit', { outputFile: 'test-results.xml' }]
  ],
  use: {
    baseURL: process.env.BASE_URL || 'http://web/cms/panel',
    trace: 'on-first-retry',
    screenshot: 'only-on-failure',
    video: 'retain-on-failure',
  },
  projects: [
    {
      name: 'chromium',
      use: { ...devices['Desktop Chrome'] },
    },
    {
      name: 'firefox',
      use: { ...devices['Desktop Firefox'] },
    },
    {
      name: 'Mobile Chrome',
      use: { ...devices['Pixel 5'] },
    },
  ],
});
```

---

## **📊 Test Data Management**

### **Test Data Dosyası**
```javascript
// fixtures/test-data.json
{
  "users": {
    "admin": {
      "username": "admin",
      "password": "123456",
      "role": "administrator"
    },
    "operator": {
      "username": "operator",
      "password": "123456",
      "role": "operator"
    }
  },
  "girdikontrol": {
    "valid_record": {
      "tedarikci": "Test Tedarikçi",
      "malzeme": "Test Malzeme",
      "miktar": "100",
      "birim": "KG"
    }
  }
}
```

### **Fixture Kullanımı**
```javascript
// utils/test-helpers.js
export class TestDataManager {
  constructor() {
    this.data = require('../fixtures/test-data.json');
  }
  
  getUser(type = 'admin') {
    return this.data.users[type];
  }
  
  getGirdiKontrolData() {
    return this.data.girdikontrol.valid_record;
  }
}
```

---

## **🚀 Test Execution Komutları**

### **NPM Scripts (package.json)**
```json
{
  "scripts": {
    "test": "npx playwright test",
    "test:headed": "npx playwright test --headed",
    "test:debug": "npx playwright test --debug",
    "test:ui": "npx playwright test --ui",
    "test:chrome": "npx playwright test --project=chromium",
    "test:firefox": "npx playwright test --project=firefox",
    "test:mobile": "npx playwright test --project='Mobile Chrome'",
    "test:smoke": "npx playwright test --grep '@smoke'",
    "test:regression": "npx playwright test --grep '@regression'",
    "test:report": "npx playwright test --reporter=html",
    "show:report": "npx playwright show-report",
    "test:update-snapshots": "npx playwright test --update-snapshots"
  }
}
```

### **PowerShell Komutları**
```powershell
# Tüm testleri çalıştır
npm test

# Browser görünümde çalıştır
npm run test:headed

# Belirli test dosyasını çalıştır
npx playwright test login.spec.js

# Debug mode
npm run test:debug

# UI mode (interaktif)
npm run test:ui

# Sadece Chrome'da çalıştır
npm run test:chrome

# Smoke testleri çalıştır
npm run test:smoke

# Raporu görüntüle
npm run show:report
```

---

## **🏷️ Test Tagging ve Filtreleme**

### **Test Tag'leri**
```javascript
test.describe('Login İşlemleri', () => {
  test('Başarılı giriş @smoke @critical', async ({ page }) => {
    // kritik test
  });
  
  test('Hatalı giriş denemesi @regression', async ({ page }) => {
    // regression test
  });
});
```

### **Tag Kullanımı**
```bash
# Sadece smoke testleri
npx playwright test --grep "@smoke"

# Critical testleri
npx playwright test --grep "@critical"

# Belirli modül testleri
npx playwright test --grep "girdikontrol"
```

---

## **📈 Test Raporlama ve Monitoring**

### **Gerekli Metrikler**
- **Pass Rate:** > %95
- **Execution Time:** < 10 dakika (tüm testler)
- **Flaky Test Rate:** < %2
- **Coverage:** Ana akışlar %100

### **CI/CD Integration**
```yaml
# .github/workflows/e2e-tests.yml
name: E2E Tests
on: [push, pull_request]
jobs:
  test:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v3
      - uses: actions/setup-node@v3
      - run: npm ci
      - run: npx playwright install
      - run: npm run test:report
      - uses: actions/upload-artifact@v3
        with:
          name: playwright-report
          path: playwright-report/
```

---

## **🔧 Debugging ve Troubleshooting**

### **Debug Teknikleri**
```javascript
// Console log
await page.evaluate(() => console.log('Debug point'));

// Pause execution
await page.pause();

// Screenshot
await page.screenshot({ path: 'debug.png' });

// Wait for specific element
await page.waitForSelector('[data-testid="result"]');

// Network monitoring
page.on('response', response => {
  console.log(`${response.status()} ${response.url()}`);
});
```

### **Yaygın Sorunlar ve Çözümler**
1. **Timeout Error:** `page.setDefaultTimeout(30000)`
2. **Element Not Found:** `waitForSelector` kullan
3. **Flaky Tests:** `toHaveText` yerine `toContainText` kullan
4. **Performance:** `networkidle` yerine spesifik selector bekle

---

## **📋 Test Checklist - Her Test İçin**

### **Yazım Öncesi**
- [ ] Test senaryosu net tanımlandı
- [ ] Page Object pattern kullanıldı
- [ ] Test data hazırlandı
- [ ] Bağımlılıklar belirlendi

### **Yazım Sırasında**
- [ ] Açıklayıcı test adı verildi
- [ ] Uygun locator stratejisi kullanıldı
- [ ] Error handling eklendi
- [ ] Screenshot/video kayıt aktif

### **Yazım Sonrası**
- [ ] Test lokalden geçiyor
- [ ] Farklı browser'larda test edildi
- [ ] Mobile responsive kontrol edildi
- [ ] CI/CD pipeline'da çalışıyor

---

**💡 Test Mottosu:** "Her test, production'daki bir kullanıcı hikayesini temsil eder."

**Son Güncelleme:** 2025-01-07  
**Durum:** Aktif geliştirme - QMS v2.0 