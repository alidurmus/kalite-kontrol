<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="utf-8">


</head>

<body class="menubar-left menubar-unfold menubar-light theme-primary">
<!--============= start main area -->

  
    <!-- APP MAIN ==========-->
    <main id="app-main" class="app-main">
        <div class="wrap">
            <section class="app-content">
                <?php $this->load->view("{$viewFolder}/{$subViewFolder}/content"); ?>
            </section><!-- #dash-content -->
        </div><!-- .wrap -->

        <!-- APP FOOTER -->
       
        <!-- /#app-footer -->
    </main>
    <!--========== END app main -->

    <?php $this->load->view("includes/include_script"); ?>
 
    <script src="<?php echo base_url("assets"); ?>/assets/js/printThis.js"></script>


  <!-- demo -->
  <script>
    $('#basic').on("click", function () {
      $('.demo').printThis({
        base: "https://jasonday.github.io/printThis/"
      });
    });


    $('#advanced').on("click", function () {
      $('#kitty-one, #kitty-two, #kitty-three').printThis({
        importCSS: false,
        header: "<h1>Look at all of my kitties!</h1>",
        base: "https://jasonday.github.io/printThis/"
      });
    });
    $('#yazdir').on("click", function () {
        document.title = " "; window.print();
        $(".yazdirilacak").printThis({
        debug: false,               // show the iframe for debugging
        importCSS: true,            // import parent page css
        importStyle: false,         // import style tags
        printContainer: true,       // print outer container/$.selector
        loadCSS: "",                // path to additional css file - use an array [] for multiple
        pageTitle: "",              // add title to print page
        removeInline: false,        // remove inline styles from print elements
        removeInlineSelector: "*",  // custom selectors to filter inline styles. removeInline must be true
        printDelay: 333,            // variable print delay
        header: null,               // prefix to html
        footer: null,               // postfix to html
        base: false,                // preserve the BASE tag or accept a string for the URL
        formValues: true,           // preserve input/form values
        canvas: false,              // copy canvas content
        doctypeString: '...',       // enter a different doctype for older markup
        removeScripts: false,       // remove script tags from print content
        copyTagClasses: false,      // copy classes from the html & body tag
        beforePrintEvent: null,     // function for printEvent in iframe
        beforePrint: null,          // function called before iframe is filled
        afterPrint: null            // function called before iframe is removed
        });
    });  




  </script>
  
  <!-- Global site tag (gtag.js) - Google Analytics -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=UA-114774247-1"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'UA-114774247-1');
  </script>

</body>
</html>