<?php

session_start();

if (!isset($_SESSION['ssLoginPOS'])) {
    header("location: ../auth/login.php");
    exit();
}

require_once "../config/config.php";
require_once "../config/functions.php";
require_once "../module/mode-stok-keluar.php";

$title = "Stok Keluar - Toko Inda";
require_once "../template/header.php";
require_once "../template/navbar.php";
require_once "../template/sidebar.php";

if (isset($_GET['msg'])) {
    $msg = $_GET['msg'];
} else {
    $msg = '';
}

$alert = '';
// jalankan fungsi hapus barang
if ($msg == 'deleted') {
    $id = $_GET['id'];
    if (delete($id)) {
        $alert = "<script>
                    $(document).ready(function() {
                        $(document).Toasts('create', {
                            title: 'Sukses',
                            body: 'Data stok keluar berhasil dihapus..',
                            class: 'bg-success',
                            icon: 'fas fa-check-circle'
                        })
                    });
                </script>";
    }
}

if ($msg == 'added') {
    $alert = "<script>
                $(document).ready(function() {
                    $(document).Toasts('create', {
                        title: 'Sukses',
                        body: 'Data stok keluar berhasil ditambahkan..',
                        class: 'bg-success',
                        icon: 'fas fa-check-circle'
                    })
                });
            </script>";
}

?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Stok Keluar</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?= $main_url ?>dashboard.php">Home</a></li>
              <li class="breadcrumb-item active">Stok Keluar</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <?php if ($alert != '') {
                    echo $alert;
                }
                ?>
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-list fa-sm"></i> Data Stok Keluar</h3>
                    <a href="<?= $main_url ?>stok-keluar/form-stok-keluar.php" class="mr-2 btn btn-primary btn-sm float-right"><i class="fas fa-plus"></i> Tambah Stok Keluar</a>
                </div>
                <div class="card-body table-responsive p-3">
                    <table class="table table-hover text-nowrap" id="tblData">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>ID Barang</th>
                                <th>Nama Barang</th>
                                <th>Jumlah</th>
                                <th>Keterangan</th>
                                <th>Tanggal</th>
                                <th style="width: 10%;" class="text-center">Operasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $barang = getData("SELECT * FROM tbl_barang_keluar ORDER BY tgl_keluar DESC");
                            foreach ($barang as $brg) { ?>
                                <tr>
                                    <td> <?= $no++ ?> </td>
                                    <td> <?= $brg['id_barang'] ?> </td>
                                    <td> <?= $brg['nama_barang'] ?> </td>
                                    <td> <?= $brg['qty'] ?> </td>
                                    <td> <?= $brg['keterangan'] ?> </td>
                                    <td> <?= $brg['tgl_keluar'] ?> </td>
                                    <td class="text-center">
                                        <a href="?id=<?= $brg['id'] ?>&msg=deleted" class="btn btn-danger btn-sm" title="hapus stok keluar" onclick="return confirm('Anda yakin akan menghapus ini?')"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>


<?php

require_once "../template/footer.php";

?>
