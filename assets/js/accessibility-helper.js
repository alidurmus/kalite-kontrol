/**
 * QMS Accessibility Helper
 * 
 * Keyboard navigation, ARIA labels, and accessibility enhancements
 */

class AccessibilityHelper {
    constructor() {
        this.init();
    }

    /**
     * Initialize accessibility enhancements
     */
    init() {
        this.addAriaLabels();
        this.setupKeyboardNavigation();
        this.enhanceFormAccessibility();
        this.addSkipLinks();
        this.setupFocusManagement();
        this.announcePageChanges();
    }

    /**
     * Add ARIA labels to elements
     */
    addAriaLabels() {
        // Tables
        const tables = document.querySelectorAll('table');
        tables.forEach((table, index) => {
            if (!table.getAttribute('aria-label')) {
                const caption = table.querySelector('caption');
                const title = caption ? caption.textContent : `Tablo ${index + 1}`;
                table.setAttribute('aria-label', title);
                table.setAttribute('role', 'table');
            }
        });

        // Buttons without aria-label
        const buttons = document.querySelectorAll('button:not([aria-label])');
        buttons.forEach(button => {
            const text = button.textContent.trim();
            const icon = button.querySelector('i');
            
            if (!text && icon) {
                // Button with only icon
                const iconClass = icon.className;
                let label = 'Buton';
                
                if (iconClass.includes('edit') || iconClass.includes('pencil')) {
                    label = 'Düzenle';
                } else if (iconClass.includes('delete') || iconClass.includes('trash')) {
                    label = 'Sil';
                } else if (iconClass.includes('plus') || iconClass.includes('add')) {
                    label = 'Ekle';
                } else if (iconClass.includes('search')) {
                    label = 'Ara';
                } else if (iconClass.includes('save') || iconClass.includes('check')) {
                    label = 'Kaydet';
                }
                
                button.setAttribute('aria-label', label);
            }
        });

        // Form inputs
        const inputs = document.querySelectorAll('input:not([aria-label]):not([aria-labelledby])');
        inputs.forEach(input => {
            const label = input.closest('.form-group')?.querySelector('label');
            const placeholder = input.getAttribute('placeholder');
            
            if (label) {
                const labelId = `label-${Math.random().toString(36).substr(2, 9)}`;
                label.id = labelId;
                input.setAttribute('aria-labelledby', labelId);
            } else if (placeholder) {
                input.setAttribute('aria-label', placeholder);
            }
        });

        // Links
        const links = document.querySelectorAll('a:not([aria-label])');
        links.forEach(link => {
            const text = link.textContent.trim();
            if (!text) {
                const icon = link.querySelector('i');
                if (icon) {
                    link.setAttribute('aria-label', 'Bağlantı');
                }
            }
        });
    }

    /**
     * Setup keyboard navigation
     */
    setupKeyboardNavigation() {
        // Table keyboard navigation
        const tables = document.querySelectorAll('table');
        tables.forEach(table => {
            this.makeTableKeyboardAccessible(table);
        });

        // Modal keyboard navigation
        const modals = document.querySelectorAll('.modal');
        modals.forEach(modal => {
            this.makeModalKeyboardAccessible(modal);
        });

        // Dropdown keyboard navigation
        const dropdowns = document.querySelectorAll('.dropdown');
        dropdowns.forEach(dropdown => {
            this.makeDropdownKeyboardAccessible(dropdown);
        });

        // Global keyboard shortcuts
        document.addEventListener('keydown', (e) => {
            // Alt + M: Main navigation
            if (e.altKey && e.key === 'm') {
                e.preventDefault();
                const mainNav = document.querySelector('.navbar-nav, .app-menu');
                if (mainNav) {
                    const firstLink = mainNav.querySelector('a');
                    if (firstLink) firstLink.focus();
                }
            }

            // Alt + C: Main content
            if (e.altKey && e.key === 'c') {
                e.preventDefault();
                const mainContent = document.querySelector('main, .container, .content');
                if (mainContent) {
                    mainContent.setAttribute('tabindex', '-1');
                    mainContent.focus();
                }
            }

            // Escape: Close modals/dropdowns
            if (e.key === 'Escape') {
                this.closeOpenElements();
            }
        });
    }

