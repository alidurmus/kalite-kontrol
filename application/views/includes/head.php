<?php 
// Load security helper for XSS protection
$this->load->helper('security');
$settings = get_settings(); ?>

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title><?php echo isset($title) ? $title : "CMS Panel"; ?></title>

<!-- VENDOR CSS -->
<link rel="stylesheet" href="<?php echo base_url("assets"); ?>/libs/bower/font-awesome/css/font-awesome.min.css">
<link rel="stylesheet" href="<?php echo base_url("assets"); ?>/libs/bower/material-design-iconic-font/dist/css/material-design-iconic-font.css">
<link rel="stylesheet" href="<?php echo base_url("assets"); ?>/libs/bower/animate.css/animate.min.css">
<link rel="stylesheet" href="<?php echo base_url("assets"); ?>/libs/bower/perfect-scrollbar/css/perfect-scrollbar.css">
<link rel="stylesheet" href="<?php echo base_url("assets"); ?>/libs/bower/switchery/dist/switchery.min.css">
<link rel="stylesheet" href="<?php echo base_url("assets"); ?>/libs/bower/jquery-ui/themes/ui-lightness/jquery-ui.min.css">
<link rel="stylesheet" href="<?php echo base_url("assets"); ?>/libs/bower/lightbox2/dist/css/lightbox.min.css">
<link rel="stylesheet" href="<?php echo base_url("assets"); ?>/libs/bower/select2/dist/css/select2.min.css">
<link rel="stylesheet" href="<?php echo base_url("assets"); ?>/libs/bower/bootstrap-daterangepicker/daterangepicker.css">
<link rel="stylesheet" href="<?php echo base_url("assets"); ?>/libs/bower/dropzone/dist/min/dropzone.min.css">

<!-- APP CSS -->
<link rel="stylesheet" href="<?php echo base_url("assets"); ?>/assets/css/app.css">
<link rel="stylesheet" href="<?php echo base_url("assets"); ?>/assets/css/app-1.css">
<link rel="stylesheet" href="<?php echo base_url("assets"); ?>/assets/css/app-2.css">

<!-- QMS Enhancements -->
<link rel="stylesheet" href="<?php echo base_url("assets"); ?>/css/breadcrumb.css">
<link rel="stylesheet" href="<?php echo base_url("assets"); ?>/css/mobile-responsive.css">

<!-- Error Handling Script -->
<script>
    // Global error handler to suppress console errors
    window.addEventListener('error', function(e) {
        if (e.message.includes('Dropzone') || e.message.includes('sort_both.png') || e.message.includes('sort_desc.png')) {
            e.preventDefault();
            return true;
        }
    });
    
    // Console override for production
    if (window.location.hostname !== 'localhost' && window.location.hostname !== '127.0.0.1') {
        console.error = function() {};
    }
</script>

<!-- DataTable Icons Fix -->
<style>
    table.dataTable thead .sorting {
        background-image: none !important;
        position: relative;
    }
    table.dataTable thead .sorting:after {
        content: "⇅";
        position: absolute;
        right: 8px;
        top: 50%;
        transform: translateY(-50%);
        color: #999;
        font-size: 12px;
    }
    table.dataTable thead .sorting_asc {
        background-image: none !important;
    }
    table.dataTable thead .sorting_asc:after {
        content: "↑";
        position: absolute;
        right: 8px;
        top: 50%;
        transform: translateY(-50%);
        color: #333;
        font-size: 12px;
    }
    table.dataTable thead .sorting_desc {
        background-image: none !important;
    }
    table.dataTable thead .sorting_desc:after {
        content: "↓";
        position: absolute;
        right: 8px;
        top: 50%;
        transform: translateY(-50%);
        color: #333;
        font-size: 12px;
    }
</style>