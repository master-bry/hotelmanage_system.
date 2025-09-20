<?php
// Check if user is logged in and is staff
if (!session()->has('usermail') || !session()->get('isStaff')) {
    return redirect()->to(base_url('/'));
}
?>
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
    <title><?= $title ?></title>
</head>
<body>
    <div id="guestdetailpanel">
        <form action="<?= base_url('admin/addroombook') ?>" method="POST" class="guestdetailpanelform">
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
                        <?php
                        $countries = ["Afghanistan", "Albania", "Algeria", /* ... add all countries ... */];
                        foreach ($countries as $country) {
                            echo "<option value='$country'>$country</option>";
                        }
                        ?>
                    </select>
                    <input type="text" name="Phone" placeholder="Enter Phone" required>
                </div>
                <div class="roominfo">
                    <h4>Room information</h4>
                    <select name="troom" class="form-control">
                        <option value selected></option>
                        <option value="Superior Room">SUPERIOR ROOM</option>
                        <option value="Deluxe Room">DELUXE ROOM</option>
                        <option value="Guest House">GUEST HOUSE</option>
                        <option value="Single Room">SINGLE ROOM</option>
                    </select>
                    <select name="bed" class="form-control">
                        <option value selected></option>
                        <option value="Single">Single</option>
                        <option value="Double">Double</option>
                        <option value="Triple">Triple</option>
                        <option value="Quad">Quad</option>
                        <option value="None">None</option>
                    </select>
                    <select name="nroom" class="form-control">
                        <option value selected></option>
                        <option value="1">1</option>
                    </select>
                    <select name="meal" class="form-control">
                        <option value selected></option>
                        <option value="Room only">Room only</option>
                        <option value="Breakfast">Breakfast</option>
                        <option value="Half Board">Half Board</option>
                        <option value="Full Board">Full Board</option>
                    </select>
                    <input type="date" name="cin" placeholder="Check-In">
                    <input type="date" name="cout" placeholder="Check-Out">
                </div>
            </div>
            <div class="footer">
                <button type="submit" class="btn btn-primary" name="addroombook">Submit</button>
            </div>
        </form>
    </div>
    <div class="searchsection">
        <input type="text" name="search_bar" id="search_bar" placeholder="search..." onkeyup="searchFun()">
        <button class="adduser" id="adduser" onclick="adduseropen()"><i class="fa-solid fa-bookmark"></i> Add</button>
        <form action="<?= base_url('admin/exportdata') ?>" method="post">
            <button class="exportexcel" id="exportexcel" name="exportexcel" type="submit"><i class="fa-solid fa-file-arrow-down"></i></button>
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
                    <th scope="col">Check-In</th>
                    <th scope="col">Check-Out</th>
                    <th scope="col">No of Day</th>
                    <th scope="col">Status</th>
                    <th scope="col" class="action">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($roombookData as $res): ?>
                <tr>
                    <td><?= $res['id'] ?></td>
                    <td><?= $res['Name'] ?></td>
                    <td><?= $res['Email'] ?></td>
                    <td><?= $res['Country'] ?></td>
                    <td><?= $res['Phone'] ?></td>
                    <td><?= $res['RoomType'] ?></td>
                    <td><?= $res['Bed'] ?></td>
                    <td><?= $res['NoofRoom'] ?></td>
                    <td><?= $res['Meal'] ?></td>
                    <td><?= $res['cin'] ?></td>
                    <td><?= $res['cout'] ?></td>
                    <td><?= $res['nodays'] ?></td>
                    <td><?= $res['stat'] ?></td>
                    <td class="action">
                        <?php if ($res['stat'] != "Confirm"): ?>
                            <a href="<?= base_url('admin/roomconfirm/' . $res['id']) ?>"><button class="btn btn-success">Confirm</button></a>
                            <a href="<?= base_url('admin/roombookdelete/' . $res['id']) ?>"><button class="btn btn-danger">Delete</button></a>
                        <?php endif; ?>
                        <a href="<?= base_url('admin/roombookedit/' . $res['id']) ?>"><button class="btn btn-primary">Edit</button></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <script src="<?= base_url('js/roombook.js') ?>"></script>
</body>
</html>