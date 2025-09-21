<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= base_url('css/login.css') ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <script src="https://cdn.jsdelivr.net/npm/pace-js@latest/pace.min.js"></script>
    <link rel="stylesheet" href="<?= base_url('css/flash.css') ?>">
    <title><?= esc($title ?? 'SKY Hotel') ?></title>
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

                <?php if (session()->has('error')): ?>
                    <script>
                        swal({
                            title: '<?= esc(session('error')) ?>',
                            icon: 'error',
                        });
                    </script>
                <?php endif; ?>

                <form id="userlogin" action="<?= base_url('auth/ajaxLogin') ?>" method="POST">
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
                    <button type="submit" name="login_submit" class="auth_btn">Log In</button>
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
            <form id="signupForm" action="<?= base_url('auth/ajaxSignup') ?>" method="POST">
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
                <button type="submit" name="user_signup_submit" class="auth_btn">Sign Up</button>
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

        const authForm = document.getElementById('auth_form');
        const signupTemplate = document.getElementById('signup_template');
        const baseUrl = '<?= base_url() ?>'; // Store base_url in a variable

        // Pass session error to JavaScript as a variable
        const sessionError = '<?= session()->has('error') ? esc(session('error')) : '' ?>';

        // Debug logging
        console.log('authForm element:', authForm);
        console.log('signupTemplate element:', signupTemplate);
        console.log('Session error:', sessionError);

        if (!authForm || !signupTemplate) {
            console.error('authForm or signupTemplate not found in DOM');
        }

        // Display session error if it exists
        if (sessionError) {
            swal({
                title: sessionError,
                icon: 'error',
            });
        }

        // Toggle to signup page
        document.querySelectorAll('.page_move_btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                console.log('Page move button clicked:', btn.textContent.trim());
                if (btn.textContent.trim() === 'Sign Up') {
                    const signupContent = signupTemplate.content.cloneNode(true);
                    authForm.innerHTML = '';
                    authForm.appendChild(signupContent.querySelector('.user_signup'));
                    authForm.classList.remove('user_login');
                    authForm.classList.add('user_signup');
                } else if (btn.textContent.trim() === 'Log In') {
                    authForm.innerHTML = `
                        <h2>Log In</h2>
                        <div class="role_btn">
                            <div class="btns active" data-type="user">User</div>
                            <div class="btns" data-type="staff">Staff</div>
                        </div>
                        <form id="userlogin" action="<?= base_url('auth/ajaxLogin') ?>" method="POST">
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
                            <button type="submit" name="login_submit" class="auth_btn">Log In</button>
                            <div class="footer_line">
                                <h6>Don't have an account? <span class="page_move_btn">Sign Up</span></h6>
                            </div>
                        </form>
                    `;
                    // Display session error if it was set before toggle
                    if (sessionError) {
                        swal({
                            title: sessionError,
                            icon: 'error',
                        });
                    }
                    authForm.classList.remove('user_signup');
                    authForm.classList.add('user_login');
                }
            });
        });

        // Role button toggle
        const roleBtns = document.querySelectorAll('.role_btn .btns');
        roleBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                console.log('Role button clicked:', btn.dataset.type);
                roleBtns.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                const userLoginForm = authForm.querySelector('#userlogin');
                if (btn.dataset.type === 'user') {
                    userLoginForm.querySelector('input[name="login_type"]').value = 'user';
                    userLoginForm.action = '<?= base_url('auth/ajaxLogin') ?>';
                } else {
                    userLoginForm.querySelector('input[name="login_type"]').value = 'staff';
                    userLoginForm.action = '<?= base_url('auth/ajaxLogin') ?>'; // Same action for now
                }
            });
        });

        // Form submission handlers
        document.getElementById('userlogin')?.addEventListener('submit', function(e) {
            e.preventDefault();
            console.log('User login form submitted');
            const formData = new FormData(this);
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = data.redirect;
                } else {
                    swal({ title: 'Error', text: data.error, icon: 'error' });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                swal({ title: 'Error', text: 'An error occurred during login. Please try again.', icon: 'error' });
            });
        });

        document.getElementById('signupForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            console.log('Signup form submitted');
            const formData = new FormData(this);
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = data.redirect;
                } else {
                    swal({ title: 'Error', text: data.error, icon: 'error' });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                swal({ title: 'Error', text: 'An error occurred during signup. Please try again.', icon: 'error' });
            });
        });
    </script>
</body>
</html>