<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preload" href="<?= base_url('css/admin.css') ?>" as="style">
    <link rel="preload" href="<?= base_url('image/bluebirdlogo.png') ?>" as="image">
    <link rel="stylesheet" href="<?= base_url('css/admin.css') ?>">
    <link rel="stylesheet" href="<?= base_url('css/flash.css') ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <title><?= esc($title ?? 'Admin Panel') ?></title>
</head>
<body>
    <div id="mobileview">
        <h5>Admin panel doesn't show in mobile view</h5>
    </div>
    <nav class="uppernav">
        <div class="logo">
            <img class="bluebirdlogo" src="<?= base_url('image/bluebirdlogo.png') ?>" alt="logo">
            <p>SkyBird HOTEL</p>
        </div>
        <div class="logout">
            <a href="<?= base_url('auth/logout') ?>"><button class="btn btn-primary">Logout</button></a>
        </div>
    </nav>
    <nav class="sidenav">
        <ul>
            <li class="pagebtn <?= current_url() == base_url('admin') ? 'active' : '' ?>">
                <img src="<?= base_url('image/icon/dashboard.png') ?>">&nbsp;&nbsp;&nbsp; Dashboard
            </li>
            <li class="pagebtn"><img src="<?= base_url('image/icon/bed.png') ?>">&nbsp;&nbsp;&nbsp; Room Booking</li>
            <li class="pagebtn"><img src="<?= base_url('image/icon/wallet.png') ?>">&nbsp;&nbsp;&nbsp; Payment</li>
            <li class="pagebtn"><img src="<?= base_url('image/icon/bedroom.png') ?>">&nbsp;&nbsp;&nbsp; Rooms</li>
            <li class="pagebtn"><img src="<?= base_url('image/icon/staff.png') ?>">&nbsp;&nbsp;&nbsp; Staff</li>
        </ul>
    </nav>
    <div class="main-content">
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        <?= $this->renderSection('content') ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>