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
    <link rel="stylesheet" href="<?= base_url('css/room.css') ?>">
    <style>
        .roombox {
            background-color: #d1d7ff;
            padding: 10px;
        }
    </style>
    <title><?= esc($title) ?></title>
</head>
<body>
    <div class="addroomsection">
        <form action="<?= base_url('admin/addstaff') ?>" method="POST">
            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
            <label for="staffname">Name:</label>
            <input type="text" name="staffname" class="form-control" required>
            <label for="staffwork">Work:</label>
            <select name="staffwork" class="form-control" required>
                <option value selected></option>
                <option value="Manager">Manager</option>
                <option value="Chef">Chef</option>
                <option value="Reception">Reception</option>
            </select>
            <button type="submit" class="btn btn-success" name="addstaff">Add Staff</button>
        </form>
    </div>
    <div class="room">
        <?php foreach ($staffData as $row): ?>
            <div class="roombox">
                <div class="text-center no-boder">
                    <i class="fa fa-users fa-5x"></i>
                    <h3><?= esc($row['name']) ?></h3>
                    <div class="mb-1"><?= esc($row['work']) ?></div>
                    <a href="<?= base_url('admin/staffdelete/' . $row['id']) ?>"><button class="btn btn-danger">Delete</button></a>
                </div>
            </div>
        <?php endforeach; ?>
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
</body>
</html>