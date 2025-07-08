# 📦 Ürünler (Products) Modülü

**Path:** `application/modules/products`  
**Kategori:** Kaynak Yönetimi  
**Sorumluluk:** Ürün kataloğu yönetimi, gelişmiş resim yönetimi ve ürün bilgileri organizasyonu

---

## 📋 **MODÜL ÖZETİ**

Products modülü, modern ve kapsamlı bir ürün katalog sistemidir. Drag-drop resim yükleme, çoklu resim desteği, sıralama ve gelişmiş CRUD işlemleri sunar. Sistem genelinde kalite kontrol ve üretim süreçlerinde referans ürün bilgileri sağlar.

> **Not:** Sistemde iki ürün modülü bulunmaktadır:
> - **`products`** (Modern, resim odaklı) - ✅ **ÖNERİLEN**
> - **`urunler`** (Eski, ölçüm odaklı) - ⚠️ **LEGACY**

---

## 🏗️ **MİMARİ YAPISI**

### **Dosya Organizasyonu**
```
products/
├── controllers/
│   └── Products.php              # Ana controller
├── models/
│   ├── Product_model.php         # Ürün veri modeli
│   └── Product_image_model.php   # Resim yönetim modeli
└── views/
    ├── add/                      # Yeni ürün formu
    ├── list/                     # Ürün listesi + sıralama
    ├── update/                   # Ürün düzenleme formu
    └── image/                    # Gelişmiş resim yönetimi
        └── render_elements/      # Resim widget'ları
```

### **Veritabanı Şeması**
```sql
-- Ana ürün tablosu
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    rank INT DEFAULT 0,
    isActive BOOLEAN DEFAULT 1,
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Ürün resimleri tablosu  
CREATE TABLE product_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT,
    img_url VARCHAR(500),
    rank INT DEFAULT 0,
    FOREIGN KEY (product_id) REFERENCES products(id)
);
```

---

## ⚙️ **ANA İŞLEVLER**

### **1. Ürün CRUD İşlemleri**
```php
// Ana controller fonksiyonları
new_form()         // Yeni ürün formu
save()             // Ürün kaydetme (POST)
update_form($id)   // Düzenleme formu
update($id)        // Güncelleme (POST)
delete($id)        // Ürün silme
```

### **2. Gelişmiş Resim Yönetimi**
```php
// Resim işlemleri
image_upload($id)         // Dropzone ile çoklu upload
imageDelete($id, $parent_id)  // Tek resim silme
imageRankSetter()         // Ajax ile sıralama
refresh_image_list($id)   // Resim listesi yenileme
```

### **3. Modern UI Özellikleri**
- **Dropzone Integration:** Drag-drop file upload
- **Sortable Lists:** jQuery UI ile sıralama
- **Ajax Operations:** Sayfa yenileme olmadan işlemler
- **Empty States:** Kullanıcı dostu boş durum mesajları

---

## 🎨 **KULLANICI DENEYİMİ**

### **Ana Özellikler**
```html
<!-- Drag-Drop Upload -->
<form class="dropzone" data-plugin="dropzone">
    <div class="dz-message">
        <h3>Yüklemek istediğiniz resimleri buraya sürükleyiniz</h3>
        <p>(Dosyalarınızı sürükleyiniz veya tıklayınız)</p>
    </div>
</form>

<!-- Sortable Product List -->
<tbody class="sortable" data-url="products/rankSetter">
    <!-- Ajax ile sıralanabilir ürünler -->
</tbody>

<!-- Empty State -->
<div class="alert alert-info text-center">
    <p>Herhangi bir veri bulunmamaktadır. 
       <a href="products/new_form">Eklemek için tıklayınız</a>
    </p>
</div>
```

---

## 🛡️ **GÜVENLİK ANALİZİ**

### **✅ Mevcut Güvenlik Önlemleri**
```php
// XSS koruması aktif
$this->load->helper('security');

// Form validation
if(isset($form_error)){ 
    echo form_error("title"); 
}

// Authorization kontrolü
if(isAllowedWriteModule()){ 
    // Yazma işlemleri
}
```

