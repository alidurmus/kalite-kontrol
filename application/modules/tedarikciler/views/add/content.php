<div class="row">
    <div class="col-md-12">
        <h4 class="m-b-lg">
            Müşteriler Ekle
        </h4>
    </div><!-- END column -->
    <div class="col-md-12">
        <div class="widget">
            <div class="widget-body">
                <form action="<?php echo base_url("tedarikciler/save"); ?>" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Adı</label>
                        <input class="form-control" placeholder="adi" name="adi">                        
                    </div>
                    <div class="form-group">
                        <label>Kodu</label>
                        <input class="form-control" placeholder="kodu" name="kodu">                        
                    </div>
                    <div class="form-group">
                        <label>Açıklama</label>
                        <textarea name="aciklama" class="m-0" data-plugin="summernote" data-options="{height: 250}"></textarea>
                    </div> 
                    <button type="submit" class="btn btn-primary btn-md btn-outline">Kaydet</button>
                    <a href="<?php echo base_url("tedarikciler"); ?>" class="btn btn-md btn-danger btn-outline">İptal</a>
                </form>
            </div><!-- .widget-body -->
        </div><!-- .widget -->
    </div><!-- END column -->
</div>