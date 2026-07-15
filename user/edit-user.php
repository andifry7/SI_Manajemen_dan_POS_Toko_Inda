<?php

session_start();

if (!isset($_SESSION['ssLoginPOS'])) {
    header("location: ../auth/login.php");
    exit();
}

require "../config/config.php";
require "../config/functions.php";
require "../module/mode-user.php";

$title = "Update User - Toko Inda";
require "../template/header.php";
require "../template/navbar.php";
require "../template/sidebar.php";

$id = $_GET['id'];

$sqlEdit = "SELECT * FROM tbl_user WHERE userid = '$id'";
$user = getData($sqlEdit)[0];
$level = $user['level'];

if (isset($_POST['koreksi'])) {
    if (update($_POST) > 0) {
        $_SESSION['success'] = "Data pengguna berhasil di-update.";
    } else {
        $_SESSION['error'] = "Data pengguna gagal di-update.";
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
                    <div class="col-lg-4">

                        <input type="hidden" name="oldImg" value="<?= $user['foto'] ?>">

                        <div class="card shadow-sm">
                            <div class="card-header bg-warning">
                                <h3 class="card-title">
                                    <i class="fas fa-user-edit"></i>
                                    Foto Pengguna
                                </h3>
                            </div>

                            <div class="card-body text-center">

                                <img
                                    src="<?= $main_url ?>asset/image/<?= $user['foto'] ?>"
                                    id="previewImage"
                                    class="img-circle elevation-2 mb-3"
                                    style="width:180px;height:180px;object-fit:cover;border:4px solid #dee2e6;"
                                    alt="Preview">

                                <div class="custom-file mb-3">

                                    <input
                                        type="file"
                                        class="custom-file-input"
                                        id="image"
                                        name="image"
                                        accept=".jpg,.jpeg,.png,.gif">

                                    <label class="custom-file-label text-left" for="image">
                                        Pilih gambar baru...
                                    </label>

                                </div>

                                <button
                                    type="button"
                                    id="resetImage"
                                    class="btn btn-outline-danger btn-sm">

                                    <i class="fas fa-trash"></i>
                                    Batalkan Ganti Foto

                                </button>

                                <hr>

                                <small class="text-muted d-block">
                                    Foto saat ini
                                </small>

                                <span class="badge badge-success">JPG</span>
                                <span class="badge badge-info">PNG</span>
                                <span class="badge badge-warning">GIF</span>

                                <br><br>

                                <small class="text-muted">
                                    Kosongkan jika tidak ingin mengganti foto.
                                </small>

                            </div>
                        </div>

                    </div>
                    </div>
                </div>
              </form>
            </div>
        </div>
      </section>

<style>
  <?php include 'style.css'; ?>
</style>

<script>

const imageInput = document.getElementById("image");
const preview = document.getElementById("previewImage");
const resetBtn = document.getElementById("resetImage");

const oldImage = "<?= $main_url ?>asset/image/<?= $user['foto'] ?>";

imageInput.addEventListener("change", function(){

    const file = this.files[0];

    if(!file){
        preview.src = oldImage;
        return;
    }

    const ext = file.name.split('.').pop().toLowerCase();

    const allow = ['jpg','jpeg','png','gif'];

    if(!allow.includes(ext)){

        Swal.fire({
            icon: 'warning',
            title: 'Format Tidak Didukung',
            text: 'Format gambar harus JPG, JPEG, PNG, atau GIF.',
            confirmButtonColor: '#f39c12'
        });

        this.value = "";

        preview.src = oldImage;

        document.querySelector(".custom-file-label").innerHTML = "Pilih gambar baru...";

        return;
    }

    document.querySelector(".custom-file-label").innerHTML = file.name;

    const reader = new FileReader();

    reader.onload = function(e){
        preview.src = e.target.result;
    }

    reader.readAsDataURL(file);

});


resetBtn.addEventListener("click", function(){

    imageInput.value = "";

    preview.src = oldImage;

    document.querySelector(".custom-file-label").innerHTML = "Pilih gambar baru...";

});

</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php

require "../template/footer.php";

?>

<?php if (isset($_SESSION['success'])) : ?>
<script>
Swal.fire({
    icon: 'success',
    title: 'Berhasil!',
    text: '<?= $_SESSION['success']; ?>',
    confirmButtonColor: '#28a745',
    confirmButtonText: 'OK'
});
</script>
<?php unset($_SESSION['success']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error'])) : ?>
<script>
Swal.fire({
    icon: 'error',
    title: 'Gagal!',
    text: '<?= $_SESSION['error']; ?>',
    confirmButtonColor: '#dc3545',
    confirmButtonText: 'OK'
});
</script>
<?php unset($_SESSION['error']); ?>
<?php endif; ?>