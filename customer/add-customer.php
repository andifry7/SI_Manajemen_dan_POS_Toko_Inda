<?php

session_start();

if (!isset($_SESSION['ssLoginPOS'])) {
    header("location: ../auth/login.php");
    exit();
}

require "../config/config.php";
require "../config/functions.php";
require "../module/mode-customer.php";

$title = "Tambah Customer - Toko Inda";
require "../template/header.php";
require "../template/navbar.php";
require "../template/sidebar.php";

$alert = '';

if (isset($_POST['simpan'])) {
    if (insert($_POST)) {
        $alert = '<div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="icon fas fa-check"></i>Customer berhasil ditambahkan.
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        </div>';
    }
}

?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Customer</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?= $main_url ?>dashboard.php">Home</a></li>
              <li class="breadcrumb-item"><a href="<?= $main_url ?>customer/data-customer.php">Customer</a></li>
              <li class="breadcrumb-item active">Tambah Customer</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>

<section class="content">
    <div class="container-fluid">
        <div class="card">
            <form action="" method="post">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-plus fa-sm"></i> Tambah Customer</h3>
                    <button type="submit" name="simpan" class="btn btn-primary btn-sm float-right"><i class="fas fa-save"></i> Simpan</button>
                    <button type="reset" class="btn btn-danger btn-sm float-right mr-1"><i class="fa fa-undo fa-sm"></i> Reset</button>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-8 mb-3">
                        <?php if ($alert != '') {
                            echo $alert;
                        } ?>
                            <div class="form-group">
                                <label for="name">Nama</label>
                                <input type="text" name="nama" class="form-control" id="nama" placeholder="Masukkan nama customer" autofocus aoutocomplete="off" required>
                            </div>
                            <div class="form-group">
                                <label for="telpon">Telepon</label>
                                <input type="text" name="telpon" class="form-control" id="telpon" placeholder="Masukkan nomor telepon customer" pattern="[0-9]{5,}" title="Minimal 5 angka">
                            </div>
                            <div class="form-group">
                                <label for="ketr">Deskripsi</label>
                                <textarea name="ketr" id="ketr" rows="1" class="form-control" placeholder="Masukkan keterangan customer" required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="alamat">Alamat</label>
                                <textarea name="alamat" id="alamat" rows="3" class="form-control" placeholder="Masukkan alamat customer"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
<?php 

require "../template/footer.php";

?>