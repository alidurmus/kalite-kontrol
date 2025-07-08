/**
 * Dashboard Clock Module
 * Real-time clock display for dashboard
 */

class DashboardClock {
    constructor(selector) {
        this.element = document.querySelector(selector);
        this.locale = 'tr-TR';
        this.options = {
            weekday: 'long',
            year: 'numeric', 
            month: 'long',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        };
        this.intervalId = null;
    }

    init() {
        if (!this.element) {
            console.warn('Clock element not found');
            return;
        }
        
        this.updateTime();
        this.startClock();
    }

    updateTime() {
        const now = new Date();
        const timeString = now.toLocaleDateString(this.locale, this.options);
        
        if (this.element) {
            this.element.textContent = timeString;
        }
    }

    startClock() {
        // Update every second
        this.intervalId = setInterval(() => {
            this.updateTime();
        }, 1000);
    }

    stopClock() {
        if (this.intervalId) {
            clearInterval(this.intervalId);
            this.intervalId = null;
        }
    }

    destroy() {
        this.stopClock();
        this.element = null;
    }
}

// Export for module usage
if (typeof module !== 'undefined' && module.exports) {
    module.exports = DashboardClock;
} else {
    window.DashboardClock = DashboardClock;
} 