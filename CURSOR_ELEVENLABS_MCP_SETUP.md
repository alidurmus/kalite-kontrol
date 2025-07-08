# Cursor IDE için ElevenLabs MCP Kurulum Kılavuzu

## 🎯 Cursor IDE + ElevenLabs MCP

Cursor IDE'de ElevenLabs'ın güçlü AI ses teknolojilerini kullanarak kod geliştirme sürecinizi ses ile destekleyin.

## ✅ Kurulum Durumu

✅ **ElevenLabs MCP v0.4.0** kuruldu  
✅ **Python bağımlılıkları** hazır  
✅ **API anahtarı** yapılandırıldı  
✅ **Cursor konfigürasyonu** hazırlandı

## 🔧 Cursor IDE Konfigürasyonu

### Windows Kullanıcıları:

1. **Cursor IDE'yi açın**
2. **Ctrl + Shift + P** tuşlarına basın
3. **"MCP: Configure"** komutunu arayın ve çalıştırın
4. Aşağıdaki konfigürasyonu ekleyin:

```json
{
  "mcpServers": {
    "ElevenLabs": {
      "command": "python",
      "args": ["-m", "elevenlabs_mcp"],
      "env": {
        "ELEVENLABS_API_KEY": "sk_668fa1c278c0be88f46ce5c7a0fc5e7c45185353da84b1e2"
      }
    }
  }
}
```

### Alternatif Kurulum:

**Konfigürasyon dosyası konumu:**
- Windows: `%APPDATA%\Cursor\User\globalStorage\mcp-config.json`
- macOS: `~/Library/Application Support/Cursor/User/globalStorage/mcp-config.json`
- Linux: `~/.config/Cursor/User/globalStorage/mcp-config.json`

## 🎤 Cursor'da Kullanılabilir Özellikler

### 1. Kod Açıklaması Seslendirme
```
"Bu fonksiyonun açıklamasını sesli olarak oku"
"Bu kodu podcast formatında anlat"
```

### 2. Kod Review Seslendirme
```
"Bu kod incelemesini ses ile yap"
"Bu pull request'i sesli olarak özetle"
```

### 3. Dokumentasyon Seslendirme
```
"Bu API dokümantasyonunu sesli oku"
"README dosyasını podcast formatında sun"
```

### 4. Hata Mesajları Seslendirme
```
"Bu hata mesajını açıkla ve seslendir"
"Debugging sürecini sesli anlat"
```

### 5. Kod Eğitimi
```
"Bu algoritmanın çalışma mantığını sesli anlat"
"Bu design pattern'i öğretici bir sesle açıkla"
```

## 🧪 Cursor'da Test Etme

Cursor IDE'nin chat bölümünde şu komutları deneyin:

```
"ElevenLabs ile bu fonksiyonun açıklamasını seslendir"
"Bu kodu farklı karakter sesleriyle anlat"
"Bu API dokümantasyonunu podcast formatında oku"
```

## 🎯 Geliştirici Senaryoları

### 1. Kod İnceleme Toplantıları
```
"Bu pull request'i toplantı formatında sesli sun"
"Kod değişikliklerini profesyonel bir sesle açıkla"
```

### 2. Eğitim İçeriği Oluşturma
```
"Bu kodu yeni başlayanlara öğretici bir sesle anlat"
"Bu tutorial'ı adım adım sesli rehber yap"
```

### 3. Kod Dokümantasyonu
```
"Bu API'nin kullanım kılavuzunu sesli hazırla"
"Bu fonksiyonların açıklamalarını ses dosyası yap"
```

### 4. Debugging Yardımı
```
"Bu hatanın çözümünü sesli anlat"
"Bu bug'ın nedenini detaylı sesli açıkla"
```

## 🔧 Sorun Giderme

### MCP Server Başlamıyor
1. Cursor IDE'yi yeniden başlatın
2. **Ctrl + Shift + P** → **"Developer: Reload Window"**
3. Terminal'de `python -m elevenlabs_mcp --help` komutunu test edin

### API Bağlantı Hatası
1. İnternet bağlantınızı kontrol edin
2. ElevenLabs hesabınızda kredi olduğundan emin olun
3. API anahtarının doğru olduğunu kontrol edin

### Cursor MCP Logları
- **Windows:** `%APPDATA%\Cursor\logs\`
- **macOS:** `~/Library/Logs/Cursor/`
- **Linux:** `~/.config/Cursor/logs/`

## 🚀 İleri Seviye Kullanım

### 1. Workspace Konfigürasyonu
Proje bazında farklı ses ayarları:

```json
{
  "mcpServers": {
    "ElevenLabs-Project": {
      "command": "python",
      "args": ["-m", "elevenlabs_mcp"],
      "env": {
        "ELEVENLABS_API_KEY": "sk_668fa1c278c0be88f46ce5c7a0fc5e7c45185353da84b1e2",
        "ELEVENLABS_MCP_BASE_PATH": "./audio-output"
      }
    }
  }
}
```

### 2. Otomatik Ses Çıktıları
```
"Her commit mesajını sesli oku"
"Build hatalarını sesli bildir"
"Test sonuçlarını sesli raporla"
```

### 3. Çoklu Dil Desteği
```
"Bu kodu İngilizce ve Türkçe sesli anlat"
"Bu dokümantasyonu farklı aksanlarla oku"
```

## 📊 Performans Optimizasyonu

### Ses Dosyası Önbellekleme
```json
{
  "env": {
    "ELEVENLABS_API_KEY": "sk_668fa1c278c0be88f46ce5c7a0fc5e7c45185353da84b1e2",
    "ELEVENLABS_MCP_CACHE_DIR": "./audio-cache",
    "ELEVENLABS_MCP_CACHE_TTL": "3600"
  }
}
```

### Ses Kalitesi Ayarları
```json
{
  "env": {
    "ELEVENLABS_VOICE_QUALITY": "high",
    "ELEVENLABS_OUTPUT_FORMAT": "mp3_44100_128"
  }
}
```

## 🎉 Cursor + ElevenLabs MCP Hazır!

Artık Cursor IDE'de kod geliştirirken ElevenLabs'ın ses teknolojilerini kullanabilirsiniz:

- **Kod açıklamaları** sesli dinleyin
- **Dokümantasyon** podcast formatında alın  
- **Hata mesajları** sesli açıklama
- **Code review** sesli sunum
- **Eğitim içeriği** ses dosyası oluşturma

---

**📅 Kurulum Tarihi:** 2025-01-08  
**🔧 Sürüm:** ElevenLabs MCP v0.4.0  
**💻 Platform:** Cursor IDE + Windows  
**🎯 Durum:** Kurulum Tamamlandı ✅

## 🔗 Faydalı Linkler

- [Cursor IDE MCP Docs](https://cursor.sh/mcp)
- [ElevenLabs API Docs](https://elevenlabs.io/docs)
- [MCP Protocol Spec](https://modelcontextprotocol.io/)
- [GitHub: ElevenLabs MCP](https://github.com/elevenlabs/elevenlabs-mcp) 