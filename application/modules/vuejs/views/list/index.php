<!DOCTYPE html>
<html lang="tr">
<head>
    <?php $this->load->view("vuejs/includes/head"); ?>
   
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    
    <script src="<?php echo base_url("assets"); ?>/assets/js/vue-qrcode-reader.browser.js"></script>
   
    <link rel="stylesheet" href="https://unpkg.com/vue-qrcode-reader@2.0.3/dist/vue-qrcode-reader.css">
   
   </head>

<body class="menubar-left menubar-unfold menubar-light theme-primary">
<!--============= start main area -->

    <!-- APP NAVBAR ==========-->
    <?php $this->load->view("vuejs/includes/navbar"); ?>
    <!--========== END app navbar -->

    <!-- APP ASIDE ==========-->
    <?php $this->load->view("vuejs/includes/aside"); ?>
    <!--========== END app aside -->

    <!-- navbar search -->
    <?php $this->load->view("vuejs/includes/navbar-search"); ?>
    <!-- .navbar-search -->

    <!-- APP MAIN ==========-->
    <main id="app-main" class="app-main">
        <div class="wrap">
            <section class="app-content">
                <?php $this->load->view("{$viewFolder}/{$subViewFolder}/content"); ?>
            </section><!-- #dash-content -->
        </div><!-- .wrap -->

        <!-- APP FOOTER -->
        <?php $this->load->view("vuejs/includes/footer"); ?>
        <!-- /#app-footer -->
    </main>
    <!--========== END app main -->

    <?php $this->load->view("vuejs/includes/include_script"); ?>

</body>
</html>