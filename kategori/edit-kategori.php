<?php

session_start();

if (!isset($_SESSION['ssLoginPOS'])) {
    header("location: ../auth/login.php");
    exit();
}

require "../config/config.php";
require "../config/functions.php";
require "../module/mode-kategori.php";

$title = "Edit Kategori - Toko Inda";
require "../template/header.php";
require "../template/navbar.php";
require "../template/sidebar.php";

// jalanakan fungsi update data
if (isset($_POST['update'])) {
    if (update($_POST)) {
        echo "
            <script>
                document.location.href = 'data-kategori.php?msg=updated';
            </script>
        ";
    }
}

$id = $_GET['id'];

$sqlEdit = "SELECT * FROM tbl_kategori WHERE id_kategori = '$id'";
$kategori = getData($sqlEdit)[0];


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
              <li class="breadcrumb-item"><a href="<?= $main_url ?>kategori/data-kategori.php">Kategori</a></li>
              <li class="breadcrumb-item active">Edit Kategori</li>
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
                    <h3 class="card-title"><i class="fas fa-pen fa-sm"></i> Edit Kategori</h3>
                    <button type="submit" name="update" class="btn btn-primary btn-sm float-right"><i class="fas fa-save"></i> Update</button>
                    <button type="reset" class="btn btn-danger btn-sm float-right mr-1"><i class="fa fa-undo fa-sm"></i> Reset</button>
                </div>
                <div class="card-body">
                    <div class="row">
                        <input type="hidden" name="id" value="<?= $kategori['id_kategori'] ?>">
                        <div class="col-lg-8 mb-3">
                            <div class="form-group">
                                <label for="name">Nama</label>
                                <input type="text" name="nama" class="form-control" id="nama" placeholder="Masukkan nama kategori" autofocus value="<?= $kategori['nama'] ?>" required>
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