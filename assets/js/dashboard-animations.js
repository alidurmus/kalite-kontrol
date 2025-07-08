/**
 * Dashboard Animations Module
 * Handles AOS initialization and counter animations
 */

class DashboardAnimations {
    constructor() {
        this.counters = [];
        this.isAOSLoaded = false;
    }

    /**
     * Initialize AOS (Animate On Scroll) library
     */
    initAOS() {
        if (typeof AOS !== 'undefined') {
            AOS.init({
                duration: 800,
                easing: 'ease-in-out-sine',
                delay: 100,
                once: true
            });
            this.isAOSLoaded = true;
            console.log('AOS initialized successfully');
        } else {
            console.warn('AOS library not loaded');
        }
    }

    /**
     * Refresh AOS (useful for dynamically added content)
     */
    refreshAOS() {
        if (this.isAOSLoaded && typeof AOS !== 'undefined') {
            AOS.refresh();
        }
    }

    /**
     * Initialize counter animations for statistics
     * @param {string} selector - CSS selector for counter elements
     * @param {number} duration - Animation duration in milliseconds
     */
    initCounters(selector = '.stats-number', duration = 2000) {
        const counterElements = document.querySelectorAll(selector);
        
        counterElements.forEach(element => {
            const finalValue = parseInt(element.textContent) || 0;
            this.animateCounter(element, 0, finalValue, duration);
        });
    }

    /**
     * Animate a single counter
     * @param {HTMLElement} element - Counter element
     * @param {number} start - Start value
     * @param {number} end - End value
     * @param {number} duration - Animation duration
     */
    animateCounter(element, start, end, duration) {
        const startTime = performance.now();
        const range = end - start;

        const updateCounter = (currentTime) => {
            const elapsedTime = currentTime - startTime;
            const progress = Math.min(elapsedTime / duration, 1);
            
            // Easing function (ease-out)
            const easedProgress = 1 - Math.pow(1 - progress, 3);
            const currentValue = Math.floor(start + (range * easedProgress));
            
            element.textContent = currentValue.toLocaleString('tr-TR');
            
            if (progress < 1) {
                requestAnimationFrame(updateCounter);
            } else {
                element.textContent = end.toLocaleString('tr-TR');
            }
        };

        requestAnimationFrame(updateCounter);
    }

    /**
     * Add fade-in animation to elements
     * @param {string} selector - CSS selector
     * @param {number} delay - Delay between elements (ms)
     */
    fadeInElements(selector, delay = 100) {
        const elements = document.querySelectorAll(selector);
        
        elements.forEach((element, index) => {
            setTimeout(() => {
                element.classList.add('fade-in');
            }, index * delay);
        });
    }

    /**
     * Add hover animations to cards
     * @param {string} selector - CSS selector for cards
     */
    initCardHovers(selector = '.stats-card, .mini-stats-card, .quick-action-item') {
        const cards = document.querySelectorAll(selector);
        
        cards.forEach(card => {
            card.addEventListener('mouseenter', () => {
                card.style.transform = 'translateY(-5px)';
                card.style.transition = 'all 0.3s ease';
            });
            
            card.addEventListener('mouseleave', () => {
                card.style.transform = 'translateY(0)';
            });
        });
    }

    /**
     * Initialize all animations
     */
    init() {
        // Initialize AOS
        this.initAOS();
        
        // Initialize counters with a small delay to ensure DOM is ready
        setTimeout(() => {
            this.initCounters();
        }, 300);
        
        // Initialize card hover effects
        this.initCardHovers();
        
        // Fade in dashboard elements
        this.fadeInElements('.dashboard-welcome-card, .stats-card, .mini-stats-card');
    }

    /**
     * Destroy all animations and clear resources
     */
    destroy() {
        this.counters = [];
        
        // Remove hover events
        const cards = document.querySelectorAll('.stats-card, .mini-stats-card, .quick-action-item');
        cards.forEach(card => {
            card.removeEventListener('mouseenter', null);
            card.removeEventListener('mouseleave', null);
        });
    }
}

// Export for module usage
if (typeof module !== 'undefined' && module.exports) {
    module.exports = DashboardAnimations;
} else {
    window.DashboardAnimations = DashboardAnimations;
} 