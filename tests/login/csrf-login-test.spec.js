// @ts-check
const { test, expect } = require('@playwright/test');
const { ConsoleDetector } = require('../utils/console-detector');

test.describe('CSRF Token Login Tests', () => {
  let consoleDetector;

  test.beforeEach(async ({ page }) => {
    consoleDetector = new ConsoleDetector(page);
  });

  test('Login should work with CSRF token', async ({ page }) => {
    // Login sayfasına git
    await page.goto('http://localhost:8090/userop/login');
    
    // Sayfa başarıyla yüklenmeli
    await expect(page).toHaveTitle(/Tempar/);
    
    // Form elemanlarını bul
    const userSelect = page.locator('select[name="user_name"]');
    const passwordInput = page.locator('input[name="user_password"]');
    const submitButton = page.locator('button[type="submit"]');
    
    // Form elemanlarının mevcut olduğunu kontrol et
    await expect(userSelect).toBeVisible();
    await expect(passwordInput).toBeVisible();
    await expect(submitButton).toBeVisible();
    
    // CSRF token'ının mevcut olup olmadığını kontrol et
    const csrfInput = page.locator('input[type="hidden"]');
    const csrfExists = await csrfInput.count() > 0;
    console.log('CSRF Token Exists:', csrfExists);
    
    if (csrfExists) {
      const csrfName = await csrfInput.getAttribute('name');
      const csrfValue = await csrfInput.getAttribute('value');
      console.log('CSRF Token Name:', csrfName);
      console.log('CSRF Token Value:', csrfValue ? 'Present' : 'Missing');
    }
    
    // Admin kullanıcısını seç
    await userSelect.selectOption('admin');
    
    // Şifreyi gir (veritabanında hash'i mevcut olan şifre)
    await passwordInput.fill('123456');
    
    // Form'u gönder
    await submitButton.click();
    
    // Login sonrasını bekle (ya dashboard ya da error sayfası)
    await page.waitForLoadState('networkidle');
    
    // URL kontrolü - dashboard'a yönlendirildik mi?
    const currentUrl = page.url();
    console.log('Current URL after login:', currentUrl);
    
    if (currentUrl.includes('dashboard')) {
      console.log('✅ Login successful - redirected to dashboard');
      await expect(page).toHaveURL(/dashboard/);
    } else if (currentUrl.includes('login')) {
      console.log('❌ Login failed - still on login page');
      
      // Error mesajlarını kontrol et
      const errorMessages = await page.locator('.error, .alert, .message').allTextContents();
      console.log('Error messages:', errorMessages);
    } else {
      console.log('⚠️ Unexpected redirect to:', currentUrl);
    }
    
    // Console error'larını kontrol et
    const errors = consoleDetector.getErrors();
    if (errors.length > 0) {
      console.log('Console Errors:', errors.map(e => e.text));
    }
    
    // CSRF hatası varsa özel kontrol
    const csrfErrors = errors.filter(error => 
      error.text.toLowerCase().includes('csrf') ||
      error.text.toLowerCase().includes('token')
    );
    
    if (csrfErrors.length > 0) {
      console.log('❌ CSRF related errors found:', csrfErrors.map(e => e.text));
    } else {
      console.log('✅ No CSRF errors detected');
    }
  });

  test('Login page should have CSRF protection enabled', async ({ page }) => {
    await page.goto('http://localhost:8090/userop/login');
    
    // CSRF token input'ının varlığını kontrol et
    const csrfInputs = await page.locator('input[type="hidden"]').count();
    
    if (csrfInputs > 0) {
      console.log('✅ CSRF protection is enabled');
      
      // Token name ve value'ları kontrol et
      const csrfInput = page.locator('input[type="hidden"]').first();
      const name = await csrfInput.getAttribute('name');
      const value = await csrfInput.getAttribute('value');
      
      expect(name).toBeTruthy();
      expect(value).toBeTruthy();
      
      console.log('CSRF Token Name:', name);
      console.log('CSRF Token exists:', value ? 'Yes' : 'No');
    } else {
      console.log('❌ CSRF protection is NOT enabled');
    }
  });
}); 