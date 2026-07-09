<?php

session_start();

if (!isset($_SESSION['ssLoginPOS'])) {
    header("location: ../auth/login.php");
    exit();
}

require_once "../config/config.php";
require_once "../config/functions.php";

$tgl1 = mysqli_real_escape_string($koneksi, $_GET['tgl1'] ?? '');
$tgl2 = mysqli_real_escape_string($koneksi, $_GET['tgl2'] ?? '');

if ($tgl1 != '' && $tgl2 != '') {
    $dataStokKeluar = getData("SELECT * FROM tbl_barang_keluar WHERE tgl_keluar BETWEEN '$tgl1' AND '$tgl2' ORDER BY tgl_keluar DESC");
} else {
    $dataStokKeluar = getData("SELECT * FROM tbl_barang_keluar ORDER BY tgl_keluar DESC");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Stok Keluar</title>
</head>
<body>
    <div style="text-align: center;">
        <h2 style="margin-bottom: -15px;">Rekap Laporan Stok Keluar</h2>
        <h2 style="margin-bottom: 15px;">Toko Inda</h2>
    </div>

    <table>
        <thead>
            <tr>
                <td colspan="6" style="height: 5px;">
                    <hr style="margin-bottom: 2px; margin-left: -5px; margin-top: 1px;", size="3", color="gray">
                </td>
            </tr>
            <tr>
                <th scope="col">No</th>
                <th scope="col" style="width: 120px;">ID Barang</th>
                <th scope="col" style="width: 200px;">Nama Barang</th>
                <th scope="col" style="width: 120px;">Tgl Stok Keluar</th>
                <th scope="col" style="width: 120px;">Jumlah</th>
                <th scope="col">Keterangan</th>
            </tr>
            <tr>
                <td colspan="6" style="height: 5px;">
                    <hr style="margin-bottom: 2px; margin-left: -5px; margin-top: 1px;", size="3", color="gray">
                </td>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            foreach ($dataStokKeluar as $data) { ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td style="text-align: center;"><?= $data['id_barang'] ?></td>
                    <td style="text-align: center;"><?= $data['nama_barang'] ?></td>
                    <td style="text-align: center;"><?= in_date($data['tgl_keluar']) ?></td>
                    <td style="text-align: center;">
                        <?= $data['qty'] ?>
                    </td>
                    <td style="text-align: center;">
                        <?= $data['keterangan'] ?>
                    </td>
                </tr>
            <?php
            }
            ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="6" style="height: 5px;">
                    <hr style="margin-bottom: 2px; margin-left: -5px; margin-top: 1px;", size="3", color="gray">
                </td>
            </tr>
        </tfoot>
    </table>

    <script>
        window.print();
    </script>
</body>
</html>
