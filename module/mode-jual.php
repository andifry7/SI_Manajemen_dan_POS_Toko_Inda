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
    $qty = mysqli_real_escape_string($koneksi, $data['qty']);
    $harga = mysqli_real_escape_string($koneksi, $data['harga']);
    $jmlHarga = mysqli_real_escape_string($koneksi, $data['jmlHarga']);
    $stok = mysqli_real_escape_string($koneksi, $data['stok']);


    // cek barang sudah di-input atau belum
    $cekbrg = mysqli_query($koneksi, "SELECT * FROM tbl_jual_detail WHERE no_jual = '$no' AND barcode = '$kode'");
    if (mysqli_num_rows($cekbrg)) {
        echo "<script>
                alert('Barang sudah ada di daftar penjualan, anda harus menghapusnya terlebih dahulu jika ingin mengubah jumlah barang');
        </script>";
        return false;
    }

    // qty barang tidak boleh kosong
    if (empty($qty)) {
        echo "<script>
                alert('Jumlah barang tidak boleh kosong');
        </script>";
        return false;
    } elseif ($qty > $stok) {
        echo "<script>
                alert('Stok barang tidak mencukupi, silahkan kurangi jumlah barang yang akan dijual');
        </script>";
        return false;
    } else {
        $sqlJual = "INSERT INTO tbl_jual_detail VALUES (null, '$no', '$tgl','$kode', '$nama', '$kategori', '$qty', '$harga', '$jmlHarga')";
        mysqli_query($koneksi, $sqlJual);
    }
        
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