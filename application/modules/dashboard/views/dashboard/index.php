<!DOCTYPE html>
<html lang="tr">
<head>
    <?php $this->load->view("includes/head"); ?>
    <!-- AOS Animation Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Optional theme -->      
    <link rel="stylesheet" href="<?php echo base_url("assets"); ?>/assets/css/jquery.dataTables.min.css">
    
    <!-- NEW: SCSS Compiled Main CSS File -->
    <link rel="stylesheet" href="<?php echo base_url("assets"); ?>/css/main.css">
    
    <!-- LEGACY: Fallback modular CSS files (kept for compatibility) -->
    <!-- 
    <link rel="stylesheet" href="<?php echo base_url("assets"); ?>/css/dashboard-welcome.css">
    <link rel="stylesheet" href="<?php echo base_url("assets"); ?>/css/dashboard-stats.css">
    <link rel="stylesheet" href="<?php echo base_url("assets"); ?>/css/dashboard-components.css">
    <link rel="stylesheet" href="<?php echo base_url("assets"); ?>/css/dashboard-responsive.css">
    -->
</head>

<body class="menubar-left menubar-unfold menubar-light theme-primary">
<!--============= start main area -->

<!-- APP NAVBAR ==========-->
<?php $this->load->view("includes/navbar"); ?>
<!--============= end app navbar -->

<!-- APP ASIDE ==========-->
<?php $this->load->view("includes/aside"); ?>
<!--============= end app aside -->

<!-- navbar search -->
<?php $this->load->view("includes/navbar-search"); ?>
<!-- .navbar-search -->

<!-- APP MAIN ==========-->
<main id="app-main" class="app-main">
    <div class="wrap">
        <section class="app-content">
            <?php $this->load->view("{$viewFolder}/{$subViewFolder}/content"); ?>
        </section><!-- #dash-content -->
    </div><!-- .wrap -->

    <!-- APP FOOTER -->
    <?php $this->load->view("includes/footer"); ?>
    <!-- /#app-footer -->
</main>
<!--============= end app main -->

<!-- APP SCRIPTS -->
<?php $this->load->view("includes/include_script"); ?>

<!-- AOS Animation Script -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

<!-- Load Dropzone library -->
<link rel="stylesheet" href="<?php echo base_url("assets"); ?>/libs/bower/dropzone/dist/min/dropzone.min.css">
<script src="<?php echo base_url("assets"); ?>/libs/bower/dropzone/dist/min/dropzone.min.js"></script>

<script src="https://cdn.datatables.net/fixedheader/3.1.6/js/dataTables.fixedHeader.min.js"></script>
<script src="<?php echo base_url("assets"); ?>/assets/js/dataTables.turkish.js"></script>

<!-- Dashboard Modular JavaScript Files -->
<script src="<?php echo base_url("assets"); ?>/js/dashboard-clock.js"></script>
<script src="<?php echo base_url("assets"); ?>/js/dashboard-animations.js"></script>
<script src="<?php echo base_url("assets"); ?>/js/dashboard-charts.js"></script>
<script src="<?php echo base_url("assets"); ?>/js/dashboard-main.js"></script>

<!-- Dashboard Data for JavaScript -->
<script>
    // Pass PHP data to JavaScript
    window.dashboardData = {
        girdi_kontrol: <?php echo isset($stats->girdi_kontrol_total) ? $stats->girdi_kontrol_total : 0; ?>,
        proses_kontrol: <?php echo isset($stats->proses_kontrol_total) ? $stats->proses_kontrol_total : 0; ?>,
        final_kontrol: <?php echo isset($stats->final_kontrol_total) ? $stats->final_kontrol_total : 0; ?>
    };
</script>

</body>
</html> 