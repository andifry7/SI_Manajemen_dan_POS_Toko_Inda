<?php

session_start();

if (!isset($_SESSION['ssLoginPOS'])) {
    header("location: ../auth/login.php");
    exit();
}

require "../config/config.php";
require "../config/functions.php";
require "../module/mode-password.php";

$title = "Ganti Password - Toko Inda";
require "../template/header.php";
require "../template/navbar.php";
require "../template/sidebar.php";

// update password
if (isset($_POST['simpan'])) {
    if (update($_POST)) {
        echo "<script>
                alert('Password berhasil diubah!');
                document.location = 'change-password.php?';
            </script>";
    }
}

if (isset($_GET['msg'])) {
    $msg = $_GET['msg'];
} else {
    $msg = "";
}

$alert1 = '<small class="text-danger pl-2 font-italic">Konfirmasi password tidak sama dengan password baru</small>';
$alert2 = '<small class="text-danger pl-2 font-italic">Password saat ini salah</small>';

?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Password</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?= $main_url ?>dashboard.php">Home</a></li>
              <li class="breadcrumb-item active">Password</li>
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
                        <h3 class="card-title"><i class="fas fa-key"></i> Ganti Password</h3>
                        <button type="submit" name="simpan" class="btn btn-primary btn-sm float-right"><i class="fas fa-edit fa-sm"></i> Submit</button>
                        <button type="reset" name="reset" class="btn btn-danger btn-sm float-right mr-2"><i class="fas fa-undo fa-sm"></i> Reset</button>
                    </div>
                    <div class="card-body">
                        <div class="col-lg-8 mb-3">
                            <div class="form-group">
                                <label for="curPass"> Password Saat Ini</label>
                                <input type="password" name="curPass" id="curPass" class="form-control" placeholder="Masukkan password anda saat ini" required>
                                <?php if ($msg == "err2") 
                                    echo $alert2;
                                ?>
                            </div>
                        </div>
                        <div class="col-lg-8 mb-3">
                            <div class="form-group">
                                <label for="newPass"> Password Baru</label>
                                <input type="password" name="newPass" id="newPass" class="form-control" placeholder="Masukkan password baru anda" required>
                            </div>
                        </div>
                        <div class="col-lg-8 mb-3">
                            <div class="form-group">
                                <label for="confPass"> Konfirmasi Password</label>
                                <input type="password" name="confPass" id="confPass" class="form-control" placeholder="Masukkan kembali password baru anda" required>
                                <?php if ($msg == "err1") 
                                    echo $alert1;
                                ?>
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