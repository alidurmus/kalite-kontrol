/**
 * Global Test Setup - Console Detection
 * QMS Test Automation Framework
 */

async function globalSetup(config) {
  console.log('🚀 QMS Test Automation Framework başlatılıyor...');
  console.log('📊 Console detection aktif edildi');
  console.log('🌐 Base URL:', config.use?.baseURL || 'localhost');
  console.log('📁 Test dizini:', config.testDir || './tests');
  console.log('📈 Rapor klasörü:', 'docs/reports/');
  
  // Console mesajları için global ayarlar
  global.consoleMessages = [];
  global.consoleErrors = [];
  global.consoleWarnings = [];
  
  return Promise.resolve();
}

module.exports = globalSetup; 