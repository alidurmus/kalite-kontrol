<div class="container-fluid">

<div class="jumbotron text-center">
  <h1>Ürün Seç  </h1>
  <p></p> 
</div>
  
<div class="container">
  <div class="row">
    <div class="col-sm-4">      
    </div>
    <div class="col-sm-4">
    <form action="<?php 
// Load security helper for XSS protection
$this->load->helper('security');
echo base_url("anasayfa/finalkontrol_ekle"); ?>" method="post" enctype="multipart/form-data">
        
        <!-- CSRF Token -->
        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
        
        <div class="form-group">
            <label for="control-demo-6" class="">Ürün </label>
            <div id="control-demo-6" class="">
                <select class="form-control news_type_select" name="urun" required>
                    <option value="">-- Ürün Seçiniz --</option>  
                    <?php foreach($urunler as $urun) { ?>
                        <option value="<?php echo safe_attr($urun->id); ?>"><?php echo safe_output($urun->adi); ?></option>
                    <?php } ?>                              
                </select>
            </div>
        </div><!-- .form-group -->
        <button type="submit" class="btn btn-primary btn-md btn-outline">Seç</button>
        <a href="<?php echo base_url("finalkontrol"); ?>" class="btn btn-md btn-danger btn-outline">İptal</a>
    </form>
     
    </div>
    <div class="col-sm-4">
    
    </div>
  </div>
</div>
    
</div>

