const { test, expect } = require('@playwright/test');

test.describe('Login Sayfası', () => {
  test('Giriş formu ve butonu görünüyor', async ({ page }) => {
    await page.goto('/login');
    await expect(page.locator('input[name="user"]')).toBeVisible();
    await expect(page.locator('input[name="password"]')).toBeVisible();
    await expect(page.locator('button[type="submit"]')).toBeVisible();
  });

  test('Doğru bilgilerle giriş yapılabiliyor', async ({ page }) => {
    await page.goto('/login');
    await page.fill('input[name="user"]', 'admin');
    await page.fill('input[name="password"]', '123456');
    await page.click('button[type="submit"]');
    // Başarılı giriş sonrası dashboard veya ana sayfa başlığı kontrolü
    await expect(page).toHaveURL(/dashboard|anasayfa/i);
  });
}); 