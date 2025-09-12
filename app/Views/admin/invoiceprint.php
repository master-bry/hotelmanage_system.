<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?></title>
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
        header { margin: 0 0 3em; }
        header:after { clear: both; content: ""; display: table; }
        header h1 { background: #000; border-radius: 0.25em; color: #FFF; margin: 0 0 1em; padding: 0.5em 0; }
        header address { float: left; font-size: 75%; font-style: normal; line-height: 1.25; margin: 0 1em 1em 0; }
        header address p { margin: 0 0 0.25em; }
        header span, header img { display: block; float: right; }
        header span { margin: 0 0 1em 1em; max-height: 25%; max-width: 60%; position: relative; }
        header img { max-height: 100%; max-width: 100%; }
        article, article address, table.meta, table.inventory { margin: 0 0 3em; }
        article:after { clear: both; content: ""; display: table; }
        article h1 { clip: rect(0 0 0 0); position: absolute; }
        article address { float: left; font-size: 125%; font-weight: bold; }
        table.meta, table.balance { float: right; width: 36%; }
        table.meta:after, table.balance:after { clear: both; content: ""; display: table; }
        table.meta th { width: 40%; }
        table.balance th, table.balance td { width: 50%; }
    </style>
</head>
<body>
    <header>
        <h1>Invoice</h1>
        <address>
            <p>SkyBird HOTEL,</p>
            <p>(+255) 746212372</p>
        </address>
        <span><img alt="" src="<?= base_url('image/logo.jpg') ?>"></span>
    </header>
    <article>
        <h1>Recipient</h1>
        <address>
            <p><?= $booking['Name'] ?> <br></p>
        </address>
        <table class="meta">
            <tr>
                <th><span>Invoice #</span></th>
                <td><span><?= $booking['id'] ?></span></td>
            </tr>
            <tr>
                <th><span>Date</span></th>
                <td><span><?= $booking['cout'] ?></span></td>
            </tr>
        </table>
        <table class="inventory">
            <thead>
                <tr>
                    <th><span>Item</span></th>
                    <th><span>No of Days</span></th>
                    <th><span>Rate</span></th>
                    <th><span>Quantity</span></th>
                    <th><span>Price</span></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><span><?= $booking['RoomType'] ?></span></td>
                    <td><span><?= $booking['noofdays'] ?></span></td>
                    <td><span data-prefix>Tsh</span><span><?= $type_of_room ?></span></td>
                    <td><span><?= $booking['NoofRoom'] ?></span></td>
                    <td><span data-prefix>Tsh</span><span><?= $ttot ?></span></td>
                </tr>
                <tr>
                    <td><span><?= $booking['Bed'] ?> Bed</span></td>
                    <td><span><?= $booking['noofdays'] ?></span></td>
                    <td><span data-prefix>Tsh</span><span><?= $type_of_bed ?></span></td>
                    <td><span><?= $booking['NoofRoom'] ?></span></td>
                    <td><span data-prefix>Tsh</span><span><?= $btot ?></span></td>
                </tr>
                <tr>
                    <td><span><?= $booking['meal'] ?></span></td>
                    <td><span><?= $booking['noofdays'] ?></span></td>
                    <td><span data-prefix>Tsh</span><span><?= $type_of_meal ?></span></td>
                    <td><span><?= $booking['NoofRoom'] ?></span></td>
                    <td><span data-prefix>Tsh</span><span><?= $mepr ?></span></td>
                </tr>
            </tbody>
        </table>
        <table class="balance">
            <tr>
                <th><span>Total</span></th>
                <td><span data-prefix>Tsh</span><span><?= $fintot ?></span></td>
            </tr>
            <tr>
                <th><span>Amount Paid</span></th>
                <td><span data-prefix>Tsh</span><span>0.00</span></td>
            </tr>
            <tr>
                <th><span>Balance Due</span></th>
                <td><span data-prefix>Tsh</span><span><?= $fintot ?></span></td>
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