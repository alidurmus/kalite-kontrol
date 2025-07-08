<?php
/**
 * PHPUnit Bootstrap File
 * Kalite Yönetim Sistemi Test Ortamı
 */

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set timezone
date_default_timezone_set('Europe/Istanbul');

// Define paths
define('BASEPATH', realpath(__DIR__ . '/../system/') . '/');
define('APPPATH', realpath(__DIR__ . '/../application/') . '/');
define('FCPATH', realpath(__DIR__ . '/../') . '/');
define('SYSDIR', 'system');
define('ENVIRONMENT', 'testing');

// Load Composer autoloader
require_once __DIR__ . '/../vendor/autoload.php';

// Load security helper for testing
if (file_exists(APPPATH . 'helpers/security_helper.php')) {
    require_once APPPATH . 'helpers/security_helper.php';
}

// Simple test database config
function init_test_database()
{
    return array(
        'dsn'      => '',
        'hostname' => 'localhost',
        'username' => 'root',
        'password' => '',
        'database' => 'cms_panel_test',
        'dbdriver' => 'mysqli',
        'dbprefix' => '',
        'pconnect' => FALSE,
        'db_debug' => TRUE,
        'cache_on' => FALSE,
        'cachedir' => '',
        'char_set' => 'utf8mb4',
        'dbcollat' => 'utf8mb4_unicode_ci',
        'swap_pre' => '',
        'encrypt'  => FALSE,
        'compress' => FALSE,
        'stricton' => FALSE,
        'failover' => array(),
        'save_queries' => TRUE
    );
}

// Create test database if not exists
function setup_test_database()
{
    $db_config = init_test_database();
    
    try {
        // Connect to MySQL without database
        $connection = new mysqli(
            $db_config['hostname'],
            $db_config['username'],
            $db_config['password']
        );

        if ($connection->connect_error) {
            throw new Exception('Database connection failed: ' . $connection->connect_error);
        }

        // Create test database if not exists
        $sql = "CREATE DATABASE IF NOT EXISTS `{$db_config['database']}` 
                CHARACTER SET {$db_config['char_set']} 
                COLLATE {$db_config['dbcollat']}";
        
        if (!$connection->query($sql)) {
            throw new Exception('Failed to create test database: ' . $connection->error);
        }

        $connection->close();
    } catch (Exception $e) {
        // Database bağlantısı yoksa test'leri sadece helper fonksiyonları ile sınırlayalım
        error_log('Test database setup failed: ' . $e->getMessage());
    }
}

// Setup test environment
setup_test_database(); 