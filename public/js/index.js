// JavaScript file should not contain PHP code. Ensure the server sends the correct Content-Type header.
document.addEventListener('DOMContentLoaded', function () {
    const authForm = document.getElementById('auth_form');
    const signupBtn = document.querySelector('#auth_form .page_move_btn');
    const loginBtn = document.querySelector('#auth_form .page_move_btn'); // Will be updated dynamically

    console.log('index.js loaded');
    console.log('Auth form element:', authForm);
    console.log('Signup button:', signupBtn);

    if (signupBtn) {
        signupBtn.addEventListener('click', function (e) {
            e.preventDefault();
            console.log('signuppage called');
            showSignupForm();
        });
    }

    function showLoginForm(loginType = 'user') {
        console.log('loginpage called for type:', loginType);
        authForm.innerHTML = `
            <h2>Log In (${loginType.charAt(0).toUpperCase() + loginType.slice(1)})</h2>
            <form id="loginForm" action="<?= base_url('auth/ajaxLogin') ?>" method="POST">
                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
                <input type="hidden" name="login_type" value="${loginType}">
                <div class="form-floating">
                    <input type="email" class="form-control" name="Email" placeholder=" " required>
                    <label for="Email">Email</label>
                </div>
                <div class="form-floating">
                    <input type="password" class="form-control" name="Password" placeholder=" " required>
                    <label for="Password">Password</label>
                </div>
                <button type="submit" class="auth_btn">Log In</button>
                <div class="footer_line">
                    <h6>Don't have an account? <span class="page_move_btn" data-action="signup">Sign Up</span></h6>
                    ${loginType === 'user' ? '<h6>Staff? <span class="page_move_btn" data-action="login-staff">Staff Login</span></h6>' : '<h6>User? <span class="page_move_btn" data-action="login-user">User Login</span></h6>'}
                </div>
            </form>
        `;
        setupLoginForm();
        attachPageMoveListeners();
    }

    function showSignupForm() {
        console.log('signuppage called');
        authForm.innerHTML = `
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
                <button type="submit" class="auth_btn">Sign Up</button>
                <div class="footer_line">
                    <h6>Already have an account? <span class="page_move_btn" data-action="login">Log In</span></h6>
                </div>
            </form>
        `;
        setupSignupForm();
        attachPageMoveListeners();
    }

    function setupLoginForm() {
        const loginForm = document.getElementById('loginForm');
        if (loginForm) {
            loginForm.addEventListener('submit', function (e) {
                e.preventDefault();
                console.log('Submitting login form');
                fetch(loginForm.action, {
                    method: 'POST',
                    body: new FormData(loginForm)
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Login response:', data);
                    if (data.success) {
                        window.location.href = data.redirect;
                    } else {
                        alert(data.error);
                    }
                })
                .catch(error => console.error('Error submitting login form:', error));
            });
        }
    }

    function setupSignupForm() {
        const signupForm = document.getElementById('signupForm');
        if (signupForm) {
            signupForm.addEventListener('submit', function (e) {
                e.preventDefault();
                console.log('Submitting signup form');
                fetch(signupForm.action, {
                    method: 'POST',
                    body: new FormData(signupForm)
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Signup response:', data);
                    if (data.success) {
                        window.location.href = data.redirect;
                    } else {
                        alert(data.error);
                    }
                })
                .catch(error => console.error('Error submitting signup form:', error));
            });
        }
    }

    function attachPageMoveListeners() {
        document.querySelectorAll('.page_move_btn').forEach(btn => {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                const action = btn.getAttribute('data-action');
                if (action === 'signup') {
                    showSignupForm();
                } else if (action === 'login') {
                    showLoginForm('user');
                } else if (action === 'login-staff') {
                    showLoginForm('staff');
                } else if (action === 'login-user') {
                    showLoginForm('user');
                }
            });
        });
    }

    // Initial load
    showLoginForm('user');
});