<!-- Dashboard Content with Helper Functions -->
<div class="container-fluid">
    
    <!-- Welcome Header Card -->
    <div class="dashboard-welcome-card" data-aos="fade-down">
        <div class="d-flex align-items-center">
            <div class="dashboard-icon">
                <i class="zmdi zmdi-shield-check zmdi-hc-2x"></i>
            </div>
            <div class="flex-grow-1">
                <h1 class="welcome-title">
                    <?php echo $dashboard_config['dashboard_title'] ?? 'Kalite Yönetim Dashboard'; ?>
                </h1>
                <p class="welcome-subtitle">
                    <?php echo $dashboard_config['dashboard_subtitle'] ?? 'Kalite kontrol süreçlerinizi tek yerden yönetin'; ?>
                </p>
                <div class="dashboard-time" id="current-time">
                    <!-- Clock will be updated by JavaScript -->
                </div>
            </div>
            <div>
                <button class="btn btn-premium dashboard-refresh" onclick="dashboard.refresh()">
                    <i class="zmdi zmdi-refresh"></i> Yenile
                </button>
            </div>
        </div>
    </div>

    <!-- Main Statistics Cards -->
    <div class="row">
        <div class="col-xl-3 col-lg-6 col-md-6">
            <?php 
            echo render_stat_card([
                'number' => $stats->girdi_kontrol_total ?? 0,
                'label' => 'Girdi Kontrol',
                'icon' => 'zmdi-input-antenna',
                'color' => 'primary',
                'today_text' => 'Bugün',
                'today_value' => $stats->girdi_kontrol_today ?? 0,
                'link_url' => base_url('girdikontrol'),
                'link_text' => 'Detayları Görüntüle',
                'animation_delay' => '100'
            ]);
            ?>
        </div>
        
        <div class="col-xl-3 col-lg-6 col-md-6">
            <?php 
            echo render_stat_card([
                'number' => $stats->proses_kontrol_total ?? 0,
                'label' => 'Proses Kontrol',
                'icon' => 'zmdi-settings',
                'color' => 'success',
                'today_text' => 'Bugün',
                'today_value' => $stats->proses_kontrol_today ?? 0,
                'link_url' => base_url('proseskontrol'),
                'link_text' => 'Detayları Görüntüle',
                'animation_delay' => '200'
            ]);
            ?>
        </div>
        
        <div class="col-xl-3 col-lg-6 col-md-6">
            <?php 
            echo render_stat_card([
                'number' => $stats->final_kontrol_total ?? 0,
                'label' => 'Final Kontrol',
                'icon' => 'zmdi-check-circle',
                'color' => 'danger',
                'today_text' => 'Bugün',
                'today_value' => $stats->final_kontrol_today ?? 0,
                'link_url' => base_url('finalkontrol'),
                'link_text' => 'Detayları Görüntüle',
                'animation_delay' => '300'
            ]);
            ?>
        </div>
        
        <div class="col-xl-3 col-lg-6 col-md-6">
            <?php 
            echo render_stat_card([
                'number' => $stats->kontrol_no_total ?? 0,
                'label' => 'Kontrol No',
                'icon' => 'zmdi-assignment',
                'color' => 'warning',
                'today_text' => 'Toplam',
                'today_value' => $stats->kontrol_no_total ?? 0,
                'link_url' => base_url('kontrol_no'),
                'link_text' => 'Detayları Görüntüle',
                'animation_delay' => '400'
            ]);
            ?>
        </div>
    </div>

    <!-- Mini Statistics Row -->
    <div class="row">
        <div class="col-xl-3 col-lg-6 col-md-6">
            <?php 
            echo render_mini_stat_card([
                'number' => $mini_stats->tedarikciler_total ?? 0,
                'label' => 'Tedarikçiler',
                'icon' => 'zmdi-truck',
                'color' => '#6f42c1',
                'animation_delay' => '500'
            ]);
            ?>
        </div>
        
        <div class="col-xl-3 col-lg-6 col-md-6">
            <?php 
            echo render_mini_stat_card([
                'number' => $mini_stats->malzemeler_total ?? 0,
                'label' => 'Malzemeler',
                'icon' => 'zmdi-archive',
                'color' => '#e83e8c',
                'animation_delay' => '600'
            ]);
            ?>
        </div>
        
        <div class="col-xl-3 col-lg-6 col-md-6">
            <?php 
            echo render_mini_stat_card([
                'number' => $mini_stats->urunler_total ?? 0,
                'label' => 'Ürünler',
                'icon' => 'zmdi-shopping-cart',
                'color' => '#fd7e14',
                'animation_delay' => '700'
            ]);
            ?>
        </div>
        
        <div class="col-xl-3 col-lg-6 col-md-6">
            <?php 
            echo render_mini_stat_card([
                'number' => $mini_stats->users_total ?? 0,
                'label' => 'Kullanıcılar',
                'icon' => 'zmdi-accounts',
                'color' => '#20c997',
                'animation_delay' => '800'
            ]);
            ?>
        </div>
    </div>

    <!-- Quick Actions & Performance Metrics Row -->
    <div class="row">
        <!-- Quick Actions -->
        <div class="col-lg-8">
            <div class="premium-card" data-aos="fade-up" data-aos-delay="200">
                <div class="premium-header">
                    <h5 class="mb-0">
                        <i class="zmdi zmdi-flash text-primary mr-2"></i>
                        Hızlı İşlemler
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-4 col-md-6 mb-3">
                            <?php 
                            echo render_quick_action([
                                'title' => 'Kalite Merkezi',
                                'icon' => 'zmdi-shield-check',
                                'color' => 'primary',
                                'url' => base_url('kalite'),
                                'animation_delay' => '0'
                            ]);
                            ?>
                        </div>
                        
                        <div class="col-lg-4 col-md-6 mb-3">
                            <?php 
                            echo render_quick_action([
                                'title' => 'Girdi Kontrol',
                                'icon' => 'zmdi-input-antenna',
                                'color' => 'info',
                                'url' => base_url('girdikontrol'),
                                'animation_delay' => '100'
                            ]);
                            ?>
                        </div>
                        
                        <div class="col-lg-4 col-md-6 mb-3">
                            <?php 
                            echo render_quick_action([
                                'title' => 'Proses Kontrol',
                                'icon' => 'zmdi-settings',
                                'color' => 'success',
                                'url' => base_url('proseskontrol'),
                                'animation_delay' => '200'
                            ]);
                            ?>
                        </div>
                        
                        <div class="col-lg-4 col-md-6 mb-3">
                            <?php 
                            echo render_quick_action([
                                'title' => 'Final Kontrol',
                                'icon' => 'zmdi-check-circle',
                                'color' => 'danger',
                                'url' => base_url('finalkontrol'),
                                'animation_delay' => '300'
                            ]);
                            ?>
                        </div>
                        
                        <div class="col-lg-4 col-md-6 mb-3">
                            <?php 
                            echo render_quick_action([
                                'title' => 'Malzemeler',
                                'icon' => 'zmdi-archive',
                                'color' => 'secondary',
                                'url' => base_url('malzemeler'),
                                'animation_delay' => '400'
                            ]);
                            ?>
                        </div>
                        
                        <div class="col-lg-4 col-md-6 mb-3">
                            <?php 
                            echo render_quick_action([
                                'title' => 'Ürünler',
                                'icon' => 'zmdi-shopping-cart',
                                'color' => 'dark',
                                'url' => base_url('urunler'),
                                'animation_delay' => '500'
                            ]);
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Performance Metrics -->
        <div class="col-lg-4">
            <div class="premium-card" data-aos="fade-up" data-aos-delay="300">
                <div class="premium-header">
                    <h5 class="mb-0">
                        <i class="zmdi zmdi-trending-up text-success mr-2"></i>
                        Performans Metrikleri
                    </h5>
                </div>
                <div class="card-body">
                    <?php 
                    echo render_performance_metric([
                        'label' => 'Kalite Başarı Oranı',
                        'percentage' => $performance_metrics['quality_success'] ?? 95,
                        'color' => 'success'
                    ]);
                    
                    echo render_performance_metric([
                        'label' => 'Zamanında Teslimat',
                        'percentage' => $performance_metrics['delivery_ontime'] ?? 88,
                        'color' => 'warning'
                    ]);
                    
                    echo render_performance_metric([
                        'label' => 'Müşteri Memnuniyeti',
                        'percentage' => $performance_metrics['customer_satisfaction'] ?? 92,
                        'color' => 'info'
                    ]);
                    ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts & Recent Activities Row -->
    <div class="row">
        <!-- Quality Chart -->
        <div class="col-lg-6">
            <div class="premium-card" data-aos="fade-up" data-aos-delay="400">
                <div class="premium-header">
                    <h5 class="mb-0">
                        <i class="zmdi zmdi-chart-donut text-primary mr-2"></i>
                        Kalite Kontrol Dağılımı
                    </h5>
                    <div class="chart-controls">
                        <!-- Chart filter buttons will be added by JavaScript -->
                    </div>
                </div>
                <div class="card-body">
                    <div style="height: 300px;">
                        <canvas id="qualityChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="col-lg-6">
            <div class="premium-card" data-aos="fade-up" data-aos-delay="500">
                <div class="premium-header">
                    <h5 class="mb-0">
                        <i class="zmdi zmdi-time text-info mr-2"></i>
                        Son Aktiviteler
                    </h5>
                </div>
                <div class="card-body">
                    <div class="activity-list">
                        <?php if (!empty($activities->recent_girdi)): ?>
                            <h6 class="activity-section-title">Son Girdi Kontrolleri</h6>
                            <?php foreach (array_slice($activities->recent_girdi, 0, 3) as $item): ?>
                                <?php 
                                echo render_activity_item([
                                    'time' => $item->created_at ?? date('Y-m-d H:i:s'),
                                    'title' => 'Girdi Kontrol ID: ' . ($item->id ?? 'N/A'),
                                    'description' => 'Yeni girdi kontrol kaydı oluşturuldu',
                                    'type' => 'create',
                                    'user' => $item->created_by ?? 'Sistem'
                                ]);
                                ?>
                            <?php endforeach; ?>
                        <?php endif; ?>

                        <?php if (!empty($activities->recent_proses)): ?>
                            <h6 class="activity-section-title">Son Proses Kontrolleri</h6>
                            <?php foreach (array_slice($activities->recent_proses, 0, 3) as $item): ?>
                                <?php 
                                echo render_activity_item([
                                    'time' => $item->created_at ?? date('Y-m-d H:i:s'),
                                    'title' => 'Proses Kontrol ID: ' . ($item->id ?? 'N/A'),
                                    'description' => 'Yeni proses kontrol kaydı oluşturuldu',
                                    'type' => 'create',
                                    'user' => $item->created_by ?? 'Sistem'
                                ]);
                                ?>
                            <?php endforeach; ?>
                        <?php endif; ?>

                        <?php if (!empty($activities->recent_final)): ?>
                            <h6 class="activity-section-title">Son Final Kontrolleri</h6>
                            <?php foreach (array_slice($activities->recent_final, 0, 3) as $item): ?>
                                <?php 
                                echo render_activity_item([
                                    'time' => $item->created_at ?? date('Y-m-d H:i:s'),
                                    'title' => 'Final Kontrol ID: ' . ($item->id ?? 'N/A'),
                                    'description' => 'Yeni final kontrol kaydı oluşturuldu',
                                    'type' => 'create',
                                    'user' => $item->created_by ?? 'Sistem'
                                ]);
                                ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="row">
        <div class="col-12">
            <div class="premium-card" data-aos="fade-up" data-aos-delay="600">
                <div class="premium-header">
                    <h5 class="mb-0">
                        <i class="zmdi zmdi-grid text-primary mr-2"></i>
                        Kontrol No Listesi
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Search Form -->
                    <form method="post" action="<?php echo base_url('dashboard'); ?>" class="mb-3">
                        <div class="row">
                            <div class="col-md-8">
                                <input type="text" 
                                       name="search" 
                                       value="<?php echo htmlspecialchars($search_text ?? '', ENT_QUOTES, 'UTF-8'); ?>" 
                                       class="form-control" 
                                       placeholder="Proses ismi ile arama yapın...">
                            </div>
                            <div class="col-md-4">
                                <button type="submit" name="submit" class="btn btn-primary btn-block">
                                    <i class="zmdi zmdi-search"></i> Ara
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Data Table -->
                    <div class="table-responsive">
                        <table class="table table-hover" id="controlTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Proses İsmi</th>
                                    <th>Durum</th>
                                    <th>Oluşturulma Tarihi</th>
                                    <th>İşlemler</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($items)): ?>
                                    <?php foreach ($items as $item): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($item->id ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                                            <td><?php echo htmlspecialchars($item->process_isim ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                                            <td>
                                                <span class="badge badge-<?php echo get_color_by_status($item->status ?? ''); ?>">
                                                    <?php echo htmlspecialchars($item->status ?? 'Bilinmiyor', ENT_QUOTES, 'UTF-8'); ?>
                                                </span>
                                            </td>
                                            <td><?php echo format_time_display($item->created_at ?? ''); ?></td>
                                            <td>
                                                <a href="<?php echo base_url('kontrol_no/detail/' . ($item->id ?? '')); ?>" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="zmdi zmdi-eye"></i> Görüntüle
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center">Veri bulunamadı</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        <?php echo $links ?? ''; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 