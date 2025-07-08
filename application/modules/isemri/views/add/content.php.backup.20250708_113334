<div class="row">
    <div class="col-md-12">
        <h4 class="m-b-lg">
            İş Emri Ekle
        </h4>
    </div><!-- END column -->
    <div class="col-md-12">
        <div class="widget">
            <div class="widget-body">
                <form action="<?php echo base_url("isemri/save"); ?>" method="post" enctype="multipart/form-data">
            
                    <div class="form-group col-md-6">
                        <label>lot</label>
                        <input class="form-control" placeholder="lot" name="lot">                        
                    </div>
                    <div class="form-group col-md-6">
                        <label>musteri</label>
                        <input class="form-control" placeholder="musteri" name="musteri">                        
                    </div>
                    <div class="form-group col-md-6">
                        <label>siparis_no</label>
                        <input class="form-control" placeholder="siparis_no" name="siparis_no">                        
                    </div>
                    <div class="form-group col-md-6">
                        <label>uretim_tarihi</label>
                        <input type="date" class="form-control" placeholder="uretim_tarihi" name="uretim_tarihi">                        
                    </div>
                    <div class="form-group col-md-6">
                        <label>sevk_tarihi</label>
                        <input type="date" class="form-control" placeholder="sevk_tarihi" name="sevk_tarihi">                        
                    </div>
                    <div class="form-group col-md-6">
                        <label>Tarih</label>
                        <input type="date" class="form-control" placeholder="tarih" name="tarih">                        
                    </div>
                    <div class="form-group">
                        <table class="table table-striped table-sm"> 
                            <tbody>                          
                                <tr>
                                    <td colspan="2"> Ürün  Bilgileri </td>                                   
                                </tr>                                 
                                <tr>
                                    <td>Ürün Adı</td>
                                    <td>Adet</td>                                    
                                </tr> 
                                <?php foreach($json->{'isemri'} as $row => $d) {?>
                                    <tr> 
                                    <?php foreach($d as $key => $val) {?>
                                        <?php // echo $row . ' - ' .$key . ': ' . $val; ?>   
                                            <td>
                                                <div class="input-group"> 
                                                    <input  type="text" class="form-control input-sm" value="<?php echo $val; ?>" name="form[isemri][<?php echo $row; ?>][<?php echo $key; ?>]" > 
                                                </div>
                                            </td>                                        
                                    <?php } ?>
                                    </tr> 
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="form-group">
                        <label>Açıklama</label>
                        <textarea name="aciklama" class="m-0" data-plugin="summernote" data-options="{height: 250}"></textarea>
                    </div> 
                    <button type="submit" class="btn btn-primary btn-md btn-outline">Kaydet</button>
                    <a href="<?php echo base_url("isemri"); ?>" class="btn btn-md btn-danger btn-outline">İptal</a>
                </form>
            </div><!-- .widget-body -->
        </div><!-- .widget -->
    </div><!-- END column -->
</div>