### **❌ Kritik Güvenlik Açıkları**

1. **File Upload Güvenliği**
   ```php
   // ⚠️ RISK: Dosya türü kontrolü eksik
   // image_upload() fonksiyonunda
   
   // ✅ ÖNERİ: MIME type ve extension kontrolü
   $allowed_types = array('jpg', 'jpeg', 'png', 'gif');
   $file_info = pathinfo($_FILES['file']['name']);
   if (!in_array(strtolower($file_info['extension']), $allowed_types)) {
       throw new Exception('Geçersiz dosya türü');
   }
   ```

2. **CSRF Koruması Eksik**
   - Form'larda CSRF token yok
   - Ajax işlemlerinde token kontrolü yok

3. **SQL Injection Potansiyeli**
   ```php
   // ⚠️ Risk var mı kontrol et
   // Rank setter ve diğer dynamic query'lerde
   ```

4. **Path Traversal**
   - Resim upload path'lerinde validation eksik

---

## 🚀 **PERFORMANS ANALİZİ**

### **Mevcut Durum**
- Çoklu resim upload performans sorunu yaratabilir
- Database query'leri optimize edilmemiş
- Image resize/compression yok

### **Önerilen İyileştirmeler**
```php
// 1. Image optimization
class ImageProcessor {
    public function optimizeImage($file_path) {
        $image = new SimpleImage($file_path);
        $image->resize(800, 600, true); // Max boyut sınırı
        $image->save($file_path, 80);   // %80 kalite
    }
}

// 2. Database indexleri
CREATE INDEX idx_products_rank ON products(rank);
CREATE INDEX idx_products_active ON products(isActive);
CREATE INDEX idx_product_images_rank ON product_images(rank);

// 3. Cache layer
$this->load->driver('cache', array('adapter' => 'redis'));
$products = $this->cache->get('products_list');
if (!$products) {
    $products = $this->Product_model->get_all();
    $this->cache->save('products_list', $products, 300); // 5 dakika
}
```

---

## 🧪 **TEST REQUİREMENTS**

### **PHPUnit Birim Testleri (%85+ Coverage)**
```php
class ProductsTest extends PHPUnit\Framework\TestCase 
{
    // Product CRUD tests
    public function test_product_creation_with_valid_data()
    public function test_product_update_validation()
    public function test_product_deletion_cascade()
    
    // Image management tests  
    public function test_image_upload_success()
    public function test_image_upload_invalid_type()
    public function test_image_rank_update()
    public function test_image_deletion()
    
    // Security tests
    public function test_xss_protection_in_title()
    public function test_file_upload_security()
}
```

### **Playwright E2E Testleri**
```javascript
// tests/products/product-management.spec.js
test.describe('🛍️ Product Management', () => {
    test('Create new product with images @critical', async ({ page }) => {
        await page.goto('/products/new_form');
        await page.fill('[name="title"]', 'Test Product');
        await page.fill('[name="description"]', 'Test Description');
        await page.click('button[type="submit"]');
        
        // Image upload test
        await page.goto('/products/image/1');
        await page.setInputFiles('.dropzone', './test-files/product.jpg');
        await expect(page.locator('.dz-success')).toBeVisible();
    });
    
    test('Product list sorting @user-experience', async ({ page }) => {
        // Drag-drop sıralama testi
    });
});
```

---

## 📱 **UI/UX İYİLEŞTİRMELERİ**

### **Mevcut Sorunlar**
1. **Mobile Responsive:** Drag-drop mobile'da zor kullanılıyor
2. **Progress Feedback:** Upload progress bar yok
3. **Error Handling:** Resim upload hatalarında kullanıcı bilgilendirilmiyor

