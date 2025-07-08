# 🏢 Tedarikçiler Modülü

**Modül Adı:** `tedarikciler`  
**Kategori:** Kaynak Yönetimi Modülleri  
**Öncelik:** 🟡 Yüksek  
**Controller:** `application/modules/tedarikciler/controllers/Tedarikciler.php`

## 📋 Genel Bakış

Tedarikçiler modülü, şirkete malzeme temin eden tedarikçi firmaların bilgilerini yönetir. Bu modül kalite kontrol süreçlerinde tedarikçi bazlı takip ve değerlendirme yapmak için kritik önem taşır.

## 🎯 Ana Sorumluluklar

### 1. **Tedarikçi Bilgi Yönetimi**
- Firma bilgileri ve iletişim detayları
- Tedarikçi kodları ve kategorilendirme
- Yasal dokümantasyon takibi
- Kalite sertifikaları yönetimi

### 2. **Tedarikçi Performans Takibi**
- Kalite performans metrikleri
- Teslimat performansı
- Fiyat karşılaştırmaları
- Şikayet ve uygunsuzluk kayıtları

### 3. **Onaylı Tedarikçi Listesi (AVL)**
- Değerlendirme kriterleri
- Onay durumu yönetimi
- Audit ve değerlendirme kayıtları
- Risk değerlendirmesi

## 🔧 Ana İşlevler

### Controller Method'ları:

| Method | Açıklama | HTTP | Kullanım |
|--------|----------|------|----------|
| `index()` | Ana tedarikçi listesi | GET | Listeleme |
| `new_form()` | Yeni tedarikçi ekleme formu | GET | Form görünümü |
| `save()` | Yeni tedarikçi kaydetme | POST | Kayıt ekleme |
| `update_form($id)` | Tedarikçi düzenleme formu | GET | Düzenleme formu |
| `update($id)` | Tedarikçi bilgileri güncelleme | POST | Kayıt güncelleme |
| `delete($id)` | Tedarikçi silme | POST | Kayıt silme |
| `detail($id)` | Tedarikçi detay görünümü | GET | Detay sayfası |

### Arama ve Filtreleme:
- **Firma adına göre** arama
- **Tedarikçi koduna göre** arama
- **Şehir/Bölge filtreleme**
- **Onay durumu filtreleme**
- **Kategori filtreleme**

## 🔗 Modül Bağımlılıkları

### Bu modülün verilerini kullanan modüller:
- **`girdikontrol`** - Gelen malzeme tedarikçi takibi
- **`proseskontrol`** - Tedarikçi bazlı kalite izleme (referans)
- **`malzemeler`** - Malzeme-tedarikçi ilişkilendirmesi
- **`excel`** - Tedarikçi raporları ve performans analizi

### Bağımlı olduğu modüller:
- **`users`** - Kullanıcı session kontrolü

## 📊 Veri Modeli

### Ana Tablolar:
```sql
tedarikciler_table:
- id (PK)
- tedarikci_kodu (VARCHAR) - Unique tedarikçi kodu
- firma_adi (VARCHAR) - Firma ismi
- kategori (VARCHAR) - Tedarikçi kategorisi
- iletisim_kisi (VARCHAR) - İletişim sorumlusu
- telefon (VARCHAR) - Telefon numarası
- email (VARCHAR) - Email adresi
- adres (TEXT) - Firma adresi
- sehir (VARCHAR) - Şehir
- ulke (VARCHAR) - Ülke
- vergi_no (VARCHAR) - Vergi numarası
- kalite_belgesi (VARCHAR) - Kalite sertifikası bilgisi
- onay_durumu (ENUM) - 'ONAYLANDI', 'DEĞERLENDIRILIYOR', 'REDDEDILDI'
- degerlendirme_puani (DECIMAL) - 1-10 arası puan
- notlar (TEXT) - Ek notlar ve değerlendirmeler
- aktif (TINYINT) - 1=aktif, 0=pasif
- created_at, updated_at
```

### Performans Değerlendirme Kriterleri:
```json
{
  "kalite_metrikleri": {
    "kalite_orani": {
      "deger": "98.5",
      "birim": "%",
      "hedef": "95.0",
      "durum": "HEDEF_USTU"
    },
    "teslimat_performansi": {
      "deger": "94.2", 
      "birim": "%",
      "hedef": "90.0",
      "durum": "HEDEF_USTU"
    },
    "fiyat_rekabetciligi": {
      "deger": "7.8",
      "birim": "puan",
      "hedef": "6.0",
      "durum": "IYI"
    }
  }
}
```

