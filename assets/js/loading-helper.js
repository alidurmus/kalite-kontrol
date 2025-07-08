/**
 * Loading Helper - QMS Loading States & Notifications
 * 
 * Ajax işlemlerde loading spinner'lar ve notification sistemi
 */

class LoadingHelper {
    constructor() {
        this.loadingClass = 'qms-loading';
        this.spinnerClass = 'qms-spinner';
        this.notificationContainer = null;
        this.init();
    }

    /**
     * Initialize loading helper
     */
    init() {
        this.createNotificationContainer();
        this.addLoadingStyles();
        this.setupAjaxDefaults();
    }

    /**
     * Create notification container
     */
    createNotificationContainer() {
        if (!document.getElementById('qms-notifications')) {
            const container = document.createElement('div');
            container.id = 'qms-notifications';
            container.className = 'qms-notification-container';
            document.body.appendChild(container);
            this.notificationContainer = container;
        }
    }

    /**
     * Add loading CSS styles
     */
    addLoadingStyles() {
        if (!document.getElementById('qms-loading-styles')) {
            const style = document.createElement('style');
            style.id = 'qms-loading-styles';
            style.textContent = `
                .qms-loading {
                    position: relative;
                    pointer-events: none;
                    opacity: 0.6;
                }

                .qms-spinner {
                    position: absolute;
                    top: 50%;
                    left: 50%;
                    transform: translate(-50%, -50%);
                    width: 32px;
                    height: 32px;
                    border: 3px solid #f3f3f3;
                    border-top: 3px solid #007bff;
                    border-radius: 50%;
                    animation: qms-spin 1s linear infinite;
                    z-index: 9999;
                }

                @keyframes qms-spin {
                    0% { transform: translate(-50%, -50%) rotate(0deg); }
                    100% { transform: translate(-50%, -50%) rotate(360deg); }
                }

                .qms-notification-container {
                    position: fixed;
                    top: 20px;
                    right: 20px;
                    z-index: 10000;
                    max-width: 400px;
                }

                .qms-notification {
                    background: #fff;
                    border-radius: 6px;
                    padding: 16px;
                    margin-bottom: 10px;
                    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                    border-left: 4px solid #007bff;
                    animation: qms-slideIn 0.3s ease-out;
                    position: relative;
                }

                .qms-notification.success {
                    border-left-color: #28a745;
                }

                .qms-notification.error {
                    border-left-color: #dc3545;
                }

                .qms-notification.warning {
                    border-left-color: #ffc107;
                }

                .qms-notification.info {
                    border-left-color: #17a2b8;
                }

                .qms-notification-title {
                    font-weight: 600;
                    margin-bottom: 4px;
                    color: #333;
                }

                .qms-notification-message {
                    color: #666;
                    font-size: 14px;
                }

                .qms-notification-close {
                    position: absolute;
                    top: 8px;
                    right: 12px;
                    background: none;
                    border: none;
                    font-size: 18px;
                    cursor: pointer;
                    color: #999;
                    line-height: 1;
                }

                .qms-notification-close:hover {
                    color: #333;
                }

                @keyframes qms-slideIn {
                    from {
                        transform: translateX(100%);
                        opacity: 0;
                    }
                    to {
                        transform: translateX(0);
                        opacity: 1;
                    }
                }

                @keyframes qms-slideOut {
                    from {
                        transform: translateX(0);
                        opacity: 1;
                    }
                    to {
                        transform: translateX(100%);
                        opacity: 0;
                    }
                }

                .qms-btn-loading {
                    position: relative;
                    pointer-events: none;
                }

                .qms-btn-loading::after {
                    content: '';
                    position: absolute;
                    width: 16px;
                    height: 16px;
                    margin: auto;
                    border: 2px solid transparent;
                    border-top-color: currentColor;
                    border-radius: 50%;
                    animation: qms-spin 1s linear infinite;
                    top: 0;
                    left: 0;
                    bottom: 0;
                    right: 0;
                }
            `;
            document.head.appendChild(style);
        }
    }

    /**
     * Setup default Ajax settings
     */
    setupAjaxDefaults() {
        // jQuery Ajax defaults (eğer jQuery varsa)
        if (typeof $ !== 'undefined') {
            $(document).ajaxStart(() => {
                this.showPageLoading();
            }).ajaxStop(() => {
                this.hidePageLoading();
            });
        }
    }

    /**
     * Show loading state on element
     */
    showLoading(element) {
        if (typeof element === 'string') {
            element = document.querySelector(element);
        }
        
        if (element) {
            element.classList.add(this.loadingClass);
            
            // Add spinner if not exists
            if (!element.querySelector(`.${this.spinnerClass}`)) {
                const spinner = document.createElement('div');
                spinner.className = this.spinnerClass;
                element.appendChild(spinner);
            }
        }
    }

    /**
     * Hide loading state from element
     */
    hideLoading(element) {
        if (typeof element === 'string') {
            element = document.querySelector(element);
        }
        
        if (element) {
            element.classList.remove(this.loadingClass);
            
            // Remove spinner
            const spinner = element.querySelector(`.${this.spinnerClass}`);
            if (spinner) {
                spinner.remove();
            }
        }
    }

