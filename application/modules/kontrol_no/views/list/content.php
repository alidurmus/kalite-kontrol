<div class="row">
    <div class="col-md-12">
        <h4 class="m-b-lg">
            Kontrol No Listesi
            <?php if(isAllowedWriteModule()){ ?>
            <a href="<?php echo base_url("kontrol_no/new_form"); ?>" class="btn btn-outline btn-primary btn-xs pull-right"> 
                <i class="fa fa-plus"></i> Yeni Ekle
            </a>
            <?php } ?>
        </h4>
    </div>
</div>

<!-- İstatistik Kartları -->
<div class="row m-b-lg">
    <div class="col-md-3">
        <div class="widget stats-widget">
            <div class="widget-body clearfix">
                <div class="pull-left">
                    <h3 class="widget-title text-primary">
                        <span class="counter"><?php echo safe_output($today_count ?? 0); ?></span>
                    </h3>
                    <small class="text-color">Bugün</small>
                </div>
                <span class="pull-right big-icon text-primary">
                    <i class="fa fa-calendar"></i>
                </span>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="widget stats-widget">
            <div class="widget-body clearfix">
                <div class="pull-left">
                    <h3 class="widget-title text-success">
                        <span class="counter"><?php echo safe_output($girdi_count ?? 0); ?></span>
                    </h3>
                    <small class="text-color">Girdi Kontrol</small>
                </div>
                <span class="pull-right big-icon text-success">
                    <i class="fa fa-check-circle"></i>
                </span>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="widget stats-widget">
            <div class="widget-body clearfix">
                <div class="pull-left">
                    <h3 class="widget-title text-warning">
                        <span class="counter"><?php echo safe_output($week_count ?? 0); ?></span>
                    </h3>
                    <small class="text-color">Bu Hafta</small>
                </div>
                <span class="pull-right big-icon text-warning">
                    <i class="fa fa-line-chart"></i>
                </span>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="widget stats-widget">
            <div class="widget-body clearfix">
                <div class="pull-left">
                    <h3 class="widget-title text-info">
                        <span class="counter"><?php echo safe_output($total_rows ?? 0); ?></span>
                    </h3>
                    <small class="text-color">Toplam</small>
                </div>
                <span class="pull-right big-icon text-info">
                    <i class="fa fa-database"></i>
                </span>
            </div>
        </div>
    </div>
</div>

<!-- Filtreleme Formu -->
<div class="row">
    <div class="col-md-12">
        <div class="widget">
            <div class="widget-body">
                <form method="get" action="<?php echo base_url('kontrol_no'); ?>" class="form-inline">
                    <div class="form-group m-r-sm">
                        <label for="search_process">Proses:</label>
                        <select name="search_process" id="search_process" class="form-control">
                            <option value="">Tümü</option>
                            <option value="girdikontrol" <?php echo ($search_process == 'girdikontrol') ? 'selected' : ''; ?>>Girdi Kontrol</option>
                            <option value="proseskontrol" <?php echo ($search_process == 'proseskontrol') ? 'selected' : ''; ?>>Proses Kontrol</option>
                            <option value="finalkontrol" <?php echo ($search_process == 'finalkontrol') ? 'selected' : ''; ?>>Final Kontrol</option>
                        </select>
                    </div>
                    
                    <div class="form-group m-r-sm">
                        <label for="search_parti">Parti No:</label>
                        <input type="text" name="search_parti" id="search_parti" 
                               value="<?php echo safe_output($search_parti ?? ''); ?>" 
                               class="form-control" placeholder="Parti No">
                    </div>
                    
                    <div class="form-group m-r-sm">
                        <label for="search_date">Tarih:</label>
                        <input type="date" name="search_date" id="search_date" 
                               value="<?php echo safe_output($search_date ?? ''); ?>" 
                               class="form-control">
                    </div>
                    
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-search"></i> Filtrele
                    </button>
                    
                    <a href="<?php echo base_url('kontrol_no'); ?>" class="btn btn-default">
                        <i class="fa fa-refresh"></i> Temizle
                    </a>
                    
                    <?php if(isAllowedViewModule()){ ?>
                    <a href="<?php echo base_url('kontrol_no/export_excel'); ?>" class="btn btn-success">
                        <i class="fa fa-file-excel-o"></i> Excel'e Aktar
                    </a>
                    <?php } ?>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Ana Tablo -->
