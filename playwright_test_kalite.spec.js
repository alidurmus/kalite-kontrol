const { test, expect } = require('@playwright/test');

test.describe('Kalite Modülü Ana Sayfası', () => {
  test('Kalite başlığı ve butonlar görünüyor', async ({ page }) => {
    await page.goto('/anasayfa/kalite');
    await expect(page.locator('h1, h2, h3')).toContainText([/Kalite|Kontrol/i]);
    await expect(page.locator('a, button')).toContainText([/Girdi|Proses|Final|Ölçüm/i]);
    // Açıklama veya bilgi bölümü
    await expect(page.locator('section, .info, .alert, .jumbotron')).toBeVisible();
  });
}); 