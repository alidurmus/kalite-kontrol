# 📂 **Kod Kalitesi ve Güvenlik Kuralları - QMS Projesi**

## **🔒 1. GÜVENLİK KURALLARI - KRİTİK ÖNCELİK**

### **1.1. XSS (Cross-Site Scripting) Koruması - ZORUNLU**

```php
// ❌ YASAK - Doğrudan output
<?php echo $user_input; ?>

// ✅ ZORUNLU - Güvenli output
<?php echo htmlspecialchars($user_input, ENT_QUOTES, 'UTF-8'); ?>

// ✅ Helper function kullanımı
<?php echo safe_output($user_input); ?>
```

**Helper Function (application/helpers/security_helper.php):**
```php
<?php
if (!function_exists('safe_output')) {
    function safe_output($data, $double_encode = FALSE) {
        if (is_array($data)) {
            return array_map('safe_output', $data);
        }
        return htmlspecialchars($data, ENT_QUOTES, 'UTF-8', $double_encode);
    }
}

if (!function_exists('safe_url')) {
    function safe_url($url) {
        return filter_var($url, FILTER_SANITIZE_URL);
    }
}
```

### **1.2. CSRF Koruması - ZORUNLU**

**config.php Ayarları:**
```php
$config['csrf_protection'] = TRUE;
$config['csrf_token_name'] = 'csrf_test_name';
$config['csrf_cookie_name'] = 'csrf_cookie_name';
$config['csrf_expire'] = 7200;
$config['csrf_regenerate'] = TRUE;
```

**Form Kullanımı:**
```html
<!-- ✅ CSRF Token dahil et -->
<form method="post" action="<?php echo site_url('module/action'); ?>">
    <?php echo form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()); ?>
    <!-- form alanları -->
</form>
```

**Ajax Kullanımı:**
```javascript
// ✅ CSRF token ile Ajax
$.ajaxSetup({
    data: {
        '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
    }
});
```

### **1.3. Input Validation - ZORUNLU**

```php
// ✅ Controller'da validation
public function create() {
    $this->form_validation->set_rules('name', 'Name', 'required|min_length[3]|max_length[50]|xss_clean');
    $this->form_validation->set_rules('email', 'Email', 'required|valid_email|xss_clean');
    $this->form_validation->set_rules('phone', 'Phone', 'numeric|min_length[10]|max_length[15]');
    
    if ($this->form_validation->run() === FALSE) {
        $this->output->set_output(json_encode([
            'success' => false,
            'errors' => validation_errors()
        ]));
        return;
    }
    
    // Sanitize numeric inputs
    $data = [
        'name' => $this->security->xss_clean($this->input->post('name')),
        'email' => filter_var($this->input->post('email'), FILTER_SANITIZE_EMAIL),
        'phone' => intval($this->input->post('phone'))
    ];
}
```

### **1.4. SQL Injection Koruması - ZORUNLU**

```php
// ✅ Active Record kullanımı (Güvenli)
public function get_user_by_id($id) {
    return $this->db->where('id', intval($id))->get('users')->row();
}

// ✅ Prepared Statement (Raw SQL gerektiğinde)
public function search_users($term) {
    $sql = "SELECT * FROM users WHERE name LIKE ? OR email LIKE ?";
    $query = $this->db->query($sql, ["%{$term}%", "%{$term}%"]);
    return $query->result();
}

// ❌ YASAK - Raw SQL injection riski
public function bad_search($term) {
    $sql = "SELECT * FROM users WHERE name LIKE '%{$term}%'";
    return $this->db->query($sql)->result();
}
```

---

## **📐 2. PSR-12 KOD STANDARTLARI**

### **2.1. Class ve Method Yapısı**
```php
<?php

declare(strict_types=1);

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Services\UserService;

/**
 * User Controller
 * 
 * Handles user management operations
 */
class UserController extends Controller
{
    private UserService $userService;
    
    public function __construct()
    {
        parent::__construct();
        $this->userService = new UserService();
    }
    
    /**
     * Display user list
     *
     * @return void
     */
    public function index(): void
    {
        try {
            $users = $this->userService->getAllUsers();
            $this->load->view('users/index', ['users' => $users]);
        } catch (Exception $e) {
            log_message('error', 'User listing failed: ' . $e->getMessage());
            show_error('An error occurred while loading users.');
        }
    }
}
```

