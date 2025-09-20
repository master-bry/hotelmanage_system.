<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <title><?= esc($title) ?></title>
</head>
<body>
    <div class="paymenttable table-responsive-xl">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th scope="col">Id</th>
                    <th scope="col">Name</th>
                    <th scope="col">Room Type</th>
                    <th scope="col">Bed Type</th>
                    <th scope="col">Check In</th>
                    <th scope="col">Check Out</th>
                    <th scope="col">No of Days</th>
                    <th scope="col">No of Room</th>
                    <th scope="col">Meal</th>
                    <th scope="col">Room Total</th>
                    <th scope="col">Bed Total</th>
                    <th scope="col">Meal Total</th>
                    <th scope="col">Final Total</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($paymentData as $row): ?>
                    <tr>
                        <td><?= esc($row['id']) ?></td>
                        <td><?= esc($row['Name']) ?></td>
                        <td><?= esc($row['RoomType']) ?></td>
                        <td><?= esc($row['Bed']) ?></td>
                        <td><?= esc($row['cin']) ?></td>
                        <td><?= esc($row['cout']) ?></td>
                        <td><?= esc($row['noofdays']) ?></td>
                        <td><?= esc($row['NoofRoom']) ?></td>
                        <td><?= esc($row['meal']) ?></td>
                        <td>Tsh <?= esc(number_format($row['roomtotal'], 2)) ?></td>
                        <td>Tsh <?= esc(number_format($row['bedtotal'], 2)) ?></td>
                        <td>Tsh <?= esc(number_format($row['mealtotal'], 2)) ?></td>
                        <td>Tsh <?= esc(number_format($row['finaltotal'], 2)) ?></td>
                        <td>
                            <a href="<?= base_url('admin/invoiceprint/' . $row['id']) ?>"><button class="btn btn-primary btn-sm">Print</button></a>
                            <a href="<?= base_url('admin/paymantdelete/' . $row['id']) ?>"><button class="btn btn-danger btn-sm">Delete</button></a>
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
</body>
</html>