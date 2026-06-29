<?php

session_start();

if (!isset($_SESSION['ssLoginPOS'])) {
    header("location: ../auth/login.php");
    exit();
}

require "../config/config.php";
require "../config/functions.php";
require "../module/mode-barang.php";

$title = "Form Barang - Toko Inda POS";
require "../template/header.php";
require "../template/navbar.php";
require "../template/sidebar.php";

if (isset($_GET['msg'])) {
    $msg = $_GET['msg'];
    $id = $_GET['id'];
    $sqlEdit = "SELECT * FROM tbl_barang WHERE id_barang = '$id'";
    $barang = getData($sqlEdit)[0];
} else {
    $msg = '';
}

$alert = '';

if (isset($_POST['simpan'])) {
    if ($msg != '') {
        if (update($_POST)) {
            echo "<script>document.location.href = 'index.php?msg=updated';</script>";
        } else {
            echo "<script>document.location.href = 'index.php';</script>";
        }
    } else {
        if (insert($_POST)) {
            $alert = '<div class="alert alert-success alert-dismissible">
                      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                      <h5><i class="icon fas fa-check"></i> Barang berhasil ditambahkan.</h5>
                    </div>';
        }
    }
}

?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Barang</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?= $main_url ?>dashboard.php">Home</a></li>
              <li class="breadcrumb-item"><a href="<?= $main_url ?>barang">Barang</a></li>
              <li class="breadcrumb-item active"><?= $msg != '' ? 'Edit Barang' : 'Tambah Barang' ?></li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>

    <section class="content">
        <div class="container-field">
            <div class="card">
                <form action="" method="post" enctype="multipart/form-data">
                <?php if ($alert != '')
                    echo $alert;
                ?>
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-pen fa-sm"></i><?= $msg != '' ? ' Edit Barang' : ' Input Barang' ?></h3>
                    <button type="submit" name="simpan" class="btn btn-primary btn-sm float-right"><i class="fas fa-save"></i> Simpan</button>
                    <button type="reset" class="btn btn-danger btn-sm float-right mr-1"><i class="fa fa-undo fa-sm"></i> Reset</button>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-8 mb-3 pr-3">
                            <div class="form-group">
                                <label for="kode">Kode Barang</label>
                                <input type="text" name="kode" value="<?= $msg != '' ? $barang['id_barang'] : generateId() ?>" class="form-control" id="kode" readonly>
                            </div>
                            <div class="form-group">
                                <label for="barcode">Barcode *</label>
                                <input type="text" name="barcode" class="form-control" id="barcode" value="<?= $msg != '' ? $barang['barcode'] : null ?>"placeholder="Masukkan barcode barang" autofocus autocomplete="off" required>
                            </div>
                            <div class="form-group">
                                <label for="name">Nama Barang *</label>
                                <input type="text" name="name" class="form-control" id="name" value="<?= $msg != '' ? $barang['nama_barang'] : null ?>" placeholder="Masukkan nama barang" autofocus autocomplete="off" required>
                            </div>
                            <div class="form-group">
                                <label for="kategori">Kategori Barang *</label>
                                <select name="kategori" class="form-control" id="kategori" required>
                                    <?php
                                        if ($msg != '') {
                                            $kategori = ["Elektronik", "ATK", "Makanan", "Minuman"];
                                            foreach ($kategori as $ket) {
                                                if ($barang['kategori'] == $ket) { ?>
                                                    <option value="<?= $ket ?>" selected><?= $ket ?></option>
                                                <?php } else { ?>
                                                    <option value="<?= $ket ?>"><?= $ket ?></option>
                                                <?php
                                                }
                                            }
                                        } else { ?>
                                        <option value="">-- Pilih Kategori --</option>
                                        <option value="Elektronik">Elektronik</option>
                                        <option value="ATK">ATK</option>
                                        <option value="Makanan">Makanan</option>
                                        <option value="Minuman">Minuman</option>
                                    <?php
                                        }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="satuan">Satuan *</label>
                                <select name="satuan" class="form-control" id="satuan" required>
                                    <?php
                                        if ($msg != '') {
                                            $satuan = ["Piece", "Botol", "Kaleng", "Pouch"];
                                            foreach ($satuan as $sat) {
                                                if ($barang['satuan'] == $sat) { ?>
                                                    <option value="<?= $sat ?>" selected><?= $sat ?></option>
                                                <?php } else { ?>
                                                    <option value="<?= $sat ?>"><?= $sat ?></option>
                                                <?php
                                                }
                                            }
                                        } else { ?>
                                    <option value="">-- Pilih Satuan --</option>
                                    <option value="Piece">Piece</option>
                                    <option value="Botol">Botol</option>
                                    <option value="Kaleng">Kaleng</option>
                                    <option value="Pouch">Pouch</option>
                                    <?php
                                        }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="harga_beli">Harga Beli *</label>
                                <input type="number" name="harga_beli" class="form-control" id="harga_beli" value="<?= $msg != '' ? $barang['harga_beli'] : null ?>" placeholder="Rp 0" autocomplete="off" required>
                            </div>
                            <div class="form-group">
                                <label for="harga_jual">Harga Jual *</label>
                                <input type="number" name="harga_jual" class="form-control" id="harga_jual" value="<?= $msg != '' ? $barang['harga_jual'] : null ?>" placeholder="Rp 0" autocomplete="off" required>
                            </div>
                            <div class="form-group">
                                <label for="stock_minimal">Stock Minimal *</label>
                                <input type="number" name="stock_minimal" class="form-control" id="stock_minimal" value="<?= $msg != '' ? $barang['stock_minimal'] : null ?>" placeholder="0" autocomplete="off" required>
                            </div>
                        </div>
                        <div class="col-lg-4 mb-3 text-center px-3">
                            <input type="hidden" name="oldImg" value="<?= $msg != '' ? $barang['gambar'] : null ?>"></input>
                            <img src="<?= $main_url ?>asset/image/<?= $msg != '' ? $barang['gambar'] : 'default-brg.jpg' ?>" class="profile-user-img mb-3 mt-4" alt="">
                            <input type="file" class="form-control" name="image">
                            <span class="text-sm">Type file gambar JPG | PNG | GIF</span>
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