document.addEventListener('DOMContentLoaded', function () {
    const authForm = document.getElementById('auth_form');
    const signupBtn = document.querySelector('#auth_form .signup_btn');
    const loginBtn = document.querySelector('#auth_form .login_btn'); // Will be updated dynamically
    const signupTemplate = document.getElementById('signup_template');

    console.log('index.js loaded');
    console.log('Auth form element:', authForm);
    console.log('Signup button:', signupBtn);
    console.log('Signup template:', signupTemplate);

    if (signupBtn) {
        signupBtn.addEventListener('click', function (e) {
            e.preventDefault();
            console.log('signuppage called');
            fetch('/auth/getSignupForm') // Replace with the actual URL
                .then(response => {
                    console.log('Response status:', response.status);
                    return response.text();
                })
                .then(html => {
                    authForm.innerHTML = html;
                    const newLoginBtn = document.querySelector('#auth_form .page_move_btn');
                    if (newLoginBtn) {
                        newLoginBtn.addEventListener('click', loginpage);
                    }
                })
                .catch(error => console.error('Error fetching signup form:', error));
        });
    }

    function loginpage() {
        console.log('loginpage called');
        authForm.innerHTML = `
            <h2>Log In</h2>
            <form action="<?= base_url('login') ?>" method="POST">
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
                    <h6>Don't have an account? <span class="page_move_btn">Sign Up</span></h6>
                </div>
            </form>
        `;
        const newSignupBtn = document.querySelector('#auth_form .page_move_btn');
        if (newSignupBtn) {
            newSignupBtn.addEventListener('click', function (e) {
                e.preventDefault();
                signuppage();
            });
        }
    }

    function signuppage() {
        console.log('signuppage called');
        fetch('/auth/getSignupForm') // Replace with the actual URL
            .then(response => {
                console.log('Response status:', response.status);
                return response.text();
            })
            .then(html => {
                authForm.innerHTML = html;
                const newLoginBtn = document.querySelector('#auth_form .page_move_btn');
                if (newLoginBtn) {
                    newLoginBtn.addEventListener('click', loginpage);
                }
            })
            .catch(error => console.error('Error fetching signup form:', error));
    }
});