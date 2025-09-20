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
    <title><?= esc($title) ?></title>
</head>
<body>
    <div id="guestdetailpanel" style="display: flex;">
        <form action="<?= base_url('admin/roombookupdate/' . $booking['id']) ?>" method="POST" class="guestdetailpanelform">
            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
            <div class="head">
                <h3>EDIT RESERVATION</h3>
                <i class="fa-solid fa-circle-xmark" onclick="window.location.href='<?= base_url('admin/roombook') ?>'"></i>
            </div>
            <div class="middle">
                <div class="guestinfo">
                    <h4>Guest information</h4>
                    <input type="text" name="Name" placeholder="Enter Full name" value="<?= esc($booking['Name']) ?>" required>
                    <input type="email" name="Email" placeholder="Enter Email" value="<?= esc($booking['Email']) ?>" required>
                    <select name="Country" class="form-control">
                        <option value="" <?= empty($booking['Country']) ? 'selected' : '' ?>>Select Country</option>
                        <option value="Tanzania" <?= $booking['Country'] == 'Tanzania' ? 'selected' : '' ?>>Tanzania</option>
                        <option value="Kenya" <?= $booking['Country'] == 'Kenya' ? 'selected' : '' ?>>Kenya</option>
                        <option value="Uganda" <?= $booking['Country'] == 'Uganda' ? 'selected' : '' ?>>Uganda</option>
                    </select>
                    <input type="text" name="Phone" placeholder="Enter Phone" value="<?= esc($booking['Phone']) ?>">
                    <select name="RoomType" class="form-control">
                        <option value="" <?= empty($booking['RoomType']) ? 'selected' : '' ?>>Select Room Type</option>
                        <option value="Superior Room" <?= $booking['RoomType'] == 'Superior Room' ? 'selected' : '' ?>>Superior Room</option>
                        <option value="Deluxe Room" <?= $booking['RoomType'] == 'Deluxe Room' ? 'selected' : '' ?>>Deluxe Room</option>
                        <option value="Guest House" <?= $booking['RoomType'] == 'Guest House' ? 'selected' : '' ?>>Guest House</option>
                        <option value="Single Room" <?= $booking['RoomType'] == 'Single Room' ? 'selected' : '' ?>>Single Room</option>
                    </select>
                    <select name="Bed" class="form-control">
                        <option value="None" <?= $booking['Bed'] == 'None' ? 'selected' : '' ?>>None</option>
                        <option value="Single" <?= $booking['Bed'] == 'Single' ? 'selected' : '' ?>>Single</option>
                        <option value="Double" <?= $booking['Bed'] == 'Double' ? 'selected' : '' ?>>Double</option>
                    </select>
                    <select name="NoofRoom" class="form-control">
                        <option value="" <?= empty($booking['NoofRoom']) ? 'selected' : '' ?>>No of Room</option>
                        <option value="1" <?= $booking['NoofRoom'] == '1' ? 'selected' : '' ?>>1</option>
                        <option value="2" <?= $booking['NoofRoom'] == '2' ? 'selected' : '' ?>>2</option>
                        <option value="3" <?= $booking['NoofRoom'] == '3' ? 'selected' : '' ?>>3</option>
                    </select>
                    <select name="Meal" class="form-control">
                        <option value="" <?= empty($booking['Meal']) ? 'selected' : '' ?>>Meal</option>
                        <option value="Room only" <?= $booking['Meal'] == 'Room only' ? 'selected' : '' ?>>Room only</option>
                        <option value="Breakfast" <?= $booking['Meal'] == 'Breakfast' ? 'selected' : '' ?>>Breakfast</option>
                        <option value="Half Board" <?= $booking['Meal'] == 'Half Board' ? 'selected' : '' ?>>Half Board</option>
                        <option value="Full Board" <?= $booking['Meal'] == 'Full Board' ? 'selected' : '' ?>>Full Board</option>
                    </select>
                    <div class="datesection">
                        <span>
                            <label for="cin">Check-In</label>
                            <input name="cin" type="date" value="<?= esc($booking['cin']) ?>" required>
                        </span>
                        <span>
                            <label for="cout">Check-Out</label>
                            <input name="cout" type="date" value="<?= esc($booking['cout']) ?>" required>
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