<?php

if (userLogin()['level'] == 2) {
    header("location:" . $main_url . "error-page.php");
    exit();
}

function insert($data) {
    global $koneksi;

    $nama = mysqli_real_escape_string($koneksi, $data['nama']);
    $telpon = mysqli_real_escape_string($koneksi, $data['telpon']);
    $ketr = mysqli_real_escape_string($koneksi, $data['ketr']);
    $alamat = mysqli_real_escape_string($koneksi, $data['alamat']);

    $sqlSupplier = "INSERT INTO tbl_supplier VALUES (null,'$nama', '$telpon', '$ketr', '$alamat')";
    mysqli_query($koneksi, $sqlSupplier);

    return mysqli_affected_rows($koneksi);

}

?>