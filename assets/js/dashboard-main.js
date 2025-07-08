/**
 * Main Dashboard Module
 * Orchestrates all dashboard functionality
 */

class Dashboard {
    constructor(options = {}) {
        this.options = {
            clockSelector: '.dashboard-time',
            chartData: {},
            enableAnimations: true,
            enableDataTables: true,
            ...options
        };
        
        this.modules = {
            clock: null,
            animations: null,
            charts: null
        };
        
        this.isInitialized = false;
    }

    /**
     * Initialize all dashboard modules
     */
    async init() {
        console.log('Initializing Dashboard...');
        
        try {
            // Initialize clock
            if (typeof DashboardClock !== 'undefined' && this.options.clockSelector) {
                this.modules.clock = new DashboardClock(this.options.clockSelector);
                this.modules.clock.init();
            }
            
            // Initialize animations
            if (typeof DashboardAnimations !== 'undefined' && this.options.enableAnimations) {
                this.modules.animations = new DashboardAnimations();
                this.modules.animations.init();
            }
            
            // Initialize charts
            if (typeof DashboardCharts !== 'undefined') {
                this.modules.charts = new DashboardCharts();
                this.modules.charts.init(this.options.chartData);
            }
            
            // Initialize DataTables
            if (this.options.enableDataTables) {
                this.initDataTables();
            }
            
            // Setup refresh functionality
            this.setupRefresh();
            
            // Setup event listeners
            this.setupEventListeners();
            
            this.isInitialized = true;
            console.log('Dashboard initialized successfully');
            
        } catch (error) {
            console.error('Dashboard initialization failed:', error);
        }
    }

    /**
     * Initialize DataTables
     */
    initDataTables() {
        if (typeof $.fn.DataTable !== 'undefined') {
            $('#controlTable').DataTable({
                responsive: true,
                pageLength: 10,
                order: [[5, 'desc']],
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.11.5/i18n/tr.json'
                },
                dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rtip',
                initComplete: function() {
                    console.log('DataTable initialized');
                }
            });
        }
    }

    /**
     * Setup dashboard refresh functionality
     */
    setupRefresh() {
        const refreshBtn = document.querySelector('.dashboard-refresh');
        if (refreshBtn) {
            refreshBtn.addEventListener('click', () => {
                this.refresh();
            });
        }
        
        // Auto-refresh every 5 minutes
        setInterval(() => {
            this.refreshData();
        }, 5 * 60 * 1000);
    }

    /**
     * Setup global event listeners
     */
    setupEventListeners() {
        // Handle window resize
        window.addEventListener('resize', this.debounce(() => {
            if (this.modules.charts) {
                // Refresh charts on resize
                Object.values(this.modules.charts.charts).forEach(chart => {
                    chart.resize();
                });
            }
        }, 250));
        
        // Handle visibility change
        document.addEventListener('visibilitychange', () => {
            if (document.hidden) {
                // Pause unnecessary updates when tab is not visible
                if (this.modules.clock) {
                    this.modules.clock.stopClock();
                }
            } else {
                // Resume updates when tab becomes visible
                if (this.modules.clock) {
                    this.modules.clock.startClock();
                }
            }
        });
    }

    /**
     * Refresh dashboard data
     */
    async refreshData() {
        try {
            // Show loading state
            this.showLoading();
            
            // Fetch fresh data (placeholder)
            const response = await this.fetchDashboardData();
            
            if (response && this.modules.charts) {
                // Update charts with new data
                this.modules.charts.updateChartData('qualityChart', response.chartData);
            }
            
            // Hide loading state
            this.hideLoading();
            
            console.log('Dashboard data refreshed');
            
        } catch (error) {
            console.error('Failed to refresh dashboard data:', error);
            this.hideLoading();
        }
    }

    /**
     * Fetch dashboard data from server
     */
    async fetchDashboardData() {
        // Placeholder for actual API call
        return new Promise((resolve) => {
            setTimeout(() => {
                resolve({
                    chartData: [
                        Math.floor(Math.random() * 100),
                        Math.floor(Math.random() * 100),
                        Math.floor(Math.random() * 100)
                    ]
                });
            }, 1000);
        });
    }

    /**
     * Show loading state
     */
    showLoading() {
        const refreshBtn = document.querySelector('.dashboard-refresh');
        if (refreshBtn) {
            const icon = refreshBtn.querySelector('i');
            if (icon) {
                icon.classList.add('zmdi-hc-spin');
            }
            refreshBtn.disabled = true;
        }
    }

    /**
     * Hide loading state
     */
    hideLoading() {
        const refreshBtn = document.querySelector('.dashboard-refresh');
        if (refreshBtn) {
            const icon = refreshBtn.querySelector('i');
            if (icon) {
                icon.classList.remove('zmdi-hc-spin');
            }
            refreshBtn.disabled = false;
        }
    }

    /**
     * Full dashboard refresh
     */
    refresh() {
        console.log('Refreshing dashboard...');
        
        // Refresh animations
        if (this.modules.animations) {
            this.modules.animations.refreshAOS();
        }
        
        // Refresh data
        this.refreshData();
    }

    /**
     * Debounce utility function
     */
    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    /**
     * Destroy dashboard and cleanup
     */
    destroy() {
        // Destroy all modules
        Object.values(this.modules).forEach(module => {
            if (module && typeof module.destroy === 'function') {
                module.destroy();
            }
        });
        
        // Clear modules
        this.modules = {};
        this.isInitialized = false;
        
        console.log('Dashboard destroyed');
    }
}

// Auto-initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    // Get chart data from PHP (if available)
    const chartData = window.dashboardData || {
        girdi_kontrol: 60,
        proses_kontrol: 30,
        final_kontrol: 31
    };
    
    // Initialize dashboard
    const dashboard = new Dashboard({
        chartData: chartData,
        enableAnimations: true,
        enableDataTables: true
    });
    
    dashboard.init();
    
    // Make dashboard available globally
    window.dashboard = dashboard;
});

// Export for module usage
if (typeof module !== 'undefined' && module.exports) {
    module.exports = Dashboard;
} else {
    window.Dashboard = Dashboard;
} 