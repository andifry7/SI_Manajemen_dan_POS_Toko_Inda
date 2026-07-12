<?php

if (userLogin()['level'] == 2) {
    header("location:" . $main_url . "error-page.php");
    exit();
}

function insert($data) {
    global $koneksi;

    $nama = mysqli_real_escape_string($koneksi, $data['nama']);
    $now = date('Y-m-d H:i:s');

    $sqlCategory = "INSERT INTO tbl_kategori (nama, created, updated)
                    VALUES ('$nama', '$now', '$now')";

    if (!mysqli_query($koneksi, $sqlCategory)) {
        die(mysqli_error($koneksi));
    }

    return mysqli_affected_rows($koneksi);
}

function delete($id) {
    global $koneksi;

    $sqlDelete = "DELETE FROM tbl_kategori WHERE id_kategori = '$id'";

    mysqli_query($koneksi, $sqlDelete);
    return mysqli_affected_rows($koneksi);
}

function update($data) {
    global $koneksi;

    $id = mysqli_real_escape_string($koneksi, $data['id']);
    $nama = mysqli_real_escape_string($koneksi, $data['nama']);

    $sqlCategory = "UPDATE tbl_kategori SET
                    nama = '$nama'
                    WHERE id_kategori = '$id'";
    mysqli_query($koneksi, $sqlCategory);

    return mysqli_affected_rows($koneksi);
}

?>