const { test, expect } = require('@playwright/test');

test.describe('Dashboard Sayfası', () => {
  test('Dashboard başlığı ve istatistik kartları görünüyor', async ({ page }) => {
    await page.goto('/dashboard');
    await expect(page.locator('h1, h2, h3')).toContainText([/Dashboard|İstatistik|Kalite/i]);
    // İstatistik kartları
    await expect(page.locator('.card, .stat-card')).toHaveCountGreaterThan(0);
    // Hızlı erişim butonları
    await expect(page.locator('a, button')).toContainText([/Kalite|Yeni Kayıt|Malzeme|Ürün/i]);
  });
}); 