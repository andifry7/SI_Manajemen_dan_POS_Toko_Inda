<?php

function uploadimg($url = null, $name = null) {
    $namaFile = $_FILES['image']['name'];
    $ukuran = $_FILES['image']['size'];
    $tmp = $_FILES['image']['tmp_name'];

    // validasi file gambar yang boleh diupload
    $ekstensiGambarValid = ['jpg', 'jpeg', 'png', 'gif'];
    $ekstensiGambar = explode('.', $namaFile);
    $ekstensiGambar = strtolower(end($ekstensiGambar));

    if (!in_array($ekstensiGambar, $ekstensiGambarValid)) {
        if ($url != null) {
            echo '<script>
                alert("File yang anda upload bukan file gambar, Data gagal di-update!");
                document.location.href = "' . $url . '";
            </script>';
            die();
        } else {
            echo '<script>
                alert("File yang anda upload bukan file gambar, Data gagal ditambahkan!");
            </script>';
            return false;
        }
    }

    // validasi ukuran file gambar max 1MB
    if ($ukuran > 1000000) {
        if ($url != null) {
            echo '<script>
                alert("Ukuran gambar melebihi 1MB, Data gagal di-update!");
                document.location.href = "' . $url . '";
            </script>';
            die();
        } else {
            echo '<script>
                alert("File yang anda upload bukan file gambar, Data gagal ditambahkan!");
            </script>';
            return false;
        }
    }

    if ($name != null) {
        $namaFileBaru = $name . '.' . $ekstensiGambar;
    } else {
        $namaFileBaru = rand(10, 1000) . '.' . $namaFile;
    }

    move_uploaded_file($tmp, '../asset/image/' . $namaFileBaru);
    return $namaFileBaru;
}

function getData($sql) {
    global $koneksi;

    $result = mysqli_query($koneksi, $sql);
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    return $rows;
}

function userLogin() {
    $userActive = $_SESSION['ssUserPOS'];
    $dataUser = getData("SELECT * FROM tbl_user WHERE username = '$userActive'")[0];
    return $dataUser;
}

function userMenu() {
    $uri_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $uri_segments = explode('/', $uri_path);
    $menu = $uri_segments[2];
    return $menu;
}

function menuHome() {
    if (userMenu() == 'dashboard.php') {
        $result = 'active';
    } else {
        $result = null;
    }
    return $result;
}

function menuSetting() {
    if (userMenu() == 'user') {
        $result = 'menu-is-opening menu-open';
    } else {
        $result = null;
    }
    return $result;
}

function menuUser() {
    if (userMenu() == 'user') {
        $result = 'active';
    } else {
        $result = null;
    }
    return $result;
}

function menuSupplier() {
    if (userMenu() == 'supplier') {
        $result = 'active';
    } else {
        $result = null;
    }
    return $result;
}

function menuCustomer() {
    if (userMenu() == 'customer') {
        $result = 'active';
    } else {
        $result = null;
    }
    return $result;
}

function menuBarang() {
    if (userMenu() == 'barang') {
        $result = 'active';
    } else {
        $result = null;
    }
    return $result;
}

function menuMaster() {
    if (userMenu() == 'supplier' || userMenu() == 'customer' || userMenu() == 'barang') {
        $result = 'menu-is-opening menu-open';
    } else {
        $result = null;
    }
    return $result;
}

function in_date($tgl) {
    $tg = substr($tgl, 8, 2);
    $bln = substr($tgl, 5, 2);
    $thn = substr($tgl, 0, 4);
    return $tg . '-' . $bln . '-' . $thn;
}

function pembelian() {
    if (userMenu() == 'pembelian') {
        $result = 'active';
    } else {
        $result = null;
    }
    return $result;
}

function penjualan() {
    if (userMenu() == 'penjualan') {
        $result = 'active';
    } else {
        $result = null;
    }
    return $result;
}

function stokKeluar() {
    if (userMenu() == 'stok-keluar') {
        $result = 'active';
    } else {
        $result = null;
    }
    return $result;
}

function laporanBeli() {
    if (userMenu() == 'laporan-pembelian') {
        $result = 'active';
    } else {
        $result = null;
    }
    return $result;
}

function laporanJual() {
    if (userMenu() == 'laporan-penjualan') {
        $result = 'active';
    } else {
        $result = null;
    }
    return $result;
}

function laporanStock() {
    if (userMenu() == 'laporan-stok') {
        $result = 'active';
    } else {
        $result = null;
    }
    return $result;
}

function laporanStockKeluar() {
    if (userMenu() == 'laporan-stok-keluar') {
        $result = 'active';
    } else {
        $result = null;
    }
    return $result;
}

function omzet() {
    global $koneksi;
    
    $queryOmzet = mysqli_query($koneksi, "SELECT SUM(total) AS omzet FROM tbl_jual_head");
    $data = mysqli_fetch_assoc($queryOmzet);
    $omzet = number_format($data['omzet'], 0, ',', '.');

    return $omzet;
}

?>