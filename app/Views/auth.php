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
    <title><?= $title ?? 'SKY Hotel' ?></title>
    <style>
        /* Add CSS styles if login.css is missing */
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow: hidden;
        }
        
        .carousel_section {
            position: fixed;
            width: 100%;
            height: 100%;
            z-index: -1;
        }
        
        .carousel-image {
            width: 100%;
            height: 100vh;
            object-fit: cover;
            filter: brightness(0.7);
        }
        
        #auth_section {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            position: relative;
            z-index: 1;
        }
        
        .logo {
            text-align: center;
            margin-bottom: 30px;
            color: white;
        }
        
        .bluebirdlogo {
            width: 100px;
            height: auto;
            margin-bottom: 10px;
        }
        
        .logo p {
            font-size: 24px;
            font-weight: bold;
            margin: 0;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }
        
        .auth_container {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
            width: 400px;
            max-width: 90%;
        }
        
        .authsection {
            display: none;
        }
        
        .authsection.active {
            display: block;
        }
        
        .authsection h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #0d6efd;
        }
        
        .form-floating {
            margin-bottom: 15px;
        }
        
        .auth_btn {
            width: 100%;
            padding: 12px;
            background-color: #0d6efd;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 10px;
            transition: background-color 0.3s;
        }
        
        .auth_btn:hover {
            background-color: #0b5ed7;
        }
        
        .footer_line {
            text-align: center;
            margin-top: 20px;
        }
        
        .page_move_btn {
            color: #0d6efd;
            cursor: pointer;
            text-decoration: underline;
        }
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
            <div id="login_form" class="authsection active">
                <h2>Log In</h2>
                <?php if (session()->has('error')): ?>
                    <div class="alert alert-danger"><?= session('error') ?></div>
                <?php endif; ?>
                <?php if (session()->has('success')): ?>
                    <div class="alert alert-success"><?= session('success') ?></div>
                <?php endif; ?>

                <form action="<?= base_url('doLogin') ?>" method="POST">
                    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
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
                        <h6>Don't have an account? <span class="page_move_btn" onclick="showSignup()">Sign Up</span></h6>
                    </div>
                </form>
            </div>

            <div id="signup_form" class="authsection">
                <h2>Sign Up</h2>
                <form action="<?= base_url('signup') ?>" method="POST">
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
                    <button type="submit" name="user_signup_submit" class="auth_btn">Sign Up</button>
                    <div class="footer_line">
                        <h6>Already have an account? <span class="page_move_btn" onclick="showLogin()">Log In</span></h6>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <script>
        function showSignup() {
            document.getElementById('login_form').classList.remove('active');
            document.getElementById('signup_form').classList.add('active');
        }
        
        function showLogin() {
            document.getElementById('signup_form').classList.remove('active');
            document.getElementById('login_form').classList.add('active');
        }
        
        // Show SweetAlert notifications if there are any flash messages
        <?php if (session()->has('error')): ?>
            swal({
                title: 'Error',
                text: '<?= session('error') ?>',
                icon: 'error',
            });
        <?php endif; ?>
        
        <?php if (session()->has('success')): ?>
            swal({
                title: 'Success',
                text: '<?= session('success') ?>',
                icon: 'success',
            });
        <?php endif; ?>
    </script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script>
        AOS.init();
    </script>
</body>
</html>