### **2.2. Naming Conventions**
```php
// ✅ Class names - PascalCase
class UserService {}
class GirdiKontrolService {}

// ✅ Method names - camelCase
public function getUserById($id) {}
public function createNewRecord($data) {}

// ✅ Variable names - snake_case
$user_data = [];
$control_result = '';

// ✅ Constants - UPPER_CASE
const MAX_UPLOAD_SIZE = 1024000;
const DEFAULT_STATUS = 'PENDING';
```

### **2.3. Indentation ve Formatting**
```php
// ✅ 4 spaces indentation
if ($condition) {
    if ($nested_condition) {
        $result = $this->processData([
            'field1' => $value1,
            'field2' => $value2,
            'field3' => $value3
        ]);
    }
}

// ✅ Array formatting
$config = [
    'key1' => 'value1',
    'key2' => 'value2',
    'nested' => [
        'subkey1' => 'subvalue1',
        'subkey2' => 'subvalue2'
    ]
];
```

---

## **🏗️ 3. SERVICE LAYER PATTERN - ZORUNLU**

### **3.1. Service Layer Yapısı**
```php
// application/services/GirdiKontrolService.php
<?php

class GirdiKontrolService
{
    private $CI;
    private $girdi_model;
    
    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->model('Girdi_model', 'girdi_model');
    }
    
    /**
     * Create new girdi kontrol record
     *
     * @param array $data
     * @return array
     * @throws Exception
     */
    public function createRecord(array $data): array
    {
        try {
            // Business logic
            $processed_data = $this->processGirdiData($data);
            
            // Validation
            if (!$this->validateGirdiData($processed_data)) {
                throw new InvalidArgumentException('Invalid girdi data');
            }
            
            // Save to database
            $result_id = $this->girdi_model->insert($processed_data);
            
            // Log activity
            log_message('info', "New girdi kontrol created: ID {$result_id}");
            
            return [
                'success' => true,
                'id' => $result_id,
                'message' => 'Girdi kontrol successfully created'
            ];
            
        } catch (Exception $e) {
            log_message('error', 'Girdi kontrol creation failed: ' . $e->getMessage());
            throw new Exception('Failed to create girdi kontrol record');
        }
    }
    
    private function processGirdiData(array $data): array
    {
        // Business logic implementation
        $data['status'] = $this->determineStatus($data);
        $data['created_at'] = date('Y-m-d H:i:s');
        return $data;
    }
    
    private function validateGirdiData(array $data): bool
    {
        // Validation logic
        return !empty($data['tedarikci']) && !empty($data['malzeme']);
    }
    
    private function determineStatus(array $data): string
    {
        // Business rule: quantity > 100 = approved, else pending
        return ($data['miktar'] > 100) ? 'APPROVED' : 'PENDING';
    }
}
```

### **3.2. Controller'da Service Kullanımı**
```php
// ❌ Fat Controller (YASAK)
public function create() {
    $data = $this->input->post();
    if ($data['deger'] > 100) {
        $data['status'] = 'RED';
    }
    $this->kontrol_model->insert($data);
}

// ✅ Thin Controller (DOĞRU)
public function create() {
    try {
        $this->load->library('GirdiKontrolService', '', 'girdi_service');
        $data = $this->input->post();
        
        $result = $this->girdi_service->createRecord($data);
        
        $this->output->set_content_type('application/json')
                     ->set_output(json_encode($result));
                     
    } catch (Exception $e) {
        $this->output->set_status_header(500)
                     ->set_content_type('application/json')
                     ->set_output(json_encode([
                         'success' => false,
                         'message' => 'An error occurred'
                     ]));
    }
}
```

---

## **⚠️ 4. EXCEPTION HANDLING - ZORUNLU**

### **4.1. Custom Exception Classes**
```php
// application/libraries/exceptions/QMSException.php
<?php

class QMSException extends Exception
{
    protected $errorCode;
    
    public function __construct($message, $errorCode = 0, Exception $previous = null)
    {
        $this->errorCode = $errorCode;
        parent::__construct($message, 0, $previous);
    }
    
    public function getErrorCode()
    {
        return $this->errorCode;
    }
}

class ValidationException extends QMSException {}
class DatabaseException extends QMSException {}
class AuthenticationException extends QMSException {}
```

