<?php

class Userop extends MY_Controller
{
    public $viewFolder = '';

    public function __construct()
    {
        parent::__construct();

        $this->viewFolder = 'users';

        $this->load->model('users/user_model');
    }

    public function login()
    {
        if (get_active_user()) {
            redirect(base_url());
        }

        $viewData = new stdClass();

        // Tüm kullanıcıları çek
        $users = $this->user_model->get_all();

        // View'e gönderilecek Değişkenlerin Set Edilmesi..
        $viewData->viewFolder = $this->viewFolder;
        $viewData->subViewFolder = 'login';
        $viewData->users = $users;

        $this->load->view("{$viewData->viewFolder}/{$viewData->subViewFolder}/index", $viewData);
    }

    public function do_login()
    {
        /*
           #1	Admin
           #2	Kalite
           #3	Yönetim
           #4	Planlama
           #5	Arge
        */
        $active_user = get_active_user();
        if ($active_user && is_object($active_user) && isset($active_user->user_role_id)) {
            switch ($active_user->user_role_id) {
                case 1: // admin
                    redirect(base_url('dashboard'));
                    break;
                case 2: // kalite
                    redirect(base_url('anasayfa/kalite'));
                    break;
                case 3: // yonetim
                    redirect(base_url('dashboard'));
                    break;
                case 4: // planlama
                    redirect(base_url('dashboard'));
                    break;
                case 5: // arge
                    redirect(base_url('dashboard'));
                    break;
                default:
                    redirect(base_url('dashboard'));
                    break;
            }
        }

        $this->load->library('form_validation');

        // Form validation rules
        $this->form_validation->set_rules('user_name', 'Kullanıcı Adı', 'required|trim');
        $this->form_validation->set_rules('user_password', 'Şifre', 'required|trim|min_length[4]|max_length[20]');

        $this->form_validation->set_message(
            [
                'required'    => '<b>{field}</b> alanı doldurulmalıdır',
                'valid_email' => 'Lütfen geçerli bir e-posta adresi giriniz',
                'min_length'  => '<b>{field}</b> en az 4 karakterden oluşmalıdır',
                'max_length'  => '<b>{field}</b> en fazla 20 karakterden oluşmalıdır',
            ]
        );

        // Form Validation Check
        if ($this->form_validation->run() == false) {
            // Debug: Form validation errors
            error_log("DEBUG: Form validation failed");
            error_log("DEBUG: Validation errors: " . print_r(validation_errors(), true));
            error_log("DEBUG: POST data: " . print_r($this->input->post(), true));
            
            $viewData = new stdClass();
            
            // Users listesini tekrar yükle
            $users = $this->user_model->get_all();
            $viewData->users = $users;

            // View'e gönderilecek Değişkenlerin Set Edilmesi..
            $viewData->viewFolder = $this->viewFolder;
            $viewData->subViewFolder = 'login';
            $viewData->form_error = true;

            $this->load->view("{$viewData->viewFolder}/{$viewData->subViewFolder}/index", $viewData);
            return; // Early return to prevent further execution
        }

        // Form validation passed, proceed with login
        error_log("DEBUG: Attempting login with user_name: " . $this->input->post('user_name'));
        
        // First get user by username only
        $user = $this->user_model->get(
            [
                'user_name' => $this->input->post('user_name'),
                'isActive'  => 1,
            ]
        );

        error_log("DEBUG: User found: " . ($user ? "YES" : "NO"));
        
        // Verify password using secure method
        $password_valid = false;
        if ($user) {
            $input_password = $this->input->post('user_password');
            
            // Check if password is stored as MD5 (legacy) or bcrypt (new)
            if (strlen($user->password) === 32) {
                // Legacy MD5 password - verify and upgrade
                if (md5($input_password) === $user->password) {
                    $password_valid = true;
                    
                    // Upgrade to bcrypt
                    $new_hash = password_hash($input_password, PASSWORD_DEFAULT);
                    $this->user_model->update(
                        ['id' => $user->id],
                        ['password' => $new_hash]
                    );
                    error_log("DEBUG: Password upgraded from MD5 to bcrypt for user " . $user->id);
                }
            } else {
                // Modern bcrypt password
                $password_valid = password_verify($input_password, $user->password);
            }
            
            error_log("DEBUG: Password valid: " . ($password_valid ? "YES" : "NO"));
        }

        if ($user && $password_valid) {
            // Login successful
            error_log("DEBUG: User details - ID: " . $user->id . " Role: " . $user->user_role_id);
            
            $full_name = '';
            if (!empty($user->first_name) && !empty($user->last_name)) {
                $full_name = $user->first_name . ' ' . $user->last_name;
            } elseif (!empty($user->first_name)) {
                $full_name = $user->first_name;
            } else {
                $full_name = $user->user_name;
            }

            $alert = [
                'title' => 'İşlem Başarılı',
                'text' => "$full_name hoşgeldiniz",
                'type'  => 'success',
            ];

            // Kullanici Yetkilerinin Session'a Aktarilmasi
            setUserRoles();

            // Set user session
            $this->session->set_userdata('user', $user);
            $this->session->set_flashdata('alert', $alert);

            // Role-based redirect
            switch ($user->user_role_id) {
                case 1: // admin
                    redirect(base_url('dashboard'));
                    break;
                case 2: // kalite
                    redirect(base_url('anasayfa/kalite'));
                    break;
                case 3: // yonetim
                    redirect(base_url('anasayfa/yonetim'));
                    break;
                case 4: // planlama
                    redirect(base_url('anasayfa/planlama'));
                    break;
                case 5: // arge
                    redirect(base_url('anasayfa/arge'));
                    break;
                default:
                    redirect(base_url('dashboard'));
                    break;
            }
        } else {
            // Login failed
            $alert = [
                'title' => 'İşlem Başarısız',
                'text' => 'Lütfen giriş bilgilerinizi kontrol ediniz',
                'type'  => 'error',
            ];

            $this->session->set_flashdata('alert', $alert);
            redirect(base_url('login'));
        }
    }

