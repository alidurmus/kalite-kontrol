/**
 * Dropzone.js Fallback
 * Provides fallback functionality when Dropzone.js library is not loaded
 */

// Check if Dropzone is already defined
if (typeof Dropzone === 'undefined') {
    // Create a fallback Dropzone constructor
    window.Dropzone = function(element, options) {
        console.log('Dropzone fallback: Library not loaded, using fallback');
        
        // Store element and options
        this.element = typeof element === 'string' ? document.querySelector(element) : element;
        this.options = options || {};
        
        // Basic properties
        this.files = [];
        this.listeners = {};
        
        // Event handling methods
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
        
        // Basic file handling
        this.addFile = function(file) {
            this.files.push(file);
            this.emit('addedfile', file);
        };
        
        this.removeFile = function(file) {
            var index = this.files.indexOf(file);
            if (index > -1) {
                this.files.splice(index, 1);
                this.emit('removedfile', file);
            }
        };
        
        // Fallback upload simulation
        this.processQueue = function() {
            console.log('Dropzone fallback: processQueue called');
        };
        
        // Add a basic file input fallback
        if (this.element && !this.element.querySelector('input[type="file"]')) {
            var fileInput = document.createElement('input');
            fileInput.type = 'file';
            fileInput.multiple = true;
            fileInput.style.width = '100%';
            fileInput.style.height = '100px';
            fileInput.style.border = '2px dashed #ccc';
            fileInput.style.borderRadius = '4px';
            fileInput.style.padding = '20px';
            fileInput.style.textAlign = 'center';
            fileInput.style.cursor = 'pointer';
            
            var self = this;
            fileInput.addEventListener('change', function(e) {
                Array.from(e.target.files).forEach(function(file) {
                    self.addFile(file);
                });
            });
            
            this.element.appendChild(fileInput);
        }
        
        return this;
    };
    
    // Static properties and methods
    window.Dropzone.autoDiscover = true;
    
    window.Dropzone.discover = function() {
        console.log('Dropzone fallback: discover called');
        return [];
    };
    
    window.Dropzone.options = {};
    
    console.log('Dropzone.js fallback loaded');
} 