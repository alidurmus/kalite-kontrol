# 📦 Malzemeler Modülü

**Modül Adı:** `malzemeler`  
**Kategori:** Kaynak Yönetimi Modülleri  
**Öncelik:** 🟡 Yüksek  
**Controller:** `application/modules/malzemeler/controllers/Malzemeler.php`

## 📋 Genel Bakış

Malzemeler modülü, üretimde kullanılan hammadde, yarı mamul ve yardımcı malzemelerin katalog yönetimini sağlar. Bu modül kalite kontrol süreçleri için temel referans verilerini içerir ve her malzemenin kalite parametrelerini tanımlar.

## 🎯 Ana Sorumluluklar

### 1. **Malzeme Kataloğu Yönetimi**
- Malzeme kodları ve isimleri
- Teknik özellikler ve spesifikasyonlar
- Birim tanımları (kg, adet, metre vs.)
- Kategori ve sınıflandırma

### 2. **Kalite Parametreleri Tanımlama**
- Her malzeme için kalite kriterleri
- Ölçüm parametreleri ve toleransları
- Kabul/Red limit değerleri
- Kontrol noktaları tanımlama

### 3. **Tedarikçi İlişkilendirme**
- Malzeme-tedarikçi eşleştirmesi
- Onaylı tedarikçi listeleri
- Kalite geçmişi takibi
- Alternatif tedarikçi yönetimi

## 🔧 Ana İşlevler

### Controller Method'ları:

| Method | Açıklama | HTTP | Kullanım |
|--------|----------|------|----------|
| `index()` | Ana malzeme listesi ve arama | GET | Listeleme |
| `new_form()` | Yeni malzeme ekleme formu | GET | Form görünümü |
| `save()` | Yeni malzeme kaydetme | POST | Kayıt ekleme |
| `update_form($id)` | Malzeme düzenleme formu | GET | Düzenleme formu |
| `update($id)` | Malzeme güncelleme | POST | Kayıt güncelleme |
| `delete($id)` | Malzeme silme | POST | Kayıt silme |
| `detail($id)` | Malzeme detay görünümü | GET | Detay sayfası |

### Arama ve Filtreleme:
- **Malzeme adına göre** arama
- **Malzeme koduna göre** arama
- **Kategori filtreleme**
- **Aktif/Pasif durum filtreleme**

## 🔗 Modül Bağımlılıkları

### Bu modülün verilerini kullanan modüller:
- **`girdikontrol`** - Gelen malzeme kalite kontrolü
- **`proseskontrol`** - Üretimde kullanılan malzeme takibi
- **`tedarikciler`** - Malzeme-tedarikçi ilişkilendirmesi
- **`excel`** - Malzeme raporları

### Bağımlı olduğu modüller:
- **`users`** - Kullanıcı session kontrolü

## 📊 Veri Modeli

### Ana Tablolar:
```sql
malzemeler_table:
- id (PK)
- malzeme_kodu (VARCHAR) - Unique malzeme kodu
- malzeme_adi (VARCHAR) - Malzeme ismi
- kategori (VARCHAR) - Malzeme kategorisi
- birim (VARCHAR) - Ölçü birimi (kg, adet, m vs.)
- aciklama (TEXT) - Detaylı açıklama
- teknik_ozellikler (TEXT) - Teknik spesifikasyonlar
- kalite_parametreleri (JSON) - Kalite kontrol kriterleri
- min_stok (DECIMAL) - Minimum stok seviyesi
- max_stok (DECIMAL) - Maximum stok seviyesi
- aktif (TINYINT) - 1=aktif, 0=pasif
- created_at, updated_at
```

### Kalite Parametreleri JSON Yapısı:
```json
{
  "kalite_kriterleri": {
    "boyut": {
      "parametre": "Uzunluk",
      "birim": "mm",
      "min_deger": "45.0",
      "max_deger": "45.5",
      "tolerans": "±0.2",
      "kritiklik": "YUKSEK"
    },
    "malzeme_kalitesi": {
      "parametre": "Yüzey pürüzlülüğü",
      "birim": "Ra",
      "min_deger": "1.6",
      "max_deger": "3.2",
      "tolerans": "±0.5",
      "kritiklik": "ORTA"
    },
    "kimyasal_ozellik": {
      "parametre": "Karbon oranı",
      "birim": "%",
      "min_deger": "0.15",
      "max_deger": "0.25",
      "tolerans": "±0.02",
      "kritiklik": "KRITIK"
    }
  }
}
```

## 🎯 İş Akışı

### 1. Yeni Malzeme Ekleme Süreci:
```
1. Malzeme Formu Açma → new_form()
   ↓
2. Temel Bilgiler Girişi (kod, ad, kategori)
   ↓
3. Teknik Özellikler Tanımlama
   ↓
4. Kalite Parametreleri Belirleme
   ↓
5. Stok Limitleri Ayarlama
   ↓
6. Kaydetme → save()
   ↓
7. Tedarikçi İlişkilendirme (Opsiyonel)
```

### 2. Kalite Parametresi Entegrasyonu:
```
Malzeme Tanımlama → Kalite Kriterleri → 
Girdi Kontrol Kullanımı → Ölçüm Validasyonu
```

## 🔒 Güvenlik Gereksinimleri

### Mevcut Güvenlik Durumu:
- ✅ **Session Check:** `get_active_user()` kontrolü
- ❌ **CSRF Protection:** Form'larda eksik
- ❌ **XSS Protection:** Input sanitization eksik
- ✅ **SQL Injection:** Active Record kullanımı
- ❌ **Input Validation:** Detaylı validation eksik

