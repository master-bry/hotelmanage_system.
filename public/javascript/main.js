// Main application JavaScript - Common functionality across all pages
document.addEventListener('DOMContentLoaded', function() {
    // Initialize common components
    initializeCommonComponents();
    
    // Initialize page-specific functionality
    initializePageSpecificFunctionality();

    function initializeCommonComponents() {
        // Initialize tooltips
        initializeTooltips();

        // Initialize loading states
        initializeLoadingStates();

        // Initialize navigation
        initializeNavigation();

        // Initialize flash messages
        initializeGlobalFlashMessages();
    }

    function initializeTooltips() {
        // Bootstrap tooltip initialization if available
        if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        }
    }

    function initializeLoadingStates() {
        // Add loading states to all submit buttons
        document.addEventListener('submit', function(e) {
            const form = e.target;
            const submitBtn = form.querySelector('button[type="submit"]');
            
            if (submitBtn && !form.classList.contains('no-loading')) {
                submitBtn.classList.add('loading');
                submitBtn.disabled = true;
                
                // Re-enable after 30 seconds timeout (safety measure)
                setTimeout(() => {
                    submitBtn.classList.remove('loading');
                    submitBtn.disabled = false;
                }, 30000);
            }
        });

        // Reset loading state on form reset
        document.addEventListener('reset', function(e) {
            const form = e.target;
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.classList.remove('loading');
                submitBtn.disabled = false;
            }
        });
    }

    function initializeNavigation() {
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Mobile menu toggle (if needed)
        const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
        if (mobileMenuToggle) {
            mobileMenuToggle.addEventListener('click', function() {
                const nav = document.querySelector('nav');
                nav.classList.toggle('mobile-open');
            });
        }
    }

    function initializeGlobalFlashMessages() {
        // Check for global flash messages
        if (typeof swal === 'function') {
            // Success messages
            const successElements = document.querySelectorAll('[data-flash-success]');
            successElements.forEach(el => {
                swal({
                    title: 'Success',
                    text: el.getAttribute('data-flash-success'),
                    icon: 'success'
                });
                el.remove();
            });

            // Error messages
            const errorElements = document.querySelectorAll('[data-flash-error]');
            errorElements.forEach(el => {
                swal({
                    title: 'Error',
                    text: el.getAttribute('data-flash-error'),
                    icon: 'error'
                });
                el.remove();
            });
        }
    }

    function initializePageSpecificFunctionality() {
        const body = document.body;
        
        // Home page specific functionality
        if (body.classList.contains('home-page') || body.id === 'home-page') {
            initializeHomePage();
        }
        
        // Admin page specific functionality
        if (body.classList.contains('admin-page') || body.id === 'admin-page') {
            initializeAdminPage();
        }
        
        // Auth page specific functionality
        if (body.classList.contains('auth-page') || body.id === 'auth-page') {
            initializeAuthPage();
        }
    }

    function initializeHomePage() {
        // Room booking modal functionality
        const bookButtons = document.querySelectorAll('[data-booking-toggle]');
        const bookingModal = document.getElementById('guestdetailpanel');
        
        if (bookingModal) {
            bookButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    bookingModal.style.display = 'flex';
                });
            });

            // Close modal functionality
            const closeButtons = bookingModal.querySelectorAll('[data-close-modal]');
            closeButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    bookingModal.style.display = 'none';
                });
            });

            // Close modal when clicking outside
            bookingModal.addEventListener('click', function(e) {
                if (e.target === bookingModal) {
                    bookingModal.style.display = 'none';
                }
            });
        }

        // Date validation for booking forms
        const bookingForms = document.querySelectorAll('form[action*="book"]');
        bookingForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                const checkIn = form.querySelector('input[name="check_in"]');
                const checkOut = form.querySelector('input[name="check_out"]');
                
                if (checkIn && checkOut) {
                    const checkInDate = new Date(checkIn.value);
                    const checkOutDate = new Date(checkOut.value);
                    const today = new Date();
                    today.setHours(0, 0, 0, 0);

                    if (checkInDate < today) {
                        e.preventDefault();
                        showAlert('Error', 'Check-in date cannot be in the past.');
                        return;
                    }

                    if (checkOutDate <= checkInDate) {
                        e.preventDefault();
                        showAlert('Error', 'Check-out date must be after check-in date.');
                        return;
                    }
                }
            });
        });
    }

    function initializeAdminPage() {
        // Admin-specific initialization
        console.log('Admin page initialized');
    }

    function initializeAuthPage() {
        // Auth page is handled by auth.js
        console.log('Auth page initialized');
    }

    function showAlert(title, message, type = 'error') {
        if (typeof swal === 'function') {
            swal({
                title: title,
                text: message,
                icon: type
            });
        } else {
            alert(`${title}: ${message}`);
        }
    }

    // Global utility functions
    window.utils = {
        showAlert: showAlert,
        
        formatCurrency: function(amount, currency = 'Tsh') {
            return `${currency} ${parseFloat(amount).toLocaleString('en-TZ', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            })}`;
        },
        
        debounce: function(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        },
        
        getCSRFToken: function() {
            return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                   document.querySelector('input[name="<?= csrf_token() ?>"]')?.value;
        }
    };
});

// Error handling for the entire application
window.addEventListener('error', function(e) {
    console.error('Global error:', e.error);
});

// Handle unhandled promise rejections
window.addEventListener('unhandledrejection', function(e) {
    console.error('Unhandled promise rejection:', e.reason);
});