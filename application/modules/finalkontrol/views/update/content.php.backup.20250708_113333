<div class="row">
    <div class="col-md-12">
        <h4 class="m-b-lg">
            <?php echo "<b>$item->id</b> kaydını düzenliyorsunuz"; ?>
        </h4>
    </div><!-- END column -->
    <div class="col-md-12">
        <div class="widget">
            <div class="widget-body">
                <form action="<?php echo base_url("finalkontrol/update/$item->id"); ?>" method="post" enctype="multipart/form-data">
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
                        <table class="table table-striped table-sm"> 
                            <tbody>                    
                                <tr>
                                    <td colspan="1">Ürün  Adı:</td>
                                    <td colspan="3">
                                        <div class="input-group">
                                            <input readonly type="text" name="urun_adi" class="form-control input-sm" value="<?php echo "$urun->adi " ?>" placeholder="urun"> 
                                        </div>                            
                                    </td>
                                    <td></td>
                                    <td colspan="2">Kutu Nr:</td>
                                    <td colspan="2">
                                        <div class="input-group"> 
                                        <input  type="text" class="form-control input-sm"  name="kutu_no"  value="<?php echo "$item->kutu_no " ?>" placeholder="0000">                             </div>
                                    </td>
                                    <td> </td>
                                    <td> </td>
                                </tr>                    
                                <tr>
                                    <td colspan="2"> :	</td>
                                    <td colspan="2">
                                        <div class="form-group">  
                                                
                                        </div><!-- .form-group -->                            
                                    </td>
                                    <td>-</td>
                                    <td colspan="2">Lot No</td>
                                    <td colspan="2">
                                        <div class="input-group"> 
                                            <input  type="text" class="form-control input-sm"  name="lot" value="<?php echo "$item->lot " ?>" placeholder="0000"> 
                                        </div>
                                    </td>
                                    <td></td>
                                    <td></td>
                                </tr>                                 
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
                                    <td>Uygun Değil</td>
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
                                                        <?php if($key == "olcum" || $key == "sonuc" ){ ?>  
                                                            <input  type="text" class="form-control input-sm" value="<?php echo $val; ?>" name="form[olcum][<?php echo $row; ?>][<?php echo $key; ?>]" > 
                                                        <?php }else { ?>
                                                            <input readonly type="text" class="form-control input-sm" value="<?php echo $val; ?>" name="form[olcum][<?php echo $row; ?>][<?php echo $key; ?>]" > 
                                                        <?php } ?>    
                                                    </div>
                                                </td>
                                        <?php } ?>
                                    <?php } ?>
                                </tr> 
                                        
                                <?php } ?>
                                <tr>
                                    <td colspan="2">Açıklama:</td>
                                    <td colspan="9">
                                        <div class="form-group">
                                            <textarea class="form-control" name="aciklama" rows="2" id="comment"><?php echo "$item->aciklama " ?></textarea>
                                        
                                    </td>
                                </tr>
                                <tr>                                  
                                    <td>Tarih:</td>
                                    <td colspan="2">
                                    <?php echo "$item->tarih " ?>
                                    </td>
                                    <td colspan="1">Sonuç:</td>
                                    <td colspan="2">
                                        <div class="input-group"> 
                                            <select name="sonuc" class="form-control">                               
                                                    <?php foreach($sonuc as $sonuc_tek) { ?>
                                                        <?php $sonuc_id = isset($form_error) ? set_value("sonuc_tek") : $item->sonuc; ?>
                                                        <option
                                                            <?php echo ($sonuc_tek->id === $sonuc_id) ? "selected" : ""; ?>
                                                            value="<?php echo $sonuc_tek->id; ?>"><?php echo $sonuc_tek->adi; ?></option>
                                                    <?php } ?>
                                            </select>
                                            <select class="form-control news_type_select" name="sonuc"> 

                                                <option value="1">Geçti</option>
                                                <option value="2">Şartlı Geçti</option>
                                                <option value="3">Kaldı</option>                                                                
                                            </select>
                                        </div>
                                    </td>
                                    <td colspan="3"></td>
                                    <td colspan="3">
                                    
                                    </td>
                                </tr>
                            </tbody>
                        </table>
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