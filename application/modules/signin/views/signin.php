<?php

// Load security helper for XSS protection
$this->load->helper('security');
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="tr">
    <head>
        <meta charset="utf-8">
        <title><?php echo safe_output($title); ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
        <style>
            body {
                background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            .login-card {
                background: rgba(172, 152, 152, 0.95);
                border-radius: 1rem;
                box-shadow: 0 8px 32px 0 rgba(31,38,135,0.2);
                padding: 2.5rem 2rem 2rem 2rem;
                max-width: 370px;
                width: 100%;
            }
            .login-card .form-control {
                border-radius: 2rem;
                padding-left: 2.5rem;
            }
            .login-card .input-icon {
                position: absolute;
                left: 15px;
                top: 50%;
                transform: translateY(-50%);
                color: #6a11cb;
            }
            .login-card .btn-primary {
                border-radius: 2rem;
                font-weight: bold;
                letter-spacing: 1px;
            }
            .login-card .form-group {
                position: relative;
            }
            .login-card .error {
                color: #fff;
                background: #e74c3c;
                border-radius: 0.5rem;
                padding: 0.5rem 1rem;
                margin-bottom: 1rem;
                text-align: center;
            }
            @media (max-width: 500px) {
                .login-card { padding: 1.5rem 0.5rem; }
            }
        </style>
    </head>
    <body>
        <div class="login-card mx-auto">
            <h3 class="text-center mb-4 font-weight-bold" style="color:#2575fc">Giriş Yap</h3>
            <?php if (validation_errors()) { ?>
                <div class="error">
                    <?php echo validation_errors(' ', ' '); ?>
                </div>
            <?php } ?>
            <?php echo form_open('signin'); ?>
                <div class="form-group">
                    <span class="input-icon"><i class="fas fa-user"></i></span>
                    <input type="text" name="username" class="form-control pl-5" placeholder="Kullanıcı Adı" required autofocus>
                </div>
                <div class="form-group">
                    <span class="input-icon"><i class="fas fa-lock"></i></span>
                    <input type="password" name="password" class="form-control pl-5" placeholder="Şifre" required>
                </div>
                <div class="form-group form-check mb-3">
                    <input type="checkbox" class="form-check-input" id="rememberMe">
                    <label class="form-check-label" for="rememberMe">Beni Hatırla</label>
                </div>
                <button type="submit" class="btn btn-primary btn-block py-2">Giriş Yap</button>
            <?php echo form_close(); ?>
            <div class="text-center mt-3">
                <a href="<?php echo base_url(); ?>" class="text-secondary">Siteye Dön</a>
            </div>
            <p class="footer text-center mt-3 mb-0" style="font-size:12px; color:#888;">Sayfa yüklenme süresi <strong>{elapsed_time}</strong> sn. <?php echo (ENVIRONMENT === 'development') ? 'CodeIgniter Sürüm <strong>' . CI_VERSION . '</strong>' : '' ?></p>
        </div>
    </body>
</html>