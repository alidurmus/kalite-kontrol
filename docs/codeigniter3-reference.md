# CodeIgniter 3 Framework Referansı

**Tarih:** 2025-01-07  
**Kaynak:** https://codeigniter.com/userguide3/  
**Proje:** Kalite Yönetim Sistemi  

---

## 📋 İçerik

1. [Framework Genel Bakış](#framework-genel-bakış)
2. [MVC Mimarisi](#mvc-mimarisi)
3. [Database İşlemleri](#database-işlemleri)
4. [Güvenlik](#güvenlik)
5. [Routing](#routing)
6. [Controllers](#controllers)
7. [Models](#models)
8. [Views](#views)
9. [Libraries](#libraries)
10. [Helpers](#helpers)
11. [HMVC Yapısı](#hmvc-yapısı)
12. [Best Practices](#best-practices)

---

## 🎯 Framework Genel Bakış

### URL Yapısı
CodeIgniter segment-based URL yapısı kullanır:
```
example.com/controller/method/parameter1/parameter2
```

### Temel Özellikler
- **MVC Pattern:** Model-View-Controller mimarisi
- **Active Record:** Database işlemleri için
- **Template Engine:** Native PHP (performans için)
- **Security:** Built-in XSS ve CSRF koruması
- **Caching:** Query ve page caching
- **Error Handling:** Kapsamlı hata yönetimi

---

## 🏗️ MVC Mimarisi

### Controller Yapısı
```php
<?php
class Blog extends CI_Controller {
    
    public function __construct()
    {
        parent::__construct();
        // Constructor kodları
    }
    
    public function index()
    {
        echo 'Hello World!';
    }
    
    public function comments()
    {
        echo 'Look at this!';
    }
}
```

### Model Yapısı
```php
<?php
class Blog_model extends CI_Model {
    
    public $title;
    public $content;
    public $date;
    
    public function get_last_ten_entries()
    {
        $query = $this->db->get('entries', 10);
        return $query->result();
    }
    
    public function insert_entry()
    {
        $this->title = $_POST['title'];
        $this->content = $_POST['content'];
        $this->date = time();
        
        $this->db->insert('entries', $this);
    }
    
    public function update_entry()
    {
        $this->title = $_POST['title'];
        $this->content = $_POST['content'];
        $this->date = time();
        
        $this->db->update('entries', $this, array('id' => $_POST['id']));
    }
}
```

### View Yapısı
```html
<html lang="en">
<head>
    <title><?php echo $title;?></title>
</head>
<body>
    <h1><?php echo $heading;?></h1>
    
    <h3>My Todo List</h3>
    <ul>
    <?php foreach ($todo_list as $item):?>
        <li><?php echo $item;?></li>
    <?php endforeach;?>
    </ul>
</body>
</html>
```

---

## 💾 Database İşlemleri

### Database Bağlantısı
```php
// Otomatik yükleme
$this->load->database();

// Belirli grup
$this->load->database('group_name');

// Manuel yapılandırma
$config['hostname'] = 'localhost';
$config['username'] = 'myusername';
$config['password'] = 'mypassword';
$config['database'] = 'mydatabase';
$config['dbdriver'] = 'mysqli';
$this->load->database($config);
```

### Query Builder (Active Record)
```php
// SELECT
$query = $this->db->get('table_name');
foreach ($query->result() as $row) {
    echo $row->title;
}

// INSERT
$data = array(
    'title' => $title,
    'name' => $name,
    'date' => $date
);
$this->db->insert('mytable', $data);

// UPDATE
$data = array(
    'title' => $title,
    'name' => $name
);
$this->db->where('id', $id);
$this->db->update('mytable', $data);

// DELETE
$this->db->where('id', $id);
$this->db->delete('mytable');
```

### Raw SQL Queries
```php
// Temel query
$query = $this->db->query('SELECT name, title, email FROM my_table');

// Güvenli query (escaped)
$sql = "INSERT INTO mytable (title, name) VALUES (".$this->db->escape($title).", ".$this->db->escape($name).")";
$this->db->query($sql);

// Sonuçları alma
foreach ($query->result() as $row) {
    echo $row->title;
    echo $row->name;
    echo $row->email;
}

// Array olarak
foreach ($query->result_array() as $row) {
    echo $row['title'];
    echo $row['name'];
    echo $row['email'];
}

// Tek satır
$query = $this->db->query('SELECT name FROM my_table LIMIT 1'); 
$row = $query->row();
echo $row->name;
```

### Transactions
```php
// Otomatik transaction
$this->db->trans_start();
$this->db->query('AN SQL QUERY...');
$this->db->query('ANOTHER QUERY...');
$this->db->trans_complete();

// Manuel transaction
$this->db->trans_begin();
$this->db->query('AN SQL QUERY...');
$this->db->query('ANOTHER QUERY...');

if ($this->db->trans_status() === FALSE) {
    $this->db->trans_rollback();
} else {
    $this->db->trans_commit();
}
```

---

## 🔒 Güvenlik

### XSS Koruması
```php
// Input'u temizleme
$data = $this->input->post('data', TRUE); // XSS filtreleme aktif

// Output'u güvenli hale getirme
echo htmlspecialchars($data, ENT_QUOTES, 'UTF-8');

// Helper fonksiyon
function safe_output($data, $double_encode = FALSE) {
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8', $double_encode);
}
```

### CSRF Koruması
```php
// Config'de aktifleştirme
$config['csrf_protection'] = TRUE;
$config['csrf_token_name'] = 'csrf_test_name';
$config['csrf_cookie_name'] = 'csrf_cookie_name';

// Form'da kullanım
echo form_open('email/send');
echo form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash());
```

### Input Validation
```php
// Form validation
$this->load->library('form_validation');

$this->form_validation->set_rules('username', 'Username', 'required');
$this->form_validation->set_rules('password', 'Password', 'required');
$this->form_validation->set_rules('email', 'Email', 'required|valid_email');

if ($this->form_validation->run() == FALSE) {
    $this->load->view('myform');
} else {
    $this->load->view('formsuccess');
}
```

### URI Güvenliği
```php
// İzin verilen karakterler
$config['permitted_uri_chars'] = 'a-z 0-9~%.:_-';

// Null karakter temizleme
$clean_string = remove_invisible_characters('Java\0script');
// Returns: 'Javascript'
```

---

## 🛣️ Routing

### Temel Routing
```php
// routes.php dosyasında
$route['default_controller'] = 'welcome';
$route['404_override'] = '';

// Basit yönlendirme
$route['journals'] = 'blogs';

// Parametre ile
$route['blog/joe'] = 'blogs/users/34';

// Wildcard kullanımı
$route['product/:num'] = 'catalog/product_lookup';
$route['product/(:any)'] = 'catalog/product_lookup';
$route['product/(:num)'] = 'catalog/product_lookup_by_id/$1';

// Regular expressions
$route['products/([a-z]+)/(\d+)'] = '$1/id_$2';

// HTTP verbs
$route['products']['put'] = 'product/insert';
$route['products/(:num)']['DELETE'] = 'product/delete/$1';

// Callback functions
$route['products/([a-zA-Z]+)/edit/(\d+)'] = function ($product_type, $id) {
    return 'catalog/product_edit/' . strtolower($product_type) . '/' . $id;
};
```

### URI Dash Translation
```php
$route['translate_uri_dashes'] = TRUE; // my-method -> my_method
```

---

## 🎮 Controllers

### Temel Controller
```php
<?php
class Blog extends CI_Controller {
    
    public function __construct()
    {
        parent::__construct();
        // Library ve model yükleme
        $this->load->model('blog_model');
        $this->load->helper('url');
    }
    
    public function index()
    {
        $data['query'] = $this->blog_model->get_last_ten_entries();
        $this->load->view('blog', $data);
    }
}
```

### Method Remapping
```php
public function _remap($method, $params = array())
{
    $method = 'process_'.$method;
    if (method_exists($this, $method)) {
        return call_user_func_array(array($this, $method), $params);
    }
    show_404();
}
```

### Private Methods
```php
private function _utility()
{
    // Private method - URL'den erişilemez
}
```

### Output Processing
```php
public function _output($output)
{
    echo $output;
}
```

---

## 📊 Models

### Model Yükleme
```php
// Controller'da
$this->load->model('model_name');
$this->model_name->method();

// Özel isim ile
$this->load->model('model_name', 'foobar');
$this->foobar->method();

// Database ile
$this->load->model('model_name', '', TRUE);

// Özel database config ile
$config['hostname'] = 'localhost';
$config['username'] = 'myusername';
// ... diğer config
$this->load->model('model_name', '', $config);
```

### Model Yapısı
```php
<?php
class News_model extends CI_Model {
    
    public function __construct()
    {
        $this->load->database();
    }
    
    public function get_news($slug = FALSE)
    {
        if ($slug === FALSE) {
            $query = $this->db->get('news');
            return $query->result_array();
        }
        
        $query = $this->db->get_where('news', array('slug' => $slug));
        return $query->row_array();
    }
}
```

---

## 👀 Views

### View Yükleme
```php
// Basit view
$this->load->view('blogview');

// Data ile
$data['title'] = "My Real Title";
$data['heading'] = "My Real Heading";
$this->load->view('blogview', $data);

// Çoklu view
$this->load->view('header');
$this->load->view('menu');
$this->load->view('content', $data);
$this->load->view('footer');
```

### Template Yapısı
```php
// Header template
<html lang="en">
<head>
    <title><?php echo $title; ?></title>
</head>
<body>
    <h1><?php echo $title; ?></h1>

// Footer template
</body>
</html>
```

---

## 📚 Libraries

### Library Yükleme
```php
$this->load->library('email');
$this->load->library('session');

// Parametreli
$config = array(
    'protocol' => 'smtp',
    'smtp_host' => 'ssl://smtp.googlemail.com'
);
$this->load->library('email', $config);
```

### Özel Library Oluşturma
```php
<?php
defined('BASEPATH') OR exit('No direct script access allowed'); 

class Someclass {
    
    protected $CI;
    
    public function __construct()
    {
        $this->CI =& get_instance();
    }
    
    public function some_method()
    {
        $this->CI->load->helper('url');
        $this->CI->config->item('base_url');
    }
}
```

### Core Class Extension
```php
// MY_Input.php
class MY_Input extends CI_Input {
    // Özel metodlar
}

// Config'de prefix ayarı
$config['subclass_prefix'] = 'MY_';
```

---

## 🔧 Helpers

### Helper Yükleme
```php
$this->load->helper('url');
$this->load->helper(array('url', 'file'));

// Autoload'da
$autoload['helper'] = array('url', 'file');
```

### Common Functions
```php
// PHP version kontrolü
if (is_php('5.5')) {
    echo json_last_error_msg();
}

// Null karakter temizleme
$clean = remove_invisible_characters('Java\0script');

// CodeIgniter instance
$CI =& get_instance();
```

---

## 🏢 HMVC Yapısı

### Modül Yapısı
```
application/
├── modules/
│   ├── blog/
│   │   ├── controllers/
│   │   ├── models/
│   │   ├── views/
│   │   └── config/
│   └── news/
│       ├── controllers/
│       ├── models/
│       └── views/
```

### Modüller Arası İletişim
```php
// Modül çağırma
$data = Modules::run('blog/get_recent_posts', 5);

// Library yükleme
$this->load->module_library('blog', 'blog_lib');
```

---

## 🎯 Best Practices

### Güvenlik Best Practices
```php
// ✅ DOĞRU - XSS koruması
echo htmlspecialchars($data, ENT_QUOTES, 'UTF-8');

// ✅ DOĞRU - CSRF koruması
echo form_open('controller/method');

// ✅ DOĞRU - SQL Injection koruması
$this->db->where('id', $id);
$this->db->get('table');

// ❌ YANLIŞ - Raw SQL
$this->db->query("SELECT * FROM table WHERE id = " . $id);
```

### Performance Best Practices
```php
// ✅ Query caching
$this->db->cache_on();
$query = $this->db->get('table');
$this->db->cache_off();

// ✅ Database connection
$this->load->database('', FALSE, TRUE); // Persistent connection

// ✅ Autoloading
$autoload['libraries'] = array('database', 'session');
```

### Code Organization
```php
// ✅ Service Layer Pattern
class Blog_service {
    public function create_post($data) {
        // Business logic here
        return $this->blog_model->insert($data);
    }
}

// ✅ Controller sadece HTTP işlemleri
public function create() {
    $data = $this->input->post();
    $result = $this->blog_service->create_post($data);
    
    if ($result) {
        redirect('blog/success');
    } else {
        $this->load->view('blog/error');
    }
}
```

---

## 🔍 Debug ve Profiling

### Profiler Kullanımı
```php
$this->output->enable_profiler(TRUE);

// Belirli bölümler
$sections = array(
    'config'  => TRUE,
    'queries' => TRUE
);
$this->output->set_profiler_sections($sections);
```

### Error Handling
```php
// Environment ayarları
define('ENVIRONMENT', 'development'); // veya 'production'

// Log seviyesi
$config['log_threshold'] = 1; // 0=off, 1=error, 2=debug, 3=info, 4=all

// Özel error handling
if ($this->db->trans_status() === FALSE) {
    log_message('error', 'Database transaction failed');
    show_error('An error occurred');
}
```

---

## 📝 Migration ve Database Forge

### Migration Yapısı
```php
<?php
class Migration_Create_blog extends CI_Migration {
    
    public function up() {
        $this->dbforge->add_field(array(
            'blog_id' => array(
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'blog_title' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
            ),
            'blog_description' => array(
                'type' => 'TEXT',
                'null' => TRUE,
            ),
        ));
        $this->dbforge->add_key('blog_id', TRUE);
        $this->dbforge->create_table('blog');
    }
    
    public function down() {
        $this->dbforge->drop_table('blog');
    }
}
```

### Database Forge Kullanımı
```php
$this->load->dbforge();

// Tablo oluşturma
$fields = array(
    'users_id' => array(
        'type' => 'INT',
        'constraint' => 5,
        'unsigned' => TRUE,
        'auto_increment' => TRUE
    ),
    'username' => array(
        'type' => 'VARCHAR',
        'constraint' => '100',
        'unique' => TRUE,
    ),
);

$this->dbforge->add_field($fields);
$this->dbforge->add_key('users_id', TRUE);
$this->dbforge->create_table('users');

// Kolon ekleme
$fields = array(
    'preferences' => array('type' => 'TEXT')
);
$this->dbforge->add_column('table_name', $fields);

// Kolon silme
$this->dbforge->drop_column('table_name', 'column_to_drop');
```

---

## 🌐 Session Management

### Session Konfigürasyonu
```php
// Config/session.php
$config['sess_driver'] = 'files';
$config['sess_cookie_name'] = 'ci_session';
$config['sess_expiration'] = 7200;
$config['sess_save_path'] = NULL;
$config['sess_match_ip'] = FALSE;
$config['sess_time_to_update'] = 300;
$config['sess_regenerate_destroy'] = FALSE;
```

### Session Kullanımı
```php
// Session başlatma
$this->load->library('session');

// Veri ekleme
$this->session->set_userdata('username', 'johndoe');

// Veri okuma
$username = $this->session->userdata('username');
// veya
$username = $_SESSION['username'];

// Veri silme
$this->session->unset_userdata('username');

// Tüm session verisi
$session_data = $this->session->userdata();

// Flash data
$this->session->set_flashdata('message', 'Hello World!');
$message = $this->session->flashdata('message');
```

---

## 📧 Email Library

### Email Konfigürasyonu
```php
$config = array(
    'protocol' => 'smtp',
    'smtp_host' => 'ssl://smtp.googlemail.com',
    'smtp_port' => 465,
    'smtp_user' => 'myemail@gmail.com',
    'smtp_pass' => 'mypassword',
    'mailtype'  => 'html',
    'charset'   => 'utf-8'
);

$this->load->library('email', $config);
```

### Email Gönderme
```php
$this->load->library('email');

$this->email->from('your@example.com', 'Your Name');
$this->email->to('someone@example.com');
$this->email->cc('another@another-example.com');
$this->email->bcc('them@their-example.com');

$this->email->subject('Email Test');
$this->email->message('Testing the email class.');

if ($this->email->send()) {
    echo 'Email sent successfully';
} else {
    echo $this->email->print_debugger();
}
```

---

## 🔄 Form Validation

### Validation Rules
```php
$this->load->library('form_validation');

$this->form_validation->set_rules('username', 'Username', 'required|min_length[5]|max_length[12]|is_unique[users.username]');
$this->form_validation->set_rules('password', 'Password', 'required|matches[passconf]');
$this->form_validation->set_rules('passconf', 'Password Confirmation', 'required');
$this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[users.email]');

if ($this->form_validation->run() == FALSE) {
    $this->load->view('signup_form');
} else {
    $this->load->view('signup_success');
}
```

### Custom Validation
```php
public function username_check($str)
{
    if ($str == 'test') {
        $this->form_validation->set_message('username_check', 'The {field} field can not be the word "test"');
        return FALSE;
    } else {
        return TRUE;
    }
}

// Rule'da kullanım
$this->form_validation->set_rules('username', 'Username', 'callback_username_check');
```

---

## 🌍 Internationalization

### Language Files
```php
// application/language/english/message_lang.php
$lang['message_welcome'] = 'Welcome to our site!';
$lang['message_goodbye'] = 'Thanks for visiting!';

// application/language/turkish/message_lang.php
$lang['message_welcome'] = 'Sitemize hoş geldiniz!';
$lang['message_goodbye'] = 'Ziyaretiniz için teşekkürler!';
```

### Language Kullanımı
```php
// Language yükleme
$this->lang->load('message', 'turkish');

// Dil değişkenini alma
$welcome_message = $this->lang->line('message_welcome');

// Session'a göre dil seçimi
$idiom = $this->session->userdata('language');
$this->lang->load('error_messages', $idiom);
```

---

## 📊 Caching

### Page Caching
```php
// Controller'da
$this->output->cache(60); // 60 dakika cache

// Cache silme
$this->output->delete_cache('/blog/comments');
```

### Query Caching
```php
// Query cache açma
$this->db->cache_on();

$query = $this->db->get('mytable');

// Cache kapatma
$this->db->cache_off();

// Cache silme
$this->db->cache_delete('blog', 'comments');
$this->db->cache_delete_all();
```

---

## 🔧 Configuration

### Environment-based Config
```php
// index.php'de
define('ENVIRONMENT', isset($_SERVER['CI_ENV']) ? $_SERVER['CI_ENV'] : 'development');

// Config dosyalarında
if (ENVIRONMENT == 'development') {
    $config['base_url'] = 'http://localhost/myapp/';
} else {
    $config['base_url'] = 'https://www.mysite.com/';
}
```

### Dynamic Base URL
```php
$allowed_domains = array('domain1.tld', 'domain2.tld');
$default_domain  = 'domain1.tld';

if (in_array($_SERVER['HTTP_HOST'], $allowed_domains, TRUE)) {
    $domain = $_SERVER['HTTP_HOST'];
} else {
    $domain = $default_domain;
}

if (!empty($_SERVER['HTTPS'])) {
    $config['base_url'] = 'https://'.$domain;
} else {
    $config['base_url'] = 'http://'.$domain;
}
```

---

## 🚀 Performance Optimizasyonu

### Database Optimizasyonu
```php
// Connection pooling
$db['default']['pconnect'] = TRUE;

// Query caching
$db['default']['cache_on'] = TRUE;
$db['default']['cachedir'] = APPPATH.'cache/db/';

// Compression
$config['compress_output'] = TRUE;
```

### Autoloading
```php
// application/config/autoload.php
$autoload['packages'] = array();
$autoload['libraries'] = array('database', 'session');
$autoload['drivers'] = array();
$autoload['helper'] = array('url', 'form');
$autoload['config'] = array();
$autoload['language'] = array();
$autoload['model'] = array();
```

---

## 📋 Proje Entegrasyonu

### QMS Projesi için Özel Notlar

#### 1. Güvenlik Implementasyonu
```php
// application/helpers/security_helper.php (mevcut)
function safe_output($data, $double_encode = FALSE) {
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8', $double_encode);
}

function safe_attr($data) {
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

function safe_url($data) {
    return filter_var($data, FILTER_SANITIZE_URL);
}
```

#### 2. HMVC Modül Yapısı
```
application/modules/
├── girdikontrol/
├── proseskontrol/
├── finalkontrol/
├── tedarikciler/
└── migration/
```

#### 3. Database Migration Pattern
```php
// MeasurementDataService kullanımı
public function migrate_json_to_files() {
    $this->load->library('MeasurementDataService');
    
    $records = $this->get_records_with_json();
    foreach ($records as $record) {
        $result = $this->measurementdataservice->migrateToFile(
            $record['olcum_degerleri'], 
            $record['id'], 
            'gi'
        );
        
        if ($result['success']) {
            $this->update_record_with_file_path($record['id'], $result['file_path']);
        }
    }
}
```

#### 4. Performance Monitoring
```php
// application/controllers/Performance.php
public function test_database() {
    $this->benchmark->mark('db_start');
    
    $query = $this->db->get('girdikontrol');
    
    $this->benchmark->mark('db_end');
    
    echo 'Database query time: ' . $this->benchmark->elapsed_time('db_start', 'db_end');
}
```

---

## 📚 Referanslar

- **Resmi Dokümantasyon:** https://codeigniter.com/userguide3/
- **GitHub Repository:** https://github.com/bcit-ci/CodeIgniter
- **Community Forum:** https://forum.codeigniter.com/
- **Stack Overflow:** https://stackoverflow.com/questions/tagged/codeigniter

---

**Son Güncelleme:** 2025-01-07  
**Versiyon:** CodeIgniter 3.1.x  
**Proje:** Kalite Yönetim Sistemi v2.1.0 