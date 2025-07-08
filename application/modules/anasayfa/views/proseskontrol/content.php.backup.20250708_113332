<div class="container">
    <div class="row">
        <div class="col-md-12">
            <hr>
            <div class="row">
                <div class="col-md-7">
                    <a href="<?php echo base_url("anasayfa/kalite"); ?>" class="btn btn-primary  btn-lg"><-- Kalite</a>

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
            <table id="dataTable" class="table table-hover table-striped table-bordered content-container">
                <thead>
                    <th>urun</th>
                    <th>lot</th>
                    <th>Kontrol No</th>
                    <th>Parti_no</th>
                    <th>Tarih</th>
                    <th>İşlem</th>
                </thead>
                <tbody class="sortable" data-url="<?php echo base_url("proseskontrol/rankSetter"); ?>">
                    <?php foreach ($items as $item) { ?>
                        <tr>
                            <td><?php echo $item->urun_adi; ?></td>
                            <td><?php echo $item->lot; ?></td>
                            <td><?php echo $item->kontrol_no; ?></td>
                            <td><?php echo $item->parti_no; ?></td>
                            <td><?php echo tarih_ayarla($item->tarih, "Y/m/d H:i");  ?></td>
                            <td>
                                <a href="<?php echo base_url("anasayfa/proseskontrol_duzenle"); ?>/<?php echo $item->id; ?>" class="btn btn-info">Düzenle</a>
                                <button data-url="<?php echo base_url("anasayfa/proseskontrol_sil/$item->id"); ?>" class="btn btn-sm btn-danger btn-outline remove-btn">
                                    <i class="fa fa-trash"></i> Sil
                                </button>


                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#dataTable').DataTable();
    });
</script>