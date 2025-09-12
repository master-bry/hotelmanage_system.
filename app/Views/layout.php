<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= base_url('css/admin.css') ?>">
    <link rel="stylesheet" href="<?= base_url('css/flash.css') ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <title><?= $title ?></title>
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
            <a href="<?= base_url('logout') ?>"><button class="btn btn-primary">Logout</button></a>
        </div>
    </nav>
    <nav class="sidenav">
        <ul>
            <li class="pagebtn active"><img src="<?= base_url('image/icon/dashboard.png') ?>">&nbsp;&nbsp;&nbsp; Dashboard</li>
            <li class="pagebtn"><img src="<?= base_url('image/icon/bed.png') ?>">&nbsp;&nbsp;&nbsp; Room Booking</li>
            <li class="pagebtn"><img src="<?= base_url('image/icon/wallet.png') ?>">&nbsp;&nbsp;&nbsp; Payment</li>
            <li class="pagebtn"><img src="<?= base_url('image/icon/bedroom.png') ?>">&nbsp;&nbsp;&nbsp; Rooms</li>
            <li class="pagebtn"><img src="<?= base_url('image/icon/staff.png') ?>">&nbsp;&nbsp;&nbsp; Staff</li>
        </ul>
    </nav>
    <?= $this->renderSection('content') ?>
</body>
</html>