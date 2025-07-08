<div class="row">
    <div class="col-md-12">
        <h4 class="m-b-lg">
            <?php echo "<b>$item->id</b> kaydını düzenliyorsunuz"; ?>
        </h4>
    </div><!-- END column -->
    <div class="col-md-12">
        <div class="widget">
            <div class="widget-body">
                <form action="<?php echo base_url("isemri/update/$item->id"); ?>" method="post" enctype="multipart/form-data">
                <div class="form-group col-md-6">
                        <label>lot</label>
                        <input class="form-control" placeholder="lot" name="lot" value="<?php echo $item->lot; ?>"> 
                        <?php if(isset($form_error)){ ?>
                            <small class="pull-right input-form-error"> <?php echo form_error("lot"); ?></small>
                        <?php } ?>                       
                    </div>
                    <div class="form-group col-md-6">
                        <label>musteri</label>
                        <input class="form-control" placeholder="musteri" name="musteri" value="<?php echo $item->musteri; ?>">                        
                        <?php if(isset($form_error)){ ?>
                            <small class="pull-right input-form-error"> <?php echo form_error("musteri"); ?></small>
                        <?php } ?>    
                    </div>
                    <div class="form-group col-md-6">
                        <label>siparis_no</label>
                        <input class="form-control" placeholder="siparis_no" name="siparis_no" value="<?php echo $item->siparis_no; ?>">                        
                        <?php if(isset($form_error)){ ?>
                            <small class="pull-right input-form-error"> <?php echo form_error("siparis_no"); ?></small>
                        <?php } ?>    
                    </div>
                    <div class="form-group col-md-6">
                        <label>uretim_tarihi</label>
                        <input type="date" class="form-control" placeholder="uretim_tarihi" name="uretim_tarihi" value="<?php echo $item->uretim_tarihi; ?>">                        
                        <?php if(isset($form_error)){ ?>
                            <small class="pull-right input-form-error"> <?php echo form_error("uretim_tarihi"); ?></small>
                        <?php } ?>    
                    </div>
                    <div class="form-group col-md-6">
                        <label>sevk_tarihi</label>
                        <input type="date" class="form-control" placeholder="sevk_tarihi" name="sevk_tarihi" value="<?php echo $item->sevk_tarihi; ?>">                        
                        <?php if(isset($form_error)){ ?>
                            <small class="pull-right input-form-error"> <?php echo form_error("sevk_tarihi"); ?></small>
                        <?php } ?>
                    </div>
                    <div class="form-group col-md-6">
                        <label>Tarih</label>
                        <input type="date" class="form-control" placeholder="tarih" name="tarih" value="<?php echo $item->tarih; ?>">                        
                        <?php if(isset($form_error)){ ?>
                            <small class="pull-right input-form-error"> <?php echo form_error("tarih"); ?></small>
                        <?php } ?>
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
                        <textarea name="aciklama" class="m-0" data-plugin="summernote" data-options="{height: 250}"><?php echo $item->aciklama; ?></textarea>
                    </div>                 
                    <button type="submit" class="btn btn-primary btn-md btn-outline">Güncelle</button>
                    <a href="<?php echo base_url("isemri"); ?>" class="btn btn-md btn-danger btn-outline">İptal</a>
                </form>

                
            </div><!-- .widget-body -->
        </div><!-- .widget -->
    </div><!-- END column -->
</div>