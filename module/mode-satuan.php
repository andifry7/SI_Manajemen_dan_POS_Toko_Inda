<?php

if (userLogin()['level'] == 2) {
    header("location:" . $main_url . "error-page.php");
    exit();
}

function insert($data) {
    global $koneksi;

    $nama = mysqli_real_escape_string($koneksi, $data['nama']);
    $now = date('Y-m-d H:i:s');

    $cekNama = mysqli_query($koneksi, "SELECT * FROM tbl_satuan WHERE nama = '$nama'");
    if (mysqli_num_rows($cekNama)) {
        echo '<script>
            alert("Satuan dengan nama tersebut sudah terdaftar, gagal ditambahkan!");
        </script>';
        return false;
    }

    $sqlCategory = "INSERT INTO tbl_satuan (nama, created, updated)
                    VALUES ('$nama', '$now', '$now')";

    if (!mysqli_query($koneksi, $sqlCategory)) {
        die(mysqli_error($koneksi));
    }

    return mysqli_affected_rows($koneksi);
}

function delete($id) {
    global $koneksi;

    $sqlDelete = "DELETE FROM tbl_satuan WHERE id_satuan = '$id'";

    mysqli_query($koneksi, $sqlDelete);
    return mysqli_affected_rows($koneksi);
}

function update($data) {
    global $koneksi;

    $id = mysqli_real_escape_string($koneksi, $data['id']);
    $nama = mysqli_real_escape_string($koneksi, $data['nama']);

    // nama lama
    $queryNama = mysqli_query($koneksi, "SELECT * FROM tbl_satuan WHERE id_satuan = '$id'");
    $dataSatuan = mysqli_fetch_assoc($queryNama);
    $curNama = $dataSatuan['nama'];

    // jika nama diganti, cek apakah nama baru sudah dipakai satuan lain
    if ($nama !== $curNama) {
        $cekNama = mysqli_query($koneksi, "SELECT * FROM tbl_satuan WHERE nama = '$nama'");
        if (mysqli_num_rows($cekNama)) {
            echo '<script>
                alert("Satuan dengan nama tersebut sudah terdaftar, gagal diperbarui!");
            </script>';
            return false;
        }
    }

    $sqlCategory = "UPDATE tbl_satuan SET
                    nama = '$nama'
                    WHERE id_satuan = '$id'";
    mysqli_query($koneksi, $sqlCategory);

    return mysqli_affected_rows($koneksi);
}

?>