### **4.2. Exception Handling Pattern**
```php
public function processData($data)
{
    try {
        // Main operation
        $result = $this->performOperation($data);
        return $result;
        
    } catch (ValidationException $e) {
        log_message('warning', 'Validation error: ' . $e->getMessage());
        throw new QMSException('Invalid data provided', 400);
        
    } catch (DatabaseException $e) {
        log_message('error', 'Database error: ' . $e->getMessage());
        throw new QMSException('Database operation failed', 500);
        
    } catch (Exception $e) {
        log_message('error', 'Unexpected error: ' . $e->getMessage());
        throw new QMSException('An unexpected error occurred', 500);
    }
}
```

---

## **📝 5. PHPDOC DOKÜMANTASYONU - ZORUNLU**

### **5.1. Class Documentation**
```php
/**
 * Girdi Kontrol Service
 * 
 * Handles business logic for incoming material quality control processes.
 * Manages creation, validation, and status determination of control records.
 *
 * @package    QMS
 * @subpackage Services
 * @category   Quality Control
 * @author     QMS Team
 * @version    2.0.0
 * @since      1.0.0
 */
class GirdiKontrolService
{
    // class content
}
```

### **5.2. Method Documentation**
```php
/**
 * Create new girdi kontrol record
 *
 * Processes incoming material data, applies business rules,
 * validates the data, and creates a new control record.
 *
 * @param  array  $data  Raw input data containing material info
 * @return array  Result array with success status and record ID
 * @throws ValidationException  When input data is invalid
 * @throws DatabaseException    When database operation fails
 * 
 * @example
 * $service = new GirdiKontrolService();
 * $result = $service->createRecord([
 *     'tedarikci' => 'ABC Ltd',
 *     'malzeme' => 'Steel Rod',
 *     'miktar' => 150
 * ]);
 */
public function createRecord(array $data): array
{
    // method implementation
}
```

---

## **🚀 6. PERFORMANCE KURALLARI**

### **6.1. Database Query Optimization**
```php
// ❌ N+1 Query Problem
public function getUsersWithRoles() {
    $users = $this->db->get('users')->result();
    foreach ($users as $user) {
        $user->role = $this->db->where('id', $user->role_id)->get('roles')->row();
    }
    return $users;
}

// ✅ Single Query with JOIN
public function getUsersWithRoles() {
    return $this->db->select('u.*, r.name as role_name')
                    ->from('users u')
                    ->join('roles r', 'u.role_id = r.id', 'left')
                    ->get()
                    ->result();
}
```

### **6.2. Caching Strategy**
```php
public function getStatistics($cache_duration = 300) {
    $cache_key = 'dashboard_statistics';
    $cached_data = $this->cache->get($cache_key);
    
    if ($cached_data !== FALSE) {
        return $cached_data;
    }
    
    // Expensive operation
    $statistics = $this->calculateStatistics();
    
    // Cache for 5 minutes
    $this->cache->save($cache_key, $statistics, $cache_duration);
    
    return $statistics;
}
```

---

## **✅ 7. KALITE KONTROL CHECKLİSTİ**

### **Her Commit Öncesi Kontroller:**
- [ ] PSR-12 formatında yazıldı
- [ ] XSS koruması eklendi
- [ ] Input validation yapıldı
- [ ] SQL injection koruması var
- [ ] Exception handling implementasyonu
- [ ] PHPDoc dokümantasyonu tamamlandı
- [ ] Service layer pattern kullanıldı
- [ ] Performance göz önünde bulunduruldu

### **Code Review Kriterleri:**
- [ ] Business logic Service layer'da
- [ ] Controller'lar thin
- [ ] Error handling comprehensive
- [ ] Security best practices uygulandı
- [ ] Database queries optimize
- [ ] Documentation complete

---

**🔧 IDE Konfigürasyonu (VSCode):**
```json
// .vscode/settings.json
{
    "editor.insertSpaces": true,
    "editor.tabSize": 4,
    "editor.detectIndentation": false,
    "php.validate.enable": true,
    "php.validate.run": "onType",
    "phpcs.enable": true,
    "phpcs.standard": "PSR12"
}
```

**Son Güncelleme:** 2025-01-07  
**Durum:** Aktif geliştirme - QMS v2.0 