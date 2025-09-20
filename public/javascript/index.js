const role_btns = document.querySelectorAll('.role_btn .btns');
const Log_in = document.getElementById('Log_in');
const sign_up = document.getElementById('sign_up');
const baseUrl = '<?= base_url() ?>'; // Store base_url in a variable to avoid PHP templating issues

role_btns.forEach((btn) => {
    btn.addEventListener('click', () => {
        role_btns.forEach((b) => b.classList.remove('active'));
        btn.classList.add('active');
        if (btn.dataset.type === 'user') {
            document.getElementById('userlogin').classList.remove('d-none');
            document.getElementById('stafflogin').classList.add('d-none');
            document.getElementById('userlogin').querySelector('input[name="login_type"]').value = 'user';
        } else {
            document.getElementById('userlogin').classList.add('d-none');
            document.getElementById('stafflogin').classList.remove('d-none');
            document.getElementById('stafflogin').querySelector('input[name="login_type"]').value = 'staff';
        }
    });
});

function signuppage() {
    Log_in.classList.add('d-none');
    sign_up.classList.remove('d-none');
}

function loginpage() {
    sign_up.classList.add('d-none');
    Log_in.classList.remove('d-none');
}

document.getElementById('userlogin').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    fetch(`${baseUrl}/auth/ajaxLogin`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            window.location.href = data.redirect;
        } else {
            swal({
                title: 'Error',
                text: data.error,
                icon: 'error',
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        swal({
            title: 'Error',
            text: 'An error occurred during login. Please try again.',
            icon: 'error',
        });
    });
});

document.getElementById('stafflogin').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    fetch(`${baseUrl}/auth/ajaxLogin`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            window.location.href = data.redirect;
        } else {
            swal({
                title: 'Error',
                text: data.error,
                icon: 'error',
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        swal({
            title: 'Error',
            text: 'An error occurred during login. Please try again.',
            icon: 'error',
        });
    });
});

document.getElementById('signupForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    fetch(`${baseUrl}/auth/ajaxSignup`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            window.location.href = data.redirect;
        } else {
            swal({
                title: 'Error',
                text: data.error,
                icon: 'error',
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        swal({
            title: 'Error',
            text: 'An error occurred during signup. Please try again.',
            icon: 'error',
        });
    });
});