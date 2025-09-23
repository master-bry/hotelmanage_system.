<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= base_url('css/dashboard.css') ?>">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title><?= esc($title) ?></title>
</head>
<body>
    <div class="databox">
        <div class="box roombookbox">
            <h2>Total Booked Room</h2>
            <h1><?= esc($roombookCount) ?> / <?= esc($roomCount) ?></h1>
        </div>
        <div class="box guestbox">
            <h2>Total Staff</h2>
            <h1><?= esc($staffCount) ?></h1>
        </div>
        <div class="box profitbox">
            <h2>Profit</h2>
            <h1><?= esc($totalProfit) ?> <span>Tsh</span></h1>
        </div>
    </div>
    <div class="chartbox">
        <div class="bookroomchart">
            <canvas id="bookroomchart"></canvas>
            <h3 style="text-align: center; margin:10px 0;">Booked Room</h3>
        </div>
        <div class="profitchart">
            <canvas id="profitchart"></canvas>
            <h3 style="text-align: center; margin:10px 0;">Profit</h3>
        </div>
    </div>
    <script>
        fetch('<?= base_url('admin/chartdata') ?>')
            .then(response => response.json())
            .then(data => {
                const roomChart = new Chart(document.getElementById('bookroomchart'), {
                    type: 'doughnut',
                    data: {
                        labels: ['Superior Room', 'Deluxe Room', 'Guest House', 'Single Room'],
                        datasets: [{
                            label: 'Booked Rooms',
                            data: data.roomData,
                            backgroundColor: ['rgba(255, 99, 132, 1)', 'rgba(255, 159, 64, 1)', 'rgba(54, 162, 235, 1)', 'rgba(153, 102, 255, 1)'],
                            borderColor: 'black',
                        }]
                    },
                    options: {}
                });

                const profitChart = new Chart(document.getElementById('profitchart'), {
                    type: 'bar',
                    data: {
                        labels: data.profitData.map(item => item.date),
                        datasets: [{
                            label: 'Profit',
                            data: data.profitData.map(item => item.profit),
                            backgroundColor: 'rgba(153, 102, 255, 1)',
                            borderColor: 'black',
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            });
    </script>
</body>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= base_url('css/dashboard.css') ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <title><?= esc($title) ?></title>
</head>
<body>
    <div class="databox">
        <div class="box roombookbox">
            <h2>Total Booked Room</h2>
            <h1><?= esc($roombookCount) ?> / <?= esc($roomCount) ?></h1>
        </div>
        <div class="box guestbox">
            <h2>Total Staff</h2>
            <h1><?= esc($staffCount) ?></h1>
        </div>
        <div class="box profitbox">
            <h2>Profit</h2>
            <h1><?= esc($totalProfit) ?> <span>Tsh</span></h1>
        </div>
    </div>
    <div class="chartbox">
        <div class="bookroomchart">
            <canvas id="bookroomchart"></canvas>
            <h3 style="text-align: center; margin:10px 0;">Booked Room</h3>
        </div>
        <div class="profitchart">
            <canvas id="profitchart"></canvas>
            <h3 style="text-align: center; margin:10px 0;">Profit</h3>
        </div>
    </div>
    <script>
        fetch('<?= base_url('admin/chartdata') ?>')
            .then(response => response.json())
            .then(data => {
                const roomChart = new Chart(document.getElementById('bookroomchart'), {
                    type: 'doughnut',
                    data: {
                        labels: ['Superior Room', 'Deluxe Room', 'Guest House', 'Single Room'],
                        datasets: [{
                            label: 'Booked Rooms',
                            data: data.roomData,
                            backgroundColor: ['rgba(255, 99, 132, 1)', 'rgba(255, 159, 64, 1)', 'rgba(54, 162, 235, 1)', 'rgba(153, 102, 255, 1)'],
                            borderColor: 'black',
                        }]
                    },
                    options: {}
                });

                const profitChart = new Chart(document.getElementById('profitchart'), {
                    type: 'bar',
                    data: {
                        labels: data.profitData.map(item => item.date),
                        datasets: [{
                            label: 'Profit',
                            data: data.profitData.map(item => item.profit),
                            backgroundColor: 'rgba(153, 102, 255, 1)',
                            borderColor: 'black',
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            })
            .catch(error => {
                swal('Error', 'Failed to load chart data', 'error');
            });
    </script>
</body>
</html>