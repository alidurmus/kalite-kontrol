<div class="row">
    <div class="col-md-12">
        <h4 class="m-b-lg">
            Girdi Kontrol Formu Ekle
        </h4>
        
    </div><!-- END column -->
    <div class="col-md-12">
        <form id="kontrolForm" action="<?php echo base_url("anasayfa/girdikontrol_kaydet"); ?>" method="post" enctype="multipart/form-data">
            <table class="table table-striped table-sm"> 
                <tbody>                    
                    <tr>
                        <td colspan="2">Malzeme  Adı:</td>
                        <td colspan="2">
                            <div class="input-group">
                                <input readonly type="text"  name="malzeme_adi" required class="form-control input-sm required" value="<?php echo $malzeme->adi; ?>" placeholder="malzeme"> 
                            </div>                            
                        </td>
                        <td></td>
                        <td colspan="2">Parti Nr:</td>
                        <td colspan="2">
                            <div class="input-group"> 
                            <input  type="text" class="form-control input-sm required"  required  name="parti_no" value="0"   placeholder="0000">                             </div>
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
                                    <input  type="text" class="form-control input-sm  required"   required  name="irsaliye" value="0" placeholder="0000"> 
                            </div>
                        </td>
                        <td></td>
                        <td></td>
                    </tr>     
                    <tr>
                        <td colspan="2">	</td>
                        <td colspan="2">
                            <div class="form-group">  
                             
                            </div><!-- .form-group -->                            
                        </td>
                        <td>-</td>
                        <td colspan="2">Hammadde Parti Kodu</td>
                        <td colspan="2">
                            <div class="input-group"> 
                                <input  type="text" class="form-control input-sm  required"   required  name="hpk" value="0" placeholder="0000"> 
                            </div>
                        </td>
                        <td></td>
                        <td></td>
                    </tr>                             
                     <!-- olcum alanı ekleme işlemi -->
                     <?php $this->load->view("includes/olcum"); ?>
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
 
<?php



?>
    </div><!-- END column -->
</div>
<script>
   function check_required_inputs() {
        var valid =  Array.prototype.every.call($('.required'), function(input) {
            return input.value;
        });

        if (valid) {
            return true;
        } else {
            alert('Lütfen eksik alanları doldurunuz.');

            return false;
        }
    } 
    $("#kontrolForm").on('submit', function(e){
        e.preventDefault();
        if(check_required_inputs()){
            $('form#kontrolForm')[0].submit();
        }

    });
</script>