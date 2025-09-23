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
    <title><?= esc($title ?? 'SKY Hotel') ?></title>
    <style>
        .auth_btn .loading { display: none; }
        .auth_btn.submitting .loading { display: inline-block; }
        .auth_btn.submitting { opacity: 0.7; cursor: not-allowed; }
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
                <button type="submit" name="user_signup_submit" class="auth_btn">Sign Up <span class="loading">Loading...</span></button>
                <div class="footer_line">
                    <h6>Already have an account? <span class="page_move_btn">Log In</span></h6>
                </div>
            </form>
        </div>
    </template>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
 <script>
// Debug version - replace your entire script section with this
document.addEventListener('DOMContentLoaded', function() {
    console.log('Page loaded - starting authentication system');
    
    let isSubmitting = false;

    // Debug function to check what's being sent
    function debugRequest(form, formData) {
        console.log('=== DEBUG REQUEST ===');
        console.log('Form action:', form.action);
        console.log('Form method:', form.method);
        console.log('Form data:');
        for (let [key, value] of formData.entries()) {
            console.log('  ', key + ':', value);
        }
        console.log('=== END DEBUG ===');
    }

    // Handle form submissions
    document.addEventListener('submit', function(e) {
        e.preventDefault();
        console.log('Form submission intercepted');
        
        if (isSubmitting) {
            console.log('Already submitting, ignoring');
            return;
        }
        
        const form = e.target;
        const submitBtn = form.querySelector('.auth_btn');
        const formData = new FormData(form);
        
        // Debug what we're sending
        debugRequest(form, formData);
        
        isSubmitting = true;
        submitBtn.classList.add('submitting');
        submitBtn.disabled = true;

        // Use fetch with proper error handling
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            console.log('Response status:', response.status);
            console.log('Response headers:', response.headers);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            
            submitBtn.classList.remove('submitting');
            submitBtn.disabled = false;
            isSubmitting = false;
            
            if (data.success) {
                console.log('Success, redirecting to:', data.redirect);
                window.location.href = data.redirect;
            } else {
                swal({
                    title: 'Error',
                    text: data.error || 'Unknown error occurred',
                    icon: 'error'
                });
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            
            submitBtn.classList.remove('submitting');
            submitBtn.disabled = false;
            isSubmitting = false;
            
            swal({
                title: 'Network Error',
                text: 'An error occurred during login. Please try again. Error: ' + error.message,
                icon: 'error'
            });
        });
    });

    // Page navigation between login and signup
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('page_move_btn')) {
            e.preventDefault();
            console.log('Page move button clicked:', e.target.textContent);
            
            const targetPage = e.target.textContent.trim();
            const authForm = document.getElementById('auth_form');
            const signupTemplate = document.getElementById('signup_template');
            
            if (targetPage === 'Sign Up') {
                const signupContent = signupTemplate.content.cloneNode(true);
                authForm.innerHTML = '';
                authForm.appendChild(signupContent);
                authForm.classList.remove('user_login');
                authForm.classList.add('user_signup');
                console.log('Switched to signup page');
            } else {
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
                        <button type="submit" name="login_submit" class="auth_btn">Log In <span class="loading">Loading...</span></button>
                        <div class="footer_line">
                            <h6>Don't have an account? <span class="page_move_btn">Sign Up</span></h6>
                        </div>
                    </form>
                `;
                authForm.classList.remove('user_signup');
                authForm.classList.add('user_login');
                console.log('Switched to login page');
            }
        }
    });

    // Role button functionality
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('btns')) {
            console.log('Role button clicked:', e.target.dataset.type);
            
            const roleBtns = document.querySelectorAll('.role_btn .btns');
            roleBtns.forEach(btn => btn.classList.remove('active'));
            e.target.classList.add('active');
            
            const loginType = e.target.getAttribute('data-type');
            const loginForm = document.getElementById('userlogin');
            if (loginForm) {
                const loginTypeInput = loginForm.querySelector('input[name="login_type"]');
                if (loginTypeInput) {
                    loginTypeInput.value = loginType;
                    console.log('Login type set to:', loginType);
                }
            }
        }
    });

    console.log('Authentication system initialized');
});
</script>  
    <script src ='/javascript/index.js'></script>
</body>
</html>