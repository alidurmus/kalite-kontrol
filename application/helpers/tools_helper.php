<?php

function convertToSEO($text)
{
    $turkce = ['ç', 'Ç', 'ğ', 'Ğ', 'ü', 'Ü', 'ö', 'Ö', 'ı', 'İ', 'ş', 'Ş', '.', ',', '!', "'", '"', ' ', '?', '*', '_', '|', '=', '(', ')', '[', ']', '{', '}'];
    $convert = ['c', 'c', 'g', 'g', 'u', 'u', 'o', 'o', 'i', 'i', 's', 's', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-'];

    return strtolower(str_replace($turkce, $convert, $text));
}

function tarih_ayarla($tarih, $format)
{
    $date = date_create($tarih);

    return date_format($date, $format);
}

function get_readable_date($date)
{
    return strftime('%e %B %Y', strtotime($date));
}

function get_active_user()
{
    $t = &get_instance();

    // Session library'sinin yüklenip yüklenmediğini kontrol et
    if (!isset($t->session)) {
        $t->load->library('session');
    }

    $user = $t->session->userdata('user');

    if ($user) {
        return $user;
    } else {
        return false;
    }
}

function isAdmin()
{
    $t = &get_instance();

    // Session library'sinin yüklenip yüklenmediğini kontrol et
    if (!isset($t->session)) {
        $t->load->library('session');
    }

    $user = $t->session->userdata('user');

    return true;

    if ($user && $user->user_role == 'admin') {
        return true;
    } else {
        return false;
    }
}

function get_user_roles()
{
    $t = &get_instance();

    // Session library'sinin yüklenip yüklenmediğini kontrol et
    if (!isset($t->session)) {
        $t->load->library('session');
    }

    return $t->session->userdata('user_roles');
}

function setUserRoles()
{
    $t = &get_instance();

    // Session library'sinin yüklenip yüklenmediğini kontrol et
    if (!isset($t->session)) {
        $t->load->library('session');
    }

    $t->load->model('user_roles/user_role_model');

    $user_roles = $t->user_role_model->get_all(
        [
            'isActive'  => 1,
        ]
    );

    foreach ($user_roles as $role) {
        $roles[$role->id] = $role->permissions;
    }
    $t->session->set_userdata('user_roles', $roles);
}

function getControllerList()
{
    $t = &get_instance();

    $controllers = [];
    $t->load->helper('file');

    $files = get_dir_file_info(APPPATH . 'modules', true);

    foreach (array_keys($files) as $file) {
        if ($file !== 'index.html') {
            $controllers[] = strtolower(str_replace('.php', '', $file));
        }
    }

    return $controllers;
}

function send_email($toEmail = '', $subject = '', $message = '')
{
    $t = &get_instance();

    $t->load->model('email/emailsettings_model');

    $email_settings = $t->emailsettings_model->get(
        [
            'isActive'  => 1,
        ]
    );

    $config = [
        'protocol'   => $email_settings->protocol,
        'smtp_host'  => $email_settings->host,
        'smtp_port'  => $email_settings->port,
        'smtp_user'  => $email_settings->user,
        'smtp_pass'  => $email_settings->password,
        'starttls'   => true,
        'charset'    => 'utf-8',
        'mailtype'   => 'html',
        'wordwrap'   => true,
        'newline'    => "\r\n",
    ];

    $t->load->library('email', $config);

    $t->email->from($email_settings->from, $email_settings->user_name);
    $t->email->to($toEmail);
    $t->email->subject($subject);
    $t->email->message($message);

    return $t->email->send();
}

function get_settings()
{
    $t = &get_instance();

    // Session library'sinin yüklenip yüklenmediğini kontrol et
    if (!isset($t->session)) {
        $t->load->library('session');
    }

    $t->load->model('settings/settings_model');

    // Always try to get fresh data from database first
    $settings = $t->settings_model->get();

    if (!$settings) {
        // If no settings found in database, create default object
        $settings = new stdClass();
        $settings->company_name = 'Kalite Yönetim Sistemi';
        $settings->logo = 'default';
        $settings->favicon = 'default';
        $settings->slogan = '';
        $settings->address = '';
        $settings->phone_1 = '';
        $settings->email = '';
    }

    // Cache in session for performance
    $t->session->set_userdata('settings', $settings);

    return $settings;
}

function clear_settings_cache()
{
    $t = &get_instance();

    // Session library'sinin yüklenip yüklenmediğini kontrol et
    if (!isset($t->session)) {
        $t->load->library('session');
    }

    $t->session->unset_userdata('settings');
}

function get_category_title($category_id = 0)
{
    $t = &get_instance();

    $t->load->model('portfolio_categories/portfolio_category_model');

    $category = $t->portfolio_category_model->get(
        [
            'id'    => $category_id,
        ]
    );

    if ($category) {
        return $category->title;
    } else {
        return "<b style='color:red'>Tanımlı Değil</b>";
    }
}

function fn_upload_file($input_name, $path, $types = 'gif|jpg|jpeg|png|pdf')
{
    $t = &get_instance();
    $config = [];
    $config['upload_path'] = $path;
    $config['allowed_types'] = $types;
    $config['max_size'] = '15000000';

    $t->load->library('upload');

    $t->upload->initialize($config);

    if (!is_dir($path)) {
        @mkdir($path, 0777, true);
    }

    if (!$t->upload->do_upload($input_name)) {
        return false;
    } else {
        return $t->upload->data();
    }
}

function upload_picture($file, $uploadPath, $width, $height, $name)
{
    $t = &get_instance();
    $t->load->library('simpleimagelib');

    if (!is_dir("{$uploadPath}/{$width}x{$height}")) {
        mkdir("{$uploadPath}/{$width}x{$height}");
    }

    $upload_error = false;
    try {
        $simpleImage = $t->simpleimagelib->get_simple_image_instance();

        $simpleImage
            ->fromFile($file)
            ->thumbnail($width, $height, 'center')
            ->toFile("{$uploadPath}/{$width}x{$height}/$name", null, 75);
    } catch (Exception $err) {
        $error = $err->getMessage();
        $upload_error = true;
    }

    if ($upload_error) {
        echo $error;
    } else {
        return true;
    }
}

function get_picture($path = '', $picture = '', $resolution = '50x50')
{
    if ($picture != '') {
        if (file_exists(FCPATH . "uploads/$path/$resolution/$picture")) {
            $picture = base_url("uploads/$path/$resolution/$picture");
        } else {
            $picture = base_url('assets/assets/images/default_image.png');
        }
    } else {
        $picture = base_url('assets/assets/images/default_image.png');
    }

    return $picture;
}

function get_page_list($page)
{
    $page_list = [
        'home_v'                => 'Anasayfa',
        'about_v'               => 'Hakkımızda Sayfası',
        'news_list_v'           => 'Haberler Sayfası',
        'galleries'             => 'Galeri Sayfası',
        'portfolio_list_v'      => 'Portfolyo Sayfası',
        'reference_list_v'      => 'Referanslar Sayfası',
        'service_list_v'        => 'Hizmetler Sayfası',
        'course_list_v'         => 'Eğitimler Sayfası',
        'brand_list_v'          => 'Markalar Sayfası',
        'contact_v'             => 'İletişim Sayfası',
    ];

    return (empty($page)) ? $page_list : $page_list[$page];
}

// insert işlemi yaptıktan sonra en son id nosunu alma işlemi
function get_kontrol_id($post_data)
{
    $t = &get_instance();
    $t->db->insert('kontrol_no', $post_data);
    $insert_id = $t->db->insert_id();

    return  $insert_id;
}
