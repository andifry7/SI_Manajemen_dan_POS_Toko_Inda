<?php 

function generateNo() {
    global $koneksi;

    $queryNo = mysqli_query($koneksi, "SELECT MAX(no_jual) AS maxno FROM tbl_jual_head");
    $row = mysqli_fetch_assoc($queryNo);
    $maxno = $row['maxno'];

    $noUrut = (int) substr($maxno, 2, 4);
    $noUrut++;
    $maxno = 'PJ' . sprintf("%04s", $noUrut);

    return $maxno;
}

function insert($data) {
    global $koneksi;

    $no = mysqli_real_escape_string($koneksi, $data['nojual']);
    $tgl = mysqli_real_escape_string($koneksi, $data['tglNota']);
    $kode = mysqli_real_escape_string($koneksi, $data['barcode']);
    $nama = mysqli_real_escape_string($koneksi, $data['namaBrg']);
    $kategori = mysqli_real_escape_string($koneksi, $data['kategori']);
    $qty = (int) mysqli_real_escape_string($koneksi, $data['qty']);
    $harga = (float) mysqli_real_escape_string($koneksi, $data['harga']);
    $jmlHarga = (float) mysqli_real_escape_string($koneksi, $data['jmlHarga']);

    if (empty($qty) || $qty <= 0) {
        echo "<script>
                alert('Jumlah barang tidak boleh kosong');
        </script>";
        return false;
    }

    // ambil stok terkini langsung dari tbl_barang (bukan dari field form yang bisa basi)
    $cekStok = mysqli_query($koneksi, "SELECT stock FROM tbl_barang WHERE barcode = '$kode'");
    $dataStok = mysqli_fetch_assoc($cekStok);
    $stokSaatIni = $dataStok ? (int) $dataStok['stock'] : 0;

    if ($qty > $stokSaatIni) {
        echo "<script>
                alert('Stok barang tidak mencukupi, silahkan kurangi jumlah barang yang akan dijual');
        </script>";
        return false;
    }

    // cek barang sudah di-input atau belum
    $cekbrg = mysqli_query($koneksi, "SELECT * FROM tbl_jual_detail WHERE no_jual = '$no' AND barcode = '$kode'");

    if ($cekbrg && mysqli_num_rows($cekbrg)) {
        // barang sudah ada di daftar -> tambahkan qty & total harga ke baris yang sudah ada
        $existing = mysqli_fetch_assoc($cekbrg);
        $newQty = $existing['qty'] + $qty;
        $newJmlHarga = $existing['jml_harga'] + $jmlHarga;

        $sqlUpdate = "UPDATE tbl_jual_detail SET qty = '$newQty', jml_harga = '$newJmlHarga' WHERE no_jual = '$no' AND barcode = '$kode'";
        mysqli_query($koneksi, $sqlUpdate);
    } else {
        // barang belum ada -> insert baris baru seperti biasa
        $sqlJual = "INSERT INTO tbl_jual_detail VALUES (null, '$no', '$tgl', '$kode', '$nama', '$kategori', '$qty', '$harga', '$jmlHarga')";
        mysqli_query($koneksi, $sqlJual);
    }

    // stock dikurangi sebesar qty yang baru ditambahkan saja
    mysqli_query($koneksi, "UPDATE tbl_barang SET stock = stock - $qty WHERE barcode = '$kode'");

    return mysqli_affected_rows($koneksi);
}

function totalJual($noJual) {
    global $koneksi;

    $totalJual = mysqli_query($koneksi, "SELECT SUM(jml_harga) AS total FROM tbl_jual_detail WHERE no_jual = '$noJual'");
    $data = mysqli_fetch_assoc($totalJual);
    $total = $data['total'];

    return $total;
}

function delete($barcode, $noJual, $qty) {
    global $koneksi;

    $sqlDel = "DELETE FROM tbl_jual_detail WHERE barcode = '$barcode' AND no_jual = '$noJual'";
    mysqli_query($koneksi, $sqlDel);

    mysqli_query($koneksi, "UPDATE tbl_barang SET stock = stock + $qty WHERE barcode = '$barcode'");

    return mysqli_affected_rows($koneksi);

}

function simpan($data) {
    global $koneksi;

    $nojual = mysqli_real_escape_string($koneksi, $data['nojual']);
    $tgl = mysqli_real_escape_string($koneksi, $data['tglNota']);
    $total = mysqli_real_escape_string($koneksi, $data['total']);
    $customer = mysqli_real_escape_string($koneksi, $data['customer']);
    $keterangan = mysqli_real_escape_string($koneksi, $data['ketr']);
    $bayar = mysqli_real_escape_string($koneksi, $data['bayar']);
    $kembalian = mysqli_real_escape_string($koneksi, $data['kembalian']);

    $sqljual = "INSERT INTO tbl_jual_head VALUES ('$nojual', '$tgl', '$customer', '$total', '$keterangan', '$bayar', '$kembalian')";
    mysqli_query($koneksi, $sqljual);

    return mysqli_affected_rows($koneksi);
}

?>