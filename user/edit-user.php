<?php

session_start();

if (!isset($_SESSION['ssLoginPOS'])) {
    header("location: ../auth/login.php");
    exit();
}

require "../config/config.php";
require "../config/functions.php";
require "../module/mode-user.php";

$title = "Update User - Toko Inda POS";
require "../template/header.php";
require "../template/navbar.php";
require "../template/sidebar.php";

$id = $_GET['id'];

$sqlEdit = "SELECT * FROM tbl_user WHERE userid = '$id'";
$user = getData($sqlEdit)[0];
$level = $user['level'];

if (isset($_POST['koreksi'])) {
    if (update($_POST)) {
        echo '<script>
            alert("Data user berhasil di-update..");
            document.location.href = "data-user.php";
            </script>';
    }
}

?>

<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Pengguna</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?= $main_url ?>dashboard.php">Home</a></li>
              <li class="breadcrumb-item"><a href="<?= $main_url ?>user/data-user.php">Pengguna</a></li>
              <li class="breadcrumb-item active">Edit Pengguna</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="card">
              <form action="" method="post" enctype="multipart/form-data">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-pen fa-sm"></i> Edit Pengguna</h3>
                    <button type="submit" name="koreksi" class="btn btn-primary btn-sm float-right"><i class="fas fa-save"></i> Koreksi</button>
                    <button type="reset" class="btn btn-danger btn-sm float-right mr-1"><i class="fa fa-undo fa-sm"></i> Reset</button>
                </div>
                <div class="card-body">
                  <div class="row">
                    <input type="hidden" value="<?= $user['userid'] ?>" name="id">
                    <div class="col-lg-8 mb-3">
                      <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" name="username" class="form-control" id="username" placeholder="Masukkan username" autofocus aoutocomplete="off" value="<?= $user['username'] ?>"required>
                      </div>
                      <div class="form-group">
                        <label for="fullname">Nama Lengkap</label>
                        <input type="text" name="fullname" class="form-control" id="fullname" placeholder="Masukkan nama lengkap" value="<?= $user['fullname'] ?>" required>
                      </div>
                      <div class="form-group">
                        <label for="level">Level</label>
                        <select name="level" id="level" class="form-control" required>
                          <option value="">-- Pilih level --</option>
                          <option value="1" <?= selectUser1($level) ?>>Administrator</option>
                          <option value="2" <?= selectUser2($level) ?>>Operator</option>
                        </select>
                      </div>
                      <div class="form-group">
                        <label for="address">Alamat</label>
                        <textarea name="address" id="address" cols="" row="3" class="form-control" placeholder="Masukkan alamat pengguna" required><?= $user['address'] ?></textarea>
                      </div>
                    </div>
                    <div class="col-lg-4 text-center">
                      <input type="hidden" name="oldImg" value="<?= $user['foto'] ?>">
                      <img src="<?= $main_url ?>asset/image/<?= $user['foto'] ?>" alt="Default User" class="profile-user-img img-circle mb-3">
                      <input type="file" class="form-control" name="image">
                      <span class="text-sm">Type file gambar JPG | PNG | GIF</span><br>
                      <span class="text-sm">Width = Height</span>
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