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
                        <label>Kontrol No</label>
                        <input readonly="readonly" class="form-control" placeholder="kontrol_no" name="kontrol_no" value="<?php echo $item->kontrol_no; ?>">
                        <?php if(isset($form_error)){ ?>
                            <small class="pull-right input-form-error"> <?php echo form_error("kontrol_no"); ?></small>
                        <?php } ?>
                    </div>
                    
                    
                    <div class="form-group">
                        <label>Parti No</label>
                        <input class="form-control" placeholder="parti_no" name="parti_no" value="<?php echo $item->parti_no; ?>">
                        <?php if(isset($form_error)){ ?>
                            <small class="pull-right input-form-error"> <?php echo form_error("parti_no"); ?></small>
                        <?php } ?>
                    </div>
                    <div class="form-group">
                        <label for="control-demo-6" class="">Tedarikçiler</label>
                        <div id="control-demo-6" class="">                               
                            <select name="tedarikci" class="form-control">                               
                                    <?php foreach($tedarikciler as $tedarikci) { ?>
                                        <?php $tedarikci_id = isset($form_error) ? set_value("tedarikci") : $item->tedarikci; ?>
                                        <option
                                            <?php echo ($tedarikci->id === $tedarikci_id) ? "selected" : ""; ?>
                                            value="<?php echo $tedarikci->id; ?>"><?php echo $tedarikci->adi; ?></option>
                                    <?php } ?>
                            </select>
                                <?php if(isset($form_error)){ ?>
                                    <small class="pull-right input-form-error"> <?php echo form_error("tedarikci"); ?></small>
                                <?php } ?>
                                                
                        </div>
                    </div><!-- .form-group -->
                    <div class="form-group">
                        <label for="control-demo-6" class="">Malzemeler</label>
                        <div id="control-demo-6" class=""> 
                            <select name="malzeme" class="form-control">                               
                                    <?php foreach($malzemeler as $malzeme) { ?>
                                        <?php $malzeme_id = isset($form_error) ? set_value("malzeme") : $item->malzeme; ?>
                                        <option
                                            <?php echo ($malzeme->id === $malzeme_id) ? "selected" : ""; ?>
                                            value="<?php echo $malzeme->id; ?>"><?php echo $malzeme->adi; ?></option>
                                    <?php } ?>
                             </select>

                                <?php if(isset($form_error)){ ?>
                                    <small class="pull-right input-form-error"> <?php echo form_error("malzeme"); ?></small>
                                <?php } ?>                               
                                                     
                        </div>
                    </div><!-- .form-group -->
                    <div class="form-group">
                        <label for="control-demo-6" class="">Kullanicilar</label>
                        <div id="control-demo-6" class=""> 
                            <select name="kullanici" class="form-control">                               
                                    <?php foreach($kullanicilar as $kullanici) { ?>
                                        <?php $kullanici_id = isset($form_error) ? set_value("kullanici") : $item->kullanici; ?>
                                        <option
                                            <?php echo ($kullanici->id === $kullanici_id) ? "selected" : ""; ?>
                                            value="<?php echo $kullanici->id; ?>"><?php echo $kullanici->adi; ?></option>
                                    <?php } ?>
                             </select>

                                <?php if(isset($form_error)){ ?>
                                    <small class="pull-right input-form-error"> <?php echo form_error("kullanici"); ?></small>
                                <?php } ?>                               
                                                     
                        </div>
                    </div><!-- .form-group -->
                    <div class="form-group">
                        <label>irsaliye</label>
                        <input class="form-control" placeholder="irsaliye" name="irsaliye"  value="<?php echo $item->irsaliye; ?>">                        
                    </div>
                    <div class="form-group">
                        <label>Tarih</label>
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