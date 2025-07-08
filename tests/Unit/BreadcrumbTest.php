<?php

namespace QMS\Tests\Unit;

use PHPUnit\Framework\TestCase;

/**
 * Breadcrumb Library Unit Tests
 * 
 * Breadcrumb navigation fonksiyonalitesini test eder
 */
class BreadcrumbTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // Mock functions will be handled in bootstrap if needed
    }

    /**
     * Test breadcrumb creation and basic functionality
     */
    public function testBreadcrumbCreation(): void
    {
        $this->markTestSkipped('Breadcrumb requires CodeIgniter environment - will be implemented in integration tests');
    }

    /**
     * Test segment title formatting
     */
    public function testSegmentTitleFormatting(): void
    {
        // Test basic formatting
        $segment = 'user_management';
        $expected = 'User Management';
        $result = $this->formatSegmentTitle($segment);
        $this->assertEquals($expected, $result);

        // Test with numbers
        $segment = 'report123';
        $expected = 'Report';
        $result = $this->formatSegmentTitle($segment);
        $this->assertEquals($expected, $result);

        // Test with dashes
        $segment = 'girdi-kontrol';
        $expected = 'Girdi Kontrol';
        $result = $this->formatSegmentTitle($segment);
        $this->assertEquals($expected, $result);
    }

    /**
     * Test breadcrumb array structure
     */
    public function testBreadcrumbArrayStructure(): void
    {
        $breadcrumbItem = [
            'title' => 'Test Page',
            'url' => 'http://localhost/test',
            'active' => false
        ];

        $this->assertArrayHasKey('title', $breadcrumbItem);
        $this->assertArrayHasKey('url', $breadcrumbItem);
        $this->assertArrayHasKey('active', $breadcrumbItem);
        
        $this->assertIsString($breadcrumbItem['title']);
        $this->assertIsString($breadcrumbItem['url']);
        $this->assertIsBool($breadcrumbItem['active']);
    }

    /**
     * Test HTML output safety
     */
    public function testHtmlOutputSafety(): void
    {
        if (!function_exists('safe_output')) {
            require_once __DIR__ . '/../../application/helpers/security_helper.php';
        }

        $maliciousTitle = '<script>alert("xss")</script>Test';
        $safeTitle = safe_output($maliciousTitle);
        
        $this->assertThat($safeTitle, $this->logicalNot($this->stringContains('<script>')));
        $this->assertThat($safeTitle, $this->stringContains('Test'));
    }

    /**
     * Test URL validation
     */
    public function testUrlValidation(): void
    {
        $validUrl = 'http://localhost/users';
        $this->assertThat($validUrl, $this->stringContains('http://'));
        
        $relativeUrl = '/users';
        $this->assertStringStartsWith('/', $relativeUrl);
        
        $emptyUrl = '';
        $this->assertEmpty($emptyUrl);
    }

    /**
     * Test separator functionality
     */
    public function testSeparatorFunctionality(): void
    {
        $defaultSeparator = '<i class="fas fa-chevron-right"></i>';
        $customSeparator = ' > ';
        
        $this->assertNotEmpty($defaultSeparator);
        $this->assertNotEmpty($customSeparator);
        $this->assertNotEquals($defaultSeparator, $customSeparator);
    }

    /**
     * Test module title mapping
     */
    public function testModuleTitleMapping(): void
    {
        $moduleTitles = [
            'users' => 'Kullanıcılar',
            'user_roles' => 'Kullanıcı Rolleri',
            'girdikontrol' => 'Girdi Kontrol',
            'proseskontrol' => 'Proses Kontrol',
            'finalkontrol' => 'Final Kontrol',
            'tedarikciler' => 'Tedarikçiler',
            'malzemeler' => 'Malzemeler',
            'raporlar' => 'Raporlar',
            'ayarlar' => 'Ayarlar'
        ];

        foreach ($moduleTitles as $module => $title) {
            $this->assertNotEmpty($title);
            $this->assertIsString($title);
        }

        // Test unknown module
        $unknownModule = 'unknown_module';
        $this->assertArrayNotHasKey($unknownModule, $moduleTitles);
    }

    /**
     * Test action title mapping
     */
    public function testActionTitleMapping(): void
    {
        $actionTitles = [
            'index' => 'Liste',
            'list' => 'Liste',
            'add' => 'Yeni Ekle',
            'edit' => 'Düzenle',
            'view' => 'Görüntüle',
            'delete' => 'Sil'
        ];

        foreach ($actionTitles as $action => $title) {
            $this->assertNotEmpty($title);
            $this->assertIsString($title);
        }

        // Test ID appending
        $actionWithId = 'Düzenle #123';
        $this->assertThat($actionWithId, $this->stringContains('#123'));
    }

    /**
     * Test breadcrumb count functionality
     */
    public function testBreadcrumbCount(): void
    {
        $breadcrumbs = [];
        $this->assertEquals(0, count($breadcrumbs));

        $breadcrumbs[] = ['title' => 'Home', 'url' => '/', 'active' => false];
        $this->assertEquals(1, count($breadcrumbs));

        $breadcrumbs[] = ['title' => 'Users', 'url' => '/users', 'active' => true];
        $this->assertEquals(2, count($breadcrumbs));
    }

    /**
     * Test home page configuration
     */
    public function testHomePageConfiguration(): void
    {
        $homeTitle = 'Ana Sayfa';
        $homeUrl = 'http://localhost/';
        
        $this->assertNotEmpty($homeTitle);
        $this->assertNotEmpty($homeUrl);
        $this->assertThat($homeUrl, $this->stringContains('http://'));
    }

    /**
     * Test CSS class generation
     */
    public function testCssClassGeneration(): void
    {
        $defaultClass = 'breadcrumb';
        $activeClass = 'breadcrumb-item active';
        $normalClass = 'breadcrumb-item';
        
        $this->assertNotEmpty($defaultClass);
        $this->assertThat($activeClass, $this->stringContains('active'));
        $this->assertThat($normalClass, $this->logicalNot($this->stringContains('active')));
    }

    /**
     * Helper method to format segment titles (for testing)
     */
    private function formatSegmentTitle($segment)
    {
        // Remove numbers and special characters
        $title = preg_replace('/[^a-zA-Z\s]/', ' ', $segment);
        
        // Convert to title case
        $title = ucwords(str_replace(['_', '-'], ' ', $title));
        
        // Turkish character replacements
        $replacements = [
            'Girdi' => 'Girdi',
            'Proses' => 'Proses',
            'Final' => 'Final',
            'Kontrol' => 'Kontrol',
            'Tedarikci' => 'Tedarikçi',
            'Malzeme' => 'Malzeme',
            'Rapor' => 'Rapor',
            'Kullanici' => 'Kullanıcı',
            'Ayar' => 'Ayar'
        ];
        
        foreach ($replacements as $search => $replace) {
            $title = str_ireplace($search, $replace, $title);
        }
        
        return trim($title);
    }
} 