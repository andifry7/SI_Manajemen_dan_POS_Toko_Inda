<?php

session_start();

if (!isset($_SESSION['ssLoginPOS'])) {
    header("location: ../auth/login.php");
    exit();
}

require "../config/config.php";
require "../config/functions.php";
require "../module/mode-barang.php";

$title = "Form Barang - Toko Inda";
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
                                    <option value="">-- Pilih Kategori --</option>

                                    <?php
                                    // Ambil semua kategori dari database
                                    $queryKategori = mysqli_query($koneksi, "SELECT * FROM tbl_kategori ORDER BY nama ASC");

                                    while ($dataKategori = mysqli_fetch_assoc($queryKategori)) {

                                        // Saat edit barang
                                        if ($msg != '' && $barang['kategori'] == $dataKategori['nama']) {
                                            $selected = "selected";
                                        } else {
                                            $selected = "";
                                        }
                                    ?>
                                        <option value="<?= $dataKategori['nama']; ?>" <?= $selected; ?>>
                                            <?= $dataKategori['nama']; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="satuan">Satuan *</label>
                                <select name="satuan" class="form-control" id="satuan" required>
                                    <option value="">-- Pilih Satuan --</option>

                                    <?php
                                    // Ambil semua satuan dari database
                                    $querySatuan = mysqli_query($koneksi, "SELECT * FROM tbl_satuan ORDER BY nama ASC");

                                    while ($dataSatuan = mysqli_fetch_assoc($querySatuan)) {

                                        // Saat edit barang
                                        if ($msg != '' && $barang['satuan'] == $dataSatuan['nama']) {
                                            $selected = "selected";
                                        } else {
                                            $selected = "";
                                        }
                                    ?>
                                        <option value="<?= $dataSatuan['nama']; ?>" <?= $selected; ?>>
                                            <?= $dataSatuan['nama']; ?>
                                        </option>
                                    <?php } ?>
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
                        <div class="col-lg-4">

                            <input type="hidden"
                                name="oldImg"
                                value="<?= $msg != '' ? $barang['gambar'] : '' ?>">

                            <div class="card shadow-sm">

                                <div class="card-header bg-info">
                                    <h3 class="card-title">
                                        <i class="fas fa-box-open"></i>
                                        Gambar Barang
                                    </h3>
                                </div>

                                <div class="card-body text-center">

                                    <img
                                        id="previewImage"
                                        src="<?= $main_url ?>asset/image/<?= $msg != '' ? $barang['gambar'] : 'default-brg.jpg' ?>"
                                        class="elevation-2 mb-3"
                                        style="
                                            width:220px;
                                            height:220px;
                                            object-fit:cover;
                                            border-radius:15px;
                                            border:4px solid #dee2e6;
                                        "
                                        alt="Preview Barang">

                                    <div class="custom-file mb-3">

                                        <input
                                            type="file"
                                            id="image"
                                            name="image"
                                            class="custom-file-input"
                                            accept=".jpg,.jpeg,.png,.gif">

                                        <label class="custom-file-label text-left" for="image">
                                            Pilih gambar barang...
                                        </label>

                                    </div>

                                    <button
                                        type="button"
                                        id="resetImage"
                                        class="btn btn-outline-danger btn-sm">

                                        <i class="fas fa-trash"></i>
                                        Reset Gambar

                                    </button>

                                    <hr>

                                    <small class="text-muted d-block mb-2">
                                        Format yang didukung
                                    </small>

                                    <span class="badge badge-success">JPG</span>
                                    <span class="badge badge-info">PNG</span>
                                    <span class="badge badge-warning">GIF</span>

                                    <br><br>

                                    <small class="text-muted">
                                        Disarankan ukuran 500 × 500 px
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

const defaultImage = "<?= $main_url ?>asset/image/<?= $msg != '' ? $barang['gambar'] : 'default-brg.jpg' ?>";

imageInput.addEventListener("change", function(){

    const file = this.files[0];

    if(!file){
        preview.src = defaultImage;
        return;
    }

    const ext = file.name.split('.').pop().toLowerCase();

    const allow = ['jpg','jpeg','png','gif'];

    if(!allow.includes(ext)){

        alert("Format gambar harus JPG, JPEG, PNG, atau GIF");

        this.value = "";

        preview.src = defaultImage;

        document.querySelector(".custom-file-label").innerHTML = "Pilih gambar barang...";

        return;
    }

    document.querySelector(".custom-file-label").innerHTML = file.name;

    const reader = new FileReader();

    reader.onload = function(e){
        preview.src = e.target.result;
    }

    reader.readAsDataURL(file);

});

resetBtn.addEventListener("click",function(){

    imageInput.value = "";

    preview.src = defaultImage;

    document.querySelector(".custom-file-label").innerHTML = "Pilih gambar barang...";

});

</script>

<?php

require "../template/footer.php";

?>