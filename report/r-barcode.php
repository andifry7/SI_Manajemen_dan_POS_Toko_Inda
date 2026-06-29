<?php

session_start();

if (!isset($_SESSION['ssLoginPOS'])) {
    header("location: ../auth/login.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Barcode</title>
    <style>
        body {
            margin: 0;
            padding: 20px;
        }

        .print-area {
            display: grid;
            grid-template-columns: repeat(3, 210px);
            justify-content: center;
            gap: 30px;
        }

        .barcode-item {
            width: 210px;
            text-align: center;
            break-inside: avoid;
        }
    </style>
</head>
<body>
    <div class="print-area">
    <?php

    $jmlCetak = $_GET['jmlCetak'];
    for ($i=1; $i <= $jmlCetak; $i++) { ?>
        <div class="barcode-item">
            <?php
            
            $barcode = $_GET['barcode'];

            require '../asset/barcodeGenerator/vendor/autoload.php';
            
            $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
            echo '<img src="data:image/png;base64,' . base64_encode($generator->getBarcode($barcode, $generator::TYPE_CODE_128)) . '"width="200px">';
            ?>
            <div><?= $barcode ?></div>
        </div>
    <?php
    }
    ?>
    </div>

    <script>
        window.print();
    </script>

</body>
</html>
