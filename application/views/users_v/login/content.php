<div class="simple-page-wrap">
    <div class="simple-page-logo animated swing">
        <a href="index.html">
            <span><i class="fa fa-gg"></i></span>
            <span>TEMPAR</span>
        </a>
    </div><!-- logo -->
    <div class="simple-page-form animated flipInY" id="login-form">
        <h4 class="form-title m-b-xl text-center">Kullanıcı Giriş Ekranı</h4>
        
        <!-- CSRF Debug Info (Visible) -->
        <div style="background: #f8f9fa; padding: 10px; margin: 10px 0; border: 1px solid #ddd; font-size: 12px;">
            <strong>DEBUG INFO:</strong><br>
            CSRF Protection: <?php echo $this->config->item('csrf_protection') ? 'TRUE' : 'FALSE'; ?><br>
            Environment: <?php echo ENVIRONMENT; ?><br>
            <?php if($this->config->item('csrf_protection')): ?>
                Token Name: <?php echo $this->security->get_csrf_token_name(); ?><br>
                Token Hash: <?php echo substr($this->security->get_csrf_hash(), 0, 10); ?>...<br>
            <?php endif; ?>
        </div>
        
        <form action="<?php echo base_url("userop/do_login"); ?>" method="post">
            <?php if($this->config->item('csrf_protection')): ?>
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
                <div style="color: green; font-size: 12px; margin: 5px 0;">✅ CSRF Token Added</div>
            <?php else: ?>
                <div style="color: red; font-size: 12px; margin: 5px 0;">❌ CSRF Protection Disabled</div>
            <?php endif; ?>
            
            <div class="form-group">
                <select id="sign-in-email" tabindex="1" class="form-control" name="user_name">
                    <option value="">Kullanıcı Seçiniz..</option>
                    <?php foreach ($users as $user) { ?>
                        <option value="<?php echo $user->user_name; ?>"><?php echo $user->full_name; ?></option>
                    <?php } ?>

                </select>
            </div><!-- form-group -->
            <div class="form-group">
                <input id="sign-in-password" type="password" tabindex="2" class="form-control" name="user_password" placeholder="Şifre" value="">
            </div><!-- form-group -->
            <div class="form-group text-center">
                <button id="sign-in-submit" type="submit" tabindex="3" class="btn btn-block btn-primary">
                    <i class="fa fa-lock"></i> Giriş
                </button>
            </div><!-- form-group -->
        </form>
    </div><!-- .simple-page-form -->
    
    <div class="simple-page-footer">
        <p><a href="<?php echo base_url('sifremi-unuttum'); ?>">Şifremi Unuttum ?</a></p>
    </div><!-- .simple-page-footer -->
</div><!-- .simple-page-wrap -->