/**
 * Console Detection Utilities
 * QMS Test Automation Framework
 * 
 * Chrome, Firefox ve Mobile Chrome console mesajlarını yakalar ve analiz eder
 */

class ConsoleDetector {
  constructor() {
    this.messages = [];
    this.errors = [];
    this.warnings = [];
    this.logs = [];
    this.startTime = Date.now();
  }

  /**
   * Page'e console listener'ları ekler
   * @param {Page} page - Playwright page object
   */
  async attachToPage(page) {
    // Console mesajlarını yakala
    page.on('console', async (msg) => {
      const timestamp = new Date().toISOString();
      const type = msg.type();
      const text = msg.text();
      const location = msg.location();
      
      const consoleEntry = {
        timestamp,
        type,
        text,
        url: location.url,
        lineNumber: location.lineNumber,
        columnNumber: location.columnNumber
      };

      // Tüm mesajları kaydet
      this.messages.push(consoleEntry);

      // Türe göre sınıflandır
      switch (type) {
        case 'error':
          this.errors.push(consoleEntry);
          console.log(`❌ Console Error: ${text}`);
          break;
        case 'warning':
          this.warnings.push(consoleEntry);
          console.log(`⚠️  Console Warning: ${text}`);
          break;
        case 'log':
        case 'info':
        case 'debug':
          this.logs.push(consoleEntry);
          console.log(`📝 Console Log: ${text}`);
          break;
        default:
          console.log(`🔍 Console ${type}: ${text}`);
      }
    });

    // Page errors'ları yakala (uncaught exceptions)
    page.on('pageerror', (error) => {
      const timestamp = new Date().toISOString();
      const errorEntry = {
        timestamp,
        type: 'pageerror',
        text: error.message,
        stack: error.stack,
        url: 'N/A',
        lineNumber: 0,
        columnNumber: 0
      };
      
      this.errors.push(errorEntry);
      console.log(`💥 Page Error: ${error.message}`);
    });

    // Network request failures
    page.on('requestfailed', (request) => {
      const timestamp = new Date().toISOString();
      const failureEntry = {
        timestamp,
        type: 'network_error',
        text: `Failed to load: ${request.url()} - ${request.failure()?.errorText}`,
        url: request.url(),
        method: request.method(),
        status: 'failed'
      };
      
      this.errors.push(failureEntry);
      console.log(`🌐 Network Error: ${request.url()} - ${request.failure()?.errorText}`);
    });

    return this;
  }

  /**
   * Kritik hataları kontrol eder
   * @returns {boolean} - Kritik hata var mı?
   */
  hasCriticalErrors() {
    const criticalPatterns = [
      /uncaught/i,
      /typeerror/i,
      /referenceerror/i,
      /syntaxerror/i,
      /failed to fetch/i,
      /network error/i,
      /500|502|503|504/
    ];

    return this.errors.some(error => 
      criticalPatterns.some(pattern => pattern.test(error.text))
    );
  }

  /**
   * Tüm console hatalarını döndürür
   * @returns {Array} - Tüm console hataları
   */
  getErrors() {
    return this.errors;
  }

  /**
   * JavaScript hataları filtreler
   * @returns {Array} - JS hataları
   */
  getJavaScriptErrors() {
    return this.errors.filter(error => 
      error.type === 'error' || error.type === 'pageerror'
    );
  }

  /**
   * Network hatalarını filtreler
   * @returns {Array} - Network hataları
   */
  getNetworkErrors() {
    return this.errors.filter(error => 
      error.type === 'network_error' || 
      error.text.includes('Failed to load') ||
      error.text.includes('fetch')
    );
  }

  /**
   * Console özet raporu oluşturur
   * @returns {Object} - Detaylı console raporu
   */
  getReport() {
    const duration = Date.now() - this.startTime;
    
    return {
      summary: {
        totalMessages: this.messages.length,
        errors: this.errors.length,
        warnings: this.warnings.length,
        logs: this.logs.length,
        hasCriticalErrors: this.hasCriticalErrors(),
        duration: `${duration}ms`
      },
      details: {
        errors: this.errors,
        warnings: this.warnings,
        logs: this.logs.slice(-10), // Son 10 log mesajı
        criticalErrors: this.getJavaScriptErrors(),
        networkErrors: this.getNetworkErrors()
      },
      timestamp: new Date().toISOString()
    };
  }

  /**
   * Console mesajlarını temizler
   */
  clear() {
    this.messages = [];
    this.errors = [];
    this.warnings = [];
    this.logs = [];
    this.startTime = Date.now();
  }

  /**
   * Test sonrası console raporunu dosyaya yazar
   * @param {string} testName - Test adı
   * @param {string} browser - Browser adı
   */
  async saveReport(testName, browser = 'chrome') {
    const fs = require('fs').promises;
    const path = require('path');
    
    const report = this.getReport();
    const filename = `console-${testName}-${browser}-${Date.now()}.json`;
    const filepath = path.join('docs/reports/console/', filename);
    
    try {
      // Klasör yoksa oluştur
      await fs.mkdir(path.dirname(filepath), { recursive: true });
      
      // Raporu kaydet
      await fs.writeFile(filepath, JSON.stringify(report, null, 2));
      console.log(`💾 Console raporu kaydedildi: ${filepath}`);
    } catch (error) {
      console.error('❌ Console raporu kaydedilemedi:', error.message);
    }
  }
}

/**
 * Test helper function - Console detector'ı test'e ekler
 * @param {Object} test - Playwright test context
 * @param {Page} page - Page object
 * @returns {ConsoleDetector} - Console detector instance
 */
async function setupConsoleDetection(test, page) {
  const detector = new ConsoleDetector();
  await detector.attachToPage(page);
  
  // Test bitiminde rapor kaydet
  test.info().attachments.push({
    name: 'console-report',
    body: JSON.stringify(detector.getReport(), null, 2),
    contentType: 'application/json'
  });
  
  return detector;
}

/**
 * Console assertions - Test içinde kullanım için
 * @param {ConsoleDetector} detector - Console detector instance
 */
function assertNoConsoleErrors(detector) {
  const errors = detector.getJavaScriptErrors();
  if (errors.length > 0) {
    throw new Error(`Console errors detected: ${errors.map(e => e.text).join(', ')}`);
  }
}

function assertNoCriticalErrors(detector) {
  if (detector.hasCriticalErrors()) {
    const criticalErrors = detector.errors.filter(e => 
      /uncaught|typeerror|referenceerror|syntaxerror/i.test(e.text)
    );
    throw new Error(`Critical console errors detected: ${criticalErrors.map(e => e.text).join(', ')}`);
  }
}

module.exports = {
  ConsoleDetector,
  setupConsoleDetection,
  assertNoConsoleErrors,
  assertNoCriticalErrors
}; 