<?php


session_start();

if (!isset($_SESSION['ssLoginPOS'])) {
    header("location: ../auth/login.php");
    exit();
}

require_once "../config/config.php";
require_once "../config/functions.php";

$title = "Laporan Stok Keluar - Toko Inda";
require_once "../template/header.php";
require_once "../template/navbar.php";
require_once "../template/sidebar.php";

$stokBrg = getData("SELECT * FROM tbl_barang_keluar ORDER BY tgl_keluar DESC");

?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Laporan Stok Keluar</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?= $main_url ?>dashboard.php">Home</a></li>
              <li class="breadcrumb-item active">Laporan Stok Keluar</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-list fa-sm"></i> Laporan Stok Keluar</h3>
                    <button type="button" class="btn btn-sm btn-outline-primary float-right" data-toggle="modal" data-target="#mdlPeriodeStokKeluar"><i class="fas fa-print"></i> Cetak</button>
                </div>
                <div class="card-body table-responsive p-3">
                    <table class="table table-hover text-nowrap" id="tblData">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>ID Barang</th>
                                <th>Nama Barang</th>
                                <th>Tanggal</th>
                                <th>Jumlah</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            foreach ($stokBrg as $stok) { ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= $stok['id_barang'] ?></td>
                                    <td><?= $stok['nama_barang'] ?></td>
                                                                        <td><?= in_date($stok['tgl_keluar']) ?></td>
                                                                        <td><?= $stok['qty'] ?></td>
                                                                        <td><?= $stok['keterangan'] ?></td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

        <div class="modal fade" id="mdlPeriodeStokKeluar">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Periode Stok Keluar</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                                <div class="form-group row">
                                        <label for="tgl1" class="col-sm-3 col-form-label">Tanggal Awal</label>
                                        <div class="col-sm-9">
                                                <input type="date" class="form-control" id="tgl1">
                                        </div>
                                </div>
                                <div class="form-group row">
                                        <label for="tgl2" class="col-sm-3 col-form-label">Tanggal Akhir</label>
                                        <div class="col-sm-9">
                                                <input type="date" class="form-control" id="tgl2">
                                        </div>
                                </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" onclick="printDoc()"><i class="fas fa-print"></i> Cetak</button>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                let tgl1 = document.getElementById('tgl1');
                let tgl2 = document.getElementById('tgl2');

                function printDoc() {
                        if (tgl1.value != '' && tgl2.value != '') {
                                window.open('../report/r-stok-keluar.php?tgl1=' + tgl1.value + '&tgl2=' + tgl2.value, 'width=900', 'height=600', 'left=100');
                        } else {
                                alert('Tanggal awal dan akhir harus diisi!');
                        }
                }
            </script>

<?php

require_once "../template/footer.php";

?>
