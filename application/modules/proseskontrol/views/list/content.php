<div class="container">
    <div class="row">
        <div class="col-md-12">
            <hr>
            <div class="row">
                <div class="col-md-7">
                    <a href="<?php 
// Load security helper for XSS protection
$this->load->helper('security');
echo base_url("anasayfa/kalite"); ?>" class="btn btn-primary  btn-lg"><-- Kalite</a>

                            <a href="<?php echo base_url("dashboard"); ?>" class="btn btn-warning  btn-lg"><-- Yönetim</a>
                                    <a href="<?php echo base_url("excel/proses_kontrol"); ?>" class="btn btn-danger  btn-lg">Excel</a>

                                    <a href="<?php echo base_url("anasayfa/urun"); ?>" class="btn btn-success  btn-lg">Yeni Ekle --></a>
                </div>
                <div class="col-md-5 ">
                    <a href="#" class="btn btn-success  btn-lg  btn-block"><STRong>PROSES KONTROL</STRong></a>
                </div>
            </div>
            <hr>
        </div>
        <div class="col-md-12">
            <h4 class="m-b-lg">
                Proses Kontrol Listesi
                <?php if(isAllowedWriteModule()){ ?>
                    <a href="<?php echo base_url("proseskontrol/new_form"); ?>" class="btn btn-outline btn-primary btn-xs pull-right"> <i class="fa fa-plus"></i> Yeni Ekle</a>
                <?php } ?>
            </h4>
        </div><!-- END column -->
        <div class="col-md-12">
            <div class="widget">
                <div class="widget-body">
                    <!-- Search Form -->
                    <form action="<?php echo base_url("proseskontrol/index"); ?>" method="get" class="mb-3">
                        <div class="row">
                            <div class="form-group col-md-3">
                                <label for="search_urun">Ürün Adı</label>
                                <input type="text" name="search_urun" class="form-control" placeholder="Ürün adı..." value="<?php echo safe_attr($this->input->get('search_urun')); ?>">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="search_lot">Lot No</label>
                                <input type="text" name="search_lot" class="form-control" placeholder="Lot no..." value="<?php echo safe_attr($this->input->get('search_lot')); ?>">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="search_parti_no">Parti No</label>
                                <input type="text" name="search_parti_no" class="form-control" placeholder="Parti no..." value="<?php echo safe_attr($this->input->get('search_parti_no')); ?>">
                            </div>
                            <div class="form-group col-md-3 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary mr-2">Ara</button>
                                <a href="<?php echo base_url("proseskontrol"); ?>" class="btn btn-outline-secondary">Temizle</a>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table id="dataTable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>HPK</th>
                                    <th>Ürün Adı</th>
                                    <th>Lot</th>
                                    <th>Kontrol No</th>
                                    <th>Parti No</th>
                                    <th>Tarih</th>
                                    <th>İşlemler</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($items as $item) { ?>
                                    <tr>
                                        <td><?php echo safe_output($item->hpk); ?></td>
                                        <td><?php echo safe_output($item->urun_adi); ?></td>
                                        <td><?php echo safe_output($item->lot); ?></td>
                                        <td><?php echo safe_output($item->kontrol_no); ?></td>
                                        <td><?php echo safe_output($item->parti_no); ?></td>
                                        <td><?php echo safe_output($item->tarih); ?></td>
                                        <td class="text-center">
                                            <?php if(isAllowedViewModule()){ ?>
                                                <a href="<?php echo base_url("anasayfa/proseskontrol_duzenle/".safe_attr($item->id)); ?>" class="btn btn-xs btn-info btn-outline"><i class="fa fa-pencil"></i> Düzenle</a>
                                            <?php } ?>
                                            <?php if(isAllowedDeleteModule()){ ?>
                                                <a href="javascript:void(0)" data-url="<?php echo base_url("proseskontrol/delete/".safe_attr($item->id)); ?>" class="btn btn-xs btn-danger btn-outline remove-btn"><i class="fa fa-trash"></i> Sil</a>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <?php if(isset($links) && !empty($links)) { ?>
                        <div class="row mt-3">
                            <div class="col-12 text-center">
                                <?php echo safe_output($links); ?>
                            </div>
                        </div>
                    <?php } ?>
                </div><!-- .widget-body -->
            </div><!-- .widget -->
        </div><!-- END column -->
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#dataTable').DataTable();
    });
</script>