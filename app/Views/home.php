<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'SkyBird Hotel - Welcome') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <link rel="stylesheet" href="<?= base_url('css/home.css') ?>">
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --accent-color: #e74c3c;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
        }
        nav {
            background: var(--primary-color);
            padding: 1rem 2rem;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        nav .logo {
            display: flex;
            align-items: center;
            color: white;
        }
        .bluebirdlogo {
            width: 50px;
            height: 50px;
            margin-right: 1rem;
        }
        nav ul {
            display: flex;
            list-style: none;
            margin: 0;
            align-items: center;
        }
        nav ul li {
            margin-left: 2rem;
        }
        nav ul li a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }
        nav ul li a:hover {
            color: var(--secondary-color);
        }
    </style>
</head>
<body>
    <nav>
        <div class="logo">
            <img class="bluebirdlogo" src="<?= base_url('image/bluebirdlogo.png') ?>" alt="logo">
            <h4>SkyBird Hotel</h4>
        </div>
        <ul>
            <li><a href="#home">Home</a></li>
            <li><a href="#about">About</a></li>
            <li><a href="#rooms">Rooms</a></li>
            <li><a href="#contact">Contact</a></li>
            <li><a href="<?= base_url('auth/logout') ?>">Logout</a></li>
        </ul>
    </nav>

    <section id="home">
        <h1>Welcome, <?= esc($user['name']) ?>!</h1>
        <p>Your email: <?= esc($user['email']) ?></p>
        <button onclick="openBookingModal()" class="btn btn-primary">Book a Room</button>
    </section>

    <div id="guestdetailpanel" class="modal fade">
        <form action="<?= base_url('home/book') ?>" method="POST">
            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Book a Room</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Full Name *</label>
                                    <input type="text" class="form-control" name="name" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Email *</label>
                                    <input type="email" class="form-control" name="email" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Phone *</label>
                                    <input type="tel" class="form-control" name="phone" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Country *</label>
                                    <input type="text" class="form-control" name="country" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Room Type *</label>
                                    <select class="form-select" name="room_type" required>
                                        <option value="Superior Room">Superior Room</option>
                                        <option value="Deluxe Room">Deluxe Room</option>
                                        <option value="Guest House">Guest House</option>
                                        <option value="Single Room">Single Room</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Bed Type *</label>
                                    <select class="form-select" name="bed_type" required>
                                        <option value="Single">Single</option>
                                        <option value="Double">Double</option>
                                        <option value="None">None</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Room Count *</label>
                                    <input type="number" class="form-control" name="room_count" min="1" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Meal Plan *</label>
                                    <select class="form-select" name="meal_plan" required>
                                        <option value="Room only">Room only</option>
                                        <option value="Breakfast">Breakfast</option>
                                        <option value="Half Board">Half Board</option>
                                        <option value="Full Board">Full Board</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Check-in Date *</label>
                                    <input type="date" class="form-control" name="check_in" required min="<?= date('Y-m-d') ?>">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Check-out Date *</label>
                                    <input type="date" class="form-control" name="check_out" required min="<?= date('Y-m-d', strtotime('+1 day')) ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Confirm Booking</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script>
        function openBookingModal() {
            var modal = new bootstrap.Modal(document.getElementById('guestdetailpanel'));
            modal.show();
        }

    
        document.querySelector('input[name="check_in"]').addEventListener('change', function() {
            const checkOut = document.querySelector('input[name="check_out"]');
            const minDate = new Date(this.value);
            minDate.setDate(minDate.getDate() + 1);
            checkOut.min = minDate.toISOString().split('T')[0];
        });

        <?php if (session()->getFlashdata('success')): ?>
            swal({
                title: 'Success!',
                text: '<?= session()->getFlashdata('success') ?>',
                icon: 'success'
            });
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            swal({
                title: 'Error',
                text: '<?= session()->getFlashdata('error') ?>',
                icon: 'error'
            });
        <?php endif; ?>
    </script>
</body>
</html>