<div class="simple-page-wrap">
    <div class="simple-page-logo animated swing">
        <a href="index.html">
            <span><i class="fa fa-gg"></i></span>
            <span>TEMPAR</span>
        </a>
    </div><!-- logo -->
    <div class="simple-page-form animated flipInY" id="login-form">
        <h4 class="form-title m-b-xl text-center">Kullanıcı Giriş Ekranı</h4>
        
        <?php if(isset($form_error) && $form_error): ?>
            <div class="alert alert-danger">
                <h5>Form Hataları:</h5>
                <?php echo validation_errors('<p class="text-danger">', '</p>'); ?>
            </div>
        <?php endif; ?>
        
        <form action="<?php 
// Load security helper for XSS protection
$this->load->helper('security');
echo base_url("userop/do_login"); ?>" method="post">
            
            <?php if($this->config->item('csrf_protection')): ?>
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
            <?php endif; ?>
            <div class="form-group">           
                <select id="sign-in-email"  name="user_name" class="form-control" required>
                    <?php if(isset($users) && !empty($users)) { ?>
                        <?php foreach($users as $user) { ?>                   
                            <option value="<?php echo safe_attr($user->user_name); ?>">
                                <?php echo isset($user->full_name) && !empty($user->full_name) ? $user->full_name : $user->user_name; ?>
                            </option>
                        <?php } ?>
                    <?php } else { ?>
                        <option value="admin">Admin Kullanıcı</option>
                    <?php } ?>
                </select>
                <?php if(isset($form_error)){ ?>
                    <small class="pull-right input-form-error"> <?php echo form_error("user_name"); ?></small>
                <?php } ?>
            </div>
            <div class="form-group">
                <input id="sign-in-password" type="password" class="form-control" placeholder="Şifre" name="user_password" required>
                <?php if(isset($form_error)){ ?>
                    <small class="pull-right input-form-error"> <?php echo form_error("user_password"); ?></small>
                <?php } ?>
            </div>
            <button type="submit" class="btn btn-primary">Giriş Yap</button>
        </form>
    </div><!-- #login-form -->
    <div class="simple-page-footer">
        <p><a href="<?php echo base_url("sifremi-unuttum"); ?>">Şifremi Unuttum ?</a></p>
    </div><!-- .simple-page-footer -->
</div><!-- .simple-page-wrap -->