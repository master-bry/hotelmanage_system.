<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= base_url('css/login.css') ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <link rel="stylesheet" href="<?= base_url('css/flash.css') ?>">
    <title><?= $title ?? 'SKY Hotel' ?></title>
    <style>
        /* Your existing CSS styles */
        .loading {
            opacity: 0.7;
            pointer-events: none;
        }
        .btn-loading {
            position: relative;
        }
        .btn-loading::after {
            content: '';
            position: absolute;
            width: 16px;
            height: 16px;
            top: 50%;
            left: 50%;
            margin: -8px 0 0 -8px;
            border: 2px solid #ffffff;
            border-radius: 50%;
            border-right-color: transparent;
            animation: spinner .75s linear infinite;
        }
        @keyframes spinner {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <!-- Your existing carousel and structure -->
    <section id="auth_section">
        <div class="logo">
            <img class="bluebirdlogo" src="<?= base_url('image/bluebirdlogo.png') ?>" alt="logo">
            <p>SkyBird HOTEL</p>
        </div>

        <div class="auth_container">
            <!-- Login Form -->
            <div id="login_form" class="authsection active">
                <h2>Log In</h2>
                <form id="loginForm" onsubmit="return handleLogin(event)">
                    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
                    <div class="form-floating">
                        <input type="email" class="form-control" name="Email" placeholder=" " required>
                        <label for="Email">Email</label>
                    </div>
                    <div class="form-floating">
                        <input type="password" class="form-control" name="Password" placeholder=" " required>
                        <label for="Password">Password</label>
                    </div>
                    <button type="submit" id="loginBtn" class="auth_btn">Log In</button>
                    <div class="footer_line">
                        <h6>Don't have an account? <span class="page_move_btn" onclick="showSignup()">Sign Up</span></h6>
                    </div>
                </form>
            </div>

            <!-- Signup Form -->
            <div id="signup_form" class="authsection">
                <h2>Sign Up</h2>
                <form id="signupForm" onsubmit="return handleSignup(event)">
                    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
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
                    <button type="submit" id="signupBtn" class="auth_btn">Sign Up</button>
                    <div class="footer_line">
                        <h6>Already have an account? <span class="page_move_btn" onclick="showLogin()">Log In</span></h6>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <script>
        // Form switching functions
        function showSignup() {
            document.getElementById('login_form').classList.remove('active');
            document.getElementById('signup_form').classList.add('active');
        }
        
        function showLogin() {
            document.getElementById('signup_form').classList.remove('active');
            document.getElementById('login_form').classList.add('active');
        }

        // AJAX Login handler
        async function handleLogin(event) {
            event.preventDefault();
            
            const form = event.target;
            const submitBtn = form.querySelector('button[type="submit"]');
            const formData = new FormData(form);
            
            // Show loading state
            submitBtn.classList.add('btn-loading');
            form.classList.add('loading');
            
            try {
                const response = await fetch('<?= base_url('auth/ajaxLogin') ?>', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                const result = await response.json();
                
                if (result.success) {
                    swal({
                        title: 'Success!',
                        text: 'Login successful',
                        icon: 'success',
                        timer: 1000,
                        buttons: false
                    }).then(() => {
                        window.location.href = result.redirect;
                    });
                } else {
                    swal({
                        title: 'Error',
                        text: result.error,
                        icon: 'error'
                    });
                }
            } catch (error) {
                swal({
                    title: 'Error',
                    text: 'Network error. Please try again.',
                    icon: 'error'
                });
            } finally {
                // Remove loading state
                submitBtn.classList.remove('btn-loading');
                form.classList.remove('loading');
            }
            
            return false;
        }

        // AJAX Signup handler
        async function handleSignup(event) {
            event.preventDefault();
            
            const form = event.target;
            const submitBtn = form.querySelector('button[type="submit"]');
            const formData = new FormData(form);
            
            // Validate password confirmation
            const password = form.querySelector('input[name="Password"]').value;
            const confirmPassword = form.querySelector('input[name="CPassword"]').value;
            
            if (password !== confirmPassword) {
                swal({
                    title: 'Error',
                    text: 'Passwords do not match',
                    icon: 'error'
                });
                return false;
            }
            
            // Show loading state
            submitBtn.classList.add('btn-loading');
            form.classList.add('loading');
            
            try {
                const response = await fetch('<?= base_url('auth/ajaxSignup') ?>', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                const result = await response.json();
                
                if (result.success) {
                    swal({
                        title: 'Success!',
                        text: 'Account created successfully',
                        icon: 'success',
                        timer: 1000,
                        buttons: false
                    }).then(() => {
                        window.location.href = result.redirect;
                    });
                } else {
                    swal({
                        title: 'Error',
                        text: result.error,
                        icon: 'error'
                    });
                }
            } catch (error) {
                swal({
                    title: 'Error',
                    text: 'Network error. Please try again.',
                    icon: 'error'
                });
            } finally {
                // Remove loading state
                submitBtn.classList.remove('btn-loading');
                form.classList.remove('loading');
            }
            
            return false;
        }

        // Auto-hide alerts after 3 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.display = 'none';
                }, 3000);
            });
        });
    </script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script>
        AOS.init();
    </script>
</body>
</html>