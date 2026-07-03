<?php 

session_start();

if (!isset($_SESSION['ssLoginPOS'])) {
    header("location: ../auth/login.php");
    exit();
}

require "../config/config.php";
require "../config/functions.php";
require "../module/mode-jual.php";

$title = "Transaksi - Toko Inda POS";
require "../template/header.php";
require "../template/navbar.php";
require "../template/sidebar.php";

$nojual = generateNo();

?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Penjualan Barang</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?= $main_url ?>dashboard.php">Home</a></li>
              <li class="breadcrumb-item active">Tambah Penjualan</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>

    <section>
        <div class="container-fluid">
            <form action="" method="post">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="card card-outline card-warning p-3">
                            <div class="form-group row mb-2">
                                <label for="noNota" class="col-sm-2 col-form-label">No Nota</label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" id="noNota" name="nojual" value="<?= $nojual ?>">
                                </div>
                                <label for="tglNota" class="col-sm-2 col-form-label">Tanggal</label>
                                <div class="col-sm-4">
                                    <input type="date" class="form-control" id="tglNota" name="tglNota" value="<?= @$_GET['tgl'] ? $_GET['tgl'] : date('Y-m-d') ?>" required>
                                </div>
                            </div>
                            <div class="form-group row mb-2">
                                <label for="barcode" class="col-sm-2 col-form-label">Barcode</label>
                                <div class="col-sm-10 input-group">
                                    <input type="text" name="barcode" id="barcode" value="<?= @$_GET['barcode'] ? $_GET['barcode'] : '' ?>" class="form-control" placeholder="Masukkan barcode barang">
                                    <div class="input-group-append">
                                        <span class="input-group-text" id="icon-barcode"><i class="fas fa-barcode"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card card-outline card-danger pt-3 px-3 pb-2">
                            <h6 class="font-weight-bold text-right">Total Penjualan</h6>
                            <h1 class="font-weight-bold text-right" style="font-size: 40pt;">
                            <input type="hidden" name="total" value="<?= 0 ?>">0
                            </h1>
                        </div>
                    </div>
                </div>
                <div class="card pt-1 pb-2 px-3">
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="form-group">
                                <input type="hidden" value="<?= @$_GET['barcode'] ? $selectBrg['id_barang'] : '' ?>" name="barcode">
                                <label for="namaBrg">Nama Barang</label>
                                <input type="text" name="namaBrg" class="form-control form-control-sm" id="namaBrg" value="<?= @$_GET['barcode'] ? $selectBrg['nama_barang'] : '' ?>"readonly>
                            </div>
                        </div>
                        <div class="col-lg-1">
                            <div class="form-group">
                                <label for="kategori">Kategori</label>
                                <input type="text" name="kategori" class="form-control form-control-sm" id="kategori" value="<?= @$_GET['barcode'] ? $selectBrg['kategori'] : '' ?>"readonly>
                            </div>
                        </div>
                        <div class="col-lg-1">
                            <div class="form-group">
                                <label for="stok">Stok</label>
                                <input type="number" name="stok" class="form-control form-control-sm" id="stok" value="<?= @$_GET['barcode'] ? $selectBrg['stock'] : '' ?>" readonly>
                            </div>
                        </div>
                        <div class="col-lg-1">
                            <div class="form-group">
                                <label for="satuan">Satuan</label>
                                <input type="text" name="satuan" class="form-control form-control-sm" id="satuan" value="<?= @$_GET['barcode'] ? $selectBrg['satuan'] : '' ?>" readonly>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label for="harga">Harga</label>
                                <input type="number" name="harga" class="form-control form-control-sm" id="harga" value="<?= @$_GET['barcode'] ? $selectBrg['harga_jual'] : '' ?>" readonly>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label for="qty">Jumlah</label>
                                <input type="number" name="qty" class="form-control form-control-sm" id="qty" value="<?= @$_GET['barcode'] ? 1 : '' ?>">
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label for="jmlHarga">Total Harga</label>
                                <input type="number" name="jmlHarga" class="form-control form-control-sm" id="jmlHarga" value="<?= @$_GET['barcode'] ? $selectBrg['harga_jual'] : '' ?>" readonly>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-sm btn-info btn-block" name="addbrg"><i class="fas fa-cart-plus fa-sm"></i> Tambah Barang</button>
                </div>
                <div class="card card-outline card-success table-responsive px-2">
                    <table class="table table-sm table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th>Kategori</th>
                                <th class="text-right">Harga</th>
                                <th class="text-right">Jumlah</th>
                                <th class="text-right">Total Harga</th>
                                <th class="text-center" width="10%">Operasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>
                </div>
                <div class="row">
                    <div class="col-lg-4 p-2">
                        <div class="form-group row mb-2">
                            <label for="customer" class="col-sm-3 col-form-label col-form-label-sm">Customer</label>
                            <div class="col-sm-9">
                                <select name="customer" id="customer" class="form-control form-control-sm">
                                    <option value="">-- Pilih Customer --</option>
                                    <?php
                                    $customers = getData("SELECT * FROM tbl_customer");
                                    foreach ($customers as $customer) { ?>
                                        <option value="<?= $customer['nama'] ?>"><?= $customer['nama'] ?></option>    
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row mb-2">
                            <label for="ketr" class="col-sm-3 col-form-label">Keterangan</label>
                            <div class="col-sm-9">
                                <textarea name="ketr" id="ketr" class="form-control form-control-sm"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 py-2 px-3">
                        <div class="form-group row mb-2">
                            <label for="bayar" class = "col-sm-3 col-form-label">Bayar</label>
                            <div class="col-sm-9">
                                <input type="number" name="bayar" class="form-control form-control-sm text-right" id="bayar">
                            </div>
                        </div>
                        <div class="form-group row mb-2">
                            <label for="kembalian" class = "col-sm-3 col-form-label">Kembalian</label>
                            <div class="col-sm-9">
                                <input type="number" name="kembalian" class="form-control form-control-sm text-right" id="kembalian" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 p-2">
                        <button type="submit" name="simpan" id="simpan" class="btn btn-primary btn-sm btn-block"><i class="fa fa-save"></i> Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </section>


<?php 

require "../template/footer.php";

?>