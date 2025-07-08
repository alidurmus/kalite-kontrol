/**
 * Error Fixes and Fallbacks for CMS Panel
 * Handles common JavaScript errors and provides graceful degradation
 */

// Load fallback libraries immediately (before DOM ready)
(function() {
    // Breakpoints.js Fallback
    if (typeof Breakpoints === 'undefined') {
        window.Breakpoints = {
            current: function() {
                var width = window.innerWidth || document.documentElement.clientWidth;
                var name = 'lg';
                
                if (width < 576) name = 'xs';
                else if (width < 768) name = 'sm';
                else if (width < 992) name = 'md';
                else if (width < 1200) name = 'lg';
                else name = 'xl';
                
                return { name: name, width: width };
            },
            
            on: function(event, callback) {
                if (typeof callback === 'function') {
                    if (event === 'change') {
                        var lastBreakpoint = this.current().name;
                        var self = this;
                        
                        window.addEventListener('resize', function() {
                            var currentBreakpoint = self.current().name;
                            if (currentBreakpoint !== lastBreakpoint) {
                                callback.call({
                                    current: self.current(),
                                    previous: { name: lastBreakpoint }
                                });
                                lastBreakpoint = currentBreakpoint;
                            }
                        });
                    } else if (event === 'xs' && typeof callback === 'object') {
                        var self = this;
                        var isXs = this.current().name === 'xs';
                        
                        window.addEventListener('resize', function() {
                            var currentIsXs = self.current().name === 'xs';
                            
                            if (!isXs && currentIsXs && callback.enter) {
                                callback.enter();
                            } else if (isXs && !currentIsXs && callback.leave) {
                                callback.leave();
                            }
                            
                            isXs = currentIsXs;
                        });
                        
                        if (isXs && callback.enter) {
                            setTimeout(callback.enter, 0);
                        }
                    }
                }
            }
        };
        
        var BreakpointsFunction = function() {
            return window.Breakpoints;
        };
        
        for (var method in window.Breakpoints) {
            if (window.Breakpoints.hasOwnProperty(method)) {
                BreakpointsFunction[method] = window.Breakpoints[method];
            }
        }
        
        window.Breakpoints = BreakpointsFunction;
        console.log('Breakpoints.js fallback loaded');
    }
    
    // Dropzone.js Fallback
    if (typeof Dropzone === 'undefined') {
        window.Dropzone = function(element, options) {
            this.element = typeof element === 'string' ? document.querySelector(element) : element;
            this.options = options || {};
            this.files = [];
            this.listeners = {};
            
            this.on = function(event, callback) {
                if (!this.listeners[event]) {
                    this.listeners[event] = [];
                }
                this.listeners[event].push(callback);
                return this;
            };
            
            this.emit = function(event, ...args) {
                if (this.listeners[event]) {
                    this.listeners[event].forEach(callback => {
                        try {
                            callback.apply(this, args);
                        } catch (error) {
                            console.log('Dropzone fallback event error:', error);
                        }
                    });
                }
                return this;
            };
            
            return this;
        };
        
        window.Dropzone.autoDiscover = true;
        window.Dropzone.discover = function() { return []; };
        window.Dropzone.options = {};
        
        console.log('Dropzone.js fallback loaded');
    }
})();

$(document).ready(function() {
    
    // Fix Dropzone initialization errors
    function initDropzoneSafely() {
        // Disable auto discovery to prevent conflicts
        if (typeof Dropzone !== 'undefined') {
            Dropzone.autoDiscover = false;
        }
        
        // Initialize Dropzone only if element exists and library is loaded
        if (typeof Dropzone !== 'undefined' && $("#dropzone").length > 0) {
            try {
                var dropzoneElement = document.getElementById("dropzone");
                
                // Check if Dropzone is already initialized
                if (dropzoneElement && !dropzoneElement.dropzone) {
                    var uploadSection = new Dropzone("#dropzone", {
                        url: $("#dropzone").attr("action") || "/upload",
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

                                // Re-initialize Switchery
                                $('[data-switchery]').each(function () {
                                    var $this = $(this),
                                        color = $this.attr('data-color') || '#188ae2',
                                        jackColor = $this.attr('data-jackColor') || '#ffffff',
                                        size = $this.attr('data-size') || 'default'

                                    if (typeof Switchery !== 'undefined') {
                                        new Switchery(this, {
                                            color: color,
                                            size: size,
                                            jackColor: jackColor
                                        });
                                    }
                                });

                                // Re-initialize sortable
                                if (typeof $.fn.sortable !== 'undefined') {
                                    $(".sortable").sortable();
                                }
                            });
                        }
                    });

                    uploadSection.on("error", function(file, errorMessage) {
                        console.log("Dropzone error:", errorMessage);
                    });
                }
            } catch (error) {
                console.log("Dropzone initialization failed:", error);
            }
        }
    }
    
    // Fix DataTable sorting icons if DataTables is loaded
    function fixDataTableIcons() {
        if (typeof $.fn.DataTable !== 'undefined') {
            // Add CSS fixes for DataTable sorting icons
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
        }
    }
    
    // Handle missing images gracefully
    function handleMissingImages() {
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
    }
    
    // Initialize fixes
    setTimeout(function() {
        initDropzoneSafely();
        fixDataTableIcons();
        handleMissingImages();
    }, 100);
    
    // Global error handler for uncaught errors
    window.addEventListener('error', function(e) {
        if (e.message.includes('Dropzone')) {
            console.log('Dropzone error handled:', e.message);
            e.preventDefault();
        }
        if (e.message.includes('Breakpoints')) {
            console.log('Breakpoints error handled:', e.message);
            e.preventDefault();
        }
    });
    
    // Handle missing resources
    $(window).on('load', function() {
        // Check for failed resource loads
        $('link[rel="stylesheet"], script[src]').each(function() {
            var element = this;
            if (element.tagName === 'LINK') {
                // Check CSS loading
                var sheet = element.sheet || element.styleSheet;
                if (!sheet) {
                    console.log('Failed to load CSS:', element.href);
                }
            }
        });
    });
});

// Fallback for SweetAlert if not loaded
if (typeof swal === 'undefined') {
    window.swal = function(options) {
        return new Promise(function(resolve) {
            var result = confirm(options.text || options.title || 'Are you sure?');
            resolve({ value: result });
        });
    };
}

// Console log suppression for production
if (window.location.hostname !== 'localhost' && window.location.hostname !== '127.0.0.1') {
    console.log = function() {};
    console.warn = function() {};
} 