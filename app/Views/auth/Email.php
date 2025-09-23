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
    <title><?= esc($title ?? 'Verify Your Email') ?></title>
    <style>
        .auth_btn .loading { display: none; }
        .auth_btn.submitting .loading { display: inline-block; }
        .auth_btn.submitting { opacity: 0.7; cursor: not-allowed; }
        .resend_btn { margin-top: 10px; }
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
            <div id="auth_form" class="authsection">
                <h2>Verify Your Email</h2>
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
                
                <form id="verifyForm" action="<?= base_url('auth/verify') ?>" method="POST">
                    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
                    <div class="form-floating">
                        <input type="text" class="form-control" id="verification_code" name="verification_code" placeholder=" " required>
                        <label for="verification_code">Verification Code</label>
                    </div>
                    <div class="form-text">Check your email for the 6-digit verification code</div>
                    <button type="submit" class="auth_btn">Verify Email <span class="loading">Loading...</span></button>
                    <button type="button" class="auth_btn resend_btn" onclick="resendCode()">Resend Code <span class="loading">Loading...</span></button>
                    <div class="footer_line">
                        <h6>Back to <span class="page_move_btn">Log In</span></h6>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script>
        AOS.init();
        let isSubmitting = false;

        function resendCode() {
            if (isSubmitting) return;
            isSubmitting = true;

            const btn = document.querySelector('.resend_btn');
            btn.classList.add('submitting');

            fetch('<?= base_url('auth/resend') ?>', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
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
                    });
                } else {
                    swal('Error', data.message, 'error');
                }
            })
            .catch(error => {
                btn.classList.remove('submitting');
                isSubmitting = false;
                swal('Error', 'Network error. Please try again.', 'error');
            });
        }

        document.getElementById('verifyForm').addEventListener('submit', function(e) {
            e.preventDefault();
            if (isSubmitting) return;
            isSubmitting = true;

            const code = document.querySelector('input[name="verification_code"]').value;
            if (code.length !== 6 || !/^\d+$/.test(code)) {
                swal('Error', 'Verification code must be 6 digits', 'error');
                isSubmitting = false;
                return;
            }

            const btn = this.querySelector('.auth_btn:not(.resend_btn)');
            btn.classList.add('submitting');
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
                }
            })
            .catch(error => {
                btn.classList.remove('submitting');
                isSubmitting = false;
                swal('Error', 'Network error. Please try again.', 'error');
            });
        });

        document.querySelectorAll('.page_move_btn').forEach(btn => {
            btn.addEventListener('click', () => {
                window.location.href = '<?= base_url() ?>';
            });
        });
    </script>
</body>
</html>