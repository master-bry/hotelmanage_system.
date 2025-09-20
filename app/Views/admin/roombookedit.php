
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
    <link rel="stylesheet" href="<?= base_url('css/roombook.css') ?>">
    <title><?= $title ?></title>
</head>
<body>
    <div id="guestdetailpanel" style="display: flex;">
        <form action="<?= base_url('admin/roombookupdate/' . $booking['id']) ?>" method="POST" class="guestdetailpanelform">
            <div class="head">
                <h3>EDIT RESERVATION</h3>
                <i class="fa-solid fa-circle-xmark" onclick="history.back()"></i>
            </div>
            <div class="middle">
                <div class="guestinfo">
                    <h4>Guest information</h4>
                    <input type="text" name="Name" placeholder="Enter Full name" value="<?= $booking['Name'] ?>" required>
                    <input type="email" name="Email" placeholder="Enter Email" value="<?= $booking['Email'] ?>" required>
                    <select name="Country" class="selectinput">
                        <option value selected>Select Country</option>
                        <?php
                        $countries = ["Afghanistan", "Albania", "Algeria", /* ... add all countries ... */];
                        foreach ($countries as $country) {
                            $selected = ($country == $booking['Country']) ? 'selected' : '';
                            echo "<option value='$country' $selected>$country</option>";
                        }
                        ?>
                    </select>
                    <input type="text" name="Phone" placeholder="Enter Phone" value="<?= $booking['Phone'] ?>" required>
                </div>
                <div class="reservationinfo">
                    <h4>Reservation information</h4>
                    <select name="RoomType" class="selectinput">
                        <option value selected>Type Of Room</option>
                        <option value="Superior Room" <?= $booking['RoomType'] == 'Superior Room' ? 'selected' : '' ?>>SUPERIOR ROOM</option>
                        <option value="Deluxe Room" <?= $booking['RoomType'] == 'Deluxe Room' ? 'selected' : '' ?>>DELUXE ROOM</option>
                        <option value="Guest House" <?= $booking['RoomType'] == 'Guest House' ? 'selected' : '' ?>>GUEST HOUSE</option>
                        <option value="Single Room" <?= $booking['RoomType'] == 'Single Room' ? 'selected' : '' ?>>SINGLE ROOM</option>
                    </select>
                    <select name="Bed" class="selectinput">
                        <option value selected>Bedding Type</option>
                        <option value="Single" <?= $booking['Bed'] == 'Single' ? 'selected' : '' ?>>Single</option>
                        <option value="Double" <?= $booking['Bed'] == 'Double' ? 'selected' : '' ?>>Double</option>
                    </select>
                    <select name="NoofRoom" class="selectinput">
                        <option value selected>No of Room</option>
                        <option value="1" <?= $booking['NoofRoom'] == '1' ? 'selected' : '' ?>>1</option>
                        <option value="2" <?= $booking['NoofRoom'] == '2' ? 'selected' : '' ?>>2</option>
                        <option value="3" <?= $booking['NoofRoom'] == '3' ? 'selected' : '' ?>>3</option>
                    </select>
                    <select name="Meal" class="selectinput">
                        <option value selected>Meal</option>
                        <option value="Room only" <?= $booking['Meal'] == 'Room only' ? 'selected' : '' ?>>Room only</option>
                        <option value="Breakfast" <?= $booking['Meal'] == 'Breakfast' ? 'selected' : '' ?>>Breakfast</option>
                        <option value="Half Board" <?= $booking['Meal'] == 'Half Board' ? 'selected' : '' ?>>Half Board</option>
                        <option value="Full Board" <?= $booking['Meal'] == 'Full Board' ? 'selected' : '' ?>>Full Board</option>
                    </select>
                    <div class="datesection">
                        <span>
                            <label for="cin"> Check-In</label>
                            <input name="cin" type="date" value="<?= $booking['cin'] ?>">
                        </span>
                        <span>
                            <label for="cout"> Check-Out</label>
                            <input name="cout" type="date" value="<?= $booking['cout'] ?>">
                        </span>
                    </div>
                </div>
            </div>
            <div class="footer">
                <button class="btn btn-success" name="guestdetailedit">Edit</button>
            </div>
        </form>
    </div>
</body>
</html>