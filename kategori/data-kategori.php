<?php

session_start();

if (!isset($_SESSION['ssLoginPOS'])) {
    header("location: ../auth/login.php");
    exit();
}

require "../config/config.php";
require "../config/functions.php";
require "../module/mode-kategori.php";

$title = "Data Kategori - Toko Inda";
require "../template/header.php";
require "../template/navbar.php";
require "../template/sidebar.php";

if (isset($_GET['msg'])) {
    $msg = $_GET['msg'];
} else {
    $msg = '';
}

$alert = '';
if ($msg == 'deleted') {
    $alert = '<div class="alert alert-success alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  <h5><i class="icon fas fa-check"></i> Kategori berhasil dihapus.</h5>
                </div>';
}

if ($msg == 'aborted') {
    $alert = '<div class="alert alert-danger alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  <h5><i class="icon fas fa-exclamation-triangle"></i> Gagal menghapus kategori.</h5>
                </div>';
}

if ($msg == 'updated') {
    $alert = '<div class="alert alert-success alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  <h5><i class="icon fas fa-check"></i> Kategori berhasil diperbarui.</h5>
                </div>';
}

?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Kategori</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?= $main_url ?>dashboard.php">Home</a></li>
              <li class="breadcrumb-item active">Data Kategori</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>

    <section class="container-fluid">
        <div class="card">
            <?php if ($alert != '') {
                echo $alert;
                }?>
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-list fa-sm"></i> Data Kategori</h3>
                <a href="<?= $main_url ?>kategori/add-kategori.php" class="btn btn-sm btn-primary float-right"><i class="fas fa-plus fa-sm"></i> Tambah Kategori</a>
            </div>
            <div class="card-body table-responsive p-3">
                <table class="table table-hover text-nowrap" id="tblData">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th style="width: 10%;">Operasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        $categories = getData("SELECT * FROM tbl_kategori");
                        foreach ($categories as $category) :
                        ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $category['nama'] ?></td>
                                <td>
                                    <a href="edit-kategori.php?id=<?= $category['id_kategori'] ?>" class="btn btn-sm btn-warning" title="edit kategori"><i class="fas fa-pen"></i></a>
                                    <a href="del-kategori.php?id=<?= $category['id_kategori'] ?>" class="btn btn-sm btn-danger" title="hapus kategori" onclick="return confirm('Anda yakin akan menghapus kategori ini?')"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

<?php 

require "../template/footer.php";

?>