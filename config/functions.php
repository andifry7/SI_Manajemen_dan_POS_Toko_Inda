<?php

function uploadimg() {
    $namaFile = $_FILES['image']['name'];
    $ukuran = $_FILES['image']['size'];
    $tmp = $_FILES['image']['tmp_name'];

    // validasi file gambar yang boleh diupload
    $ekstensiGambarValid = ['jpg', 'jpeg', 'png', 'gif'];
    $ekstensiGambar = explode('.', $namaFile);
    $ekstensiGambar = strtolower(end($ekstensiGambar));

    if (!in_array($ekstensiGambar, $ekstensiGambarValid)) {
        echo '<script>
            alert("File yang anda upload bukan file gambar, Data gagal ditambahkan!");
            </script>';
        return false;
    }

    // validasi ukuran file gambar max 1MB
    if ($ukuran > 1000000) {
        echo '<script>
            alert("Ukuran gambar tidak boleh melebihi 1MB, Data gagal ditambahkan!");
            </script>';
        return false;
    }

    $namaFileBaru = rand(10, 1000) . '-' . $namaFile;

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

?>