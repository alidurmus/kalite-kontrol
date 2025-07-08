<?php

namespace QMS\Tests\Unit;

use PHPUnit\Framework\TestCase;

/**
 * Security Helper Unit Tests
 * 
 * XSS koruması ve güvenlik fonksiyonlarının test edilmesi
 */
class SecurityHelperTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        
        // Security helper'ı yükle
        if (!function_exists('safe_output')) {
            require_once __DIR__ . '/../../application/helpers/security_helper.php';
        }
    }

    /**
     * Test safe_output function with normal text
     */
    public function testSafeOutputWithNormalText(): void
    {
        $input = 'Normal text content';
        $expected = 'Normal text content';
        $result = safe_output($input);
        
        $this->assertEquals($expected, $result);
    }

    /**
     * Test safe_output function with XSS attempt
     */
    public function testSafeOutputWithXSSAttempt(): void
    {
        $input = '<script>alert("XSS")</script>';
        $expected = '&lt;script&gt;alert(&quot;XSS&quot;)&lt;/script&gt;';
        $result = safe_output($input);
        
        $this->assertEquals($expected, $result);
    }

    /**
     * Test safe_output with quotes
     */
    public function testSafeOutputWithQuotes(): void
    {
        $input = 'Text with "quotes" and \'apostrophes\'';
        $expected = 'Text with &quot;quotes&quot; and &#039;apostrophes&#039;';
        $result = safe_output($input);
        
        $this->assertEquals($expected, $result);
    }

    /**
     * Test safe_attr function for HTML attributes
     */
    public function testSafeAttr(): void
    {
        if (!function_exists('safe_attr')) {
            $this->markTestSkipped('safe_attr function not available');
        }

        $input = 'value" onclick="alert(\'XSS\')"';
        $result = safe_attr($input);
        
        // Should escape quotes and prevent attribute injection
        $this->assertThat($result, $this->logicalNot($this->stringContains('onclick')));
        $this->assertThat($result, $this->logicalNot($this->stringContains('"')));
    }

    /**
     * Test safe_url function for URL sanitization
     */
    public function testSafeUrl(): void
    {
        if (!function_exists('safe_url')) {
            $this->markTestSkipped('safe_url function not available');
        }

        $validUrl = 'https://example.com/page';
        $result = safe_url($validUrl);
        $this->assertEquals($validUrl, $result);

        $maliciousUrl = 'javascript:alert("XSS")';
        $result = safe_url($maliciousUrl);
        $this->assertNotEquals($maliciousUrl, $result);
    }

    /**
     * Test sanitize_search function
     */
    public function testSanitizeSearch(): void
    {
        if (!function_exists('sanitize_search')) {
            $this->markTestSkipped('sanitize_search function not available');
        }

        $input = '<script>search term</script>';
        $result = sanitize_search($input);
        
        // Should remove script tags but keep search term
        $this->assertThat($result, $this->logicalNot($this->stringContains('<script>')));
        $this->assertThat($result, $this->stringContains('search term'));
    }

    /**
     * Test with empty input
     */
    public function testSafeOutputWithEmptyInput(): void
    {
        $input = '';
        $result = safe_output($input);
        $this->assertEquals('', $result);
    }

    /**
     * Test with null input
     */
    public function testSafeOutputWithNullInput(): void
    {
        $input = null;
        $result = safe_output($input);
        $this->assertEquals('', $result);
    }

    /**
     * Test with numeric input
     */
    public function testSafeOutputWithNumericInput(): void
    {
        $input = 12345;
        $result = safe_output($input);
        $this->assertEquals('12345', $result);
    }

    /**
     * Test double encoding prevention
     */
    public function testSafeOutputDoubleEncodingPrevention(): void
    {
        $input = '&lt;script&gt;';
        $result = safe_output($input, false); // false = don't double encode
        
        // Should not double encode already encoded entities
        $this->assertEquals('&lt;script&gt;', $result);
    }

    /**
     * Test with mixed content
     */
    public function testSafeOutputWithMixedContent(): void
    {
        $input = 'Normal text <b>bold</b> and <script>alert("xss")</script>';
        $result = safe_output($input);
        
        $this->assertThat($result, $this->stringContains('Normal text'));
        $this->assertThat($result, $this->stringContains('&lt;b&gt;bold&lt;/b&gt;'));
        $this->assertThat($result, $this->stringContains('&lt;script&gt;'));
        $this->assertThat($result, $this->logicalNot($this->stringContains('<script>')));
    }
} 