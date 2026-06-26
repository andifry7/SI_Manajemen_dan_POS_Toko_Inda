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

    $sqlCustomer = "INSERT INTO tbl_customer VALUES (null,'$nama', '$telpon', '$ketr', '$alamat')";
    mysqli_query($koneksi, $sqlCustomer);

    return mysqli_affected_rows($koneksi);

}

function delete($id) {
    global $koneksi;

    $sqlDelete = "DELETE FROM tbl_customer WHERE id_customer = '$id'";

    mysqli_query($koneksi, $sqlDelete);
    return mysqli_affected_rows($koneksi);
}

function update($data) {
    global $koneksi;

    $id = mysqli_real_escape_string($koneksi, $data['id']);
    $nama = mysqli_real_escape_string($koneksi, $data['nama']);
    $telpon = mysqli_real_escape_string($koneksi, $data['telpon']);
    $ketr = mysqli_real_escape_string($koneksi, $data['ketr']);
    $alamat = mysqli_real_escape_string($koneksi, $data['alamat']);

    $sqlCustomer = "UPDATE tbl_customer SET
                    nama = '$nama',
                    telpon = '$telpon',
                    deskripsi = '$ketr',
                    alamat = '$alamat'
                    WHERE id_customer = '$id'";
    mysqli_query($koneksi, $sqlCustomer);

    return mysqli_affected_rows($koneksi);
}

?>