    public function logout()
    {
        $this->session->unset_userdata('user');
        redirect(base_url('login'));
    }

    public function forget_password()
    {
        if (get_active_user()) {
            redirect(base_url());
        }

        $viewData = new stdClass();

        // View'e gönderilecek Değişkenlerin Set Edilmesi..
        $viewData->viewFolder = $this->viewFolder;
        $viewData->subViewFolder = 'forget_password';

        $this->load->view("{$viewData->viewFolder}/{$viewData->subViewFolder}/index", $viewData);
    }

    public function reset_password()
    {
        $this->load->library('form_validation');

        // Kurallar yazilir..
        $this->form_validation->set_rules('email', 'E-posta', 'required|trim|valid_email');

        $this->form_validation->set_message(
            [
                'required'    => '<b>{field}</b> alanı doldurulmalıdır',
                'valid_email' => 'Lütfen geçerli bir <b>e-posta</b> adresi giriniz',
            ]
        );

        if ($this->form_validation->run() === false) {
            $viewData = new stdClass();

            // View'e gönderilecek Değişkenlerin Set Edilmesi..
            $viewData->viewFolder = $this->viewFolder;
            $viewData->subViewFolder = 'forget_password';
            $viewData->form_error = true;

            $this->load->view("{$viewData->viewFolder}/{$viewData->subViewFolder}/index", $viewData);
        } else {
            $user = $this->user_model->get(
                [
                    'isActive'  => 1,
                    'email'     => $this->input->post('email'),
                ]
            );

            if ($user) {
                $this->load->helper('string');
                $temp_password = random_string();

                $send = send_email($user->email, 'Şifremi Unuttum', "CMS'e geçici olarak <b>{$temp_password}</b> şifresiyle giriş yapabilirsiniz");

                if ($send) {
                    echo 'E-posta başarılı bir şekilde gonderilmiştir..';

                    $this->user_model->update(
                        [
                            'id'    => $user->id,
                        ],
                        [
                            'password'  => password_hash($temp_password, PASSWORD_DEFAULT),
                        ]
                    );

                    $alert = [
                        'title' => 'İşlem Başarılı',
                        'text' => 'Şifreniz başarılı bir şekilde resetlendi. Lütfen E-postanızı kontrol ediniz!',
                        'type'  => 'success',
                    ];

                    $this->session->set_flashdata('alert', $alert);

                    redirect(base_url('login'));

                    die();
                } else {
//                    echo $this->email->print_debugger();
                    $alert = [
                        'title' => 'İşlem Başarısız',
                        'text' => 'E-posta gönderilirken bir problem oluştu!!',
                        'type'  => 'error',
                    ];

                    $this->session->set_flashdata('alert', $alert);

                    redirect(base_url('sifremi-unuttum'));

                    die();
                }
            } else {
                $alert = [
                    'title' => 'İşlem Başarısız',
                    'text' => 'Böyle bir kullanıcı bulunamadı!!!',
                    'type'  => 'error',
                ];

                $this->session->set_flashdata('alert', $alert);

                redirect(base_url('sifremi-unuttum'));
            }
        }
    }
}
