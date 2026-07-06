<?php

session_start();

if (!isset($_SESSION['ssLoginPOS'])) {
    header("location: auth/login.php");
    exit();
}

require "config/config.php";
require "config/functions.php";

$title = "Dashboard - Toko Inda";
require "template/header.php";
require "template/navbar.php";
require "template/sidebar.php";

$users = getData("SELECT * FROM tbl_user");
$userNum = count($users);

$suppliers = getData("SELECT * FROM tbl_supplier");
$supplierNum = count($suppliers);

$customers = getData("SELECT * FROM tbl_customer");
$customerNum = count($customers);

$barang = getData("SELECT * FROM tbl_barang");
$brgNum = count($barang);

?>

  

<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Dashboard</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?= $main_url ?>dashboard.php">Home</a></li>
              <li class="breadcrumb-item active">Dashboard</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3><?= $userNum ?></h3>

                <p>Pengguna</p>
              </div>
              <div class="icon">
                <i class="ion ion-person-add"></i>
              </div>
              <a href="<?= $main_url ?>user" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3><?= $supplierNum ?></h3>

                <p>Suppliers</p>
              </div>
              <div class="icon">
                <i class="ion ion-android-bus"></i>
              </div>
              <a href="<?= $main_url ?>supplier" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <h3><?= $customerNum ?></h3>

                <p>Customers</p>
              </div>
              <div class="icon">
                <i class="ion ion-person-stalker"></i>
              </div>
              <a href="<?= $main_url ?>customer" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
              <div class="inner">
                <h3><?= $brgNum ?></h3>

                <p>Item Barang</p>
              </div>
              <div class="icon">
                <i class="ion ion-android-cart"></i>
              </div>
              <a href="<?= $main_url ?>barang" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
        </div>

        <div class="row">
          <div class="col-lg-6">
            <div class="card card-outline card-danger">
              <div class="card-header text-info">
                <h5 class="card-title">Info Stok Barang</h5>
                <h5><a href="laporan-stok" class="float-right" title="laporan stok"><i class="fas fa-arrow-right"></i></a></h5>
              </div>
              <table class="table">
                <tbody>
                  <?php
                  $stokMin = getData("SELECT * FROM tbl_barang WHERE stock < stock_minimal");
                  foreach ($stokMin as $min) { ?>
                    <tr>
                      <td><?= $min['nama_barang'] ?></td>
                      <td class="text-danger">Stok Kurang</td>
                    </tr>
                    <?php
                  }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
          <div class="col-lg-6">
            <div class="card card-outline card-success">
              <div class="card-header text-info">
                <h5 class="card-title">Omzet Penjualan</h5>
              </div>
              <div class="card-body text-primary">
                <h2><span class="h4">Rp </span><?= omzet() ?></h2>
              </div>
            </div>
          </div>
        </div>
    </div>
    </div>
    <!-- /.content -->
  

    <?php

    require "template/footer.php";

    ?>
