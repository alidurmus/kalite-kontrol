/**
 * Breakpoints.js Fallback
 * Provides fallback functionality when Breakpoints.js library is not loaded
 */

// Check if Breakpoints is already defined
if (typeof Breakpoints === 'undefined') {
    // Create a fallback Breakpoints object
    window.Breakpoints = {
        // Current breakpoint information
        current: function() {
            var width = window.innerWidth || document.documentElement.clientWidth;
            var name = 'lg'; // default
            
            if (width < 576) {
                name = 'xs';
            } else if (width < 768) {
                name = 'sm';
            } else if (width < 992) {
                name = 'md';
            } else if (width < 1200) {
                name = 'lg';
            } else {
                name = 'xl';
            }
            
            return {
                name: name,
                width: width
            };
        },
        
        // Event handling
        on: function(event, callback) {
            if (typeof callback === 'function') {
                // For 'change' event, listen to window resize
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
                    // Handle xs breakpoint specific events
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
                    
                    // Call initial state
                    if (isXs && callback.enter) {
                        setTimeout(callback.enter, 0);
                    }
                }
            }
        },
        
        // Initialize function (for compatibility)
        init: function() {
            console.log('Breakpoints fallback initialized');
            return this;
        }
    };
    
    // Make it callable as a function (like the original library)
    var BreakpointsFunction = function() {
        return window.Breakpoints.init();
    };
    
    // Copy all methods to the function
    for (var method in window.Breakpoints) {
        if (window.Breakpoints.hasOwnProperty(method)) {
            BreakpointsFunction[method] = window.Breakpoints[method];
        }
    }
    
    // Replace the object with the function
    window.Breakpoints = BreakpointsFunction;
    
    console.log('Breakpoints.js fallback loaded');
} 