## 🎯 İş Akışı

### 1. Yeni Tedarikçi Onay Süreci:
```
1. Tedarikçi Başvurusu/Kaydı → new_form()
   ↓
2. Temel Bilgiler Girişi (firma, iletişim)
   ↓
3. Dokümantasyon Kontrolü (vergi no, belgeler)
   ↓
4. Kalite Değerlendirmesi
   ↓
5. Pilot Sipariş Test Süreci
   ↓
6. Performans Değerlendirmesi
   ↓
7. Onay Kararı → 'ONAYLANDI'/'REDDEDILDI'
   ↓
8. AVL (Approved Vendor List) Ekleme
```

### 2. Tedarikçi Performans İzleme:
```
Girdi Kontrol Verileri → Kalite Performans Hesaplama →
Periyodik Değerlendirme → Puan Güncelleme → 
Risk Değerlendirmesi
```

## 🔒 Güvenlik Gereksinimleri

### Mevcut Güvenlik Durumu:
- ✅ **Session Check:** `get_active_user()` kontrolü
- ❌ **CSRF Protection:** Form'larda eksik
- ❌ **XSS Protection:** Input sanitization eksik
- ✅ **SQL Injection:** Active Record kullanımı
- ❌ **Data Validation:** Detaylı validation eksik

### Güvenlik Riskleri:
```php
// Hassas veriler:
1. Tedarikçi iletişim bilgileri
2. Fiyat ve maliyet verileri
3. Performans değerlendirmeleri
4. Ticari sır niteliğinde bilgiler
```

### Güvenlik İyileştirmeleri:
```php
// 1. Enhanced Validation
$this->form_validation->set_rules([
    [
        'field' => 'firma_adi',
        'label' => 'Firma Adı',
        'rules' => 'required|min_length[3]|max_length[100]'
    ],
    [
        'field' => 'email',
        'label' => 'Email',
        'rules' => 'required|valid_email'
    ],
    [
        'field' => 'vergi_no',
        'label' => 'Vergi No',
        'rules' => 'required|exact_length[10]|numeric'
    ]
]);

// 2. Data Encryption
$encrypted_data = $this->encryption->encrypt($sensitive_info);

// 3. Access Control
if(!$this->auth->has_permission('supplier_management')) {
    show_403();
}
```

## 📋 Test Senaryoları

### PHPUnit Testleri:
```php
// Kritik testler:
- test_tedarikci_create_with_valid_data()
- test_tedarikci_code_uniqueness()
- test_email_validation()
- test_vergi_no_format_validation()
- test_onay_durumu_workflow()
- test_performance_calculation()
- test_search_functionality()
- test_aktif_pasif_status()
```

### E2E Testler (Playwright):
1. **Yeni tedarikçi ekleme** complete workflow
2. **Onay durumu değiştirme** işlemleri
3. **Arama ve filtreleme** tüm kombinasyonları
4. **Düzenleme ve güncelleme** işlemleri
5. **Performans değerlendirme** girişi

## 🚀 Performans Optimizasyonu

### Mevcut Performans Sorunları:
```php
// Problem alanları:
1. Performans hesaplamalarında gereksiz query'ler
2. Arama işlemlerinde optimization eksik
3. Large dataset'lerde pagination yavaş
4. JSON data processing optimization eksik
```

### Optimizasyon Önerileri:
```sql
-- Performance Index'ler:
CREATE INDEX idx_tedarikci_kodu ON tedarikciler_table(tedarikci_kodu);
CREATE INDEX idx_firma_adi ON tedarikciler_table(firma_adi);
CREATE INDEX idx_onay_durumu ON tedarikciler_table(onay_durumu);
CREATE INDEX idx_aktif ON tedarikciler_table(aktif);
CREATE INDEX idx_sehir ON tedarikciler_table(sehir);

-- Composite index search için:
CREATE INDEX idx_tedarikci_search ON tedarikciler_table(aktif, onay_durumu, sehir);

-- Full-text search:
CREATE FULLTEXT INDEX idx_tedarikci_fulltext ON tedarikciler_table(firma_adi, iletisim_kisi, notlar);
```

