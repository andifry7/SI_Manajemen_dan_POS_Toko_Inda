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

// ===== Ringkasan Omzet (hari ini, bulan ini, total) =====
$omzetHariIniData = getData("SELECT SUM(total) AS total FROM tbl_jual_head WHERE tgl_jual = CURDATE()");
$omzetHariIni = $omzetHariIniData[0]['total'] ?? 0;

$omzetBulanIniData = getData("SELECT SUM(total) AS total FROM tbl_jual_head WHERE MONTH(tgl_jual) = MONTH(CURDATE()) AND YEAR(tgl_jual) = YEAR(CURDATE())");
$omzetBulanIni = $omzetBulanIniData[0]['total'] ?? 0;

$omzetTotalData = getData("SELECT SUM(total) AS total FROM tbl_jual_head");
$omzetTotal = $omzetTotalData[0]['total'] ?? 0;

// ===== Ringkasan Laba (hari ini, bulan ini, total) =====
// Laba = (harga_jual transaksi - harga_beli master barang) x qty, join via barcode
$labaHariIniData = getData("
    SELECT SUM((jd.harga_jual - b.harga_beli) * jd.qty) AS laba
    FROM tbl_jual_detail jd
    JOIN tbl_barang b ON jd.barcode = b.barcode
    WHERE jd.tgl_jual = CURDATE()
");
$labaHariIni = $labaHariIniData[0]['laba'] ?? 0;

$labaBulanIniData = getData("
    SELECT SUM((jd.harga_jual - b.harga_beli) * jd.qty) AS laba
    FROM tbl_jual_detail jd
    JOIN tbl_barang b ON jd.barcode = b.barcode
    WHERE MONTH(jd.tgl_jual) = MONTH(CURDATE()) AND YEAR(jd.tgl_jual) = YEAR(CURDATE())
");
$labaBulanIni = $labaBulanIniData[0]['laba'] ?? 0;

$labaTotalData = getData("
    SELECT SUM((jd.harga_jual - b.harga_beli) * jd.qty) AS laba
    FROM tbl_jual_detail jd
    JOIN tbl_barang b ON jd.barcode = b.barcode
");
$labaTotal = $labaTotalData[0]['laba'] ?? 0;

// ===== Grafik Penjualan & Laba 7 Hari Terakhir =====
$grafikOmzetRaw = getData("SELECT tgl_jual, SUM(total) AS total FROM tbl_jual_head WHERE tgl_jual >= DATE_SUB(CURDATE(), INTERVAL 6 DAY) GROUP BY tgl_jual ORDER BY tgl_jual ASC");

$grafikOmzetMap = [];
foreach ($grafikOmzetRaw as $row) {
    $grafikOmzetMap[$row['tgl_jual']] = (int) $row['total'];
}

$grafikLabaRaw = getData("
    SELECT jd.tgl_jual, SUM((jd.harga_jual - b.harga_beli) * jd.qty) AS laba
    FROM tbl_jual_detail jd
    JOIN tbl_barang b ON jd.barcode = b.barcode
    WHERE jd.tgl_jual >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
    GROUP BY jd.tgl_jual
    ORDER BY jd.tgl_jual ASC
");

$grafikLabaMap = [];
foreach ($grafikLabaRaw as $row) {
    $grafikLabaMap[$row['tgl_jual']] = (int) $row['laba'];
}

$grafikLabel     = [];
$grafikOmzetData = [];
$grafikLabaData  = [];
for ($i = 6; $i >= 0; $i--) {
    $tgl = date('Y-m-d', strtotime("-$i day"));
    $grafikLabel[]     = date('d M', strtotime($tgl));
    $grafikOmzetData[] = $grafikOmzetMap[$tgl] ?? 0;
    $grafikLabaData[]  = $grafikLabaMap[$tgl] ?? 0;
}

// ===== Produk Terlaris (Top 5) =====
$produkTerlaris = getData("SELECT nama_brg, SUM(qty) AS total_qty FROM tbl_jual_detail GROUP BY nama_brg ORDER BY total_qty DESC LIMIT 5");

// ===== Transaksi Terbaru (5 terakhir) =====
$transaksiTerbaru = getData("SELECT * FROM tbl_jual_head ORDER BY tgl_jual DESC, no_jual DESC LIMIT 5");

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
        <!-- /.row (stat boxes) -->

        <!-- Ringkasan Omzet -->
        <div class="row">
          <div class="col-lg-4 col-md-4 col-6">
            <div class="small-box bg-gradient-info">
              <div class="inner">
                <h3 style="font-size:1.6rem;">Rp <?= number_format($omzetHariIni, 0, ',', '.') ?></h3>
                <p>Omzet Hari Ini</p>
              </div>
              <div class="icon">
                <i class="ion ion-cash"></i>
              </div>
            </div>
          </div>
          <div class="col-lg-4 col-md-4 col-6">
            <div class="small-box bg-gradient-success">
              <div class="inner">
                <h3 style="font-size:1.6rem;">Rp <?= number_format($omzetBulanIni, 0, ',', '.') ?></h3>
                <p>Omzet Bulan Ini</p>
              </div>
              <div class="icon">
                <i class="ion ion-calendar"></i>
              </div>
            </div>
          </div>
          <div class="col-lg-4 col-md-4 col-12">
            <div class="small-box bg-gradient-primary">
              <div class="inner">
                <h3 style="font-size:1.6rem;">Rp <?= number_format($omzetTotal, 0, ',', '.') ?></h3>
                <p>Total Omzet</p>
              </div>
              <div class="icon">
                <i class="ion ion-stats-bars"></i>
              </div>
            </div>
          </div>
        </div>
        <!-- /.row (ringkasan omzet) -->

        <!-- Ringkasan Laba -->
        <div class="row">
          <div class="col-lg-4 col-md-4 col-6">
            <div class="small-box bg-gradient-secondary">
              <div class="inner">
                <h3 style="font-size:1.6rem;">Rp <?= number_format($labaHariIni, 0, ',', '.') ?></h3>
                <p>Laba Hari Ini</p>
              </div>
              <div class="icon">
                <i class="ion ion-social-usd"></i>
              </div>
            </div>
          </div>
          <div class="col-lg-4 col-md-4 col-6">
            <div class="small-box bg-gradient-purple" style="background:#6f42c1;color:#fff;">
              <div class="inner">
                <h3 style="font-size:1.6rem;">Rp <?= number_format($labaBulanIni, 0, ',', '.') ?></h3>
                <p>Laba Bulan Ini</p>
              </div>
              <div class="icon">
                <i class="ion ion-ios-analytics"></i>
              </div>
            </div>
          </div>
          <div class="col-lg-4 col-md-4 col-12">
            <div class="small-box bg-gradient-dark">
              <div class="inner">
                <h3 style="font-size:1.6rem;">Rp <?= number_format($labaTotal, 0, ',', '.') ?></h3>
                <p>Total Laba</p>
              </div>
              <div class="icon">
                <i class="ion ion-trophy"></i>
              </div>
            </div>
          </div>
        </div>
        <!-- /.row (ringkasan laba) -->

        <!-- Grafik penjualan & produk terlaris -->
        <div class="row">
          <div class="col-lg-8">
            <div class="card card-outline card-info">
              <div class="card-header">
                <h5 class="card-title">Grafik Omzet &amp; Laba 7 Hari Terakhir</h5>
              </div>
              <div class="card-body">
                <canvas id="chartPenjualan" style="min-height:250px;height:250px;max-height:280px;"></canvas>
              </div>
            </div>
          </div>
          <div class="col-lg-4">
            <div class="card card-outline card-warning">
              <div class="card-header">
                <h5 class="card-title">Produk Terlaris</h5>
              </div>
              <div class="card-body p-0" style="max-height:320px;overflow-y:auto;">
                <table class="table table-striped mb-0">
                  <thead>
                    <tr>
                      <th>Produk</th>
                      <th class="text-right">Terjual</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if (count($produkTerlaris) > 0) : ?>
                      <?php foreach ($produkTerlaris as $p) : ?>
                        <tr>
                          <td><?= htmlspecialchars($p['nama_brg']) ?></td>
                          <td class="text-right"><span class="badge badge-warning"><?= (int) $p['total_qty'] ?></span></td>
                        </tr>
                      <?php endforeach; ?>
                    <?php else : ?>
                      <tr>
                        <td colspan="2" class="text-center text-muted py-3">Belum ada data penjualan</td>
                      </tr>
                    <?php endif; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
        <!-- /.row (grafik & produk terlaris) -->

        <!-- Stok minim & transaksi terbaru -->
        <div class="row">
          <div class="col-lg-6">
            <div class="card card-outline card-danger">
              <div class="card-header">
                <h5 class="card-title">Info Stok Barang</h5>
                <div class="card-tools">
                  <a href="laporan-stok" title="laporan stok"><i class="fas fa-arrow-right"></i></a>
                </div>
              </div>
              <div class="card-body p-0" style="max-height:320px;overflow-y:auto;">
                <table class="table table-striped mb-0">
                  <thead>
                    <tr>
                      <th>Nama Barang</th>
                      <th class="text-center">Stok</th>
                      <th class="text-center">Min. Stok</th>
                      <th class="text-center">Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $stokMin = getData("SELECT * FROM tbl_barang WHERE stock < stock_minimal");
                    if (count($stokMin) > 0) :
                        foreach ($stokMin as $min) : ?>
                          <tr>
                            <td><?= htmlspecialchars($min['nama_barang']) ?></td>
                            <td class="text-center"><?= (int) $min['stock'] ?></td>
                            <td class="text-center"><?= (int) $min['stock_minimal'] ?></td>
                            <td class="text-center"><span class="badge badge-danger">Stok Kurang</span></td>
                          </tr>
                        <?php endforeach;
                    else : ?>
                      <tr>
                        <td colspan="4" class="text-center text-muted py-3">Semua stok aman</td>
                      </tr>
                    <?php endif; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <div class="col-lg-6">
            <div class="card card-outline card-success">
              <div class="card-header">
                <h5 class="card-title">Transaksi Terbaru</h5>
              </div>
              <div class="card-body p-0" style="max-height:320px;overflow-y:auto;">
                <table class="table table-striped mb-0">
                  <thead>
                    <tr>
                      <th>No. Jual</th>
                      <th>Tanggal</th>
                      <th>Customer</th>
                      <th class="text-right">Total</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if (count($transaksiTerbaru) > 0) : ?>
                      <?php foreach ($transaksiTerbaru as $t) : ?>
                        <tr>
                          <td><?= htmlspecialchars($t['no_jual']) ?></td>
                          <td><?= date('d/m/Y', strtotime($t['tgl_jual'])) ?></td>
                          <td><?= htmlspecialchars($t['customer']) ?></td>
                          <td class="text-right">Rp <?= number_format($t['total'], 0, ',', '.') ?></td>
                        </tr>
                      <?php endforeach; ?>
                    <?php else : ?>
                      <tr>
                        <td colspan="4" class="text-center text-muted py-3">Belum ada transaksi</td>
                      </tr>
                    <?php endif; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
        <!-- /.row (stok minim & transaksi terbaru) -->

      </div>
    </div>
    <!-- /.content -->

  <!-- Chart.js (dipakai untuk grafik omzet & laba 7 hari terakhir) -->
  <script src="<?= $main_url ?>asset/AdminLTE-3.2.0/plugins/chart.js/Chart.min.js"></script>
  <script>
    (function () {
      var ctx = document.getElementById('chartPenjualan');
      if (!ctx) return;

      new Chart(ctx.getContext('2d'), {
        type: 'line',
        data: {
          labels: <?= json_encode($grafikLabel) ?>,
          datasets: [
            {
              label: 'Omzet',
              data: <?= json_encode($grafikOmzetData) ?>,
              fill: true,
              borderColor: '#17a2b8',
              backgroundColor: 'rgba(23,162,184,0.15)',
              tension: 0.3,
              pointBackgroundColor: '#17a2b8',
              pointRadius: 3
            },
            {
              label: 'Laba',
              data: <?= json_encode($grafikLabaData) ?>,
              fill: true,
              borderColor: '#28a745',
              backgroundColor: 'rgba(40,167,69,0.15)',
              tension: 0.3,
              pointBackgroundColor: '#28a745',
              pointRadius: 3
            }
          ]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: { display: true, position: 'top' },
            tooltip: {
              callbacks: {
                label: function (context) {
                  return context.dataset.label + ': Rp ' + context.parsed.y.toLocaleString('id-ID');
                }
              }
            }
          },
          scales: {
            y: {
              beginAtZero: true,
              ticks: {
                callback: function (value) {
                  return 'Rp ' + value.toLocaleString('id-ID');
                }
              }
            }
          }
        }
      });
    })();
  </script>

<?php

require "template/footer.php";

?>