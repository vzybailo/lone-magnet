<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        @page {
            size: A4;
            margin: 5mm;
        }
        html, body {
            margin: 0;
            padding: 0;
            font-family: sans-serif;
            font-size: 10pt;
        }
        .photo-img {
            width: 50.8mm;
            height: 50.8mm;
            object-fit: cover;
        }
        .photo-caption {
            font-size: 8pt;
            margin-top: 1mm;
        }
        .site-caption {
            font-size: 8pt;
            margin-bottom: 1mm;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }
        td {
            width: 33.33%;
            height: 95.666mm;
            padding: 0;
            margin: 0;
            border-bottom: 0.1mm dashed #999;
            border-right: 0.1mm dashed #999;
        }
        td:last-child {
            border-right: none;
        }
        tr:last-child td {
            border-bottom: none;
        }
        .cell-inner {
            display: table;
            width: 100%;
            height: 95.666mm; /* обязательно */
        }
        .cell-content {
            display: table-cell;
            vertical-align: middle;
            text-align: center;
        }

    </style>
</head>
<body>

<table>
    <?php for ($row = 0; $row < 3; $row++): ?>
        <tr>
            <?php for ($col = 0; $col < 3; $col++): ?>
                <?php $index = $row * 3 + $col; ?>
                <td>
                    <div class="cell-inner">
                        <div class="cell-content">
                            <?php if (!empty($photos[$index])): ?>
                                <div class="site-caption"><?= get_home_url(); ?></div>
                                <img class="photo-img" src="<?= $photos[$index] ?>" alt="Photo <?= $index + 1 ?>">
                                <div class="photo-caption">Order #<?= $order_id ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </td>
            <?php endfor; ?>
        </tr>
    <?php endfor; ?>
</table>

</body>
</html>
