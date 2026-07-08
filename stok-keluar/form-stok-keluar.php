<?php

session_start();

if (!isset($_SESSION['ssLoginPOS'])) {
    header("location: ../auth/login.php");
    exit();
}

require_once "../config/config.php";
require_once "../config/functions.php";
require_once "../module/mode-stok-keluar.php";

$title = "Form Stok Keluar - Toko Inda";
require_once "../template/header.php";
require_once "../template/navbar.php";
require_once "../template/sidebar.php";

if (isset($_GET['pilihbrg'])) {
    $idBarang = mysqli_real_escape_string($koneksi, $_GET['pilihbrg']);
    $sqlBarang = "SELECT * FROM tbl_barang WHERE id_barang = '$idBarang'";
    $selectedBarang = getData($sqlBarang)[0] ?? null;
} else {
    $selectedBarang = null;
}

$tgl = isset($_GET['tgl']) ? $_GET['tgl'] : date('Y-m-d');

$alert = '';

if (isset($_POST['simpan'])) {
    if (insert($_POST)) {
        echo "<script>document.location.href = 'index.php?msg=added';</script>";
    } else {
        $alert = '<div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5><i class="icon fas fa-times"></i> Data stok keluar gagal ditambahkan.</h5>
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
            <h1 class="m-0">Stok Keluar</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?= $main_url ?>dashboard.php">Home</a></li>
              <li class="breadcrumb-item"><a href="<?= $main_url ?>stok-keluar">Stok Keluar</a></li>
              <li class="breadcrumb-item active">Tambah Stok Keluar</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>

    <section class="content">
        <div class="container-field">
            <div class="card">
                <form action="" method="post" enctype="multipart/form-data">
                <?php if ($alert != '') {
                    echo $alert;
                } ?>
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-pen fa-sm"></i> Input Stok Keluar</h3>
                    <button type="submit" name="simpan" class="btn btn-primary btn-sm float-right"><i class="fas fa-save"></i> Simpan</button>
                    <button type="reset" class="btn btn-danger btn-sm float-right mr-1"><i class="fa fa-undo fa-sm"></i> Reset</button>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-8 mb-3 pr-3">
                            <div class="form-group">
                                <label for="tglKeluar">Tanggal</label>
                                <input type="date" name="tglKeluar" value="<?= $tgl ?>" class="form-control" id="tglKeluar">
                            </div>
                            <div class="form-group">
                                <label for="kodeBrg">Kode Barang</label>
                                <select name="kodeBrg" id="kodeBrg" class="form-control" autofocus required>
                                    <option value="">-- Pilih Kode Barang --</option>
                                        <?php
                                        $daftarBarang = getData("SELECT * FROM tbl_barang");
                                        foreach ($daftarBarang as $brg) { ?>
                                            <option value="<?= $brg['id_barang'] ?>" <?= @$_GET['pilihbrg'] == $brg['id_barang'] ? 'selected' : null ?>><?= $brg['id_barang'] . " | " . $brg['nama_barang'] ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="name">Nama Barang *</label>
                                <input type="text" name="namaBrg" class="form-control" id="name" value="<?= $selectedBarang ? $selectedBarang['nama_barang'] : '' ?>" placeholder="Masukkan nama barang" autocomplete="off" readonly>
                            </div>
                            <div class="form-group">
                                <label for="satuan">Satuan *</label>
                                <input type="text" name="satuan" class="form-control" id="satuan" value="<?= $selectedBarang ? $selectedBarang['satuan'] : '' ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label for="stokAwal">Stok Awal *</label>
                                <input type="number" name="stokAwal" class="form-control" id="stokAwal" value="<?= $selectedBarang ? $selectedBarang['stock'] : '' ?>" placeholder="0" autocomplete="off" readonly>
                            </div>
                            <div class="form-group">
                                <label for="qty">Jumlah Keluar *</label>
                                <input type="number" name="qty" class="form-control" id="qty" value="<?= $selectedBarang ? 1 : '' ?>" placeholder="0" autocomplete="off" required>
                            </div>
                            <div class="form-group">
                                <label for="keterangan">Keterangan *</label>
                                <input type="text" name="keterangan" class="form-control" id="keterangan" value="" placeholder="Rusak/Hilang/Kadaluwarsa/etc" autocomplete="off" required>
                            </div>
                        </div>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </section>

<script>
    let pilihbrg = document.getElementById('kodeBrg');
    let tgl = document.getElementById('tglKeluar');

    pilihbrg.addEventListener('change', function() {
        document.location.href = '?pilihbrg=' + this.value + '&tgl=' + tgl.value;
    });
</script>

<?php

require_once "../template/footer.php";

?>
