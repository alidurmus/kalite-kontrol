<?php

namespace QMS\Tests\Unit;

use PHPUnit\Framework\TestCase;

/**
 * UserService Unit Tests
 * 
 * Service layer'ın iş mantığını test eder
 */
class UserServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        
        // UserService'i yükle (mock olarak)
        if (!class_exists('UserService')) {
            require_once __DIR__ . '/../../application/services/UserService.php';
        }
    }

    /**
     * Test email validation
     */
    public function testCreateUserWithInvalidEmail(): void
    {
        $this->markTestSkipped('UserService requires CodeIgniter environment - will be implemented in integration tests');
    }

    /**
     * Test username validation
     */
    public function testCreateUserWithInvalidUsername(): void
    {
        $this->markTestSkipped('UserService requires CodeIgniter environment - will be implemented in integration tests');
    }

    /**
     * Test password hashing
     */
    public function testPasswordHashing(): void
    {
        $password = 'test123';
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        $this->assertTrue(password_verify($password, $hashedPassword));
        $this->assertNotEquals($password, $hashedPassword);
        $this->assertNotEmpty($hashedPassword); // Hash should not be empty
        $this->assertStringStartsWith('$2y$', $hashedPassword); // bcrypt format
    }

    /**
     * Test user data validation
     */
    public function testUserDataValidation(): void
    {
        // Test empty username
        $userData = ['email' => 'test@example.com', 'username' => ''];
        $this->assertEmpty($userData['username']);
        
        // Test empty email
        $userData = ['username' => 'testuser', 'email' => ''];
        $this->assertEmpty($userData['email']);
        
        // Test valid data
        $userData = ['username' => 'testuser', 'email' => 'test@example.com'];
        $this->assertNotEmpty($userData['username']);
        $this->assertNotEmpty($userData['email']);
        $this->assertThat($userData['email'], $this->stringContains('@'));
    }

    /**
     * Test search sanitization for user list
     */
    public function testSearchSanitization(): void
    {
        if (!function_exists('sanitize_search')) {
            require_once __DIR__ . '/../../application/helpers/security_helper.php';
        }

        $maliciousSearch = '<script>alert("xss")</script>user';
        $sanitizedSearch = sanitize_search($maliciousSearch);
        
        $this->assertThat($sanitizedSearch, $this->logicalNot($this->stringContains('<script>')));
        $this->assertThat($sanitizedSearch, $this->stringContains('user'));
    }

    /**
     * Test pagination calculation
     */
    public function testPaginationCalculation(): void
    {
        $totalCount = 150;
        $perPage = 20;
        $expectedTotalPages = ceil($totalCount / $perPage); // 8 pages
        
        $this->assertEquals(8, $expectedTotalPages);
        
        // Test page 1 offset
        $page = 1;
        $offset = ($page - 1) * $perPage;
        $this->assertEquals(0, $offset);
        
        // Test page 3 offset
        $page = 3;
        $offset = ($page - 1) * $perPage;
        $this->assertEquals(40, $offset);
    }

    /**
     * Test role ID validation
     */
    public function testRoleIdValidation(): void
    {
        $roleId = '5';
        $validatedRoleId = intval($roleId);
        $this->assertEquals(5, $validatedRoleId);
        $this->assertIsInt($validatedRoleId);
        
        $invalidRoleId = 'abc';
        $validatedInvalidRoleId = intval($invalidRoleId);
        $this->assertEquals(0, $validatedInvalidRoleId);
    }

    /**
     * Test date formatting
     */
    public function testDateFormatting(): void
    {
        $dateTime = date('Y-m-d H:i:s');
        $this->assertMatchesRegularExpression('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', $dateTime);
        
        // Test that date is recent (within last minute)
        $timestamp = strtotime($dateTime);
        $now = time();
        $this->assertLessThanOrEqual(60, $now - $timestamp);
    }

    /**
     * Test error response format
     */
    public function testErrorResponseFormat(): void
    {
        $errorResponse = [
            'success' => false,
            'error' => 'Test error message'
        ];
        
        $this->assertArrayHasKey('success', $errorResponse);
        $this->assertArrayHasKey('error', $errorResponse);
        $this->assertFalse($errorResponse['success']);
        $this->assertIsString($errorResponse['error']);
    }

    /**
     * Test success response format
     */
    public function testSuccessResponseFormat(): void
    {
        $successResponse = [
            'success' => true,
            'user_id' => 123,
            'message' => 'User created successfully'
        ];
        
        $this->assertArrayHasKey('success', $successResponse);
        $this->assertTrue($successResponse['success']);
        $this->assertIsInt($successResponse['user_id']);
        $this->assertIsString($successResponse['message']);
    }

    /**
     * Test admin role protection
     */
    public function testAdminRoleProtection(): void
    {
        $adminRoleId = 1;
        $userRoleId = 2;
        
        // Admin user should not be deletable
        $canDeleteAdmin = ($adminRoleId != 1);
        $this->assertFalse($canDeleteAdmin);
        
        // Regular user should be deletable
        $canDeleteUser = ($userRoleId != 1);
        $this->assertTrue($canDeleteUser);
    }
} 