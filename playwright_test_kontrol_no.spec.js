const { test, expect } = require('@playwright/test');

test.describe('Kontrol No Sayfası', () => {
  test('Sayfa açılıyor ve ana başlık görünüyor', async ({ page }) => {
    await page.goto('/kontrol_no');
    await expect(page).toHaveTitle(/Kontrol/i);
    await expect(page.locator('h1, h2, h3')).toContainText([/Kontrol No|Kontrol/i]);
  });

  test('Tablo ve kayıtlar yükleniyor', async ({ page }) => {
    await page.goto('/kontrol_no');
    // Tablo başlıkları veya en az bir satır var mı?
    const table = page.locator('table');
    await expect(table).toBeVisible();
    // En az bir veri satırı var mı?
    const rows = table.locator('tbody tr');
    await expect(rows).toHaveCountGreaterThan(0);
  });
}); 