    /**
     * Make table keyboard accessible
     */
    makeTableKeyboardAccessible(table) {
        const cells = table.querySelectorAll('td, th');
        
        cells.forEach((cell, index) => {
            cell.setAttribute('tabindex', '0');
            
            cell.addEventListener('keydown', (e) => {
                const currentRow = cell.parentNode;
                const currentCellIndex = Array.from(currentRow.children).indexOf(cell);
                const rows = Array.from(table.querySelectorAll('tr'));
                const currentRowIndex = rows.indexOf(currentRow);
                
                let targetCell = null;
                
                switch (e.key) {
                    case 'ArrowRight':
                        targetCell = currentRow.children[currentCellIndex + 1];
                        break;
                    case 'ArrowLeft':
                        targetCell = currentRow.children[currentCellIndex - 1];
                        break;
                    case 'ArrowDown':
                        const nextRow = rows[currentRowIndex + 1];
                        targetCell = nextRow?.children[currentCellIndex];
                        break;
                    case 'ArrowUp':
                        const prevRow = rows[currentRowIndex - 1];
                        targetCell = prevRow?.children[currentCellIndex];
                        break;
                    case 'Home':
                        targetCell = currentRow.children[0];
                        break;
                    case 'End':
                        targetCell = currentRow.children[currentRow.children.length - 1];
                        break;
                }
                
                if (targetCell) {
                    e.preventDefault();
                    targetCell.focus();
                }
            });
        });
    }

    /**
     * Make modal keyboard accessible
     */
    makeModalKeyboardAccessible(modal) {
        modal.addEventListener('shown.bs.modal', () => {
            const focusableElements = modal.querySelectorAll(
                'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
            );
            
            if (focusableElements.length > 0) {
                focusableElements[0].focus();
            }
            
            // Trap focus within modal
            modal.addEventListener('keydown', (e) => {
                if (e.key === 'Tab') {
                    const firstElement = focusableElements[0];
                    const lastElement = focusableElements[focusableElements.length - 1];
                    
                    if (e.shiftKey && document.activeElement === firstElement) {
                        e.preventDefault();
                        lastElement.focus();
                    } else if (!e.shiftKey && document.activeElement === lastElement) {
                        e.preventDefault();
                        firstElement.focus();
                    }
                }
            });
        });
    }

