# 🔬 Girdi Kontrol Modülü

**Modül Adı:** `girdikontrol`  
**Kategori:** Kalite Kontrol Modülleri (QMS Core)  
**Öncelik:** 🔴 Kritik  
**Controller:** `application/modules/girdikontrol/controllers/Girdikontrol.php`

## 📋 Genel Bakış

Girdi Kontrol modülü, tedarikçilerden gelen hammadde ve malzemelerin kalite kontrolünü yapmak için kullanılır. Bu modül ISO kalite standartlarına uygun gelen malzeme muayene süreçlerini digitalize eder.

## 🎯 Ana Sorumluluklar

### 1. **Gelen Malzeme Kontrolü**
- Tedarikçiden gelen malzemelerin kayıt edilmesi
- Parti numarası ve irsaliye takibi
- Kalite kontrol parametrelerinin ölçülmesi
- Kabul/Red durumuna karar verilmesi

### 2. **Kalite Kriterleri Yönetimi**
- Malzeme bazlı ölçüm parametreleri
- Tolerans değerleri ve limit kontrolleri
- Ölçüm sonuçlarının kayıt edilmesi
- Kontrol noktalarının belgelenmesi

### 3. **Traceability (İzlenebilirlik)**
- Her parti için benzersiz kontrol numarası oluşturma
- Tarih ve kullanıcı bilgisi takibi
- Malzeme geçmişi ve durum takibi

## 🔧 Ana İşlevler

### Controller Method'ları:

| Method | Açıklama | HTTP |
|--------|----------|------|
| `index()` | Ana liste görünümü, arama ve pagination | GET |
| `new_form()` | Yeni girdi kontrol formu | GET |
| `save()` | Girdi kontrol kaydı ekleme | POST |
| `update_form($id)` | Kayıt düzenleme formu | GET |
| `update($id)` | Kayıt güncelleme | POST |
| `delete($id)` | Kayıt silme | POST |
| `gorsel_*()` | Görsel ekleme/düzenleme | GET/POST |
| `olcum_*()` | Ölçüm kayıtları yönetimi | GET/POST |

### Arama ve Filtreleme:
- **Tedarikçi adına göre** arama
- **Malzeme adına göre** arama  
- **Parti numarasına göre** arama
- Tarih aralığı filtreleme

## 🔗 Modül Bağımlılıkları

### Bağımlı Olduğu Modüller:
- **`tedarikciler`** - Tedarikçi bilgileri
- **`malzemeler`** - Malzeme kataloğu ve ölçüm kriterleri
- **`kontrol_no`** - Kontrol numarası yönetimi

### Bağımlı Olan Modüller:
- **`proseskontrol`** - Kabul edilen malzemeler proses kontrolüne geçer
- **`excel`** - Rapor export işlemleri
- **`etiket`** - Etiket yazdırma

## 📊 Veri Modeli

### Ana Tablolar:
```sql
girdi_kontrol_table:
- id (PK)
- parti_no (VARCHAR)
- tedarikci (VARCHAR) 
- malzeme (VARCHAR)
- irsaliye (VARCHAR)
- tarih (DATETIME)
- kullanici (VARCHAR)
- aciklama (TEXT)
- durum (ENUM: 'BEKLIYOR', 'KABUL', 'RED')
- kontrol_no_id (FK)
- created_at, updated_at
```

### JSON Ölçüm Verileri:
```json
{
  "olcum": {
    "k1": {
      "adi": "K1",
      "olcu": "10.5",
      "tolerans": "±0.2",
      "alt_limit": "10.3",
      "ust_limit": "10.7", 
      "olcum": "10.45",
      "sonuc": "OK",
      "kontrol_noktasi": "G1",
      "gorsel": "3"
    }
  }
}
```

## 🎯 İş Akışı

### 1. Yeni Girdi Kontrol Süreci:
```
1. Yeni Form Açma → new_form()
2. Tedarikçi Seçimi (tedarikciler tablosundan)
3. Malzeme Seçimi (malzemeler tablosundan)
4. Parti No ve İrsaliye Girişi
5. Kalite Kontrol Parametre Ölçümü
6. Sonuç Değerlendirmesi (KABUL/RED)
7. Kayıt → save()
8. Kontrol No Oluşturma (kontrol_no tablosuna)
```

### 2. Kalite Kontrol Değerlendirmesi:
```
Ölçüm Değeri ∈ [Alt Limit, Üst Limit] → KABUL
Ölçüm Değeri ∉ [Alt Limit, Üst Limit] → RED
```

## 🔒 Güvenlik Gereksinimleri

### Kritik Güvenlik Alanları:
- ✅ **XSS Koruması:** Tüm input/output'larda `htmlspecialchars()`
- ❌ **CSRF Koruması:** Eksik - eklenmeli
- ✅ **Input Validation:** Form validation aktif
- ✅ **SQL Injection:** Active Record kullanımı
- ❌ **File Upload:** Görsel upload güvenliği eksik

### Acil Düzeltmeler Gerekli:
```php
// TODO: CSRF token eklenmeli
echo '<input type="hidden" name="' . $this->security->get_csrf_token_name() . '" value="' . $this->security->get_csrf_hash() . '">';

// TODO: File upload validation
$config['allowed_types'] = 'gif|jpg|jpeg|png';
$config['max_size'] = 2048; // 2MB
```

## 📋 Test Senaryoları

### PHPUnit Testleri:
```php
// Gerekli testler:
- test_girdi_kontrol_create()
- test_malzeme_selection_validation()
- test_olcum_limit_check()
- test_durum_update()
- test_search_functionality()
- test_pagination()
```

### E2E Testler (Playwright):
1. **Yeni girdi kontrol oluşturma** workflow
2. **Arama ve filtreleme** işlevselliği  
3. **Ölçüm girişi** ve sonuç hesaplama
4. **Düzenleme** ve **silme** işlemleri

## 🚀 Performans Optimizasyonu

### Mevcut Sorunlar:
- Sayfalama 100 kayıt - çok yüksek
- Where clause'da LIKE kullanımı yavaş
- Index eksiklikleri

### Öneriler:
```sql
-- Index eklenmeli:
CREATE INDEX idx_girdi_parti_no ON girdi_kontrol_table(parti_no);
CREATE INDEX idx_girdi_tarih ON girdi_kontrol_table(tarih);
CREATE INDEX idx_girdi_tedarikci ON girdi_kontrol_table(tedarikci);
```

## 🎨 UI/UX İyileştirmeleri

### Mevcut Problemler:
- Loading state'leri eksik
- Success/Error feedback yetersiz
- Mobile responsive değil

### Öneriler:
- Ajax form submission
- Real-time validation
- Progress indicator'lar
- Mobile-first design

## 📈 Metrikler ve KPI'lar

### Takip Edilmesi Gerekenler:
- **Kabul Oranı:** KABUL / (KABUL + RED) * 100
- **Tedarikçi Performansı:** Tedarikçi bazlı kabul oranları
- **Kontrol Süresi:** Ortalama işlem tamamlama süresi
- **Ölçüm Doğruluğu:** Limit dışı ölçüm oranları

---

**Son Güncelleme:** 2025-01-07  
**Sorumlu Geliştirici:** QMS Team  
**Test Durumu:** ❌ Testler yazılmamış  
**Güvenlik Durumu:** ⚠️ Kritik güvenlik açıkları mevcut 