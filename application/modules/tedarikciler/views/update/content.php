<div class="row">
    <div class="col-md-12">
        <h4 class="m-b-lg">
            <?php 
// Load security helper for XSS protection
$this->load->helper('security');
echo "<b>".safe_output($item->adi)."</b> kaydını düzenliyorsunuz"; ?>
        </h4>
    </div><!-- END column -->
    <div class="col-md-12">
        <div class="widget">
            <div class="widget-body">
                <form action="<?php echo base_url("tedarikciler/update/".safe_attr($item->id)); ?>" method="post">

                    <div class="form-group">
                        <label>Adı</label>
                        <input class="form-control" placeholder="Adı" name="adi" value="<?php echo isset($form_error) ? set_value("adi") : safe_attr($item->adi); ?>">
                        <?php if(isset($form_error)){ ?>
                            <small class="pull-right input-form-error"> <?php echo form_error("adi"); ?></small>
                        <?php } ?>
                    </div>

                    <div class="form-group">
                        <label>Kodu</label>
                        <input class="form-control" placeholder="kodu" name="kodu" value="<?php echo isset($form_error) ? set_value("kodu") : safe_attr($item->kodu); ?>">
                        <?php if(isset($form_error)){ ?>
                            <small class="pull-right input-form-error"> <?php echo form_error("kodu"); ?></small>
                        <?php } ?>
                    </div>

                    <div class="form-group">
                        <label>Açıklama</label>
                        <textarea name="aciklama" class="m-0" data-plugin="summernote" data-options="{height: 250}"><?php echo isset($form_error) ? set_value("aciklama") : safe_output($item->aciklama); ?></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary btn-md btn-outline">Güncelle</button>
                    <a href="<?php echo base_url("tedarikciler"); ?>" class="btn btn-md btn-danger btn-outline">İptal</a>
                </form>
            </div><!-- .widget-body -->
        </div><!-- .widget -->
    </div><!-- END column -->
</div>