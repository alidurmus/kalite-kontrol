<div class="row">
    <div class="col-md-12">
        <h4 class="m-b-lg">
            <?php echo "<b>$item->id</b> kaydını düzenliyorsunuz"; ?>
        </h4>
    </div><!-- END column -->
    <div class="col-md-12">
        <div class="widget">
            <div class="widget-body">
                <form action="<?php echo base_url("girdikontrol/update/$item->id"); ?>" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>parti_no</label>
                        <input class="form-control" placeholder="parti_no" name="parti_no" value="<?php echo $item->parti_no; ?>">
                        <?php if(isset($form_error)){ ?>
                            <small class="pull-right input-form-error"> <?php echo form_error("parti_no"); ?></small>
                        <?php } ?>
                    </div>
                    <div class="form-group">
                        <label for="control-demo-6" class="">Tedarikçiler</label>
                        <div id="control-demo-6" class="">                          
                            <select class="form-control news_type_select" name="tedarikci">
                                <option value="<?php echo $item->tedarikci; ?>"><?php echo $item->tedarikci; ?></option>
                                <?php foreach($tedarikciler as $tedarikci2) { ?>
                                    <option value="<?php echo $tedarikci2->id; ?>"><?php echo $tedarikci2->adi; ?></option>
                                <?php } ?>                                     
                            </select>                           
                        </div>
                    </div><!-- .form-group -->
                    <div class="form-group">
                        <label for="control-demo-6" class="">Malzemeler</label>
                        <div id="control-demo-6" class="">                          
                            <select class="form-control news_type_select" name="malzeme">
                                <option value="<?php echo $item->malzeme; ?>"><?php echo $item->malzeme; ?></option>
                                <?php foreach($malzemeler as $malzeme2) { ?>
                                    <option value="<?php echo $malzeme2->id; ?>"><?php echo $malzeme2->adi; ?></option>
                                <?php } ?>                                     
                            </select>                           
                        </div>
                    </div><!-- .form-group -->
                    <div class="form-group">
                        <label>irsaliye</label>
                        <input class="form-control" placeholder="irsaliye" name="irsaliye"  value="<?php echo $item->irsaliye; ?>">                        
                    </div>
                    <div class="form-group">
                        <label>tarih</label>
                        <input class="form-control" placeholder="tarih" name="tarih" value="<?php echo $item->tarih; ?>">                        
                    </div>
                    <div class="form-group">
                        <label>Açıklama</label>
                        <textarea name="aciklama" class="m-0" data-plugin="summernote" data-options="{height: 250}"><?php echo $item->aciklama; ?></textarea>
                    </div>                 
                    <button type="submit" class="btn btn-primary btn-md btn-outline">Güncelle</button>
                    <a href="<?php echo base_url("news"); ?>" class="btn btn-md btn-danger btn-outline">İptal</a>
                </form>
            </div><!-- .widget-body -->
        </div><!-- .widget -->
    </div><!-- END column -->
</div>