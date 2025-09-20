<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= base_url('css/login.css') ?>">
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
            <div id="Log_in">
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

                <form class="user_login" id="userlogin" action="<?= base_url('auth/ajaxLogin') ?>" method="POST">
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
                        <h6>Don't have an account? <span class="page_move_btn" onclick="signuppage()">Sign Up</span></h6>
                    </div>
                </form>

                <form class="staff_login d-none" id="stafflogin" action="<?= base_url('auth/ajaxLogin') ?>" method="POST">
                    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
                    <input type="hidden" name="login_type" value="staff">
                    <div class="form-floating">
                        <input type="email" class="form-control" name="Email" placeholder=" " required>
                        <label for="Email">Email</label>
                    </div>
                    <div class="form-floating">
                        <input type="password" class="form-control" name="Password" placeholder=" " required>
                        <label for="Password">Password</label>
                    </div>
                    <button type="submit" name="staff_login_submit" class="auth_btn">Log In</button>
                    <div class="footer_line">
                        <h6>Don't have an account? <span class="page_move_btn" onclick="signuppage()">Sign Up</span></h6>
                    </div>
                </form>
            </div>

            <div id="sign_up" class="d-none">
                <h2>Sign Up</h2>
                <form class="user_signup" id="signupForm" action="<?= base_url('auth/ajaxSignup') ?>" method="POST">
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
                        <h6>Already have an account? <span class="page_move_btn" onclick="loginpage()">Log In</span></h6>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <script src="<?= base_url('javascript/index.js') ?>"></script>
</body>
</html>