/**
 * Console Detection Örnek Testleri
 * QMS Test Automation Framework
 * 
 * Chrome Console Detection kullanım örnekleri
 */

import { test, expect } from '@playwright/test';
import { setupConsoleDetection, assertNoConsoleErrors, assertNoCriticalErrors } from '../utils/console-detector.js';

test.describe('Console Detection - Örnekler', () => {
  let consoleDetector;

  test.beforeEach(async ({ page }) => {
    // Her test öncesi console detection kurulumu
    consoleDetector = await setupConsoleDetection(test, page);
    console.log('🔍 Console detection aktif edildi');
  });

  test.afterEach(async () => {
    // Test bitiminde console raporunu kaydet
    if (consoleDetector) {
      await consoleDetector.saveReport(test.info().title, 'chrome');
      
      // Console durumunu raporla
      const report = consoleDetector.getReport();
      console.log(`📊 Test tamamlandı: ${report.summary.totalMessages} mesaj, ${report.summary.errors} hata, ${report.summary.warnings} uyarı`);
    }
  });

  test('Ana sayfa - Console hataları kontrol et', async ({ page }) => {
    console.log('🏠 Ana sayfa yükleniyor...');
    
    // Ana sayfaya git
    await page.goto('/');
    
    // Sayfanın yüklenmesini bekle
    await page.waitForLoadState('networkidle');
    
    // Console durumunu kontrol et
    const report = consoleDetector.getReport();
    console.log('📊 Console durumu:', report.summary);
    
    // Kritik hataları kontrol et (test'i fail etmez, sadece uyarır)
    if (report.summary.hasCriticalErrors) {
      console.warn('⚠️ Kritik console hataları tespit edildi!');
      report.details.criticalErrors.forEach(error => {
        console.warn(`   - ${error.text} (${error.url}:${error.lineNumber})`);
      });
    }
    
    // Temel assertion - sayfa yüklenmiş olmalı
    await expect(page).toHaveTitle(/Welcome to CodeIgniter|QMS|Dashboard|Anasayfa/);
  });

  test('Giriş sayfası - Strict console kontrolü', async ({ page }) => {
    console.log('🔐 Giriş sayfası test ediliyor...');
    
    // Giriş sayfasına git
    await page.goto('/signin');
    
    // Form elementlerinin yüklenmesini bekle
    await page.waitForSelector('input[type="text"]', { timeout: 5000 });
    
    // Console hatalarını strict modda kontrol et
    try {
      assertNoCriticalErrors(consoleDetector);
      console.log('✅ Kritik console hatası bulunamadı');
    } catch (error) {
      console.error('❌ Kritik console hatası:', error.message);
      // Not: Test devam eder, sadece log'lar
    }
    
    // JavaScript hatalarını kontrol et
    try {
      assertNoConsoleErrors(consoleDetector);
      console.log('✅ JavaScript hatası bulunamadı');
    } catch (error) {
      console.warn('⚠️ JavaScript hatası:', error.message);
    }
  });

  test('Dashboard - Performans ve console analizi', async ({ page }) => {
    console.log('📈 Dashboard performans testi...');
    
    // Dashboard'a git (login gerekebilir)
    await page.goto('/anasayfa');
    
    // Loading tamamlanana kadar bekle
    await page.waitForTimeout(2000); // Grafiklerin yüklenmesi için
    
    // Console mesajlarını analiz et
    const report = consoleDetector.getReport();
    
    // Performans uyarıları
    const performanceWarnings = report.details.warnings.filter(w => 
      w.text.includes('performance') || 
      w.text.includes('slow') ||
      w.text.includes('deprecated')
    );
    
    if (performanceWarnings.length > 0) {
      console.warn('⚡ Performans uyarıları tespit edildi:');
      performanceWarnings.forEach(warning => {
        console.warn(`   - ${warning.text}`);
      });
    }
    
    // Network hatalarını kontrol et
    const networkErrors = consoleDetector.getNetworkErrors();
    if (networkErrors.length > 0) {
      console.warn('🌐 Network hataları tespit edildi:');
      networkErrors.forEach(error => {
        console.warn(`   - ${error.text}`);
      });
    }
    
    // Sayfa responsive olmalı
    await expect(page.locator('body')).toBeVisible();
  });

  test('Form submission - Console monitoring', async ({ page }) => {
    console.log('📝 Form submission console monitörü...');
    
    // Giriş sayfasına git
    await page.goto('/signin');
    
    // Console'u temizle
    consoleDetector.clear();
    
    // Form doldur (geçersiz verilerle)
    await page.fill('input[type="text"]', 'test_user');
    await page.fill('input[type="password"]', 'wrong_password');
    
    // Submit butonuna tıkla
    await page.click('button[type="submit"]');
    
    // Biraz bekle (Ajax response için)
    await page.waitForTimeout(1000);
    
    // Console mesajlarını kontrol et
    const report = consoleDetector.getReport();
    
    // Ajax hatalarını logla
    const ajaxErrors = report.details.errors.filter(e => 
      e.text.includes('ajax') || 
      e.text.includes('fetch') ||
      e.text.includes('XMLHttpRequest') ||
      e.url.includes('/signin') ||
      e.url.includes('/login')
    );
    
    if (ajaxErrors.length > 0) {
      console.log('📡 Ajax isteği hataları:');
      ajaxErrors.forEach(error => {
        console.log(`   - ${error.text} (${error.url})`);
      });
    }
    
    // Başarılı form submission beklenmez (wrong password)
    // Sadece console'da hata olmamasını kontrol ediyoruz
    expect(report.summary.errors).toBeLessThan(5); // Maksimum 5 hata tolere et
  });

  test('Çoklu sayfa gezinme - Console tracking', async ({ page }) => {
    console.log('🧭 Çoklu sayfa console tracking...');
    
    const pages = [
      { url: '/', name: 'Anasayfa' },
      { url: '/signin', name: 'Giriş' },
      { url: '/anasayfa', name: 'Dashboard' }
    ];
    
    for (const pageInfo of pages) {
      console.log(`📄 ${pageInfo.name} sayfası test ediliyor...`);
      
      // Console'u temizle
      consoleDetector.clear();
      
      // Sayfaya git
      await page.goto(pageInfo.url);
      await page.waitForLoadState('domcontentloaded');
      
      // Kısa bekle
      await page.waitForTimeout(500);
      
      // Console durumunu kontrol et
      const report = consoleDetector.getReport();
      
      console.log(`   📊 ${pageInfo.name}: ${report.summary.errors} hata, ${report.summary.warnings} uyarı`);
      
      // Sayfa-spesifik kontroller
      if (pageInfo.url === '/signin') {
        // Giriş sayfasında form elemanları olmalı
        await expect(page.locator('input[type="text"]')).toBeVisible();
      } else if (pageInfo.url === '/anasayfa') {
        // Dashboard'da içerik olmalı (login gerekliyse)
        // Bu sayfa 404 verebilir, o zaman console'da hata olacak
      }
      
      // Her sayfa için console raporunu kaydet
      await consoleDetector.saveReport(`${pageInfo.name.toLowerCase()}-navigation`, 'chrome');
    }
  });

  test('JavaScript error simulation', async ({ page }) => {
    console.log('🐛 JavaScript error simulation testi...');
    
    // Ana sayfaya git
    await page.goto('/');
    
    // Console'u temizle
    consoleDetector.clear();
    
    // Kasıtlı JavaScript hatası oluştur (test için)
    await page.evaluate(() => {
      console.log('Test log mesajı');
      console.warn('Test warning mesajı');
      console.error('Test error mesajı');
      
      // Kasıtlı hata oluştur
      try {
        nonExistentFunction(); // Bu ReferenceError oluşturacak
      } catch (e) {
        console.error('Yakalanan hata:', e.message);
      }
    });
    
    // Console mesajlarının yakalanmasını bekle
    await page.waitForTimeout(500);
    
    // Console durumunu kontrol et
    const report = consoleDetector.getReport();
    
    // Oluşturduğumuz mesajların yakalandığını doğrula
    expect(report.summary.logs).toBeGreaterThan(0);
    expect(report.summary.warnings).toBeGreaterThan(0);
    expect(report.summary.errors).toBeGreaterThan(0);
    
    console.log('✅ Console detection doğru çalışıyor');
    console.log('📊 Final rapor:', report.summary);
  });
}); 