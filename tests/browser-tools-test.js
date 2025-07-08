const { BrowserToolsMCP } = require('@agentdeskai/browser-tools-mcp');

async function testBrowserTools() {
    console.log('🚀 BrowserTools MCP Test Başlatılıyor...');
    
    try {
        // BrowserTools MCP client'ını başlat
        const browserTools = new BrowserToolsMCP({
            debug: true,
            port: 3000,
            browser: 'chrome',
            headless: false
        });
        
        console.log('✅ BrowserTools MCP client oluşturuldu');
        
        // Browser'ı başlat
        await browserTools.start();
        console.log('✅ Browser başlatıldı');
        
        // Test sayfasına git
        const testUrl = 'http://localhost:8090';
        await browserTools.navigate(testUrl);
        console.log(`✅ ${testUrl} adresine gidildi`);
        
        // Sayfa bilgilerini al
        const pageInfo = await browserTools.getPageInfo();
        console.log('📄 Sayfa Bilgileri:', {
            title: pageInfo.title,
            url: pageInfo.url,
            loaded: pageInfo.loaded
        });
        
        // Screenshot al
        const screenshot = await browserTools.takeScreenshot();
        console.log('📸 Screenshot alındı:', screenshot.path);
        
        // DOM elementlerini say
        const elementCount = await browserTools.countElements('*');
        console.log(`🔢 Toplam DOM elementi: ${elementCount}`);
        
        // Performance metrikleri al
        const performance = await browserTools.getPerformanceMetrics();
        console.log('⚡ Performance Metrikleri:', {
            loadTime: performance.loadTime,
            domContentLoaded: performance.domContentLoaded,
            firstPaint: performance.firstPaint
        });
        
        // Browser'ı kapat
        await browserTools.stop();
        console.log('✅ Browser kapatıldı');
        
        console.log('🎉 BrowserTools MCP test başarıyla tamamlandı!');
        
    } catch (error) {
        console.error('❌ Test hatası:', error.message);
        console.error('Stack:', error.stack);
    }
}

// Test'i çalıştır
if (require.main === module) {
    testBrowserTools();
}

module.exports = { testBrowserTools }; 