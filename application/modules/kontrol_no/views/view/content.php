<!-- Kontrol Numarası Detay Görüntüleme -->
<div class="container-fluid">
    
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1">
                        <i class="zmdi zmdi-assignment"></i> 
                        Kontrol Kaydı #<?php 
// Load security helper for XSS protection
$this->load->helper('security');
echo str_pad($item->id, 4, '0', STR_PAD_LEFT); ?>
                    </h2>
                    <p class="text-muted mb-0">Kontrol kaydı detay bilgileri</p>
                </div>
                <div>
                    <a href="<?php echo base_url('kontrol_no'); ?>" class="btn btn-outline-secondary mr-2">
                        <i class="zmdi zmdi-arrow-left"></i> Geri Dön
                    </a>
                    <?php if(isAllowedWriteModule()){ ?>
                        <a href="<?php echo base_url('kontrol_no/update_form/' . $item->id); ?>" class="btn btn-primary">
                            <i class="zmdi zmdi-edit"></i> Düzenle
                        </a>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Cards -->
    <div class="row">
        
        <!-- Main Information Card -->
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="zmdi zmdi-info"></i> Kontrol Bilgileri
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold text-muted">Kontrol Numarası</label>
                                <div class="mt-1">
                                    <span class="badge badge-primary badge-lg">
                                        #<?php echo str_pad($item->id, 4, '0', STR_PAD_LEFT); ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold text-muted">Proses Türü</label>
                                <div class="mt-1">
                                    <?php 
                                    $process_badges = [
                                        'girdikontrol' => 'badge-info',
                                        'proseskontrol' => 'badge-success', 
                                        'finalkontrol' => 'badge-danger'
                                    ];
                                    $process_names = [
                                        'girdikontrol' => 'Girdi Kontrol',
                                        'proseskontrol' => 'Proses Kontrol',
                                        'finalkontrol' => 'Final Kontrol'
                                    ];
                                    $badge_class = isset($process_badges[$item->process_isim]) ? $process_badges[$item->process_isim] : 'badge-secondary';
                                    $process_name = isset($process_names[$item->process_isim]) ? $process_names[$item->process_isim] : $item->process_isim;
                                    ?>
                                    <span class="badge <?php echo safe_output($badge_class); ?> badge-lg">
                                        <?php echo safe_output($process_name); ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold text-muted">Parti Numarası</label>
                                <div class="mt-1">
                                    <h5 class="mb-0 text-dark"><?php echo htmlspecialchars($item->parti_no); ?></h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold text-muted">Lot Numarası</label>
                                <div class="mt-1">
                                    <h5 class="mb-0 text-dark"><?php echo htmlspecialchars($item->lot_no ?: 'Belirtilmemiş'); ?></h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold text-muted">Kutu Numarası</label>
                                <div class="mt-1">
                                    <h5 class="mb-0 text-dark"><?php echo htmlspecialchars($item->kutu_no ?: 'Belirtilmemiş'); ?></h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold text-muted">Kayıt Tarihi</label>
                                <div class="mt-1">
                                    <h5 class="mb-0 text-dark">
                                        <i class="zmdi zmdi-calendar mr-1"></i>
                                        <?php echo date('d.m.Y H:i', strtotime($item->tarih)); ?>
                                    </h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status and Quick Actions -->
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="zmdi zmdi-settings"></i> Durum ve İşlemler
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Status -->
                    <div class="mb-4">
                        <label class="font-weight-bold text-muted d-block mb-2">Kayıt Durumu</label>
                        <?php 
                        $is_recent = (strtotime($item->tarih) > strtotime('-24 hours'));
                        if($is_recent) {
                            echo '<div class="alert alert-success text-center">';
                            echo '<i class="zmdi zmdi-check-circle zmdi-hc-2x mb-2"></i>';
                            echo '<h6 class="mb-0">Aktif Kayıt</h6>';
                            echo '<small>24 saat içinde oluşturuldu</small>';
                            echo '</div>';
                        } else {
                            echo '<div class="alert alert-secondary text-center">';
                            echo '<i class="zmdi zmdi-time zmdi-hc-2x mb-2"></i>';
                            echo '<h6 class="mb-0">Eski Kayıt</h6>';
                            echo '<small>24 saatten fazla süre geçmiş</small>';
                            echo '</div>';
                        }
                        ?>
                    </div>
                    
                    <!-- Quick Actions -->
                    <div class="mb-3">
                        <label class="font-weight-bold text-muted d-block mb-2">Hızlı İşlemler</label>
                        <div class="btn-group-vertical btn-block">
                            <?php if(isAllowedWriteModule()){ ?>
                                <a href="<?php echo base_url('kontrol_no/update_form/' . $item->id); ?>" class="btn btn-outline-primary mb-2">
                                    <i class="zmdi zmdi-edit"></i> Düzenle
                                </a>
                            <?php } ?>
                            <button class="btn btn-outline-info mb-2" onclick="printRecord()">
                                <i class="zmdi zmdi-print"></i> Yazdır
                            </button>
                            <button class="btn btn-outline-success mb-2" onclick="exportRecord()">
                                <i class="zmdi zmdi-download"></i> Dışa Aktar
                            </button>
                            <?php if(isAllowedDeleteModule()){ ?>
                                <button class="btn btn-outline-danger" onclick="deleteRecord()">
                                    <i class="zmdi zmdi-delete"></i> Sil
                                </button>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <div class="row mt-4">
        <div class="col-12 text-center">
            <a href="<?php echo base_url('kontrol_no'); ?>" class="btn btn-outline-secondary mr-2">
                <i class="zmdi zmdi-arrow-left"></i> Kontrol Listesine Dön
            </a>
            <a href="<?php echo base_url('dashboard'); ?>" class="btn btn-outline-primary">
                <i class="zmdi zmdi-view-dashboard"></i> Dashboard'a Git
            </a>
        </div>
    </div>

</div>

<!-- Custom Styles -->
<style>
.badge-lg {
    padding: 8px 12px;
    font-size: 13px;
}
.card {
    border: none;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
}
.card-header {
    border-bottom: 1px solid #f0f0f0;
    background: #fafafa;
}
.form-group label {
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.alert {
    border: none;
    border-radius: 8px;
}
.btn-group-vertical .btn {
    border-radius: 6px !important;
}
</style>

<!-- JavaScript Functions -->
<script>
function printRecord() {
    window.print();
}

function exportRecord() {
    // Export single record
    window.location.href = '<?php echo base_url("kontrol_no/export_excel?id=" . $item->id); ?>';
}

function deleteRecord() {
    if(confirm('Bu kaydı silmek istediğinize emin misiniz?\n\nKontrol No: #<?php echo safe_output($item->id); ?>\nParti No: <?php echo htmlspecialchars($item->parti_no); ?>\n\nBu işlem geri alınamaz!')) {
        window.location.href = '<?php echo base_url("kontrol_no/delete/" . $item->id); ?>';
    }
}
</script> 