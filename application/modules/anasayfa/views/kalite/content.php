<div class="container-fluid">

<div class="jumbotron text-center bg-primary text-white">
  <h1><i class="zmdi zmdi-check-circle zmdi-hc-2x"></i> Kalite Kontrol Merkezi</h1>
  <p class="lead">Üretim sürecinizin her aşamasında kalite güvencesi</p> 
  <a href="<?php echo base_url('dashboard'); ?>" class="btn btn-light btn-lg">
    <i class="zmdi zmdi-view-dashboard"></i> Dashboard'a Dön
  </a>
</div>
  
<div class="container">
  <div class="row">
    <div class="col-sm-3">
    </div>
    <div class="col-sm-6">
      
      <div class="row">
        <div class="col-md-12 mb-3">                        
          <a href="<?php echo base_url('girdikontrol'); ?>" class="btn btn-primary btn-lg btn-block">
            <i class="zmdi zmdi-inbox zmdi-hc-lg"></i><br>
            <strong>Girdi Kontrol</strong><br>
            <small>Gelen malzemelerin kalite kontrolü</small>
          </a>             
        </div>
        
        <div class="col-md-12 mb-3">
          <a href="<?php echo base_url('proseskontrol'); ?>" class="btn btn-success btn-lg btn-block">
            <i class="zmdi zmdi-settings zmdi-hc-lg"></i><br>
            <strong>Proses Kontrol</strong><br>
            <small>Üretim sürecindeki kalite kontrolleri</small>
          </a>
        </div>
        
        <div class="col-md-12 mb-3">
          <a href="<?php echo base_url('finalkontrol'); ?>" class="btn btn-danger btn-lg btn-block">
            <i class="zmdi zmdi-check-circle zmdi-hc-lg"></i><br>
            <strong>Final Kontrol</strong><br>
            <small>Son ürün kalite kontrolleri</small>
          </a>
        </div>
        
        <div class="col-md-12 mb-3">
          <a href="<?php echo base_url('dashboard'); ?>" class="btn btn-warning btn-lg btn-block">
            <i class="zmdi zmdi-view-dashboard zmdi-hc-lg"></i><br>
            <strong>Yönetim Dashboard</strong><br>
            <small>İstatistikler ve raporlar</small>
          </a>
        </div>
        
        <div class="col-md-12 mb-3">
          <a href="<?php echo base_url('etiket'); ?>" class="btn btn-info btn-lg btn-block">
            <i class="zmdi zmdi-label zmdi-hc-lg"></i><br>
            <strong>Kutu No Etiket</strong><br>
            <small>Etiket oluşturma ve yazdırma</small>
          </a>
        </div>
      </div>
                                     
    </div>  
    <div class="col-sm-3">
    </div>  
      
    </div>
    
    <!-- Additional Information -->
    <div class="row mt-5">
      <div class="col-12">
        <div class="card">
          <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="zmdi zmdi-info"></i> Kalite Kontrol Süreçleri</h5>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-4">
                <h6><i class="zmdi zmdi-inbox text-primary"></i> Girdi Kontrol</h6>
                <p class="text-muted">Tedarikçilerden gelen hammadde ve malzemelerin spesifikasyonlara uygunluğunun kontrolü.</p>
              </div>
              <div class="col-md-4">
                <h6><i class="zmdi zmdi-settings text-success"></i> Proses Kontrol</h6>
                <p class="text-muted">Üretim sürecinde belirlenen kontrol noktalarında yapılan kalite kontrolleri.</p>
              </div>
              <div class="col-md-4">
                <h6><i class="zmdi zmdi-check-circle text-danger"></i> Final Kontrol</h6>
                <p class="text-muted">Tamamlanan ürünlerin sevkiyat öncesi son kalite kontrolleri ve onayı.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    
  </div>
</div>
    
</div>

