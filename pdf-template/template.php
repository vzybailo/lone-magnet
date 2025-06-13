<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        @page {
            size: A4;
            margin: 5mm;
        }

        body {
            font-family: sans-serif;
            font-size: 10pt;
            margin: 0;
            padding: 0;
        }

        table {
            width: 100%;
            height: 287mm; 
            border-collapse: collapse;
        }
        td {
            width: 33.33%;
            height: 95.666mm;
            text-align: center;
            vertical-align: middle;
            padding: 0;
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
    </style>
</head>
<body>

<table>
    <?php for ($row = 0; $row < 3; $row++): ?>
        <tr>
            <?php for ($col = 0; $col < 3; $col++): ?>
                <?php $index = $row * 3 + $col; ?>
                <td>
                    <?php if (!empty($photos[$index])): ?>
                        <div class="site-caption"><?= get_home_url(); ?></div>
                        <img class="photo-img" src="<?= $photos[$index] ?>" alt="Photo <?= $index + 1 ?>">
                        <div class="photo-caption">Order #<?= $order_id ?></div>
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
        </tr>
    <?php endfor; ?>
</table>

</body>
</html>
