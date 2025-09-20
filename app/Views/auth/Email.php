<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-center">Verify Your Email</h3>
                    </div>
                    <div class="card-body">
                        <?php if (session()->getFlashdata('error')): ?>
                            <script>
                                swal({
                                    title: '<?= esc(session()->getFlashdata('error')) ?>',
                                    icon: 'error',
                                });
                            </script>
                        <?php endif; ?>
                        
                        <?php if (session()->getFlashdata('success')): ?>
                            <script>
                                swal({
                                    title: '<?= esc(session()->getFlashdata('success')) ?>',
                                    icon: 'success',
                                });
                            </script>
                        <?php endif; ?>
                        
                        <form action="<?= base_url('auth/verify') ?>" method="POST">
                            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
                            <div class="mb-3">
                                <label for="verification_code" class="form-label">Verification Code</label>
                                <input type="text" class="form-control" id="verification_code" name="verification_code" required>
                                <div class="form-text">Check your email for the verification code</div>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Verify Email</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>