    /**
     * Make dropdown keyboard accessible
     */
    makeDropdownKeyboardAccessible(dropdown) {
        const toggle = dropdown.querySelector('.dropdown-toggle');
        const menu = dropdown.querySelector('.dropdown-menu');
        
        if (!toggle || !menu) return;
        
        toggle.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowDown' || e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                if (!dropdown.classList.contains('open')) {
                    $(dropdown).dropdown('toggle');
                }
                const firstItem = menu.querySelector('a, button');
                if (firstItem) firstItem.focus();
            }
        });
        
        const menuItems = menu.querySelectorAll('a, button');
        menuItems.forEach((item, index) => {
            item.addEventListener('keydown', (e) => {
                switch (e.key) {
                    case 'ArrowDown':
                        e.preventDefault();
                        const nextItem = menuItems[index + 1] || menuItems[0];
                        nextItem.focus();
                        break;
                    case 'ArrowUp':
                        e.preventDefault();
                        const prevItem = menuItems[index - 1] || menuItems[menuItems.length - 1];
                        prevItem.focus();
                        break;
                    case 'Escape':
                        e.preventDefault();
                        $(dropdown).dropdown('toggle');
                        toggle.focus();
                        break;
                }
            });
        });
    }

    /**
     * Enhance form accessibility
     */
    enhanceFormAccessibility() {
        // Required field indicators
        const requiredInputs = document.querySelectorAll('input[required], select[required], textarea[required]');
        requiredInputs.forEach(input => {
            input.setAttribute('aria-required', 'true');
            
            const label = document.querySelector(`label[for="${input.id}"]`) || 
                         input.closest('.form-group')?.querySelector('label');
            
            if (label && !label.querySelector('.required-indicator')) {
                const indicator = document.createElement('span');
                indicator.className = 'required-indicator';
                indicator.textContent = ' *';
                indicator.setAttribute('aria-label', 'gerekli alan');
                label.appendChild(indicator);
            }
        });

        // Form validation messages
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            form.addEventListener('submit', (e) => {
                const invalidInputs = form.querySelectorAll(':invalid');
                if (invalidInputs.length > 0) {
                    invalidInputs[0].focus();
                    this.announceMessage('Form hatası: Lütfen gerekli alanları doldurun', 'error');
                }
            });
        });
    }

    /**
     * Add skip links
     */
    addSkipLinks() {
        if (document.querySelector('.skip-links')) return;
        
        const skipLinks = document.createElement('div');
        skipLinks.className = 'skip-links';
        skipLinks.innerHTML = `
            <a href="#main-content" class="skip-link">Ana içeriğe geç</a>
            <a href="#main-navigation" class="skip-link">Ana navigasyona geç</a>
        `;
        
        // Add skip link styles
        const style = document.createElement('style');
        style.textContent = `
            .skip-links {
                position: absolute;
                top: -40px;
                left: 6px;
                z-index: 10000;
            }
            .skip-link {
                position: absolute;
                top: -40px;
                left: 6px;
                background: #000;
                color: #fff;
                padding: 8px;
                text-decoration: none;
                border-radius: 0 0 4px 4px;
                font-size: 14px;
                transition: top 0.3s;
            }
            .skip-link:focus {
                top: 0;
                color: #fff;
            }
        `;
        
        document.head.appendChild(style);
        document.body.insertBefore(skipLinks, document.body.firstChild);
    }

    /**
     * Setup focus management
     */
    setupFocusManagement() {
        // Focus visible indicator
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Tab') {
                document.body.classList.add('keyboard-navigation');
            }
        });
        
        document.addEventListener('mousedown', () => {
            document.body.classList.remove('keyboard-navigation');
        });
        
        // Add focus styles for keyboard navigation
        const style = document.createElement('style');
        style.textContent = `
            body:not(.keyboard-navigation) *:focus {
                outline: none;
            }
            .keyboard-navigation *:focus {
                outline: 2px solid #007bff;
                outline-offset: 2px;
            }
        `;
        document.head.appendChild(style);
    }

    /**
     * Announce page changes to screen readers
     */
    announcePageChanges() {
        // Create live region for announcements
        if (!document.getElementById('aria-live-region')) {
            const liveRegion = document.createElement('div');
            liveRegion.id = 'aria-live-region';
            liveRegion.setAttribute('aria-live', 'polite');
            liveRegion.setAttribute('aria-atomic', 'true');
            liveRegion.style.cssText = `
                position: absolute;
                left: -10000px;
                width: 1px;
                height: 1px;
                overflow: hidden;
            `;
            document.body.appendChild(liveRegion);
        }
    }

    /**
     * Announce message to screen readers
     */
    announceMessage(message, priority = 'polite') {
        const liveRegion = document.getElementById('aria-live-region');
        if (liveRegion) {
            liveRegion.setAttribute('aria-live', priority);
            liveRegion.textContent = message;
            
            // Clear after announcement
            setTimeout(() => {
                liveRegion.textContent = '';
            }, 1000);
        }
    }

    /**
     * Close open elements (modals, dropdowns)
     */
    closeOpenElements() {
        // Close modals
        const openModals = document.querySelectorAll('.modal.show');
        openModals.forEach(modal => {
            $(modal).modal('hide');
        });
        
        // Close dropdowns
        const openDropdowns = document.querySelectorAll('.dropdown.open');
        openDropdowns.forEach(dropdown => {
            $(dropdown).dropdown('toggle');
        });
        
        // Close notifications
        const notifications = document.querySelectorAll('.qms-notification');
        notifications.forEach(notification => {
            if (window.qmsLoading) {
                window.qmsLoading.closeNotification(notification);
            }
        });
    }

    /**
     * Update page title for dynamic content
     */
    updatePageTitle(title) {
        document.title = title;
        this.announceMessage(`Sayfa değişti: ${title}`);
    }

    /**
     * Add landmark roles
     */
    addLandmarkRoles() {
        // Main content
        const mainContent = document.querySelector('.container, .content, main');
        if (mainContent && !mainContent.getAttribute('role')) {
            mainContent.setAttribute('role', 'main');
            mainContent.id = 'main-content';
        }
        
        // Navigation
        const navigation = document.querySelector('.navbar, .app-menu');
        if (navigation && !navigation.getAttribute('role')) {
            navigation.setAttribute('role', 'navigation');
            navigation.id = 'main-navigation';
        }
        
        // Search
        const searchForm = document.querySelector('form[action*="search"], .search-form');
        if (searchForm && !searchForm.getAttribute('role')) {
            searchForm.setAttribute('role', 'search');
        }
    }
}

// Initialize accessibility helper when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    window.qmsAccessibility = new AccessibilityHelper();
}); 