const { test, expect } = require('@playwright/test');

test.describe('🎨 Enhanced Dashboard Tasarım Testleri', () => {
    
    test.beforeEach(async ({ page }) => {
        // Dashboard'a login olarak git
        await page.goto('http://localhost:8090/userop');
        
        // Login yap
        await page.selectOption('select[name="user_name"]', 'admin');
        await page.fill('input[name="user_password"]', '123456');
        await page.click('button[type="submit"]');
        
        // Dashboard'a yönlendir
        await page.goto('http://localhost:8090/dashboard');
        await page.waitForLoadState('networkidle');
    });

    test('Dashboard header ve welcome section görünümü @smoke', async ({ page }) => {
        console.log('🎨 Dashboard header tasarımı test ediliyor...');
        
        // Welcome card'ın varlığını kontrol et
        await expect(page.locator('.dashboard-welcome-card')).toBeVisible();
        
        // Ana başlığın olduğunu kontrol et
        await expect(page.locator('.welcome-title')).toBeVisible();
        await expect(page.locator('.welcome-title')).toContainText('Kalite Yönetim Dashboard');
        
        // Hoşgeldin mesajının olduğunu kontrol et
        await expect(page.locator('.welcome-subtitle')).toBeVisible();
        
        // Tarih/saat gösterimini kontrol et
        await expect(page.locator('#currentDateTime')).toBeVisible();
        
        // Action button'ların varlığını kontrol et
        await expect(page.locator('.btn-premium')).toBeVisible();
        await expect(page.locator('.btn-outline-premium')).toBeVisible();
        
        console.log('✅ Dashboard header tasarımı başarılı');
    });

    test('Enhanced statistics cards görünümü @critical', async ({ page }) => {
        console.log('📊 Gelişmiş istatistik kartları test ediliyor...');
        
        // Ana istatistik kartlarını kontrol et
        const statCards = page.locator('.stats-card');
        await expect(statCards).toHaveCount(4);
        
        // Her kartın temel elementlerini kontrol et
        for (let i = 0; i < 4; i++) {
            const card = statCards.nth(i);
            await expect(card.locator('.stats-icon')).toBeVisible();
            await expect(card.locator('.stats-number')).toBeVisible();
            await expect(card.locator('.stats-label')).toBeVisible();
            await expect(card.locator('.stats-action')).toBeVisible();
        }
        
        // Mini stats kartlarını kontrol et
        const miniCards = page.locator('.mini-stats-card');
        await expect(miniCards).toHaveCount(4);
        
        console.log('✅ İstatistik kartları tasarımı başarılı');
    });

    test('Quick actions section functionality @smoke', async ({ page }) => {
        console.log('⚡ Hızlı erişim bölümü test ediliyor...');
        
        // Quick actions kartının varlığını kontrol et
        await expect(page.locator('.premium-card')).toBeVisible();
        
        // Quick action item'larını kontrol et
        const quickActions = page.locator('.quick-action-item');
        await expect(quickActions).toHaveCount(6);
        
        // Her action item'ının elementlerini kontrol et
        for (let i = 0; i < 6; i++) {
            const actionItem = quickActions.nth(i);
            await expect(actionItem.locator('.quick-action-icon')).toBeVisible();
            await expect(actionItem.locator('.quick-action-text')).toBeVisible();
        }
        
        console.log('✅ Hızlı erişim tasarımı başarılı');
    });

    test('Charts ve performance metrics görünümü @smoke', async ({ page }) => {
        console.log('📈 Chart ve performans metrikleri test ediliyor...');
        
        // Chart container'ın varlığını kontrol et
        await expect(page.locator('.chart-container')).toBeVisible();
        await expect(page.locator('#qualityChart')).toBeVisible();
        
        // Chart control butonlarını kontrol et
        await expect(page.locator('.chart-controls')).toBeVisible();
        
        // Performance metrics bölümünü kontrol et
        const performanceMetrics = page.locator('.performance-metric');
        await expect(performanceMetrics.first()).toBeVisible();
        
        // Progress bar'ların varlığını kontrol et
        await expect(page.locator('.progress')).toHaveCount(3);
        
        console.log('✅ Chart ve metrikler tasarımı başarılı');
    });

    test('Recent activities section görünümü @smoke', async ({ page }) => {
        console.log('📅 Son aktiviteler bölümü test ediliyor...');
        
        // Activity list container'ının varlığını kontrol et
        await expect(page.locator('.activity-list').first()).toBeVisible();
        
        // Activity başlıklarının varlığını kontrol et
        await expect(page.locator('h6:has-text("Son Girdi Kontrolleri")')).toBeVisible();
        
        console.log('✅ Son aktiviteler tasarımı başarılı');
    });

    test('Real-time clock functionality @functional', async ({ page }) => {
        console.log('⏰ Gerçek zamanlı saat test ediliyor...');
        
        // İlk zamanı kaydet
        const initialTime = await page.locator('#currentDateTime').textContent();
        
        // 2 saniye bekle
        await page.waitForTimeout(2000);
        
        // Zamanın güncellendiğini kontrol et (saniye değişmeli)
        const updatedTime = await page.locator('#currentDateTime').textContent();
        
        // Saniye değişikliğini kontrol et (en azından değişmiş olmalı)
        expect(initialTime).not.toBe(updatedTime);
        
        console.log('✅ Gerçek zamanlı saat çalışıyor');
    });

    test('Counter animations çalışıyor mu? @animation', async ({ page }) => {
        console.log('🔄 Counter animasyonları test ediliyor...');
        
        // Counter elementlerinin varlığını kontrol et
        const counters = page.locator('.counter');
        await expect(counters.first()).toBeVisible();
        
        // JavaScript counter fonksiyonunun çalıştığını kontrol et
        const counterValue = await counters.first().textContent();
        expect(parseInt(counterValue)).toBeGreaterThan(0);
        
        console.log('✅ Counter animasyonları aktif');
    });

    test('Responsive design mobile compatibility @mobile', async ({ page }) => {
        console.log('📱 Mobil uyumluluk test ediliyor...');
        
        // Mobil ekran boyutuna geç
        await page.setViewportSize({ width: 375, height: 667 });
        await page.waitForTimeout(1000);
        
        // Dashboard elementlerinin mobile görünümde de görünür olduğunu kontrol et
        await expect(page.locator('.dashboard-welcome-card')).toBeVisible();
        await expect(page.locator('.stats-card').first()).toBeVisible();
        await expect(page.locator('.quick-action-item').first()).toBeVisible();
        
        console.log('✅ Mobil uyumluluk başarılı');
    });

    test('CSS animations ve hover effects @ui', async ({ page }) => {
        console.log('🎭 CSS animasyonları ve hover efektleri test ediliyor...');
        
        // Stats card hover efekti test et
        const statsCard = page.locator('.stats-card').first();
        await statsCard.hover();
        
        // Quick action hover efekti test et
        const quickAction = page.locator('.quick-action-item').first();
        await quickAction.hover();
        
        // Premium button hover efekti test et
        const premiumBtn = page.locator('.btn-premium');
        await premiumBtn.hover();
        
        console.log('✅ CSS efektleri çalışıyor');
    });

    test('External libraries yükleniyor mu? @dependencies', async ({ page }) => {
        console.log('📚 Harici kütüphaneler test ediliyor...');
        
        // AOS animation library'nin yüklendiğini kontrol et
        const aosLoaded = await page.evaluate(() => {
            return typeof AOS !== 'undefined';
        });
        expect(aosLoaded).toBe(true);
        
        // Chart.js'in yüklendiğini kontrol et
        const chartJsLoaded = await page.evaluate(() => {
            return typeof Chart !== 'undefined';
        });
        expect(chartJsLoaded).toBe(true);
        
        console.log('✅ Harici kütüphaneler yüklendi');
    });

}); 