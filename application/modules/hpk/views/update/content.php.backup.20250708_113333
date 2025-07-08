<div class="row">
    <div class="col-md-12">
        <h4 class="m-b-lg">
            <?php echo "<b>$item->hpk</b> kaydını düzenliyorsunuz"; ?>
        </h4>
    </div><!-- END column -->
    <div class="col-md-12">
        <div class="widget">
            <div class="widget-body">
                <form action="<?php echo base_url("hpk/update/$item->id"); ?>" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Hpk</label>
                        <input class="form-control" placeholder="Hpk" name="hpk" value="<?php echo $item->hpk; ?>">
                        <?php if(isset($form_error)){ ?>
                            <small class="pull-right input-form-error"> <?php echo form_error("hpk"); ?></small>
                        <?php } ?>
                    </div>

                    <div class="form-group">
                        <label>Proses</label>

                        <select name="proses" class="form-control">

                        <input class="form-control" placeholder="main_id" name="main_id" value="<?php echo $item->main_id; ?>">
                        <?php if(isset($form_error)){ ?>
                            <small class="pull-right input-form-error"> <?php echo form_error("main_id"); ?></small>
                        <?php } ?>
                    </div>

                    <div class="form-group col-md-12">
                            <label>Ana İşlem </label>

                            <select name="category_id" class="form-control">
                                <?php foreach($categories as $category) { ?>
                                    <?php $category_id = isset($form_error) ? set_value("category_id") : $item->category_id; ?>
                                    <option
                                        <?php echo ($category->id === $category_id) ? "selected" : ""; ?>
                                        value="<?php echo $category->id; ?>"><?php echo $category->title; ?></option>
                                <?php } ?>
                            </select>

                            <?php if(isset($form_error)){ ?>
                                <small class="pull-right input-form-error"> <?php echo form_error("category_id"); ?></small>
                            <?php } ?>
                        </div>


                    <div class="form-group">
                        <label>Ana İşlem</label>
                        <input class="form-control" placeholder="proses" name="proses" value="<?php echo $item->proses; ?>">
                        <?php if(isset($form_error)){ ?>
                            <small class="pull-right input-form-error"> <?php echo form_error("proses"); ?></small>
                        <?php } ?>
                    </div>
                    <button type="submit" class="btn btn-primary btn-md btn-outline">Güncelle</button>
                    <a href="<?php echo base_url("hpk"); ?>" class="btn btn-md btn-danger btn-outline">İptal</a>
                </form>
            </div><!-- .widget-body -->
        </div><!-- .widget -->
    </div><!-- END column -->
</div>