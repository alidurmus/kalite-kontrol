<div class="row">
    <div class="col-md-12">
        <h4 class="m-b-lg">
            <?php echo "<b>$item->id</b> kaydını düzenliyorsunuz"; ?>
        </h4>
    </div><!-- END column -->
    <div class="col-md-12">
        <div class="widget">
            <div class="widget-body">
                <form action="<?php echo base_url("proses_categories/update/$item->id"); ?>" method="post">
                    <div class="form-group">
                        <label>Ana Proses</label>

                        <select name="main_id" class="form-control">
                            <?php foreach ($categories as $category) { ?>
                                <?php $main_id = isset($form_error) ? set_value("main_id") : $item->category_id; ?>
                                <option <?php echo ($category->id === $main_id) ? "selected" : ""; ?> value="<?php echo $category->id; ?>"><?php echo $category->title; ?></option>
                            <?php } ?>
                        </select>

                        <?php if (isset($form_error)) { ?>
                            <small class="pull-right input-form-error"> <?php echo form_error("category_id"); ?></small>
                        <?php } ?>
                    </div>

                    <div class="form-group">
                        <label>Alt Proses</label>

                        <select name="proses_id" class="form-control">
                            <?php foreach ($categories as $category) { ?>
                                <?php $proses_id = isset($form_error) ? set_value("proses_id") : $item->category_id; ?>
                                <option <?php echo ($category->id === $proses_id) ? "selected" : ""; ?> value="<?php echo $category->id; ?>"><?php echo $category->title; ?></option>
                            <?php } ?>
                        </select>

                        <?php if (isset($form_error)) { ?>
                            <small class="pull-right input-form-error"> <?php echo form_error("category_id"); ?></small>
                        <?php } ?>
                    </div>

                    <button type="submit" class="btn btn-primary btn-md btn-outline">Güncelle</button>
                    <a href="<?php echo base_url("proses_categories"); ?>" class="btn btn-md btn-danger btn-outline">İptal</a>
                </form>
            </div><!-- .widget-body -->
        </div><!-- .widget -->
    </div><!-- END column -->
</div>