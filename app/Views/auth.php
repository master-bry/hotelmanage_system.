<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --accent-color: #e74c3c;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        
        .auth-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            max-width: 400px;
            width: 100%;
        }
        
        .auth-header {
            background: var(--primary-color);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        
        .auth-header img {
            width: 80px;
            height: 80px;
            margin-bottom: 1rem;
        }
        
        .auth-body {
            padding: 2rem;
        }
        
        .form-control {
            border-radius: 10px;
            border: 2px solid #e9ecef;
            padding: 0.75rem 1rem;
            transition: all 0.3s;
        }
        
        .form-control:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
        }
        
        .btn-primary {
            background: var(--secondary-color);
            border: none;
            border-radius: 10px;
            padding: 0.75rem;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .btn-primary:hover {
            background: #2980b9;
            transform: translateY(-2px);
        }
        
        .user-type-toggle {
            display: flex;
            background: #f8f9fa;
            border-radius: 10px;
            padding: 5px;
            margin-bottom: 1.5rem;
        }
        
        .user-type-btn {
            flex: 1;
            padding: 0.5rem;
            text-align: center;
            border: none;
            background: transparent;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .user-type-btn.active {
            background: var(--secondary-color);
            color: white;
        }
        
        .auth-switch {
            text-align: center;
            margin-top: 1.5rem;
            color: #6c757d;
        }
        
        .auth-switch a {
            color: var(--secondary-color);
            text-decoration: none;
            font-weight: 600;
            cursor: pointer;
        }
        
        .loading {
            display: none;
        }
        
        .btn-loading .loading {
            display: inline-block;
        }
        
        .btn-loading .text {
            display: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="auth-container">
                    <div class="auth-header">
                        <img src="<?= base_url('image/bluebirdlogo.png') ?>" alt="SkyBird Hotel" class="img-fluid">
                        <h3>SkyBird Hotel</h3>
                        <p class="mb-0">Welcome to our luxury experience</p>
                    </div>
                    
                    <div class="auth-body">
                        <!-- Login Form -->
                        <div id="login-form">
                            <h4 class="text-center mb-4">Sign In to Your Account</h4>
                            
                            <div class="user-type-toggle">
                                <button type="button" class="user-type-btn active" data-type="user">
                                    <i class="fas fa-user me-2"></i>Guest
                                </button>
                                <button type="button" class="user-type-btn" data-type="staff">
                                    <i class="fas fa-concierge-bell me-2"></i>Staff
                                </button>
                            </div>
                            
                            <form id="loginForm">
                                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
                                <input type="hidden" name="user_type" value="user">
                                
                                <div class="mb-3">
                                    <label class="form-label">Email Address</label>
                                    <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Password</label>
                                    <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
                                </div>
                                
                                <button type="submit" class="btn btn-primary w-100 btn-login">
                                    <span class="text">Sign In</span>
                                    <span class="loading">
                                        <i class="fas fa-spinner fa-spin me-2"></i>Signing In...
                                    </span>
                                </button>
                            </form>
                            
                            <div class="auth-switch">
                                Don't have an account? <a id="show-register">Sign Up</a>
                            </div>
                        </div>
                        
                        <!-- Register Form -->
                        <div id="register-form" style="display: none;">
                            <h4 class="text-center mb-4">Create New Account</h4>
                            
                            <form id="registerForm">
                                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
                                
                                <div class="mb-3">
                                    <label class="form-label">Full Name</label>
                                    <input type="text" name="username" class="form-control" placeholder="Enter your full name" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Email Address</label>
                                    <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Password</label>
                                    <input type="password" name="password" class="form-control" placeholder="Create a password" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Confirm Password</label>
                                    <input type="password" name="confirm_password" class="form-control" placeholder="Confirm your password" required>
                                </div>
                                
                                <button type="submit" class="btn btn-primary w-100 btn-register">
                                    <span class="text">Create Account</span>
                                    <span class="loading">
                                        <i class="fas fa-spinner fa-spin me-2"></i>Creating Account...
                                    </span>
                                </button>
                            </form>
                            
                            <div class="auth-switch">
                                Already have an account? <a id="show-login">Sign In</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Elements
            const loginForm = document.getElementById('login-form');
            const registerForm = document.getElementById('register-form');
            const showRegister = document.getElementById('show-register');
            const showLogin = document.getElementById('show-login');
            const userTypeBtns = document.querySelectorAll('.user-type-btn');
            const userTypeInput = document.querySelector('input[name="user_type"]');
            
            // Switch between login and register forms
            showRegister.addEventListener('click', function(e) {
                e.preventDefault();
                loginForm.style.display = 'none';
                registerForm.style.display = 'block';
            });
            
            showLogin.addEventListener('click', function(e) {
                e.preventDefault();
                registerForm.style.display = 'none';
                loginForm.style.display = 'block';
            });
            
            // User type toggle
            userTypeBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    userTypeBtns.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    userTypeInput.value = this.dataset.type;
                });
            });
            
            // Login form submission
            document.getElementById('loginForm').addEventListener('submit', function(e) {
                e.preventDefault();
                handleLogin(this);
            });
            
            // Register form submission
            document.getElementById('registerForm').addEventListener('submit', function(e) {
                e.preventDefault();
                handleRegister(this);
            });
            
            function handleLogin(form) {
                const btn = form.querySelector('.btn-login');
                const formData = new FormData(form);
                
                btn.classList.add('btn-loading');
                
                fetch('<?= base_url('auth/login') ?>', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    btn.classList.remove('btn-loading');
                    
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: data.message,
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.href = data.redirect;
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.message
                        });
                    }
                })
                .catch(error => {
                    btn.classList.remove('btn-loading');
                    Swal.fire({
                        icon: 'error',
                        title: 'Network Error',
                        text: 'Please check your connection and try again.'
                    });
                });
            }
            
            function handleRegister(form) {
                const btn = form.querySelector('.btn-register');
                const formData = new FormData(form);
                
                btn.classList.add('btn-loading');
                
                fetch('<?= base_url('auth/register') ?>', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    btn.classList.remove('btn-loading');
                    
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Welcome!',
                            text: data.message,
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.href = data.redirect;
                        });
                    } else {
                        let errorMessage = data.message;
                        if (data.errors) {
                            errorMessage += '\n' + Object.values(data.errors).join('\n');
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Registration Failed',
                            text: errorMessage
                        });
                    }
                })
                .catch(error => {
                    btn.classList.remove('btn-loading');
                    Swal.fire({
                        icon: 'error',
                        title: 'Network Error',
                        text: 'Please check your connection and try again.'
                    });
                });
            }
            
            // Auto-focus first input
            document.querySelector('input[name="email"]')?.focus();
        });
    </script>
</body>
</html>