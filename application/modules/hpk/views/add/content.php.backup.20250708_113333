<div class="row">
    <div class="col-md-12">
        <h4 class="m-b-lg">
            Yeni Hammadde Parti Kodu Ekle
        </h4>
    </div><!-- END column -->
    <div class="col-md-12">
        <div class="widget">
            <div class="widget-body">
                <form action="<?php echo base_url("hpk/save"); ?>" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Hammadde Parti Kodu</label>
                        <input class="form-control" placeholder="Hpk" name="hpk">
                        <?php if (isset($form_error)) { ?>
                            <small class="pull-right input-form-error"> <?php echo form_error("hpk"); ?></small>
                        <?php } ?>
                    </div>


                    <input type="hidden" name="main_id" value="<?php echo $main_id ?>">
                    <input type="hidden" name="proses" value="<?php echo $proses ?>">
                    <button type="submit" class="btn btn-primary btn-md btn-outline">Kaydet</button>
                    <a href="<?php echo base_url("hpk"); ?>" class="btn btn-md btn-danger btn-outline">İptal</a>
                </form>
            </div><!-- .widget-body -->
        </div><!-- .widget -->
    </div><!-- END column -->
</div>