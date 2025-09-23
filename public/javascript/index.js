// Authentication System JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Global variables
    let isSubmitting = false;
    const baseUrl = window.location.origin;
    
    // Initialize AOS animations
    if (typeof AOS !== 'undefined') {
        AOS.init();
    }

    // Show flash messages from session
    const sessionError = document.querySelector('meta[name="session-error"]')?.getAttribute('content') || '';
    if (sessionError) {
        showSweetAlert('Error', sessionError, 'error');
    }

    // Role button functionality
    initializeRoleButtons();

    // Page navigation between login and signup
    initializePageNavigation();

    // Form submissions
    initializeFormSubmissions();

    // Carousel initialization
    initializeCarousel();

    function initializeRoleButtons() {
        const roleBtns = document.querySelectorAll('.role_btn .btns');
        roleBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const loginType = this.getAttribute('data-type');
                
                // Update active state
                roleBtns.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                
                // Update login type in forms
                const loginForm = document.getElementById('userlogin');
                if (loginForm) {
                    const loginTypeInput = loginForm.querySelector('input[name="login_type"]');
                    if (loginTypeInput) {
                        loginTypeInput.value = loginType;
                    }
                }
                
                console.log('Login type changed to:', loginType);
            });
        });
    }

    function initializePageNavigation() {
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('page_move_btn')) {
                e.preventDefault();
                const targetPage = e.target.textContent.trim();
                
                if (targetPage === 'Sign Up') {
                    showSignupPage();
                } else if (targetPage === 'Log In') {
                    showLoginPage();
                }
            }
        });
    }

    function showSignupPage() {
        const authForm = document.getElementById('auth_form');
        if (!authForm) return;

        authForm.innerHTML = `
            <h2>Sign Up</h2>
            <form id="signupForm" action="${baseUrl}/auth/ajaxSignup" method="POST">
                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
                <div class="form-floating">
                    <input type="text" class="form-control" name="Username" placeholder=" " required>
                    <label for="Username">Username</label>
                </div>
                <div class="form-floating">
                    <input type="email" class="form-control" name="Email" placeholder=" " required>
                    <label for="Email">Email</label>
                </div>
                <div class="form-floating">
                    <input type="password" class="form-control" name="Password" placeholder=" " required>
                    <label for="Password">Password</label>
                </div>
                <div class="form-floating">
                    <input type="password" class="form-control" name="CPassword" placeholder=" " required>
                    <label for="CPassword">Confirm Password</label>
                </div>
                <button type="submit" name="user_signup_submit" class="auth_btn">Sign Up <span class="loading">Loading...</span></button>
                <div class="footer_line">
                    <h6>Already have an account? <span class="page_move_btn">Log In</span></h6>
                </div>
            </form>
        `;

        authForm.classList.remove('user_login');
        authForm.classList.add('user_signup');
        
        // Re-initialize form submission for the new form
        initializeFormSubmissions();
    }

    function showLoginPage() {
        const authForm = document.getElementById('auth_form');
        if (!authForm) return;

        authForm.innerHTML = `
            <h2>Log In</h2>
            <div class="role_btn">
                <div class="btns active" data-type="user">User</div>
                <div class="btns" data-type="staff">Staff</div>
            </div>
            <form id="userlogin" action="${baseUrl}/auth/ajaxLogin" method="POST">
                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
                <input type="hidden" name="login_type" value="user">
                <div class="form-floating">
                    <input type="email" class="form-control" name="Email" placeholder=" " required>
                    <label for="Email">Email</label>
                </div>
                <div class="form-floating">
                    <input type="password" class="form-control" name="Password" placeholder=" " required>
                    <label for="Password">Password</label>
                </div>
                <button type="submit" name="login_submit" class="auth_btn">Log In <span class="loading">Loading...</span></button>
                <div class="footer_line">
                    <h6>Don't have an account? <span class="page_move_btn">Sign Up</span></h6>
                </div>
            </form>
        `;

        authForm.classList.remove('user_signup');
        authForm.classList.add('user_login');
        
        // Re-initialize components
        initializeRoleButtons();
        initializeFormSubmissions();
    }

    function initializeFormSubmissions() {
        // Login form submission
        const loginForm = document.getElementById('userlogin');
        if (loginForm) {
            loginForm.addEventListener('submit', handleLoginSubmit);
        }

        // Signup form submission
        const signupForm = document.getElementById('signupForm');
        if (signupForm) {
            signupForm.addEventListener('submit', handleSignupSubmit);
        }
    }

    function handleLoginSubmit(e) {
        e.preventDefault();
        
        if (isSubmitting) {
            return;
        }

        const form = e.target;
        const submitBtn = form.querySelector('.auth_btn');
        const formData = new FormData(form);

        // Client-side validation
        if (!validateLoginForm(formData)) {
            return;
        }

        isSubmitting = true;
        submitBtn.classList.add('submitting');

        // Add AJAX header
        const headers = new Headers();
        headers.append('X-Requested-With', 'XMLHttpRequest');

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: headers
        })
        .then(handleResponse)
        .then(data => {
            submitBtn.classList.remove('submitting');
            isSubmitting = false;

            if (data.success) {
                window.location.href = data.redirect;
            } else {
                showSweetAlert('Login Error', data.error, 'error');
            }
        })
        .catch(error => {
            submitBtn.classList.remove('submitting');
            isSubmitting = false;
            console.error('Login error:', error);
            showSweetAlert('Network Error', 'An error occurred during login. Please try again.', 'error');
        });
    }

    function handleSignupSubmit(e) {
        e.preventDefault();
        
        if (isSubmitting) {
            return;
        }

        const form = e.target;
        const submitBtn = form.querySelector('.auth_btn');
        const formData = new FormData(form);

        // Client-side validation
        if (!validateSignupForm(formData)) {
            return;
        }

        isSubmitting = true;
        submitBtn.classList.add('submitting');

        // Add AJAX header
        const headers = new Headers();
        headers.append('X-Requested-With', 'XMLHttpRequest');

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: headers
        })
        .then(handleResponse)
        .then(data => {
            submitBtn.classList.remove('submitting');
            isSubmitting = false;

            if (data.success) {
                window.location.href = data.redirect;
            } else {
                showSweetAlert('Signup Error', data.error, 'error');
            }
        })
        .catch(error => {
            submitBtn.classList.remove('submitting');
            isSubmitting = false;
            console.error('Signup error:', error);
            showSweetAlert('Network Error', 'An error occurred during signup. Please try again.', 'error');
        });
    }

    function validateLoginForm(formData) {
        const email = formData.get('Email');
        const password = formData.get('Password');
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (!emailRegex.test(email)) {
            showSweetAlert('Validation Error', 'Please enter a valid email address.', 'error');
            return false;
        }

        if (password.length < 6) {
            showSweetAlert('Validation Error', 'Password must be at least 6 characters long.', 'error');
            return false;
        }

        return true;
    }

    function validateSignupForm(formData) {
        const username = formData.get('Username');
        const email = formData.get('Email');
        const password = formData.get('Password');
        const confirmPassword = formData.get('CPassword');
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        const passwordRegex = /^(?=.*[a-zA-Z])(?=.*\d).*$/;

        if (username.length < 3) {
            showSweetAlert('Validation Error', 'Username must be at least 3 characters long.', 'error');
            return false;
        }

        if (!emailRegex.test(email)) {
            showSweetAlert('Validation Error', 'Please enter a valid email address.', 'error');
            return false;
        }

        if (password.length < 8) {
            showSweetAlert('Validation Error', 'Password must be at least 8 characters long.', 'error');
            return false;
        }

        if (!passwordRegex.test(password)) {
            showSweetAlert('Validation Error', 'Password must contain both letters and numbers.', 'error');
            return false;
        }

        if (password !== confirmPassword) {
            showSweetAlert('Validation Error', 'Passwords do not match.', 'error');
            return false;
        }

        return true;
    }

    function handleResponse(response) {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const contentType = response.headers.get('content-type');
        if (contentType && contentType.includes('application/json')) {
            return response.json();
        } else {
            throw new Error('Invalid JSON response');
        }
    }

    function showSweetAlert(title, text, icon) {
        if (typeof swal === 'function') {
            swal({
                title: title,
                text: text,
                icon: icon,
                button: 'OK'
            });
        } else {
            alert(`${title}: ${text}`);
        }
    }

    function initializeCarousel() {
        // Bootstrap carousel initialization
        const carousel = document.querySelector('#carouselExampleControls');
        if (carousel) {
            // Bootstrap 5 carousel auto-initializes with data-bs-ride
            console.log('Carousel initialized');
        }
    }

    // Utility function to get CSRF token
    function getCSRFToken() {
        return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
               document.querySelector('input[name="<?= csrf_token() ?>"]')?.value;
    }

    // Export functions for global access (if needed)
    window.authUtils = {
        showSignupPage,
        showLoginPage,
        validateLoginForm,
        validateSignupForm
    };
});