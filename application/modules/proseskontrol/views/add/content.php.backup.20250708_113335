<div class="row">
    <div class="col-md-12">
        <h4 class="m-b-lg">
            Proses Kontrol Ekle
        </h4>
    </div><!-- END column -->
    <div class="col-md-12">
        <div class="widget">
            <div class="widget-body">
                <form action="<?php echo base_url("proseskontrol/save"); ?>" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>parti_no</label>
                        <input class="form-control" placeholder="parti_no" name="parti_no">                        
                    </div>
                    <div class="form-group">
                        <label for="control-demo-6" class="">Tedarikci </label>
                        <div id="control-demo-6" class="">
                        <select name="tedarikci" class="form-control">                               
                                    <?php foreach($tedarikciler as $tedarikci) { ?>                                       
                                        <option value="<?php echo $tedarikci->id; ?>"><?php echo $tedarikci->adi; ?></option>
                                    <?php } ?>
                            </select>                          
                        </div>
                    </div><!-- .form-group -->
                    <div class="form-group">
                        <label for="control-demo-6" class="">Malzeme </label>
                        <div id="control-demo-6" class="">
                            <select class="form-control news_type_select" name="malzeme">  
                                <?php foreach($malzemeler as $malzeme) { ?>
                                    <option value="<?php echo $malzeme->id; ?>"><?php echo $malzeme->adi; ?></option>
                                <?php } ?>                              
                            </select>
                        </div>
                    </div><!-- .form-group -->
                    <div class="form-group">
                        <label for="control-demo-6" class="">Kullanici </label>
                        <div id="control-demo-6" class="">
                            <select class="form-control news_type_select" name="kullanici">  
                                <?php foreach($kullanicilar as $kullanici) { ?>
                                    <option value="<?php echo $kullanici->id; ?>"><?php echo $kullanici->adi; ?></option>
                                <?php } ?>                              
                            </select>
                        </div>
                    </div><!-- .form-group -->
                    <div class="form-group">
                        <label>irsaliye</label>
                        <input class="form-control" placeholder="irsaliye" name="irsaliye">                        
                    </div>
                    <div class="form-group">
                        <label>tarih</label>
                        <input class="form-control" placeholder="tarih" name="tarih" value="<?php echo date("Y-m-d H:i:s") ?>" >                        
                    </div>
                    <div class="form-group">
                        <label>Açıklama</label>
                        <textarea name="aciklama" class="m-0" data-plugin="summernote" data-options="{height: 250}"></textarea>
                    </div> 
                    <button type="submit" class="btn btn-primary btn-md btn-outline">Kaydet</button>
                    <a href="<?php echo base_url("news"); ?>" class="btn btn-md btn-danger btn-outline">İptal</a>
                </form>
            </div><!-- .widget-body -->
        </div><!-- .widget -->
    </div><!-- END column -->
</div>