### Cache Stratejisi:
```php
// Cache implementation:
- Onaylı tedarikçi listesi (30 dakika cache)
- Şehir/bölge bazlı grouping (1 saat cache)
- Performans metrikleri (15 dakika cache)
- Arama sonuçları (5 dakika cache)
```

## 📈 Tedarikçi Performans Metrikleri

### KPI Dashboard:
```php
// Tedarikçi değerlendirme metrikleri:
$kpi = [
    'kalite_performansi' => [
        'kabul_orani' => 'Girdi kontrolden geçme oranı',
        'sikayetsiz_teslimat' => 'Şikayetsiz teslimat sayısı',
        'kalite_sapma_orani' => 'Kalite kriterlerinden sapma'
    ],
    'teslimat_performansi' => [
        'zamaninda_teslimat' => 'Zamanında teslimat oranı',
        'tam_teslimat' => 'Tam miktarda teslimat oranı',
        'hasarsiz_teslimat' => 'Hasarsız teslimat oranı'
    ],
    'ticari_performans' => [
        'fiyat_stabilite' => 'Fiyat istikrarı',
        'odeme_uyumu' => 'Ödeme şartlarına uyum',
        'isbirligi_kalitesi' => 'İşbirliği ve iletişim kalitesi'
    ]
];
```

### Automatic Scoring System:
```php
// Otomatik puanlama algoritması:
public function calculate_supplier_score($supplier_id) {
    $kalite_orani = $this->get_quality_ratio($supplier_id);
    $teslimat_orani = $this->get_delivery_ratio($supplier_id);
    $maliyet_performansi = $this->get_cost_performance($supplier_id);
    
    $toplam_puan = ($kalite_orani * 0.4) + 
                   ($teslimat_orani * 0.3) + 
                   ($maliyet_performansi * 0.3);
    
    return round($toplam_puan, 2);
}
```

## 🎨 UI/UX İyileştirmeleri

### Mevcut UI Özellikleri:
- Basic CRUD operations
- Simple search functionality
- Pagination sistemi

### Geliştirme Önerileri:
```javascript
// Frontend enhancements:
1. Tedarikçi performans dashboard'u
2. Interactive map tedarikçi lokasyonları
3. Document upload functionality
4. Performance trend charts
5. Supplier comparison tool
6. Mobile-responsive supplier directory
7. QR kod ile hızlı erişim
```

### Advanced Features:
```php
// Feature roadmap:
1. Tedarikçi self-service portal
2. Document management system
3. Audit scheduling ve takip
4. Supplier risk assessment
5. Automated performance alerts
6. Integration with procurement system
```

## 🔧 Entegrasyon Noktaları

### ERP Integration:
```php
// Future integrations:
- Purchase order integration
- Invoice matching
- Payment processing
- Inventory management
- Cost accounting
```

### API Development:
```php
// REST API endpoints:
GET /api/suppliers - List all suppliers
GET /api/suppliers/{id} - Get supplier details
POST /api/suppliers - Create new supplier
PUT /api/suppliers/{id} - Update supplier
GET /api/suppliers/{id}/performance - Get performance metrics
POST /api/suppliers/{id}/evaluate - Submit evaluation
```

## 🎯 Development Roadmap

### Sprint 1 (1 hafta):
- ✅ Güvenlik açıklarını kapatma (CSRF, XSS)
- ✅ Enhanced validation rules
- ✅ Performance index'leri

### Sprint 2 (2 hafta):
- ✅ Performance calculation automation
- ✅ Dashboard widgets
- ✅ Document management

### Sprint 3 (1 ay):
- ✅ API development
- ✅ ERP integration preparation
- ✅ Mobile responsive design
- ✅ Advanced analytics

---

**Son Güncelleme:** 2025-01-07  
**Sorumlu Geliştirici:** QMS Team  
**Test Durumu:** ❌ Comprehensive test coverage gerekli  
**Güvenlik Durumu:** ⚠️ CSRF, XSS protection ve data encryption eklenecek  
**Performans Durumu:** 🟡 Index optimizasyonu ve cache layer eklenecek  
**Integration Status:** ✅ Kalite kontrol modülleri ile entegre  
**Business Impact:** 🟡 Tedarikçi performans takibi için kritik 