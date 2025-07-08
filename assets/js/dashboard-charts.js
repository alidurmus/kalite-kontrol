/**
 * Dashboard Charts Module
 * Handles Chart.js initialization and chart management
 */

class DashboardCharts {
    constructor() {
        this.charts = new Map();
        this.defaultColors = {
            primary: '#007bff',
            success: '#28a745', 
            danger: '#dc3545',
            warning: '#ffc107',
            info: '#17a2b8'
        };
    }

    /**
     * Initialize quality control statistics chart
     * @param {string} canvasId - Canvas element ID
     * @param {Object} data - Chart data object
     */
    initQualityChart(canvasId = 'qualityChart', data = {}) {
        const canvas = document.getElementById(canvasId);
        if (!canvas) {
            console.warn(`Canvas element #${canvasId} not found`);
            return null;
        }

        const ctx = canvas.getContext('2d');
        const chartConfig = {
            type: 'doughnut',
            data: {
                labels: ['Girdi Kontrol', 'Proses Kontrol', 'Final Kontrol'],
                datasets: [{
                    data: [
                        data.girdi_kontrol || 0,
                        data.proses_kontrol || 0,
                        data.final_kontrol || 0
                    ],
                    backgroundColor: [
                        this.defaultColors.primary,
                        this.defaultColors.success,
                        this.defaultColors.danger
                    ],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true,
                            font: {
                                size: 12
                            }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.parsed;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((value / total) * 100).toFixed(1);
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                },
                animation: {
                    animateRotate: true,
                    duration: 1500
                }
            }
        };

        const chart = new Chart(ctx, chartConfig);
        this.charts.set(canvasId, chart);
        return chart;
    }

    /**
     * Initialize performance metrics chart
     * @param {string} canvasId - Canvas element ID
     * @param {Object} data - Performance data
     */
    initPerformanceChart(canvasId = 'performanceChart', data = {}) {
        const canvas = document.getElementById(canvasId);
        if (!canvas) {
            console.warn(`Canvas element #${canvasId} not found`);
            return null;
        }

        const ctx = canvas.getContext('2d');
        const chartConfig = {
            type: 'bar',
            data: {
                labels: ['Kalite Başarı', 'Zamanında Teslimat', 'Müşteri Memnuniyeti'],
                datasets: [{
                    label: 'Performans %',
                    data: [
                        data.quality_success || 95,
                        data.delivery_ontime || 88,
                        data.customer_satisfaction || 92
                    ],
                    backgroundColor: [
                        this.defaultColors.success,
                        this.defaultColors.warning,
                        this.defaultColors.info
                    ],
                    borderRadius: 8,
                    borderSkipped: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        ticks: {
                            callback: function(value) {
                                return value + '%';
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `${context.dataset.label}: ${context.parsed.y}%`;
                            }
                        }
                    }
                },
                animation: {
                    duration: 1500,
                    easing: 'easeOutQuart'
                }
            }
        };

        const chart = new Chart(ctx, chartConfig);
        this.charts.set(canvasId, chart);
        return chart;
    }

    /**
     * Update chart data
     * @param {string} chartId - Chart identifier
     * @param {Array} newData - New data array
     */
    updateChartData(chartId, newData) {
        const chart = this.charts.get(chartId);
        if (chart && newData) {
            chart.data.datasets[0].data = newData;
            chart.update('active');
        }
    }

    /**
     * Add chart controls for time period filtering
     * @param {string} chartId - Chart identifier
     * @param {string} controlsSelector - Controls container selector
     */
    addChartControls(chartId, controlsSelector = '.chart-controls') {
        const controlsContainer = document.querySelector(controlsSelector);
        if (!controlsContainer) return;

        const periods = [
            { label: 'Haftalık', value: 'weekly' },
            { label: 'Aylık', value: 'monthly' },
            { label: 'Yıllık', value: 'yearly' }
        ];

        const buttonsHTML = periods.map(period => 
            `<button class="btn btn-sm btn-outline-primary chart-filter" data-period="${period.value}">
                ${period.label}
            </button>`
        ).join('');

        controlsContainer.innerHTML = buttonsHTML;

        // Add click events
        controlsContainer.addEventListener('click', (e) => {
            if (e.target.classList.contains('chart-filter')) {
                const period = e.target.dataset.period;
                this.filterChartByPeriod(chartId, period);
                
                // Update active button
                controlsContainer.querySelectorAll('.chart-filter').forEach(btn => {
                    btn.classList.remove('btn-primary');
                    btn.classList.add('btn-outline-primary');
                });
                e.target.classList.remove('btn-outline-primary');
                e.target.classList.add('btn-primary');
            }
        });
    }

    /**
     * Filter chart data by time period
     * @param {string} chartId - Chart identifier
     * @param {string} period - Time period (weekly, monthly, yearly)
     */
    filterChartByPeriod(chartId, period) {
        // This would typically fetch new data from server
        console.log(`Filtering chart ${chartId} by period: ${period}`);
        
        // Placeholder for actual implementation
        const chart = this.charts.get(chartId);
        if (chart) {
            // Simulate data loading
            chart.options.plugins.tooltip.enabled = false;
            chart.update();
            
            setTimeout(() => {
                chart.options.plugins.tooltip.enabled = true;
                chart.update();
            }, 500);
        }
    }

    /**
     * Destroy specific chart
     * @param {string} chartId - Chart identifier
     */
    destroyChart(chartId) {
        const chart = this.charts.get(chartId);
        if (chart) {
            chart.destroy();
            this.charts.delete(chartId);
        }
    }

    /**
     * Destroy all charts
     */
    destroyAll() {
        this.charts.forEach(chart => chart.destroy());
        this.charts.clear();
    }

    /**
     * Initialize all dashboard charts
     * @param {Object} data - Chart data object
     */
    init(data = {}) {
        // Initialize quality chart
        this.initQualityChart('qualityChart', data);
        
        // Initialize performance chart if canvas exists
        this.initPerformanceChart('performanceChart', data);
        
        // Add chart controls
        this.addChartControls('qualityChart');
        
        console.log('Dashboard charts initialized');
    }
}

// Export for module usage
if (typeof module !== 'undefined' && module.exports) {
    module.exports = DashboardCharts;
} else {
    window.DashboardCharts = DashboardCharts;
} 