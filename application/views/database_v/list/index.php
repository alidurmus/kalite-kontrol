<div class="row">
    <div class="col-md-12">
        <h4 class="m-b-lg">
            Veritabanı Yönetimi
        </h4>
    </div><!-- END column -->
    <div class="col-md-12">
        <div class="widget">
            <div class="widget-body">
                <div class="row">
                    <!-- Yedekleme -->
                    <div class="col-md-6">
                        <div class="panel panel-primary text-center">
                            <div class="panel-heading">
                                <h3 class="panel-title">Veritabanı Yedekleme</h3>
                            </div>
                            <div class="panel-body">
                                <p>Tüm veritabanının yedeğini alın. Bu işlem, tüm tabloları ve verileri içeren bir <code>.zip</code> dosyası oluşturur ve bilgisayarınıza indirir.</p>
                                <a href="<?php echo base_url("database/backup"); ?>" class="btn btn-primary btn-md"><i class="fa fa-database"></i> Yedeği İndir</a>
                            </div>
                        </div>
                    </div>

                    <!-- Bakım -->
                    <div class="col-md-6">
                        <div class="panel panel-danger text-center">
                            <div class="panel-heading">
                                <h3 class="panel-title">Veritabanı Bakımı</h3>
                            </div>
                            <div class="panel-body">
                                <p>Eski kayıtları silerek veritabanı boyutunu azaltın. Bu işlem, her bir ana tabloda sadece en son <b>100</b> kaydı bırakır ve geri kalanını siler. <strong>Bu işlem geri alınamaz!</strong></p>
                                <a href="<?php echo base_url("database/reduce_data"); ?>" class="btn btn-danger btn-md remove-btn" target="_blank"><i class="fa fa-cogs"></i> Bakımı Başlat</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- .widget-body -->
        </div><!-- .widget -->
    </div><!-- END column -->
</div> 