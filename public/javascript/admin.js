// Admin Panel JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Frame navigation system
    initializeFrameNavigation();

    // Initialize all admin functionality
    initializeAdminComponents();

    function initializeFrameNavigation() {
        const btns = document.querySelectorAll('.pagebtn');
        const frames = document.querySelectorAll('.frame');

        if (btns.length === 0 || frames.length === 0) return;

        const frameActive = function(manual) {
            // Remove active classes
            btns.forEach((btn) => btn.classList.remove('active'));
            frames.forEach((frame) => frame.classList.remove('active'));

            // Add active classes to current
            btns[manual].classList.add('active');
            frames[manual].classList.add('active');

            // Load content if not already loaded
            const url = frames[manual].getAttribute('data-url');
            if (!frames[manual].innerHTML.trim() || frames[manual].innerHTML.includes('Loading...')) {
                loadFrameContent(frames[manual], url);
            }
        };

        // Add click events to buttons
        btns.forEach((btn, i) => {
            btn.addEventListener('click', () => frameActive(i));
        });

        // Load initial frame content
        const activeFrame = document.querySelector('.frame.active');
        if (activeFrame) {
            const url = activeFrame.getAttribute('data-url');
            loadFrameContent(activeFrame, url);
        }
    }

    function loadFrameContent(frame, url) {
        frame.innerHTML = '<div class="text-center p-4">Loading...</div>';
        
        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.text();
        })
        .then(data => {
            frame.innerHTML = data;
            initializeDynamicContent(frame);
        })
        .catch(error => {
            console.error('Error loading content:', error);
            frame.innerHTML = '<div class="alert alert-danger">Failed to load content. Please try again.</div>';
        });
    }

    function initializeDynamicContent(container) {
        // Initialize charts if they exist
        initializeCharts(container);

        // Initialize form submissions
        initializeForms(container);

        // Initialize sweet alerts for flash messages
        initializeFlashMessages(container);

        // Initialize any other dynamic content
        initializeModalWindows(container);
    }

    function initializeCharts(container) {
        const chartCanvases = container.querySelectorAll('canvas');
        if (chartCanvases.length > 0 && typeof Chart !== 'undefined') {
            // Check if we need to fetch chart data
            const chartDataUrl = container.querySelector('[data-chart-url]')?.getAttribute('data-chart-url');
            if (chartDataUrl) {
                fetchChartData(chartDataUrl, container);
            }
        }
    }

    function fetchChartData(url, container) {
        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            // Initialize charts with received data
            initializeChartWithData(data, container);
        })
        .catch(error => {
            console.error('Error loading chart data:', error);
        });
    }

    function initializeChartWithData(data, container) {
        // Implement chart initialization based on your specific chart structure
        // This is a placeholder - adjust based on your actual chart implementation
        console.log('Chart data received:', data);
    }

    function initializeForms(container) {
        const forms = container.querySelectorAll('form');
        forms.forEach(form => {
            form.addEventListener('submit', function(e) {
                // Add loading states or AJAX handling if needed
                const submitBtn = this.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.classList.add('loading');
                }
            });
        });
    }

    function initializeFlashMessages(container) {
        const successMsg = container.querySelector('[data-flash-success]');
        const errorMsg = container.querySelector('[data-flash-error]');

        if (successMsg && typeof swal === 'function') {
            swal({
                title: 'Success',
                text: successMsg.getAttribute('data-flash-success'),
                icon: 'success'
            });
        }

        if (errorMsg && typeof swal === 'function') {
            swal({
                title: 'Error',
                text: errorMsg.getAttribute('data-flash-error'),
                icon: 'error'
            });
        }
    }

    function initializeModalWindows(container) {
        // Initialize any modal windows or popups
        const modals = container.querySelectorAll('[data-toggle="modal"]');
        modals.forEach(modal => {
            modal.addEventListener('click', function() {
                const target = this.getAttribute('data-target');
                // Implement modal show logic if using custom modals
            });
        });
    }

    function initializeAdminComponents() {
        // Add any additional admin-specific initialization here
        console.log('Admin panel initialized');
    }

    // Global admin functions
    window.adminUtils = {
        refreshFrame: function(frameIndex) {
            const frames = document.querySelectorAll('.frame');
            if (frames[frameIndex]) {
                const url = frames[frameIndex].getAttribute('data-url');
                loadFrameContent(frames[frameIndex], url);
            }
        },
        
        showNotification: function(message, type = 'info') {
            // Implement notification system
            if (typeof swal === 'function') {
                swal({
                    title: type.charAt(0).toUpperCase() + type.slice(1),
                    text: message,
                    icon: type
                });
            } else {
                alert(`${type}: ${message}`);
            }
        }
    };
});