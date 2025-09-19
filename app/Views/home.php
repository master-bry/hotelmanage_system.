<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= base_url('css/home.css') ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <link rel="stylesheet" href="<?= base_url('admin/css/roombook.css') ?>">
    <title><?= $title ?? 'SkyBird Hotel' ?></title>
    <style>
      #guestdetailpanel {
        display: none;
      }
      #guestdetailpanel .middle {
        height: 450px;
      }
    </style>
</head>
<body>
    <nav>
        <div class="logo">
            <img class="bluebirdlogo" src="<?= base_url('image/bluebirdlogo.png') ?>" alt="logo">
            <p>SkyBird HOTEL</p>
        </div>
        <ul>
            <li><a href="#firstsection">Home</a></li>
            <li><a href="#secondsection">Rooms</a></li>
            <li><a href="#thirdsection">Facilities</a></li>
            <?php if (session()->get('isStaff')): ?>
                <li><a href="<?= base_url('admin') ?>">Admin Panel</a></li>
            <?php endif; ?>
            <li><a href="<?= base_url('logout') ?>">Logout</a></li>
        </ul>
    </nav>

    <section id="firstsection">
        <h1>Welcome to SkyBird Hotel</h1>
        <p>Hello, <?= session('usermail') ?>!</p>
    </section>

    <section id="secondsection">
        <h1 class="head">≼ Rooms ≽</h1>
        <div class="room">
            <div class="roombox">
                <div class="hotelphoto h1"></div>
                <div class="roomdata">
                    <h2>Superior Room</h2>
                    <div class="services">
                        <i class="fa-solid fa-wifi"></i>
                        <i class="fa-solid fa-burger"></i>
                        <i class="fa-solid fa-spa"></i>
                        <i class="fa-solid fa-dumbbell"></i>
                    </div>
                    <button class="btn btn-primary bookbtn" onclick="openbookbox()">Book</button>
                </div>
            </div>
            <div class="roombox">
                <div class="hotelphoto h2"></div>
                <div class="roomdata">
                    <h2>Deluxe Room</h2>
                    <div class="services">
                        <i class="fa-solid fa-wifi"></i>
                        <i class="fa-solid fa-burger"></i>
                        <i class="fa-solid fa-spa"></i>
                    </div>
                    <button class="btn btn-primary bookbtn" onclick="openbookbox()">Book</button>
                </div>
            </div>
            <div class="roombox">
                <div class="hotelphoto h3"></div>
                <div class="roomdata">
                    <h2>Guest Room</h2>
                    <div class="services">
                        <i class="fa-solid fa-wifi"></i>
                        <i class="fa-solid fa-burger"></i>
                        <i class="fa-solid fa-spa"></i>
                    </div>
                    <button class="btn btn-primary bookbtn" onclick="openbookbox()">Book</button>
                </div>
            </div>
            <div class="roombox">
                <div class="hotelphoto h4"></div>
                <div class="roomdata">
                    <h2>Single Room</h2>
                    <div class="services">
                        <i class="fa-solid fa-wifi"></i>
                        <i class="fa-solid fa-burger"></i>
                    </div>
                    <button class="btn btn-primary bookbtn" onclick="openbookbox()">Book</button>
                </div>
            </div>
        </div>
    </section>

    <section id="thirdsection">
        <h1 class="head">≼ Facilities ≽</h1>
        <div class="facility">
            <div class="box">
                <h2>Swimming Pool</h2>
            </div>
            <div class="box">
                <h2>Spa</h2>
            </div>
            <div class="box">
                <h2>24/7 Restaurants</h2>
            </div>
            <div class="box">
                <h2>24/7 Gym</h2>
            </div>
            <div class="box">
                <h2>Heli Service</h2>
            </div>
        </div>
    </section>

    <section id="contactus">
        <div class="social">
            <i class="fa-brands fa-instagram"></i>
            <i class="fa-brands fa-facebook"></i>
            <i class="fa-solid fa-envelope"></i>
        </div>
        <div class="createdby">
            <h5>Created by @master</h5>
        </div>
    </section>

    <div id="guestdetailpanel">
        <div class="middle">
            <form action="<?= base_url('book') ?>" method="POST">
                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
                <h2>Book a Room</h2>
                <div class="form-floating">
                    <input type="text" class="form-control" name="name" placeholder=" " required>
                    <label for="name">Name</label>
                </div>
                <div class="form-floating">
                    <input type="email" class="form-control" name="email" placeholder=" " required>
                    <label for="email">Email</label>
                </div>
                <div class="form-floating">
                    <select class="form-control" name="room_type" required>
                        <option value="Superior Room">Superior Room</option>
                        <option value="Deluxe Room">Deluxe Room</option>
                        <option value="Guest Room">Guest Room</option>
                        <option value="Single Room">Single Room</option>
                    </select>
                    <label for="room_type">Room Type</label>
                </div>
                <div class="form-floating">
                    <input type="date" class="form-control" name="check_in" placeholder=" " required>
                    <label for="check_in">Check-In Date</label>
                </div>
                <div class="form-floating">
                    <input type="date" class="form-control" name="check_out" placeholder=" " required>
                    <label for="check_out">Check-Out Date</label>
                </div>
                <button type="submit" class="btn btn-primary">Book Now</button>
                <button type="button" class="btn btn-secondary" onclick="closebox()">Cancel</button>
            </form>
        </div>
    </div>

    <?php if (session()->has('error')): ?>
        <script>
            swal({
                title: '<?= session('error') ?>',
                icon: 'error',
            });
        </script>
    <?php endif; ?>
    <?php if (session()->has('success')): ?>
        <script>
            swal({
                title: '<?= session('success') ?>',
                icon: 'success',
            });
        </script>
    <?php endif; ?>

    <script>
        var bookbox = document.getElementById("guestdetailpanel");
        function openbookbox() {
            bookbox.style.display = "flex";
        }
        function closebox() {
            bookbox.style.display = "none";
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
</body>
</html>