### **Önerilen İyileştirmeler**
```html
<!-- Progress bar -->
<div class="upload-progress" style="display:none;">
    <div class="progress">
        <div class="progress-bar" role="progressbar" style="width: 0%"></div>
    </div>
    <span class="upload-status">Yükleniyor...</span>
</div>

<!-- Mobile-friendly upload -->
<div class="mobile-upload-btn d-block d-md-none">
    <input type="file" id="mobile-file-input" multiple accept="image/*">
    <label for="mobile-file-input" class="btn btn-primary btn-block">
        📷 Fotoğraf Seç
    </label>
</div>

<!-- Better error messages -->
<div class="alert alert-danger upload-error" style="display:none;">
    <h5>Yükleme Hatası</h5>
    <ul id="upload-error-list"></ul>
</div>
```

---

## 🔧 **GELİŞTİRME ROADMAP**

### **Sprint 1: Güvenlik (1 hafta)**
- [ ] File upload güvenliği (MIME validation)
- [ ] CSRF token'ları ekle
- [ ] Path traversal koruması
- [ ] Image size/type restrictions

### **Sprint 2: Performance (3 gün)**
- [ ] Image optimization (resize/compress)
- [ ] Database indexleri
- [ ] Query optimization
- [ ] Cache layer ekle

### **Sprint 3: UX (5 gün)**
- [ ] Mobile responsive upload
- [ ] Progress bar ekle
- [ ] Error handling iyileştir
- [ ] Bulk operations (toplu silme/düzenleme)

### **Sprint 4: Tests (1 hafta)**
- [ ] PHPUnit test suite
- [ ] E2E scenarios
- [ ] Performance tests
- [ ] Security penetration tests

---

## 🔗 **MODÜL ENTEGRASYONLARİ**

| Bağlı Modül | İlişki Türü | Açıklama |
|-------------|-------------|----------|
| **girdikontrol** | FK Reference | Gelen malzeme ürün kontrolü |
| **proseskontrol** | FK Reference | Üretim ürün kalite kontrolü |
| **finalkontrol** | FK Reference | Final ürün kontrol |
| **etiketler** | Data Source | Ürün etiket basımı |
| **musteriler** | Business Logic | Müşteri-ürün ilişkisi |

---

## ⚡ **LEGACY MODÜL KARŞILAŞTIRMASI**

| Özellik | **Products** (Modern) | **Urunler** (Legacy) |
|---------|----------------------|---------------------|
| **Güvenlik** | ✅ XSS protection | ❌ Korumasız |
| **Resim Yönetimi** | ✅ Multi-upload + Dropzone | ❌ Temel upload |
| **UI/UX** | ✅ Modern, responsive | ❌ Eski tasarım |
| **Database** | `products`, `product_images` | `urunler`, `urun_olcum` |
| **Özellikler** | Sıralama, Ajax, Empty states | Basit CRUD |
| **Kod Kalitesi** | ✅ Organized, PSR-12 | ❌ Legacy code |

---

## 🏷️ **İLGİLİ ETIKETLER**

`#product-catalog` `#image-management` `#modern-ui` `#drag-drop` `#resource-management` `#ajax-operations`

---

## 📊 **KRİTİKLİK SKORU: 8/10**

**Neden Kritik:**
- Tüm kalite kontrol modülleri ürün bilgilerine bağımlı
- Etiket ve raporlama sisteminin temel veri kaynağı
- Modern UI/UX standardları için referans modül
- Multi-module integration point

**Öncelik:** 🟡 **YÜKSEK** - Güvenlik açıklarının kapatılması ve performans optimizasyonu öncelikli

---

*Doküman Sürümü: v1.0*  
*Son Güncelleme: 2025-01-07*  
*Sorumlu: QMS Geliştirme Ekibi*

---

## 💡 **GELİŞTİRİCİ NOTLARI**

1. **Migration Strategy:** `urunler` modülünden `products` modülüne veri taşıma planı hazırlanmalı
2. **Backup Strategy:** Resim dosyaları için otomatik backup sistemi kurulmalı
3. **CDN Integration:** Büyük resim dosyaları için CDN desteği değerlendirilmeli
4. **API Layer:** REST API endpoints mobile app entegrasyonu için eklenmeli 