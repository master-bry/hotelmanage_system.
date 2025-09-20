<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= esc($title) ?></title>
    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
    <style>
        * { border: 0; box-sizing: content-box; color: inherit; font-family: inherit; font-size: inherit; font-style: inherit; font-weight: inherit; line-height: inherit; list-style: none; margin: 0; padding: 0; text-decoration: none; vertical-align: top; }
        *[contenteditable] { border-radius: 0.25em; min-width: 1em; outline: 0; cursor: pointer; }
        *[contenteditable]:hover, *[contenteditable]:focus, td:hover *[contenteditable], td:focus *[contenteditable], img.hover { background: #DEF; box-shadow: 0 0 1em 0.5em #DEF; }
        span[contenteditable] { display: inline-block; }
        h1 { font: bold 100% sans-serif; letter-spacing: 0.5em; text-align: center; text-transform: uppercase; }
        table { font-size: 75%; table-layout: fixed; width: 100%; border-collapse: separate; border-spacing: 2px; }
        th, td { border-width: 1px; padding: 0.5em; position: relative; text-align: left; border-radius: 0.25em; border-style: solid; }
        th { background: #EEE; border-color: #BBB; }
        td { border-color: #DDD; }
        html { font: 16px/1 'Open Sans', sans-serif; overflow: auto; padding: 0.5in; background: #999; cursor: default; }
        body { box-sizing: border-box; height: 11in; margin: 0 auto; overflow: hidden; padding: 0.5in; width: 8.5in; background: #FFF; border-radius: 1px; box-shadow: 0 0 1in -0.25in rgba(0, 0, 0, 0.5); }
    </style>
</head>
<body>
    <article>
        <h1>Invoice</h1>
        <table class="meta">
            <tr>
                <th><span>Invoice #</span></th>
                <td><span><?= esc($booking['id']) ?></span></td>
            </tr>
            <tr>
                <th><span>Date</span></th>
                <td><span><?= date('Y-m-d') ?></span></td>
            </tr>
            <tr>
                <th><span>Name</span></th>
                <td><span><?= esc($booking['Name']) ?></span></td>
            </tr>
        </table>
        <table class="inventory">
            <thead>
                <tr>
                    <th><span>Item</span></th>
                    <th><span>Days</span></th>
                    <th><span>Rate</span></th>
                    <th><span>Quantity</span></th>
                    <th><span>Price</span></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><span><?= esc($booking['RoomType']) ?> Room</span></td>
                    <td><span><?= esc($booking['nodays']) ?></span></td>
                    <td><span data-prefix>Tsh</span><span><?= esc($type_of_room) ?></span></td>
                    <td><span><?= esc($booking['NoofRoom']) ?></span></td>
                    <td><span data-prefix>Tsh</span><span><?= esc($rtot) ?></span></td>
                </tr>
                <tr>
                    <td><span><?= esc($booking['Bed']) ?> Bed</span></td>
                    <td><span><?= esc($booking['nodays']) ?></span></td>
                    <td><span data-prefix>Tsh</span><span><?= esc($type_of_bed) ?></span></td>
                    <td><span><?= esc($booking['NoofRoom']) ?></span></td>
                    <td><span data-prefix>Tsh</span><span><?= esc($btot) ?></span></td>
                </tr>
                <tr>
                    <td><span><?= esc($booking['meal']) ?></span></td>
                    <td><span><?= esc($booking['nodays']) ?></span></td>
                    <td><span data-prefix>Tsh</span><span><?= esc($type_of_meal) ?></span></td>
                    <td><span><?= esc($booking['NoofRoom']) ?></span></td>
                    <td><span data-prefix>Tsh</span><span><?= esc($mepr) ?></span></td>
                </tr>
            </tbody>
        </table>
        <table class="balance">
            <tr>
                <th><span>Total</span></th>
                <td><span data-prefix>Tsh</span><span><?= esc($fintot) ?></span></td>
            </tr>
            <tr>
                <th><span>Amount Paid</span></th>
                <td><span data-prefix>Tsh</span><span>0.00</span></td>
            </tr>
            <tr>
                <th><span>Balance Due</span></th>
                <td><span data-prefix>Tsh</span><span><?= esc($fintot) ?></span></td>
            </tr>
        </table>
    </article>
    <aside>
        <h1><span>Contact us</span></h1>
        <div>
            <p align="center">Email: skybirdhotel@gmail.com || Web: www.skybirdhotel.com || Phone: +255 746212372</p>
        </div>
    </aside>
</body>
</html>