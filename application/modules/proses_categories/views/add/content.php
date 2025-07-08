<div class="row">
    <div class="col-md-12">
        <h4 class="m-b-lg">
            Yeni Proses Kategorisi Ekle xxx
        </h4>
    </div><!-- END column -->
    <div class="col-md-12">
        <div class="widget">
            <div class="widget-body">
                <form action="<?php 
// Load security helper for XSS protection
$this->load->helper('security');
echo base_url("proses_categories/save"); ?>" method="post">

                    <div class="form-group">
                        <label>Başlık</label>
                        <input class="form-control" placeholder="Başlık" name="adi">
                        <?php if (isset($form_error)) { ?>
                            <small class="pull-right input-form-error"> <?php echo form_error("adi"); ?></small>
                        <?php } ?>
                    </div>

                    <div class="form-group">
                        <label>kodu</label>
                        <input class="form-control" placeholder="kodu" name="kisaltma">
                        <?php if (isset($form_error)) { ?>
                            <small class="pull-right input-form-error"> <?php echo form_error("kisaltma"); ?></small>
                        <?php } ?>
                    </div>

                    <input type="hidden" name="main_id" value="<?php echo safe_attr($main_id); ?>">
                    <input type="hidden" name="proses" value="<?php echo safe_attr($proses); ?>">
                    <button type="submit" class="btn btn-primary btn-md btn-outline">Kaydet</button>
                    <a href="<?php echo base_url("proses_categories"); ?>" class="btn btn-md btn-danger btn-outline">İptal</a>
                </form>
            </div><!-- .widget-body -->
        </div><!-- .widget -->
    </div><!-- END column -->
</div>