    /**
     * Show button loading state
     */
    showButtonLoading(button, originalText = null) {
        if (typeof button === 'string') {
            button = document.querySelector(button);
        }
        
        if (button) {
            if (originalText) {
                button.setAttribute('data-original-text', button.textContent);
                button.textContent = originalText;
            }
            button.classList.add('qms-btn-loading');
            button.disabled = true;
        }
    }

    /**
     * Hide button loading state
     */
    hideButtonLoading(button) {
        if (typeof button === 'string') {
            button = document.querySelector(button);
        }
        
        if (button) {
            button.classList.remove('qms-btn-loading');
            button.disabled = false;
            
            const originalText = button.getAttribute('data-original-text');
            if (originalText) {
                button.textContent = originalText;
                button.removeAttribute('data-original-text');
            }
        }
    }

    /**
     * Show page-wide loading
     */
    showPageLoading() {
        if (!document.getElementById('qms-page-loading')) {
            const overlay = document.createElement('div');
            overlay.id = 'qms-page-loading';
            overlay.style.cssText = `
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(255,255,255,0.8);
                z-index: 9998;
                display: flex;
                align-items: center;
                justify-content: center;
            `;
            
            const spinner = document.createElement('div');
            spinner.className = 'qms-spinner';
            spinner.style.position = 'relative';
            spinner.style.transform = 'none';
            
            overlay.appendChild(spinner);
            document.body.appendChild(overlay);
        }
    }

    /**
     * Hide page-wide loading
     */
    hidePageLoading() {
        const overlay = document.getElementById('qms-page-loading');
        if (overlay) {
            overlay.remove();
        }
    }

    /**
     * Show notification
     */
    showNotification(message, type = 'info', title = '', duration = 5000) {
        const notification = document.createElement('div');
        notification.className = `qms-notification ${type}`;
        
        const closeButton = document.createElement('button');
        closeButton.className = 'qms-notification-close';
        closeButton.innerHTML = '×';
        closeButton.onclick = () => this.closeNotification(notification);
        
        let content = '';
        if (title) {
            content += `<div class="qms-notification-title">${title}</div>`;
        }
        content += `<div class="qms-notification-message">${message}</div>`;
        
        notification.innerHTML = content;
        notification.appendChild(closeButton);
        
        this.notificationContainer.appendChild(notification);
        
        // Auto close
        if (duration > 0) {
            setTimeout(() => {
                this.closeNotification(notification);
            }, duration);
        }
        
        return notification;
    }

    /**
     * Close notification
     */
    closeNotification(notification) {
        notification.style.animation = 'qms-slideOut 0.3s ease-out';
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }

    /**
     * Show success notification
     */
    success(message, title = 'Başarılı') {
        return this.showNotification(message, 'success', title);
    }

    /**
     * Show error notification
     */
    error(message, title = 'Hata') {
        return this.showNotification(message, 'error', title);
    }

    /**
     * Show warning notification
     */
    warning(message, title = 'Uyarı') {
        return this.showNotification(message, 'warning', title);
    }

    /**
     * Show info notification
     */
    info(message, title = 'Bilgi') {
        return this.showNotification(message, 'info', title);
    }

    /**
     * Ajax helper with loading states
     */
    ajax(options) {
        const defaults = {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            loadingElement: null,
            loadingButton: null,
            showNotifications: true
        };

        options = Object.assign(defaults, options);

        // Show loading
        if (options.loadingElement) {
            this.showLoading(options.loadingElement);
        }
        if (options.loadingButton) {
            this.showButtonLoading(options.loadingButton, 'İşleniyor...');
        }

        return fetch(options.url, {
            method: options.method,
            headers: options.headers,
            body: options.data
        })
        .then(response => response.json())
        .then(data => {
            // Hide loading
            if (options.loadingElement) {
                this.hideLoading(options.loadingElement);
            }
            if (options.loadingButton) {
                this.hideButtonLoading(options.loadingButton);
            }

            // Show notifications
            if (options.showNotifications) {
                if (data.success) {
                    this.success(data.message || 'İşlem başarılı');
                } else {
                    this.error(data.error || 'İşlem başarısız');
                }
            }

            if (options.success) {
                options.success(data);
            }

            return data;
        })
        .catch(error => {
            // Hide loading
            if (options.loadingElement) {
                this.hideLoading(options.loadingElement);
            }
            if (options.loadingButton) {
                this.hideButtonLoading(options.loadingButton);
            }

            // Show error notification
            if (options.showNotifications) {
                this.error('Bağlantı hatası oluştu');
            }

            if (options.error) {
                options.error(error);
            }

            throw error;
        });
    }
}

// Global instance
window.LoadingHelper = new LoadingHelper();

// Shorthand methods
window.showLoading = (element) => LoadingHelper.showLoading(element);
window.hideLoading = (element) => LoadingHelper.hideLoading(element);
window.showNotification = (message, type, title) => LoadingHelper.showNotification(message, type, title);
window.notify = {
    success: (message, title) => LoadingHelper.success(message, title),
    error: (message, title) => LoadingHelper.error(message, title),
    warning: (message, title) => LoadingHelper.warning(message, title),
    info: (message, title) => LoadingHelper.info(message, title)
}; 