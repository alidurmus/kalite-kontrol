<?php
/**
 * Dashboard Configuration File
 * 
 * Centralized configuration for dashboard settings, charts, cache, and performance
 * 
 * @package    CodeIgniter
 * @subpackage Config
 * @category   Dashboard
 * @author     QMS Development Team
 * @version    1.0
 */

defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Dashboard General Settings
|--------------------------------------------------------------------------
|
| These settings control the general behavior of the dashboard
|
*/

// Dashboard title and branding
$config['dashboard_title'] = 'Kalite Yönetim Dashboard';
$config['dashboard_subtitle'] = 'Kalite kontrol süreçlerinizi tek yerden yönetin';
$config['dashboard_version'] = '2.1.0';

// Update intervals (in seconds)
$config['dashboard_refresh_interval'] = 300; // 5 minutes
$config['dashboard_clock_update'] = 1; // 1 second
$config['dashboard_auto_refresh'] = true;

/*
|--------------------------------------------------------------------------
| Cache Configuration
|--------------------------------------------------------------------------
|
| Configure caching behavior for dashboard components
|
*/

// Cache TTL settings (in seconds)
$config['cache_ttl'] = array(
    'dashboard_stats' => 300,        // 5 minutes
    'user_lists' => 600,            // 10 minutes  
    'product_lists' => 900,         // 15 minutes
    'supplier_lists' => 1800,       // 30 minutes
    'activity_logs' => 180,         // 3 minutes
    'chart_data' => 600,            // 10 minutes
    'performance_metrics' => 900     // 15 minutes
);

// Cache prefixes
$config['cache_prefix'] = array(
    'dashboard' => 'dash_',
    'stats' => 'stats_',
    'charts' => 'chart_',
    'activities' => 'activity_'
);

// Cache driver (file, redis, memcached)
$config['cache_driver'] = 'file';

/*
|--------------------------------------------------------------------------
| Chart Configuration
|--------------------------------------------------------------------------
|
| Default chart settings and configurations
|
*/

// Chart colors
$config['chart_colors'] = array(
    'primary' => '#007bff',
    'success' => '#28a745',
    'danger' => '#dc3545',
    'warning' => '#ffc107',
    'info' => '#17a2b8',
    'secondary' => '#6c757d',
    'dark' => '#343a40',
    'purple' => '#6f42c1',
    'pink' => '#e83e8c',
    'orange' => '#fd7e14'
);

// Chart default options
$config['chart_defaults'] = array(
    'responsive' => true,
    'maintainAspectRatio' => false,
    'animation_duration' => 1500,
    'animation_easing' => 'easeOutQuart',
    'legend_position' => 'bottom',
    'tooltip_enabled' => true
);

// Quality control chart settings
$config['quality_chart'] = array(
    'type' => 'doughnut',
    'labels' => array('Girdi Kontrol', 'Proses Kontrol', 'Final Kontrol'),
    'colors' => array('#007bff', '#28a745', '#dc3545'),
    'cutout' => '70%',
    'borderWidth' => 0,
    'hoverOffset' => 4
);

// Performance chart settings
$config['performance_chart'] = array(
    'type' => 'bar',
    'labels' => array('Kalite Başarı', 'Zamanında Teslimat', 'Müşteri Memnuniyeti'),
    'colors' => array('#28a745', '#ffc107', '#17a2b8'),
    'borderRadius' => 8,
    'maxValue' => 100
);

/*
|--------------------------------------------------------------------------
| Statistics Cards Configuration
|--------------------------------------------------------------------------
|
| Configure the main statistics cards displayed on dashboard
|
*/

$config['stats_cards'] = array(
    'girdi_kontrol' => array(
        'label' => 'Girdi Kontrol',
        'icon' => 'zmdi-input-antenna',
        'color' => 'primary',
        'table' => 'girdi_kontrol',
        'link' => 'girdikontrol',
        'order_by' => 'created_at DESC'
    ),
    'proses_kontrol' => array(
        'label' => 'Proses Kontrol',
        'icon' => 'zmdi-settings',
        'color' => 'success',
        'table' => 'proses_kontrol',
        'link' => 'proseskontrol',
        'order_by' => 'created_at DESC'
    ),
    'final_kontrol' => array(
        'label' => 'Final Kontrol',
        'icon' => 'zmdi-check-circle',
        'color' => 'danger',
        'table' => 'final_kontrol',
        'link' => 'finalkontrol',
        'order_by' => 'created_at DESC'
    ),
    'kontrol_no' => array(
        'label' => 'Kontrol No',
        'icon' => 'zmdi-assignment',
        'color' => 'warning',
        'table' => 'kontrol_no',
        'link' => 'kontrol_no',
        'order_by' => 'id DESC'
    )
);

/*
|--------------------------------------------------------------------------
| Mini Statistics Configuration
|--------------------------------------------------------------------------
|
| Configure mini statistics cards for additional metrics
|
*/

$config['mini_stats'] = array(
    'suppliers' => array(
        'label' => 'Tedarikçiler',
        'icon' => 'zmdi-truck',
        'color' => '#6f42c1',
        'table' => 'tedarikciler',
        'count_field' => 'id'
    ),
    'materials' => array(
        'label' => 'Malzemeler',
        'icon' => 'zmdi-archive',
        'color' => '#e83e8c',
        'table' => 'malzemeler',
        'count_field' => 'id'
    ),
    'products' => array(
        'label' => 'Ürünler',
        'icon' => 'zmdi-shopping-cart',
        'color' => '#fd7e14',
        'table' => 'urunler',
        'count_field' => 'id'
    ),
    'users' => array(
        'label' => 'Kullanıcılar',
        'icon' => 'zmdi-accounts',
        'color' => '#20c997',
        'table' => 'users',
        'count_field' => 'id'
    )
);

