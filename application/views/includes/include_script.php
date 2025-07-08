<!-- build:js /assets/js/core.min.js -->
<script src="<?php echo base_url("assets"); ?>/libs/bower/jquery/dist/jquery.js"></script>
<script src="<?php echo base_url("assets"); ?>/libs/bower/jquery-ui/jquery-ui.min.js"></script>
<script src="<?php echo base_url("assets"); ?>/libs/bower/jQuery-Storage-API/jquery.storageapi.min.js"></script>
<script src="<?php echo base_url("assets"); ?>/libs/bower/bootstrap-sass/assets/javascripts/bootstrap.js"></script>
<script src="<?php echo base_url("assets"); ?>/libs/bower/jquery-slimscroll/jquery.slimscroll.js"></script>
<script src="<?php echo base_url("assets"); ?>/libs/bower/perfect-scrollbar/js/perfect-scrollbar.jquery.js"></script>
<script src="<?php echo base_url("assets"); ?>/libs/bower/PACE/pace.min.js"></script>
<!-- endbuild -->

<!-- build:js <?php echo base_url("assets"); ?>/assets/js/app.min.js -->
<!--<script src="--><?php //echo base_url("assets"); ?><!--/assets/js/library.js"></script>-->
<?php $this->load->view("includes/library"); ?>
<script src="<?php echo base_url("assets"); ?>/assets/js/plugins.js"></script>
<script src="<?php echo base_url("assets"); ?>/assets/js/app.js"></script>
<!-- endbuild -->
<script src="<?php echo base_url("assets"); ?>/libs/bower/moment/moment.js"></script>
<script src="<?php echo base_url("assets"); ?>/libs/bower/fullcalendar/dist/fullcalendar.min.js"></script>
<script src="<?php echo base_url("assets"); ?>/assets/js/fullcalendar.js"></script>

<script src="<?php echo base_url("assets"); ?>/assets/js/sweetalert2.all.js"></script>

<script src="<?php echo base_url("assets"); ?>/assets/js/iziToast.min.js"></script>

<?php $this->load->view("includes/alert"); ?>

<!-- QMS UI Enhancements -->
<script src="<?php echo base_url("assets"); ?>/js/loading-helper.js"></script>
<script src="<?php echo base_url("assets"); ?>/js/accessibility-helper.js"></script>

<!-- Error Fixes and Fallbacks - Load BEFORE other scripts to prevent undefined errors -->
<script src="<?php echo base_url("assets"); ?>/js/error-fixes.js"></script>

<link rel="stylesheet" href="<?php echo base_url("assets"); ?>/css/datatable-fixes.css">
<script>
    // Initialize QMS UI Helpers
    window.qmsLoading = new LoadingHelper();
    
    // Initialize mobile table enhancement
    $(document).ready(function() {
        // Add mobile responsive classes to tables
        $('table').each(function() {
            const table = $(this);
            if (!table.closest('.table-responsive').length) {
                table.wrap('<div class="table-responsive table-responsive-mobile"></div>');
            }
            
            // Add data labels for mobile view
            const headers = table.find('thead th');
            table.find('tbody tr').each(function() {
                $(this).find('td').each(function(index) {
                    const headerText = headers.eq(index).text().trim();
                    if (headerText) {
                        $(this).attr('data-label', headerText);
                    }
                });
            });
        });
        
        // Enhanced form submission with loading states
        $('form').on('submit', function() {
            const submitBtn = $(this).find('button[type="submit"], input[type="submit"]');
            if (submitBtn.length && window.qmsLoading) {
                window.qmsLoading.showButtonLoading(submitBtn[0]);
            }
        });
        
        // Enhanced Ajax with notifications
        $(document).ajaxComplete(function(event, xhr, settings) {
            if (xhr.status === 200 && window.qmsLoading) {
                window.qmsLoading.success('İşlem başarıyla tamamlandı');
            } else if (xhr.status >= 400 && window.qmsLoading) {
                window.qmsLoading.error('İşlem sırasında bir hata oluştu');
            }
        });
    });
</script>

<!--<script src="--><?php //echo base_url("assets"); ?><!--/assets/js/custom.js"></script>-->
