<?php

session_start();

if (!isset($_SESSION['ssLoginPOS'])) {
    header("location: ../auth/login.php");
    exit();
}

require "../config/config.php";
require "../config/functions.php";
require "../module/mode-user.php";

$title = "Pengguna - Toko Inda";
require "../template/header.php";
require "../template/navbar.php";
require "../template/sidebar.php";

?>

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
              <li class="breadcrumb-item active">Pengguna</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-list fa-sm"></i> Data Pengguna</h3>
                    <div class="card-tools">
                        <a href="<?= $main_url ?>user/add-user.php" class="btn btn-primary btn-sm"><i class="fas fa-plus fa-sm"></i> Tambah Pengguna</a>
                    </div>
                </div>
                <div class="card-body table-responsive p-3">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Foto</th>
                                <th>Username</th>
                                <th>Nama Lengkap</th>
                                <th>Alamat</th>
                                <th>Level Pengguna</th>
                                <th style="width: 10%;">Operasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $users = getData("SELECT * FROM tbl_user");
                            foreach ($users as $user) : ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><img src="../asset/image/<?= $user['foto'] ?>" class="rounded-circle" alt="" width="60px"></td>
                                    <td><?= $user['username'] ?></td>
                                    <td><?= $user['fullname'] ?></td>
                                    <td><?= $user['address'] ?></td>
                                    <td>
                                        <?php
                                        if ($user['level'] == 1) {
                                            echo "Administrator";
                                        } else {
                                            echo "Operator";
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <a href="edit-user.php?id=<?= $user['userid'] ?>" class="btn btn-warning btn-sm" title="edit user"><i class="fas fa-user-edit"></i></a>
                                        <button type="button" class="btn btn-danger btn-sm" title="hapus user"
                                            onclick="hapusUser('<?= $user['userid'] ?>', '<?= $user['foto'] ?>')">
                                            <i class="fas fa-user-times"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function hapusUser(id, foto) {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Data pengguna ini akan dihapus permanen dan tidak dapat dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'del-user.php?id=' + encodeURIComponent(id) + '&foto=' + encodeURIComponent(foto);
        }
    });
}

<?php if (isset($_GET['status'])): ?>
    <?php if ($_GET['status'] === 'success'): ?>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: 'Pengguna berhasil dihapus.',
            timer: 2000,
            showConfirmButton: false
        });
    <?php elseif ($_GET['status'] === 'error'): ?>
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: 'Pengguna gagal dihapus.'
        });
    <?php endif; ?>
<?php endif; ?>
</script>

<?php 

require "../template/footer.php";

?>