/*
|--------------------------------------------------------------------------
| Quick Actions Configuration
|--------------------------------------------------------------------------
|
| Configure quick action buttons for dashboard
|
*/

$config['quick_actions'] = array(
    'quality_center' => array(
        'title' => 'Kalite Merkezi',
        'icon' => 'zmdi-shield-check',
        'color' => 'primary',
        'url' => 'kalite'
    ),
    'input_control' => array(
        'title' => 'Girdi Kontrol',
        'icon' => 'zmdi-input-antenna',
        'color' => 'info',
        'url' => 'girdikontrol'
    ),
    'process_control' => array(
        'title' => 'Proses Kontrol',
        'icon' => 'zmdi-settings',
        'color' => 'success',
        'url' => 'proseskontrol'
    ),
    'final_control' => array(
        'title' => 'Final Kontrol',
        'icon' => 'zmdi-check-circle',
        'color' => 'danger',
        'url' => 'finalkontrol'
    ),
    'materials' => array(
        'title' => 'Malzemeler',
        'icon' => 'zmdi-archive',
        'color' => 'secondary',
        'url' => 'malzemeler'
    ),
    'products' => array(
        'title' => 'Ürünler',
        'icon' => 'zmdi-shopping-cart',
        'color' => 'dark',
        'url' => 'urunler'
    )
);

/*
|--------------------------------------------------------------------------
| Performance Metrics Configuration
|--------------------------------------------------------------------------
|
| Configure performance indicators and their targets
|
*/

$config['performance_metrics'] = array(
    'quality_success' => array(
        'label' => 'Kalite Başarı Oranı',
        'target' => 95,
        'current' => 0, // Will be calculated dynamically
        'color' => 'success',
        'format' => 'percentage'
    ),
    'delivery_ontime' => array(
        'label' => 'Zamanında Teslimat',
        'target' => 90,
        'current' => 0,
        'color' => 'warning',
        'format' => 'percentage'
    ),
    'customer_satisfaction' => array(
        'label' => 'Müşteri Memnuniyeti',
        'target' => 85,
        'current' => 0,
        'color' => 'info',
        'format' => 'percentage'
    )
);

/*
|--------------------------------------------------------------------------
| Activity Logs Configuration
|--------------------------------------------------------------------------
|
| Configure activity logging and display settings
|
*/

$config['activity_logs'] = array(
    'enabled' => true,
    'max_display' => 10,
    'date_format' => 'd.m.Y H:i',
    'types' => array(
        'create' => array('label' => 'Oluşturuldu', 'icon' => 'zmdi-plus', 'color' => 'success'),
        'update' => array('label' => 'Güncellendi', 'icon' => 'zmdi-edit', 'color' => 'info'),
        'delete' => array('label' => 'Silindi', 'icon' => 'zmdi-delete', 'color' => 'danger'),
        'approve' => array('label' => 'Onaylandı', 'icon' => 'zmdi-check', 'color' => 'success'),
        'reject' => array('label' => 'Reddedildi', 'icon' => 'zmdi-close', 'color' => 'danger')
    )
);

/*
|--------------------------------------------------------------------------
| Environment Specific Settings
|--------------------------------------------------------------------------
|
| Different settings for different environments
|
*/

// Detect environment
$environment = (defined('ENVIRONMENT')) ? ENVIRONMENT : 'production';

switch ($environment) {
    case 'development':
        $config['dashboard_debug'] = true;
        $config['cache_ttl'] = array_map(function($value) { return 60; }, $config['cache_ttl']); // 1 minute cache in dev
        $config['dashboard_refresh_interval'] = 30; // 30 seconds in dev
        break;
        
    case 'testing':
        $config['dashboard_debug'] = true;
        $config['cache_ttl'] = array_map(function($value) { return 10; }, $config['cache_ttl']); // 10 seconds cache in test
        $config['dashboard_refresh_interval'] = 15; // 15 seconds in test
        break;
        
    case 'production':
    default:
        $config['dashboard_debug'] = false;
        // Use default cache settings for production
        break;
}

/*
|--------------------------------------------------------------------------
| Animation & UI Settings
|--------------------------------------------------------------------------
|
| Configure animations and user interface behavior
|
*/

$config['animations'] = array(
    'enabled' => true,
    'aos_duration' => 800,
    'aos_easing' => 'ease-in-out-sine',
    'aos_delay' => 100,
    'aos_once' => true,
    'counter_duration' => 2000,
    'chart_animation_duration' => 1500
);

$config['ui_settings'] = array(
    'theme' => 'light',
    'sidebar_collapsed' => false,
    'breadcrumbs_enabled' => true,
    'notifications_enabled' => true,
    'fullscreen_charts' => false
);

/*
|--------------------------------------------------------------------------
| Security Settings
|--------------------------------------------------------------------------
|
| Security-related configuration for dashboard
|
*/

$config['security'] = array(
    'csrf_protection' => true,
    'xss_filtering' => true,
    'sql_injection_protection' => true,
    'session_timeout' => 3600, // 1 hour
    'max_login_attempts' => 5
);

/* End of file dashboard_config.php */
/* Location: ./application/config/dashboard_config.php */ 