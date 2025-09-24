<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preload" href="<?= base_url('css/login.css') ?>" as="style">
    <link rel="preload" href="<?= base_url('image/bluebirdlogo.png') ?>" as="image">
    <link rel="stylesheet" href="<?= base_url('css/login.css') ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <script src="https://cdn.jsdelivr.net/npm/pace-js@latest/pace.min.js"></script>
    <link rel="stylesheet" href="<?= base_url('css/flash.css') ?>">
    <title><?= esc($title ?? 'SkyBird Hotel') ?></title>
    <style>
        .auth_btn .loading { display: none; }
        .auth_btn.submitting .loading { display: inline-block; }
        .auth_btn.submitting { opacity: 0.7; cursor: not-allowed; }
        .role_btn { display: flex; margin-bottom: 20px; }
        .role_btn .btns { flex: 1; padding: 10px; text-align: center; cursor: pointer; border: 1px solid #ddd; }
        .role_btn .btns.active { background: #007bff; color: white; }
    </style>
</head>
<body>
    <section id="carouselExampleControls" class="carousel slide carousel_section" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img class="carousel-image" src="<?= base_url('image/hotel1.jpg') ?>" alt="Hotel Image 1">
            </div>
            <div class="carousel-item">
                <img class="carousel-image" src="<?= base_url('image/hotel2.jpg') ?>" alt="Hotel Image 2">
            </div>
            <div class="carousel-item">
                <img class="carousel-image" src="<?= base_url('image/hotel3.jpg') ?>" alt="Hotel Image 3">
            </div>
            <div class="carousel-item">
                <img class="carousel-image" src="<?= base_url('image/hotel4.jpg') ?>" alt="Hotel Image 4">
            </div>
        </div>
    </section>

    <section id="auth_section">
        <div class="logo">
            <img class="bluebirdlogo" src="<?= base_url('image/bluebirdlogo.png') ?>" alt="logo">
            <p>SkyBird HOTEL</p>
        </div>

        <div class="auth_container">
            <div id="auth_form" class="authsection user_login active">
                <h2>Log In</h2>
                <div class="role_btn">
                    <div class="btns active" data-type="user">User</div>
                    <div class="btns" data-type="staff">Staff</div>
                </div>

                <?php if (session()->getFlashdata('error')): ?>
                    <script>
                        swal({
                            title: '<?= esc(session()->getFlashdata('error')) ?>',
                            icon: 'error',
                        });
                    </script>
                <?php elseif (session()->getFlashdata('success')): ?>
                    <script>
                        swal({
                            title: '<?= esc(session()->getFlashdata('success')) ?>',
                            icon: 'success',
                        });
                    </script>
                <?php endif; ?>

                <form id="userlogin" action="<?= base_url('auth/login') ?>" method="POST">
                    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
                    <input type="hidden" name="user_type" value="user">
                    <div class="form-floating">
                        <input type="email" class="form-control" name="email" placeholder=" " required>
                        <label for="email">Email</label>
                    </div>
                    <div class="form-floating">
                        <input type="password" class="form-control" name="password" placeholder=" " required>
                        <label for="password">Password</label>
                    </div>
                    <button type="submit" name="login_submit" class="auth_btn">Log In <span class="loading">Loading...</span></button>
                    <div class="footer_line">
                        <h6>Don't have an account? <span class="page_move_btn">Sign Up</span></h6>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <template id="signup_template">
        <div class="authsection user_signup">
            <h2>Sign Up</h2>
            <form id="signupForm" action="<?= base_url('auth/register') ?>" method="POST">
                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
                <div class="form-floating">
                    <input type="text" class="form-control" name="username" placeholder=" " required>
                    <label for="username">Username</label>
                </div>
                <div class="form-floating">
                    <input type="email" class="form-control" name="email" placeholder=" " required>
                    <label for="email">Email</label>
                </div>
                <div class="form-floating">
                    <input type="password" class="form-control" name="password" placeholder=" " required>
                    <label for="password">Password</label>
                </div>
                <div class="form-floating">
                    <input type="password" class="form-control" name="confirm_password" placeholder=" " required>
                    <label for="confirm_password">Confirm Password</label>
                </div>
                <button type="submit" name="signup_submit" class="auth_btn">Sign Up <span class="loading">Loading...</span></button>
                <div class="footer_line">
                    <h6>Already have an account? <span class="page_move_btn">Log In</span></h6>
                </div>
            </form>
        </div>
    </template>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script>
        AOS.init();
        let isSubmitting = false;

        // Role toggle
        document.querySelectorAll('.role_btn .btns').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.role_btn .btns').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                document.querySelector('input[name="user_type"]').value = this.dataset.type;
            });
        });

        // Form submissions
        document.addEventListener('submit', function(e) {
            if (e.target.id === 'signupForm') {
                e.preventDefault();
                if (isSubmitting) return;
                isSubmitting = true;

                const username = e.target.querySelector('input[name="username"]').value;
                const email = e.target.querySelector('input[name="email"]').value;
                const password = e.target.querySelector('input[name="password"]').value;
                const confirmPassword = e.target.querySelector('input[name="confirm_password"]').value;
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                const passwordRegex = /^(?=.*[a-zA-Z])(?=.*\d).{8,}$/;

                if (username.length < 3) {
                    swal('Error', 'Username must be at least 3 characters', 'error');
                    isSubmitting = false;
                    return;
                }
                if (!emailRegex.test(email)) {
                    swal('Error', 'Invalid email format', 'error');
                    isSubmitting = false;
                    return;
                }
                if (!passwordRegex.test(password)) {
                    swal('Error', 'Password must be at least 8 characters and contain letters and numbers', 'error');
                    isSubmitting = false;
                    return;
                }
                if (password !== confirmPassword) {
                    swal('Error', 'Passwords do not match', 'error');
                    isSubmitting = false;
                    return;
                }

                const btn = e.target.querySelector('.auth_btn');
                btn.classList.add('submitting');
                const formData = new FormData(e.target);

                fetch(e.target.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    btn.classList.remove('submitting');
                    isSubmitting = false;
                    if (data.success) {
                        swal({
                            title: 'Success',
                            text: data.message,
                            icon: 'success'
                        }).then(() => {
                            window.location.href = data.redirect;
                        });
                    } else {
                        let errorMessage = data.message;
                        if (data.errors) {
                            errorMessage += '\n' + Object.values(data.errors).join('\n');
                        }
                        swal('Error', errorMessage, 'error');
                    }
                })
                .catch(error => {
                    btn.classList.remove('submitting');
                    isSubmitting = false;
                    swal('Error', 'Network error. Please try again.', 'error');
                });
            }

            if (e.target.id === 'userlogin') {
                e.preventDefault();
                if (isSubmitting) return;
                isSubmitting = true;

                const email = e.target.querySelector('input[name="email"]').value;
                const password = e.target.querySelector('input[name="password"]').value;
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                if (!emailRegex.test(email)) {
                    swal('Error', 'Invalid email format', 'error');
                    isSubmitting = false;
                    return;
                }
                if (password.length < 6) {
                    swal('Error', 'Password must be at least 6 characters', 'error');
                    isSubmitting = false;
                    return;
                }

                const btn = e.target.querySelector('.auth_btn');
                btn.classList.add('submitting');
                const formData = new FormData(e.target);

                fetch(e.target.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    btn.classList.remove('submitting');
                    isSubmitting = false;
                    if (data.success) {
                        swal({
                            title: 'Success',
                            text: data.message,
                            icon: 'success',
                            timer: 1500,
                            buttons: false
                        }).then(() => {
                            window.location.href = data.redirect;
                        });
                    } else {
                        swal('Error', data.message, 'error');
                        if (data.redirect) {
                            setTimeout(() => {
                                window.location.href = data.redirect;
                            }, 2000);
                        }
                    }
                })
                .catch(error => {
                    btn.classList.remove('submitting');
                    isSubmitting = false;
                    swal('Error', 'Network error. Please try again.', 'error');
                });
            }
        });

        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('page_move_btn')) {
                e.preventDefault();
                if (e.target.textContent.trim() === 'Sign Up') {
                    const signupContent = document.getElementById('signup_template').content.cloneNode(true);
                    const authForm = document.getElementById('auth_form');
                    authForm.innerHTML = '';
                    authForm.appendChild(signupContent.querySelector('.user_signup'));
                    authForm.classList.remove('user_login');
                    authForm.classList.add('user_signup');
                } else if (e.target.textContent.trim() === 'Log In') {
                    const authForm = document.getElementById('auth_form');
                    authForm.innerHTML = `
                        <h2>Log In</h2>
                        <div class="role_btn">
                            <div class="btns active" data-type="user">User</div>
                            <div class="btns" data-type="staff">Staff</div>
                        </div>
                        <form id="userlogin" action="<?= base_url('auth/login') ?>" method="POST">
                            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
                            <input type="hidden" name="user_type" value="user">
                            <div class="form-floating">
                                <input type="email" class="form-control" name="email" placeholder=" " required>
                                <label for="email">Email</label>
                            </div>
                            <div class="form-floating">
                                <input type="password" class="form-control" name="password" placeholder=" " required>
                                <label for="password">Password</label>
                            </div>
                            <button type="submit" name="login_submit" class="auth_btn">Log In <span class="loading">Loading...</span></button>
                            <div class="footer_line">
                                <h6>Don't have an account? <span class="page_move_btn">Sign Up</span></h6>
                            </div>
                        </form>
                    `;
                    authForm.classList.remove('user_signup');
                    authForm.classList.add('user_login');
                }
            }
        });
    </script>
</body>
</html>