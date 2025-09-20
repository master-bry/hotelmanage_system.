<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <link rel="stylesheet" href="<?= base_url('css/roombook.css') ?>">
    <title><?= esc($title) ?></title>
</head>
<body>
    <div id="guestdetailpanel">
        <form action="<?= base_url('admin/addroombook') ?>" method="POST" class="guestdetailpanelform">
            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
            <div class="head">
                <h3>RESERVATION</h3>
                <i class="fa-solid fa-circle-xmark" onclick="adduserclose()"></i>
            </div>
            <div class="middle">
                <div class="guestinfo">
                    <h4>Guest information</h4>
                    <input type="text" name="Name" placeholder="Enter Full name" required>
                    <input type="email" name="Email" placeholder="Enter Email" required>
                    <select name="Country" class="form-control">
                        <option value selected></option>
                        <option value="Tanzania">Tanzania</option>
                        <option value="Kenya">Kenya</option>
                        <option value="Uganda">Uganda</option>
                    </select>
                    <input type="text" name="Phone" placeholder="Enter Phone">
                    <select name="RoomType" class="form-control">
                        <option value selected></option>
                        <option value="Superior Room">Superior Room</option>
                        <option value="Deluxe Room">Deluxe Room</option>
                        <option value="Guest House">Guest House</option>
                        <option value="Single Room">Single Room</option>
                    </select>
                    <select name="Bed" class="form-control">
                        <option value selected></option>
                        <option value="Single">Single</option>
                        <option value="Double">Double</option>
                    </select>
                    <select name="NoofRoom" class="form-control">
                        <option value selected></option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                    </select>
                    <select name="Meal" class="form-control">
                        <option value selected></option>
                        <option value="Room only">Room only</option>
                        <option value="Breakfast">Breakfast</option>
                        <option value="Half Board">Half Board</option>
                        <option value="Full Board">Full Board</option>
                    </select>
                    <div class="datesection">
                        <span>
                            <label for="cin">Check-In</label>
                            <input name="cin" type="date" required>
                        </span>
                        <span>
                            <label for="cout">Check-Out</label>
                            <input name="cout" type="date" required>
                        </span>
                    </div>
                </div>
            </div>
            <div class="footer">
                <button class="btn btn-success" name="guestdetailadd">Add</button>
            </div>
        </form>
    </div>
    <div class="roombooktable table-responsive-xl">
        <table class="table table-bordered" id="table-data">
            <thead>
                <tr>
                    <th scope="col">Id</th>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Country</th>
                    <th scope="col">Phone</th>
                    <th scope="col">Type of Room</th>
                    <th scope="col">Type of Bed</th>
                    <th scope="col">No of Room</th>
                    <th scope="col">Meal</th>
                    <th scope="col">Check In</th>
                    <th scope="col">Check Out</th>
                    <th scope="col">Status</th>
                    <th scope="col">Days</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($roombookData as $row): ?>
                    <tr>
                        <td><?= esc($row['id']) ?></td>
                        <td><?= esc($row['Name']) ?></td>
                        <td><?= esc($row['Email']) ?></td>
                        <td><?= esc($row['Country']) ?></td>
                        <td><?= esc($row['Phone']) ?></td>
                        <td><?= esc($row['RoomType']) ?></td>
                        <td><?= esc($row['Bed']) ?></td>
                        <td><?= esc($row['NoofRoom']) ?></td>
                        <td><?= esc($row['Meal']) ?></td>
                        <td><?= esc($row['cin']) ?></td>
                        <td><?= esc($row['cout']) ?></td>
                        <td>
                            <?php if ($row['stat'] == "Confirm"): ?>
                                <p class="btn btn-success btn-sm">Confirm</p>
                            <?php else: ?>
                                <a href="<?= base_url('admin/roomconfirm/' . $row['id']) ?>"><button class="btn btn-warning btn-sm">Not Confirm</button></a>
                            <?php endif; ?>
                        </td>
                        <td><?= esc($row['nodays']) ?></td>
                        <td>
                            <a href="<?= base_url('admin/roombookedit/' . $row['id']) ?>"><button class="btn btn-primary btn-sm">Edit</button></a>
                            <a href="<?= base_url('admin/roombookdelete/' . $row['id']) ?>"><button class="btn btn-danger btn-sm">Delete</button></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php if (session()->has('success')): ?>
        <script>
            swal({
                title: '<?= esc(session('success')) ?>',
                icon: 'success',
            });
        </script>
    <?php endif; ?>
    <?php if (session()->has('error')): ?>
        <script>
            swal({
                title: '<?= esc(session('error')) ?>',
                icon: 'error',
            });
        </script>
    <?php endif; ?>
    <script>
        function adduserclose() {
            document.getElementById('guestdetailpanel').style.display = 'none';
        }
    </script>
</body>
</html>