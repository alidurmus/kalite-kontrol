    <div class="col-md-12">
        <form action="<?php echo base_url("anasayfa/girdikontrol_guncelle/$item->id"); ?>" method="post" enctype="multipart/form-data">
            <table class="table table-striped table-sm"> 
                <tbody>  
                    <tr>
                        <td colspan="4"> 
                            <h4>  Girdi Kontrol Formu Düzenleme </h4>
                        </td>                       
                        <td></td>
                        <td colspan="2">Kontrol Nr:</td>
                        <td colspan="2">
                            <div class="input-group"> 
                                <?php echo "$item->kontrol_no " ?>
                            </div>
                        </td>
                        <td> </td>
                        <td> </td>
                    </tr>                   
                    <tr>
                        <td colspan="2">Malzeme  Adı:</td>
                        <td colspan="2">
                            <div class="input-group">
                                <input readonly type="text" name="malzeme_adi"  class="form-control input-sm" value="<?php echo "$malzeme->adi " ?>" placeholder="malzeme"> 
                            </div>                            
                        </td>
                        <td></td>
                        <td colspan="2">Parti Nr:</td>
                        <td colspan="2">
                            <div class="input-group"> 
                            <input  type="text" class="form-control input-sm" required name="parti_no"  value="<?php echo "$item->parti_no " ?>" placeholder="0000">                             </div>
                        </td>
                        <td> </td>
                        <td> </td>
                    </tr>                    
                    <tr>
                        <td colspan="2">Tedarikçi Adı:	</td>
                        <td colspan="2">
                            <div class="form-group">  
                                <select name="tedarikci" class="form-control">                               
                                    <?php foreach($tedarikciler as $tedarikci) { ?>                                       
                                        <option value="<?php echo $tedarikci->id; ?>"><?php echo $tedarikci->adi; ?></option>
                                    <?php } ?>
                                </select>      
                            </div><!-- .form-group -->                            
                        </td>
                        <td>-</td>
                        <td colspan="2">İrsaliye No</td>
                        <td colspan="2">
                            <div class="input-group"> 
                                <input  type="text" class="form-control input-sm" required name="irsaliye" value="<?php echo "$item->irsaliye " ?>" placeholder="0000"> 
                            </div>
                        </td>
                        <td></td>
                        <td></td>
                    </tr>     
                    <tr>
                        <td colspan="2">	</td>
                        <td colspan="2">
                                                  
                        </td>
                        <td></td>
                        <td colspan="2">Hammadde Parti Kodu</td>
                        <td colspan="2">
                            <div class="input-group"> 
                                <input  type="text" class="form-control input-sm" required name="hpk" value="<?php echo "$item->hpk " ?>" placeholder="0000"> 
                            </div>
                        </td>
                        <td></td>
                        <td></td>
                    </tr>                                
                    <!-- olcum alanı ekleme işlemi -->
                    <?php $this->load->view("includes/olcum_duzenle"); ?>
                </tbody>
            </table>
            <input  type="hidden" name="malzeme" class="form-control input-sm" value="<?php echo "$malzeme->id " ?>" > 
            <input  type="hidden" name="tarih" class="form-control input-sm"   value="<?php echo date("Y-m-d H:i:s"); ?>"/>
            <input   type="hidden" name="kullanici" class="form-control input-sm"  value="<?php echo $user->id ?>" />
            <button type="submit" class="btn btn-primary btn-md btn-outline">Kaydet</button>
            <a href="<?php echo base_url("anasayfa/girdikontrol"); ?>" class="btn btn-md btn-danger btn-outline">İptal</a>
            <button type="button" class="btn btn-info btn-md" data-toggle="modal" data-target="#myModal">Teknik Resim</button>
        </form> 

        
        <!-- Modal -->
        <div id="myModal" class="modal fade" role="dialog">
            <div class="modal-dialog modal-lg">

                <!-- Modal content-->
                <div class="modal-content">
                    
                    <div class="modal-body">

                        <embed src="<?php echo base_url("uploads/pdf"); ?>/<?php echo "$malzeme->pdf " ?>" type="application/pdf"  frameborder="0" width="100%" height="800px">

                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Kapat</button>
                        </div>
                    </div>

                </div>
            </div>
        </div>


    </div><!-- END column -->
</div>