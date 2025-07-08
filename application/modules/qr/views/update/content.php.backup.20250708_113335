<div class="row">
    <div class="col-md-12">
        <h4 class="m-b-lg">
            <?php echo "<b>$item->id</b> kaydını düzenliyorsunuz"; ?>
        </h4>
    </div><!-- END column -->
    <div class="col-md-12">
        <div class="widget">
            <div class="widget-body">
                <form action="<?php echo base_url("urunler/update/$item->id"); ?>" method="post" enctype="multipart/form-data">
                <div class="form-group">
                        <label>Adı</label>
                        <input class="form-control" placeholder="Adı" name="adi" value="<?php echo $item->adi; ?>">
                        <?php if(isset($form_error)){ ?>
                            <small class="pull-right input-form-error"> <?php echo form_error("adi"); ?></small>
                        <?php } ?>
                    </div>
                    <div class="form-group">
                        <label>kodu No</label>
                        <input class="form-control" placeholder="kodu" name="kodu" value="<?php echo $item->kodu; ?>">
                        <?php if(isset($form_error)){ ?>
                            <small class="pull-right input-form-error"> <?php echo form_error("kodu"); ?></small>
                        <?php } ?>
                    </div>                   
                    <div class="form-group">
                        <table class="table table-striped table-sm"> 
                            <tbody>                          
                                <tr>
                                    <td colspan="6"> Metrik Kontroller </td>
                                    <td colspan="5">Görsel Kontroller</td>
                                </tr>                                 
                                <tr>
                                    <td>Ölçüm Adı</td>
                                    <td>Ölçü (mm)</td>
                                    <td>Tolerans	</td>
                                    <td>Alt Limit	</td>
                                    <td>Üst Üst Limit	</td>
                                    <td>Ölçüm</td>
                                    <td>Sonuç</td>
                                    <td>Kontrol Noktası</td>
                                    <td>Uygun</td>
                                    <td>Şartlı Kabul	</td>
                                    <td>Uygun değil</td>
                                </tr> 
                                <?php foreach($json->{'olcum'} as $row => $d) {?>
                                    <tr> 
                                    <?php foreach($d as $key => $val) {?>
                                        <?php // echo $row . ' - ' .$key . ': ' . $val; ?>                                      
                                        <?php if($key == "gorsel"){ ?>                                            
                                        <td>
                                            <div class="input-group"> 
                                                <input  type="radio" value="1" <?php echo ($val == '1') ? 'checked' : ''; ?> name="form[olcum][<?php echo $row; ?>][<?php echo $key; ?>]" placeholder="00"> 
                                            </div>
                                        </td>
                                        <td>
                                            <div class="input-group"> 
                                                <input  type="radio" value="2" <?php echo ($val == '2') ? 'checked' : ''; ?> name="form[olcum][<?php echo $row; ?>][<?php echo $key; ?>]" placeholder="00"> 
                                            </div>
                                        </td> 
                                        <td>
                                            <div class="input-group"> 
                                                <input  type="radio" value="3" <?php echo ($val == '3') ? 'checked' : ''; ?> name="form[olcum][<?php echo $row; ?>][<?php echo $key; ?>]" placeholder="00"> 
                                            </div>
                                        </td>
                                        <?php }else { ?>
                                            <td>
                                                    <div class="input-group"> 
                                                            <input  type="text" class="form-control input-sm" value="<?php echo $val; ?>" name="form[olcum][<?php echo $row; ?>][<?php echo $key; ?>]" placeholder="Username"> 
                                                    </div>
                                                </td>
                                        <?php } ?>
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
                    <a href="<?php echo base_url("urunler"); ?>" class="btn btn-md btn-danger btn-outline">İptal</a>
                </form>
            </div><!-- .widget-body -->
        </div><!-- .widget -->
    </div><!-- END column -->
</div>