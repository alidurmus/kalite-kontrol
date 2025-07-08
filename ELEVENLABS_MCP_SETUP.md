# ElevenLabs MCP Kurulum ve Kullanım Kılavuzu

## 🎯 ElevenLabs MCP Nedir?

ElevenLabs MCP (Model Context Protocol) server'ı, Claude Desktop ve diğer MCP istemcilerinde ElevenLabs'ın güçlü AI ses teknolojilerini kullanmanızı sağlar.

## ✅ Kurulum Tamamlandı

✅ **ElevenLabs MCP v0.4.0** başarıyla kuruldu  
✅ **Tüm bağımlılıklar** yüklendi  
✅ **Konfigürasyon dosyası** hazırlandı

## 🔑 API Anahtarı Alma

1. [ElevenLabs hesabınıza](https://elevenlabs.io/app/settings/api-keys) giriş yapın
2. **API Keys** bölümüne gidin
3. **Create API Key** butonuna tıklayın
4. Anahtarı kopyalayın

**💡 Ücretsiz Plan:** Ayda 10,000 kredi

## ⚙️ Claude Desktop Konfigürasyonu

### Windows Kullanıcıları:

1. **Claude Desktop'ı kapatın**
2. **Windows + R** tuşlarına basın, `%APPDATA%` yazın ve Enter'a basın
3. `Claude` klasörüne gidin
4. `claude_desktop_config.json` dosyasını açın (yoksa oluşturun)
5. Aşağıdaki konfigürasyonu ekleyin:

```json
{
  "mcpServers": {
    "ElevenLabs": {
      "command": "python",
      "args": ["-m", "elevenlabs_mcp"],
      "env": {
        "ELEVENLABS_API_KEY": "YOUR_ACTUAL_API_KEY_HERE"
      }
    }
  }
}
```

6. `YOUR_ACTUAL_API_KEY_HERE` yerine gerçek API anahtarınızı yazın
7. **Claude Desktop'ı yeniden başlatın**

### macOS Kullanıcıları:

```bash
# Konfigürasyon dosyası konumu
~/Library/Application Support/Claude/claude_desktop_config.json
```

## 🎤 Kullanılabilir Özellikler

### 1. Text-to-Speech (Metin → Ses)
```
"Bu metni sesli okur musun?"
"Bu makaleyi podcast formatında oku"
```

### 2. Speech-to-Text (Ses → Metin)
```
"Bu ses dosyasını metne çevir"
"Toplantı kaydımı transkript et"
```

### 3. Voice Design (Ses Tasarımı)
```
"Film noir dedektifi gibi konuşan bir ses tasarla"
"Antik ejder karakteri için 3 farklı ses varyasyonu oluştur"
```

### 4. Voice Cloning (Ses Klonlama)
```
"Bu ses kaydından yeni bir ses klonla"
"Sesimi ortaçağ şövalyesi gibi dönüştür"
```

### 5. Conversational AI (Konuşmalı AI)
```
"Pizza sipariş eden bir AI agent oluştur"
"Müşteri hizmetleri için konuşmalı bot yap"
```

### 6. Sound Effects (Ses Efektleri)
```
"Yoğun ormanda fırtına ses efekti oluştur"
"Bilim kurgu uzay gemisi sesleri yap"
```

## 🧪 Test Etme

Claude Desktop'ta şu komutları deneyin:

```
"ElevenLabs ile 'Merhaba dünya' metnini seslendir"
"Bana güçlü bir ejder sesi tasarla"
"Bu ses dosyasını analiz et ve metne çevir"
```

## 🔧 Sorun Giderme

### MCP Server Başlamıyor
1. Claude Desktop'ı kapatın
2. Konfigürasyon dosyasını kontrol edin
3. API anahtarının doğru olduğundan emin olun
4. Claude Desktop'ı yeniden başlatın

### API Hatası
- API anahtarınızın geçerli olduğunu kontrol edin
- ElevenLabs hesabınızda kredi olduğundan emin olun
- İnternet bağlantınızı kontrol edin

### Log Dosyaları
**Windows:** `%APPDATA%\Claude\logs\mcp-server-elevenlabs.log`  
**macOS:** `~/Library/Logs/Claude/mcp-server-elevenlabs.log`

## 📚 Örnek Kullanım Senaryoları

### 1. Podcast Oluşturma
```
"Bu blog yazısını podcast formatında okuyacak profesyonel bir ses oluştur"
```

### 2. Çoklu Dil Desteği
```
"Bu İngilizce metni Türkçe aksanla oku"
```

### 3. Karakter Seslendirme
```
"Hikayemdeki her karakter için farklı ses tonları oluştur"
```

### 4. Eğitim İçeriği
```
"Bu ders notlarını öğretmen sesiyle seslendir"
```

## 🎉 Başarılı Kurulum!

ElevenLabs MCP artık Claude Desktop'ta kullanıma hazır. Ses teknolojilerinin gücünü keşfetmeye başlayabilirsiniz!

---

**📅 Kurulum Tarihi:** $(Get-Date -Format "yyyy-MM-dd HH:mm")  
**🔧 Sürüm:** ElevenLabs MCP v0.4.0  
**💻 Platform:** Windows PowerShell  
**🎯 Durum:** Kurulum Tamamlandı ✅ 