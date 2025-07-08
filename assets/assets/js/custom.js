$(document).ready(function () {

    $(".sortable").sortable();

    $(".content-container, .image_list_container").on('click', '.remove-btn', function () {

        var $data_url = $(this).data("url");

        swal({
            title: 'Emin misiniz?',
            text: "Bu işlemi geri alamayacaksınız!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Evet, Sil!',
            cancelButtonText : "Hayır"
        }).then(function (result) {
            if (result.value) {

                window.location.href = $data_url;
            }
        });

    })

    $(".content-container, .image_list_container").on('change', '.isActive', function(){

        var $data = $(this).prop("checked");
        var $data_url = $(this).data("url");

        if(typeof $data !== "undefined" && typeof $data_url !== "undefined"){

            $.post($data_url, { data : $data}, function (response) {

            });

        }

    })

    $(".image_list_container").on('change', '.isCover', function(){

        var $data = $(this).prop("checked");
        var $data_url = $(this).data("url");

        if(typeof $data !== "undefined" && typeof $data_url !== "undefined"){

            $.post($data_url, { data : $data}, function (response) {

                $(".image_list_container").html(response);

                $('[data-switchery]').each(function () {
                    var $this = $(this),
                        color = $this.attr('data-color') || '#188ae2',
                        jackColor = $this.attr('data-jackColor') || '#ffffff',
                        size = $this.attr('data-size') || 'default'

                    new Switchery(this, {
                        color: color,
                        size: size,
                        jackColor: jackColor
                    });
                });

                $(".sortable").sortable();

            });

        }

    })

    $(".content-container, .image_list_container").on("sortupdate", '.sortable',  function(event, ui){

        var $data = $(this).sortable("serialize");
        var $data_url = $(this).data("url");

        $.post($data_url, {data : $data}, function(response){})

    })


    $(".button_usage_btn").change(function(){

        $(".button-information-container").slideToggle();

    })

    // Initialize Dropzone with proper error handling
    function initializeDropzone() {
        try {
            // Check if Dropzone is available and if dropzone element exists
            if (typeof Dropzone !== 'undefined' && $("#dropzone").length > 0) {
                // Disable auto discovery to prevent conflicts
                Dropzone.autoDiscover = false;
                
                // Get the element
                var dropzoneElement = document.getElementById("dropzone");
                
                if (dropzoneElement && !dropzoneElement.dropzone) {
                    var uploadSection = new Dropzone("#dropzone", {
                        url: $("#dropzone").attr("action"),
                        paramName: "file",
                        maxFilesize: 10, // MB
                        acceptedFiles: "image/*,.pdf,.doc,.docx",
                        addRemoveLinks: true,
                        dictDefaultMessage: "Dosyaları buraya sürükleyin veya tıklayın",
                        dictRemoveFile: "Kaldır",
                        dictCancelUpload: "İptal",
                        dictUploadCanceled: "Yükleme iptal edildi"
                    });

                    uploadSection.on("complete", function(file){
                        var $data_url = $("#dropzone").data("url");

                        if ($data_url) {
                            $.post($data_url, {}, function(response){
                                $(".image_list_container").html(response);

                                $('[data-switchery]').each(function () {
                                    var $this = $(this),
                                        color = $this.attr('data-color') || '#188ae2',
                                        jackColor = $this.attr('data-jackColor') || '#ffffff',
                                        size = $this.attr('data-size') || 'default'

                                    new Switchery(this, {
                                        color: color,
                                        size: size,
                                        jackColor: jackColor
                                    });
                                });

                                $(".sortable").sortable();
                            });
                        }
                    });

                    uploadSection.on("error", function(file, errorMessage) {
                        console.log("Dropzone error:", errorMessage);
                    });
                }
            }
        } catch (error) {
            console.log("Dropzone initialization failed:", error);
        }
    }

    // Initialize Dropzone after a short delay to ensure all libraries are loaded
    setTimeout(initializeDropzone, 100);
    
    // Fix DataTable sorting icons with CSS
    $('<style>').text(`
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
    `).appendTo('head');
    
    // Handle missing images gracefully
    $('img').on('error', function() {
        var $img = $(this);
        var src = $img.attr('src');
        
        // Don't replace if already using placeholder
        if (src && !src.includes('data:image')) {
            console.log('Image not found:', src);
            // Use a simple base64 placeholder
            $img.attr('src', 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTUwIiBoZWlnaHQ9IjM1IiB2aWV3Qm94PSIwIDAgMTUwIDM1IiBmaWxsPSJub25lIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPjxyZWN0IHdpZHRoPSIxNTAiIGhlaWdodD0iMzUiIGZpbGw9IiNmNWY1ZjUiLz48dGV4dCB4PSI3NSIgeT0iMjAiIGZvbnQtZmFtaWx5PSJBcmlhbCwgc2Fucy1zZXJpZiIgZm9udC1zaXplPSIxMiIgZmlsbD0iIzk5OSIgdGV4dC1hbmNob3I9Im1pZGRsZSI+SW1hZ2UgTm90IEZvdW5kPC90ZXh0Pjwvc3ZnPg==');
        }
    });

})