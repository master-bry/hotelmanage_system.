<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= base_url('css/dashboard.css') ?>">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
    <title><?= $title ?></title>
</head>
<body>
    <div class="databox">
        <div class="box roombookbox">
            <h2>Total Booked Room</h2>
            <h1><?= $roombookCount ?> / <?= $roomCount ?></h1>
        </div>
        <div class="box guestbox">
            <h2>Total Staff</h2>
            <h1><?= $staffCount ?></h1>
        </div>
        <div class="box profitbox">
            <h2>Profit</h2>
            <h1><?= $totalProfit ?> <span>Tsh</span></h1>
        </div>
    </div>
    <div class="chartbox">
        <div class="bookroomchart">
            <canvas id="bookroomchart"></canvas>
            <h3 style="text-align: center; margin:10px 0;">Booked Room</h3>
        </div>
        <div class="profitchart">
            <div id="profitchart"></div>
            <h3 style="text-align: center; margin:10px 0;">Profit</h3>
        </div>
    </div>
    <script>
        const labels = ['Superior Room', 'Deluxe Room', 'Guest House', 'Single Room'];
        const data = {
            labels: labels,
            datasets: [{
                label: 'My First dataset',
                backgroundColor: ['rgba(255, 99, 132, 1)', 'rgba(255, 159, 64, 1)', 'rgba(54, 162, 235, 1)', 'rgba(153, 102, 255, 1)'],
                borderColor: 'black',
                data: [<?= $chartroom1 ?>, <?= $chartroom2 ?>, <?= $chartroom3 ?>, <?= $chartroom4 ?>],
            }]
        };
        const doughnutchart = {
            type: 'doughnut',
            data: data,
            options: {}
        };
        const myChart = new Chart(document.getElementById('bookroomchart'), doughnutchart);
        Morris.Bar({
            element: 'profitchart',
            data: [<?= $chart_data ?>],
            xkey: 'date',
            ykeys: ['profit'],
            labels: ['Profit'],
            hideHover: 'auto',
            stacked: true,
            barColors: ['rgba(153, 102, 255, 1)']
        });
    </script>
</body>
</html>