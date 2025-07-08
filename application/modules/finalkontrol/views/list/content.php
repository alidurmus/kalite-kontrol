<div class="container">
    <div class="row">
        <div class="col-md-12">
            <hr>
            <div class="row">
                <div class="col-md-7">
                    <a href="<?php 
// Load security helper for XSS protection
$this->load->helper('security');
echo base_url("anasayfa/kalite"); ?>" class="btn btn-primary   btn-lg"><-- Kalite</a>

                            <a href="<?php echo base_url("dashboard"); ?>" class="btn btn-warning   btn-lg"><-- Yönetim</a>

                                    <a href="<?php echo base_url("excel/final_kontrol"); ?>" class="btn btn-danger  btn-lg">Excel</a>

                                    <a href="<?php echo base_url("anasayfa/urun2"); ?>" class="btn btn-success btn-lg">Yeni Ekle --></a>
                </div>

                <div class="col-md-5">
                    <a href="#" class="btn btn-danger  btn-lg  btn-block"><STRong>FİNAL KONTROL</STRong></a>
                </div>
            </div>
            <hr>
        </div>
        <div class="col-md-12">
            <h4 class="m-b-lg">
                Final Kontrol Listesi
                <a href="<?php echo base_url("anasayfa/urun2"); ?>" class="btn btn-outline btn-primary btn-sm pull-right"> <i class="fa fa-plus"></i> Yeni Ekle</a>
            </h4>
        </div>
        <div class="col-md-12">
            <div class="widget">
                <div class="widget-body">
                    <form action="<?php echo base_url("finalkontrol/index"); ?>" method="get">
                        <div class="row">
                            <div class="form-group col-md-3">
                                <label for="search_urun">Ürün Adı</label>
                                <input type="text" name="search_urun" class="form-control" placeholder="Ürün adı..." value="<?php echo safe_attr($search_urun); ?>">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="search_kutu">Kutu Numarası</label>
                                <input type="text" name="search_kutu" class="form-control" placeholder="Kutu no..." value="<?php echo safe_attr($search_kutu); ?>">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="search_lot">Lot Numarası</label>
                                <input type="text" name="search_lot" class="form-control" placeholder="Lot no..." value="<?php echo safe_attr($search_lot); ?>">
                            </div>
                            <div class="form-group col-md-3 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary mr-2">Ara</button>
                                <a href="<?php echo base_url("finalkontrol"); ?>" class="btn btn-outline-secondary">Temizle</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="widget p-lg">
                <table id="dataTablex" class="table table-hover table-striped table-bordered content-container">
                    <thead>
                        <th>id</th>
                        <th>Ürün Adı</th>
                        <th>Kontrol No</th>
                        <th>Kutu No</th>
                        <th>Lot</th>
                        <th>Tarih</th>
                        <th>İşlem</th>
                    </thead>
                    <tbody class="sortable" data-url="<?php echo base_url("finalkontrol/rankSetter"); ?>">
                        <?php foreach ($items as $item) { ?>
                            <tr>
                                <td><?php echo safe_output($item->id); ?></td>
                                <td><?php echo safe_output($item->urun_adi); ?></td>
                                <td><?php echo safe_output($item->kontrol_no); ?></td>
                                <td><?php echo safe_output($item->kutu_no); ?></td>
                                <td><?php echo safe_output($item->lot); ?></td>
                                <td><?php echo tarih_ayarla($item->tarih, "Y/m/d H:i");  ?></td>
                                <td>
                                    <a href="<?php echo base_url("anasayfa/finalkontrol_duzenle"); ?>/<?php echo safe_output($item->id); ?>" class="btn btn-info">Düzenle</a>
                                    <button data-url="<?php echo base_url("anasayfa/finalkontrol_sil/$item->id"); ?>" class="btn btn-sm btn-danger btn-outline remove-btn">
                                        <i class="fa fa-trash"></i> Sil
                                    </button>
                                    <!--<a href="<?php echo base_url("etiket/final_kontrol"); ?>/<?php echo safe_output($item->id); ?>" class="btn btn-warning">Etiket</a> -->
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <p><?php echo safe_output($links); ?></p>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#dataTable').DataTable();
    });
</script>