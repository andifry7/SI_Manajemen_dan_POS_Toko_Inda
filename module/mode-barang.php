<?php

if (userLogin()['level'] == 2) {
    header("location:" . $main_url . "error-page.php");
    exit();
}

function generateId() {
    global $koneksi;

    $queryId = mysqli_query($koneksi, "SELECT MAX(id_barang) AS maxid FROM tbl_barang");
    $data = mysqli_fetch_array($queryId);
    $maxid = $data['maxid'];

    $noUrut = (int) substr($maxid, 4, 3);
    $noUrut++;
    $maxid = "BRG-" . sprintf("%03s", $noUrut);

    return $maxid;
}

function insert($data) {
    global $koneksi;

    $id = mysqli_real_escape_string($koneksi, $data['kode']);
    $barcode = mysqli_real_escape_string($koneksi, $data['barcode']);
    $name = mysqli_real_escape_string($koneksi, $data['name']);
    $kategori = mysqli_real_escape_string($koneksi, $data['kategori']);
    $harga_beli = mysqli_real_escape_string($koneksi, $data['harga_beli']);
    $harga_jual = mysqli_real_escape_string($koneksi, $data['harga_jual']);
    $satuan = mysqli_real_escape_string($koneksi, $data['satuan']);
    $stockmin = mysqli_real_escape_string($koneksi, $data['stock_minimal']);
    $gambar = mysqli_real_escape_string($koneksi, $_FILES['image']['name']);

    $cekBarcode = mysqli_query($koneksi, "SELECT * FROM tbl_barang WHERE barcode = '$barcode'");
    if (mysqli_num_rows($cekBarcode)) {
        echo '<script>
            alert("Barcode sudah digunakan, barang gagal ditambahkan!");
        </script>';
        return false;
    }

    //upload gambar barang
    if ($gambar != null) {
        $gambar = uploadimg(null, $id);
    } else {
        $gambar = 'default-brg.jpg';
    }

    // Gambar tidak sesuai validasi
    if ($gambar == '') {
        return false;
    }

    $sqlBrg = "INSERT INTO tbl_barang VALUES ('$id', '$barcode', '$name', '$kategori', '$harga_beli', '$harga_jual', 0, '$satuan', '$stockmin', '$gambar')";
    mysqli_query($koneksi, $sqlBrg);

    return mysqli_affected_rows($koneksi);
}

function delete($id, $gbr) {
    global $koneksi;

    $sqlDel = "DELETE FROM tbl_barang WHERE id_barang = '$id'";
    mysqli_query($koneksi, $sqlDel);
    if ($gbr != 'default-brg.jpg') {
        unlink('../asset/image/' . $gbr);
    }

    return mysqli_affected_rows($koneksi);
}

function update($data) {
    global $koneksi;

    $id = mysqli_real_escape_string($koneksi, $data['kode']);
    $barcode = mysqli_real_escape_string($koneksi, $data['barcode']);
    $name = mysqli_real_escape_string($koneksi, $data['name']);
    $kategori = mysqli_real_escape_string($koneksi, $data['kategori']);
    $harga_beli = mysqli_real_escape_string($koneksi, $data['harga_beli']);
    $harga_jual = mysqli_real_escape_string($koneksi, $data['harga_jual']);
    $satuan = mysqli_real_escape_string($koneksi, $data['satuan']);
    $stockmin = mysqli_real_escape_string($koneksi, $data['stock_minimal']);
    $gbrLama = mysqli_real_escape_string($koneksi, $data['oldImg']);
    $gambar = mysqli_real_escape_string($koneksi, $_FILES['image']['name']);

    // barcode lama
    $queryBarcode = mysqli_query($koneksi, "SELECT * FROM tbl_barang WHERE id_barang'$id'");
    $dataBrg = mysqli_fetch_assoc($queryBarcode);
    $curBarcode = $dataBrg['barcode'];

    // barcode baru
    $cekBarcode = mysqli_query($koneksi, "SELECT * FROM tbl_barang WHERE barcode = '$barcode'");

    // jika barcode diganti
    if ($barcode !== $curBarcode) {
        // jika barcode sudah ada
        if (mysqli_num_rows($cekBarcode)) {
        echo '<script>
            alert("Barcode sudah digunakan, barang gagal diperbarui!");
        </script>';
        return false;
        }
    }

    // cek gambar
    if ($gambar != null) {
        $url = 'index.php';
        if ($gbrLama == 'default-brg.jpg') {
            $nmgbr = $id;
        } else {
            $nmgbr = $id . '-' . rand(10, 1000);
        }
        $imgBrg = uploadimg($url, $nmgbr);
        if ($gbrLama != 'default-brg.jpg') {
            unlink('../asset/image/' . $gbrLama);
        }
    } else {
        $imgBrg = $gbrLama;
    }

    mysqli_query($koneksi, "UPDATE tbl_barang SET
                            barcode = '$barcode',
                            nama_barang = '$name',
                            kategori = '$kategori',
                            harga_beli = '$harga_beli',
                            harga_jual = '$harga_jual',
                            satuan = '$satuan',
                            stock_minimal = '$stockmin',
                            gambar = '$imgBrg'
                            WHERE id_barang = '$id'");

    return mysqli_affected_rows($koneksi);
}