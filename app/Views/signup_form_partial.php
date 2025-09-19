<div class="authsection user_signup">
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
            <h6>Already have an account? <span class="page_move_btn">Log In</span></h6>
        </div>
    </form>
</div>