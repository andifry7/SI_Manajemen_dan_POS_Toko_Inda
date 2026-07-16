<?php 

function generateNo() {
    global $koneksi;

    $queryNo = mysqli_query($koneksi, "SELECT MAX(no_beli) AS maxno FROM tbl_beli_head");
    $row = mysqli_fetch_assoc($queryNo);
    $maxno = $row['maxno'];

    $noUrut = (int) substr($maxno, 2, 4);
    $noUrut++;
    $maxno = 'PB' . sprintf("%04s", $noUrut);

    return $maxno;
}

function totalBeli($noBeli) {
    global $koneksi;

    $totalBeli = mysqli_query($koneksi, "SELECT SUM(jml_harga) AS total FROM tbl_beli_detail WHERE no_beli = '$noBeli'");
    $data = mysqli_fetch_assoc($totalBeli);
    $total = $data['total'];

    return $total;
}

function insert($data) {
    global $koneksi;

    $no = mysqli_real_escape_string($koneksi, $data['nobeli']);
    $tgl = mysqli_real_escape_string($koneksi, $data['tglNota']);
    $kode = mysqli_real_escape_string($koneksi, $data['kodeBrg']);
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

    $cekbrg = mysqli_query($koneksi, "SELECT * FROM tbl_beli_detail WHERE no_beli = '$no' AND kode_brg = '$kode'");

    if ($cekbrg && mysqli_num_rows($cekbrg)) {
        $existing = mysqli_fetch_assoc($cekbrg);
        $newQty = $existing['qty'] + $qty;
        $newJmlHarga = $existing['jml_harga'] + $jmlHarga;

        $sqlUpdate = "UPDATE tbl_beli_detail SET qty = '$newQty', jml_harga = '$newJmlHarga' WHERE no_beli = '$no' AND kode_brg = '$kode'";
        mysqli_query($koneksi, $sqlUpdate);
    } else {
        $sqlbeli = "INSERT INTO tbl_beli_detail VALUES (null, '$no', '$tgl', '$kode', '$nama', '$kategori', '$qty', '$harga', '$jmlHarga')";
        mysqli_query($koneksi, $sqlbeli);
    }

    mysqli_query($koneksi, "UPDATE tbl_barang SET stock = stock + $qty WHERE id_barang = '$kode'");

    return mysqli_affected_rows($koneksi);
}

function delete($idbrg, $idbeli, $qty) {
    global $koneksi;

    $sqlDel = "DELETE FROM tbl_beli_detail WHERE kode_brg = '$idbrg' AND no_beli = '$idbeli'";
    mysqli_query($koneksi, $sqlDel);

    mysqli_query($koneksi, "UPDATE tbl_barang SET stock = stock - $qty WHERE id_barang = '$idbrg'");

    return mysqli_affected_rows($koneksi);

}

function simpan($data) {
    global $koneksi;

    $nobeli = mysqli_real_escape_string($koneksi, $data['nobeli']);
    $tgl = mysqli_real_escape_string($koneksi, $data['tglNota']);
    $total = mysqli_real_escape_string($koneksi, $data['total']);
    $supplier = mysqli_real_escape_string($koneksi, $data['supplier']);
    $keterangan = mysqli_real_escape_string($koneksi, $data['ketr']);

    $sqlbeli = "INSERT INTO tbl_beli_head VALUES ('$nobeli', '$tgl', '$supplier', '$total', '$keterangan')";
    mysqli_query($koneksi, $sqlbeli);

    return mysqli_affected_rows($koneksi);
}

?>