### Güvenlik İyileştirmeleri:
```php
// 1. Form Validation Rules
$this->form_validation->set_rules([
    [
        'field' => 'malzeme_kodu',
        'label' => 'Malzeme Kodu',
        'rules' => 'required|is_unique[malzemeler.malzeme_kodu]|alpha_numeric_spaces'
    ],
    [
        'field' => 'malzeme_adi',
        'label' => 'Malzeme Adı',
        'rules' => 'required|min_length[3]|max_length[100]'
    ]
]);

// 2. XSS Protection
$malzeme_adi = $this->security->xss_clean($this->input->post('malzeme_adi'));

// 3. CSRF Token
echo '<input type="hidden" name="' . $this->security->get_csrf_token_name() . '" value="' . $this->security->get_csrf_hash() . '">';
```

## 📋 Test Senaryoları

### PHPUnit Testleri:
```php
// Kritik testler:
- test_malzeme_create_with_valid_data()
- test_malzeme_code_uniqueness()
- test_kalite_parametreleri_validation()
- test_search_functionality()
- test_category_filtering()
- test_aktif_pasif_status()
- test_json_parameters_structure()
```

### E2E Testler (Playwright):
1. **Yeni malzeme ekleme** workflow
2. **Arama ve filtreleme** tüm kombinasyonları
3. **Düzenleme ve güncelleme** işlemleri
4. **Kalite parametre girişi** ve validasyon
5. **Silme işlemi** ve confirmation

## 🚀 Performans Optimizasyonu

### Mevcut Performans Sorunları:
```php
// Problem alanları:
1. JSON parametre parsing optimizasyonu eksik
2. Arama işlemlerinde LIKE kullanımı yavaş
3. Pagination 100 kayıt (çok yüksek)
4. Index eksiklikleri
```

### Optimizasyon Önerileri:
```sql
-- Kritik Index'ler:
CREATE INDEX idx_malzeme_kodu ON malzemeler_table(malzeme_kodu);
CREATE INDEX idx_malzeme_adi ON malzemeler_table(malzeme_adi);
CREATE INDEX idx_kategori ON malzemeler_table(kategori);
CREATE INDEX idx_aktif ON malzemeler_table(aktif);

-- Full-text search için:
CREATE FULLTEXT INDEX idx_malzeme_search ON malzemeler_table(malzeme_adi, aciklama);

-- Composite index:
CREATE INDEX idx_malzeme_aktif_kategori ON malzemeler_table(aktif, kategori);
```

### Cache Stratejisi:
```php
// Cache implementation:
- Aktif malzeme listesi (15 dakika cache)
- Kategori bazlı malzemeler (30 dakika cache)
- Kalite parametreleri (1 saat cache)
- Arama sonuçları (5 dakika cache)
```

## 🎨 UI/UX İyileştirmeleri

### Mevcut UI Özellikleri:
- Bootstrap 4 form styling
- Basic search functionality
- Pagination sistemi

### Geliştirme Önerileri:
```javascript
// Frontend enhancements:
1. Malzeme kodu auto-generation
2. Kalite parametreleri için dynamic form builder
3. JSON parameter editor (tree view)
4. Bulk import/export functionality
5. Mobile-responsive malzeme kataloğu
6. Barcode/QR kod entegrasyonu
```

### Advanced Features:
```php
// Feature roadmap:
1. Malzeme grouping ve hierarchy
2. Version control (malzeme revizyonları)
3. Supplier rating integration
4. Cost tracking integration
5. Environmental compliance tracking
```

## 📈 Malzeme Yönetimi Metrikleri

### KPI Takibi:
- **Aktif Malzeme Sayısı:** Toplam katalog büyüklüğü
- **Kategori Dağılımı:** Malzeme çeşitliliği
- **Kalite Parametre Kapsamı:** Tanımlı kriter oranı
- **Kullanım Sıklığı:** En çok kullanılan malzemeler

### Raporlama:
```php
// Malzeme rapor tipleri:
1. Malzeme kataloğu export (Excel/PDF)
2. Kalite parametreleri raporu
3. Kategori bazlı analiz
4. Tedarikçi-malzeme ilişki raporu
5. Stok level warnings
```

## 🔧 Teknik İyileştirmeler

### Code Quality:
```php
// Refactoring areas:
1. JSON parameter handling için service class
2. Validation rules'ları config dosyasına taşımak
3. Search functionality için repository pattern
4. Cache layer implementation
5. API endpoints oluşturmak
```

### Database Schema İyileştirmeleri:
```sql
-- Schema enhancements:
1. Malzeme versioning table
2. Malzeme-tedarikçi relation table
3. Kalite geçmişi tracking table
4. Kategori hierarchy table
5. Stok movement log table
```

## 🎯 Development Roadmap

### Sprint 1 (1 hafta):
- ✅ Güvenlik açıklarını kapatma (CSRF, XSS)
- ✅ Input validation iyileştirmesi
- ✅ Performance index'leri

### Sprint 2 (2 hafta):
- ✅ JSON parameter editor
- ✅ Advanced search functionality
- ✅ Cache implementation

### Sprint 3 (1 ay):
- ✅ API development
- ✅ Mobile responsive design
- ✅ Bulk operations
- ✅ Integration testing

---

**Son Güncelleme:** 2025-01-07  
**Sorumlu Geliştirici:** QMS Team  
**Test Durumu:** ❌ Comprehensive test coverage gerekli  
**Güvenlik Durumu:** ⚠️ CSRF ve XSS protection eklenecek  
**Performans Durumu:** 🟡 Index optimizasyonu ve cache layer eklenecek  
**Integration Status:** ✅ Kalite kontrol modülleri ile entegre 