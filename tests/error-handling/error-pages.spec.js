// @ts-check
const { test, expect } = require('@playwright/test');
const { ConsoleDetector } = require('../utils/console-detector');

test.describe('Error Handling Tests', () => {
  let consoleDetector;

  test.beforeEach(async ({ page }) => {
    consoleDetector = new ConsoleDetector(page);
  });

  test('Error pages should not cause fatal errors themselves', async ({ page }) => {
    // Gezinti sırasında hiç error görmemeliyiz
    await page.goto('http://localhost:8090/userop/login');
    
    // Sayfa başarıyla yüklenmeli
    await expect(page).toHaveTitle(/Tempar/);
    
    // Console'da fatal error olmamalı
    const errors = consoleDetector.getErrors();
    const fatalErrors = errors.filter(error => 
      error.text.includes('Fatal error') || 
      error.text.includes('Undefined property: CI_Exceptions::$load')
    );
    
    console.log('Console errors:', errors.length);
    if (fatalErrors.length > 0) {
      console.log('Fatal errors found:', fatalErrors);
    }
    
    expect(fatalErrors).toHaveLength(0);
  });

  test('Error helper function should be available', async ({ page }) => {
    // Safe output fonksiyonunun mevcut olup olmadığını test et
    await page.goto('http://localhost:8090/userop/login');
    
    // Sayfa yüklenmeli
    await expect(page.locator('body')).toBeVisible();
    
    // Console'da helper ile ilgili error olmamalı
    const errors = consoleDetector.getErrors();
    const helperErrors = errors.filter(error => 
      error.text.includes('safe_output') || 
      error.text.includes('helper')
    );
    
    expect(helperErrors).toHaveLength(0);
  });

  test('Invalid route should show proper error page', async ({ page }) => {
    // Geçersiz bir route'a git
    const response = await page.goto('http://localhost:8090/nonexistent-page', {
      waitUntil: 'networkidle'
    });
    
    // 404 error dönemli
    expect(response.status()).toBe(404);
    
    // Error sayfası yüklenmeli, fatal error olmamalı
    const errors = consoleDetector.getErrors();
    const fatalErrors = errors.filter(error => 
      error.text.includes('Fatal error') || 
      error.text.includes('Call to a member function')
    );
    
    expect(fatalErrors).toHaveLength(0);
  });

  test('Session should work properly without errors', async ({ page }) => {
    await page.goto('http://localhost:8090/userop/login');
    
    // Login formunu doldur
    await page.selectOption('select[name="user_name"]', 'admin');
    await page.fill('input[name="user_password"]', 'admin');
    
    // Submit et
    await page.click('button[type="submit"]');
    
    // Dashboard'a yönlendirilmeli
    await expect(page).toHaveURL(/dashboard/);
    
    // Session error'ları olmamalı
    const errors = consoleDetector.getErrors();
    const sessionErrors = errors.filter(error => 
      error.text.includes('session') ||
      error.text.includes('Undefined property: CI::$session')
    );
    
    console.log('Session-related errors:', sessionErrors.length);
    expect(sessionErrors).toHaveLength(0);
  });

  test.afterEach(async () => {
    // Test sonrası console log'ları rapor et
    const errors = consoleDetector.getErrors();
    if (errors.length > 0) {
      console.log(`Test completed with ${errors.length} console errors`);
    } else {
      console.log('✅ Test completed successfully - no console errors');
    }
  });
}); 