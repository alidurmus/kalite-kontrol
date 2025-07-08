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
    <form action="<?php echo base_url("anasayfa/finalkontrol_ekle"); ?>" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="control-demo-6" class="">urun </label>
            <div id="control-demo-6" class="">
                <select class="form-control news_type_select" name="urun">  
                    <?php foreach($urunler as $urun) { ?>
                        <option value="<?php echo $urun->id; ?>"><?php echo $urun->adi; ?></option>
                    <?php } ?>                              
                </select>
            </div>
        </div><!-- .form-group -->
        <button type="submit" class="btn btn-primary btn-md btn-outline">Seç</button>
        <a href="<?php echo base_url("anasayfa/proseskontrol"); ?>" class="btn btn-md btn-danger btn-outline">iptal</a>
    </form>
     
    </div>
    <div class="col-sm-4">
    
    </div>
  </div>
</div>
    
</div>