<div class="row">
    <div class="col-md-12">
        <div class="widget p-lg">
            <?php if(empty($items)) { ?>
                <div class="alert alert-info text-center">
                    <p>Burada herhangi bir veri bulunmamaktadır. Eklemek için lütfen 
                       <a href="<?php echo base_url("kontrol_no/new_form"); ?>">tıklayınız</a>
                    </p>
                </div>
            <?php } else { ?>
                <div class="table-responsive">
                    <table class="table table-hover table-striped table-bordered content-container">
                        <thead>
                            <tr>
                                <th class="w50">#ID</th>
                                <th>Proses</th>
                                <th>Parti No</th>
                                <th>Lot No</th>
                                <th>Kutu No</th>
                                <th>Tarih</th>
                                <th>Durum</th>
                                <th class="text-center">İşlemler</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($items as $item) { ?>
                            <tr>
                                <td class="text-center">#<?php echo safe_output($item->id); ?></td>
                                <td>
                                    <span class="label label-<?php 
                                        echo ($item->process_isim == 'girdikontrol') ? 'primary' : 
                                             (($item->process_isim == 'proseskontrol') ? 'warning' : 'success'); 
                                    ?>">
                                        <?php echo safe_output(ucfirst($item->process_isim)); ?>
                                    </span>
                                </td>
                                <td><?php echo safe_output($item->parti_no); ?></td>
                                <td><?php echo safe_output($item->lot_no); ?></td>
                                <td><?php echo safe_output($item->kutu_no); ?></td>
                                <td><?php echo safe_output(date('d.m.Y H:i', strtotime($item->tarih))); ?></td>
                                <td class="text-center">
                                    <?php if(isset($item->isActive)): ?>
                                    <input
                                        data-url="<?php echo base_url("kontrol_no/isActiveSetter/$item->id"); ?>"
                                        class="isActive"
                                        type="checkbox"
                                        data-switchery
                                        data-color="#10c469"
                                        <?php echo ($item->isActive) ? "checked" : ""; ?>
                                    />
                                    <?php else: ?>
                                    <span class="label label-success">Aktif</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="<?php echo base_url("kontrol_no/view/$item->id"); ?>" 
                                           class="btn btn-sm btn-info btn-outline">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        
                                        <?php if(isAllowedUpdateModule()){ ?>
                                        <a href="<?php echo base_url("kontrol_no/update_form/$item->id"); ?>" 
                                           class="btn btn-sm btn-warning btn-outline">
                                            <i class="fa fa-pencil"></i>
                                        </a>
                                        <?php } ?>
                                        
                                        <?php if(isAllowedDeleteModule()){ ?>
                                        <button
                                            data-url="<?php echo base_url("kontrol_no/delete/$item->id"); ?>"
                                            class="btn btn-sm btn-danger btn-outline remove-btn">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                        <?php } ?>
                                    </div>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <?php if(isset($links) && !empty($links)): ?>
                <div class="row">
                    <div class="col-md-6">
                        <p class="text-muted">
                            Sayfa <?php echo safe_output($current_page ?? 1); ?> / <?php echo safe_output($total_pages ?? 1); ?> 
                            (Toplam <?php echo safe_output($total_rows ?? 0); ?> kayıt)
                        </p>
                    </div>
                    <div class="col-md-6">
                        <?php echo $links; ?>
                    </div>
                </div>
                <?php endif; ?>
                
            <?php } ?>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Remove button confirmation
    $('.remove-btn').click(function(e) {
        e.preventDefault();
        var deleteUrl = $(this).data('url');
        
        if(confirm('Bu kaydı silmek istediğinizden emin misiniz?')) {
            window.location.href = deleteUrl;
        }
    });
    
    // Counter animation
    $('.counter').each(function() {
        var $this = $(this);
        var countTo = $this.text();
        
        $({countNum: 0}).animate({
            countNum: countTo
        }, {
            duration: 1000,
            easing: 'linear',
            step: function() {
                $this.text(Math.floor(this.countNum));
            },
            complete: function() {
                $this.text(this.countNum);
            }
        });
    });
});
</script> 